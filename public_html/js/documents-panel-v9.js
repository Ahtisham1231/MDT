"use strict"

let timeOutHandler;

function clearPHPMsg() {
	if (timeOutHandler) {
		clearTimeout(timeOutHandler);
	}
	$('.phpMsg').hide();
}

function clearAllObjectsAndInputs() {

	//	clear supplier invoice form
	listOfProducts = {};
	$('#form_requisition_to_supplier .for_fill_out_with_products').html('');
	$('#supplierInvoiceDateInput').val('');
	$('#supplierInvoiceSupplierSelect').val('');
	$('#supplierInvoiceProductQuantityInput').val('');
	$('#supplierInvoiceProductsSelection').val('');
	$('#supplierInvoiceProductPriceInput').val('');
	$('#supplierInvoicePriceTotal').val('');

	//	clear customer invoice form
	listOfProductsCustomer = {};
	$('#form_requisition_to_customer .for_fill_out_with_products').html('');
	$('#customerInvoiceDateInput').val('');
	$('#customerInvoiceCustomerSelect').val('');
	$('#customerInvoiceProductQuantityInput').val('');
	$('#customerInvoiceProductsSelection').val('');
	$('#customerInvoiceProductPriceInput').val('');
	$('#customerInvoicePriceTotal').val('');

	//	clear return of products form
	listOfProductsReturn = {};
	$('#return_of_products_form .for_fill_out_with_products').html('');
	$('#return_invoice_date_input').val('');
	$('#return_invoice_select_supplier_customer').val('');
	$('#return_invoice_product_quantity_input').val('');
	$('#return_invoice_products_selection').val('');
	$('#return_invoice_product_price_input').val('');
	$('#return_invoice_select_type').val('N/A');
	$('#return_invoice_price_total').val('');

	$('#return_type_selection_wrapper').hide();
}

/* Supplier invoice start */

let listOfProducts 	= {};
let orderNumber 	= 1;
function addValuesToObjectAndAddRow(productID, product, quantity, price) {
	
	listOfProducts[orderNumber] = {
		"productID" : productID,
		"quantity" 	: quantity,
		"price" 	: price
	};

	let html = `
		<div class="productRow disFlex" data-row="${orderNumber}">
			<div data-row="${orderNumber}" class="propAbsForErase">x</div>
			<div class="name_of_prod_2 propListOrder" class="">
				<span>${product}</span>
			</div>
			<div class="kg_of_prod_2 margLeft propListOrder1">
				<span>${quantity}</span>
			</div>
			<div class="price_of_prod_2 margLeft propListOrder1">
				<span>${price}$</span>
			</div>
		</div>
	`;

	$('#form_requisition_to_supplier .for_fill_out_with_products').append(html);
	orderNumber++;
}

function deleteRowAndObjectElement(dataNo) {
	delete listOfProducts[dataNo];
	$(`[data-row=${dataNo}]`).remove();
}

//	product selection
$('#supplierInvoiceProductsSelection').on('change', function() {

	let element = $(this);
	let id 		= element.val();
	let price 	= element.find(':selected').data('price');

	$('#supplierInvoiceProductPriceInput').val(price);
});

//	delete row
$('#form_requisition_to_supplier .for_fill_out_with_products').on("click", ".propAbsForErase", function(){
	let dataNo = $(this).data('row');

	//	adjust total price
	let currentTotal 	= $('#supplierInvoicePriceTotal').val();
	let currentTotalInt = currentTotal ?  parseInt(currentTotal) : 0;
	let newTotal 		= parseInt(currentTotalInt - (listOfProducts[dataNo].quantity * listOfProducts[dataNo].price));
	$('#supplierInvoicePriceTotal').val(newTotal);

	deleteRowAndObjectElement(dataNo);
})

