<?php
userLoggedIn();

if ( ! isAdmin()) {
	goLogin();
}

$pageTitle = 'MDT - Documents';

//	suppliers list (used for creating new supplier invoices)
$sql = "SELECT id, name FROM suppliers WHERE active = 1";
$suppliers = $db->getRows($sql);

$suppliersHTML = '';
$suppliersHTML .= '<option value="">Please Select</option>';

foreach ($suppliers as $supplier) {
	$suppliersHTML .= '<option value="' . $supplier->id . '">' . $supplier->name . '</option>';
}

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

$productsForSupplierHTML = '';
$productsForSupplierHTML .= '<option data-price="0" value="0">Please Select</option>';

foreach ($supplierProducts as $supplierProduct) {
	$productsForSupplierHTML .= '<option data-price="' . $supplierProduct->price . '" value="' . $supplierProduct->id . '">' . $supplierProduct->name . '</option>';
}

// 	customers list
$sql = "SELECT id, username FROM users WHERE role = 2 AND active = 1";
$customers = $db->getRows($sql);

$customersHTML = '';
$customersHTML .= '<option value="">Please Select</option>';

foreach ($customers as $customer) {
	$customersHTML .= '<option value="' . $customer->id . '">' . $customer->username . '</option>';
}
?>