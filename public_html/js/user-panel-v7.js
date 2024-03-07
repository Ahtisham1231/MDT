"use strict"

let timeOutHandler;

function clearPHPMsg() {
	if (timeOutHandler) {
		clearTimeout(timeOutHandler);
	}
	$('.phpMsg').hide();
}

/* Customer invoice start */

let listOfProducts = {};
let orderNumber = 1;
function addValuesToObjectAndAddRow(productID, product, quantity, price) {
	listOfProducts[orderNumber] = {
		"productID" : productID,
		"quantity" : quantity,
		"price" : price
	};

	let html = `<div class="productRow disFlex" data-row="${orderNumber}">
					<div data-row="${orderNumber}" class="propAbsForErase">x</div>
					<div class="name_of_prod_2 propListOrder" class=""><span>${product}</span></div>
					<div class="kg_of_prod_2 margLeft propListOrder1"><span>${quantity}g</span></div>
					<div class="price_of_prod_2 margLeft propListOrder1"><span>${price}$</span></div>
				</div>`
	$('.for_fill_out_with_products').append(html);
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
$('.for_fill_out_with_products').on("click", ".propAbsForErase", function(){
	let dataNo = $(this).data('row');

	//	adjust total price
	let currentTotal 	= $('#supplierInvoicePriceTotal').val();
	let currentTotalInt = currentTotal ?  parseInt(currentTotal) : 0;
	let newTotal 		= parseInt(currentTotalInt - (listOfProducts[dataNo].quantity * listOfProducts[dataNo].price));
	$('#supplierInvoicePriceTotal').val(newTotal);

	deleteRowAndObjectElement(dataNo);
});

//	add new product to invoice
$('#addProduct').on('click', function() {

	$('#supplierInvoiceProductQuantityInput, #supplierInvoiceProductPriceInput, #supplierInvoiceProductsSelection').removeClass('inputError');


	let quantity 	= parseFloat( $('#supplierInvoiceProductQuantityInput').val() );
	let price 		= parseFloat( $('#supplierInvoiceProductPriceInput').val());
	let productID 	= $('#supplierInvoiceProductsSelection').find(':selected').val();
	let product 	= $('#supplierInvoiceProductsSelection').find(':selected').html();

	if ( !isNaN(quantity) && (quantity > 0) && !isNaN(price) && (price > 0) && (product != 'Please Select')) {

		//$('#supplierInvoiceProductQuantityInput, #supplierInvoiceProductPriceInput, #supplierInvoiceProductsSelection').css("border", "1px solid #236000");
		$('#supplierInvoiceProductQuantityInput, #supplierInvoiceProductPriceInput, #supplierInvoiceProductsSelection').removeClass('inputError');
		addValuesToObjectAndAddRow(productID, product, quantity, price, orderNumber);
		$('#supplierInvoiceProductQuantityInput, #supplierInvoiceProductPriceInput').val('');
		$('#supplierInvoiceProductsSelection').val('0');

		//	adjust total price
		let currentTotal 	= parseFloat($('#supplierInvoicePriceTotal').val());
		let currentTotalInt = currentTotal ?  currentTotal : 0;
		let newTotal 		= currentTotalInt + (quantity * price);

		$('#supplierInvoicePriceTotal').val(newTotal);
		// $('#addProduct').css("border", "none");
		$('#addProduct').removeClass('inputError');

	} else {

		if (isNaN(quantity) || (quantity <= 0) ) {
			$('#supplierInvoiceProductQuantityInput').addClass('inputError');
		}

		// if (isNaN(price) || (price < 0) ) {
			// $('#supplierInvoiceProductPriceInput').addClass('inputError');
		// }

		if (product == 'Please Select' ) {
			$('#supplierInvoiceProductsSelection').addClass('inputError');
		}

	}

	//console.log(productID, product, quantity, price);
});
$('#closePopupButton').on('click', function() {
    // Hide the popup when the button is clicked
    $('#popup_supplier_invoice_details').hide();
});
//	customer invoice submit
$('#form_requisition_to_supplier').on('submit', function(e) {

	e.preventDefault();

	clearPHPMsg();

	let objForPHP = {};

	objForPHP.customerInvoiceSubmit = true;
	objForPHP.total 				= $('#supplierInvoicePriceTotal').val();
	objForPHP.total 				= $('#supplierInvoicePriceTotal').val();
	objForPHP.inventory 			= $('#customerInvoiceProductsinverntoriesN').val();
	objForPHP.products				= listOfProducts;

	if ($.isEmptyObject(listOfProducts)) {
		//console.log('no products added');
		$('#addProduct').css("border", "1px solid red");
		return;
	}

	$.post('../php/ajax.php', objForPHP, null, 'json').done(function(response) {

		if (response.ok == '1') {

			listOfProducts = {};
			$('.for_fill_out_with_products').html('');
			$('#supplierInvoicePriceTotal').val('');
			$('#customerInvoiceNoteInput').val('');

			$('#customerInvoiceMsg').html(response.html);
			$('#customerInvoiceMsg').show(400);

			timeOutHandler = setTimeout(function() {
				$('#customerInvoiceMsg').hide(400);
			}, 4000);

		} else {

			let html = `
				<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
					<i style="color: red" class="fas fa-exclamation-triangle"></i> There was an error. Please try again later.
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

let myOrdersTable;

function drawMyOrdersTable() {

	if (myOrdersTable) {
		myOrdersTable.draw();
	} else {
		myOrdersTable = $('#myOrdersTable').DataTable({
			"order"			: [[0, "asc"]],
			"columnDefs" 	: [{
				"targets"  		: 'no-sort',
				"orderable"		: false,
			}],
			"pageLength"	: 50,
			"processing"	: true,
			"serverSide" 	: true,
			"ajax" 			: {
				"url"			: "../php/ajax.php",
				"type"			: "POST",
				"data" 			: {"getMyOrders" : true},
				"dataSrc"		: function(response) {
					$('#myInvoicesTotal').text('Invoices total : ' + response.total);
					$('#myBalance').text('Current debt : ' + response.balance);
					return response.data;
				}
			},
			"deferRender"	: true
		});
	}
}


//	Hide Invoice details popup
// $('body').on("click", function() {
// 	// $('#popup_supplier_invoice_details').hide();
// });
$('#closePopupButton').on('click', function() {
    // Hide the popup when the button is clicked
    $('#popup_supplier_invoice_details').hide();
});

//	Buyer invoice details
$('#user_dash_wrapper').on("click", ".details_buttons", function(){

	let id 			= $(this).data('id');
	let total 		= $(this).data('total');
	let date 		= $(this).data('date');

	let objForPHP = {
		"invoiceID" 			: id,
		"buyerInvoiceDetails" 	: true
	};

	$.post('../php/ajax.php', objForPHP, null, 'json').done(function(response) {

		// console.log(objForPHP);
		// console.log(response);

		let html = ``;

		if (response) {

			$('#supp_inv_id').text(id);
			// $('#supp_inv_supplier').text(supplier);
			$('#supp_inv_total').text(total);
			$('#supp_inv_date').text(date);

			$('#supp_inv_table').html('');

			$.each(response, function(index, value){
				html +=
					`<div class="supp_inv_row">
						<div class="supp_inv_productNumber">
							<p>${index + 1}</p>
						</div>
						<div class="supp_inv_productName">
							<p>${value.name}</p>
						</div>
						<div class="supp_inv_productQuantity">
							<p>${value.quantity} g</p>
						</div>
						<div class="supp_inv_productPrice">
							<p>$${value.price}</p>
						</div>
						<div class="supp_inv_productSum">
							<p>$${value.total}</p>
						</div>
					</div>`;
			});

			$('#supp_inv_table').html(html);
			$('#popup_supplier_invoice_details').show();
		}
	});
});

//	update password form
$('#update_password_form').on('submit', function(e) {

	e.preventDefault();

	clearPHPMsg();

	let oldPassword = $('#old_password_input').val();
	let newPassword = $('#new_password_input').val();

	let objForPHP = {
		'updateMyPassword' 	: true,
		'oldPassword'		: oldPassword,
		'newPassword' 		: newPassword,
	};

	let post = $.post('../php/ajax.php', objForPHP, null, 'json');
	post.done(function(response) {

		$('#old_password_input').val('');
		$('#new_password_input').val('');

		if (response.ok == '1') {

			$('#updatePasswordMsg').html(response.html);
			$('#updatePasswordMsg').show(400);

			timeOutHandler = setTimeout(function() {
				$('#updatePasswordMsg').hide(400);
			}, 4000);

		} else {

			let html = `
				<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
					<i style="color: red" class="fas fa-exclamation-triangle"></i> Application error. Try again later.
				</div>`
			;

			$('#updatePasswordMsg').html(html);
			$('#updatePasswordMsg').show(400);

			timeOutHandler = setTimeout(function() {
				$('#updatePasswordMsg').hide(400);
			}, 4000);
		}
	});
});

//	navigation clicks
$('#make_order_link').on('click', function(e) {

	e.preventDefault();

	$('.userForms').hide();
	$('#form_requisition_to_supplier').show();

});
$('#my_orders_link').on('click', function(e) {

	e.preventDefault();

	$('.userForms').hide();
	drawMyOrdersTable();
	$('#myOrdersTableWrapper').show();

});
$('#update_password_link').on('click', function(e) {

	e.preventDefault();

	$('.userForms').hide();
	$('#update_password_form').show();
});

//	remove all msgs on any click in side bar
$('#form_thumb_main_wrapp h5').on('click', function() {
	clearPHPMsg();
});

$(function() {
	let docHeight = $(window).height();
	let navHeight = $('#main_nav_wrapp').outerHeight();
	let footHeight = $('#footer_main').outerHeight();

	$('#documents_inner').css("minHeight", `${docHeight - navHeight - footHeight}px`);
});