//	add new product to invoice
$('#addProduct').on('click', function() {
	$('#labelsContainer').show();
	let quantity 	= parseFloat( $('#supplierInvoiceProductQuantityInput').val() );
	let price 		= parseFloat( $('#supplierInvoiceProductPriceInput').val());
	let productID 	= $('#supplierInvoiceProductsSelection').find(':selected').val();
	let product 	= $('#supplierInvoiceProductsSelection').find(':selected').html();

	if ( !isNaN(quantity) && (quantity > 0) && !isNaN(price) && (price > 0) && (product != 'Please Select') ) {

		$('#supplierInvoiceProductQuantityInput, #supplierInvoiceProductPriceInput, #supplierInvoiceProductsSelection').removeClass("inputError");
		addValuesToObjectAndAddRow(productID, product, quantity, price, orderNumber);
		$('#supplierInvoiceProductQuantityInput, #supplierInvoiceProductPriceInput').val('');
		$('#supplierInvoiceProductsSelection').val('0');

		//	adjust total price
		let currentTotal 	= parseFloat($('#supplierInvoicePriceTotal').val());
		let currentTotalInt = currentTotal ?  currentTotal : 0;
		let newTotal 		= Math.round( 100 * (currentTotalInt + (quantity * price)) ) / 100;

		$('#supplierInvoicePriceTotal').val(newTotal);
		$('#addProduct').removeClass("inputError");

	} else {

		if (isNaN(quantity) || (quantity <= 0) ) {
			$('#supplierInvoiceProductQuantityInput').addClass("inputError");
		}

		if (isNaN(price) || (price < 0) ) {
			$('#supplierInvoiceProductPriceInput').addClass("inputError");
		}

		if (product == 'Please Select' ) {
			$('#supplierInvoiceProductsSelection').addClass("inputError");
		}
	}
});

//	supplier invoice submit
$('#form_requisition_to_supplier').on('submit', function(e) {

	e.preventDefault();

	clearPHPMsg();

	let objForPHP = {};

	objForPHP.supplierInvoiceSubmit = true;
	objForPHP.supplierID 			= $('#supplierInvoiceSupplierSelect :selected').val();
	objForPHP.total 				= $('#supplierInvoicePriceTotal').val();
	objForPHP.date 					= $('#supplierInvoiceDateInput').val();
	objForPHP.note 					= $('#supplierInvoiceNoteInput').val();
	objForPHP.inventory				= $('#supplierInvoiceProductsinverntories').val();
	objForPHP.products				= listOfProducts;
	if ($.isEmptyObject(listOfProducts)) {
		$('#addProduct').addClass("inputError");
		return;
	}

	$.post('../php/ajax.php', objForPHP, null, 'json').done(function(response) {

		listOfProducts = {};

		$('#form_requisition_to_supplier .for_fill_out_with_products').html('');

		$('#supplierInvoiceDateInput').val('');
		$('#supplierInvoiceNoteInput').val('');
		$('#supplierInvoiceSupplierSelect').val('');
		$('#supplierInvoiceProductQuantityInput').val('');
		$('#supplierInvoiceProductsSelection').val('');
		$('#supplierInvoiceProductPriceInput').val('');
		$('#supplierInvoicePriceTotal').val('');

		if (response.ok == '1') {

			$('#supplierInvoiceMsg').html(response.html);
			$('#supplierInvoiceMsg').show(400);

			timeOutHandler = setTimeout(function() {
				$('#supplierInvoiceMsg').hide(400);
			}, 4000);

		} else {

			let html = `
				<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
					<i style="color: red" class="fas fa-exclamation-triangle"></i> Application error.
				</div>`
			;

			$('#supplierInvoiceMsg').html(html);
			$('#supplierInvoiceMsg').show(400);

			timeOutHandler = setTimeout(function() {
				$('#supplierInvoiceMsg').hide(400);
			}, 4000);
		}

		//	refresh supplier prices for products
		let post = $.post('../php/ajax.php', {'getNewSupplierPrices' : true}, null, 'json');
		post.done(function(response) {

			$('#supplierInvoiceProductsSelection').html(response);
		});
	});
});

/* Supplier invoice end */




/* Customer invoice start */

let listOfProductsCustomer 	= {};
let orderNumberCustomer 	= 100001;

function addValuesToObjectAndAddRowCustomer(productID, product, quantity, price) {

	listOfProductsCustomer[orderNumberCustomer] = {
		"productID" : productID,
		"quantity" : quantity,
		"price" : price,
		"rowTotal" : price * quantity
	};

	let html = `
		<div class="productRow disFlex" data-row="${orderNumberCustomer}">
			<div data-row="${orderNumberCustomer}" class="propAbsForErase">x</div>
			<div class="name_of_prod_2 propListOrder" class="">
				<span>${product}</span>
			</div>
			<div class="kg_of_prod_2 margLeft propListOrder1">
				<span>${quantity}</span>
			</div>
			<div class="price_of_prod_2 margLeft propListOrder1">
				<span>${price}$</span>
			</div>
			<div class="total_of_prod_2 margLeft propListOrder1">
				<span>${price * quantity}$</span>
			</div>
		</div>
	`;

	$('#form_requisition_to_customer .for_fill_out_with_products').append(html);
	orderNumberCustomer++;
}

