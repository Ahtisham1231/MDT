<?php
//	stock table
if (isset($_POST['getStock'])) {

	$params = $columns = $totalRecords = $data = [];
	$params = $_POST;
	$active = isset($params['active']) ? intval($params['active']) : 1; // Default to active products if not provided
	$columns = [
		0 => 'id',
		1 => 'name',
		2 => 'quantity',
		3 => 'price',
		4 => 'total',
	];

	$where_condition = '';

	$sql = $sqlRec = "
		SELECT
			s.product_id AS id,
			p.name AS name,
			c.quantity AS cquantity,
			e.quantity AS equantity,
			t.quantity AS tquantity,
			k.quantity AS kquantity,
			c.quantity_reserved AS creserved,
			e.quantity_reserved AS ereserved,
			t.quantity_reserved AS treserved,
			k.quantity_reserved AS kreserved,
			s.supplier_price AS price,
			c.quantity * s.supplier_price AS ctotal,
			t.quantity * s.supplier_price AS ttotal,
			k.quantity * s.supplier_price AS ktotal,
			e.quantity * s.supplier_price AS etotal
		FROM
			stock AS s
		JOIN
			products AS p
		ON
			s.product_id = p.id
		JOIN
			calgary AS c
		ON
			s.product_id = c.product_id
		JOIN
			edmonton AS e
		ON
			s.product_id = e.product_id
		JOIN
		   toronto AS t
		ON
			s.product_id = t.product_id
		JOIN
		   kelowna AS k
		ON
			s.product_id = k.product_id		
		WHERE	
			p.active = $active		
		";

	if (!empty($params['search']['value'])) {
		$where_condition .= " WHERE name LIKE '%" . $params['search']['value'] . "%' ";
	}

	if (isset($where_condition) && $where_condition != '') {
		$sqlRec .= $where_condition;
	}

	$sqlRec .=  " ORDER BY " . $columns[$params['order'][0]['column']] . "   " . $params['order'][0]['dir'] . "  LIMIT " . $params['start'] . " ," . $params['length'] . " ";

	$totalRecords = $db->getColumn("SELECT COUNT(id) FROM products  WHERE active = $active");
	$queryRecords = $db->getRows($sqlRec);

	$data = [];

	foreach ($queryRecords as $row) {

		$formattedPrice 				= '$' . number_format($row->price, 2);
		$formattedProductTotalCalgary 	= '$' . number_format($row->ctotal, 2);
		$formattedProductTotalEdmonton 	= '$' . number_format($row->etotal, 2);
		$formattedProductTotalToronto 	= '$' . number_format($row->ttotal, 2);
		$formattedProductTotalKelowna 	= '$' . number_format($row->ktotal, 2);

		$data[] = [
			$row->name,
			$row->cquantity,
			$row->equantity,
			$row->tquantity,
			$row->kquantity,
			$row->creserved,
			$row->ereserved,
			$row->treserved,
			$row->kreserved,
			$formattedPrice,
			$formattedProductTotalCalgary,
			$formattedProductTotalEdmonton,
			$formattedProductTotalToronto,
			$formattedProductTotalKelowna,
		];
	}

	$sqlTotalCalgary 	= "
		SELECT
			SUM(c.quantity * s.supplier_price)
		FROM
			stock AS s
		JOIN
			calgary AS c
		ON
			s.product_id = c.id
		JOIN
			products AS p
		ON
			c.product_id = p.id	
			
		WHERE p.active = $active			
		";

	$sqlTotalEdmonton 	= "
		SELECT
			SUM(e.quantity * s.supplier_price)
		FROM
			stock AS s
		JOIN
			edmonton AS e
		ON
			s.product_id = e.id
		JOIN
			products AS p
		ON
			e.product_id = p.id	
			
		WHERE p.active = $active		
		";
	$sqlTotalToronto	= "
		SELECT
			SUM(t.quantity * s.supplier_price)
		FROM
			stock AS s
		JOIN
		toronto AS t
		ON
			s.product_id = t.id
		JOIN
			products AS p
		ON
			t.product_id = p.id	
			
		WHERE p.active = $active		
		";
	$sqlTotalKelowna	= "
		SELECT
			SUM(k.quantity * s.supplier_price)
		FROM
			stock AS s
		JOIN
		kelowna AS k
		ON
			s.product_id = k.id
		JOIN
			products AS p
		ON
			k.product_id = p.id	
			
		WHERE p.active = $active		
		";


	$calgaryTotal		= '$' . number_format($db->getColumn($sqlTotalCalgary), 2);
	$edmontonTotal		= '$' . number_format($db->getColumn($sqlTotalEdmonton), 2);
	$torontoTotal		= '$' . number_format($db->getColumn($sqlTotalToronto), 2);
	$kelownaTotal		= '$' . number_format($db->getColumn($sqlTotalKelowna), 2);
	$total 				= '$' . number_format($db->getColumn("SELECT SUM(quantity * supplier_price) FROM stock JOIN products AS p ON stock.product_id = p.id WHERE p.active = $active"), 2);

	$json_data = array(
		"draw"            	=> intval($params['draw']),
		"recordsTotal" 		=> intval($totalRecords),
		"recordsFiltered" 	=> intval($totalRecords),
		"data"            	=> $data,
		"total"				=> $total,
		"edmontonTotal"		=> $edmontonTotal,
		"calgaryTotal"		=> $calgaryTotal,
		"torontoTotal"		=> $torontoTotal,
		"kelownaTotal"		=> $kelownaTotal,
	);


	die(json_encode($json_data, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}

//	calgary table
if (isset($_POST['getCalgaryStock'])) {

	$params = $columns = $totalRecords = $data = [];
	$params = $_POST;

	$active = isset($params['active']) ? intval($params['active']) : 1; // Default to active products if not provided
	// print_r($active);
	// die;
	$columns = [
		0 => 'id',
		1 => 'name',
		2 => 'quantity',
		3 => 'price',
		4 => 'total',
	];

	$where_condition = '';

	$sql = "
		SELECT
			c.product_id AS id,
			p.name AS name,
			c.quantity AS quantity,
			c.quantity_reserved AS reserved,
			s.supplier_price AS price,
			c.quantity * s.supplier_price AS total
		FROM
			calgary AS c
		JOIN
			products AS p
		ON
			c.product_id = p.id
		JOIN
			stock as s
		ON
			c.product_id = s.product_id
			WHERE 
            p.active = $active";


	if (!empty($params['search']['value'])) {
		$where_condition .= " AND p.name LIKE '%" . $params['search']['value'] . "%' ";
	}

	if (isset($where_condition) && $where_condition != '') {
		$sql .= $where_condition;
	}

	$sql .= " ORDER BY " . $columns[$params['order'][0]['column']] . " " . $params['order'][0]['dir'] . " LIMIT " . $params['start'] . ", " . $params['length'];

	$totalRecords = $db->getColumn("SELECT COUNT(id) FROM products WHERE products.active = $active ");
	$queryRecords = $db->getRows($sql);

	$data = [];

	foreach ($queryRecords as $row) {

		$formattedPrice 		= '$' . number_format($row->price, 2);
		$formattedProductTotal 	= '$' . number_format($row->total, 2);

		$data[] = [
			$row->name,
			$row->quantity,
			$row->reserved,
			$formattedPrice,
			$formattedProductTotal,
		];
	}

	$sqlTotal 	= "
		SELECT
			SUM(c.quantity * s.supplier_price)
		FROM
			stock AS s
		JOIN
			calgary as c
		ON
			s.product_id = c.product_id
		JOIN
			products AS p
		ON
			c.product_id = p.id	
			
		WHERE p.active = $active 
		";
	$total 		= '$' . number_format($db->getColumn($sqlTotal), 2);

	$json_data = array(
		"draw"            	=> intval($params['draw']),
		"recordsTotal" 		=> intval($totalRecords),
		"recordsFiltered" 	=> intval($totalRecords),
		"data"            	=> $data,
		"total"				=> $total
	);

	die(json_encode($json_data, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}

//	edmonton table
if (isset($_POST['getEdmontonStock'])) {

	$params = $columns = $totalRecords = $data = [];
	$params = $_POST;

	$active = isset($params['active']) ? intval($params['active']) : 1;

	$columns = [
		0 => 'id',
		1 => 'name',
		2 => 'quantity',
		3 => 'price',
		4 => 'total',
	];

	$where_condition = '';

	$sql = "
		SELECT
			e.product_id AS id,
			p.name AS name,
			e.quantity AS quantity,
			e.quantity_reserved AS reserved,
			s.supplier_price AS price,
			e.quantity * s.supplier_price AS total
		FROM
			edmonton AS e
		JOIN
			products AS p
		ON
			e.product_id = p.id
		JOIN
			stock as s
		ON
			e.product_id = s.product_id
		WHERE	
			p.active = $active
		";

	if (!empty($params['search']['value'])) {
		$where_condition .= " AND p.name LIKE '%" . $params['search']['value'] . "%' ";
	}

	if (isset($where_condition) && $where_condition != '') {
		$sql .= $where_condition;
	}

	$sql .= " ORDER BY " . $columns[$params['order'][0]['column']] . " " . $params['order'][0]['dir'] . " LIMIT " . $params['start'] . " ," . $params['length'];

	$totalRecords = $db->getColumn("SELECT COUNT(id) FROM products WHERE products.active = $active");
	$queryRecords = $db->getRows($sql);

	$data = [];

	foreach ($queryRecords as $row) {

		$formattedPrice 		= '$' . number_format($row->price, 2);
		$formattedProductTotal 	= '$' . number_format($row->total, 2);

		$data[] = [
			$row->name,
			$row->quantity,
			$row->reserved,
			$formattedPrice,
			$formattedProductTotal,
		];
	}

	$sqlTotal 	= "
		SELECT
			SUM(e.quantity * s.supplier_price)
		FROM
			stock AS s
		JOIN
			edmonton as e
		ON
			s.product_id = e.product_id
		JOIN
			products AS p
		ON
			e.product_id = p.id	
			
		WHERE p.active = $active	
		";

	$total 		= '$' . number_format($db->getColumn($sqlTotal), 2);

	$json_data = array(
		"draw"            	=> intval($params['draw']),
		"recordsTotal" 		=> intval($totalRecords),
		"recordsFiltered" 	=> intval($totalRecords),
		"data"            	=> $data,
		"total"				=> $total
	);

	die(json_encode($json_data, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}
if (isset($_POST['getTorontoStock'])) {

	$params = $columns = $totalRecords = $data = [];
	$params = $_POST;
	$active = isset($params['active']) ? intval($params['active']) : 1;

	$columns = [
		0 => 'id',
		1 => 'name',
		2 => 'quantity',
		3 => 'price',
		4 => 'total',
	];

	$where_condition = '';

	$sql = "
        SELECT
            t.product_id AS id,
            p.name AS name,
            t.quantity AS quantity,
            t.quantity_reserved AS reserved,
            s.supplier_price AS price,
            t.quantity * s.supplier_price AS total
        FROM
            toronto AS t
        JOIN
            products AS p
        ON
            t.product_id = p.id
        JOIN
            stock as s
        ON
            t.product_id = s.product_id
		WHERE	
			p.active = $active
        ";

	if (!empty($params['search']['value'])) {
		$where_condition .= " AND p.name LIKE '%" . $params['search']['value'] . "%' ";
	}

	if (isset($where_condition) && $where_condition != '') {
		$sql .= $where_condition;
	}

	$sql .= " ORDER BY " . $columns[$params['order'][0]['column']] . " " . $params['order'][0]['dir'] . " LIMIT " . $params['start'] . " ," . $params['length'];

	$totalRecords = $db->getColumn("SELECT COUNT(id) FROM products WHERE products.active = $active");
	$queryRecords = $db->getRows($sql);

	$data = [];

	foreach ($queryRecords as $row) {

		$formattedPrice         = '$' . number_format($row->price, 2);
		$formattedProductTotal  = '$' . number_format($row->total, 2);

		$data[] = [
			$row->name,
			$row->quantity,
			$row->reserved,
			$formattedPrice,
			$formattedProductTotal,
		];
	}

	$sqlTotal   = "
        SELECT
            SUM(t.quantity * s.supplier_price)
        FROM
            stock AS s
        JOIN
            toronto as t
        ON
            s.product_id = t.product_id
		JOIN
			products AS p
		ON
			t.product_id = p.id	
			
		WHERE p.active = $active	
		";

	$total      = '$' . number_format($db->getColumn($sqlTotal), 2);

	$json_data = array(
		"draw"              => intval($params['draw']),
		"recordsTotal"      => intval($totalRecords),
		"recordsFiltered"   => intval($totalRecords),
		"data"              => $data,
		"total"             => $total
	);

	die(json_encode($json_data, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}

if (isset($_POST['getkelownaStock'])) {
	$params = $columns = $totalRecords = $data = [];
	$params = $_POST;
	$active = isset($params['active']) ? intval($params['active']) : 1;

	$columns = [
		0 => 'id',
		1 => 'name',
		2 => 'quantity',
		3 => 'price',
		4 => 'total',
	];

	$where_condition = '';

	$sql = "
        SELECT
            t.product_id AS id,
            p.name AS name,
            t.quantity AS quantity,
            t.quantity_reserved AS reserved,
            s.supplier_price AS price,
            t.quantity * s.supplier_price AS total
        FROM
		   kelowna AS t
        JOIN
            products AS p
        ON
            t.product_id = p.id
        JOIN
            stock as s
        ON
            t.product_id = s.product_id
		WHERE	
			p.active = $active
        ";

	if (!empty($params['search']['value'])) {
		$where_condition .= " AND p.name LIKE '%" . $params['search']['value'] . "%' ";
	}

	if (isset($where_condition) && $where_condition != '') {
		$sql .= $where_condition;
	}

	$sql .= " ORDER BY " . $columns[$params['order'][0]['column']] . " " . $params['order'][0]['dir'] . " LIMIT " . $params['start'] . " ," . $params['length'];

	$totalRecords = $db->getColumn("SELECT COUNT(id) FROM products WHERE products.active = $active");
	$queryRecords = $db->getRows($sql);

	$data = [];

	foreach ($queryRecords as $row) {

		$formattedPrice         = '$' . number_format($row->price, 2);
		$formattedProductTotal  = '$' . number_format($row->total, 2);

		$data[] = [
			$row->name,
			$row->quantity,
			$row->reserved,
			$formattedPrice,
			$formattedProductTotal,
		];
	}

	$sqlTotal   = "
        SELECT
            SUM(t.quantity * s.supplier_price)
        FROM
            stock AS s
        JOIN
		    kelowna as t
        ON
            s.product_id = t.product_id
		JOIN
			products AS p
		ON
			t.product_id = p.id	
			
		WHERE p.active = $active	
		";

	$total      = '$' . number_format($db->getColumn($sqlTotal), 2);

	$json_data = array(
		"draw"              => intval($params['draw']),
		"recordsTotal"      => intval($totalRecords),
		"recordsFiltered"   => intval($totalRecords),
		"data"              => $data,
		"total"             => $total
	);

	die(json_encode($json_data, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}

if (isset($_POST['getProductsForTransferInfo'])) {

	$sql = "
		SELECT
			id,
			name
		FROM
			products
		";

	$products = $db->getRows($sql);

	$html = '';
	$html .= '<option value="0">Select product</option>';

	foreach ($products as $product) {
		$html .= '<option value="' . $product->id . '">' . $product->name . '</option>';
	}

	die(json_encode($html, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}

if (isset($_POST['getInventoryInfoForTransfer'])) {

	$id = $_POST['productID'];

	$sql = "
		SELECT
			s.quantity AS max,
			c.quantity AS calgary,
			e.quantity AS edmonton,
			t.quantity AS toronto,
			k.quantity AS kelowna
		FROM
			stock AS s
		JOIN
			calgary AS c
		ON
			s.product_id = c.product_id
		JOIN
			edmonton AS e
		ON
			s.product_id = e.product_id
		JOIN
		    toronto AS t
		ON
			s.product_id = t.product_id
		JOIN
		    kelowna AS k
		ON
			s.product_id = k.product_id
		WHERE
			s.product_id = :id
		";

	$data = $db->getRow($sql, ['id' => $id]);

	$json = [];

	$json['max'] 		= $data->max;
	$json['calgary'] 	= $data->calgary;
	$json['edmonton'] 	= $data->edmonton;
	$json['toronto'] 	= $data->toronto;
	$json['kelowna'] 	= $data->kelowna;

	die(json_encode($json, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}
if (isset($_POST['changeStatusValue'])) {
	$status = $_POST['checkboxValue'];

	$status = ($status == 1) ? 0 : 1;
	$name = $_POST['name'];

	$db->beginTransaction();

	try {
		// Update the status in the 'inventories' table
		$params = $columns = $totalRecords = $data = [];
		$params = $_POST;

		$columns = [
			0 => 'id',
			1 => 'name',
			2 => 'quantity',
			3 => 'price',
			4 => 'total',
		];

		$where_condition = '';

		$sql = "
        SELECT
            t.product_id AS id,
            p.name AS name,
            t.quantity AS quantity,
            t.quantity_reserved AS reserved,
            s.supplier_price AS price,
            t.quantity * s.supplier_price AS total
        FROM
		   kelowna AS t
        JOIN
            products AS p
        ON
            t.product_id = p.id
        JOIN
            stock as s
        ON
            t.product_id = s.product_id
		WHERE	
			p.active = 1
        ";

		if (!empty($params['search']['value'])) {
			$where_condition .= " AND p.name LIKE '%" . $params['search']['value'] . "%' ";
		}

		if (isset($where_condition) && $where_condition != '') {
			$sql .= $where_condition;
		}

		$sql .= " ORDER BY " . $columns[$params['order'][0]['column']] . " " . $params['order'][0]['dir'] . " LIMIT " . $params['start'] . " ," . $params['length'];

		$totalRecords = $db->getColumn("SELECT COUNT(id) FROM products WHERE products.active = 1");
		$queryRecords = $db->getRows($sql);

		$data = [];

		foreach ($queryRecords as $row) {

			$formattedPrice         = '$' . number_format($row->price, 2);
			$formattedProductTotal  = '$' . number_format($row->total, 2);

			$data[] = [
				$row->name,
				$row->quantity,
				$row->reserved,
				$formattedPrice,
				$formattedProductTotal,
			];
		}

		$sqlTotal   = "
        SELECT
            SUM(t.quantity * s.supplier_price)
        FROM
            stock AS s
        JOIN
		    kelowna as t
        ON
            s.product_id = t.product_id
        ";

		$total      = '$' . number_format($db->getColumn($sqlTotal), 2);

		$json_data = array(
			"draw"              => intval($params['draw']),
			"recordsTotal"      => intval($totalRecords),
			"recordsFiltered"   => intval($totalRecords),
			"data"              => $data,
			"total"             => $total
		);

		die(json_encode($json_data, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
	} catch (PDOException $e) {
		// Rollback the transaction if an error occurred
		$db->rollBack();
		echo json_encode(["success" => false, "message" => "Error updating status: " . $e->getMessage()]);
	}
}

if (isset($_POST['setTransfer'])) {

	$productID 	= $_POST['productID'];
	$fromtable 	= $_POST['fromID'];
	$totable	= $_POST['toID'];
	$qty 	= $_POST['qty'];
	// $calgary 	= $_POST['calgary'];
	// $edmonton 	= $_POST['edmonton'];
	if ($productID == 0 || $fromtable == 0 || $totable == 0) {
		$msgHTML = '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Please select data properly
			</div>';
		die($msgHTML);
	}
	$db->beginTransaction();
	// Check if the quantity to transfer exceeds the available quantity
	$sqlForAvailableCurrentqtyquery = "SELECT quantity FROM $fromtable WHERE product_id = $productID";

	$currentFromQuantity = $db->getColumn($sqlForAvailableCurrentqtyquery);

	if ($currentFromQuantity < $qty) {
		$db->rollBack();
		$msgHTML = '
            <div style="width: 300px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
                <i style="color: red" class="fas fa-exclamation-triangle"></i> Quantity exceeds available quantity. Available quantity: ' . $currentFromQuantity . ' units.
            </div>';
		die($msgHTML);
	}
	$sqlFromCurrentqtyquery 	= "SELECT quantity - $qty FROM $fromtable where product_id = $productID";
	$sqlToCurrentqtyquery 	= "SELECT quantity + $qty FROM $totable where product_id = $productID";

	$sqlFromCurrentqty		= number_format($db->getColumn($sqlFromCurrentqtyquery), 2);
	$sqlToCurrentqty		= number_format($db->getColumn($sqlToCurrentqtyquery), 2);

	$sqlFrom = "UPDATE $fromtable SET quantity = :quantity WHERE product_id = :product_id";
	$sqlTo = "UPDATE $totable SET quantity = :quantity WHERE product_id = :product_id";

	$updateFrom = $db->updateRow($sqlFrom, [
		'quantity' 		=> $sqlFromCurrentqty,
		'product_id' 	=> $productID
	]);

	$updateTo = $db->updateRow($sqlTo, [
		'quantity' 		=> $sqlToCurrentqty,
		'product_id' 	=> $productID
	]);

	if (!$updateTo && !$updateFrom) {

		$db->rollBack();
		$msgHTML = '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Transfer failed.
			</div>';
		die($msgHTML);
	}

	$db->commit();
	$msgHTML = '
		<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
			<i style="color: green" class="fas fa-check-circle"></i> Transfer successful.
		</div>';
	die($msgHTML);
}

//	set inventories form (documents)
if (isset($_POST['setInventories'])) {

	$productID 	= $_POST['productID'];
	$calgary 	= $_POST['calgary'];
	$edmonton 	= $_POST['edmonton'];
	$toronto 	= $_POST['toronto'];
	$kelowna 	= $_POST['kelowna'];
	$stock		= $edmonton + $calgary + $toronto + $kelowna;

	$db->beginTransaction();

	$sqlCal 	= "UPDATE calgary SET quantity = :quantity WHERE product_id = :product_id";
	$sqlEdm 	= "UPDATE edmonton SET quantity = :quantity WHERE product_id = :product_id";
	$sqlTor 	= "UPDATE toronto SET quantity = :quantity WHERE product_id = :product_id";
	$sqlKel 	= "UPDATE kelowna SET quantity = :quantity WHERE product_id = :product_id";
	$sqlStock 	= "UPDATE stock SET quantity = :quantity WHERE product_id = :product_id";

	$updateCalgary = $db->updateRow($sqlCal, [
		'quantity' 		=> $calgary,
		'product_id' 	=> $productID
	]);


	$updateEdmonton = $db->updateRow($sqlEdm, [
		'quantity' 		=> $edmonton,
		'product_id' 	=> $productID
	]);



	$updateToronto = $db->updateRow($sqlTor, [
		'quantity' 		=> $toronto,
		'product_id' 	=> $productID
	]);

	$updateKelowna = $db->updateRow($sqlKel, [
		'quantity' 		=> $kelowna,
		'product_id' 	=> $productID
	]);

	$updateStock = $db->updateRow($sqlStock, [
		'quantity' 		=> $stock,
		'product_id' 	=> $productID
	]);

	if (!$updateCalgary && !$updateEdmonton && !$updateToronto && !$updateKelowna && !$updateStock) {

		$db->rollBack();
		$msgHTML = '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Update failed.
			</div>';
		die($msgHTML);
	}

	$db->commit();
	$msgHTML = '
		<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
			<i style="color: green" class="fas fa-check-circle"></i> Update successful.
		</div>';

	die($msgHTML);
}
