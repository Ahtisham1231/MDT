<?php
//	administrator submitted supplier invoice
if (isset($_POST['supplierInvoiceSubmit'])) {
	$type 			= 0;
	$supplier_id 	= trim($_POST['supplierID']);
	$user_id		= $_SESSION['userID'];
	$amount			= trim($_POST['total']);
	$status_id		= 1;
	$date			= isset($_POST['date']) ? $_POST['date'] : date('Y-m-d', time());
	$month 			= substr($date, 5, 2);
	$year 			= substr($date, 0, 4);
	$note			= trim($_POST['note']);
	$inventory		= trim($_POST['inventory']);

	//	check if date format is valid
	if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date)) {

		$response = [];
		$response['ok'] 	= 1;
		$response['html'] 	= '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Date format: ' . h($date) . ' is not valid!
			</div>';
		die(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
	}

	list($y, $m, $d) = array_pad(explode('-', $date, 3), 3, 0);
	$checkDate = ctype_digit("$y$m$d") && checkdate($m, $d, $y);

	//	check if date is valid
	if (!$checkDate) {
		$response = [];
		$response['ok'] 	= 1;
		$response['html'] 	= '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> ' . h($date) . ' is not a valid date!
			</div>';

		die(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
	}

	$db->beginTransaction();

	//	add invoice
	$sql = "INSERT INTO

		invoices (
			type,
			supplier_id,
			user_id,
			amount,
			status_id,
			date,
			month,
			year,
			inventory,
			note
		) VALUES (
			:type,
			:supplier_id,
			:user_id,
			:amount,
			:status_id,
			:date,
			:month,
			:year,
			:inventory,
			:note
		)";

	$insertInvoices = $db->insertRow($sql, [
		'type' 			=> $type,
		'supplier_id' 	=> $supplier_id,
		'user_id' 		=> $user_id,
		'amount' 		=> $amount,
		'status_id'		=> $status_id,
		'date'			=> $date,
		'month'			=> $month,
		'year'			=> $year,
		'inventory'		=> $inventory,
		'note'			=> $note,
	]);

	if (!$insertInvoices) {

		$db->rollBack();

		$response = [];
		$response['ok'] 	= 1;
		$response['html'] 	= '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Failed to add invoice!
			</div>';

		die(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
	}

	//	add products from invoice (invoice_products)
	$invoice_id = $db->getLastID();

	$sql = "INSERT INTO

		invoice_products (
			invoice_id,
			product_id,
			quantity,
			price
		) VALUES (
			:invoice_id,
			:product_id,
			:quantity,
			:price
		)";

	$insertInvoiceProducts = $db->prepare($sql);

	foreach ($_POST['products'] as $product) {

		$product_id = $product['productID'];
		$quantity 	= $product['quantity'];
		$price 		= $product['price'];
		$total  	= $quantity * $price;

		$insertInvoiceProducts->execute([
			'invoice_id' => $invoice_id,
			'product_id' => $product_id,
			'quantity' 	 => $quantity,
			'price'		 => $price
		]);

		// //	get current stock info
		// $getSQL = "
		// 	SELECT
		// 		quantity,
		// 		supplier_price,
		// 		quantity * supplier_price AS total
		// 	FROM
		// 		stock
		// 	WHERE
		// 		product_id = :product_id
		// 	";

		// $productInfo = $db->getRow($getSQL, ['product_id' => $product_id]);

		// $oldPrice 	 = $productInfo->supplier_price;
		// $oldQuantity = $productInfo->quantity;
		// $oldTotal	 = $productInfo->total;

		// $newQuantity = $oldQuantity + $quantity;
		// $newTotal 	 = $oldTotal + $total;

		// if ($oldPrice == $price) {
		// 	$supplier_price = $price;
		// } else {
		// 	$supplier_price = $newTotal / $newQuantity;
		// }

		// //	update stock
		// $updateStockSQL = "
		// 	UPDATE
		// 		stock
		// 	SET
		// 		quantity 		= quantity + :quantity,
		// 		supplier_price 	= :supplier_price
		// 	WHERE
		// 		product_id 		= :product_id
		// 	";

		// $updateStock = $db->updateRow($updateStockSQL, [
		// 	'product_id' 		=> $product_id,
		// 	'quantity' 			=> $quantity,
		// 	'supplier_price' 	=> $supplier_price,
		// ]);

		// //	update clagary inventory
		// if (isset($_POST['inventory']) && $_POST['inventory'] == 'calgary') {
		// 	$updateCalgarySQL = "
		// 	UPDATE
		// 		 calgary
		// 	SET
		// 		quantity 		= quantity + :quantity
		// 	WHERE
		// 		product_id 		= :product_id
		// 	";

		// 	$updateCalgary = $db->updateRow($updateCalgarySQL, [
		// 		'product_id' 		=> $product_id,
		// 		'quantity' 			=> $quantity,
		// 	]);
		// }
		// if (isset($_POST['inventory'])  && $_POST['inventory'] == 'edmonton') {
		// 	$updateEdmontonSQL = "
		// 	UPDATE
		// 		 edmonton
		// 	SET
		// 		quantity 		= quantity + :quantity
		// 	WHERE
		// 		product_id 		= :product_id
		// 	";

		// 	$updateEdmonton = $db->updateRow($updateEdmontonSQL, [
		// 		'product_id' 		=> $product_id,
		// 		'quantity' 			=> $quantity,
		// 	]);
		// }
		// if (isset($_POST['inventory'])  && $_POST['inventory'] == 'toronto') {
		// 	$updateTorontoSQL = "
		// 	UPDATE
		// 		toronto
		// 	SET
		// 		quantity 		= quantity + :quantity
		// 	WHERE
		// 		product_id 		= :product_id
		// 	";

		// 	$updateToronto = $db->updateRow($updateTorontoSQL, [
		// 		'product_id' 		=> $product_id,
		// 		'quantity' 			=> $quantity,
		// 	]);
		// }
		// if (isset($_POST['inventory'])   && $_POST['inventory'] == 'kelowna') {
		// 	$updateKelownaSQL = "
		// 	UPDATE
		// 		kelowna
		// 	SET
		// 		quantity 		= quantity + :quantity
		// 	WHERE
		// 		product_id 		= :product_id
		// 	";

		// 	$updateKelowna = $db->updateRow($updateKelownaSQL, [
		// 		'product_id' 		=> $product_id,
		// 		'quantity' 			=> $quantity,
		// 	]);
		// }
	}

	//	adjust supplier balance
	$sql = "UPDATE suppliers SET balance = balance + :amount WHERE id = :id";
	$updateSupplierBalance = $db->updateRow($sql, [
		'amount' 	=> $amount,
		'id' 		=> $supplier_id,
	]);

	$db->commit();

	$response = [];
	$response['ok'] 	= 1;
	$response['html'] 	= '
		<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
			<i style="color: green" class="fas fa-check-circle"></i> Added invoce ID: ' . $invoice_id . '
		</div>';

	die(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}

//	supplier invoices table
if (isset($_POST['getSupplierInvoices'])) {

	$params = $columns = $totalRecords = $data = [];
	$params = $_POST;

	$columns = [
		0 => 'id',
		1 => 'supplier',
		2 => 'total',
		3 => 'status',
		4 => 'date',
		5 => 'details',
		6 => 'complete',
		7 => 'edit',
		8 => 'delete',
	];

	$where_condition = '';

	$sql = "
		SELECT
			i.id as id,
			s.name as supplier,
			s.id as supplierID,
			i.amount as total,
			i.inventory as inventory,
			ist.status as status,
			i.status_id as status_id,
			i.user_id as user_id,
			i.date as date
		FROM
			invoices as i
		JOIN
			suppliers AS s
		ON
			i.supplier_id = s.id
		JOIN
			invoice_status AS ist
		ON
			i.status_id = ist.id
		WHERE
			i.type = 0
		";


	if (!empty($params['search']['value'])) {
		$where_condition .=	" AND ";
		$where_condition .= "( s.name LIKE '%" . $params['search']['value'] . "%' ";
		$where_condition .= " OR i.amount LIKE '%" . $params['search']['value'] . "%' ";
		$where_condition .= " OR ist.status LIKE '%" . $params['search']['value'] . "%' )";
	}

	if (isset($where_condition) && $where_condition != '') {
		$sql .= $where_condition;
	}

	$sql .=  " ORDER BY " . $columns[$params['order'][0]['column']] . "   " . $params['order'][0]['dir'] . "  LIMIT " . $params['start'] . " ," . $params['length'] . " ";

	$totalRecords = $db->getColumn("SELECT COUNT(id) FROM invoices WHERE type = 0");
	$queryRecords = $db->getRows($sql);

	$data = [];
	$i = 1;
	// print_r($queryRecords);

	foreach ($queryRecords as $row) {

		$total 		= '$' . number_format($row->total, 2);
		$showDate 	= date('m-d-Y', strtotime($row->date));
		$disabledComplete 	= $row->status_id == 3 ? ' disabled' : '';
		$disabled = ($row->total * 1 < 0) ? ' disabled' : '';

		if ($disabledComplete != '') {
			$disabled = ' disabled';
		}

		$data[] = [
			$i,
			$row->supplier,
			$total,
			$row->status,
			$showDate,
			'<button class="details_buttons" data-date="' . $showDate . '" data-total="' . $row->total . '" data-user="' . $row->supplier . '" data-id="' . $row->id . '">Details</button>',
			'<button ' . $disabledComplete . ' class="buyer_invoice_status" data-user-id="' . $row->user_id . '" data-inventory="' . $row->inventory . '" data-total="' . $row->total . '" data-id="' . $row->id . '">Complete</button>',
			'<button class="edit_supplier_invoice"' . $disabled . ' data-date="' . $row->date . '" data-total="' . $row->total . '" data-supplier="' . $row->supplierID . '" data-id="' . $row->id . '">Edit</button>',
			'<button class="delete_supplier_invoice" data-supplier="' . $row->supplierID . '" data-id="' . $row->id . '">Delete</button>',
		];
		$i++;
	}

	$json = [
		"draw"            => intval($params['draw']),
		"recordsTotal"    => intval($totalRecords),
		"recordsFiltered" => intval($totalRecords),
		"data"            => $data
	];

	die(json_encode($json, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}

//	get supplier invoice details
if (isset($_POST['supplierInvoiceDetails'])) {

	$id = $_POST['invoiceID'];

	$sql = "
		SELECT
			p.name,
			ip.quantity,
			ip.price,
			ip.quantity * ip.price AS total,
			i.note
		FROM
			invoices AS i
		JOIN
			invoice_products AS ip
		ON
			i.id = ip.invoice_id
		JOIN
			products AS p
		ON
			ip.product_id = p.id
		WHERE
			invoice_id = :id
		";

	$json = $db->getRows($sql, ['id' => $id]);
	die(json_encode($json, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}

//	supplier list table
if (isset($_POST['getSuppliers'])) {

	$params = $columns = $totalRecords = $data = [];
	$params = $_POST;

	$columns = [
		0 => 'supplier',
		1 => 'balance',
		2 => 'details',
	];

	$where_condition = '';

	$sql = "
		SELECT
			id,
			name AS supplier,
			balance
		FROM
			suppliers
		";

	if (!empty($params['search']['value'])) {
		$where_condition .=	" WHERE ";
		$where_condition .= " name LIKE '%" . $params['search']['value'] . "%' ";
	}

	if (isset($where_condition) && $where_condition != '') {
		$sql .= $where_condition;
	}

	$sql .=  " ORDER BY " . $columns[$params['order'][0]['column']] . "   " . $params['order'][0]['dir'] . "  LIMIT " . $params['start'] . " ," . $params['length'] . " ";

	$totalRecords = $db->getColumn("SELECT COUNT(id) FROM suppliers");
	$queryRecords = $db->getRows($sql);

	$data = [];

	foreach ($queryRecords as $row) {

		$details 		= '<button class="supplier_balance_details" data-id="' . $row->id . '">Invoices</button>';
		$balance 		= '$' . number_format($row->balance, 2);

		$data[] = [

			$row->supplier,
			$balance,
			$details,
		];
	}

	$sqlTotal 	= "SELECT SUM(balance) FROM suppliers";
	$total 		= $db->getColumn($sqlTotal);
	$total 		= '$' . number_format($total, 2);

	$json = [
		"draw"            => intval($params['draw']),
		"recordsTotal"    => intval($totalRecords),
		"recordsFiltered" => intval($totalRecords),
		"data"            => $data,
		"total"			  => $total,
	];

	die(json_encode($json, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}

//	supplier invoices table
if (isset($_POST['getAllSupplierInvoices'])) {

	$params = $columns = $totalRecords = $data = [];
	$params = $_POST;
	$id 	= $_POST['id'];

	$columns = [
		0 => 'id',
		1 => 'amount',
		2 => 'date',
		3 => 'status',
		4 => 'details',
	];

	$where_condition = '';

	$sql = "
		SELECT
			i.id,
			i.amount AS total,
			i.date,
			s.name AS supplier,
			ist.status as status
		FROM
			invoices as i
		JOIN
			suppliers as s
		ON
			i.supplier_id = s.id
		JOIN
			invoice_status AS ist
		ON
			i.status_id = ist.id
		WHERE
			i.supplier_id = :id
		AND
			i.type = 0
		";

	if (!empty($params['search']['value'])) {
		$where_condition .=	" AND ";
		$where_condition .= "( i.id LIKE '%" . $params['search']['value'] . "%' ";
		$where_condition .= " OR i.amount LIKE '%" . $params['search']['value'] . "%' ";
		$where_condition .= " OR i.date LIKE '%" . $params['search']['value'] . "%' )";
	}

	if (isset($where_condition) && $where_condition != '') {
		$sql .= $where_condition;
	}

	$sql .=  " ORDER BY " . $columns[$params['order'][0]['column']] . "   " . $params['order'][0]['dir'] . "  LIMIT " . $params['start'] . " ," . $params['length'] . " ";

	$totalRecords = $db->getColumn("SELECT COUNT(id) FROM invoices WHERE type = 0 AND supplier_id = :supplier_id", ['supplier_id' => $id]);
	$queryRecords = $db->getRows($sql, ['id' => $id]);

	$data = [];

	$supplier = $db->getColumn("SELECT name FROM suppliers WHERE id = :id", ['id' => $id]);

	foreach ($queryRecords as $row) {

		$total 		= '$' . number_format($row->total, 2);
		$showDate 	= date('m-d-Y', strtotime($row->date));

		$data[] = [
			$row->id,
			$total,
			$showDate,
			$row->status,
			'<button class="details_buttons" data-date="' . $showDate . '" data-total="' . $row->total . '" data-id="' . $row->id . '" data-user="' . $row->supplier . '">Details</button>'
		];
	}

	$json = [
		"draw"            => intval($params['draw']),
		"recordsTotal"    => intval($totalRecords),
		"recordsFiltered" => intval($totalRecords),
		"data"            => $data,
		"supplier"		  => $supplier
	];

	die(json_encode($json, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}

//	supplier payments table
if (isset($_POST['getSupplierPayments'])) {

	$params = $columns = $totalRecords = $data = [];
	$params = $_POST;

	$columns = [
		0 => 'id',
		1 => 'supplier',
		2 => 'amount',
		3 => 'date',
		4 => 'status',
	];

	$where_condition = '';

	$sql = "
		SELECT
			p.id,
			s.name AS supplier,
			s.id   AS supplierID,
			p.amount,
			p.status,
			p.user_id,
			p.date
		FROM
			payments as p
		JOIN
			suppliers as s
		ON
			p.supplier_id = s.id
		WHERE
			p.type = 0
		";

	if (!empty($params['search']['value'])) {
		$where_condition .=	" AND ";
		$where_condition .= "( p.id LIKE '%" . $params['search']['value'] . "%' ";
		$where_condition .= " OR p.amount LIKE '%" . $params['search']['value'] . "%' ";
		$where_condition .= " OR s.name LIKE '%" . $params['search']['value'] . "%' ";
		$where_condition .= " OR p.date LIKE '%" . $params['search']['value'] . "%' )";
	}

	if (isset($where_condition) && $where_condition != '') {
		$sql .= $where_condition;
	}

	$sql .=  " ORDER BY " . $columns[$params['order'][0]['column']] . "   " . $params['order'][0]['dir'] . "  LIMIT " . $params['start'] . " ," . $params['length'] . " ";

	$totalRecords = $db->getColumn("SELECT COUNT(id) FROM payments WHERE type = 0");
	$queryRecords = $db->getRows($sql);

	$data = [];

	foreach ($queryRecords as $row) {

		$formattedAmount 	= '$' . number_format($row->amount, 2);
		$showDate 			= date('m-d-Y', strtotime($row->date));
		$dateForFrontEnd	= date('Y-m-d', strtotime($row->date));
		$disabledComplete 	= $row->status == 3 ? ' disabled' : '';

		if ($row->status == 1) {
			$statusName = 'processing';
		} elseif ($row->status == 2) {
			$statusName = 'pending';
		} else {
			$statusName = 'complete';
		}
		if ($disabledComplete != '') {
			$disabled = ' disabled';
		}

		$data[] = [
			$row->id,
			$row->supplier,
			$formattedAmount,
			$statusName,
			$showDate,
			'<button ' . $disabledComplete . ' class="edit_payment_status"   data-amount="' . $row->amount . '" data-user-id="' . $row->supplierID . '"  data-id="' . $row->id . '">Complete</button>',
			'<button '  . $disabledComplete . ' class="edit_payment_buttons"  data-user="' . $row->supplierID . '" data-id="' . $row->id . '" data-amount="' . $row->amount . '" data-date="' . $dateForFrontEnd . '">Edit</button>',
			'<button '  . $disabledComplete . ' class="delete_payment_buttons" data-supplier-id="' . $row->supplierID . '" data-id="' . $row->id . '" data-amount="' . $row->amount . '">Delete</button>',
		];
	}

	$json = [
		"draw"            => intval($params['draw']),
		"recordsTotal"    => intval($totalRecords),
		"recordsFiltered" => intval($totalRecords),
		"data"            => $data,
	];

	die(json_encode($json, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}

//	add new supplier
if (isset($_POST['submitNewSupplier'])) {

	$supplier 	= trim($_POST['supplier']);
	$balance 	= trim($_POST['balance']);

	$exists = $db->getColumn("SELECT name FROM suppliers WHERE name = :name", ['name' => $supplier]);

	if ($exists) {
		$msgHTML = '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Supplier name already exists.
			</div>';
		die($msgHTML);
	}

	$sql = "INSERT INTO suppliers (name, balance) VALUES (:name, :balance)";
	$insert = $db->insertRow($sql, [
		'name' 		=> $supplier,
		'balance' 	=> $balance
	]);


	if ($insert) {

		$msgHTML = '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: green" class="fas fa-check-circle"></i> Added new supplier: ' . $supplier . '
			</div>';
		die($msgHTML);
	} else {

		$msgHTML = '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Could not add new supplier.
			</div>';
		die($msgHTML);
	}
}

//	payment to supplier
if (isset($_POST['supplierPayment'])) {

	$type  			= 0;
	$supplier_id 	= trim($_POST['supplierID']);
	$user_id 		= $_SESSION['userID'];
	$amount 		= trim($_POST['amount']);
	$date 			= trim($_POST['date']);

	//	check if date format is valid
	if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date)) {

		$response = [];
		$response['ok'] 	= 1;
		$response['html'] 	= '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Date format: ' . h($date) . ' is not valid!
			</div>';
		die(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
	}

	list($y, $m, $d) = array_pad(explode('-', $date, 3), 3, 0);
	$checkDate = ctype_digit("$y$m$d") && checkdate($m, $d, $y);

	//	check if date is valid
	if (!$checkDate) {
		$response = [];
		$response['ok'] 	= 1;
		$response['html'] 	= '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> ' . h($date) . ' is not a valid date!
			</div>';

		die(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
	}

	if ($amount === '' || $supplier_id === '' || $date === '') {

		$response = [];
		$response['ok'] 	= 1;
		$response['html'] 	= '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> All inputs required!
			</div>';

		die(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
	}

	$supplier = $db->getColumn("SELECT name FROM suppliers WHERE id = :id", ['id' => $supplier_id]);

	$db->beginTransaction();

	$sql = "
		INSERT
		INTO
			payments (
				type,
				supplier_id,
				user_id,
				amount,
				date
			)
		VALUES (
			:type,
			:supplier_id,
			:user_id,
			:amount,
			:date
		)
	";

	$insert = $db->insertRow($sql, [
		'type' 			=> $type,
		'supplier_id' 	=> $supplier_id,
		'user_id' 		=> $user_id,
		'amount' 		=> $amount,
		'date' 			=> $date,
	]);

	if ($insert) {

		$db->commit();

		$response = [];
		$response['ok'] 	= 1;
		$response['html'] 	= '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: green" class="fas fa-check-circle"></i> Payment for ' . $supplier . ' has been successfully processed.
			</div>';

		die(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
	} else {

		$db->rollBack();

		$response = [];
		$response['ok'] 	= 1;
		$response['html'] 	= '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Failed to record payment for ' . $supplier . '
			</div>';

		die(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
	}
}

//	get suppliers list for editing
if (isset($_POST['editSuppliersList'])) {

	$params = $columns = $totalRecords = $data = [];
	$params = $_POST;

	$columns = [
		0 => 'id',
		1 => 'supplier',
		2 => 'update',
		3 => 'active',
		//3 => 'delete',
	];

	$where_condition = '';

	$sql = "
		SELECT
			id,
			name AS supplier,
			active
		FROM
			suppliers
		";

	if (!empty($params['search']['value'])) {
		$where_condition .=	" WHERE ";
		$where_condition .= " name LIKE '%" . $params['search']['value'] . "%' ";
	}

	if (isset($where_condition) && $where_condition != '') {
		$sql .= $where_condition;
	}

	$sql .=  " ORDER BY " . $columns[$params['order'][0]['column']] . "   " . $params['order'][0]['dir'] . "  LIMIT " . $params['start'] . " ," . $params['length'] . " ";

	$totalRecords = $db->getColumn("SELECT COUNT(id) FROM suppliers");
	$queryRecords = $db->getRows($sql);

	$data = [];
	$i = 1;
	foreach ($queryRecords as $row) {

		$supplier = '<input data-id="' . $row->id . '" value="' . h($row->supplier) . '">';

		$update = '<button class="update_supplier" data-id="' . $row->id . '">Update</button>';
		//$delete = '<button class="delete_supplier" data-id="' . $row->id. '">Delete</button>';

		if ($row->active) {
			$selectedYes 	= 'selected';
			$selectedNo 	= '';
		} else {
			$selectedYes 	= '';
			$selectedNo 	= 'selected';
		}

		$activeHTML = '<select>';
		$activeHTML .= '<option value="1" ' . $selectedYes . '>Yes</option>';
		$activeHTML .= '<option value="0" ' . $selectedNo . '>No</option>';
		$activeHTML .= '</select>';

		$data[] = [
			$i,
			$supplier,
			$activeHTML,
			$update,
			//$delete,
		];
		$i++;
	}

	$json = [
		"draw"            => intval($params['draw']),
		"recordsTotal"    => intval($totalRecords),
		"recordsFiltered" => intval($totalRecords),
		"data"            => $data,
	];

	die(json_encode($json, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}

//	update supplier
if (isset($_POST['updateSupplier'])) {

	$id 		= trim($_POST['id']);
	$name 		= trim($_POST['suppllier']);
	$active 	= trim($_POST['active']);

	if ($id === '' || $name === '') {
		die('0');
	}

	$update = $db->updateRow("Update suppliers SET name = :name, active = :active WHERE id = :id", [
		'id' 		=> $id,
		'name' 		=> $name,
		'active' 	=> $active,
	]);

	if ($update) {
		die('1');
	} else {
		die('0');
	}
}

//	get supplier invoice details for editing
if (isset($_POST['getSupplierInvoiceForEditing'])) {

	$id = trim($_POST['invoiceID']);

	$sql = "
		SELECT
			p.name,
			ip.product_id AS productID,
			ip.quantity,
			ip.price,
			ip.quantity * ip.price AS total,
			i.note
		FROM
			invoice_products AS ip
		JOIN
			products AS p
		ON
			ip.product_id = p.id
		JOIN
			invoices AS i
		ON
			ip.invoice_id = i.id
		WHERE
			invoice_id = :id
		";

	$rows = $db->getRows($sql, ['id' => $id]);
	die(json_encode($rows, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}

//	edit supplier invoice
if (isset($_POST['editSupplierInvoice'])) {

	$invoiceID		= $_POST['invoiceID'];
	$supplierID	 	= $_POST['supplierID'];
	$date 			= $_POST['date'];
	$invoiceTotal 	= $_POST['total'];
	$products		= $_POST['products'];
	$month 			= substr($date, 5, 2);
	$year 			= substr($date, 0, 4);
	$note			= trim($_POST['note']);

	//	check if date format is valid
	if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date)) {

		$response = [];
		$response['ok'] 	= 1;
		$response['html'] 	= '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Date format: ' . h($date) . ' is not valid!
			</div>';
		die(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
	}

	list($y, $m, $d) = array_pad(explode('-', $date, 3), 3, 0);
	$checkDate = ctype_digit("$y$m$d") && checkdate($m, $d, $y);

	//	check if date is valid
	if (!$checkDate) {
		$response = [];
		$response['ok'] 	= 1;
		$response['html'] 	= '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> ' . h($date) . ' is not a valid date!
			</div>';

		die(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
	}

	$db->beginTransaction();

	/*
		get old invoice total and supplier id before updating
		in order to adjust balance for old (new) supplier
	*/

	$oldInvoiceTotal = $db->getColumn("SELECT amount FROM invoices WHERE id = :id", ['id' => $invoiceID]);
	$oldSupplierID   = $db->getColumn("SELECT supplier_id FROM invoices WHERE id = :id", ['id' => $invoiceID]);

	//	invoices
	$sql = "
		UPDATE
			invoices
		SET
			supplier_id = :supplier_id,
			amount  	= :amount,
			date    	= :date,
			month    	= :month,
			year    	= :year,
			note		= :note
		WHERE
			id 			= :id
		";

	$update = $db->updateRow($sql, [
		'supplier_id' 	=> $supplierID,
		'amount' 		=> $invoiceTotal,
		'date' 			=> $date,
		'id' 			=> $invoiceID,
		'month' 		=> $month,
		'year' 			=> $year,
		'note' 			=> $note,
	]);

	$updateBalanceSQL = "
		UPDATE
			suppliers
		SET
			balance = balance - :amount
		WHERE
			id = :id
		";

	$updateBalance = $db->updateRow($updateBalanceSQL, [
		'amount' 	=> $oldInvoiceTotal,
		'id' 		=> $oldSupplierID
	]);

	if (!$update || !$updateBalance) {

		$db->rollBack();

		$msgHTML = '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> No changes made. <br> Update failed.
			</div>';
		die($msgHTML);
	}

	//	adjust stock
	$sql = "
		SELECT
			quantity,
			price,
			quantity * price AS total,
			product_id
		FROM
			invoice_products
		WHERE
			invoice_id = :invoice_id
		";

	$productsToAdjust = $db->getRows($sql, ['invoice_id' => $invoiceID]);

	$selectOldStockSQL = "
		SELECT
			quantity,
			supplier_price,
			quantity * supplier_price AS total
		FROM
			stock
		WHERE
			product_id = :product_id
		";

	//	adjust stock
	$updateStockSQL = "
		UPDATE
			stock
		SET
			quantity 		= quantity - :quantity,
			supplier_price 	= :supplier_price
		WHERE
			product_id 		= :product_id
		";

	$preparedUpdatedStock = $db->prepare($updateStockSQL);

	//	adjust calgary
	$updateCalgarySQL = "
		UPDATE
			calgary
		SET
			quantity 		= quantity - :quantity
		WHERE
			product_id 		= :product_id
		";

	$preparedUpdatedCalgary = $db->prepare($updateCalgarySQL);

	foreach ($productsToAdjust as $productToAdjust) {

		$updateID 	= $productToAdjust->product_id;
		$quantity 	= $productToAdjust->quantity;
		$price 		= $productToAdjust->price;
		$total      = $productToAdjust->total;

		//	get current info
		$old = $db->getRow($selectOldStockSQL, ['product_id' => $updateID]);

		$oldPrice 		= $old->supplier_price;
		$oldQuantity 	= $old->quantity;
		$oldTotal	 	= $old->total;

		$newQuantity 	= $oldQuantity - $quantity;
		$newTotal 		= $oldTotal - $total;

		if ($oldPrice == $price) {
			$newPrice = $oldPrice;
		} else {
			$newPrice = $newTotal / $newQuantity;
		}

		$preparedUpdatedStock->execute([
			'quantity' 			=> $quantity,
			'supplier_price' 	=> $newPrice,
			'product_id' 		=> $updateID,
		]);

		$preparedUpdatedCalgary->execute([
			'quantity' 			=> $quantity,
			'product_id' 		=> $updateID,
		]);
	}

	//	delete from invoice_products
	$sql = "
		DELETE
		FROM
			invoice_products
		WHERE
			invoice_id = :invoice_id
		";

	$delete = $db->deleteRow($sql, ['invoice_id' => $invoiceID]);

	/*------------ add new products and adjust stock and balance again ------------*/

	$sql = "INSERT INTO
		invoice_products (
			invoice_id,
			product_id,
			quantity,
			price
		) VALUES (
			:invoice_id,
			:product_id,
			:quantity,
			:price
		)";

	$insertInvoiceProducts = $db->prepare($sql);

	foreach ($products as $product) {

		$product_id = $product['productID'];
		$quantity 	= $product['quantity'];
		$price 		= $product['price'];
		$total  	= $quantity * $price;

		$insertInvoiceProducts->execute([
			'invoice_id' => $invoiceID,
			'product_id' => $product_id,
			'quantity' 	 => $quantity,
			'price'		 => $price
		]);

		//	adjust stock
		$getSQL = "SELECT quantity, supplier_price, quantity * supplier_price AS total FROM stock WHERE product_id = :product_id";

		$productInfo = $db->getRow($getSQL, ['product_id' => $product_id]);

		$oldPrice 	 	= $productInfo->supplier_price;
		$oldQuantity	= $productInfo->quantity;
		$oldTotal		= $productInfo->total;

		$newQuantity 	= $oldQuantity + $quantity;
		$newTotal 		= $total + $oldTotal;

		if ($oldPrice == $price) {
			$supplier_price = $price;
		} else {
			$supplier_price = $newTotal / $newQuantity;
		}

		$updateStockSQL = "
			UPDATE
				stock
			SET
				quantity 		= quantity + :quantity,
				supplier_price 	= :supplier_price
			WHERE
				product_id 		= :product_id
			";

		$updateStock = $db->updateRow($updateStockSQL, [
			'product_id' 		=> $product_id,
			'quantity' 			=> $quantity,
			'supplier_price' 	=> $supplier_price
		]);

		$updateCalgaryInventorySQL = "
			UPDATE
				calgary
			SET
				quantity 	= quantity + :quantity
			WHERE
				product_id 	= :product_id
			";

		$updateCalgaryInventory = $db->updateRow($updateCalgaryInventorySQL, [
			'product_id' 		=> $product_id,
			'quantity' 			=> $quantity,
		]);
	}

	//	adjust supplier balance
	$sql = "UPDATE suppliers SET balance = balance + :amount WHERE id = :id";
	$updateSupplierBalance = $db->updateRow($sql, [
		'amount' 	=> $invoiceTotal,
		'id' 		=> $supplierID,
	]);

	if (!$updateSupplierBalance) {
		$db->rollBack();
		die('Invoice did not update.');
	}

	$db->commit();
	$msgHTML = '
		<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
			<i style="color: green" class="fas fa-check-circle"></i> Updated invoice ID ' . $invoiceID . '.
		</div>';

	die($msgHTML);
}

if (isset($_POST['getNewSupplierPrices'])) {

	//	products for supplier
	$sql = "
		SELECT
			products.id,
			products.name,
			stock.supplier_price AS price
		FROM
			products
		JOIN
			stock
		ON
			stock.product_id = products.id
		";

	$supplierProducts = $db->getRows($sql);

	$productsForSupplierHTML = '';
	$productsForSupplierHTML .= '<option data-price="0" value="0">Please Select</option>';

	foreach ($supplierProducts as $supplierProduct) {
		$productsForSupplierHTML .= '<option data-price="' . $supplierProduct->price . '" value="' . $supplierProduct->id . '">' . $supplierProduct->name . '</option>';
	}

	die(json_encode($productsForSupplierHTML, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}
//	edit supplier payment
if (isset($_POST['editSupplierPayment'])) {

	$paymentID 	= $_POST['id'];
	$supplierID = $_POST['supplier'];
	$amount 	= $_POST['amount'];
	$date 		= $_POST['date'];

	//	check if date format is valid
	if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date)) {

		$response = [];
		$response['ok'] 	= 1;
		$response['html'] 	= '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Date format: ' . h($date) . ' is not valid!
			</div>';
		die(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
	}

	list($y, $m, $d) = array_pad(explode('-', $date, 3), 3, 0);
	$checkDate = ctype_digit("$y$m$d") && checkdate($m, $d, $y);

	//	check if date is valid
	if (!$checkDate) {
		$response = [];
		$response['ok'] 	= 1;
		$response['html'] 	= '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> ' . h($date) . ' is not a valid date!
			</div>';

		die(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
	}

	$db->beginTransaction();

	$sql = "
		SELECT
			id,
			type,
			supplier_id,
			user_id,
			amount,
			date
		FROM
			payments
		WHERE
			id = :id
		";

	$oldPaymentInfo = $db->getRow($sql, ['id' => $paymentID]);

	//	adjust old supplier balance before updating payment
	$sql = "
		UPDATE
			suppliers
		SET
			balance = balance + :balance
		WHERE
			id = :id
		";

	$updateSupplierBalance = $db->updateRow($sql, [
		'id' 		=> $oldPaymentInfo->supplier_id,
		'balance' 	=> $oldPaymentInfo->amount,
	]);

	//	update payment
	$sql = "
		UPDATE
			payments
		SET
			supplier_id = :supplier_id,
			amount 		= :amount,
			date 		= :date
		WHERE
			id 			= :id
		";

	$update = $db->updateRow($sql, [
		'supplier_id'	=> $supplierID,
		'amount'		=> $amount,
		'date'			=> $date,
		'id'			=> $paymentID,
	]);

	//	update new supplier balance
	$sql = "
		UPDATE
			suppliers
		SET
			balance = balance - :balance
		WHERE
			id = :id
		";

	$db->updateRow($sql, [
		'balance' 	=> $amount,
		'id' 		=> $supplierID,
	]);

	$db->commit();

	$response = [];
	$response['ok'] 	= 1;
	$response['html'] 	= '
		<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
			<i style="color: green" class="fas fa-check-circle"></i> Payment updated!
		</div>';

	die(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}
// 	delete supplier payment
if (isset($_POST['deleteSupplierPayment'])) {

	$paymentID 	= $_POST['id'];

	$db->beginTransaction();

	$sql = "
		SELECT
			id,
			type,
			supplier_id,
			user_id,
			amount,
			date
		FROM
			payments
		WHERE
			id = :id
		";

	$oldPaymentInfo = $db->getRow($sql, ['id' => $paymentID]);

	//	adjust old supplier balance before deleting payment
	$sql = "
		UPDATE
			suppliers
		SET
			balance = balance + :balance
		WHERE
			id = :id
		";

	$updateSupplierBalance = $db->updateRow($sql, [
		'id' 		=> $oldPaymentInfo->supplier_id,
		'balance' 	=> $oldPaymentInfo->amount,
	]);

	//	delete payment
	$sql = "
		DELETE
		FROM
			payments
		WHERE
			id = :id
		";

	$delete = $db->deleteRow($sql, ['id' => $paymentID]);

	$db->commit();

	$response = [];
	$response['ok'] 	= 1;
	$response['html'] 	= '
		<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
			<i style="color: green" class="fas fa-check-circle"></i> Payment deleted!
		</div>';

	die(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}

// 	delete supplier invoice
if (isset($_POST['deleteSupplierInvoice'])) {

	$invoiceID 	= $_POST['id'];

	$db->beginTransaction();

	$sql = "
		SELECT
			supplier_id,
			amount
		FROM
			invoices
		WHERE
			id = :id
		";

	$oldInvoiceInfo = $db->getRow($sql, ['id' => $invoiceID]);

	$supplierID 	= $oldInvoiceInfo->supplier_id;
	$amount	 		= $oldInvoiceInfo->amount;
	$return 		= ($amount * 1) < 0 ? true : false;

	//	update supplier balance
	$updateBalanceSQL = "
		UPDATE
			suppliers
		SET
			balance = balance - :amount
		WHERE
			id = :id
		";

	$updateBalance = $db->updateRow($updateBalanceSQL, [
		'amount' 	=> $amount,
		'id' 		=> $supplierID
	]);

	if (!$updateBalance) {

		$db->rollBack();

		$response = [];
		$response['ok'] 	= 1;
		$response['html'] 	= '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Balance update failed.
			</div>';

		die(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
	}

	//	get products to ajdust
	$sql = "
		SELECT
			quantity,
			price,
			quantity * price AS total,
			product_id
		FROM
			invoice_products
		WHERE
			invoice_id = :invoice_id
		";

	$productsToAdjust = $db->getRows($sql, ['invoice_id' => $invoiceID]);

	//	prepare old product info SQL
	$selectOldStockSQL = "
		SELECT
			quantity,
			supplier_price,
			quantity * supplier_price AS total
		FROM
			stock
		WHERE
			product_id = :product_id
		";

	//	prepare stock update sql
	$updateStockSQL = "
		UPDATE
			stock
		SET
			quantity 		= quantity - :quantity,
			supplier_price 	= :supplier_price
		WHERE
			product_id 		= :product_id
		";

	$preparedUpdatedStock = $db->prepare($updateStockSQL);

	//	prepare calgary update sql
	$updateCalgarySQL = "
		UPDATE
			calgary
		SET
			quantity 		= quantity - :quantity
		WHERE
			product_id 		= :product_id
		";

	$preparedUpdatedCalgary = $db->prepare($updateCalgarySQL);

	foreach ($productsToAdjust as $productToAdjust) {

		$updateID 	= $productToAdjust->product_id;
		$quantity 	= $productToAdjust->quantity;

		if ($return) {
			$quantity = $quantity * -1;
		}

		$price 		= $productToAdjust->price;
		$total      = $productToAdjust->total;

		//	get current info
		$old = $db->getRow($selectOldStockSQL, ['product_id' => $updateID]);

		$oldPrice 		= $old->supplier_price;
		$oldQuantity 	= $old->quantity;
		$oldTotal	 	= $old->total;

		$newQuantity 	= $oldQuantity - $quantity;
		$newTotal 		= $oldTotal - $total;

		if ($oldPrice == $price) {
			$newPrice = $oldPrice;
		} else {
			$newPrice = $newTotal / $newQuantity;
		}

		$preparedUpdatedStock->execute([
			'quantity' 			=> $quantity,
			'supplier_price' 	=> $newPrice,
			'product_id' 		=> $updateID,
		]);

		$preparedUpdatedCalgary->execute([
			'quantity' 			=> $quantity,
			'product_id' 		=> $updateID,
		]);
	}

	//	delete from invoice_products
	$sql = "
		DELETE
		FROM
			invoice_products
		WHERE
			invoice_id = :invoice_id
		";

	$deleteInvoiceProducts = $db->deleteRow($sql, ['invoice_id' => $invoiceID]);

	//	delete invoice
	$sql = "
		DELETE
		FROM
			invoices
		WHERE
			id = :invoice_id
		";

	$deleteInvoice = $db->deleteRow($sql, ['invoice_id' => $invoiceID]);

	if (!$deleteInvoiceProducts) {

		$db->rollBack();

		$response = [];
		$response['ok'] 	= 1;
		$response['html'] 	= '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Failed to delete invoice ID: ' . $invoiceID . '
			</div>';

		die(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
	}

	if (!$deleteInvoice) {

		$db->rollBack();

		$response = [];
		$response['ok'] 	= 1;
		$response['html'] 	= '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Failed to delete invoice ID: ' . $invoiceID . '
			</div>';

		die(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
	}

	$db->commit();

	$response = [];
	$response['ok'] 	= 1;
	$response['html'] 	= '
		<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
			<i style="color: green" class="fas fa-check-circle"></i> Invoice deleted!
		</div>';

	die(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}