function deleteRowAndObjectElementCustomer(dataNo) {
	delete listOfProductsCustomer[dataNo];
	$(`[data-row=${dataNo}]`).remove();
}

//	product selection
$('#customerInvoiceProductsSelection').on('change', function() {

	let element = $(this);
	let id 		= element.val();
	let price 	= element.find(':selected').data('price');

	$('#customerInvoiceProductPriceInput').val(price);
});

//	delete row
$('#form_requisition_to_customer .for_fill_out_with_products').on("click", ".propAbsForErase", function(){

	let dataNo = $(this).data('row');

	//	adjust total price
	let currentTotal 	= $('#customerInvoicePriceTotal').val();
	let currentTotalInt = currentTotal ?  parseInt(currentTotal) : 0;
	let newTotal 		= parseInt(currentTotalInt - (listOfProductsCustomer[dataNo].quantity * listOfProductsCustomer[dataNo].price));
	$('#customerInvoicePriceTotal').val(newTotal);

	deleteRowAndObjectElementCustomer(dataNo);
})

//	add new product to invoice
$('#addProductCustomer').on('click', function() {
	$('#customerInvoiceProductsinverntories').prop('disabled', true);

	$('#labelsContainerCustomer').show();
	let quantity 	= parseFloat( $('#customerInvoiceProductQuantityInput').val() );
	let price 		= parseFloat( $('#customerInvoiceProductPriceInput').val());
	let productID 	= $('#customerInvoiceProductsSelection').find(':selected').val();
	let product 	= $('#customerInvoiceProductsSelection').find(':selected').html();
	var inventory   = $('#customerInvoiceProductsinverntories').val();
	if ( ! isNaN(quantity) && (inventory != '') ){
		let objForPHP 	= {
			'checkInventoryQuantity' : true,
			'inventory' 		: inventory,
			'quantity' 		    : quantity,
			'productID' 		: productID
		};
		console.log('yes');
		let post = $.post('../php/ajax.php', objForPHP, '', 'json');
		post.done(function(response) {
			if (response.ok == '0') {
				
				$('#customerInvoiceProductsSelection').addClass("inputError");
				
					$('#qtyerror').html(response.html);
					$('#qtyerror').show(400);
		
					timeOutHandler = setTimeout(function() {
						$('#qtyerror').hide(400);
					}, 4000);
					
				
			}else{
				if ( ! isNaN(quantity) && (quantity > 0) && ! isNaN(price) && (price > 0) && (product != 'Please Select')) {

					$('#customerInvoiceProductQuantityInput, #customerInvoiceProductPriceInput, #customerInvoiceProductsSelection').removeClass("inputError");
					addValuesToObjectAndAddRowCustomer(productID, product, quantity, price, orderNumberCustomer);
					$('#customerInvoiceProductQuantityInput, #customerInvoiceProductPriceInput').val('');
					$('#customerInvoiceProductsSelection').val('0');
			
					//	adjust total price
					let currentTotal 	= parseFloat($('#customerInvoicePriceTotal').val());
					let currentTotalInt = currentTotal ?  currentTotal : 0;
					let newTotal 		= Math.round( 100 * (currentTotalInt + (quantity * price)) ) / 100;
			
					$('#customerInvoicePriceTotal').val(newTotal);
					$('#customerInvoiceProductsSelection').removeClass("inputError");
					$('#addProductCustomer').removeClass("inputError");
			
				} else {
			
					if (isNaN(quantity) || (quantity <= 0)) {
						$('#customerInvoiceProductQuantityInput').addClass("inputError");
					}
			
					if (isNaN(price) || (price < 0)) {
						$('#customerInvoiceProductPriceInput').addClass("inputError");
					}
			
					if (product == 'Please Select') {
						$('#customerInvoiceProductsSelection').addClass("inputError");
					}
				}
			}
			
		});
	}
	
	
	
});

