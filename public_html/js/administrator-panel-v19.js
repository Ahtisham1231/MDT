"use strict"

let timeOutHandler;

function clearPHPMsg() {
	if (timeOutHandler) {
		clearTimeout(timeOutHandler);
	}
	$('.phpMsg').hide();
}

/*****************************

	SUPPLIERS TABLES START

*****************************/

let suppliersTable;
function drawSuppliersTable() {

	if (suppliersTable) {
		suppliersTable.draw();
		return;
	}

	suppliersTable = $('#suppliersTable').DataTable({
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
			"data"			: {"getSuppliers" : true},
			"dataSrc"		: function(response) {
				$('#totalDebtToSuppliers').text('Total debt to suppliers: ' + response.total);
				return response.data;
			}
		},
		"deferRender"	: true
	});
}

let supplierBalanceTable;
function drawSupplierBalanceTable(obj) {

	if (supplierBalanceTable) {
		supplierBalanceTable.destroy();
	}

	supplierBalanceTable = $('#supplierBalanceTable').DataTable({
		"lengthChange"	: false,
		"info"			: false,
		"destroy"		: true,
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
			"data"			: obj,
			"dataSrc"		: function(response) {
				$('#supplierName').text(response.supplier + '\'s invoices');
				return response.data;
			}
		},
		"deferRender"	: true
	});
}

let supplierInvoicesTable;
function drawSupplierInvoicesTable() {

	if (supplierInvoicesTable) {
		supplierInvoicesTable.draw();
		return;
	}

	supplierInvoicesTable = $('#supplierInvoicesTable').DataTable({
		"order"			: [[4, "desc"]],
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
			"data"			: {"getSupplierInvoices" : true}
		},
		"deferRender"	: true
	});
}

let editSuppliersTable;
function drawEditSuppliersTable() {

	if (editSuppliersTable) {
		editSuppliersTable.draw();
		return;
	}

	editSuppliersTable = $('#editSuppliersTable').DataTable({
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
			"data"			: {"editSuppliersList" : true},
		},
		"deferRender"	: true
	});
}

let supplierPaymentsTable;
function drawSupplierPaymentsTable() {

	if (supplierPaymentsTable) {
		supplierPaymentsTable.draw();
		return;
	}

	supplierPaymentsTable = $('#supplierPaymentsTable').DataTable({
		"lengthChange"	: false,
		"info"			: false,
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
			"data"			: {"getSupplierPayments" : true},
		},
		"deferRender"	: true
	});
}

/*****************************

	SUPPLIERS TABLES END

*****************************/

/*****************************

	CUSTOMER TABLES START

*****************************/

let customersTable;
function drawCustomersTable() {

	if (customersTable) {
		customersTable.draw();
		return;
	}

	customersTable = $('#customersTable').DataTable({
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
			"data"			: {"getCustomersList" : true},
			"dataSrc"		: function(response) {
				$('#totalCustomersDebt').text('Total customers debt: ' +  response.total);
				return response.data;
			}
		},
		"deferRender"	: true
	});
}

let customerBalanceTable;
function drawCustomerBalanceTable(obj) {

	if (customerBalanceTable) {
		customerBalanceTable.destroy();
	}

	customerBalanceTable = $('#customerBalanceTable').DataTable({
		"lengthChange"	: false,
		"info"			: false,
		"destroy"		: true,
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
			"data"			: obj,
			"dataSrc"		: function(response) {
				$('#customerName').text(response.username + '\'s invoices');
				return response.data;
			}
		},
		"deferRender"	: true
	});
}

let customerInvoicesTable;
function drawCustomerInvoicesTable() {

	if (customerInvoicesTable) {
		customerInvoicesTable.draw();
		return;
	}
	

	customerInvoicesTable = $('#customerInvoicesTable').DataTable({
		"order"			: [[4, "desc"]],
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
			"data"			: {"getCustomerInvoices" : true}
		},
		"deferRender"	: true
	});
}

let editCustomersTable;
function drawEditCustomersTable() {
	if (editCustomersTable) {
		editCustomersTable.draw();
		return;
	}

	editCustomersTable = $('#editCustomersTable').DataTable({
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
			"data"			: {"editCustomersList" : true},
		},
		"deferRender"	: true
	});
}

let customerPaymentsTable;
function drawCustomerPaymentsTable() {

	if (customerPaymentsTable) {
		customerPaymentsTable.draw();
		return;
	}

	customerPaymentsTable = $('#customerPaymentsTable').DataTable({
		"lengthChange"	: false,
		"info"			: false,
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
			"data"			: {"getCustomerPayments" : true},
		},
		"deferRender"	: true
	});
}

/*****************************

	CUSTOMER TABLES END

*****************************/

function buildProductPricesForCustomersTable () {

	$('#productPricesTableWrapper').html('');

	let post = $.post('../php/ajax.php', {"buildProductPricesForCustomersTable" : true}, null, 'json');

	$('#productPricesTableWrapper').hide();

	post.done(function(response) {
		$('#productPricesTableWrapper').html(response);
		initProductPricesForCustomersTable();
		$('#productPricesTableWrapper').show();
	});
}

let productPricesTable;

function initProductPricesForCustomersTable() {

	if (productPricesTable) {
		productPricesTable.destroy();
	}

	productPricesTable = $('#productPricesTable').DataTable({
		"pageLength"    : 100,
		"paginate"    	: true,
		"ordering"      : false,
		"lengthChange"	: false,
		"searching"		: false,
		"info"			: false,
		"destroy"       : true
	});
}

let productsTable;
function drawUpdateProductsTable() {

	if (productsTable) {
		productsTable.draw();
		return;
	}

	productsTable = $('#editProductsTable').DataTable({
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
			"data"			: {"getProducts" : true},
		},
		"deferRender"	: true
	});
}

let stockTable;
function drawStockTable() {

	let total;
	let calgaryTotal;
	let edmontonTotal;
	let torontoTotal;
	let kelownaTotal;

	if (stockTable) {
		stockTable.draw();
		return;
	}
	
	stockTable = $('#stockTable').DataTable({
		
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
			"data"			: {"getStock" : true},
			"dataSrc"		: function(response) {
				total 			= response.total;
				edmontonTotal	= response.edmontonTotal;
				calgaryTotal	= response.calgaryTotal;
				torontoTotal	= response.torontoTotal;
				kelownaTotal	= response.kelownaTotal;
				return response.data;
			}
        },
		"drawCallback"		: function() {
			let api = this.api();
			$('#test').html(total);
			$('#test2').html(total);
			$(api.column(6).footer()).html(calgaryTotal);
			$(api.column(7).footer()).html(edmontonTotal);
			$(api.column(8).footer()).html(torontoTotal);
			$(api.column(9).footer()).html(kelownaTotal);
        },
		"deferRender"	: true
	});
}

// Add event listener to the switch
// Define the toggleCheckbox function
function toggleCheckbox(event) {
    event.preventDefault();

    // Remove the event listener temporarily
    this.removeEventListener("click", toggleCheckbox);

    // Retrieve data attributes
    var invenName = this.getAttribute('data-invenName');
	console.log(invenName);
	// alert('yes');
    var checkboxValue = this.querySelector('input[type="checkbox"]').value;
	if(invenName == 'calgary'){
		var updatecheckbox = 1; 
		if(checkboxValue == 1){
			updatecheckbox = 0;
		}
		$('#calgaryTable').dataTable().fnDestroy(); 
		
		$('#toggleSwitchCalgary').val(updatecheckbox);
		let status = updatecheckbox;
	let total;
	calgaryTable = $('#calgaryTable').DataTable({
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
			"data"			: {"getCalgaryStock" : true, "active" : status},
			"dataSrc"		: function(response) {
				total = response.total;
				return response.data;
			}
		},
		"footerCallback"	: function() {
			let api = this.api();
			$(api.column(4).footer()).html(total);
        },
		"deferRender"	: true
	});
		// alert(checkboxValue);
		
	}
	//edmonton
	if(invenName == 'edmonton'){
		var updatecheckbox = 1;
		if(checkboxValue == 1){
			updatecheckbox = 0;
		}
		
		$('#edmontonTable').dataTable().fnDestroy(); 
		
		$('#toggleSwitchEdmonton').val(updatecheckbox);
		let status = updatecheckbox;
	let total;
	edmontonTable = $('#edmontonTable').DataTable({
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
			"data"			: {"getEdmontonStock" : true, "active" : status},
			"dataSrc"		: function(response) {
				total = response.total;
				return response.data;
			}
		},
		"footerCallback"	: function() {
			let api = this.api();
			$(api.column(4).footer()).html(total);
        },
		"deferRender"	: true
	});
		// alert(checkboxValue);
		
	}
	//toronto
    if(invenName == 'toronto'){
		var updatecheckbox = 1;
		if(checkboxValue == 1){
			updatecheckbox = 0;
		}
		
		$('#torontoTable').dataTable().fnDestroy(); 
		
		$('#toggleSwitchToronto').val(updatecheckbox);
		let status = updatecheckbox;
	let total;
	torontoTable = $('#torontoTable').DataTable({
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
			"data"			: {"getTorontoStock" : true, "active" : status},
			"dataSrc"		: function(response) {
				total = response.total;
				return response.data;
			}
		},
		"footerCallback"	: function() {
			let api = this.api();
			$(api.column(4).footer()).html(total);
        },
		"deferRender"	: true
	});
		// alert(checkboxValue);
		
	}
	//kelowna
	if(invenName == 'kelowna'){
		var updatecheckbox = 1;
		if(checkboxValue == 1){
			updatecheckbox = 0;
		}
		
		$('#kelownaTable').dataTable().fnDestroy(); 
		
		$('#toggleSwitchKelowna').val(updatecheckbox);
		let status = updatecheckbox;
	let total;
	kelownaTable = $('#kelownaTable').DataTable({
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
			"data"			: {"getkelownaStock" : true, "active" : status},
			"dataSrc"		: function(response) {
				total = response.total;
				return response.data;
			}
		},
		"footerCallback"	: function() {
			let api = this.api();
			$(api.column(4).footer()).html(total);
        },
		"deferRender"	: true
	});
		// alert(checkboxValue);
		
	}
	if(invenName == 'combined'){
		let total;
		let calgaryTotal;
		let edmontonTotal;
		let torontoTotal;
		let kelownaTotal;

		var updatecheckbox = 1;
		if(checkboxValue == 1){
			updatecheckbox = 0;
		}
		
		$('#stockTable').dataTable().fnDestroy(); 
		
		$('#toggleSwitchCombined').val(updatecheckbox);
		let status = updatecheckbox;
	stockTable = $('#stockTable').DataTable({
		
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
			"data"			: {"getStock" : true, "active" : status},
			"dataSrc"		: function(response) {
				total 			= response.total;
				edmontonTotal	= response.edmontonTotal;
				calgaryTotal	= response.calgaryTotal;
				torontoTotal	= response.torontoTotal;
				kelownaTotal	= response.kelownaTotal;
				return response.data;
			}
        },
		"drawCallback"		: function() {
			let api = this.api();
			$('#test').html(total);
			$('#test2').html(total);
			$(api.column(6).footer()).html(calgaryTotal);
			$(api.column(7).footer()).html(edmontonTotal);
			$(api.column(8).footer()).html(torontoTotal);
			$(api.column(9).footer()).html(kelownaTotal);
        },
		"deferRender"	: true
	});
		// alert(checkboxValue);
		
	}

    // Toggle the checked state of the checkbox
    this.querySelector('input[type="checkbox"]').checked = !this.querySelector('input[type="checkbox"]').checked;

    // Add the event listener back after a short delay
    setTimeout(() => {
        this.addEventListener("click", toggleCheckbox);
    }, 100);
}

