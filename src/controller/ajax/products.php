<?php
if (isset($_POST['getProducts'])) {

	$params = $columns = $totalRecords = $data = [];
	$params = $_POST;

	$columns = [
		0 => 'name',
		1 => 'active',
	];

	$where_condition = '';

	$sql = $sqlRec = "
		SELECT
			id,
			name,
			active
		FROM
			products
		";

	if (!empty($params['search']['value'])) {
		$where_condition .= " WHERE name LIKE '%" . $params['search']['value'] . "%' ";
	}

	if (isset($where_condition) && $where_condition != '') {
		$sqlRec .= $where_condition;
	}

	$sqlRec .=  " ORDER BY " . $columns[$params['order'][0]['column']] . "   " . $params['order'][0]['dir'] . "  LIMIT " . $params['start'] . " ," . $params['length'] . " ";

	$totalRecords = $db->getColumn("SELECT COUNT(id) FROM products");
	$queryRecords = $db->getRows($sqlRec);

	$data = [];

	foreach ($queryRecords as $row) {

		if ($row->active) {
			$selectedYes 	= 'selected';
			$selectedNo 	= '';
		} else {
			$selectedYes 	= '';
			$selectedNo 	= 'selected';
		}

		$product = '<input data-id="' . $row->id . '" value="' . h($row->name) . '">';

		$activeHTML = '<select>';
		$activeHTML .= '<option value="1" ' . $selectedYes . '>Yes</option>';
		$activeHTML .= '<option value="0" ' . $selectedNo . '>No</option>';
		$activeHTML .= '</select>';

		$updateHTML = '<button class="update_product" data-id="' . $row->id . '">Update</button>';

		$data[] = [
			$product,
			$activeHTML,
			$updateHTML,
		];
	}

	$json = [
		"draw"            	=> intval($params['draw']),
		"recordsTotal"    	=> intval($totalRecords),
		"recordsFiltered"	=> intval($totalRecords),
		"data"            	=> $data,
	];

	die(json_encode($json, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}

//	Update product
if (isset($_POST['updateProduct'])) {

	$id 		= trim($_POST['id']);
	$name 		= trim($_POST['product']);
	$active 	= trim($_POST['active']);

	if ($id === '' || $name === '') {
		die('0');
	}

	$update = $db->updateRow("UPDATE products SET name = :name, active = :active WHERE id = :id", [
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

if (isset($_POST['buildProductPricesForCustomersTable'])) {

	$html = "";
	$html = "<table id=\"productPricesTable\">";
	$html .= "<thead>";
	$html .= "<tr>";
	$html .= "<th>Product</th>";

	$sql = "
		SELECT
			up.user_id,
			up.product_id,
			up.price
		FROM
			users_prices AS up
		JOIN
		    users AS u
		ON
		    up.user_id = u.id
		WHERE
		    u.active = 1
		AND
		    role = 2
		";

	$userPrices = $db->getRows($sql);

	$sqlUsers = "
		SELECT
			id,
			username AS name
		FROM
			users
		WHERE
			role = 2
		AND
			active = 1
		";

	$customers = $db->getRows($sqlUsers);
	$products = $db->getRows("SELECT id, name FROM products");

	$json 				= [];
	$json['customers'] 	= $customers;
	$json['products'] 	= $products;

	$customerCount 		= count($customers) - 1;

	foreach ($customers as $customerObj) {

		$customer = $customerObj->name;

		$html .= "<th>{$customer}</th>";

		if ($customerCount) {
			$customerCount--;
		} else {
			$html .= "<th><i class=\"fas fa-cog\"></i></th></tr></thead><tbody>";
		}
	}

	$productCount = count($products);

	foreach ($products as $productObj) {

		$product 		= $productObj->name;

		$html .= "<tr><td>{$product}</td>";

		foreach ($userPrices as $userPriceObj) {

			$customerID = $userPriceObj->user_id;
			$productID  = $userPriceObj->product_id;
			$price 		= $userPriceObj->price;

			if ($productID == $productObj->id) {
				$html .= "<td><input type=\"text\" value=\"{$price}\" data-product-id=\"{$productID}\" data-customer-id=\"{$customerID}\"></td>";
			}
		}

		$html .= "<td><button class=\"setPrices\">Update</button></td>";
		$html .= "</tr>";
	}

	$html .= "</tbody>";
	$html .= "</table>";

	die(json_encode($html, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}

if (isset($_POST['addNewProductForCalagory'])) {
	$product 			= trim($_POST['productName']);
	$supplier_price 	= trim($_POST['supplierPrice']);
	$quantityCalgary 	= trim($_POST['calgaryQuantity']);
	$quantityStock 		= $quantityCalgary;

	$db->beginTransaction();

	//	check if product name already exists
	$sql 			= "SELECT name FROM products WHERE name = :name";
	$productExists 	= $db->getColumn($sql, ['name' => $product]);

	if ($productExists) {

		$db->rollBack();

		$msgHTML = '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Product name already exists.
			</div>';

		die($msgHTML);
	}

	//	add product
	$sql 		= "INSERT INTO products (name) VALUES (:name)";
	$insert 	= $db->insertRow($sql, ['name' => $product]);
	$productID 	= $db->getLastID();

	//	add new product to stock , calgary and edmonton
	$sql 	= "INSERT INTO stock (product_id, quantity, supplier_price) VALUES (:product_id, :quantity, :supplier_price)";
	$insert = $db->insertRow($sql, [
		'product_id' 		=> $productID,
		'quantity' 			=> $quantityStock,
		'supplier_price' 	=> $supplier_price,
	]);

	$sql 	= "INSERT INTO calgary (product_id, quantity) VALUES (:product_id, :quantity)";
	$insert = $db->insertRow($sql, [
		'product_id' 	=> $productID,
		'quantity' 		=> $quantityCalgary,
	]);


	//	get all users
	$sql 	= "SELECT id FROM users WHERE role = 2";
	$users 	= $db->getRows($sql);

	if ($users) {

		//	insert product into user prices table
		$sql  	= "INSERT INTO users_prices (user_id, product_id) VALUES (:user_id, :product_id)";
		$stmt  	= $db->prepare($sql);

		foreach ($users as $user) {

			$userID = $user->id;

			$stmt->execute([
				'user_id' 		=> $userID,
				'product_id' 	=> $productID,
			]);
		}
	}

	$db->commit();

	$msgHTML = '
		<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
			<i style="color: green" class="fas fa-check-circle"></i> Added new product:  ' . $product . '
		</div>';

	die($msgHTML);
}
if (isset($_POST['addNewProductForEdmonton'])) {
	$product 			= trim($_POST['productName']);
	$supplier_price 	= trim($_POST['supplierPrice']);
	$quantityEdmonton	= trim($_POST['edmontonQuantity']);
	$quantityStock 		= $quantityEdmonton;

	$db->beginTransaction();

	//	check if product name already exists
	$sql 			= "SELECT name FROM products WHERE name = :name";
	$productExists 	= $db->getColumn($sql, ['name' => $product]);

	if ($productExists) {

		$db->rollBack();

		$msgHTML = '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Product name already exists.
			</div>';

		die($msgHTML);
	}

	//	add product
	$sql 		= "INSERT INTO products (name) VALUES (:name)";
	$insert 	= $db->insertRow($sql, ['name' => $product]);
	$productID 	= $db->getLastID();
	//	add new product to stock , calgary and edmonton
	$sql 	= "INSERT INTO stock (product_id, quantity, supplier_price) VALUES (:product_id, :quantity, :supplier_price)";

	$insert = $db->insertRow($sql, [
		'product_id' 		=> $productID,
		'quantity' 			=> $quantityStock,
		'supplier_price' 	=> $supplier_price,
	]);
	$sql 	= "INSERT INTO edmonton (product_id, quantity) VALUES (:product_id, :quantity)";
	$insert = $db->insertRow($sql, [
		'product_id' 	=> $productID,
		'quantity' 		=> $quantityEdmonton,
	]);

	//	get all users
	$sql 	= "SELECT id FROM users WHERE role = 2";
	$users 	= $db->getRows($sql);

	if ($users) {

		//	insert product into user prices table
		$sql  	= "INSERT INTO users_prices (user_id, product_id) VALUES (:user_id, :product_id)";
		$stmt  	= $db->prepare($sql);

		foreach ($users as $user) {

			$userID = $user->id;

			$stmt->execute([
				'user_id' 		=> $userID,
				'product_id' 	=> $productID,
			]);
		}
	}

	$db->commit();

	$msgHTML = '
		<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
			<i style="color: green" class="fas fa-check-circle"></i> Added new product:  ' . $product . '
		</div>';

	die($msgHTML);
}
if (isset($_POST['addNewProductForToronto'])) {
	$product 			= trim($_POST['productName']);
	$supplier_price 	= trim($_POST['supplierPrice']);
	$quantityToronto	= trim($_POST['torontoQuantity']);
	$quantityStock 		= $quantityToronto;

	$db->beginTransaction();

	//	check if product name already exists
	$sql 			= "SELECT name FROM products WHERE name = :name";
	$productExists 	= $db->getColumn($sql, ['name' => $product]);

	if ($productExists) {

		$db->rollBack();

		$msgHTML = '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Product name already exists.
			</div>';

		die($msgHTML);
	}

	//	add product
	$sql 		= "INSERT INTO products (name) VALUES (:name)";
	$insert 	= $db->insertRow($sql, ['name' => $product]);
	$productID 	= $db->getLastID();
	//	add new product to stock , calgary and edmonton
	$sql 	= "INSERT INTO stock (product_id, quantity, supplier_price) VALUES (:product_id, :quantity, :supplier_price)";

	$insert = $db->insertRow($sql, [
		'product_id' 		=> $productID,
		'quantity' 			=> $quantityStock,
		'supplier_price' 	=> $supplier_price,
	]);
	$sql 	= "INSERT INTO toronto (product_id, quantity) VALUES (:product_id, :quantity)";
	$insert = $db->insertRow($sql, [
		'product_id' 	=> $productID,
		'quantity' 		=> $quantityToronto,
	]);

	//	get all users
	$sql 	= "SELECT id FROM users WHERE role = 2";
	$users 	= $db->getRows($sql);

	if ($users) {

		//	insert product into user prices table
		$sql  	= "INSERT INTO users_prices (user_id, product_id) VALUES (:user_id, :product_id)";
		$stmt  	= $db->prepare($sql);

		foreach ($users as $user) {

			$userID = $user->id;

			$stmt->execute([
				'user_id' 		=> $userID,
				'product_id' 	=> $productID,
			]);
		}
	}

	$db->commit();

	$msgHTML = '
		<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
			<i style="color: green" class="fas fa-check-circle"></i> Added new product:  ' . $product . '
		</div>';

	die($msgHTML);
}
if (isset($_POST['addNewProductForKelowna'])) {
	$product 			= trim($_POST['productName']);
	$supplier_price 	= trim($_POST['supplierPrice']);
	$quantityKelowna	= trim($_POST['kelownaQuantity']);
	$quantityStock 		= $quantityKelowna;

	$db->beginTransaction();

	//	check if product name already exists
	$sql 			= "SELECT name FROM products WHERE name = :name";
	$productExists 	= $db->getColumn($sql, ['name' => $product]);

	if ($productExists) {

		$db->rollBack();

		$msgHTML = '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Product name already exists.
			</div>';

		die($msgHTML);
	}

	//	add product
	$sql 		= "INSERT INTO products (name) VALUES (:name)";
	$insert 	= $db->insertRow($sql, ['name' => $product]);
	$productID 	= $db->getLastID();
	//	add new product to stock , calgary and edmonton
	$sql 	= "INSERT INTO stock (product_id, quantity, supplier_price) VALUES (:product_id, :quantity, :supplier_price)";

	$insert = $db->insertRow($sql, [
		'product_id' 		=> $productID,
		'quantity' 			=> $quantityStock,
		'supplier_price' 	=> $supplier_price,
	]);
	$sql 	= "INSERT INTO kelowna (product_id, quantity) VALUES (:product_id, :quantity)";
	$insert = $db->insertRow($sql, [
		'product_id' 	=> $productID,
		'quantity' 		=> $quantityKelowna,
	]);

	//	get all users
	$sql 	= "SELECT id FROM users WHERE role = 2";
	$users 	= $db->getRows($sql);

	if ($users) {

		//	insert product into user prices table
		$sql  	= "INSERT INTO users_prices (user_id, product_id) VALUES (:user_id, :product_id)";
		$stmt  	= $db->prepare($sql);

		foreach ($users as $user) {

			$userID = $user->id;

			$stmt->execute([
				'user_id' 		=> $userID,
				'product_id' 	=> $productID,
			]);
		}
	}

	$db->commit();

	$msgHTML = '
		<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
			<i style="color: green" class="fas fa-check-circle"></i> Added new product:  ' . $product . '
		</div>';

	die($msgHTML);
}
if (isset($_POST['updateUserPrices'])) {

	$prices = $_POST['prices'];

	$db->beginTransaction();

	$sql = "
		UPDATE
			users_prices
		SET
			price 		= :price
		WHERE
			user_id 	= :user_id
		AND
			product_id 	= :product_id
		";

	$check = [];

	foreach ($prices as $price) {

		$customerID = $price['customerID'];
		$productID  = $price['productID'];
		$price  	= $price['price'];

		$update = $db->updateRow($sql, [
			'user_id' 		=> $customerID,
			'product_id' 	=> $productID,
			'price' 		=> $price,
		]);

		$check[] = $update ? 1 : 0;
	}

	if (!in_array(1, $check)) {

		$db->rollBack();

		$response = [];
		$response['ok'] 	= 1;
		$response['html'] 	= '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> No changes made!
			</div>';
	} else {

		$db->commit();

		$response = [];
		$response['ok'] 	= 1;
		$response['html'] 	= '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: green" class="fas fa-check-circle"></i> New prices are set!
			</div>';
	}

	die(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}

if (isset($_POST['addNewProduct'])) {

	$product 			= trim($_POST['product']);
	$supplier_price 	= trim($_POST['supplier_price']);
	$quantityCalgary 	= trim($_POST['quantityCalgary']);
	$quantityEdmonton 	= trim($_POST['quantityEdmonton']);
	$quantityToronto 	= trim($_POST['quantityToronto']);
	$quantityKelowna 	= trim($_POST['quantityKelowna']);
	$quantityStock 		= $quantityCalgary + $quantityEdmonton + $quantityToronto + $quantityKelowna;

	$db->beginTransaction();

	//	check if product name already exists
	$sql 			= "SELECT name FROM products WHERE name = :name";
	$productExists 	= $db->getColumn($sql, ['name' => $product]);

	if ($productExists) {

		$db->rollBack();

		$msgHTML = '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Product name already exists.
			</div>';

		die($msgHTML);
	}

	//	add product
	$sql 		= "INSERT INTO products (name) VALUES (:name)";
	$insert 	= $db->insertRow($sql, ['name' => $product]);
	$productID 	= $db->getLastID();

	//	add new product to stock , calgary and edmonton
	$sql 	= "INSERT INTO stock (product_id, quantity, supplier_price) VALUES (:product_id, :quantity, :supplier_price)";
	$insert = $db->insertRow($sql, [
		'product_id' 		=> $productID,
		'quantity' 			=> $quantityStock,
		'supplier_price' 	=> $supplier_price,
	]);


	$sql 	= "INSERT INTO calgary (product_id, quantity) VALUES (:product_id, :quantity)";
	$insert = $db->insertRow($sql, [
		'product_id' 	=> $productID,
		'quantity' 		=> $quantityCalgary,
	]);

	$sql 	= "INSERT INTO edmonton (product_id, quantity) VALUES (:product_id, :quantity)";
	$insert = $db->insertRow($sql, [
		'product_id' 	=> $productID,
		'quantity' 		=> $quantityEdmonton,
	]);

	$sql 	= "INSERT INTO toronto (product_id, quantity) VALUES (:product_id, :quantity)";
	$insert = $db->insertRow($sql, [
		'product_id' 	=> $productID,
		'quantity' 		=> $quantityToronto,
	]);

	$sql 	= "INSERT INTO kelowna (product_id, quantity) VALUES (:product_id, :quantity)";
	$insert = $db->insertRow($sql, [
		'product_id' 	=> $productID,
		'quantity' 		=> $quantityKelowna,
	]);

	//	get all users
	$sql 	= "SELECT id FROM users WHERE role = 2";
	$users 	= $db->getRows($sql);
	if ($users) {

		//	insert product into user prices table
		$sql  	= "INSERT INTO users_prices (user_id, product_id) VALUES (:user_id, :product_id)";
		$stmt  	= $db->prepare($sql);

		foreach ($users as $user) {

			$userID = $user->id;

			$stmt->execute([
				'user_id' 		=> $userID,
				'product_id' 	=> $productID,
			]);
		}
	}

	$db->commit();

	$msgHTML = '
		<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
			<i style="color: green" class="fas fa-check-circle"></i> Added new product:  ' . $product . '
		</div>';

	die($msgHTML);
}
//	return of products - selected invoice type
if (isset($_POST['getInvoiceTypeSelection'])) {

	$invoiceType 	= $_POST['invoiceType'];
	$HTML 			= '';

	if (!$invoiceType) {

		//	get suppliers list
		$sql = "SELECT id, name FROM suppliers WHERE active = 1";
		$suppliers = $db->getRows($sql);

		$HTML .= '<option value="">Please Select</option>';

		foreach ($suppliers as $supplier) {
			$HTML .= '<option value="' . $supplier->id . '">' . $supplier->name . '</option>';
		}

		die(json_encode($HTML, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
	} else {

		// 	get customers list
		$sql = "SELECT id, username FROM users WHERE role = 2 AND active = 1";
		$customers = $db->getRows($sql);

		$HTML .= '<option value="">Please Select</option>';

		foreach ($customers as $customer) {
			$HTML .= '<option value="' . $customer->id . '">' . $customer->username . '</option>';
		}
	}

	die(json_encode($HTML, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}
//	return of products - get products with prices
if (isset($_POST['getProductsAndCorrectPrices'])) {

	$invoiceType 	= $_POST['invoiceType'];
	$id				= $_POST['id'];
	$HTML 			= '';

	if (!$invoiceType) {

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
			WHERE
			    products.active = 1
			";

		$supplierProducts = $db->getRows($sql);

		$HTML .= '<option data-price="0" value="0">Please Select</option>';

		foreach ($supplierProducts as $supplierProduct) {
			$HTML .= '<option data-price="' . $supplierProduct->price . '" value="' . $supplierProduct->id . '">' . $supplierProduct->name . '</option>';
		}
	} else {

		$customerID = $id;

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
			AND
				p.active = 1
			";

		$products = $db->getRows($sql, ['id' => $customerID]);


		$HTML .= '<option data-price="0" value="0">Please Select</option>';

		foreach ($products as $product) {
			$HTML .= '<option data-price="' . $product->price . '" value="' . $product->id . '">' . $product->name . '</option>';
		}
	}

	die(json_encode($HTML, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}

//	return of products form submit
if (isset($_POST['returnInvoiceSubmit'])) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	$invoiceType 	= $_POST['invoiceType'];
	$id 			= $_POST['id'];
	$total 			= $_POST['total'];
	$date 			= $_POST['date'];
	$products 		= $_POST['products'];

	if (($invoiceType != '0' && $invoiceType != '1') || !$id) {

		$response = [];
		$response['ok'] 	= 1;
		$response['html'] 	= '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Application error.
			</div>';

		die(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
	}

	//	return from customer
	if ($invoiceType) {

		$type 			= 1;
		$supplier_id 	= 0;
		$amount			= trim($_POST['total']);
		$status_id		= 3;
		$user_id 		= $id;

		$month 			= substr($date, 5, 2);
		$year 			= substr($date, 0, 4);

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
				year
			) VALUES (
				:type,
				:supplier_id,
				:user_id,
				:amount,
				:status_id,
				:date,
				:month,
				:year
			)";

		//	since its a return set negatives here
		$negativeAmount 	= 0 - $amount;

		$insertInvoices = $db->insertRow($sql, [
			'type' 			=> $type,
			'supplier_id' 	=> $supplier_id,
			'user_id' 		=> $user_id,
			'amount' 		=> $negativeAmount,
			'status_id'		=> $status_id,
			'date'			=> $date,
			'month'			=> $month,
			'year'			=> $year,
		]);

		if (!$insertInvoices) {

			$db->rollBack();

			$response = [];
			$response['ok'] 	= 1;
			$response['html'] 	= '
				<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
					<i style="color: red" class="fas fa-exclamation-triangle"></i>Failed to process invoice!
				</div>';

			die(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
		}

		//	insert invoice products
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

		//	update stock
		$updateStockSQL = "
			UPDATE
				stock
			SET
				quantity = quantity + :quantity
			WHERE
				product_id = :product_id
			";

		//	update designated customer inventory
		// $inventory = $db->getColumn("SELECT inventory FROM users WHERE id = :id", ['id' => $user_id]);
		// $inventory = $inventory ? 'calgary' : 'edmonton';
		$inventory = $_POST['inventory'];
		$updateInventorySQL = "
			UPDATE
				$inventory
			SET
				quantity = quantity + :quantity
			WHERE
				product_id = :product_id
			";

		//	update customer's balance
		$updateCustomerBalanceSQL = "
			UPDATE
				users
			SET
				balance = balance - :balance
			WHERE
				id = :id
			";

		$insertInvoiceProducts 	= $db->prepare($sql);
		$adjustStock 			= $db->prepare($updateStockSQL);
		$adjustInventory 		= $db->prepare($updateInventorySQL);
		$updateCustomerBalance 	= $db->prepare($updateCustomerBalanceSQL);

		foreach ($_POST['products'] as $product) {

			$product_id = $product['productID'];
			$quantity 	= $product['quantity'];
			$price 		= $product['price'];

			$negativePrice 		= 0 - $price;

			$supplierPriceSQL 	= "SELECT supplier_price FROM stock WHERE product_id = :product_id";
			$supplierPrice 		= $db->getColumn($supplierPriceSQL, ['product_id' => $product_id]);

			$insertInvoiceProducts->execute([
				'invoice_id' 		=> $invoice_id,
				'product_id' 		=> $product_id,
				'quantity' 	 		=> $quantity,
				'price' 	 		=> $negativePrice,
				'supplier_price' 	=> $supplierPrice,
			]);

			$adjustStock->execute([
				'quantity' 		=> $quantity,
				'product_id' 	=> $product_id,
			]);

			$adjustInventory->execute([
				'quantity'	 => $quantity,
				'product_id' => $product_id,
			]);

			$updateCustomerBalance->execute([
				'balance' => $amount,
				'id' 	  => $user_id,
			]);
		}

		$db->commit();

		$response = [];
		$response['ok'] 	= 1;
		$response['html'] 	= '
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: green" class="fas fa-check-circle"></i> Added invoce ID: ' . $invoice_id . '
			</div>';

		die(json_encode($response, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));

		//	return to supplier
	} else {

		$type 			= 0;
		$supplier_id 	= $id;
		$user_id		= $_SESSION['userID'];
		$amount			= trim($_POST['total']);
		$status_id		= 3;

		$month 			=  substr($date, 5, 2);
		$year 			=  substr($date, 0, 4);

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
				year
			) VALUES (
				:type,
				:supplier_id,
				:user_id,
				:amount,
				:status_id,
				:date,
				:month,
				:year
			)";

		//	since its a return set negatives here
		$negativeAmount 	= 0 - $amount;

		$insertInvoices = $db->insertRow($sql, [
			'type' 			=> $type,
			'supplier_id' 	=> $supplier_id,
			'user_id' 		=> $user_id,
			'amount' 		=> $negativeAmount,
			'status_id'		=> $status_id,
			'date'			=> $date,
			'month'			=> $month,
			'year'			=> $year,
		]);

		if (!$insertInvoices) {

			$db->rollBack();

			$response = [];
			$response['ok'] 	= 1;
			$response['html'] 	= '
				<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
					<i style="color: red" class="fas fa-exclamation-triangle"></i> Failed to process invoice!
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

			$negativePrice 		= 0 - $price;

			$insertInvoiceProducts->execute([
				'invoice_id' => $invoice_id,
				'product_id' => $product_id,
				'quantity' 	 => $quantity,
				'price'		 => $negativePrice,
			]);

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

			$newQuantity = $oldQuantity - $quantity;
			$newTotal 	 = $oldTotal - $total;

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
					quantity 		= quantity - :quantity,
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
			if (isset($_POST['inventory']) && $_POST['inventory'] == 'calgary') {
				$updateCalgarySQL = "
					UPDATE
						calgary
					SET
						quantity 		= quantity - :quantity
					WHERE
						product_id 		= :product_id
					";


				$updateCalgary = $db->updateRow($updateCalgarySQL, [
					'product_id' 		=> $product_id,
					'quantity' 			=> $quantity,
				]);
			}
			if (isset($_POST['inventory'])  && $_POST['inventory'] == 'edmonton') {
				$updateEdmontonSQL = "
					UPDATE
						edmonton
					SET
						quantity 		= quantity - :quantity
					WHERE
						product_id 		= :product_id
					";

				$updateEdmonton = $db->updateRow($updateEdmontonSQL, [
					'product_id' 		=> $product_id,
					'quantity' 			=> $quantity,
				]);
			}
			if (isset($_POST['inventory'])  && $_POST['inventory'] == 'toronto') {
				$updateTorontoSQL = "
				UPDATE
					toronto
				SET
					quantity 		= quantity - :quantity
				WHERE
					product_id 		= :product_id
				";

				$updateToronto = $db->updateRow($updateTorontoSQL, [
					'product_id' 		=> $product_id,
					'quantity' 			=> $quantity,
				]);
			}
			if (isset($_POST['inventory'])   && $_POST['inventory'] == 'kelowna') {
				$updateKelownaSQL = "
					UPDATE
						kelowna
					SET
						quantity 		= quantity - :quantity
					WHERE
						product_id 		= :product_id
					";

				$updateKelowna = $db->updateRow($updateKelownaSQL, [
					'product_id' 		=> $product_id,
					'quantity' 			=> $quantity,
				]);
			}
			//	update clagary inventory
			// $updateCalgarySQL = "
			// 	UPDATE
			// 		calgary
			// 	SET
			// 		quantity 		= quantity - :quantity
			// 	WHERE
			// 		product_id 		= :product_id
			// 	";

			// $updateCalgary = $db->updateRow($updateCalgarySQL, [
			// 	'product_id' 		=> $product_id,
			// 	'quantity' 			=> $quantity,
			// ]);
		}

		//	adjust supplier balance
		$sql = "UPDATE suppliers SET balance = balance - :amount WHERE id = :id";
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
}