//	ajax get correct prices on customer select
$('#customerInvoiceCustomerSelect').on('change', function() {

	let customerID 	= $(this).val();
	let objForPHP 	= {
		'getCustomerPrices' : true,
		'customerID' 		: customerID
	};

	let post = $.post('../php/ajax.php', objForPHP, '', 'json');
	post.done(function(response) {

		$('#customerInvoiceProductsSelection').html('');
		$('#customerInvoiceProductsSelection').append(response);
		$('#customerInvoiceProductsSelection').val('0');
		$('#customerInvoiceProductPriceInput').val('');
	});
});

//	customer invoice submit
$('#form_requisition_to_customer').on('submit', function(e) {
	$('#customerInvoiceProductsinverntories').prop('disabled', false);
	
	e.preventDefault();

	clearPHPMsg();

	let objForPHP = {};

	objForPHP.customerInvoiceSubmit = true;
	objForPHP.customerID 			= $('#customerInvoiceCustomerSelect :selected').val();
	objForPHP.total 				= $('#customerInvoicePriceTotal').val();
	objForPHP.date 					= $('#customerInvoiceDateInput').val();
	objForPHP.note 					= $('#customerInvoiceNoteInput').val();
	objForPHP.note 					= $('#customerInvoiceNoteInput').val();
	objForPHP.inventory 					= $('#customerInvoiceProductsinverntories').val();
	objForPHP.products				= listOfProductsCustomer;

	if ($.isEmptyObject(listOfProductsCustomer)) {

		$('#addProductCustomer').addClass("inputError");
		return;
	}

	$.post('../php/ajax.php', objForPHP, null, 'json').done(function(response) {

		listOfProductsCustomer = {};

		$('#form_requisition_to_customer .for_fill_out_with_products').html('');
		$('#customerInvoiceProductsinverntories').val('');
		$('#customerInvoiceDateInput').val('');
		$('#customerInvoiceNoteInput').val('');
		$('#customerInvoiceCustomerSelect').val('');
		$('#customerInvoiceProductQuantityInput').val('');
		$('#customerInvoiceProductsSelection').val('');
		$('#customerInvoiceProductPriceInput').val('');
		$('#customerInvoicePriceTotal').val('');

		if (response.ok == '1') {

			$('#customerInvoiceMsg').html(response.html);
			$('#customerInvoiceMsg').show(400);

			timeOutHandler = setTimeout(function() {
				$('#customerInvoiceMsg').hide(400);
			}, 4000);

		} else {

			let html = `
				<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
					<i style="color: red" class="fas fa-exclamation-triangle"></i> Application error.
				</div>`
			;

			$('#customerInvoiceMsg').html(html);
			$('#customerInvoiceMsg').show(400);

			timeOutHandler = setTimeout(function() {
				$('#customerInvoiceMsg').hide(400);
			}, 4000);
		}
	});
});

/* Customer invoice end */



/* Payment to supplier start  */

$('#submit_for_order_4').on('click', function(e) {

	e.preventDefault();

	clearPHPMsg();

	let date 		= $('#supplierPaymentDate').val();
	let amount 		= $('#supplierPaymentAmount').val();
	let supplierID 	= $('#supplierPaymentSelect').val();

	let objForPHP = {
		'supplierPayment' 	: true,
		'date' 				: date,
		'amount' 			: amount,
		'supplierID' 		: supplierID,
	};

	let post = $.post('../php/ajax.php', objForPHP, null, 'json');
	post.done(function(response) {

		if (response.ok == '1') {

			$('#supplierPaymentDate').val('');
			$('#supplierPaymentAmount').val('');
			$('#supplierPaymentSelect').val('');

			$('#supplierPaymentMsg').html(response.html);
			$('#supplierPaymentMsg').show(400);

			timeOutHandler = setTimeout(function() {
				$('#supplierPaymentMsg').hide(400);
			}, 4000);

		} else {

			let html = `
				<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
					<i style="color: red" class="fas fa-exclamation-triangle"></i> Application error.
				</div>`
			;

			$('#supplierPaymentMsg').html(html);
			$('#supplierPaymentMsg').show(400);

			timeOutHandler = setTimeout(function() {
				$('#supplierPaymentMsg').hide(400);
			}, 4000);
		}
	});
});

/* Payment to supplier end */




/* Payment from customer start  */

