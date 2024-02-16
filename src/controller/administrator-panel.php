<?php
userLoggedIn();

if (!isAdmin()) {
	goLogin();
}

$pageTitle = 'MDT - Administrator Panel';

//	suppliers list (used for editing supplier invoices)

$sql = "SELECT id, name FROM suppliers";
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

// 	active invertories
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);
// Fetch active inventories
// Fetch active inventories
$activeInventoriesQuery = "SELECT * FROM inventories WHERE status = 1";
$activeInventoriesResult =  $db->getRows($activeInventoriesQuery);
$activeInventories = "";

// Check if Calgary is among the active inventories
$isCalgaryActive = false;
$isEdmontonActive = false;
$isTorontoActive = false;
$isKelownaActive = false;
if (!empty($activeInventoriesResult)) {
	foreach ($activeInventoriesResult as $row) {
		$activeInventories .= "<p id=\"stock_link_" . strtolower($row->title) . "\">" . ucwords(strtolower($row->title)) . "</p>";
		if (strtolower($row->title) === 'calgary') {
			$isCalgaryActive = true;
		}
		if (strtolower($row->title) === 'edmonton') {
			$isEdmontonActive = true;
		}
		if (strtolower($row->title) === 'toronto') {
			$isTorontoActive = true;
		}
		if (strtolower($row->title) === 'kelowna') {
			$isKelownaActive = true;
		}
	}
}
// Fetch inactive inventories
$inactiveInventoriesQuery = "SELECT * FROM inventories WHERE status = 0";
$inactiveInventoriesResult = $db->getRows($inactiveInventoriesQuery);
$inactiveInventories = "";
$isCalgaryInActive = false;
$isEdmontonInActive = false;
$isTorontoInActive = false;
$isKelownaInActive = false;
if (!empty($inactiveInventoriesResult)) {
	foreach ($inactiveInventoriesResult as $rowN) {
		$inactiveInventories .= "<p id=\"stock_link_" . strtolower($rowN->title) . "\">" . ucwords(strtolower($rowN->title)) . "</p>";
		if (strtolower($row->title) === 'calgary') {
			$isCalgaryInActive = true;
		}
		if (strtolower($rowN->title) == 'edmonton') {
			$isEdmontonInActive = true;
		}
		if (strtolower($rowN->title) === 'toronto') {
			$isTorontoInActive = true;
		}
		if (strtolower($rowN->title) === 'kelowna') {
			$isKelownaInActive = true;
		}
	}
}


$customersHTML = '';
$customersHTML .= '<option value="">Please Select</option>';

foreach ($customers as $customer) {
	$customersHTML .= '<option value="' . $customer->id . '">' . $customer->username . '</option>';
}