// Get all elements with the toggleSwitch class and attach the event listener
var toggleSwitches = document.querySelectorAll('.toggleSwitch');
toggleSwitches.forEach(function(switchElement) {
    switchElement.addEventListener("click", toggleCheckbox);
});

let calgaryTable;
function drawCalgaryTable(active = 1) {
	
	if (calgaryTable) {
		calgaryTable.draw();
		return;
	}
	let status = active;
	let total;
	calgaryTable = $('#calgaryTable').DataTable({
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
			"data"			: {"getCalgaryStock" : true, "active" : status},
			"dataSrc"		: function(response) {
				total = response.total;
				return response.data;
			}
		},
		"footerCallback"	: function() {
			let api = this.api();
			$(api.column(4).footer()).html(total);
        },
		"deferRender"	: true
	});
}

let edmontonTable;
function drawEdmontonTable(active = 1) {

	if (edmontonTable) {
		edmontonTable.draw();
		return;
	}
	let status = active;
	let total;

	edmontonTable = $('#edmontonTable').DataTable({
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
			"data"			: {"getEdmontonStock" : true, "active" : status},
			"dataSrc"		: function(response) {
				total = response.total;
				return response.data;
			}
		},
		"footerCallback"	: function() {
			let api = this.api();
			$(api.column(4).footer()).html(total);
        },
		"deferRender"	: true
	});
}
let torontoTable;
function drawTorontoTable(active = 1) {

	if (torontoTable) {
		torontoTable.draw();
		return;
	}
	let status = active;
	let total;

	torontoTable = $('#torontoTable').DataTable({
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
			"data"			: {"getTorontoStock" : true, "active" : status},
			"dataSrc"		: function(response) {
				total = response.total;
				return response.data;
			}
		},
		"footerCallback"	: function() {
			let api = this.api();
			$(api.column(4).footer()).html(total);
        },
		"deferRender"	: true
	});
}
let kelownaTable;
function drawKelownaTable(active = 1) {
	if (kelownaTable) {
		kelownaTable.draw();
		return;
	}
	let status = active;
	let total;

	kelownaTable = $('#kelownaTable').DataTable({
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
			"data"			: {"getkelownaStock" : true, "active" : status},
			"dataSrc"		: function(response) {
				total = response.total;
				return response.data;
			}
		},
		"footerCallback"	: function() {
			let api = this.api();
			$(api.column(4).footer()).html(total);
        },
		"deferRender"	: true
	});
}

//	Monthly Balance
function drawMonthlyBalance(response) {
	let htmlForColumn0 = ``;
	let htmlForColumn1 = ``;
	let htmlForColumn2 = ``;
	let htmlForColumn3 = ``;
	let htmlForColumn4 = ``;
	let htmlForColumn5 = ``;
	let htmlForColumn6 = ``;

	let incr = 1;
	$.each(response.products, function(index, value) {

		let hoverHTML1 = `<h4> ${index} </h4>`;
		let hoverHTML0 = `<h4> ${index} </h4>`;

		$.each(value.type1.list, function(index2, value2) {
			hoverHTML1 += `
				<div class="customerHoverRow">
					<div class="customerHoverCol custHoverName">${value2.customer}</div>
					<div class="customerHoverCol custHoverDate">${value2.date}</div>
					<div class="customerHoverCol custHoverQuantity">${value2.quantity}</div>
					<div class="customerHoverCol custHoverPrice">${value2.price.toLocaleString('en-US', {style: 'currency', currency: 'USD'})} </div>
					<div class="customerHoverCol custHoverTotal">${value2.total.toLocaleString('en-US', {style: 'currency', currency: 'USD'})} </div>
				</div>
			`;
		});

		$.each(value.type0.list, function(index2, value2) {
			hoverHTML0 += `
				<div class="customerHoverRow">
					<div class="customerHoverCol custHoverName">${value2.supplier}</div>
					<div class="customerHoverCol custHoverDate">${value2.date}</div>
					<div class="customerHoverCol custHoverQuantity">${value2.quantity}</div>
					<div class="customerHoverCol custHoverPrice">${value2.price.toLocaleString('en-US', {style: 'currency', currency: 'USD'})} </div>
					<div class="customerHoverCol custHoverTotal">${value2.total.toLocaleString('en-US', {style: 'currency', currency: 'USD'})} </div>
				</div>
			`;
		});

		htmlForColumn0 += `<div id="merch_type_subb_${incr}" class="oddClass propHeadRowPara">${index}</div>`;

		htmlForColumn1 += `
			<div id="f_t_1_subb_${incr}" class="oddClass propHeadRowPara custHoverRelative">
				<span>${value.type0.totalWeight}</span>
				<div class='customerBoughtHover'>${hoverHTML0}</div>
			</div>
		`;

		htmlForColumn2 += `<div id="f_t_2_subb_${incr}" class="oddClass propHeadRowPara">${value.type0.totalPrice.toLocaleString('en-US', {style: 'currency', currency: 'USD'})}</div>`;

		htmlForColumn3 += `
			<div id="f_t_3_subb_${incr}" class="oddClass propHeadRowPara custHoverRelative">
				<span>${value.type1.totalWeight}</span>
				<div class='customerBoughtHover'>${hoverHTML1}</div>
			</div>
		`;

		htmlForColumn4 += `<div id="f_t_4_subb_${incr}" class="oddClass propHeadRowPara">${value.type1.totalPrice.toLocaleString('en-US', {style: 'currency', currency: 'USD'})}</div>`;

		htmlForColumn5 += `<div id="f_t_5_subb_${incr}" class="oddClass propHeadRowPara">${value.type1.totalProfit.toLocaleString('en-US', {style: 'currency', currency: 'USD'})}</div>`;
		htmlForColumn6 += `<div id="f_t_6_subb_${incr}" class="oddClass propHeadRowPara">${value.type0.totalWeight - value.type1.totalWeight} </div>`;
	});

	htmlForColumn2 += `<div id="f_t_2_subb_${incr}" class="oddClass propHeadRowParaTotals">${response.totals.type0.toLocaleString('en-US', {style: 'currency', currency: 'USD'})}</div>`;
	htmlForColumn4 += `<div id="f_t_4_subb_${incr}" class="oddClass propHeadRowParaTotals">${response.totals.type1.toLocaleString('en-US', {style: 'currency', currency: 'USD'})}</div>`;
	htmlForColumn5 += `<div id="f_t_5_subb_${incr}" class="oddClass propHeadRowParaTotals">${response.totals.profit.toLocaleString('en-US', {style: 'currency', currency: 'USD'})}</div>`;

	$('#merch_type').html(htmlForColumn0);
	$('#f_t_1').html(htmlForColumn1);
	$('#f_t_2').html(htmlForColumn2);
	$('#f_t_3').html(htmlForColumn3);
	$('#f_t_4').html(htmlForColumn4);
	$('#f_t_5').html(htmlForColumn5);
	$('#f_t_6').html(htmlForColumn6);
}

$('#f_t_3, #f_t_1').on("mouseenter", ".custHoverRelative", function() {

	//	get total bought or sold for this product
	let boughtOrSold  = $(this).find('span').text().trim();

	if (boughtOrSold != 0) {
		$(this).find('.customerBoughtHover').show();
	}
});
$('#f_t_3, #f_t_1').on("mouseleave", ".custHoverRelative", function() {
	$(this).find('.customerBoughtHover').hide();
});
$('#closePopupButton').on('click', function() {
    // Hide the popup when the button is clicked
    $('#popup_supplier_invoice_details').hide();
});
//	Hide Invoice details popup
// $('body').on("click", function() {
// 	// $('#popup_supplier_invoice_details').hide();
// });

/*****************************

	SUPPLIER EVENTS START

*****************************/
//	draws supplierBalanceTable
$('#suppliersTable').on('click', '.supplier_balance_details', function() {

	let element 	= $(this);
	let id			= element.data('id');

	let objForPHP  	= {
		'getAllSupplierInvoices' 	: true,
		'id'						: id
	};

	// console.log(objForPHP);

	$('#supplierBalanceTableWrapper').show();

	drawSupplierBalanceTable(objForPHP);
});

//	supplier invoice details
$('#supplierInvoicesTable, #supplierBalanceTable').on("click", ".details_buttons", function() {

	$('#invoiceNoteOutput').val('');

	let id 			= $(this).data('id');
	let supplier 	= $(this).data('user');
	let total 		= $(this).data('total');
	let date 		= $(this).data('date');

	let objForPHP = {
		"invoiceID" : id,
		"supplierInvoiceDetails" : true
	};

	$.post('../php/ajax.php', objForPHP, null, 'json').done(function(response) {

		// console.log(objForPHP);
		//console.log(response);

		let html = ``;

		if (response) {

			$('#supp_inv_id').text(id);
			// $('#supp_comp_name_id').text(supplier);
			$('#supp_inv_total').text(parseFloat(total).toLocaleString('en-US', {style: 'currency', currency: 'USD'}) );
			$('#supp_inv_date').text(date);

			$('#supp_inv_table').html('');
			$('#invoiceNoteOutput').val(response[0].note);
			// alert('yes');
			
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
							<p>${value.quantity}</p>
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
			$('#supp_inv_row_main').show();
			$('#popup_supplier_invoice_details').show();
		}
	});
});