$('#submit_for_order_3').on('click', function(e) {

	e.preventDefault();

	clearPHPMsg();

	let date 		= $('#customerPaymentDate').val();
	let amount 		= $('#customerPaymentAmount').val();
	let customerID 	= $('#customerPaymentSelect').val();

	let objForPHP = {
		'customerPayment' 	: true,
		'date' 				: date,
		'amount' 			: amount,
		'customerID' 		: customerID,
	};

	let post = $.post('../php/ajax.php', objForPHP, null, 'json');
	post.done(function(response) {

		if (response.ok == '1') {

			$('#customerPaymentDate').val('');
			$('#customerPaymentAmount').val('');
			$('#customerPaymentSelect').val('');

			$('#customerPaymentMsg').html(response.html);
			$('#customerPaymentMsg').show(400);

			timeOutHandler = setTimeout(function() {
				$('#customerPaymentMsg').hide(400);
			}, 4000);

		} else {

			let html = `
				<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
					<i style="color: red" class="fas fa-exclamation-triangle"></i> Application error.
				</div>`
			;

			$('#customerPaymentMsg').html(html);
			$('#customerPaymentMsg').show(400);

			timeOutHandler = setTimeout(function() {
				$('#customerPaymentMsg').hide(400);
			}, 4000);
		}
	});
});

/* Payment from customer end */






/* Return of products start */

//	invoice type select
$('#return_invoice_select_type').on('change', function() {

	let element 	= $(this);
	let selected 	= element.val();

	if (selected == 'N/A') {
		$('#return_type_selection_wrapper').hide(400);
		return;
	}

	if (selected == '0') {
		$('#return_type_selection_wrapper h4').text('Supplier');
	}

	if (selected == '1') {
		$('#return_type_selection_wrapper h4').text('Customer');
	}

	//	adjust next slection data attribute
	$('#return_invoice_select_supplier_customer').data('id', selected);

	let objForPHP = {
		'getInvoiceTypeSelection' 	: true,
		'invoiceType' 				: selected
	};

	let post = $.post('../php/ajax.php', objForPHP, null, 'json');
	post.done(function(response) {

		$('#return_invoice_select_supplier_customer').html(response);
		$('#return_type_selection_wrapper').show(400);
	});

});

//	customer / suppler select
$('#return_invoice_select_supplier_customer').on('change', function() {

	let element 	= $(this);
	let invoiceType = element.data('id');
	let selected 	= element.val();

	if (selected == '') {
		return;
	}

	let objForPHP = {
		'getProductsAndCorrectPrices'	: true,
		'invoiceType' 				 	: invoiceType,
		'id' 				 			: selected,
	};

	//console.log(objForPHP);

	let post = $.post('../php/ajax.php', objForPHP, null, 'json');
	post.done(function(response) {

		$('#return_invoice_products_selection').html(response);
		$('#return_invoice_products_selection').val('0');
		$('#return_invoice_product_price_input').val('');
	});
});

//	product selection
$('#return_invoice_products_selection').on('change', function() {

	let element = $(this);
	let id 		= element.val();
	let price 	= element.find(':selected').data('price');

	$('#return_invoice_product_price_input').val(price);
});

let listOfProductsReturn 	= {};
let orderNumberReturn 		= 100001;

function addValuesToObjectAndAddRowReturn(productID, product, quantity, price) {

	listOfProductsReturn[orderNumberReturn] = {
		"productID" : productID,
		"quantity" 	: quantity,
		"price" 	: price
	};

	let html = `
		<div class="productRow disFlex" data-row="${orderNumberReturn}">
			<div data-row="${orderNumberReturn}" class="propAbsForErase">x</div>
			<div class="name_of_prod_2 propListOrder" class="">
				<span>${product}</span>
			</div>
			<div class="kg_of_prod_2 margLeft propListOrder1">
				<span>${quantity}</span>
			</div>
			<div class="price_of_prod_2 margLeft propListOrder1">
				<span>${price}$</span>
			</div>
		</div>
	`;

	$('#return_of_products_form .for_fill_out_with_products').append(html);
	orderNumberReturn++;
}

function deleteRowAndObjectElementReturn(dataNo) {
	delete listOfProductsReturn[dataNo];
	$(`[data-row=${dataNo}]`).remove();
}

