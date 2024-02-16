<?php
userLoggedIn();

if ( ! isUser()) {
	goLogin();
}

$pageTitle = 'MDT - User Panel';

$sql = "
	SELECT
		p.id,
		p.name,
		up.price AS price,
		s.supplier_price
	FROM
		products AS p
	JOIN
		users_prices AS up
	ON
		p.id = up.product_id
	JOIN
		stock AS s
	ON
		p.id = s.product_id
	WHERE
		up.user_id = :id
	AND
		up.price <> 0.00
	AND
		s.supplier_price <> 0.00
	AND
		p.active = 1
	"
;

$products = $db->getRows($sql, ['id' => $_SESSION['userID']]);

$productsHTML = '';
$productsHTML .= '<option data-price="0" value="0">Please Select</option>';

foreach ($products as $product) {
	$productsHTML .= '<option data-price="' . $product->price . '" value="' . $product->id . '">' . $product->name . '</option>';
}
?>