$('#supplierInvoicesTable').on("click", ".buyer_invoice_status", function() {

	let id 		= $(this).data('id');
	let total 	= $(this).data('total');
	let userID 	= $(this).data('user-id');

	let objForPHP = {
		"id" 					: id,
		"total" 				: total,
		"userID" 				: userID,
		"supplierInvoiceComplete" 	: true
	};

	let post = $.post('../php/ajax.php', objForPHP, null, '');
	post.done(function(response) {

		if (response === '1') {
			drawSupplierInvoicesTable();
		} else {
			console.log('error');
		}
	});
});

$('#supplierPaymentsTable').on("click", ".edit_payment_status", function() {
	let id 		= $(this).data('id');
	let total 	= $(this).data('total');
	let userID 	= $(this).data('user-id');

	let objForPHP = {
		"id" 					: id,
		"total" 				: total,
		"userID" 				: userID,
		"supplierPaymentComplete" 	: true
	};

	let post = $.post('../php/ajax.php', objForPHP, null, '');
	post.done(function(response) {
			console.log(response);
		if (response === '1') {
			drawSupplierPaymentsTable();
		} else {
			console.log('error');
		}
	});
});

$('#customerPaymentsTable').on("click", ".edit_payment_status", function() {
	let id 		= $(this).data('id');
	let total 	= $(this).data('total');
	let userID 	= $(this).data('user-id');

	let objForPHP = {
		"id" 					: id,
		"total" 				: total,
		"userID" 				: userID,
		"customerPaymentComplete" 	: true
	};

	let post = $.post('../php/ajax.php', objForPHP, null, '');
	post.done(function(response) {
			console.log(response);
		if (response === '1') {
			drawCustomerPaymentsTable();
		} else {
			console.log('error');
		}
	});
});

//	get supplier invoice details for editing
$('#supplierInvoicesTable').on("click", ".edit_supplier_invoice", function() {

	clearPHPMsg();

	let id 			= $(this).data('id');
	let date 		= $(this).data('date');
	let total 		= $(this).data('total');
	let supplierID 	= $(this).data('supplier');

	//	adjust form inputs
	$('#supplierInvoiceDateInput').val(date);
	$('#supplierInvoiceSupplierSelect').val(supplierID);
	$('#supplierInvoicePriceTotal').val(total);
	$('#invoiceNumber').data('id', id);
	$('#invoiceNumber').html('Invoice ID ' + id);

	let objForPHP = {
		'getSupplierInvoiceForEditing' 	: true,
		'invoiceID'						: id
	}

	let post = $.post('../php/ajax.php', objForPHP, null, 'json');
	post.done(function(response) {

		console.log(response);

		if (response) {

			$('#form_requisition_to_supplier .for_fill_out_with_products').html('');

			let html = ``;

			$.each(response, function(index, value) {

				html += `
					<div class="productRow disFlex" data-product-id="${value.productID}" data-quantity="${value.quantity}" data-price="${value.price}">
						<div class="propAbsForErase">x</div>
						<div class="name_of_prod_2 propListOrder" class=""><span>${value.name}</span></div>
						<div class="kg_of_prod_2 margLeft propListOrder1"><span>${value.quantity}</span></div>
						<div class="price_of_prod_2 margLeft propListOrder1"><span>${value.price}$</span></div>
					</div>
				`;
			});

			$('#supplierInvoiceNoteEdit').val(response[0].note);
			$('#form_requisition_to_supplier .for_fill_out_with_products').append(html);
			$('#editSupplierInvoiceWrapper').show();

		} else {
			console.log('error');
		}
	});
});

//	delete row in edited supplier invoice and adjust total
$('#form_2_subb_1').on("click", ".propAbsForErase", function() {

	let element 		= $(this);
	let parent  		= element.parent();

	let quantity 		= parent.data('quantity');
	let price			= parent.data('price');

	let currentTotal 	= parseFloat($('#supplierInvoicePriceTotal').val());
	let newTotal 		= Math.round(100 * (currentTotal - (quantity * price))) / 100;

	parent.remove();
	$('#supplierInvoicePriceTotal').val(newTotal);
});

function addProductToEditedSupplierInvoice(productID, product, quantity, price) {

	let html = `
		<div class="productRow disFlex" data-product-id="${productID}" data-quantity="${quantity}" data-price="${price}">
			<div class="propAbsForErase">x</div>
			<div class="name_of_prod_2 propListOrder" class=""><span>${product}</span></div>
			<div class="kg_of_prod_2 margLeft propListOrder1"><span>${quantity}</span></div>
			<div class="price_of_prod_2 margLeft propListOrder1"><span>${price}$</span></div>
		</div>
	`;

	$('#form_2_subb_1 .for_fill_out_with_products').append(html);
}

//	adding products to edited supplier invoice
$('#addProduct').on('click', function() {

	let quantity 	= parseFloat($('#supplierInvoiceProductQuantityInput').val());
	let price 		= parseFloat($('#supplierInvoiceProductPriceInput').val());
	let productID 	= $('#supplierInvoiceProductsSelection').find(':selected').val();
	let product 	= $('#supplierInvoiceProductsSelection').find(':selected').html();

	if (!isNaN(quantity) && (quantity > 0) && !isNaN(price) && (price > 0) && (product != 'Please Select')) {

		$('#supplierInvoiceProductQuantityInput, #supplierInvoiceProductPriceInput, #supplierInvoiceProductsSelection').css("border", "1px solid #515151");

		addProductToEditedSupplierInvoice(productID, product, quantity, price);
		$('#supplierInvoiceProductQuantityInput, #supplierInvoiceProductPriceInput').val('');
		$('#supplierInvoiceProductsSelection').val('0');

		//	adjust total price
		let currentTotal 	= parseFloat($('#supplierInvoicePriceTotal').val());
		let currentTotalInt = currentTotal ?  currentTotal : 0;
		let newTotal 		= Math.round( 100 * (currentTotalInt + (quantity * price)) ) / 100;

		$('#supplierInvoicePriceTotal').val(newTotal);
		$('#addProduct').css("border", "none");

	} else {

		if (isNaN(quantity) || (quantity <= 0)) {
			$('#supplierInvoiceProductQuantityInput').css("border", "1px solid red");
		}

		if (isNaN(price) || (price < 0)) {
			$('#supplierInvoiceProductPriceInput').css("border", "1px solid red");
		}

		if (product == 'Please Select') {
			$('#supplierInvoiceProductsSelection').css("border", "1px solid red");
		}
	}
});

//	set product price when changing product selection in edited supplier invoice
$('#supplierInvoiceProductsSelection').on('change', function() {

	let element = $(this);
	let id 		= element.val();
	let price 	= element.find(':selected').data('price');

	$('#supplierInvoiceProductPriceInput').val(price);
});

//	submit edited supplier invoice
$('#form_requisition_to_supplier').on('submit', function(e) {

	e.preventDefault();

	let objForPHP = {};

	objForPHP.editSupplierInvoice	= true;
	objForPHP.invoiceID				= $('#invoiceNumber').data('id');
	objForPHP.supplierID 			= $('#supplierInvoiceSupplierSelect :selected').val();
	objForPHP.total 				= $('#supplierInvoicePriceTotal').val();
	objForPHP.date 					= $('#supplierInvoiceDateInput').val();
	objForPHP.note 					= $('#supplierInvoiceNoteEdit').val();
	objForPHP.products				= [];

	let productRows =  $('#form_requisition_to_supplier .for_fill_out_with_products .productRow');

	$.each(productRows, function(index, value) {

		let element 	= $(this);
		let productID   = element.data('product-id');
		let quantity   	= element.data('quantity');
		let price   	= element.data('price');

		let productObj  = {
			'productID' : productID,
			'quantity' 	: quantity,
			'price' 	: price,
		}

		objForPHP.products.push(productObj);
	});

	if ($.isEmptyObject(objForPHP.products)) {
		$('#addProduct').css("border", "1px solid red");
		return;
	}

	$.post('../php/ajax.php', objForPHP, null, '').done(function(response) {

		$('#editSupplierInvoiceWrapper').hide();
		drawSupplierInvoicesTable();

		$('.for_fill_out_with_products').text('');

		$('#supplierInvoiceDateInput').val('');
		$('#supplierInvoiceNoteEdit').val('');
		$('#supplierInvoiceSupplierSelect').val('');
		$('#supplierInvoiceProductQuantityInput').val('');
		$('#supplierInvoiceProductsSelection').val('');
		$('#supplierInvoiceProductPriceInput').val('');
		$('#supplierInvoicePriceTotal').val('');

		$('#supplierInvoiceEditMsg').html(response);
		$('#supplierInvoiceEditMsg').show(400);

		timeOutHandler = setTimeout(function() {
			$('#supplierInvoiceEditMsg').hide(400);
		}, 4000);
	});

});

//	delete supplier invoice
$('#supplierInvoicesTable').on('click', '.delete_supplier_invoice', function(e) {

	e.preventDefault();

	clearPHPMsg();

	let element 	= $(this);
	let id			= element.data('id');
	let supplier	= element.data('supplier');

	let html = `
		<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
			<i style="color: red" class="fas fa-exclamation-triangle"></i> Delete invoice ID: ${id} ?<span>
		</div>
		<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
			<button class="confirmDeleteSupplierInvoiceYes" data-id="${id}">Yes</button>
			<button class="confirmDeleteSupplierInvoiceNo">No</button>
		</div>
	`;

	$('#supplierDeleteInvoiceMsg').html(html);
	$('#supplierDeleteInvoiceMsg').show(400);

	timeOutHandler = setTimeout(function() {
		$('#supplierDeleteInvoiceMsg').hide(400);
	}, 20000);
});

//	confirm delete supplier invoice - no
$('#supplierInvoicesTableWrapper').on('click', '.confirmDeleteSupplierInvoiceNo', function(e) {
	$('#supplierDeleteInvoiceMsg').hide(400);
});

