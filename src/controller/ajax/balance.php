<?php
if (isset($_POST['balance'])) {
	$sql = "
		SELECT
			p.id AS productID,
			p.name AS product,
			s.name AS supplier,
			us.username AS customer,
			SUM(ip.quantity) AS quantity,
			ip.price,
			ip.supplier_price,
			SUM(ip.quantity * ip.price) AS total,
			i.type,
			i.date
		FROM
			products AS p
		LEFT JOIN
			invoice_products AS ip
		ON
			p.id = ip.product_id
		LEFT JOIN
			invoices AS i
		ON
			ip.invoice_id = i.id
		LEFT JOIN
			suppliers AS s
		ON
			i.supplier_id = s.id
		LEFT JOIN
			users AS us
		ON
			i.user_id = us.id
		WHERE
			i.status_id = 3
		AND
			i.month = :month
		AND
			i.year  = :year
		GROUP BY
			p.name, i.type, i.date, us.username
		ORDER BY
			p.id ASC, i.type ASC, i.date ASC
		"
	;

	$month 	= (int)date('m');
	$year 	= (int)date('Y');

	$products = $db->getRows($sql, [
		'month' => $month,
		'year' 	=> $year,
	]);

	$data['products'] 			= [];
	$data['totals']	  			= [];

	$data['totals']['type1'] 	= 0;
	$data['totals']['type0'] 	= 0;
	$data['totals']['profit'] 	= 0;

	foreach ($products as $product) {

		if (! isset($data['products'][$product->product]['type0'])) {
			$data['products'][$product->product]['type0']['list'] 			= [];
			$data['products'][$product->product]['type0']['totalPrice'] 	= 0;
			$data['products'][$product->product]['type0']['totalWeight'] 	= 0;
		}

		if (! isset($data['products'][$product->product]['type1'])) {
			$data['products'][$product->product]['type1']['list'] 			= [];
			$data['products'][$product->product]['type1']['totalPrice'] 	= 0;
			$data['products'][$product->product]['type1']['totalWeight'] 	= 0;
			$data['products'][$product->product]['type1']['totalProfit'] 	= 0;
		}

		if ($product->type) {

			$productObj = [];
			$date 		= date('m-d-Y', strtotime($product->date));

			$productObj['quantity'] 		= $product->quantity;
			$productObj['price'] 			= $product->price;
			$productObj['supplier_price'] 	= $product->supplier_price;
			$productObj['total'] 			= $product->total;
			$productObj['customer'] 		= $product->customer;
			$productObj['date'] 			= $date;

			$profit = $product->total - ($product->supplier_price * $product->quantity);

			$data['products'][$product->product]['type1']['list'][] = $productObj;

			$data['products'][$product->product]['type1']['totalPrice'] += $product->total;
			$data['products'][$product->product]['type1']['totalWeight'] += $product->quantity;
			$data['products'][$product->product]['type1']['totalProfit'] += $profit;
			$data['totals']['type1'] += $product->total;
			$data['totals']['profit'] += $profit;

		} else {

			$productObj = [];
			$date 		= date('m-d-Y', strtotime($product->date));

			$productObj['quantity'] = $product->quantity;
			$productObj['price'] 	= $product->price;
			$productObj['total'] 	= $product->total;
			$productObj['supplier'] = $product->supplier;
			$productObj['date'] 	= $date;

			$data['products'][$product->product]['type0']['list'][] = $productObj;

			$data['products'][$product->product]['type0']['totalPrice'] += $product->total;
			$data['products'][$product->product]['type0']['totalWeight'] += $product->quantity;
			$data['totals']['type0'] += $product->total;
		}
	}

	die(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK ));
}

//	get balance for a selected period
if (isset($_POST['getBalance'])) {

	$startDate 	= $_POST['startDate'];
	$endDate 	= $_POST['endDate'];

	$sql = "
		SELECT
			p.id AS productID,
			p.name AS product,
			s.name AS supplier,
			us.username AS customer,
			SUM(ip.quantity) AS quantity,
			ip.price,
			ip.supplier_price,
			SUM(ip.quantity * ip.price) AS total,
			i.type,
			i.date
		FROM
			products AS p
		LEFT JOIN
			invoice_products AS ip
		ON
			p.id = ip.product_id
		LEFT JOIN
			invoices AS i
		ON
			ip.invoice_id = i.id
		LEFT JOIN
			suppliers AS s
		ON
			i.supplier_id = s.id
		LEFT JOIN
			users AS us
		ON
			i.user_id = us.id
		WHERE
			i.status_id = 3
		AND
			(date BETWEEN :start_date AND :end_date)
		GROUP BY
			p.name, i.type, i.date, us.username
		ORDER BY
			p.id ASC, i.type ASC, i.date ASC
		"
	;

	$products = $db->getRows($sql, [
		'start_date' 	=> $startDate,
		'end_date' 		=> $endDate,
	]);

	$data['products'] 			= [];
	$data['totals']	  			= [];

	$data['totals']['type1'] 	= 0;
	$data['totals']['type0'] 	= 0;
	$data['totals']['profit'] 	= 0;

	foreach ($products as $product) {

		if (! isset($data['products'][$product->product]['type0'])) {
			$data['products'][$product->product]['type0']['list'] 			= [];
			$data['products'][$product->product]['type0']['totalPrice'] 	= 0;
			$data['products'][$product->product]['type0']['totalWeight'] 	= 0;
		}

		if (! isset($data['products'][$product->product]['type1'])) {
			$data['products'][$product->product]['type1']['list'] 			= [];
			$data['products'][$product->product]['type1']['totalPrice'] 	= 0;
			$data['products'][$product->product]['type1']['totalWeight'] 	= 0;
			$data['products'][$product->product]['type1']['totalProfit'] 	= 0;
		}

		if ($product->type) {

			$productObj = [];
			$date 		= date('m-d-Y', strtotime($product->date));

			$productObj['quantity'] 		= $product->quantity;
			$productObj['price'] 			= $product->price;
			$productObj['supplier_price'] 	= $product->supplier_price;
			$productObj['total'] 			= $product->total;
			$productObj['customer'] 		= $product->customer;
			$productObj['date'] 			= $date;

			$profit = $product->total - ($product->supplier_price * $product->quantity);

			$data['products'][$product->product]['type1']['list'][] = $productObj;

			$data['products'][$product->product]['type1']['totalPrice'] += $product->total;
			$data['products'][$product->product]['type1']['totalWeight'] += $product->quantity;
			$data['products'][$product->product]['type1']['totalProfit'] += $profit;
			$data['totals']['type1'] += $product->total;
			$data['totals']['profit'] += $profit;

		} else {

			$productObj = [];
			$date 		= date('m-d-Y', strtotime($product->date));

			$productObj['quantity'] = $product->quantity;
			$productObj['price'] 	= $product->price;
			$productObj['total'] 	= $product->total;
			$productObj['supplier'] = $product->supplier;
			$productObj['date'] 	= $date;

			$data['products'][$product->product]['type0']['list'][] = $productObj;

			$data['products'][$product->product]['type0']['totalPrice'] += $product->total;
			$data['products'][$product->product]['type0']['totalWeight'] += $product->quantity;
			$data['totals']['type0'] += $product->total;
		}
	}

	die(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK ));
}
?>