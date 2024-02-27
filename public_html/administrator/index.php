<?php
require_once '../../src/init.php';
require_once SRC . 'connect.php';
require_once SRC . 'controller/administrator-panel.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="MDTAT" content="<?= generateAjaxToken(); ?>">
	<title><?= $pageTitle ?? ''; ?></title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<!-- <script src="https://code.jquery.com/jquery-3.3.1.min.js"integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script> -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
	<link rel="stylesheet" href="<?php getAssetLink('css-v*.css', 'css'); ?>">

</head>

<body>

	<div id="w_e_i_main_wrapp">

		<?php getNavbar($pageTitle); ?>

		<div id="popup_supplier_invoice_details">
			<div id="supp_invoice_wrapper">
				<button id="closePopupButton" style="width: 104px;">Close Popup</button>
				<div id="supp_inv_main_wrap_head" class="disFlex">
					<!-- <div id="supp_invo_subb_1">
		                <h3 id="supp_comp_name_id"> My Company Name <span></span></h3>
		                <h3>City, ST ZIP: <span id="supp_citystzip_id"></span></h3>
		                <h3>Phone Number: <span id="supp_phone_id"></span></h3>
	                </div> -->
					<div id="supp_invo_subb_2" class="margLeft">
						<h3>Invoice Number: <span id="supp_inv_id"></span></h3>
						<h3>Date: <span id="supp_inv_date"></span></h3>
					</div>
				</div>
				<!-- <div id="supp_inv_main_wrap_head_2" class="disFlex">
	                <div id="supp_invo_subb_3">
		                <h2>Bill to:</h2>
						<h3 id="supp_inv_supplier"> My Company Name <span></span></h3>
						<h3>Company Name: <span id="supp_comp_name_supplier"></span></h3>
						<h3>Name of Supplier: <span id="supp_street_address_supplier"></span></h3>
					</div>
					<div id="supp_invo_subb_4">
						<h3>City, ST ZIP: <span id="supp_cityzipst_supplier"></span></h3>
						<h3>Phone Number: <span id="supp_ph_num_supplier"></span></h3>
					    <h3>Email Address: <span id="supp_em_add_supplier"></span></h3>
					</div>
				</div> -->
				<div style="    width: 900px;
    margin: 0 auto;
    padding-top: 10px;
    padding-bottom: 10px;
    border-top: 1px solid black;
    border-bottom: 1px solid black;">
					<div class="container">
						<div class="row">
							<div class="supp_inv_row_main " id="supp_inv_row_main" style="display: none;">
								<div class="col-2 supp_inv_productNumber ml-2">
									<p>ID</p>
								</div>
								<div class="col-2 supp_inv_productName">
									<p>Name</p>
								</div>
								<div class="col-2 supp_inv_productQuantity">
									<p>Quantity</p>
								</div>
								<div class="col-2 supp_inv_productPrice">
									<p>Price</p>
								</div>
								<div class="col-2 supp_inv_productSum">
									<p>total</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="supp_inv_table">

				</div>
				<h3 id="am_h3">Total Amount: <span id="supp_inv_total"></span></h3>
				<div class="invoiceNoteWrapper">
					<h4> Note </h4>
					<div class="invoiceNoteInner">
						<textarea class="invoiceNoteArea" id="invoiceNoteOutput"></textarea>
					</div>
				</div>
				<!-- <button>PRINT</button> -->
			</div>
		</div><!-- popup_supplier_invoice_details -->

		<div id="admin_dash_wrapper">
			<div id="form_thumb_main_wrapp" class="adminWrapper">
				<div class="propThumbCl">
					<div class="adminIcons"><i class="fas fa-columns"></i></div>
					<h5>ADMIN PANEL</h5>
				</div>
				<div id="admin_suppliers" class="propThumbCl">
					<div class="adminLabels">
						<div class="adminIcons"><i class="fas fa-truck"></i></div>
						<h5>Suppliers</h5>
						<div class="subMenuButton"><i class="fas fa-chevron-down"></i><i class="fas fa-chevron-up"></i></div>
					</div>
					<div class="adminSubMenus">
						<p id="suppliers_list_link">Suppliers List</p>
						<p id="supplier_invoices_list_link">Invoices</p>
						<p id="supplier_payments">Payments</p>
						<p id="supplier_add_new">Add/Update</p>
					</div>
				</div>
				<div id="admin_customers" class="propThumbCl">
					<div class="adminLabels">
						<div class="adminIcons"><i class="fas fa-shopping-cart"></i></div>
						<h5>Customers</h5>
						<div class="subMenuButton"><i class="fas fa-chevron-down"></i><i class="fas fa-chevron-up"></i></div>
					</div>
					<div class="adminSubMenus">
						<p id="customers_list_link">Customers List</p>
						<p id="customer_invoices_link">Invoices</p>
						<p id="customer_payments">Payments</p>
						<p id="add_new_customer_link">Add/Update</p>
					</div>
				</div>
				<div id="admin_products" class="propThumbCl">
					<div class="adminLabels">
						<div class="adminIcons"><i class="fab fa-pagelines"></i></div>
						<h5>Products</h5>
						<div class="subMenuButton"><i class="fas fa-chevron-down"></i><i class="fas fa-chevron-up"></i></div>
					</div>
					<div class="adminSubMenus">
						<p id="products_list_link">Prices for customers</p>
						<p id="add_new_products_link">Add/Update</p>
					</div>
				</div>
				<div id="admin_inventories" class="propThumbCl">
					<div class="adminLabels">
						<div class="adminIcons"><i class="fas fa-warehouse"></i></div>
						<h5>Inventories</h5>
						<div class="subMenuButton"><i class="fas fa-chevron-down"></i><i class="fas fa-chevron-up"></i></div>
					</div>
					<div class="adminSubMenus">
						<?php echo $activeInventories; ?>
						<p id="stock_link">Combined overview</p>
						<p id="stock_link_transfer">Transfer</p>
					</div>
				</div>

				<!-- <div id="admin_achieved_inventories" class="propThumbCl">
					<div class="adminLabels">
						<div class="adminIcons"><i class="fas fa-archive"></i></div>
						<h5>Inactive Inventories</h5>
						<div class="subMenuButton"><i class="fas fa-chevron-down"></i><i class="fas fa-chevron-up"></i></div>
					</div>
					<div class="adminSubMenus">
						<?php // echo $inactiveInventories;
						?>
					</div>
				</div> -->
				<div id="admin_balances" class="propThumbCl">
					<div class="adminLabels">
						<div class="adminIcons"><i class="fas fa-balance-scale"></i></div>
						<h5>Balances</h5>
						<div class="subMenuButton"><i class="fas fa-chevron-down"></i><i class="fas fa-chevron-up"></i></div>
					</div>
					<div class="adminSubMenus">
						<p id="monthly_balance_link">Current month</p>
					</div>
					<div class="adminSubMenus">
						<p id="select_period_balance_link">Select period</p>
					</div>
				</div>
			</div><!-- form_thumb_main_wrapp -->

			<div id="suppliersTableWrapper" class="adminBoards">
				<h3>Suppliers</h3>
				<table id="suppliersTable">
					<thead>
						<tr>
							<th>Supplier</th>
							<th>Balance</th>
							<th class="no-sort">Details</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>

				<div id="totalDebtToSuppliers"></div>

				<div id="supplierBalanceTableWrapper" class="adminBoards">
					Supplier Payments <div id="supplierName"></div>
					<table id="supplierBalanceTable">
						<thead>
							<tr>
								<th>Invoice ID</th>
								<th>Total</th>
								<th>Date</th>
								<th>Status</th>
								<th class="no-sort">Details</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>

			<div id="supplierInvoicesTableWrapper" class="adminBoards">
				<h3>Supplier Invoices</h3>
				<table id="supplierInvoicesTable">
					<thead>
						<tr>
							<th>Invoice ID</th>
							<th>Supplier</th>
							<th>Total</th>
							<th>Status</th>
							<th>Date</th>
							<th class="no-sort">Details</th>
							<th class="no-sort">Completed</th>
							<th class="no-sort">Edit</th>
							<th class="no-sort">Delete</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
				<br>
				<div id="supplierInvoiceEditMsg" class="phpMsg"></div>
				<div id="supplierDeleteInvoiceMsg" class="phpMsg"></div>
				<div id="supplierConfirmDeleteInvoiceMsg" class="phpMsg"></div>

				<div id="editSupplierInvoiceWrapper" class="adminBoards">
					<form id="form_requisition_to_supplier" class="">
						<div id="form_2_main_wrapp" class="propAllMainFormWrapp">
							<div id="form_2_subb_1">
								<h3 id="invoiceNumber" data-id="">Invoice Input</h3>
								<div class="propAllSmallFormInOut disFlex">
									<h4>Date</h4>
									<input required id="supplierInvoiceDateInput" type="date" placeholder="Date" class="propAllOutputsForm">
								</div>
								<div class="propAllSmallFormInOut disFlex">
									<h4>Supplier</h4>
									<select required id="supplierInvoiceSupplierSelect" class="propAllOutputsForm">
										<?= $suppliersHTML ?? '' ?>
									</select>
								</div>
								<div class="for_fill_out_with_products">

								</div>
								<div id="form_1_subb_2_2" class="propAllSubbOrFor">
									<div class="propAllSmallFormInOut disFlex">
										<h4>Quantity</h4>
										<input id="supplierInvoiceProductQuantityInput" type="number" placeholder="Quantity" class="propAllOutputsForm" step="0.01">
									</div>

									<div class="propAllSmallFormInOut disFlex">
										<h4>Type of Product</h4>
										<select id="supplierInvoiceProductsSelection" class="propAllOutputsForm">
											<?= $productsForSupplierHTML ?? '' ?>
										</select>
									</div>
									<div class="propAllSmallFormInOut disFlex">
										<h4>Price</h4>
										<input id="supplierInvoiceProductPriceInput" type="number" placeholder="Price" class="propAllOutputsForm">
									</div>
									<div id="addProduct" class="propAddMore">Add</div>
								</div>
								<div class="invoiceNoteWrapper">
									<h4> Note </h4>
									<div class="invoiceNoteInner">
										<textarea class="invoiceNoteArea" id="supplierInvoiceNoteEdit"></textarea>
									</div>
								</div>
								<div id="total_price_weight_main_2">
									<div class="propAllSmallFormInOut disFlex">
										<h3>Total Price</h3>
										<input id="supplierInvoicePriceTotal" step="0.01" type="number" placeholder="" class="propAllOutputPrWe">
									</div>
								</div>
								<input id="submit_for_order_2" class="propInputTypeSubmit" type="submit" value="Update" name="">
							</div>
						</div>
					</form>
				</div>
			</div>

			<div id="supplierAddNewWrapper" class="adminBoards">
				<div id="supplierAddNew_wrapper">
					<h3 class="mH3Cl">Add New Supplier</h3>
					<form id="add_new_supp_form">
						<div class="disFlex addNewSuppClass">
							<label for="new_supplier_name">Name</label>
							<input id="new_supplier_name" class="margLeft" type="text"><span class="redStar">*</span>
						</div>
						<div class="disFlex addNewSuppClass">
							<label for="new_supplier_balance">Balance</label>
							<input id="new_supplier_balance" class="margLeft" type="number" step="0.01" value="0.00">
						</div>
						<div id="add_new_supp_main" class="disFlex">
							<div id="imag_div_dis"></div><button id="add_new_supplier" class="margLeft">Add</button>
						</div>
					</form>
				</div>
				<div id="addNewSupplierMsg" class="phpMsg"></div>

				<div id="supplierAddNew_wrapper_2">
					<table id="editSuppliersTable">
						<thead>
							<tr>
								<th>ID</th>
								<th>Supplier</th>
								<th>Active</th>
								<th class="no-sort">Update</th>
								<!--<th class="no-sort">Delete</th>-->
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>

				</div>
				<div id="updateSupplierPhpMsg" class="phpMsg"></div>

			</div>

			<div id="supplierPaymentsTableWrapper" class="adminBoards">
				<h3>Supplier Payments</h3>
				<table id="supplierPaymentsTable">
					<thead>
						<tr>
							<th>Payment ID</th>
							<th>Supplier</th>
							<th>Amount</th>
							<th>Status</th>
							<th>Date</th>
							<th>Completed</th>
							<th>Edit</th>
							<th>Delete</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
				<br>
				<div id="supplierDeletePaymentMsg" class="phpMsg"></div>
				<div id="supplierConfirmDeletePaymentMsg" class="phpMsg"></div>

				<div id="edit_supplier_payment_wrapper" class="adminBoards">
					<div>
						<h3>Edit payment</h3>
						<div class="propAllSmallFormInOut disFlex">
							<h4>Date</h4>
							<input id="supplierPaymentDate" type="date" placeholder="Date" class="propAllOutputsForm">
						</div>
						<div class="propAllSmallFormInOut disFlex">
							<h4>Supplier</h4>
							<select id="supplierPaymentSelect" class="propAllOutputsForm">
								<?= $suppliersHTML ?? '' ?>
							</select>
						</div>
						<div class="propAllSmallFormInOut disFlex">
							<h4>Amount</h4>
							<input id="supplierPaymentAmount" type="number" placeholder="" class="propAllOutputsForm">
						</div>
						<input id="supplier_payment_id" type="hidden" data-id="">
						<input id="update_supplier_payment" class="propInputTypeSubmit" type="submit" value="Update" name="">
					</div>
					<br>
					<div id="supplierPaymentMsg" class="phpMsg"></div>
				</div>
			</div>

			<div id="customersTableWrapper" class="adminBoards">
				<h3>Customers</h3>
				<table id="customersTable">
					<thead>
						<tr>
							<th>Customer</th>
							<th>Balance</th>
							<!-- <th>Inventory</th> -->
							<th class="no-sort">Invoices</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>

				<div id="totalCustomersDebt"></div>

				<div id="customerBalanceTableWrapper" class="adminBoards">
					<div id="customerName"></div>
					<table id="customerBalanceTable">
						<thead>
							<tr>
								<th>Invoice ID</th>
								<th>Total</th>
								<th>Date</th>
								<th class="no-sort">Details</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>

			<div id="changePasswordWrapper" class="adminBoards" style="text-align: center;margin-left: 335px;">
				<div id="changePasswordWrapper_subb_1">
					<h3 class="mh3Class">Change Password</h3>
					<form id="change_password_form">
						<input type="hidden" name="userid" id="userid" value="<?php echo $_SESSION['userID']; ?>">
						<div>
							<label for="change_password">New Password</label>
							<input id="change_password" type="password" maxlength="255" pattern=".{8,}" title="8 characters minimum" required><span class="redStar">*</span>
						</div>
						<div>
							<label for="change_password_confirm">Confirm Password</label>
							<input id="change_password_confirm" type="password" maxlength="255" pattern=".{8,}" title="8 characters minimum" required><span class="redStar">*</span>
						</div>

						<div>
							<button id="button_change_password">Add</button>
						</div>
					</form>
				</div>
				<br>
				<div id="changepasswordMsg" class="phpMsg"></div>


			</div>


			<div id="addNewCustomerWrapper" class="adminBoards">
				<div id="addNewCustomerWrapper_subb_1">
					<h3 class="mh3Class">Add New Customer</h3>
					<form id="add_new_customer_form">
						<div>
							<label for="new_customer_name">Name</label>
							<input id="new_customer_name" type="text" required><span class="redStar">*</span>
						</div>
						<div>
							<label for="new_customer_password">Password</label>
							<input id="new_customer_password" type="text" maxlength="255" pattern=".{8,}" title="8 characters minimum" required><span class="redStar">*</span>
						</div>
						<div>
							<label for="new_customer_balance">Balance (customer debt)</label>
							<input id="new_customer_balance" type="number" step="0.01" value="0.00">
						</div>
						<!-- <div>
							<label for="new_customer_inventory">Inventory</label>
							<select id="new_customer_inventory">
								<option value="1">Calgary</option>
								<option value="0">Edmonton</option>
							</select><span class="redStar">*</span>
						</div> -->
						<div>
							<button id="add_new_customer">Add</button>
						</div>
					</form>
				</div>
				<br>
				<div id="addNewCustomerMsg" class="phpMsg"></div>

				<div id="addNewCustomerWrapper_subb_2">
					<table id="editCustomersTable">
						<thead>
							<tr>
								<th>ID</th>
								<th>Customer</th>
								<!-- <th>Inventory</th> -->
								<th>Active</th>
								<th>New Password</th>
								<th class="no-sort">Update</th>
								<th class="no-sort">Account</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>

			<div id="customerInvoicesTableWrapper" class="adminBoards">
				<h3>Customer Invoices</h3>
				<table id="customerInvoicesTable">
					<thead>
						<tr>
							<th>Invoice ID</th>
							<th>Customer</th>
							<th>Total</th>
							<th>Status</th>
							<th>Date</th>
							<th class="no-sort">Details</th>
							<th class="no-sort">Complete</th>
							<th class="no-sort">Edit</th>
							<th class="no-sort">Delete</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>

				<br>
				<div id="editCustomerInvoiceMsg" class="phpMsg"></div>
				<div id="customerDeleteInvoiceMsg" class="phpMsg"></div>
				<div id="customerConfirmDeleteInvoiceMsg" class="phpMsg"></div>

				<div id="editCustomerInvoiceWrapper" class="adminBoards">
					<form id="form_requisition_to_customer" class="">
						<div id="form_1_main_wrapp" class="propAllMainFormWrapp">
							<div id="form_1_subb_1">
								<h3 id="customerInvoiceNumber" data-id="">Invoice Input</h3>
								<div class="propAllSmallFormInOut disFlex">
									<h4>Date</h4>
									<input required id="customerInvoiceDateInput" type="date" placeholder="Date" class="propAllOutputsForm">
								</div>
								<div class="propAllSmallFormInOut disFlex">
									<h4>My Customer</h4>
									<select required id="customerInvoiceCustomerSelect" class="propAllOutputsForm">
										<?= $customersHTML ?? '' ?>
									</select>
								</div>
								<div class="propAllSmallFormInOut disFlex">
									<h4>Inventories</h4>
									<select id="customerInvoiceProductsinverntories" class="propAllOutputsForm" required>
										<option value="">Please Select</option>
										<option value="calgary">Calgary</option>
										<option value="edmonton">Edmonton</option>
										<option value="toronto">Toronto</option>
										<option value="kelowna">Kelowna</option>
									</select>
								</div>
								<div class="for_fill_out_with_products">
								</div>
								<div id="form_1_subb_2" class="propAllSubbOrFor">
									<div class="propAllSmallFormInOut disFlex">
										<h4>Quantity</h4>
										<input id="customerInvoiceProductQuantityInput" type="number" placeholder="Quantity" class="propAllOutputsForm" step="0.01">
									</div>
									<div class="propAllSmallFormInOut disFlex">
										<h4>Type of Product</h4>
										<select id="customerInvoiceProductsSelection" class="propAllOutputsForm">
											<?= $productsForCustomerHTML ?? '' ?>
										</select>
									</div>
									<div class="propAllSmallFormInOut disFlex">
										<h4>Price</h4>
										<input id="customerInvoiceProductPriceInput" type="number" placeholder="Price" class="propAllOutputsForm">
									</div>
									<div id="addProductCustomer" class="propAddMore">Add</div>
									<div id="qtyerror"></div>
								</div>
								<div class="invoiceNoteWrapper">
									<h4> Note </h4>
									<div class="invoiceNoteInner">
										<textarea class="invoiceNoteArea" id="customerInvoiceNoteEdit"></textarea>
									</div>
								</div>
								<div id="total_price_weight_main">
									<div class="propAllSmallFormInOut disFlex">
										<h3>Total Price</h3>
										<input disabled id="customerInvoicePriceTotal" step="0.01" type="number" placeholder="" class="propAllOutputPrWe">
									</div>
								</div>
								<input id="submit_for_order" class="propInputTypeSubmit" type="submit" value="Update" name="">
							</div>
						</div>
					</form>
				</div>
			</div>

			<div id="customerPaymentsTableWrapper" class="adminBoards">
				<h3>Customer Payments</h3>
				<table id="customerPaymentsTable">
					<thead>
						<tr>
							<th>Payment ID</th>
							<th>Customer</th>
							<th>Amount</th>
							<th>Date</th>
							<th>Status</th>
							<th>Completed</th>
							<th>Edit</th>
							<th>Delete</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
				<br>

				<div id="customerDeletePaymentMsg" class="phpMsg"></div>
				<div id="customerConfirmDeletePaymentMsg" class="phpMsg"></div>

				<div id="edit_customer_payment_wrapper" class="adminBoards">
					<div id="form_3_subb_1">
						<h3>Customer payment</h3>
						<div class="propAllSmallFormInOut disFlex">
							<h4>Date</h4>
							<input id="customerPaymentDate" type="date" placeholder="Date" class="propAllOutputsForm">
						</div>
						<div class="propAllSmallFormInOut disFlex">
							<h4>Customer</h4>
							<select id="customerPaymentSelect" class="propAllOutputsForm">
								<?= $customersHTML ?? '' ?>
							</select>
						</div>
						<div class="propAllSmallFormInOut disFlex">
							<h4>Amount</h4>
							<input id="customerPaymentAmount" type="number" placeholder="" class="propAllOutputsForm">
						</div>
						<input id="customer_payment_id" type="hidden" data-id="">
						<input id="update_customer_payment" class="propInputTypeSubmit" type="submit" value="Update" name="">
					</div>
					<br>
					<div id="customerPaymentMsg" class="phpMsg"></div>
					<br>
				</div>
			</div>

			<div id="addNewProductWrapper" class="adminBoards">
				<div id="addNewProduct_wrapper">
					<h3>Add New Product</h3>
					<form id="add_new_product_form">
						<div class="disFlex addNewSuppClass">
							<label for="new_product_name">Product Name</label>
							<input id="new_product_name" type="text" class="margLeft" value=""><span class="redStar">*</span>
						</div>
						<div class="disFlex addNewSuppClass">
							<label for="new_product_name">Calgary quantity</label>
							<input id="new_product_calgary_quantity" class="margLeft marRight" type="number" value="0" step="0.01">
						</div>
						<div class="disFlex addNewSuppClass">
							<label for="new_product_name">Edmonton quantity</label>
							<input id="new_product_calgary_edmonton" class="margLeft marRight" type="number" value="0" step="0.01">
						</div>
						<div class="disFlex addNewSuppClass">
							<label for="new_product_name">Toronto quantity</label>
							<input id="new_product_calgary_toronto" class="margLeft marRight" type="number" value="0" step="0.01">
						</div>
						<div class="disFlex addNewSuppClass">
							<label for="new_product_name">Kelowna quantity</label>
							<input id="new_product_calgary_kelowna" class="margLeft marRight" type="number" value="0" step="0.01">
						</div>
						<div class="disFlex addNewSuppClass">
							<label for="new_product_name">Supplier Price</label>
							<input id="new_product_supplier_price" class="margLeft marRight" type="number" step="0.01" value="0.00">
						</div>
						<div id="add_new_product_main" class="disFlex">
							<div id="imag_div_product"></div><button id="add_new_product" class="margLeft">Add</button>
						</div>
					</form>
				</div>
				<div id="addNewProductMsg" class="phpMsg"></div>
				<div id="addNewProductWrapper_2">
					<table id="editProductsTable">
						<thead>
							<tr>
								<th>Product</th>
								<th>Active</th>
								<th class="no-sort">Update</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
				<div id="addProductPhpMsg" class="phpMsg"></div>
			</div>

			<div id="productTableWrapper" class="adminBoards">
				<h3>Set Customer Prices 
				<label class="switch toggleSwitch" data-invenName="CustomerPrice">
						<input type="checkbox" checked id="toggleSwitchCustomerPrice" name="toggleSwitchCustomerPrice" value="1">
						<span class="slider round"></span>
					</label>
				</h3>
				<div id="productPricesTableWrapper"></div>
				<br>
				<div id="productPricesMsg" class="phpMsg"></div>
			</div>

			<div id="stockTableWrapper" class="adminBoards">
				<h3>Combined overview
					<label class="switch toggleSwitch" data-invenName="combined">
						<input type="checkbox" checked id="toggleSwitchCombined" name="toggleSwitchCombined" value="1">
						<span class="slider round"></span>
					</label>
				</h3>
				<table id="stockTable">
					<thead>
						<tr>
							<th>Product</th>
							<th>Calgary</th>
							<th>Edmonton</th>
							<th>Toronto</th>
							<th>Kelwona</th>
							<th>Calgary Reserved</th>
							<th>Edmonton Reserved</th>
							<th>Toronto Reserved</th>
							<th>Kelwona Reserved</th>
							<th>Price</th>
							<th>Calgary Total</th>
							<th>Edmonton Total</th>
							<th>Toronto Total</th>
							<th>Kelwona Total</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
					<tfoot>
						<tr>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th>Calgary Total</th>
							<th>Edmonton Total</th>
							<th>Toronto Total</th>
							<th>Kelwona Total</th>
						</tr>
						<tr>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th id="tes2">Total:</th>
							<th id="test"></th>
						</tr>
					</tfoot>
				</table>
				<div id="stockTotal"></div>
			</div>

			<div id="calgaryTableWrapper" class="adminBoards">
				<h3>Calgary
					<label class="switch toggleSwitch" data-invenName="calgary">
						<input type="checkbox" checked id="toggleSwitchCalgary" name="toggleSwitchCalgary" value="1">
						<span class="slider round"></span>
					</label>
				</h3>
				<!-- <button id="addProductBtnForCalgary">Add Product</button>
				<div id="addNewProductWrapperForCalgary" style="display: none;">
					<div id="addNewProduct_wrapper">
						<h3>Add New Product</h3>
						<form id="add_new_product_calagary" class="horizontal-form ">
							<div class="form-group">
								<label for="new_product_nameForCalgary">Product Name</label>
								<input id="new_product_nameForCalgary" type="text" value="" required style="margin-left: 7px;">
								<span class="redStar">*</span>
							</div>
							<div class="form-group">
								<label for="new_product_calgary_quantityForCalgary">Calgary Quantity</label>
								<input id="new_product_calgary_quantityForCalgary" type="number" value="0" step="0.01">
							</div>
							<div class="form-group">
								<label for="new_product_supplier_priceForCalgary">Supplier Price</label>
								<input id="new_product_supplier_priceForCalgary" type="number" step="0.01" value="0.00" style="margin-left: 4px;">
							</div>
							<div class="form-group">
								<button id="add_new_productForCalgary">Add</button>
							</div>
						</form>
					</div>
				</div>
				<div id="addNewProductMsgForCalgary" class="phpMsg"></div> -->

				<!-- Rounded switch -->


				<table id="calgaryTable">
					<thead>
						<tr>
							<th>Product</th>
							<th>Quantity</th>
							<th>Reserved</th>
							<th>Price</th>
							<th>Product Total</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
					<tfoot>
						<tr>
							<th></th>
							<th></th>
							<th></th>
							<th>Total:</th>
							<th></th>
						</tr>
					</tfoot>
				</table>
			</div>

			<div id="edmontonTableWrapper" class="adminBoards">
				<h3>Edmonton
					<label class="switch toggleSwitch" data-invenName="edmonton">
						<input type="checkbox" checked name="toggleSwitchEdmonton" id="toggleSwitchEdmonton" value="<?php echo $isEdmontonActive ?>">
						<span class="slider round"></span>
					</label>
				</h3>
				<!-- <button id="addProductBtnForEdmonton">Add Product</button>
				<div id="addNewProductWrapperForEdmonton" style="display: none;">
					<div id="addNewProduct_wrapper">
						<h3>Add New Product</h3>
						<form id="add_new_product_edmonton" class="horizontal-form ">
							<div class="form-group">
								<label for="new_product_nameForEdmonton">Product Name</label>
								<input id="new_product_nameForEdmonton" type="text" value="" required style="margin-left: 20px;">
								<span class="redStar">*</span>
							</div>
							<div class="form-group">
								<label for="new_product_calgary_quantityForEdmonton">Edmonton Quantity</label>
								<input id="new_product_calgary_quantityForEdmonton" type="number" value="0" step="0.01">
							</div>
							<div class="form-group">
								<label for="new_product_supplier_priceForEdmonton">Supplier Price</label>
								<input id="new_product_supplier_priceForEdmonton" type="number" step="0.01" value="0.00" style="margin-left: 18px;">
							</div>
							<div class="form-group">
								<button id="add_new_productForEdmonton">Add</button>
							</div>
						</form>
					</div>
				</div>
				<div id="addNewProductMsgForEdmonton" class="phpMsg"></div> -->

				<table id="edmontonTable">
					<thead>
						<tr>
							<th>Product</th>
							<th>Quantity</th>
							<th>Reserved</th>
							<th>Price</th>
							<th>Product Total</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
					<tfoot>
						<tr>
							<th></th>
							<th></th>
							<th></th>
							<th>Total:</th>
							<th></th>
						</tr>
					</tfoot>
				</table>
			</div>

			<div id="torontoTableWrapper" class="adminBoards">
				<h3>Toronto
					<label class="switch toggleSwitch" data-invenName="toronto">
						<input type="checkbox" checked id="toggleSwitchToronto" name="toggleSwitchToronto" value="<?php echo $isTorontoActive ?>">
						<span class="slider round"></span>
					</label>
				</h3>
				<!-- <button id="addProductBtnForToronto">Add Product</button>
				<div id="addNewProductWrapperForToronto" style="display: none;">
					<div id="addNewProduct_wrapper">
						<h3>Add New Product</h3>
						<form id="add_new_product_toronto" class="horizontal-form ">
							<div class="form-group">
								<label for="new_product_nameForToronto">Product Name</label>
								<input id="new_product_nameForToronto" type="text" value="" required style="margin-left: 20px;">
								<span class="redStar">*</span>
							</div>
							<div class="form-group">
								<label for="new_product_calgary_quantityForToronto">Toronto Quantity</label>
								<input id="new_product_calgary_quantityForToronto" type="number" value="0" step="0.01">
							</div>
							<div class="form-group">
								<label for="new_product_supplier_priceForToronto">Supplier Price</label>
								<input id="new_product_supplier_priceForToronto" type="number" step="0.01" value="0.00" style="margin-left: 18px;">
							</div>
							<div class="form-group">
								<button id="add_new_productForToronto">Add</button>
							</div>
						</form>
					</div>
				</div>
				<div id="addNewProductMsgForToronto" class="phpMsg"></div> -->

				<table id="torontoTable">
					<thead>
						<tr>
							<th>Product</th>
							<th>Quantity</th>
							<th>Reserved</th>
							<th>Price</th>
							<th>Product Total</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
					<tfoot>
						<tr>
							<th></th>
							<th></th>
							<th></th>
							<th>Total:</th>
							<th></th>
						</tr>
					</tfoot>
				</table>
			</div>

			<div id="kelownaTableWrapper" class="adminBoards">
				<h3>Kelowna
					<label class="switch toggleSwitch" data-invenName="kelowna">
						<input type="checkbox" checked id="toggleSwitchKelowna" name="toggleSwitchKelowna" value="<?php echo $isKelownaActive ?>">
						<span class="slider round"></span>
					</label>
				</h3>
				<!-- <button id="addProductBtnForKelowna">Add Product</button>
				<div id="addNewProductWrapperForKelowna" style="display: none;">
					<div id="addNewProduct_wrapper">
						<h3>Add New Product</h3>
						<form id="add_new_product_kelowna" class="horizontal-form ">
							<div class="form-group">
								<label for="new_product_nameForKelowna">Product Name</label>
								<input id="new_product_nameForKelowna" type="text" value="" required style="margin-left: 20px;">
								<span class="redStar">*</span>
							</div>
							<div class="form-group">
								<label for="new_product_calgary_quantityForKelowna">Kelowna Quantity</label>
								<input id="new_product_calgary_quantityForKelowna" type="number" value="0" step="0.01">
							</div>
							<div class="form-group">
								<label for="new_product_supplier_priceForKelowna">Supplier Price</label>
								<input id="new_product_supplier_priceForKelowna" type="number" step="0.01" value="0.00" style="margin-left: 18px;">
							</div>
							<div class="form-group">
								<button id="add_new_productForKelowna">Add</button>
							</div>
						</form>
					</div>
				</div>
				<div id="addNewProductMsgForKelowna" class="phpMsg"></div> -->

				<table id="kelownaTable">
					<thead>
						<tr>
							<th>Product</th>
							<th>Quantity</th>
							<th>Reserved</th>
							<th>Price</th>
							<th>Product Total</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
					<tfoot>
						<tr>
							<th></th>
							<th></th>
							<th></th>
							<th>Total:</th>
							<th></th>
						</tr>
					</tfoot>
				</table>
			</div>

			<div id="transferWrapper" class="adminBoards">
				<h2>Transfer</h2>
				<div>
					<label>Product</label>
					<select id="transfer_product_selection" style="margin-left: 57px;">
					</select>
				</div>
				<div>
					<label>From Inventory</label>
					<select id="supplierfrominventory">
						<option value="0">Please Select</option>
						<option value="calgary">Calgary</option>
						<option value="edmonton">Edmonton</option>
						<option value="toronto">Toronto</option>
						<option value="kelowna">Kelowna</option>
					</select>
				</div>
				<div>
					<label>To Inventory</label>
					<select id="suppliertoinventory" style="margin-left: 20px;">
						<option value="0">Please Select</option>
						<option value="calgary">Calgary</option>
						<option value="edmonton">Edmonton</option>
						<option value="toronto">Toronto</option>
						<option value="kelowna">Kelowna</option>
					</select>
				</div>

				<!-- </div> -->
				<div>
					<div class="inlineB">
						<p>qty</p>
						<input id="suppliertransferqty" type="number" min="0" max="" step="0.01" value="">
					</div>
					<!-- <div class="inlineB">
						<p>Edmonton</p>
						<input id="edmontonInput" type="number" min="0" max="" step="0.01" value="">
					</div> -->
					<div class="inlineB">
						<p></p>
						<button id="transfer_button">Transfer</button>
					</div>
				</div>
				<br>
				<div id="transferMsg" class="phpMsg"></div>
			</div>

			<div id="monthlyBalanceWrapper" class="adminBoards">
				<h3><?= date('M Y') ?></h3>
				<div id="month_balance_main_wrapper">
					<div id="month_balance_main_wrapper_subb">
						<div id="parameter_row_mont_bal">
							<div id="par_empty" class="inlineBlock propHeadRowPara">Product</div>
							<div id="par_r_1" class="inlineBlock propHeadRowPara">Bought</div>
							<div id="par_r_2" class="inlineBlock propHeadRowPara">Price</div>
							<div id="par_r_3" class="inlineBlock propHeadRowPara">Sold</div>
							<div id="par_r_4" class="inlineBlock propHeadRowPara">Revenue</div>
							<div id="par_r_5" class="inlineBlock propHeadRowPara">Profit</div>
							<div id="par_r_6" class="inlineBlock propHeadRowPara">Bought - Sold</div>
						</div>
						<div id="main_row_div_wrapper_month_bala">
							<div id="merch_type" class="inlineBlock">

							</div>
							<div id="fill_table_row_month_bala" class="inlineBlock">
								<div id="f_t_1" class="inlineBlock">

								</div>
								<div id="f_t_2" class="inlineBlock">

								</div>
								<div id="f_t_3" class="inlineBlock">

								</div>
								<div id="f_t_4" class="inlineBlock">

								</div>
								<div id="f_t_5" class="inlineBlock">

								</div>
								<div id="f_t_6" class="inlineBlock">

								</div>
							</div>
						</div><!-- main_row_div_wrapper_month_bala -->
					</div><!-- month_balance_main_wrapper_subb -->
				</div><!-- month_balance_main_wrapper -->
			</div><!-- monthlyBalanceWrapper -->

			<div id="select_period_balance_wrapper" class="adminBoards">

				<div id="date_pick_form_wrapper">
					<form id="date_pick_form" method="post">
						<div>
							<div>Date from:</div>
							<input id="startDate" type="date" required>
						</div>
						<br>
						<div>
							<div>Date to:</div>
							<input id="endDate" type="date" required>
						</div>
						<br>
						<div>
							<input id="date_picker_submit" type="submit" value="Submit">
						</div>
					</form>
				</div>

				<br>

				<div id="balanceMsg" class="phpMsg"></div>

				<div id="select_period_balance_table_wrapper">
					<div id="s_month_balance_main_wrapper_subb">
						<div id="s_parameter_row_mont_bal">
							<div id="s_par_empty" class="inlineBlock propHeadRowPara">Product</div>
							<div id="s_par_r_1" class="inlineBlock propHeadRowPara">Bought</div>
							<div id="s_par_r_2" class="inlineBlock propHeadRowPara">Price</div>
							<div id="s_par_r_3" class="inlineBlock propHeadRowPara">Sold</div>
							<div id="s_par_r_4" class="inlineBlock propHeadRowPara">Revenue</div>
							<div id="s_par_r_5" class="inlineBlock propHeadRowPara">Profit</div>
							<div id="s_par_r_6" class="inlineBlock propHeadRowPara">Bought - Sold</div>
						</div>
						<div id="s_main_row_div_wrapper_month_bala">
							<div id="s_merch_type" class="inlineBlock"></div>
							<div id="s_fill_table_row_month_bala" class="inlineBlock">
								<div id="s_f_t_1" class="inlineBlock"></div>
								<div id="s_f_t_2" class="inlineBlock"></div>
								<div id="s_f_t_3" class="inlineBlock"></div>
								<div id="s_f_t_4" class="inlineBlock"></div>
								<div id="s_f_t_5" class="inlineBlock"></div>
								<div id="s_f_t_6" class="inlineBlock"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div><!-- admin_dash_wrapper -->
	</div><!-- w_e_i_main_wrapp -->

	<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js" type="text/javascript"></script>
	<script src="https://cdn.datatables.net/buttons/1.2.1/js/dataTables.buttons.min.js" type="text/javascript"></script>
	<script src="<?php getAssetLink('administrator-panel-v*.js', 'js'); ?>"></script>
</body>

</html>