//	confirm delete supplier invoice - yes
$('#supplierInvoicesTableWrapper').on('click', '.confirmDeleteSupplierInvoiceYes', function(e) {

	let element 	= $(this);
	let id			= element.data('id');

	let objForPHP	= {
		'deleteSupplierInvoice' : true,
		'id' 					: id,
	};

	$('#supplierDeleteInvoiceMsg').hide(400);

	let post = $.post('../php/ajax.php', objForPHP, null, 'json');
	post.done(function(response) {

		if (response.ok == '1') {

			$('#supplierConfirmDeleteInvoiceMsg').html(response.html);
			$('#supplierConfirmDeleteInvoiceMsg').show(400);

			timeOutHandler = setTimeout(function() {
				$('#supplierConfirmDeleteInvoiceMsg').hide(400);
			}, 4000);

		} else {

			let html = `
				<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
					<i style="color: red" class="fas fa-exclamation-triangle"></i> Application error.
				</div>`
			;

			$('#supplierConfirmDeleteInvoiceMsg').html(html);
			$('#supplierConfirmDeleteInvoiceMsg').show(400);

			timeOutHandler = setTimeout(function() {
				$('#supplierConfirmDeleteInvoiceMsg').hide(400);
			}, 4000);
		}

		drawSupplierInvoicesTable();
	});
});