//	delete row
$('#return_of_products_form .for_fill_out_with_products').on("click", ".propAbsForErase", function(){

	let dataNo = $(this).data('row');

	//	adjust total price
	let currentTotal 	= $('#return_invoice_price_total').val();
	let currentTotalInt = currentTotal ?  parseInt(currentTotal) : 0;
	let newTotal 		= parseInt(currentTotalInt - (listOfProductsReturn[dataNo].quantity * listOfProductsReturn[dataNo].price));
	$('#return_invoice_price_total').val(newTotal);

	deleteRowAndObjectElementReturn(dataNo);
});

//	add new product to invoice
$('#add_product').on('click', function() {
	
	let quantity 	= parseFloat( $('#return_invoice_product_quantity_input').val() );
	let price 		= parseFloat( $('#return_invoice_product_price_input').val());
	let productID 	= $('#return_invoice_products_selection').find(':selected').val();
	let product 	= $('#return_invoice_products_selection').find(':selected').html();
	
	if ( ! isNaN(quantity) && (quantity > 0) && ! isNaN(price) && (price > 0) && (product != 'Please Select')) {
		$('#labelsContainerReturn').show();
		$('#return_invoice_product_quantity_input, #return_invoice_product_price_input, #return_invoice_products_selection').removeClass("inputError");
		addValuesToObjectAndAddRowReturn(productID, product, quantity, price, orderNumberReturn);
		$('#return_invoice_product_quantity_input, #return_invoice_product_price_input').val('');
		$('#return_invoice_products_selection').val('0');

		//	adjust total price
		let currentTotal 	= parseFloat($('#return_invoice_price_total').val());
		let currentTotalInt = currentTotal ?  currentTotal : 0;
		let newTotal 		= Math.round( 100 * (currentTotalInt + (quantity * price)) ) / 100;

		$('#return_invoice_price_total').val(newTotal);
		$('#add_product').removeClass("inputError");

	} else {

		if (isNaN(quantity) || (quantity <= 0)) {
			$('#return_invoice_product_quantity_input').addClass("inputError");
		}

		if (isNaN(price) || (price < 0)) {
			$('#return_invoice_product_price_input').addClass("inputError");
		}

		if (product == 'Please Select') {
			$('#return_invoice_products_selection').addClass("inputError");
		}
	}
});

//	return invoice submit
$('#return_of_products_form').on('submit', function(e) {

	e.preventDefault();

	clearPHPMsg();

	let objForPHP = {};

	objForPHP.returnInvoiceSubmit 	= true;
	objForPHP.invoiceType 			= $('#return_invoice_select_supplier_customer').data('id');
	objForPHP.id 					= $('#return_invoice_select_supplier_customer :selected').val();
	objForPHP.total 				= $('#return_invoice_price_total').val();
	objForPHP.date 					= $('#return_invoice_date_input').val();
	objForPHP.inventory 			= $('#returnInvoiceProductsinverntories').val();
	objForPHP.products				= listOfProductsReturn;

	if ($.isEmptyObject(listOfProductsReturn)) {
		$('#add_product').addClass("inputError");
		return;
	}

	//console.log(objForPHP);

	$.post('../php/ajax.php', objForPHP, null, 'json').done(function(response) {

		listOfProductsReturn = {};

		$('#return_invoice_date_input').val('');
		$('#return_invoice_select_supplier_customer').val('');
		$('#return_invoice_product_quantity_input').val('');
		$('#return_invoice_products_selection').val('');
		$('#return_invoice_product_price_input').val('');

		$('#return_of_products_form .for_fill_out_with_products').html('');
		$('#return_invoice_select_type').val('N/A');
		$('#return_invoice_select_type').trigger('change');
		$('#return_invoice_price_total').val('');

		if (response.ok == '1') {

			$('#returnProductMsg').html(response.html);
			$('#returnProductMsg').show(400);

			timeOutHandler = setTimeout(function() {
				$('#returnProductMsg').hide(400);
			}, 4000);

		} else {

			let html = `
				<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
					<i style="color: red" class="fas fa-exclamation-triangle"></i> Application error.
				</div>`
			;

			$('#returnProductMsg').html(html);
			$('#returnProductMsg').show(400);

			timeOutHandler = setTimeout(function() {
				$('#returnProductMsg').hide(400);
			}, 4000);
		}
	});
});

/* Return of products end */





/*  Set inventories start */

