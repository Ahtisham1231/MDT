<?php
require_once '../../src/init.php';
require_once SRC . 'connect.php';
require_once SRC . 'controller/user-panel.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="MDTAT" content="<?= generateAjaxToken(); ?>">
	<title><?= $pageTitle ?? ''; ?></title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.min.js"integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
	<link rel="stylesheet" href="<?php getAssetLink('css-v*.css','css'); ?>">
</head>
<body>

	<div id="w_e_i_main_wrapp">
		<?php getNavbar($pageTitle); ?>

		<div id="documents_inner">

			<div class="docs_h1">
				<h1>User Panel</h1>
			</div>

			<div id="popup_supplier_invoice_details">
				<div id="supp_invoice_wrapper">
					<div id="supp_inv_main_wrap_head" class="disFlex">
		                <div id="supp_invo_subb_2" class="margLeft">
		                	<h3>Invoice Number: <span id="supp_inv_id"></span></h3>
		                	<h3>Date: <span id="supp_inv_date"></span></h3>
		                </div>
		            </div>
		            <!-- <div id="supp_inv_main_wrap_head_2" class="disFlex">
		                <div id="supp_invo_subb_3">
			                <h2>Bill to:</h2>
							<h3>Name of Supplier: <span id="supp_inv_supplier"></span></h3>
							<h3>Company Name: <span id="supp_comp_name_supplier"></span></h3>
							<h3>Name of Supplier: <span id="supp_street_address_supplier"></span></h3>
						</div>
						<div id="supp_invo_subb_4">
							<h3>City, ST ZIP: <span id="supp_cityzipst_supplier"></span></h3>
							<h3>Phone Number: <span id="supp_ph_num_supplier"></span></h3>
						    <h3>Email Address: <span id="supp_em_add_supplier"></span></h3>
						</div>
					</div> -->
					<div id="supp_inv_table"></div>
					<h3 id="am_h3">Total Amount: <span id="supp_inv_total"></span></h3>
					<div class="invoiceNoteWrapper">
		            	<h4> Note </h4>
		            	<div class="invoiceNoteInner">
		            		<textarea class="invoiceNoteArea" id="customerInvoiceNoteOutput"></textarea>
		            	</div>
		            </div>
					<!-- <button>PRINT</button> -->
				</div>
			</div>

			<div id="user_dash_wrapper">

				<div id="form_thumb_main_wrapp" class="userWrapper">

					<a id="make_order_link" href="" class="">
						<div id="form_thumb_subb_2" class="propThumbCl">
						    <h5>PLACE AN ORDER</h5>
						    <div class="thumb_img">
						    	<!-- <img src="images/m4.jpg"> -->
						    </div>
						</div>
					</a>
					<a id="my_orders_link" href="" class="">
						<div id="form_thumb_subb_3" class="propThumbCl">
						    <h5>MY ORDERS</h5>
						    <div class="thumb_img">
						    	<!-- <img src="images/m4.jpg"> -->
						    </div>
						</div>
					</a>
					<a id="update_password_link" href="" class="">
						<div id="form_thumb_subb_4" class="propThumbCl">
						    <h5>UPDATE PASSWORD</h5>
						    <div class="thumb_img">
						    	<!--<img src="images/m4.jpg">-->
						    </div>
						</div>
					</a>
				</div><!-- form_1_main_wrapp -->

				<form id="form_requisition_to_supplier" class="userForms">
					<div id="form_2_main_wrapp" class="propAllMainFormWrapp">
						<div id="form_2_subb_1">
							<h3>Order Form</h3>

							<div class="for_fill_out_with_products"></div>

							<div id="form_1_subb_2_2" class="propAllSubbOrFor">
								<div class="propAllSmallFormInOut disFlex">
									<h4>Quantity</h4>
									<input id="supplierInvoiceProductQuantityInput" type="number" placeholder="Quantity" class="propAllOutputsForm" step="0.01">
								</div>
								<div class="propAllSmallFormInOut disFlex"><h4>Type of Product</h4>
									<select  id="supplierInvoiceProductsSelection" class="propAllOutputsForm">
										<?= $productsHTML ?? '' ?>
								    </select>
								</div>
								<div class="propAllSmallFormInOut disFlex">
									<h4>Price</h4>
									<input disabled id="supplierInvoiceProductPriceInput" type="number" step="0.01" placeholder="Price" class="propAllOutputsForm">
								</div>
								<div id="addProduct" class="propAddMore">Add</div>
				            </div>
				            <div class="invoiceNoteWrapper">
				            	<h4> Note </h4>
				            	<div class="invoiceNoteInner">
				            		<textarea class="invoiceNoteArea" id="customerInvoiceNoteInput"></textarea>
				            	</div>
				            </div>
				            <div id="total_price_weight_main_2">
				            	<div class="propAllSmallFormInOut disFlex">
									<h3>Total Price</h3>
									<input disabled id="supplierInvoicePriceTotal" type="number" step="0.01" placeholder="" class="propAllOutputPrWe">
								</div>
				            </div>
				            <input id="submit_for_order_2" class="propInputTypeSubmit" type="submit" value="Submit" name="">
						</div>
					</div><!-- form_1_main_wrapp -->
					<br>
					<div id="customerInvoiceMsg" class="phpMsg"></div>
				</form>

				<div id="myOrdersTableWrapper" class="userForms">
					<table id="myOrdersTable">
						<thead>
							<tr>
								<th>Order Number</th>
								<th>Total</th>
								<th>Status</th>
								<th>Date</th>
								<th class="no-sort">Details</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
					<br>
					<div id="myInvoicesTotal"></div>
					<br>
					<div id="myBalance"></div>

				</div>

				<form id="update_password_form" class="userForms">
					<h4>Enter Old password</h4>
					<input id="old_password_input" class="propAllOutputsForm" type="password" maxlength="255" pattern=".{8,}" title="8 characters minimum" required>
					<h4>Enter New Password</h4>
				    <input id="new_password_input" class="propAllOutputsForm" type="password" maxlength="255" pattern=".{8,}" title="8 characters minimum" required>
				    <input id="update_password_submit" class="propInputTypeSubmit" type="submit" value="Submit">
					<br>
					<div id="updatePasswordMsg" class="phpMsg"></div>
				</form>

			</div>
		</div>

		<?php getFooter($pageTitle); ?>
	</div>

<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.2.1/js/dataTables.buttons.min.js" type="text/javascript"></script>
<script src="<?php getAssetLink('user-panel-v*.js','js'); ?>"></script>
</body>
</html>