//	edit supplier payments
$('#supplierPaymentsTableWrapper').on('click', '.edit_payment_buttons', function(e) {

	e.preventDefault();

	clearPHPMsg();

	let element 	= $(this);
	let id			= element.data('id');
	let supplier	= element.data('user');
	let amount		= element.data('amount');
	let date		= element.data('date');

	$('#supplierPaymentAmount').val(amount);
	$('#supplierPaymentDate').val(date);
	$('#supplier_payment_id').data('id', id);

	$("#supplierPaymentSelect option").filter(function() {
		return $(this).text() == supplier;
	}).prop("selected", true);

	$('#edit_supplier_payment_wrapper').show();

});
//	update supplier payment
$('#update_supplier_payment').on('click', function(e) {

	e.preventDefault();

	clearPHPMsg();

	let paymentID	= $('#supplier_payment_id').data('id');
	let supplierID	= $("#supplierPaymentSelect").val();
	let amount		= $('#supplierPaymentAmount').val();
	let date		= $('#supplierPaymentDate').val();

	let objForPHP = {
		'editSupplierPayment' 	: true,
		'id' 					: paymentID,
		'supplier' 				: supplierID,
		'amount' 				: amount,
		'date' 					: date,
	};

	//console.log(objForPHP);

	let post = $.post('../php/ajax.php', objForPHP, null, 'json');
	post.done(function(response) {

		if (response.ok == '1') {

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

		drawSupplierPaymentsTable();
	});
});

//	delete supplier payment
$('#supplierPaymentsTableWrapper').on('click', '.delete_payment_buttons', function(e) {

	e.preventDefault();

	clearPHPMsg();

	$('#edit_supplier_payment_wrapper').hide(400);

	let element 	= $(this);
	let id			= element.data('id');
	let supplierID	= element.data('supplier-id');
	let amount		= element.data('amount');

	let tempObj	= {
		'id' 			: id,
		'supplierID' 	: supplierID,
		'amount' 		: amount,
	};

	let html = `
		<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
			<i style="color: red" class="fas fa-exclamation-triangle"></i> Delete payment ID: ${id} ?<span>
		</div>
		<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
			<button class="confirmDeleteSupplierPaymentYes" data-id="${id}">Yes</button>
			<button class="confirmDeleteSupplierPaymentNo">No</button>
		</div>
	`;

	$('#supplierDeletePaymentMsg').html(html);
	$('#supplierDeletePaymentMsg').show(400);

	timeOutHandler = setTimeout(function() {
		$('#supplierDeletePaymentMsg').hide(400);
	}, 20000);

});

//	confirm delete supplier payment - no
$('#supplierPaymentsTableWrapper').on('click', '.confirmDeleteSupplierPaymentNo', function(e) {
	$('#supplierDeletePaymentMsg').hide(400);
});

//	confirm delete supplier payment - yes
$('#supplierPaymentsTableWrapper').on('click', '.confirmDeleteSupplierPaymentYes', function(e) {

	let element 	= $(this);
	let id			= element.data('id');

	let objForPHP	= {
		'deleteSupplierPayment' : true,
		'id' 					: id,
	};

	$('#supplierDeletePaymentMsg').hide(400);

	let post = $.post('../php/ajax.php', objForPHP, null, 'json');
	post.done(function(response) {

		if (response.ok == '1') {

			$('#supplierConfirmDeletePaymentMsg').html(response.html);
			$('#supplierConfirmDeletePaymentMsg').show(400);

			timeOutHandler = setTimeout(function() {
				$('#supplierConfirmDeletePaymentMsg').hide(400);
			}, 4000);

		} else {

			let html = `
				<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
					<i style="color: red" class="fas fa-exclamation-triangle"></i> Application error.
				</div>`
			;

			$('#supplierConfirmDeletePaymentMsg').html(html);
			$('#supplierConfirmDeletePaymentMsg').show(400);

			timeOutHandler = setTimeout(function() {
				$('#supplierConfirmDeletePaymentMsg').hide(400);
			}, 4000);
		}

		drawSupplierPaymentsTable();
	});
});

//	add new supplier
$('#add_new_supplier').on('click', function(e) {

	e.preventDefault();

	clearPHPMsg();

	let supplier 	= $('#new_supplier_name').val();
	let balance 	= $('#new_supplier_balance').val();

	if (! supplier) {

		let html = `
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Enter supplier name.
			</div>`
		;

		$('#addNewSupplierMsg').html(html);
		$('#addNewSupplierMsg').show(400);

		timeOutHandler = setTimeout(function() {
			$('#addNewSupplierMsg').hide(400);
		}, 4000);
		return;
	}

	let objForPHP 	= {
		'submitNewSupplier' : true,
		'supplier'			: supplier,
		'balance'			: balance
	};

	let post = $.post('../php/ajax.php', objForPHP, null, '');
	post.done(function(response) {

		//console.log(response);

		$('#addNewSupplierMsg').html(response);
		$('#addNewSupplierMsg').show(400);

		timeOutHandler = setTimeout(function() {
			$('#addNewSupplierMsg').hide(400);
		}, 4000);

		$('#new_supplier_name').val('');
		$('#new_supplier_balance').val('0.00');

		drawEditSuppliersTable();
	});
});

//	Update supplier
$('#editSuppliersTable').on('click', '.update_supplier', function(e) {

	e.preventDefault();

	let element 	= $(this);
	let id 			= element.data('id');
	let parent		= element.parents('tr');
	let supplier	= parent.find('input').val();
	let active		= parent.find('select').val();

	let objForPHP 	= {
		'updateSupplier' 	: true,
		'id'				: id,
		'suppllier'			: supplier,
		'active'			: active
	};

	let post = $.post('../php/ajax.php', objForPHP, null, '');
	post.done(function(response) {

		if (response === '1') {
			let html = `
            <div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
                <i  style="color: green" class="fas fa-check-circle"></i>Status Updated Successfully
            </div>
        `;
        $('#updateSupplierPhpMsg').html(html);
        $('#updateSupplierPhpMsg').show(400);

			drawEditSuppliersTable();
		} else {
			let html = `
            <div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Failed to Update 
			</div>
        `;
        $('#updateSupplierPhpMsg').html(html);
        $('#updateSupplierPhpMsg').show(400);
			console.log('Could not update!');
		}
	});
});

/*****************************

	SUPPLIER EVENTS END

*****************************/


/*****************************

	CUSTOMER EVENTS START

*****************************/
//	draws customerBalanceTable
$('#customersTable').on('click', '.customer_balance_details', function() {

	let element 	= $(this);
	let id			= element.data('id');

	let objForPHP  	= {
		'getAllCustomerInvoices' 	: true,
		'id'						: id
	};

	drawCustomerBalanceTable(objForPHP);
	$('#customerBalanceTableWrapper').show();
});

//	customer invoice details
$('#customerInvoicesTable, #customerBalanceTable').on("click", ".buyer_invoice_details", function() {
	$('#editCustomerInvoiceWrapper').hide();
	$('#invoiceNoteOutput').val('');

	let id 			= $(this).data('id');
	let user 		= $(this).data('user');
	let total 		= $(this).data('total');
	let date 		= $(this).data('date');

	let objForPHP = {
		"invoiceID"				: id,
		"buyerInvoiceDetails" 	: true
	};

	$.post('../php/ajax.php', objForPHP, null, 'json').done(function(response) {

		let html = ``;

		if (response) {

			$('#supp_inv_id').text(id);
			$('#supp_inv_total').text(parseFloat(total).toLocaleString('en-US', {style: 'currency', currency: 'USD'}) );
			$('#supp_inv_date').text(date);

			$('#supp_inv_table').html('');

			$.each(response, function(index, value) {
				html +=
					`<div class="supp_inv_row" class="bckgWhite bckgGray">
						<div class="supp_inv_productNumber">
							<p>${index + 1}</p>
						</div>
						<div class="supp_inv_productName">
							<p>${value.name}</p>
						</div>
						<div class="supp_inv_productQuantity">
							<p>${value.quantity}</p>
						</div>
						<div class="supp_inv_productPrice">
							<p>$${value.price}</p>
						</div>
						<div class="supp_inv_productSum">
							<p>$${value.total}</p>
						</div>
					</div>`
				;
			});

			$('#invoiceNoteOutput').val(response[0].note);
			$('#supp_inv_table').html(html);
			$('#supp_inv_row_main').show();
			$('#popup_supplier_invoice_details').show();
		}
	});
});

//	change status of customer invoice
$('#customerInvoicesTable').on("click", ".buyer_invoice_status", function() {

	let id 		= $(this).data('id');
	let total 	= $(this).data('total');
	let userID 	= $(this).data('user-id');

	let objForPHP = {
		"id" 					: id,
		"total" 				: total,
		"userID" 				: userID,
		"buyerInvoiceComplete" 	: true
	};

	let post = $.post('../php/ajax.php', objForPHP, null, '');
	post.done(function(response) {

		if (response === '1') {
			drawCustomerInvoicesTable();
		} else {
			console.log('error');
		}
	});
});

//	get customer invoice details for editing
$('#customerInvoicesTable').on("click", ".edit_customer_invoice", function() {

	clearPHPMsg();

	let id 			= $(this).data('id');
	let date 		= $(this).data('date');
	let total 		= $(this).data('total');
	let customerID 	= $(this).data('customer');

	//	adjust form inputs
	$('#customerInvoiceDateInput').val(date);
	$('#customerInvoiceCustomerSelect').val(customerID);
	$('#customerInvoicePriceTotal').val(total);
	$('#customerInvoiceNumber').html('Invoice ID ' + id);
	$('#customerInvoiceNumber').data('id', id);

	let objForPHP = {
		'getCustomerInvoiceForEditing' 	: true,
		'invoiceID'						: id
	}

	let post = $.post('../php/ajax.php', objForPHP, null, 'json');
	post.done(function(response) {

		if (response) {

			console.log(response);

			$('#form_requisition_to_customer .for_fill_out_with_products').html('');

			let html = ``;

			$.each(response.products, function(index, value) {

				index++;

				html += `
					<div class="productRow disFlex" data-product-id="${value.productID}" data-quantity="${value.quantity}" data-price="${value.price}">
						<div class="propAbsForErase">x</div>
						<div class="name_of_prod_2 propListOrder" class=""><span>${value.name}</span></div>
						<div class="kg_of_prod_2 margLeft propListOrder1"><span>${value.quantity}</span></div>
						<div class="price_of_prod_2 margLeft propListOrder1"><span>${value.price}$</span></div>
					</div>
				`;
			});

			$('#customerInvoiceNoteEdit').val(response.products[0].note);

			$('#form_requisition_to_customer .for_fill_out_with_products').append(html);

			//	set product selection for this customer
			$('#customerInvoiceProductsSelection').html(response.pricesHTML);
			$('#customerInvoiceProductsSelection').val('0');
			$('#customerInvoiceProductPriceInput').val('');

			$('#editCustomerInvoiceWrapper').show();

		} else {
			console.log('error');
		}
	});
});

//	delete row in edited customer invoice and adjust total
$('#form_1_subb_1').on("click", ".propAbsForErase", function() {

	let element 		= $(this);
	let parent  		= element.parent();

	let quantity 		= parent.data('quantity');
	let price			= parent.data('price');

	let currentTotal 	= parseFloat($('#customerInvoicePriceTotal').val());
	let newTotal 		= Math.round(100 * (currentTotal - (quantity * price))) / 100;

	parent.remove();
	$('#customerInvoicePriceTotal').val(newTotal);
});

function addProductToEditedCustomerInvoice(productID, product, quantity, price) {

	let html = `
		<div class="productRow disFlex" data-product-id="${productID}" data-quantity="${quantity}" data-price="${price}">
			<div class="propAbsForErase">x</div>
			<div class="name_of_prod_2 propListOrder" class=""><span>${product}</span></div>
			<div class="kg_of_prod_2 margLeft propListOrder1"><span>${quantity}</span></div>
			<div class="price_of_prod_2 margLeft propListOrder1"><span>${price}$</span></div>
		</div>
	`;

	$('#form_1_subb_1 .for_fill_out_with_products').append(html);
}

//	adding products to edited customer invoice
$('#addProductCustomer').on('click', function() {

	let quantity 	= parseFloat($('#customerInvoiceProductQuantityInput').val());
	let price 		= parseFloat($('#customerInvoiceProductPriceInput').val());
	let productID 	= $('#customerInvoiceProductsSelection').find(':selected').val();
	let product 	= $('#customerInvoiceProductsSelection').find(':selected').html();

	if (!isNaN(quantity) && (quantity > 0) && !isNaN(price) && (price > 0) && (product != 'Please Select')) {

		$('#customerInvoiceProductQuantityInput, #customerInvoiceProductPriceInput, #customerInvoiceProductsSelection').css("border", "1px solid #515151");

		addProductToEditedCustomerInvoice(productID, product, quantity, price);
		$('#customerInvoiceProductQuantityInput, #customerInvoiceProductPriceInput').val('');
		$('#customerInvoiceProductsSelection').val('0');

		//	adjust total price
		let currentTotal 	= parseFloat($('#customerInvoicePriceTotal').val());
		let currentTotalInt = currentTotal ?  currentTotal : 0;
		let newTotal 		= Math.round(100 * (currentTotalInt + (quantity * price))) / 100;

		$('#customerInvoicePriceTotal').val(newTotal);
		$('#addProductCustomer').css("border", "none");

	} else {

		if (isNaN(quantity) || (quantity <= 0)) {
			$('#customerInvoiceProductQuantityInput').css("border", "1px solid red");
		}

		if (isNaN(price) || (price < 0)) {
			$('#customerInvoiceProductPriceInput').css("border", "1px solid red");
		}

		if (product == 'Please Select') {
			$('#customerInvoiceProductsSelection').css("border", "1px solid red");
		}
	}
});

//	ajax get correct prices on customer select
$('#customerInvoiceCustomerSelect').on('change', function() {

	let customerID = $(this).val();

	let objForPHP = {
		'getCustomerPrices' : true,
		'customerID' 		: customerID
	};

	let post = $.post('../php/ajax.php', objForPHP, '', 'json');
	post.done(function(response) {

		$('#customerInvoiceProductsSelection').html(response);
		$('#customerInvoiceProductsSelection').val('0');
		$('#customerInvoiceProductPriceInput').val('');
	});
});

//	set product price when changing product selection in edited customer invoice
$('#customerInvoiceProductsSelection').on('change', function() {

	let element = $(this);
	let id 		= element.val();
	let price 	= element.find(':selected').data('price');

	$('#customerInvoiceProductPriceInput').val(price);
});

// 	submit edited customer invoice
$('#form_requisition_to_customer').on('submit', function(e) {

	clearPHPMsg();

	e.preventDefault();

	let objForPHP = {};

	objForPHP.editCustomerInvoice	= true;
	objForPHP.invoiceID				= $('#customerInvoiceNumber').data('id');
	objForPHP.customerID 			= $('#customerInvoiceCustomerSelect :selected').val();
	objForPHP.total 				= $('#customerInvoicePriceTotal').val();
	objForPHP.date 					= $('#customerInvoiceDateInput').val();
	objForPHP.note 					= $('#customerInvoiceNoteEdit').val();
	objForPHP.products				= [];

	let productRows =  $('#form_requisition_to_customer .for_fill_out_with_products .productRow');

	$.each(productRows, function(index, value) {

		let element 	= $(this);
		let productID   = element.data('product-id');
		let quantity   	= element.data('quantity');
		let price   	= element.data('price');

		let productObj  = {
			'productID' : productID,
			'quantity' 	: quantity,
			'price' 	: price,
		}

		objForPHP.products.push(productObj);
	});

	if ($.isEmptyObject(objForPHP.products)) {
		console.log('no products added');
		$('#addProductCustomer').css("border", "1px solid red");
		return;
	}

	//console.log(objForPHP);

	$.post('../php/ajax.php', objForPHP, null, '').done(function(response) {

		//console.log(response);

		$('#editCustomerInvoiceWrapper').hide();
		drawCustomerInvoicesTable();

		$('.for_fill_out_with_products').text('');

		$('#supplierInvoiceDateInput').val('');
		$('#customerInvoiceNoteEdit').val('');
		$('#supplierInvoiceSupplierSelect').val('');
		$('#supplierInvoiceProductQuantityInput').val('');
		$('#supplierInvoiceProductsSelection').val('');
		$('#supplierInvoiceProductPriceInput').val('');
		$('#supplierInvoicePriceTotal').val('');

		$('#editCustomerInvoiceMsg').html(response);
		$('#editCustomerInvoiceMsg').show(400);

		timeOutHandler = setTimeout(function() {
			$('#editCustomerInvoiceMsg').hide(400);
		}, 4000);
	});
});

//	delete customer invoice
$('#customerInvoicesTable').on('click', '.delete_customer_invoice', function(e) {

	e.preventDefault();

	clearPHPMsg();

	let element 	= $(this);
	let id			= element.data('id');

	let html = `
		<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
			<i style="color: red" class="fas fa-exclamation-triangle"></i> Delete invoice ID: ${id} ?<span>
		</div>
		<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
			<button class="confirmDeleteCustomerInvoiceYes" data-id="${id}">Yes</button>
			<button class="confirmDeleteCustomerInvoiceNo">No</button>
		</div>
	`;

	$('#customerDeleteInvoiceMsg').html(html);
	$('#customerDeleteInvoiceMsg').show(400);

	timeOutHandler = setTimeout(function() {
		$('#customerDeleteInvoiceMsg').hide(400);
	}, 20000);
});

//	confirm delete customer invoice - no
$('#customerInvoicesTableWrapper').on('click', '.confirmDeleteCustomerInvoiceNo', function(e) {
	$('#customerDeleteInvoiceMsg').hide(400);
});

//	confirm delete customer invoice - yes
$('#customerInvoicesTableWrapper').on('click', '.confirmDeleteCustomerInvoiceYes', function(e) {

	let element 	= $(this);
	let id			= element.data('id');

	let objForPHP	= {
		'deleteCustomerInvoice' : true,
		'id' 					: id,
	};

	$('#customerDeleteInvoiceMsg').hide(400);

	let post = $.post('../php/ajax.php', objForPHP, null, 'json');
	post.done(function(response) {

		if (response.ok == '1') {

			$('#customerConfirmDeleteInvoiceMsg').html(response.html);
			$('#customerConfirmDeleteInvoiceMsg').show(400);

			timeOutHandler = setTimeout(function() {
				$('#customerConfirmDeleteInvoiceMsg').hide(400);
			}, 4000);

		} else {

			let html = `
				<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
					<i style="color: red" class="fas fa-exclamation-triangle"></i> Application error.
				</div>`
			;

			$('#customerConfirmDeleteInvoiceMsg').html(html);
			$('#customerConfirmDeleteInvoiceMsg').show(400);

			timeOutHandler = setTimeout(function() {
				$('#customerConfirmDeleteInvoiceMsg').hide(400);
			}, 4000);
		}

		drawCustomerInvoicesTable();
	});
});

//	edit customer payments
$('#customerPaymentsTableWrapper').on('click', '.edit_payment_buttons', function(e) {

	e.preventDefault();

	clearPHPMsg();

	let element 	= $(this);
	let id			= element.data('payment-id');
	let customerID	= element.data('customer-id');
	let amount		= element.data('amount');
	let date		= element.data('date');

	$('#customerPaymentAmount').val(amount);
	$('#customerPaymentDate').val(date);
	$('#customer_payment_id').data('id', id);
	$("#customerPaymentSelect").val(customerID)

	$('#edit_customer_payment_wrapper').show();

});

//	update customer payment
$('#update_customer_payment').on('click', function(e) {

	e.preventDefault();

	clearPHPMsg();

	let paymentID	= $('#customer_payment_id').data('id');
	let customerID	= $('#customerPaymentSelect').val();
	let amount		= $('#customerPaymentAmount').val();
	let date		= $('#customerPaymentDate').val();

	let objForPHP = {
		'editCustomerPayment' 	: true,
		'id' 					: paymentID,
		'customerID' 			: customerID,
		'amount' 				: amount,
		'date' 					: date,
	};

	let post = $.post('../php/ajax.php', objForPHP, null, 'json');
	post.done(function(response) {

		if (response.ok == '1') {

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

		drawCustomerPaymentsTable();
	});
});

//	delete customer payment
$('#customerPaymentsTableWrapper').on('click', '.delete_payment_buttons', function(e) {

	e.preventDefault();

	clearPHPMsg();

	$('#edit_customer_payment_wrapper').hide(400);

	let element 	= $(this);
	let id			= element.data('payment-id');
	let customerID	= element.data('customer-id');
	let amount		= element.data('amount');

	let tempObj	= {
		'id' 			: id,
		'customerID' 	: customerID,
		'amount' 		: amount,
	};

	let html = `
		<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
			<i style="color: red" class="fas fa-exclamation-triangle"></i> Delete payment ID: ${id} ?<span>
		</div>
		<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
			<button class="confirmDeleteCustomerPaymentYes" data-id="${id}">Yes</button>
			<button class="confirmDeleteCustomerPaymentNo">No</button>
		</div>
	`;

	$('#customerDeletePaymentMsg').html(html);
	$('#customerDeletePaymentMsg').show(400);

	timeOutHandler = setTimeout(function() {
		$('#customerDeletePaymentMsg').hide(400);
	}, 20000);

});

//	confirm delete customer payment - no
$('#customerPaymentsTableWrapper').on('click', '.confirmDeleteCustomerPaymentNo', function(e) {
	$('#customerDeletePaymentMsg').hide(400);
});

//	confirm delete customer payment - yes
$('#customerPaymentsTableWrapper').on('click', '.confirmDeleteCustomerPaymentYes', function(e) {

	let element 	= $(this);
	let id			= element.data('id');

	let objForPHP	= {
		'deleteCustomerPayment' : true,
		'id' 					: id,
	};

	$('#customerDeletePaymentMsg').hide(400);

	let post = $.post('../php/ajax.php', objForPHP, null, 'json');
	post.done(function(response) {

		if (response.ok == '1') {

			$('#customerConfirmDeletePaymentMsg').html(response.html);
			$('#customerConfirmDeletePaymentMsg').show(400);

			timeOutHandler = setTimeout(function() {
				$('#customerConfirmDeletePaymentMsg').hide(400);
			}, 4000);

		} else {

			let html = `
				<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
					<i style="color: red" class="fas fa-exclamation-triangle"></i> Application error.
				</div>`
			;

			$('#customerConfirmDeletePaymentMsg').html(html);
			$('#customerConfirmDeletePaymentMsg').show(400);

			timeOutHandler = setTimeout(function() {
				$('#customerConfirmDeletePaymentMsg').hide(400);
			}, 4000);
		}

		drawCustomerPaymentsTable();
	});
});
$('#button_change_password').on('click', function(e) {
    clearPHPMsg();
    e.preventDefault();

    let newPassword = $.trim($('#change_password').val());
    let confirmPassword = $.trim($('#change_password_confirm').val());
    let userid = $.trim($('#userid').val());
    if (!newPassword || !confirmPassword) {
        let html = `
            <div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
                <i style="color: red" class="fas fa-exclamation-triangle"></i> Please enter both new password and confirm password.
            </div>
        `;
        $('#changepasswordMsg').html(html);
        $('#changepasswordMsg').show(400);
        return;
    }

    if (newPassword !== confirmPassword) {
        let html = `
            <div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
                <i style="color: red" class="fas fa-exclamation-triangle"></i> New password and confirm password do not match.
            </div>
        `;
        $('#changepasswordMsg').html(html);
        $('#changepasswordMsg').show(400);
        return;
    }

    let objForPHP = {
        'submitChangePassword': true,
        'password': newPassword,
        'userid': userid,
    };

    let post = $.post('../php/ajax.php', objForPHP, null, '');
    post.done(function(response) {
        $('#changepasswordMsg').html(response);
        $('#changepasswordMsg').show(400);
        $('#change_password').val(''); // Reset the password fields
        $('#change_password_confirm').val('');
    });
});

$('#add_new_customer').on('click', function(e) {

	clearPHPMsg();

	let checkPrices = 1;

	e.preventDefault();

	let customer 	= $.trim($('#new_customer_name').val());
	// let inventory 	= $('#new_customer_inventory').val();
	let password 	= $.trim($('#new_customer_password').val());
	let balance 	= $('#new_customer_balance').val();

	if (! customer) {

		let html = `
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Enter customer name.
			</div>
		`;

		$('#addNewCustomerMsg').html(html);
		$('#addNewCustomerMsg').show(400);

		timeOutHandler = setTimeout(function() {
			$('#addNewCustomerMsg').hide(400);
		}, 4000);

		return;
	}

	if (! password) {

		let html = `
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Enter customer password (min 8 characters).
			</div>
		`;

		$('#addNewCustomerMsg').html(html);
		$('#addNewCustomerMsg').show(400);

		timeOutHandler = setTimeout(function() {
			$('#addNewCustomerMsg').hide(400);
		}, 4000);

		return;
	}

	let objForPHP 	= {
		'submitNewCustomer' : true,
		'customer'			: customer,
		// 'inventory'			: inventory,
		'password'			: password,
		'balance'			: balance,
	};

	let post = $.post('../php/ajax.php', objForPHP, null, '');
	post.done(function(response) {

		$('#addNewCustomerMsg').html(response);
		$('#addNewCustomerMsg').show(400);

		timeOutHandler = setTimeout(function() {
			$('#addNewCustomerMsg').hide(400);
		}, 4000);

		//	reset inputs
		$('#new_customer_name').val('');
		$('#new_customer_inventory').val('1');
		$('#new_customer_password').val('');
		$('#new_customer_balance').val('0.00');

		drawEditCustomersTable();
	});
});

//	Update customer info
$('#editCustomersTable').on('click', '.update_customer', function(e) {

	e.preventDefault();

	let element 		= $(this);
	let id 				= element.data('id');
	let parent			= element.parents('tr');
	let customer		= parent.find('td:nth-child(2) input').val();
	let passwordInput	= parent.find('td:nth-child(4) input');
	let password		= parent.find('td:nth-child(4) input').val();
	// let inventory		= parent.find('select').val();
	if (password && password.length < 8) {
		passwordInput.addClass("inputError");
		return;
	}

	passwordInput.removeClass('inputError');

	let objForPHP 	= {
		'updateCustomer' 	: true,
		'id'				: id,
		'username'			: customer,
		// 'inventory'			: inventory,
		'password'			: password
	};

	let post = $.post('../php/ajax.php', objForPHP, null, '');
	post.done(function(response) {
		if (response === '1') {
			drawEditCustomersTable();
		} else {
			alert('Could not update!');
		}
	});
});

//	Delete customer
$('#editCustomersTable').on('click', '.status_customer', function(e) {

	e.preventDefault();

	let element 	= $(this);
	let id 			= element.data('id');
	let active 		= element.data('active');

	let objForPHP 	= {
		'activeCustomer' 	: true,
		'id'				: id,
		'active'			: active
	};

	let post = $.post('../php/ajax.php', objForPHP, null, '');
	post.done(function(response) {

		if (response === '1') {
			drawEditCustomersTable();
		} else {
			console.log('Could not update!');
		}
	});
});

/*****************************

	CUSTOMER EVENTS END

*****************************/

/*****************************

	PRODUCT EVENTS START

*****************************/

// Add product button click event handler

$('#addProductBtnForEdmonton').on('click', function() {
    $('#addNewProductWrapperForEdmonton').toggle();
});

// Add new product form submission
$('#add_new_product_edmonton').on('submit', function(e) {
    e.preventDefault();

    // Get form values
    let productName = $('#new_product_nameForEdmonton').val().trim();
    let edmontonQuantity = $('#new_product_calgary_quantityForEdmonton').val().trim();
    let supplierPrice = $('#new_product_supplier_priceForEdmonton').val().trim();

    // Validate form inputs
    if (productName === '') {
        alert('Please enter a product name.');
        return;
    }

    // Prepare data for submission
    let formData = {
		'addNewProductForEdmonton' 	: true,
        productName: productName,
        edmontonQuantity: edmontonQuantity,
        supplierPrice: supplierPrice
    };

    // Make AJAX request to submit form data
    $.post('../php/ajax.php', formData, function(response) {
        // Handle response from server
		$('#addNewProductMsgForEdmonton').html(response);
		$('#addNewProductMsgForEdmonton').show(400);
		drawEdmontonTable();
		timeOutHandler = setTimeout(function() {
			$('#addNewProductMsgForEdmonton').hide(400);
		}, 4000);

        // Optionally, you can display a success message or perform other actions
    });
});
$('#addProductBtnForToronto').on('click', function() {
    $('#addNewProductWrapperForToronto').toggle();
});

// Add new product form submission
$('#add_new_product_toronto').on('submit', function(e) {
    e.preventDefault();

    // Get form values
    let productName = $('#new_product_nameForToronto').val().trim();
    let torontoQuantity = $('#new_product_calgary_quantityForToronto').val().trim();
    let supplierPrice = $('#new_product_supplier_priceForToronto').val().trim();

    // Validate form inputs
    if (productName === '') {
        alert('Please enter a product name.');
        return;
    }

    // Prepare data for submission
    let formData = {
		'addNewProductForToronto' 	: true,
        productName: productName,
        torontoQuantity: torontoQuantity,
        supplierPrice: supplierPrice
    };

    // Make AJAX request to submit form data
    $.post('../php/ajax.php', formData, function(response) {
        // Handle response from server
		$('#addNewProductMsgForToronto').html(response);
		$('#addNewProductMsgForToronto').show(400);
		drawTorontoTable();
		timeOutHandler = setTimeout(function() {
			$('#addNewProductMsgForToronto').hide(400);
		}, 4000);

        // Optionally, you can display a success message or perform other actions
    });
});
$('#addProductBtnForKelowna').on('click', function() {
    $('#addNewProductWrapperForKelowna').toggle();
});

// Add new product form submission
$('#add_new_product_kelowna').on('submit', function(e) {
    e.preventDefault();

    // Get form values
    let productName = $('#new_product_nameForKelowna').val().trim();
    let kelownaQuantity = $('#new_product_calgary_quantityForKelowna').val().trim();
    let supplierPrice = $('#new_product_supplier_priceForKelowna').val().trim();

    // Validate form inputs
    if (productName === '') {
        alert('Please enter a product name.');
        return;
    }

    // Prepare data for submission
    let formData = {
		'addNewProductForKelowna' 	: true,
        productName: productName,
        kelownaQuantity: kelownaQuantity,
        supplierPrice: supplierPrice
    };

    // Make AJAX request to submit form data
    $.post('../php/ajax.php', formData, function(response) {
        // Handle response from server
		$('#addNewProductMsgForKelowna').html(response);
		$('#addNewProductMsgForKelowna').show(400);
		drawKelownaTable();
		timeOutHandler = setTimeout(function() {
			$('#addNewProductMsgForKelowna').hide(400);
		}, 4000);

        // Optionally, you can display a success message or perform other actions
    });
});
$('#addProductBtnForCalgary').on('click', function() {
    $('#addNewProductWrapperForCalgary').toggle();
});

// Add new product form submission
$('#add_new_product_calagary').on('submit', function(e) {
    e.preventDefault();

    // Get form values
    let productName = $('#new_product_nameForCalgary').val().trim();
    let calgaryQuantity = $('#new_product_calgary_quantityForCalgary').val().trim();
    let supplierPrice = $('#new_product_supplier_priceForCalgary').val().trim();

    // Validate form inputs
    if (productName === '') {
        alert('Please enter a product name.');
        return;
    }

    // Prepare data for submission
    let formData = {
		'addNewProductForCalagory' 	: true,
        productName: productName,
        calgaryQuantity: calgaryQuantity,
        supplierPrice: supplierPrice
    };

    // Make AJAX request to submit form data
    $.post('../php/ajax.php', formData, function(response) {
        // Handle response from server
		$('#addNewProductMsgForCalgary').html(response);
		$('#addNewProductMsgForCalgary').show(400);
		drawCalgaryTable();
		timeOutHandler = setTimeout(function() {
			$('#addNewProductMsgForCalgary').hide(400);
		}, 4000);

        // Optionally, you can display a success message or perform other actions
    });
});
$('#addProductBtn').on('click', function() {
    $('#addNewProductWrapperN').toggle();
});

// Add new product form submission
$('#add_new_product_calagary').on('submit', function(e) {
    e.preventDefault();

    // Get form values
    let productName = $('#new_product_nameN').val().trim();
    let calgaryQuantity = $('#new_product_calgary_quantityN').val().trim();
    let supplierPrice = $('#new_product_supplier_priceN').val().trim();

    // Validate form inputs
    if (productName === '') {
        alert('Please enter a product name.');
        return;
    }

    // Prepare data for submission
    let formData = {
		'addNewProductForCalagory' 	: true,
        productName: productName,
        calgaryQuantity: calgaryQuantity,
        supplierPrice: supplierPrice
    };

    // Make AJAX request to submit form data
    $.post('../php/ajax.php', formData, function(response) {
        // Handle response from server
		$('#addNewProductMsgN').html(response);
		$('#addNewProductMsgN').show(400);
		drawCalgaryTable();
		timeOutHandler = setTimeout(function() {
			$('#addNewProductMsgN').hide(400);
		}, 4000);

        // Optionally, you can display a success message or perform other actions
    });
});

$('#add_new_product').on('click', function(e) {

	clearPHPMsg();

	e.preventDefault();

	let product 			= $.trim($('#new_product_name').val());
	let quantityCalgary 	= parseFloat($('#new_product_calgary_quantity').val());
	let quantityEdmonton 	= parseFloat($('#new_product_calgary_edmonton').val());
	let quantityToronto 	= parseFloat($('#new_product_calgary_toronto').val());
	let quantityKelowna 	= parseFloat($('#new_product_calgary_kelowna').val());
	let supplier_price 		= parseFloat($('#new_product_supplier_price').val());

	if (! product) {

		let html = `
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Enter product name.
			</div>`
		;

		$('#addNewProductMsg').html(html);
		$('#addNewProductMsg').show(400);

		timeOutHandler = setTimeout(function() {
			$('#addNewProductMsg').hide(400);
		}, 4000);

		return;
	}

	let objForPHP = {
		'addNewProduct' 	: true,
		'product' 			: product,
		'quantityCalgary' 	: quantityCalgary,
		'quantityEdmonton' 	: quantityEdmonton,
		'quantityToronto' 	: quantityToronto,
		'quantityKelowna' 	: quantityKelowna,
		'supplier_price' 	: supplier_price,
	};

	let post = $.post('../php/ajax.php', objForPHP, null, '');
	post.done(function(response) {

		$('#addNewProductMsg').html(response);
		$('#addNewProductMsg').show(400);

		timeOutHandler = setTimeout(function() {
			$('#addNewProductMsg').hide(400);
		}, 4000);

		drawUpdateProductsTable();

	});
});

//	Update products
$('#editProductsTable').on('click', '.update_product', function(e) {

	e.preventDefault();

	let element 	= $(this);
	let id 			= element.data('id');
	let parent		= element.parents('tr');
	let product		= parent.find('input').val();
	let active		= parent.find('select').val();

	let objForPHP 	= {
		'updateProduct' 	: true,
		'id'				: id,
		'product'			: product,
		'active'			: active
	};

	//console.log(objForPHP);

	let post = $.post('../php/ajax.php', objForPHP, null, '');
	post.done(function(response) {

		if (response === '1') {
			let html = `
            <div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
                <i  style="color: green" class="fas fa-check-circle"></i>Status Updated Successfully
            </div>
        `;
        $('#addProductPhpMsg').html(html);
        $('#addProductPhpMsg').show(400);
		drawUpdateProductsTable();
		} else {
			let html = `
            <div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Failed to Update 
			</div>
        `;
        $('#addProductPhpMsg').html(html);
        $('#addProductPhpMsg').show(400);
			console.log('Could not update!');
		}
	});
});

$('#productPricesTableWrapper').on('click', '.setPrices', function() {

	let element 	= $(this);
	let parentRow 	= element.closest('tr');

	let prices = [];

	$(parentRow).find('input').each(function () {

		let input 		= $(this);

		let customerID 	= input.data('customer-id');
		let productID 	= input.data('product-id');
		let price		= input.val();

		let object = {
			'customerID' 	: customerID,
			'productID'		: productID,
			'price'			: price,
		};

		prices.push(object);
	});

	let objForPHP = {
		'updateUserPrices' : true,
		'prices' : prices,
	};

	//console.log(objForPHP);

	let post = $.post('../php/ajax.php', objForPHP, null, 'json');

	post.done(function(response) {

		if (response.ok == '1') {

			$('#productPricesMsg').html(response.html);
			$('#productPricesMsg').show(400);

			timeOutHandler = setTimeout(function() {
				$('#productPricesMsg').hide(400);
			}, 4000);

			buildProductPricesForCustomersTable();

		} else {

			let html = `
				<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
					<i style="color: red" class="fas fa-exclamation-triangle"></i> Application error.
				</div>`
			;

			$('#productPricesMsg').html(html);
			$('#productPricesMsg').show(400);

			timeOutHandler = setTimeout(function() {
				$('#productPricesMsg').hide(400);
			}, 4000);

		}
	});
});

/*****************************

	PRODUCT EVENTS END

*****************************/

/*****************************

	INVENTORY EVENTS START

*****************************/

/* Transfer */

$('#transfer_product_selection').on('change', function() {

	let option 		= $(this, 'option:selected');
	let productID 	= parseInt(option.val());

	let objForPHP = {
		'getInventoryInfoForTransfer' 	: true,
		'productID' 					: productID
	};

	if (! productID) {
		return;
	}

	let post = $.post('../php/ajax.php', objForPHP, null, 'json');
	post.done(function(response) {

		//console.log(response);

		let calgary 	= $('#calgaryInput');
		let edmonton 	= $('#edmontonInput');
		let max			= response.max;

		calgary.attr('max', max);
		edmonton.attr('max', max);

		calgary.val(parseFloat(response.calgary));
		edmonton.val(parseFloat(response.edmonton));

		calgary.on('change', function() {
			edmonton.val(Math.round((max - calgary.val()) * 100) / 100);
		});

		edmonton.on('change', function() {
			calgary.val(Math.round((max - edmonton.val()) * 100) / 100);
		});
	});
});


$('#transfer_button').on('click', function(e) {

	clearPHPMsg();

	e.preventDefault();

	let option 		= $('#transfer_product_selection option:selected');
	let productID 	= option.val();
	let fromID 		= $('#supplierfrominventory option:selected').val();
	let toID 		= $('#suppliertoinventory option:selected').val();
	let qty 		= $('#suppliertransferqty').val();
	// let calgary 	= $('#calgaryInput').val();
	// let edmonton 	= $('#edmontonInput').val();

	let objForPHP = {
		'setTransfer' 	: true,
		'productID' 	: productID,
		'fromID'		: fromID,
		'toID'			: toID,
		'qty'			: qty,
		// 'calgary' 		: calgary,
		// 'edmonton' 		: edmonton,
	};

	//console.log(objForPHP)

	let post = $.post('../php/ajax.php', objForPHP, null, '');
	post.done(function(response) {

		$('#transfer_product_selection').val('0');
		$('#supplierfrominventory').val('0');
		$('#suppliertoinventory').val('0');~
		$('#supplierfrominventory option:selected').val(0);
		$('#suppliertoinventory option:selected').val(0);
		$('#suppliertransferqty').val(0);
		// $('#calgaryInput').val('');
		// $('#edmontonInput').val('');

		$('#transferMsg').html(response);
		$('#transferMsg').show(400);

		timeOutHandler = setTimeout(function() {
			$('#transferMsg').hide(400);
		}, 4000);

	});
});

/*****************************

	INVENTORY EVENTS END

*****************************/




/*****************************

	BALANCE EVENTS START

*****************************/

//	Monthly Balance
function drawBalance(response) {

	let htmlForColumn0 = ``;
	let htmlForColumn1 = ``;
	let htmlForColumn2 = ``;
	let htmlForColumn3 = ``;
	let htmlForColumn4 = ``;
	let htmlForColumn5 = ``;
	let htmlForColumn6 = ``;

	let incr = 1;
	$.each(response.products, function(index, value) {

		htmlForColumn0 += `<div id="s_merch_type_subb_${incr}" class="oddClass propHeadRowPara">${index}</div>`;

		htmlForColumn1 += `
			<div id="s_f_t_1_subb_${incr}" class="oddClass propHeadRowPara custHoverRelative">
				<span>${value.type0.totalWeight}</span>
			</div>
		`;

		htmlForColumn2 += `<div id="s_f_t_2_subb_${incr}" class="oddClass propHeadRowPara">${value.type0.totalPrice.toLocaleString('en-US', {style: 'currency', currency: 'USD'})}</div>`;

		htmlForColumn3 += `
			<div id="s_f_t_3_subb_${incr}" class="oddClass propHeadRowPara custHoverRelative">
				<span>${value.type1.totalWeight}</span>
			</div>
		`;

		htmlForColumn4 += `<div id="s_f_t_4_subb_${incr}" class="oddClass propHeadRowPara">${value.type1.totalPrice.toLocaleString('en-US', {style: 'currency', currency: 'USD'})}</div>`;

		htmlForColumn5 += `<div id="s_f_t_5_subb_${incr}" class="oddClass propHeadRowPara">${value.type1.totalProfit.toLocaleString('en-US', {style: 'currency', currency: 'USD'})}</div>`;
		htmlForColumn6 += `<div id="s_f_t_6_subb_${incr}" class="oddClass propHeadRowPara">${value.type0.totalWeight - value.type1.totalWeight} </div>`;
	});

	htmlForColumn2 += `<div id="s_f_t_2_subb_${incr}" class="oddClass propHeadRowParaTotals">${response.totals.type0.toLocaleString('en-US', {style: 'currency', currency: 'USD'})}</div>`;
	htmlForColumn4 += `<div id="s_f_t_4_subb_${incr}" class="oddClass propHeadRowParaTotals">${response.totals.type1.toLocaleString('en-US', {style: 'currency', currency: 'USD'})}</div>`;
	htmlForColumn5 += `<div id="s_f_t_5_subb_${incr}" class="oddClass propHeadRowParaTotals">${response.totals.profit.toLocaleString('en-US', {style: 'currency', currency: 'USD'})}</div>`;

	$('#s_merch_type').html(htmlForColumn0);
	$('#s_f_t_1').html(htmlForColumn1);
	$('#s_f_t_2').html(htmlForColumn2);
	$('#s_f_t_3').html(htmlForColumn3);
	$('#s_f_t_4').html(htmlForColumn4);
	$('#s_f_t_5').html(htmlForColumn5);
	$('#s_f_t_6').html(htmlForColumn6);
}

//	submit date pick form
$('#date_pick_form').on('submit', function(e) {

	e.preventDefault();

	clearPHPMsg();

	$('#select_period_balance_table_wrapper').hide();

	let startDate 	= $('#startDate').val();
	let endDate 	= $('#endDate').val();

	if (startDate == '' || endDate == '') {
		let html = `
			<div style="width: 250px; background-color: #f2f2f2; padding: 10px; margin: 0 auto;">
				<i style="color: red" class="fas fa-exclamation-triangle"></i> Select dates!
			</div>`
		;

		$('#balanceMsg').html(html);
		$('#balanceMsg').show(400);

		timeOutHandler = setTimeout(function() {
			$('#balanceMsg').hide(400);
		}, 4000);
		return;
	}

	let objForPHP = {
		'getBalance' 	: true,
		'startDate'		: startDate,
		'endDate'		: endDate,
	};

	let post = $.post('../php/ajax.php', objForPHP, null, 'json');
	post.done(function(response) {

		drawBalance(response);
		$('#select_period_balance_table_wrapper').show();
	});
});

/*****************************

	BALANCE EVENTS START

*****************************/




/*****************************

	NAVIGATION CLICKS START

*****************************/

$('.propThumbCl .adminLabels').on('click', function(e) {
	$(this).parent(".propThumbCl").find(".adminSubMenus").toggle("display");
	$(this).parent(".propThumbCl").find(".fa-chevron-down").toggle("display");
	$(this).parent(".propThumbCl").find(".fa-chevron-up").toggle("display");
});

$('#suppliers_list_link').on('click', function(e) {

	e.preventDefault();

	drawSuppliersTable();

	$('.adminBoards').hide();
	$('#suppliersTableWrapper').show();
});

$('#supplier_invoices_list_link').on('click', function(e) {

	e.preventDefault();

	drawSupplierInvoicesTable();

	$('.adminBoards').hide();
	$('#supplierInvoicesTableWrapper').show();

});

$('#supplier_add_new').on('click', function(e) {

	e.preventDefault();

	drawEditSuppliersTable();

	$('.adminBoards').hide();
	$('#supplierAddNewWrapper').show();
});

$('#supplier_payments').on('click', function(e) {

	e.preventDefault();

	drawSupplierPaymentsTable();

	$('.adminBoards').hide();
	$('#supplierPaymentsTableWrapper').show();

});

$('#customers_list_link').on('click', function(e) {

	e.preventDefault();

	drawCustomersTable();

	$('.adminBoards').hide();
	$('#customersTableWrapper').show();
});

$('#customer_invoices_link').on('click', function(e) {

	e.preventDefault();

	drawCustomerInvoicesTable();

	$('.adminBoards').hide();
	$('#customerInvoicesTableWrapper').show();

});

$('#add_new_customer_link').on('click', function(e) {

	e.preventDefault();	


	$('.adminBoards').hide();
	$('#addNewCustomerWrapper').show();
	drawEditCustomersTable();

});
$('#change_password_link').on('click', function(e) {

	e.preventDefault();


	$('.adminBoards').hide();
	$('#changePasswordWrapper').show();

});

$('#customer_payments').on('click', function(e) {

	e.preventDefault();

	drawCustomerPaymentsTable();

	$('.adminBoards').hide();
	$('#customerPaymentsTableWrapper').show();
});

$('#add_new_products_link').on('click', function(e) {

	e.preventDefault();

	$('.adminBoards').hide();
	$('#addNewProductWrapper').show();

	drawUpdateProductsTable();
});

$('#products_list_link').on('click', function(e) {

	e.preventDefault();

	buildProductPricesForCustomersTable();

	$('.adminBoards').hide();
	$('#productTableWrapper').show();
});

$('#stock_link').on('click', function(e) {

	e.preventDefault();
	

	drawStockTable();

	$('.adminBoards').hide();
	$('#stockTableWrapper').show();
});

$('#stock_link_calgary').on('click', function(e) {

	e.preventDefault();

	drawCalgaryTable();

	$('.adminBoards').hide();
	$('#calgaryTableWrapper').show();
});

$('#stock_link_edmonton').on('click', function(e) {

	e.preventDefault();

	drawEdmontonTable();

	$('.adminBoards').hide();
	$('#edmontonTableWrapper').show();
});

$('#stock_link_toronto').on('click', function(e) {

	e.preventDefault();

	drawTorontoTable();

	$('.adminBoards').hide();
	$('#torontoTableWrapper').show();
});
$('#stock_link_kelowna').on('click', function(e) {

	e.preventDefault();

	drawKelownaTable();

	$('.adminBoards').hide();
	$('#kelownaTableWrapper').show();
});


$('#stock_link_transfer').on('click', function(e) {

	e.preventDefault();

	$('.adminBoards').hide();
	$('#transferWrapper').show();

	let objForPHP = {
		'getProductsForTransferInfo' : true
	};

	let post = $.post('../php/ajax.php', objForPHP, '', 'json');
	post.done(function(response) {

		$('#transfer_product_selection').html(response);

		//	reset max attr and values for two inputs
		$('#calgaryInput').attr('max', 0);
		$('#edmontonInput').attr('max', 0);

		$('#calgaryInput').val(0);
		$('#edmontonInput').val(0);

	});
});

$('#monthly_balance_link').on('click', function(e) {

	e.preventDefault();

	$('.adminBoards').hide();
	$('#monthlyBalanceWrapper').show();

	let objForPHP = {
		"balance" 	: true
	};

	let post = $.post('../php/ajax.php', objForPHP, null, 'json');
	post.done(function(response) {
		drawMonthlyBalance(response);
		//console.log(response);
	});
});

$('#select_period_balance_link').on('click', function(e) {

	e.preventDefault();

	$('.adminBoards').hide();
	$('#date_pick_form .datePickInputs').val('');
	$('#select_period_balance_wrapper').show();
});

/*****************************

	NAVIGATION CLICKS END

*****************************/

//	remove all msgs on any click in side bar
$('#form_thumb_main_wrapp p').on('click', function() {
	clearPHPMsg();
});

$(function() {

	let docHeight = $(window).height();
	let navHeight = $('#main_nav_wrapp').outerHeight();
	let footHeight = $('#footer_main').outerHeight();

	$('#admin_dash_wrapper').css("minHeight", `${docHeight - navHeight - footHeight}px`);

	//	Warning Duplicate IDs
	// $('[id]').each(function() {
		// let ids = $('[id="' + this.id + '"]');
		// if (ids.length > 1 && ids[0] == this) {
			// console.warn('Multiple IDs #' +this.id);
		// }
	// });
});