$('#inventories_link').on('click', function(e) {

	e.preventDefault();

	let objForPHP = {
		'getProductsForTransferInfo' : true
	};

	let post = $.post('../php/ajax.php', objForPHP, '', 'json');
	post.done(function(response) {

		$('#set_inventories_product_selection').html(response);

	});
});

$('#set_inventories_product_selection').on('change', function() {

	clearPHPMsg();

	let option 		= $(this, 'option:selected');
	let productID 	= parseInt(option.val());

	let objForPHP = {
		'getInventoryInfoForTransfer' 	: true,
		'productID' 					: productID
	};

	if ( ! productID) {
		$('#calgaryInput').val('');
		$('#edmontonInput').val('');
		$('#torontoInput').val('');
		$('#kelownaInput').val('');
		return;
	}

	let post = $.post('../php/ajax.php', objForPHP, null, 'json');
	post.done(function(response) {

		let calgary 	= $('#calgaryInput');
		let edmonton 	= $('#edmontonInput');
		let toronto 	= $('#torontoInput');
		let kelowna 	= $('#kelownaInput');

		calgary.val(parseFloat(response.calgary));
		edmonton.val(parseFloat(response.edmonton));
		kelowna.val(parseFloat(response.kelowna));
		toronto.val(parseFloat(response.toronto));
	});
});

$('#set_button').on('click', function(e) {

	e.preventDefault();

	clearPHPMsg();
	
	let option 		= $('#set_inventories_product_selection option:selected');
	let productID 	= option.val();
	let calgary 	= $('#calgaryInput').val();
	let edmonton 	= $('#edmontonInput').val();
	let toronto 	= $('#torontoInput').val();
	let kelowna 	= $('#kelownaInput').val();

	let objForPHP = {
		'setInventories' 	: true,
		'productID' 		: productID,
		'calgary' 			: calgary,
		'edmonton' 			: edmonton,
		'toronto' 			: toronto,
		'kelowna' 			: kelowna,
	};

	if (! parseInt(productID)) {

		let html = `
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Please select product.
			</div>`
		;

		$('#setInventoriesMsg').html(html);
		$('#setInventoriesMsg').show(400);

		timeOutHandler = setTimeout(function() {
			$('#setInventoriesMsg').hide(400);
		}, 4000);

		return;
	}

	let post = $.post('../php/ajax.php', objForPHP, null, '');
	post.done(function(response) {

		$('#setInventoriesMsg').html(response);
		$('#setInventoriesMsg').show(400);

		timeOutHandler = setTimeout(function() {
			$('#setInventoriesMsg').hide(400);
		}, 4000);
	});
});

/* Set inventories end */

//	Click handlers
$('#supplier_invoice_link').on('click', function() {
	$('.documentsRightSides').hide();
	$('#form_requisition_to_supplier').show();
});
$('#customer_invoices_link').on('click', function() {
	$('.documentsRightSides').hide();
	$('#form_requisition_to_customer').show();
});
$('#payment_to_me_link').on('click', function() {
	$('.documentsRightSides').hide();
	$('#form_3_main_wrapp').show();
});
$('#I_pay_to_link').on('click', function() {
	$('.documentsRightSides').hide();
	$('#form_4_main_wrapp').show();
});
$('#inventories_link').on('click', function() {
	$('.documentsRightSides').hide();
	$('#set_inventories_wrapper').show();
});
$('#return_of_products_link').on('click', function() {
	$('.documentsRightSides').hide();
	$('#return_of_products_form').show();
});

//	remove all msgs on any click in side bar
$('#form_thumb_main_wrapp h5').on('click', function() {
	clearPHPMsg();
	clearAllObjectsAndInputs();
});

$(function() {

	//	Warning Duplicate IDs
	// $('[id]').each(function() {
		// let ids = $('[id="' + this.id + '"]');
		// if (ids.length > 1 && ids[0] == this) {
			// console.warn('Multiple IDs #' +this.id);
		// }
	// });

	// $('[name]').each(function() {
		// let names = $('[name="' + this.name + '"]');
	    // if (names.length > 1 && names[0] == this) {
			// console.warn('Multiple names #' + this.name);
		// }
	// });

	let docHeight 	= $(window).height();
	let navHeight 	= $('#main_nav_wrapp').outerHeight();
	let footHeight 	= $('#footer_main').outerHeight();

	$('#documents_inner').css("minHeight", `${docHeight - navHeight - footHeight}px`);

});