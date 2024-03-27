<?php
//	customers table
if (isset($_POST['getCustomersList'])) {

	$id = $_SESSION['userID'];

	$params = $columns = $totalRecords = $data = [];
	$params = $_POST;
	$active = isset($params['active']) ? intval($params['active']) : 1; 

	$columns = [
		0 => 'username',
		1 => 'balance',
		// 2 => 'inventory',
		3 => 'details',
	];

	$where_condition = '';

	$sql = $sqlRec = "
		SELECT
			id,
			username,
			balance,
			inventory
		FROM
			users
		WHERE
			role = 2
		AND 
			active = $active	
		";
	// echo $sql;die;
	if (!empty($params['search']['value'])) {
		$where_condition .=	" AND ";
		$where_condition .= " username LIKE '%" . $params['search']['value'] . "%' ";
	}

	if (isset($where_condition) && $where_condition != '') {
		$sqlRec .= $where_condition;
	}

	$sqlRec .=  " ORDER BY " . $columns[$params['order'][0]['column']] . "   " . $params['order'][0]['dir'] . "  LIMIT " . $params['start'] . " ," . $params['length'] . " ";

	$totalRecords = $db->getColumn("SELECT COUNT(id) FROM users WHERE role = 2 AND active = $active");
	$queryRecords = $db->getRows($sqlRec);

	$data = [];

	foreach ($queryRecords as $row) {

		$details = '<button class="customer_balance_details" data-id="' . $row->id . '">Invoices</button>';
		$balance = '$' . number_format($row->balance, 2);

		// $inventory = $row->inventory ? 'Calgary' : 'Edmonton';

		$data[] = [
			$row->username,
			$balance,
			// $inventory,
			$details,
		];
	}

	$sqlTotal 	= "SELECT SUM(balance) FROM users WHERE role = 2 AND active = $active";
	$total 		= $db->getColumn($sqlTotal);
	$total 		= '$' . number_format($total, 2);

	$json = [
		"draw"            	=> intval($params['draw']),
		"recordsTotal"    	=> intval($totalRecords),
		"recordsFiltered"	=> intval($totalRecords),
		"data"            	=> $data,
		"total"				=> $total
	];

	die(json_encode($json, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}

//	customer balance table
if (isset($_POST['getAllCustomerInvoices'])) {

	$params = $columns = $totalRecords = $data = [];
	$params = $_POST;
	$id 	= $_POST['id'];

	$columns = [
		0 => 'id',
		1 => 'amount',
		2 => 'date',
		3 => 'details',
	];

	$where_condition = '';

	$sql = $sqlRec = "
		SELECT
			i.id,
			i.amount AS total,
			i.date,
			u.username
		FROM
			invoices as i
		JOIN
			users AS u
		ON
			i.user_id = u.id
		WHERE
			i.user_id = :id
		AND
			i.type = 1
		";

	if (!empty($params['search']['value'])) {
		$where_condition .=	" AND ";
		$where_condition .= "( i.id LIKE '%" . $params['search']['value'] . "%' ";
		$where_condition .= " OR i.amount LIKE '%" . $params['search']['value'] . "%' ";
		$where_condition .= " OR i.date LIKE '%" . $params['search']['value'] . "%' )";
	}

	if (isset($where_condition) && $where_condition != '') {
		$sqlRec .= $where_condition;
	}

	$sqlRec .=  " ORDER BY " . $columns[$params['order'][0]['column']] . "   " . $params['order'][0]['dir'] . "  LIMIT " . $params['start'] . " ," . $params['length'] . " ";

	$totalRecords = $db->getColumn("SELECT COUNT(id) FROM invoices WHERE type = 1 AND user_id = :user_id", ['user_id' => $id]);
	$queryRecords = $db->getRows($sqlRec, ['id' => $id]);

	$data 		= [];

	$username = $db->getColumn("SELECT username FROM users WHERE id = :id", ['id' => $id]);

	foreach ($queryRecords as $row) {

		$total 		= '$' . number_format($row->total, 2);
		$showDate 	= date('m-d-Y', strtotime($row->date));

		$data[] = [
			$row->id,
			$total,
			$showDate,
			'<button class="buyer_invoice_details" data-date="' . $showDate . '" data-total="' . $row->total . '" data-id="' . $row->id . '" data-user="' . $row->username . '">Details</button>'
		];
	}

	$json = [
		"draw"            => intval($params['draw']),
		"recordsTotal"    => intval($totalRecords),
		"recordsFiltered" => intval($totalRecords),
		"data"            => $data,
		"username"		  => $username
	];

	die(json_encode($json, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}

//	customer invoices table
if (isset($_POST['getCustomerInvoices'])) {

	$params = $columns = $totalRecords = $data = [];
	$params = $_POST;

	$columns = [
		0 => 'id',
		1 => 'user',
		2 => 'total',
		3 => 'status',
		4 => 'date',
		5 => 'details',
		6 => 'complete',
		7 => 'edit',
		8 => 'delete',
	];

	$where_condition = '';

	$sql = $sqlRec = "
		SELECT
			i.id as id,
			i.status_id as statusID,
			u.username as user,
			i.amount as total,
			ist.status as status,
			i.user_id as userid,
			i.date as date,
			i.inventory as inventory 
		FROM
			invoices as i
		JOIN
			users AS u
		ON
			i.user_id = u.id
		JOIN
			invoice_status AS ist
		ON
			i.status_id = ist.id
		WHERE
			i.type = 1
		";

	if (!empty($params['search']['value'])) {
		$where_condition .=	" AND ";
		$where_condition .= "( u.username LIKE '%" . $params['search']['value'] . "%' ";
		$where_condition .= " OR i.date LIKE '%" . $params['search']['value'] . "%' ";
		$where_condition .= " OR ist.status LIKE '%" . $params['search']['value'] . "%' )";
	}

	if (isset($where_condition) && $where_condition != '') {
		$sqlRec .= $where_condition;
	}

	$sqlRec .=  " ORDER BY " . $columns[$params['order'][0]['column']] . "   " . $params['order'][0]['dir'] . "  LIMIT " . $params['start'] . " ," . $params['length'] . " ";

	$totalRecords = $db->getColumn("SELECT COUNT(id) FROM invoices WHERE type = 1");
	$queryRecords = $db->getRows($sqlRec);

	$data = [];
	$i = 1;
	foreach ($queryRecords as $row) {

		$disabledComplete 	= $row->statusID == 3 ? 'disabled' : '';
		$disabledEdit 		= $row->statusID == 3 ? 'disabled' : '';
		$disabledDelete  	= $disabledEdit;

		$total 		= '$' . number_format($row->total, 2);
		$showDate 	= date('m-d-Y', strtotime($row->date));

		if ($disabledDelete == '' && $row->total * 1 < 0) {
			$disabledEdit = 'disabled';
		}
		if ($disabledComplete != '') {
			$disabledEdit = 'disabled';
		}
		$data[] = [
			$i,
			$row->user,
			$total,
			$row->status,
			$showDate,
			'<button class="buyer_invoice_details" data-date="' . $showDate . '" data-total="' . $row->total . '" data-user="' . $row->user . '" data-id="' . $row->id . '">Details</button>',
			'<button ' . $disabledComplete . ' class="buyer_invoice_status" data-inventory="' . $row->inventory . '" data-user-id="' . $row->userid . '" data-total="' . $row->total . '" data-id="' . $row->id . '">Complete</button>',
			'<button   class="edit_customer_invoice" data-inventory="' . $row->inventory . '" data-date="' . $row->date . '" data-total="' . $row->total . '" data-customer="' . $row->userid . '" data-id="' . $row->id . '">Edit</button>',
			'<button  class="delete_customer_invoice" data-customer="' . $row->userid . '" data-id="' . $row->id . '">Delete</button>',
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

//	customers payments table
if (isset($_POST['getCustomerPayments'])) {

	$params = $columns = $totalRecords = $data = [];
	$params = $_POST;

	$columns = [
		0 => 'id',
		1 => 'customer',
		2 => 'amount',
		3 => 'date',
		4 => 'status',
	];

	$where_condition = '';

	$sql = $sqlRec = "
		SELECT
			p.id,
			u.username AS customer,
			u.id AS customerID,
			p.amount,
			p.date,
			p.status,
			p.user_id
		FROM
			payments as p
		JOIN
			users as u
		ON
			p.user_id = u.id
		WHERE
			p.type = 1
		";

	if (!empty($params['search']['value'])) {
		$where_condition .=	" AND ";
		$where_condition .= "( p.id LIKE '%" . $params['search']['value'] . "%' ";
		$where_condition .= " OR p.amount LIKE '%" . $params['search']['value'] . "%' ";
		$where_condition .= " OR u.username LIKE '%" . $params['search']['value'] . "%' ";
		$where_condition .= " OR p.date LIKE '%" . $params['search']['value'] . "%' )";
	}

	if (isset($where_condition) && $where_condition != '') {
		$sqlRec .= $where_condition;
	}

	$sqlRec .=  " ORDER BY " . $columns[$params['order'][0]['column']] . "   " . $params['order'][0]['dir'] . "  LIMIT " . $params['start'] . " ," . $params['length'] . " ";

	$totalRecords = $db->getColumn("SELECT COUNT(id) FROM payments WHERE type = 1");
	$queryRecords = $db->getRows($sqlRec);

	$data = [];

	foreach ($queryRecords as $row) {

		$formattedAmount	= '$' . number_format($row->amount, 2);
		$showDate 			= date('m-d-Y', strtotime($row->date));
		$dateForFrontEnd	= date('Y-m-d', strtotime($row->date));
		$disabledComplete 	= $row->status == 3 ? ' disabled' : '';

		if ($row->status == 1) {
			$statusName = 'Processing';
		} elseif ($row->status == 2) {
			$statusName = 'Pending';
		} else {
			$statusName = 'Complete';
		}
		if ($disabledComplete != '') {
			$disabled = ' disabled';
		}
		$data[] = [
			$row->id,
			$row->customer,
			$formattedAmount,
			$showDate,
			$statusName,
			'<button ' . $disabledComplete . ' class="edit_payment_status"  data-amount="' . $row->amount . '" data-user-id="' . $row->customerID . '"  data-id="' . $row->id . '" >Complete</button>',
			'<button' . $disabledComplete . '  class="edit_payment_buttons" data-customer-id="' . $row->customerID . '" data-payment-id="' . $row->id . '" data-amount="' . $row->amount . '" data-date="' . $dateForFrontEnd . '">Edit</button>',
			'<button ' . $disabledComplete . '  class="delete_payment_buttons" data-customer-id="' . $row->customerID . '" data-payment-id="' . $row->id . '" data-amount="' . $row->amount . '">Delete</button>',
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

//	get customer invoice details for editing
if (isset($_POST['getCustomerInvoiceForEditing'])) {

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

	$rows = $db->getRows($sql, ['id' => $id]);

	$data = [];
	$data['products'] 	= $rows;

	$customerID = $db->getColumn("SELECT user_id FROM invoices WHERE id = :id", ['id' => $id]);

	$sql = "
		SELECT
			p.id,
			p.name,
			up.price AS price
		FROM
			products AS p
		JOIN
			users_prices AS up
		ON
			p.id = up.product_id
		WHERE
			up.user_id = :id
		";

	$products = $db->getRows($sql, ['id' => $customerID]);

	$productsForCustomerHTML = '';
	$productsForCustomerHTML .= '<option data-price="0" value="0">Please Select</option>';

	foreach ($products as $product) {
		$productsForCustomerHTML .= '<option data-price="' . $product->price . '" value="' . $product->id . '">' . $product->name . '</option>';
	}

	$data['pricesHTML'] = $productsForCustomerHTML;
	die(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}

//	customer invoice submit
if (isset($_POST['customerInvoiceSubmit'])) {

	$type 			= 1;
	$supplier_id 	= 0;
	$amount			= trim($_POST['total']);
	$status_id		= 1;
	$user_id 		= $_POST['customerID'] ?? $_SESSION['userID'];
	$date			= isset($_POST['date']) ? $_POST['date'] : date('Y-m-d', time());
	$month 			= substr($date, 5, 2);
	$year 			= substr($date, 0, 4);
	$note			= trim($_POST['note']);
	$inventory      = $_POST['inventory'];
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

	//	invoices
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
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Failed to place an order!
			</div>';

		die(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
	}

	//	invoice_products and adjust stock (reserved)
	$invoice_id = $db->getLastID();

	$sql = "INSERT INTO

		invoice_products (
			invoice_id,
			product_id,
			quantity,
			price,
			supplier_price
		) VALUES (
			:invoice_id,
			:product_id,
			:quantity,
			:price,
			:supplier_price
		)";

	$updateStockSQL = "
		UPDATE
			stock
		SET
			quantity_reserved = quantity_reserved + :quantity_reserved
		WHERE
			product_id = :product_id
		";

	// $inventory = $db->getColumn("SELECT inventory FROM users WHERE id = :id", ['id' => $user_id]);
	// $inventory = $inventory ? 'calgary' : 'edmonton';
	$inventory = $_POST['inventory'];
	if (isset($inventory)) {
		$updateInventorySQL = "
		UPDATE
			$inventory
		SET
			quantity_reserved = quantity_reserved + :quantity_reserved
		WHERE
			product_id = :product_id
		";
	}


	$insertInvoiceProducts 	= $db->prepare($sql);
	$adjustStock 			= $db->prepare($updateStockSQL);
	if (isset($inventory)) {
		$adjustInventory 		= $db->prepare($updateInventorySQL);
	}

	foreach ($_POST['products'] as $product) {

		$product_id = $product['productID'];
		$quantity 	= $product['quantity'];
		$price 		= $product['price'];

		$supplierPriceSQL 	= "SELECT supplier_price FROM stock WHERE product_id = :product_id";
		$supplierPrice 		= $db->getColumn($supplierPriceSQL, ['product_id' => $product_id]);

		$insertInvoiceProducts->execute([
			'invoice_id' 		=> $invoice_id,
			'product_id' 		=> $product_id,
			'quantity' 	 		=> $quantity,
			'price' 	 		=> $price,
			'supplier_price' 	=> $supplierPrice,
		]);

		$adjustStock->execute([
			'quantity_reserved' => $quantity,
			'product_id' 		=> $product_id
		]);
		if (isset($adjustInventory)) {
			$adjustInventory->execute([
				'quantity_reserved' => $quantity,
				'product_id' 		=> $product_id
			]);
		}
	}

	$db->commit();

	$response = [];
	$response['ok'] 	= 1;
	$response['html'] 	= '
		<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
			<i style="color: green" class="fas fa-check-circle"></i> Order placed!
		</div>';

	die(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}

//	my orders table
if (isset($_POST['getMyOrders'])) {

	$params = $columns = $totalRecords = $data = [];
	$params = $_POST;
	$id 	= $_SESSION['userID'];

	$columns = [
		0 => 'id',
		1 => 'total',
		2 => 'status',
		3 => 'date',
		4 => 'details',
	];

	$where_condition = '';

	$sql = $sqlRec = "
		SELECT
			i.id as id,
			i.amount as total,
			ist.status as status,
			i.date as date
		FROM
			invoices as i
		JOIN
			invoice_status AS ist
		ON
			i.status_id = ist.id
		WHERE
			i.type = 1
		AND
			i.user_id = :user_id
		";

	if (!empty($params['search']['value'])) {
		$where_condition .=	" AND ";
		$where_condition .= "( i.amount LIKE '%" . $params['search']['value'] . "%' ";
		$where_condition .= " OR ist.status LIKE '%" . $params['search']['value'] . "%' ";
		$where_condition .= " OR i.date LIKE '%" . $params['search']['value'] . "%' )";
	}

	if (isset($where_condition) && $where_condition != '') {
		$sqlRec .= $where_condition;
	}

	$sqlRec .=  " ORDER BY " . $columns[$params['order'][0]['column']] . "   " . $params['order'][0]['dir'] . "  LIMIT " . $params['start'] . " ," . $params['length'] . " ";

	$totalRecords = $db->getColumn("SELECT COUNT(id) FROM invoices WHERE type = 1 AND user_id = :user_id", ['user_id' => $id]);
	$queryRecords = $db->getRows($sqlRec, ['user_id' => $id]);

	$data = [];

	foreach ($queryRecords as $row) {

		$total = '$' . number_format($row->total, 2);

		$data[] = [
			$row->id,
			$total,
			$row->status,
			$row->date,
			'<button class="details_buttons" data-date="' . $row->date . '" data-total="' . $row->total . '" data-id="' . $row->id . '">Details</button>'
		];
	}

	$balanceSQL = "SELECT SUM(balance) FROM users WHERE id = :id";
	$balance 	= $db->getColumn($balanceSQL, ['id' => $_SESSION['userID']]);
	$balance 	= '$' . number_format($balance, 2);

	$sqlTotal 	= "SELECT SUM(amount) FROM invoices WHERE user_id = :id AND status_id = 3";
	$total 		= $db->getColumn($sqlTotal, ['id' => $_SESSION['userID']]);
	$total 		= '$' . number_format($total, 2);

	$json = [
		"draw"            => intval($params['draw']),
		"recordsTotal"    => intval($totalRecords),
		"recordsFiltered" => intval($totalRecords),
		"data"            => $data,
		"total"			  => $total,
		"balance"		  => $balance,
	];

	die(json_encode($json, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}

if (isset($_POST['getMyPayments'])) {
	
	$params = $columns = $totalRecords = $data = [];
	$params = $_POST;
	$id 	= $_SESSION['userID'];

	$columns = [
		0 => 'id',
		1 => 'amount',
		2 => 'date',
		3 => 'status',
	];

	$where_condition = '';

	$where_condition = '';

	$sqlRec = "
		SELECT
			p.id,
			u.username AS customer,
			u.id AS customerID,
			p.amount,
			p.date,
			p.status,
			p.user_id
		FROM
			payments as p
		JOIN
			users as u
		ON
			p.user_id = u.id
		WHERE
			p.type = 1
		AND
			p.user_id = :user_id
		";
		

		if (!empty($params['search']['value'])) {
			$where_condition .=	" AND ";
			$where_condition .= "( p.id LIKE '%" . $params['search']['value'] . "%' ";
			$where_condition .= " OR p.amount LIKE '%" . $params['search']['value'] . "%' ";
			$where_condition .= " OR u.username LIKE '%" . $params['search']['value'] . "%' ";
			$where_condition .= " OR p.date LIKE '%" . $params['search']['value'] . "%' )";
		}

	if (isset($where_condition) && $where_condition != '') {
		$sqlRec .= $where_condition;
	}

	$sqlRec .=  " ORDER BY " . $columns[$params['order'][0]['column']] . "   " . $params['order'][0]['dir'] . "  LIMIT " . $params['start'] . " ," . $params['length'] . " ";
	
	$data = [];
	$totalRecords = $db->getColumn("SELECT COUNT(id) FROM payments WHERE type = 1 AND user_id = :user_id", ['user_id' => $id]);
	$sqlTotal 	= "SELECT SUM(amount) FROM payments WHERE user_id = :id AND status = 3";
	$total 		= $db->getColumn($sqlTotal, ['id' => $_SESSION['userID']]);
	$total 		= '$' . number_format($total, 2);
	$queryRecords = $db->getRows($sqlRec, ['user_id' => $id]);
	$data = [];

	foreach ($queryRecords as $row) {

		$formattedAmount	= '$' . number_format($row->amount, 2);
		$showDate 			= date('m-d-Y', strtotime($row->date));

		if ($row->status == 1) {
			$statusName = 'Processing';
		} elseif ($row->status == 2) {
			$statusName = 'Pending';
		} else {
			$statusName = 'Complete';
		}

		$data[] = [
			$row->id,
			$formattedAmount,
			$showDate,
			$statusName,
		];
	}

	$json = [
		"draw"            => intval($params['draw']),
		"recordsTotal"    => intval($totalRecords),
		"recordsFiltered" => intval($totalRecords),
		"data"            => $data,
		"total"			  => $total 	
	];

	die(json_encode($json, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}

//	Buyer invoice details
if (isset($_POST['buyerInvoiceDetails'])) {

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

	$data = $db->getRows($sql, ['id' => $id]);
	$json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
	die($json);
}
//	Change buyer invoice status to Complete
if (isset($_POST["buyerInvoiceComplete"])) {
	$id 		= $_POST['id'];
	$total		= $_POST['total'];
	$userID		= $_POST['userID'];
	$inventory	= $_POST['inventory'];

	$db->beginTransaction();

	//	invoice status
	$sql 				= "UPDATE invoices SET status_id = 3 WHERE id = :id";
	$update 			= $db->updateRow($sql, ['id' => $id]);

	//	stock
	$sql 				= "SELECT product_id, quantity FROM invoice_products WHERE invoice_id = :id";
	$products 			= $db->getRows($sql, ['id' => $id]);

	$updateStockSql 	= "UPDATE stock SET quantity = quantity - :quantity1, quantity_reserved = quantity_reserved - :quantity2 WHERE product_id = :product_id";
	$updateStock 		= $db->prepare($updateStockSql);

	// $inventory = $db->getColumn("SELECT inventory FROM users WHERE id = :id", ['id' => $userID]);
	// $inventory = $inventory ? 'calgary' : 'edmonton';

	$updateInventorySql = "UPDATE $inventory SET quantity = quantity - :quantity1, quantity_reserved = quantity_reserved - :quantity2 WHERE product_id = :product_id";
	// echo $updateInventorySql;
	// die;
	$updateInventory 	= $db->prepare($updateInventorySql);

	foreach ($products as $product) {

		$updateStock->execute([
			'quantity1' 		=> $product->quantity,
			'quantity2' 		=> $product->quantity,
			'product_id' 		=> $product->product_id
		]);

		$updateInventory->execute([
			'quantity1' 		=> $product->quantity,
			'quantity2' 		=> $product->quantity,
			'product_id' 		=> $product->product_id
		]);
	}

	//	user balance
	$sql 		= "UPDATE users SET balance = balance + :total WHERE id = :user_id";
	$update2 	= $db->updateRow($sql, [
		'total' 	=> $total,
		'user_id'	=> $userID
	]);
	// print_r($update2);
	// die('yes');
	if (!($update && $update2)) {
		$db->rollBack();
		die('0');
	} else {
		$db->commit();
		die('1');
	}
}
if (isset($_POST['checkInventoryQuantity'])) {
	$fromtable 	= $_POST['inventory'];
	$qty 	= $_POST['quantity'];
	$productID 	= $_POST['productID'];

	$db->beginTransaction();
	// Check if the quantity to transfer exceeds the available quantity
	$sqlForAvailableCurrentqtyquery = "SELECT quantity FROM $fromtable WHERE product_id = $productID";

	$currentFromQuantity = $db->getColumn($sqlForAvailableCurrentqtyquery);

	if ($currentFromQuantity < $qty) {
		$db->rollBack();
		$response['ok'] 	= 0;
		$response['html']  = '
            <div style="width: 300px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
                <i style="color: red" class="fas fa-exclamation-triangle"></i> Quantity exceeds available quantity. Available quantity: ' . $currentFromQuantity . ' units.
            </div>';
		die(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
	} else {
		$response['ok'] 	= 1;


		die(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
	}
}
if (isset($_POST["supplierInvoiceComplete"])) {
	$id 		 = $_POST['id'];
	$total		 = $_POST['total'];
	$supplier_id = $_POST['userID'];
	$inventory	 = $_POST['inventory'];

	$db->beginTransaction();

	//	invoice status
	$sql 				= "UPDATE invoices SET status_id = 3 WHERE id = :id";
	$update 			= $db->updateRow($sql, ['id' => $id]);
	// Retrieve products and quantities from the invoice
	$sql        = "SELECT * FROM invoices WHERE id = :id";
	$totalAmount   = $db->getRow($sql, ['id' => $id]);
	$sql = "UPDATE suppliers SET balance = balance + :amount WHERE id = :id";
	$updateSupplierBalance = $db->updateRow($sql, [
		'amount' 	=> $totalAmount->amount,
		'id' 		=> $supplier_id,
	]);
	// print_r($totalAmount);die;
	$sql        = "SELECT * FROM invoice_products WHERE invoice_id = :id";
	$products   = $db->getRows($sql, ['id' => $id]);
	//	adjust supplier balance
	
	foreach ($products as $product) {

		$product_id = $product->product_id;
		$quantity 	= $product->quantity;
		$price 		= $product->price;
		$total  	= $quantity * $price;



		//	get current stock info
		$getSQL = "
			SELECT
				quantity,
				supplier_price,
				quantity * supplier_price AS total
			FROM
				stock
			WHERE
				product_id = :product_id
			";

		$productInfo = $db->getRow($getSQL, ['product_id' => $product_id]);

		$oldPrice 	 = $productInfo->supplier_price;
		$oldQuantity = $productInfo->quantity;
		$oldTotal	 = $productInfo->total;

		$newQuantity = $oldQuantity + $quantity;
		$newTotal 	 = $oldTotal + $total;

		if ($oldPrice == $price) {
			$supplier_price = $price;
		} else {
			$supplier_price = $newTotal / $newQuantity;
		}

		//	update stock
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
			'supplier_price' 	=> $supplier_price,
		]);


		//	update clagary inventory
		if (isset($inventory) && $inventory == 'calgary') {
			$updateCalgarySQL = "
			UPDATE
				 calgary
			SET
				quantity 		= quantity + :quantity
			WHERE
				product_id 		= :product_id
			";

			$updateCalgary = $db->updateRow($updateCalgarySQL, [
				'product_id' 		=> $product_id,
				'quantity' 			=> $quantity,
			]);
		}
		if (isset($inventory)  && $inventory == 'edmonton') {
			$updateEdmontonSQL = "
			UPDATE
				 edmonton
			SET
				quantity 		= quantity + :quantity
			WHERE
				product_id 		= :product_id
			";

			$updateEdmonton = $db->updateRow($updateEdmontonSQL, [
				'product_id' 		=> $product_id,
				'quantity' 			=> $quantity,
			]);
		}
		if (isset($inventory)  && $inventory == 'toronto') {
			$updateTorontoSQL = "
			UPDATE
				toronto
			SET
				quantity 		= quantity + :quantity
			WHERE
				product_id 		= :product_id
			";

			$updateToronto = $db->updateRow($updateTorontoSQL, [
				'product_id' 		=> $product_id,
				'quantity' 			=> $quantity,
			]);
		}
		if (isset($inventory)   && $inventory == 'kelowna') {
			$updateKelownaSQL = "
			UPDATE
				kelowna
			SET
				quantity 		= quantity + :quantity
			WHERE
				product_id 		= :product_id
			";

			$updateKelowna = $db->updateRow($updateKelownaSQL, [
				'product_id' 		=> $product_id,
				'quantity' 			=> $quantity,
			]);
		}
	}

	if (!($update)) {
		$db->rollBack();
		die('0');
	} else {
		$db->commit();
		die('1');
	}
}
if (isset($_POST["supplierPaymentComplete"])) {

	$id 		 = $_POST['id'];
	$amount		 = $_POST['amount'];
	$supplier_id = $_POST['userID'];

	$db->beginTransaction();

	//	invoice status
	$sql 				= "UPDATE payments SET status = 3 WHERE id = :id";
	$update 			= $db->updateRow($sql, ['id' => $id]);

	$sql = "UPDATE suppliers SET balance = balance - :amount WHERE id = :id";

	$update = $db->updateRow($sql, [
		'amount' 	=> $amount,
		'id' 		=> $supplier_id,
	]);

	if (!($update)) {
		$db->rollBack();
		die('0');
	} else {
		$db->commit();
		die('1');
	}
}

if (isset($_POST["customerPaymentComplete"])) {

	$id 		= $_POST['id'];
	$amount		= $_POST['amount'];
	$userID		= $_POST['userID'];
	$sql = "UPDATE users SET balance = balance - :amount WHERE id = :id";

	$update = $db->updateRow($sql, [
		'amount' 	=> $amount,
		'id' 		=> $userID,
	]);
	$db->beginTransaction();

	//	invoice status
	$sql 				= "UPDATE payments SET status = 3 WHERE id = :id";
	$update 			= $db->updateRow($sql, ['id' => $id]);


	if (!($update)) {
		$db->rollBack();
		die('0');
	} else {
		$db->commit();
		die('1');
	}
}
if (isset($_POST['submitChangePassword'])) {

	$id 		= trim($_POST['userid']);
	$password 	= trim($_POST['password']);

	if ($id === '') {
		die('0');
	}

	$customerData = [
		'password' 	=> $password,
	];

	//	if empty password field remove this array element
	if (!$customerData['password']) {
		unset($customerData['password']);
	}

	$count 			= count($customerData);
	$params 		= [];
	$params['id'] 	= $id;
	$sql 			= "UPDATE users SET ";

	foreach ($customerData as $column => $value) {

		$count--;

		if ($column === 'password') {
			$value = password_hash($value, PASSWORD_DEFAULT);
		}

		$sql .= "$column = :$column";

		$params[$column] = $value;

		if ($count) {
			$sql .= ", ";
			continue;
		} else {
			break;
		}
	}
	$sql 		.= " WHERE id = :id";
	$update 	= $db->updateRow($sql, $params);

	if ($update) {
		$msgHTML = '
		<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
			<i style="color: green" class="fas fa-check-circle"></i> Password Updated Successfully
		</div>';
		die($msgHTML);
	} else {
		$msgHTML = '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Failed to Update the Password
			</div>';
		die($msgHTML);
	}
}


//	add new customer
if (isset($_POST['submitNewCustomer'])) {

	$customer 	= trim($_POST['customer']);
	// $inventory 	= trim($_POST['inventory']);
	$password   = trim($_POST['password']);
	$hash   	= password_hash($password, PASSWORD_DEFAULT);
	$balance    = trim($_POST['balance']);

	if (mb_strlen($password, 'utf8') < 8) {

		$msgHTML = '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Password needs to have at least 8 characters.
			</div>';

		die($msgHTML);
	}

	$db->beginTransaction();

	$exists = $db->getColumn("SELECT username FROM users WHERE role = 2 AND username = :username", ['username' => $customer]);

	if ($exists) {

		$db->rollBack();

		$msgHTML = '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Customer name already exists.
			</div>';

		die($msgHTML);
	}

	$sql = "INSERT INTO users (username, balance, password, role, email, active) VALUES (:username, :balance, :password, 2, '', 1)";
	$insert = $db->insertRow($sql, [
		'username' 	=> $customer,
		'password' 	=> $hash,
		// 'inventory' => $inventory,
		'balance' 	=> $balance,
	]);

	$user_id 	= $db->getLastID();
	$products 	= $db->getRows("SELECT id FROM products");

	//	adjust product prices for user
	if ($products) {
		$sql		= "INSERT INTO users_prices (user_id, product_id) VALUES (:user_id, :product_id)";
		$stmt 		= $db->prepare($sql);

		foreach ($products as $product) {

			$productID = $product->id;

			$stmt->execute([
				'user_id' 		=> $user_id,
				'product_id' 	=> $productID,
			]);
		}
	}

	$db->commit();
	$msgHTML = '
		<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
			<i style="color: green" class="fas fa-check-circle"></i> Added new customer:  ' . $customer . '
		</div>';

	die($msgHTML);
}

//	customer payment
if (isset($_POST['customerPayment'])) {

	$type  			= 1;
	$supplier_id 	= 0;
	$user_id 		= trim($_POST['customerID']);
	$amount 		= trim($_POST['amount']);
	$date 			= trim($_POST['date']);

	if ($amount === '' || $user_id === '' || $date === '') {

		$response = [];
		$response['ok'] 	= 1;
		$response['html'] 	= '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> All inputs required!
			</div>';

		die(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
	}

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

	$username = $db->getColumn("SELECT username FROM users WHERE id = :id", ['id' => $user_id]);

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
				<i style="color: green" class="fas fa-check-circle"></i> Payment for ' . $username . ' has been successfully processed.
			</div>';

		die(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
	} else {

		$db->rollBack();

		$response = [];
		$response['ok'] 	= 1;
		$response['html'] 	= '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Failed to record payment for ' . $username . '
			</div>';

		die(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
	}
}

//	get customers list for editing
if (isset($_POST['editCustomersList'])) {

	$params = $columns = $totalRecords = $data = [];
	$params = $_POST;

	$columns = [
		0 => 'id',
		1 => 'customer',
		// 2 => 'stock',
		3 => 'active',
		4 => 'update',
		5 => 'delete',
	];

	$where_condition = '';

	$sql = $sqlRec = "
		SELECT
			id,
			username AS customer,
			inventory,
			active
		FROM
			users
		WHERE
			role = 2
		";

	if (!empty($params['search']['value'])) {
		$where_condition .=	" AND ";
		$where_condition .= " username LIKE '%" . $params['search']['value'] . "%' ";
	}

	if (isset($where_condition) && $where_condition != '') {
		$sqlRec .= $where_condition;
	}

	$sqlRec .=  " ORDER BY " . $columns[$params['order'][0]['column']] . "   " . $params['order'][0]['dir'] . "  LIMIT " . $params['start'] . " ," . $params['length'] . " ";

	$totalRecords = $db->getColumn("SELECT COUNT(id) FROM users WHERE role = 2");
	$queryRecords = $db->getRows($sqlRec);

	$data = [];

	foreach ($queryRecords as $row) {

		$customer 	= '<input value="' . h($row->customer) . '">';
		$active 	= $row->active ? 'Yes' : 'No';
		$actString	= $row->active ? 'Deactivate' : 'Activate';

		$activeData = $row->active ? '0' : '1';

		$update 	= '<button class="update_customer" data-id="' . $row->id . '">Update</button>';
		$delete 	= '<button class="status_customer" data-active="' . $activeData . '"data-id="' . $row->id . '">' . $actString . '</button>';

		$inventory1 = $row->inventory == 1 ? 'selected' : '';
		$inventory2 = $row->inventory == 0 ? 'selected' : '';

		// $stock = '';
		// $stock .= '<select>';
		// $stock .= '<option value="1" ' . $inventory1 . '>Calgary</option>';
		// $stock .= '<option value="0" ' . $inventory2 . '>Edmonton</option>';
		// $stock .= '</select>';

		$passwordHTML = '<input type="text" placeholder="min. 8 characters">';

		$data[] = [
			$row->id,
			$customer,
			// $stock,
			$active,
			$passwordHTML,
			$update,
			$delete,
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

//	Update customer
if (isset($_POST['updateCustomer'])) {

	$id 		= trim($_POST['id']);
	$username 	= trim($_POST['username']);
	// $inventory 	= trim($_POST['inventory']);
	$password 	= trim($_POST['password']);

	if ($id === '' || $username === '') {
		die('0');
	}

	$customerData = [
		'username' 	=> $username,
		// 'inventory' => $inventory,
		'password' 	=> $password,
	];

	//	if empty password field remove this array element
	if (!$customerData['password']) {
		unset($customerData['password']);
	}

	$count 			= count($customerData);
	$params 		= [];
	$params['id'] 	= $id;
	$sql 			= "UPDATE users SET ";

	foreach ($customerData as $column => $value) {

		$count--;

		if ($column === 'password') {
			$value = password_hash($value, PASSWORD_DEFAULT);
		}

		$sql .= "$column = :$column";

		$params[$column] = $value;

		if ($count) {
			$sql .= ", ";
			continue;
		} else {
			break;
		}
	}

	$sql 		.= " WHERE id = :id";
	$update 	= $db->updateRow($sql, $params);

	if ($update) {
		die('1');
	} else {
		die('0');
	}
}

//	activate / deactivate customer
if (isset($_POST['activeCustomer'])) {

	$id 		= trim($_POST['id']);
	$active 	= trim($_POST['active']);

	if ($id === '' || $active === '') {
		die('0');
	}

	$update = $db->updateRow("Update users SET active = :active WHERE id = :id", [
		'id' 		=> $id,
		'active' 	=> $active,
	]);

	if ($update) {
		die('1');
	} else {
		die('0');
	}
}

//	edit customer invoice
if (isset($_POST['editCustomerInvoice'])) {

	$invoiceID		= $_POST['invoiceID'];
	$customerID 	= $_POST['customerID'];
	$date 			= $_POST['date'];
	$invoiceTotal	= $_POST['total'];
	$products		= $_POST['products'];
	$month 			= substr($date, 5, 2);
	$year 			= substr($date, 0, 4);
	$note 			= trim($_POST['note']);

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
		get old invoice total and customer id before updating
		in order to adjust balance for old (new) customer
	*/

	$oldInvoiceTotal = $db->getColumn("SELECT amount FROM invoices WHERE id = :id", ['id' => $invoiceID]);
	$oldCustomerID   = $db->getColumn("SELECT user_id FROM invoices WHERE id = :id", ['id' => $invoiceID]);

	//	invoices
	$sql = "
		UPDATE
			invoices
		SET
			user_id = :user_id,
			amount  = :amount,
			date    = :date,
			month   = :month,
			year    = :year,
			note	= :note
		WHERE
			id = :id
		";

	$update = $db->updateRow($sql, [
		'user_id' 	=> $customerID,
		'amount' 	=> $invoiceTotal,
		'date' 		=> $date,
		'id' 		=> $invoiceID,
		'month' 	=> $month,
		'year' 		=> $year,
		'note' 		=> $note,
	]);

	$updateBalanceSQL = "
		UPDATE
			users
		SET
			balance = balance - :amount
		WHERE
			id = :id
		";

	$updateBalance = $db->updateRow($updateBalanceSQL, [
		'amount' 	=> $oldInvoiceTotal,
		'id' 		=> $oldCustomerID
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
			product_id
		FROM
			invoice_products
		WHERE
			invoice_id = :invoice_id
		";

	$updateStockSQL = "
		UPDATE
			stock
		SET
			quantity = quantity + :quantity
		WHERE
			product_id = :product_id
		";

	$updateStock = $db->prepare($updateStockSQL);

	//	adjust inventory
	$inventory = $db->getColumn("SELECT inventory FROM users WHERE id = :id", ['id' => $oldCustomerID]);
	$inventory = $inventory ? 'calgary' : 'edmonton';

	$updateInventorySQL = "
		UPDATE
			$inventory
		SET
			quantity = quantity + :quantity
		WHERE
			product_id = :product_id
		";

	$updateInventory = $db->prepare($updateInventorySQL);

	$productsToAdjust = $db->getRows($sql, ['invoice_id' => $invoiceID]);

	foreach ($productsToAdjust as $productToAdjust) {

		$updateID 		= $productToAdjust->product_id;
		$updateQuantity = $productToAdjust->quantity;

		$updateStock->execute([
			'quantity' 		=> $updateQuantity,
			'product_id' 	=> $updateID,
		]);

		$updateInventory->execute([
			'quantity' 		=> $updateQuantity,
			'product_id' 	=> $updateID,
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
			price,
			supplier_price
		) VALUES (
			:invoice_id,
			:product_id,
			:quantity,
			:price,
			:supplier_price
		)";

	$updateStockSQL = "
		UPDATE
			stock
		SET
			quantity = quantity - :quantity
		WHERE
			product_id = :product_id
		";

	$inventory = $db->getColumn("SELECT inventory FROM users WHERE id = :id", ['id' => $customerID]);
	$inventory = $inventory ? 'calgary' : 'edmonton';

	$updateInventorySQL = "
		UPDATE
			$inventory
		SET
			quantity = quantity - :quantity
		WHERE
			product_id = :product_id
		";

	$preparedInsert 			= $db->prepare($sql);
	$preparedStockUpdate 		= $db->prepare($updateStockSQL);
	$preparedInventoryUpdate 	= $db->prepare($updateInventorySQL);

	foreach ($_POST['products'] as $product) {

		$product_id = $product['productID'];
		$quantity 	= $product['quantity'];
		$price 		= $product['price'];

		$supplierPriceSQL 	= "SELECT supplier_price FROM stock WHERE product_id = :product_id";
		$supplierPrice 		= $db->getColumn($supplierPriceSQL, ['product_id' => $product_id]);

		$preparedInsert->execute([
			'invoice_id' 		=> $invoiceID,
			'product_id' 		=> $product_id,
			'quantity' 	 		=> $quantity,
			'price' 	 		=> $price,
			'supplier_price' 	=> $supplierPrice,
		]);

		$preparedStockUpdate->execute([
			'product_id' => $product_id,
			'quantity' 	 => $quantity,
		]);

		$preparedInventoryUpdate->execute([
			'product_id' => $product_id,
			'quantity' 	 => $quantity,
		]);
	}

	//	adjust customer balance
	$sql = "UPDATE users SET balance = balance + :amount WHERE id = :id";
	$updateCustomerBalance = $db->updateRow($sql, [
		'amount' 	=> $invoiceTotal,
		'id' 		=> $customerID,
	]);

	if (!$updateCustomerBalance) {
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
			<i style="color: green" class="fas fa-check-circle"></i> Updated invoice ID ' . $invoiceID . '.
		</div>';

	die($msgHTML);
}
//	get prices for customer on select (documents: requisition from customer)
if (isset($_POST['getCustomerPrices'])) {

	$customerID = $_POST['customerID'];

	$sql = "
		SELECT DISTINCT
			p.id,
			p.name,
			up.price AS price
		FROM
			products AS p
		JOIN
			users_prices AS up
		ON
			p.id = up.product_id
		WHERE
			up.user_id = :id
		AND
			p.active = 1
		";

	$products = $db->getRows($sql, ['id' => $customerID]);
	$productsForCustomerHTML = '';
	$productsForCustomerHTML .= '<option data-price="0" value="0">Please Select</option>';

	foreach ($products as $product) {
		$productsForCustomerHTML .= '<option data-price="' . $product->price . '" value="' . $product->id . '">' . $product->name . '</option>';
	}

	die(json_encode($productsForCustomerHTML, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}

//	customer password update
if (isset($_POST['updateMyPassword'])) {

	$id 			= $_SESSION['userID'];
	$oldPassword 	= trim($_POST['oldPassword']);
	$newPassword 	= trim($_POST['newPassword']);

	if ($oldPassword === '' || $newPassword === '') {

		$response 			= [];
		$response['ok'] 	= 1;
		$response['html'] 	= '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Both inputs are required!
			</div>';
		die(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
	}

	if (mb_strlen($oldPassword, 'utf8') < 8 || mb_strlen($newPassword, 'utf8') < 8) {

		$response 			= [];
		$response['ok'] 	= 1;
		$response['html'] 	= '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Password needs to have minimum 8 characters!
			</div>';
		die(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
	}

	$currentPassword = $db->getColumn("SELECT password FROM users WHERE id = :id", ['id' => $id]);

	if (!password_verify($oldPassword, $currentPassword)) {

		$response 			= [];
		$response['ok'] 	= 1;
		$response['html'] 	= '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Old password does not match!
			</div>';
		die(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
	}

	$sql = "
		UPDATE
			users
		SET
			password = :password
		WHERE
			id = :id
		";

	$hash = password_hash($newPassword, PASSWORD_DEFAULT);

	$update = $db->updateRow($sql, [
		'password' 	=> $hash,
		'id' 		=> $id
	]);

	if (!$update) {

		$response 			= [];
		$response['ok'] 	= 1;
		$response['html'] 	= '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Could not update password. Please try again later.
			</div>';
		die(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
	}

	$response 			= [];
	$response['ok'] 	= 1;
	$response['html'] 	= '
		<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
			<i style="color: green" class="fas fa-check-circle"></i> Password updated!
		</div>';
	die(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}

//	edit customer payment
if (isset($_POST['editCustomerPayment'])) {

	$paymentID 	= $_POST['id'];
	$customerID = $_POST['customerID'];
	$amount 	= $_POST['amount'];
	$date 		= trim($_POST['date']);

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

	//	adjust old customer balance before updating payment
	$sql = "
		UPDATE
			users
		SET
			balance = balance + :balance
		WHERE
			id = :id
		";

	$updateCustomerBalance = $db->updateRow($sql, [
		'id' 		=> $oldPaymentInfo->user_id,
		'balance' 	=> $oldPaymentInfo->amount,
	]);

	//	update payment
	$sql = "
		UPDATE
			payments
		SET
			user_id 	= :user_id,
			amount 		= :amount,
			date 		= :date
		WHERE
			id 			= :id
		";

	$update = $db->updateRow($sql, [
		'user_id'		=> $customerID,
		'amount'		=> $amount,
		'date'			=> $date,
		'id'			=> $paymentID,
	]);

	//	update new customer balance
	$sql = "
		UPDATE
			users
		SET
			balance = balance - :balance
		WHERE
			id = :id
		";

	$db->updateRow($sql, [
		'balance' 	=> $amount,
		'id' 		=> $customerID,
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

// 	delete customer payment
if (isset($_POST['deleteCustomerPayment'])) {

	$paymentID = $_POST['id'];

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

	//	adjust old customer balance before deleting payment
	$sql = "
		UPDATE
			users
		SET
			balance = balance + :balance
		WHERE
			id = :id
		";

	$updateCustomerBalance = $db->updateRow($sql, [
		'id' 		=> $oldPaymentInfo->user_id,
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

//	delete customer invoice
if (isset($_POST['deleteCustomerInvoice'])) {

	$invoiceID 	= $_POST['id'];

	$db->beginTransaction();

	$sql = "
		SELECT
			user_id,
			amount
		FROM
			invoices
		WHERE
			id = :id
		";

	$oldInvoiceInfo = $db->getRow($sql, ['id' => $invoiceID]);

	$customerID 	= $oldInvoiceInfo->user_id;
	$amount	 		= $oldInvoiceInfo->amount;
	$return 		= ($amount * 1) < 0 ? true : false;

	//	update customer balance
	$updateBalanceSQL = "
		UPDATE
			users
		SET
			balance = balance - :amount
		WHERE
			id = :id
		";

	$updateBalance = $db->updateRow($updateBalanceSQL, [
		'amount' 	=> $amount,
		'id' 		=> $customerID,
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
			product_id
		FROM
			invoice_products
		WHERE
			invoice_id = :invoice_id
		";

	$productsToAdjust = $db->getRows($sql, ['invoice_id' => $invoiceID]);

	//	prepare stock update sql
	$updateStockSQL = "
		UPDATE
			stock
		SET
			quantity = quantity + :quantity
		WHERE
			product_id = :product_id
		";

	$updateStock = $db->prepare($updateStockSQL);

	//	prepare inventoryy update sql
	$inventory = $db->getColumn("SELECT inventory FROM users WHERE id = :id", ['id' => $customerID]);
	$inventory = $inventory ? 'calgary' : 'edmonton';

	$updateInventorySQL = "
		UPDATE
			$inventory
		SET
			quantity = quantity + :quantity
		WHERE
			product_id = :product_id
		";

	$updateInventory = $db->prepare($updateInventorySQL);

	foreach ($productsToAdjust as $productToAdjust) {

		$updateID 		= $productToAdjust->product_id;
		$updateQuantity = $productToAdjust->quantity;

		if ($return) {
			$updateQuantity = $updateQuantity * -1;
		}

		$updateStock->execute([
			'quantity' 		=> $updateQuantity,
			'product_id' 	=> $updateID,
		]);

		$updateInventory->execute([
			'quantity' 		=> $updateQuantity,
			'product_id' 	=> $updateID,
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
