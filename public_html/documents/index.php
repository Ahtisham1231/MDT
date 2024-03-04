<?php
require_once '../../src/init.php';
require_once SRC . 'connect.php';
require_once SRC . 'controller/documents.php';
require_once CONT . 'documents.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="MDTAT" content="<?= generateAjaxToken(); ?>">
	<title><?= $pageTitle ?? ''; ?></title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
	<link rel="stylesheet" href="<?php getAssetLink('css-v*.css', 'css'); ?>">
</head>

<body>

	<div id="w_e_i_main_wrapp">
		<?php getNavbar($pageTitle); ?>

		<div id="documents_inner">
			<!--<div class="docs_h1">
				<h1>Documents</h1>
			</div>-->

			<div id="documents_wrapper">

				<div id="form_thumb_main_wrapp" class="disFlex">

					<a id="supplier_invoice_link" href="#">
						<div id="form_thumb_subb_1" class="propThumbCl">
							<h5>SUPPLIER INVOICE</h5>
						</div>
					</a>
					<a id="I_pay_to_link" href="#" class="margLeft">
						<div id="form_thumb_subb_2" class="propThumbCl">
							<h5>SUPPLIER PAYMENT</h5>
						</div>
					</a>
					<a id="customer_invoices_link" href="#" class="margLeft">
						<div id="form_thumb_subb_3" class="propThumbCl">
							<h5>CUSTOMER INVOICE</h5>
						</div>
					</a>
					<a id="payment_to_me_link" href="#" class="margLeft">
						<div id="form_thumb_subb_4" class="propThumbCl">
							<h5>CUSTOMER PAYMENT</h5>
						</div>
					</a>
					<a id="return_of_products_link" href="#" class="margLeft">
						<div id="form_thumb_subb_5" class="propThumbCl">
							<h5>RETURN OF PRODUCTS</h5>
						</div>
					</a>
					<a id="inventories_link" href="#" class="margLeft">
						<div id="form_thumb_subb_6" class="propThumbCl">
							<h5>SET INVENTORIES</h5>
						</div>
					</a>
				</div><!-- form_thumb_main_wrapp -->

				<div id="documents_right_side">

					<form id="form_requisition_to_supplier" class="documentsRightSides">
						<div id="form_2_main_wrapp" class="propAllMainFormWrapp">
							<div id="form_2_subb_1">
								<h3>Supplier invoice</h3>
								<div class="propAllSmallFormInOut disFlex marginFix">
									<h4>Date</h4>
									<input required id="supplierInvoiceDateInput" type="date" placeholder="Date" class="propAllOutputsForm">
								</div>
								<div class="propAllSmallFormInOut disFlex marginFix">
									<h4>Supplier</h4>
									<select required id="supplierInvoiceSupplierSelect" class="propAllOutputsForm">
										<?= $suppliersHTML ?? '' ?>
									</select>
								</div>
								<div class="propAllSmallFormInOut disFlex">
									<h4>Inventories</h4>
									<select id="supplierInvoiceProductsinverntories" class="propAllOutputsForm">
										<option value="">Please Select</option>
										<option value="calgary">Calgary</option>
										<option value="edmonton">Edmonton</option>
										<option value="toronto">Toronto</option>
										<option value="kelowna">Kelowna</option>
									</select>
								</div>
								<div id="labelsContainer" class="labelRow disFlex" style="display: none;">
									<div class="label" style="margin-left: 68px;">Name</div>
									<div class="label margLeft" style="margin-left: 90px;">Quantity</div>
									<div class="label margLeft" style="margin-right: 40px;">Price</div>
								</div>
								<div class="for_fill_out_with_products">

								</div>
								<div id="form_1_subb_2_2" class="propAllSubbOrFor">
									<div class="propAllSmallFormInOut disFlex">
										<h4>Quantity</h4>
										<input id="supplierInvoiceProductQuantityInput" type="number" placeholder="Quantity" class="propAllOutputsForm" step="0.01">
									</div>

									<div class="propAllSmallFormInOut disFlex">
										<h4>Product</h4>
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
										<textarea class="invoiceNoteArea" id="supplierInvoiceNoteInput"></textarea>
									</div>
								</div>
								<div id="total_price_weight_main_2">
									<div class="propAllSmallFormInOut disFlex">
										<h3>Total</h3>
										<input id="supplierInvoicePriceTotal" step="0.01" type="number" placeholder="" class="propAllOutputPrWe" disabled>
									</div>
									<!-- <div class="propAllSmallFormInOut disFlex">
											<h3>Total Weight</h3>
											<input type="number" placeholder="" class="propAllOutputPrWe">
										</div> -->
								</div>
								<input id="submit_for_order_2" class="propInputTypeSubmit" type="submit" value="Submit" name="">
							</div>
						</div>

						<br>
						<div id="supplierInvoiceMsg" class="phpMsg"></div>

					</form><!-- form_requisition_to_supplier -->

					<div id="form_4_main_wrapp" class="propAllMainFormWrapp documentsRightSides">
						<div id="form_4_subb_1">
							<h3>Supplier payment</h3>
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
							<input id="submit_for_order_4" class="propInputTypeSubmit" type="submit" value="Submit" name="">
						</div>

						<br>
						<div id="supplierPaymentMsg" class="phpMsg"></div>
						<br>

					</div>

					<form id="form_requisition_to_customer" class="documentsRightSides">
						<div id="form_1_main_wrapp" class="propAllMainFormWrapp">
							<div id="form_1_subb_1">
								<h3>Customer invoice</h3>
								<div class="propAllSmallFormInOut disFlex marginFix">
									<h4>Date</h4>
									<input required id="customerInvoiceDateInput" type="date" placeholder="Date" class="propAllOutputsForm">
								</div>
								<div class="propAllSmallFormInOut disFlex marginFix">
									<h4>Customer</h4>
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
								<div id="labelsContainerCustomer" class="labelRow disFlex" style="display: none;">
									<div class="label" style="margin-left: 68px;">Name</div>
									<div class="label margLeft" style="margin-left: 95px;">Qty</div>
									<div class="label margLeft" style="margin-left: 70px;">Price</div>
									<div class="label margLeft" style="margin-left: 60px;margin-right: 5px">Total</div>
								</div>
								<div class="for_fill_out_with_products">
									<!--<div class="propAbsForErase">x</div>
									<div id="name_of_prod" class="propListOrder"><span>Some Marijuana</span></div>
									<div id="kg_of_prod" class="margLeft propListOrder1"><span>50.500kg</span></div>
									<div id="price_of_prod" class="margLeft propListOrder1"><span>100.000$</span></div>-->
								</div>
								<div id="form_1_subb_2" class="propAllSubbOrFor">									
									<div class="propAllSmallFormInOut disFlex">
										<h4>Product</h4>
										<select id="customerInvoiceProductsSelection" class="propAllOutputsForm">

										</select>
									</div>
									<div class="propAllSmallFormInOut disFlex">
										<h4>Quantity</h4>
										<input id="customerInvoiceProductQuantityInput" type="number" placeholder="Quantity" class="propAllOutputsForm" step="0.01">
									</div>
									<div class="propAllSmallFormInOut disFlex">
										<h4>Price $</h4>
										<input id="customerInvoiceProductPriceInput" type="number" placeholder="Price" class="propAllOutputsForm">
									</div>
									<div id="addProductCustomer" class="propAddMore">Add</div>
									<div id="qtyerror"></div>
								</div>
								<div class="invoiceNoteWrapper">
									<h4> Note </h4>
									<div class="invoiceNoteInner">
										<textarea class="invoiceNoteArea" id="customerInvoiceNoteInput"></textarea>
									</div>
								</div>
								<div id="total_price_weight_main">
									<div class="propAllSmallFormInOut disFlex">
										<h3>Total $</h3>
										<input disabled id="customerInvoicePriceTotal" step="0.01" type="number" placeholder="" class="propAllOutputPrWe">
									</div>
									<!--<div class="propAllSmallFormInOut disFlex">
										<h3>Total Weight</h3>
										<input type="number" placeholder="" class="propAllOutputPrWe">
									</div>-->
								</div>
								<input id="submit_for_order" class="propInputTypeSubmit" type="submit" value="Submit" name="">
							</div>
						</div><!-- form_1_main_wrapp -->

						<br>
						<div id="customerInvoiceMsg" class="phpMsg"></div>

					</form><!-- form_requisition_to_customer -->

					<div id="form_3_main_wrapp" class="propAllMainFormWrapp documentsRightSides">
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
								<h4>Amount $</h4>
								<input id="customerPaymentAmount" type="number" placeholder="" class="propAllOutputsForm">
							</div>
							<input id="submit_for_order_3" class="propInputTypeSubmit" type="submit" value="Submit" name="">
						</div>

						<br>
						<div id="customerPaymentMsg" class="phpMsg"></div>
						<br>

					</div>

					<form id="return_of_products_form" class="documentsRightSides">
						<div id="form_5_main_wrapp" class="propAllMainFormWrapp">
							<div id="form_5_subb_1">
								<h3>Return of products</h3>
								<div class="propAllSmallFormInOut disFlex marginFix">
									<h4>Date</h4>
									<input id="return_invoice_date_input" class="propAllOutputsForm" type="date" placeholder="Date" required>
								</div>
								<div class="propAllSmallFormInOut disFlex marginFix">
									<h4>Invoice type</h4>
									<select id="return_invoice_select_type" class="propAllOutputsForm" required>
										<option value="N/A">Please select</option>
										<option value="0">Return to supplier</option>
										<option value="1">Return from customer</option>
									</select>
								</div>
								<div class="propAllSmallFormInOut disFlex">
									<h4>Inventories</h4>
									<select id="returnInvoiceProductsinverntories" class="propAllOutputsForm">
										<option value="">Please Select</option>
										<option value="calgary">Calgary</option>
										<option value="edmonton">Edmonton</option>
										<option value="toronto">Toronto</option>
										<option value="kelowna">Kelowna</option>
									</select>
								</div>
								<div id="return_type_selection_wrapper" class="propAllSmallFormInOut disFlex">
									<h4>Supplier/Customer</h4>
									<select id="return_invoice_select_supplier_customer" class="propAllOutputsForm" data-id="" required>

									</select>
								</div>
								<div id="labelsContainerReturn" class="labelRow disFlex" style="display: none;">
									<div class="label" style="margin-left: 68px;">Name</div>
									<div class="label margLeft" style="margin-left: 90px;">Quantity</div>
									<div class="label margLeft" style="margin-right: 40px;">Price</div>
								</div>
								<div class="for_fill_out_with_products">
									<!--<div class="propAbsForErase">x</div>
									<div id="name_of_prod" class="propListOrder"><span>Some Marijuana</span></div>
									<div id="kg_of_prod" class="margLeft propListOrder1"><span>50.500kg</span></div>
									<div id="price_of_prod" class="margLeft propListOrder1"><span>100.000$</span></div>-->
								</div>
								<div id="form_5_subb_2" class="propAllSubbOrFor">
									<div class="propAllSmallFormInOut disFlex">
										<h4>Quantity</h4>
										<input class="propAllOutputsForm" id="return_invoice_product_quantity_input" type="number" placeholder="Quantity" step="0.01">
									</div>
									<div class="propAllSmallFormInOut disFlex">
										<h4>Product</h4>
										<select id="return_invoice_products_selection" class="propAllOutputsForm">

										</select>
									</div>
									<div class="propAllSmallFormInOut disFlex">
										<h4>Price</h4>
										<input id="return_invoice_product_price_input" type="number" placeholder="Price" class="propAllOutputsForm">
									</div>
									<div id="add_product" class="propAddMore">Add</div>
								</div>
								<div id="return_total_price_weight_main">
									<div class="propAllSmallFormInOut disFlex">
										<h3>Total</h3>
										<input disabled id="return_invoice_price_total" step="0.01" type="number" placeholder="" class="propAllOutputPrWe">
									</div>

								</div>
								<input id="return_submit_for_order" class="propInputTypeSubmit" type="submit" value="Submit" name="">
							</div>
						</div>

						<br>
						<div id="returnProductMsg" class="phpMsg"></div>

					</form><!-- return_of_products_form -->

					<div id="set_inventories_wrapper" class="documentsRightSides">
						<h2>Set inventories</h2>
						<div>
							<select id="set_inventories_product_selection">
							</select>
						</div>
						<div>
							<div class="inlineB">
								<p>Calgary</p>
								<input id="calgaryInput" type="number" min="0" max="" step="0.01" value="">
							</div>
							<div class="inlineB">
								<p>Edmonton</p>
								<input id="edmontonInput" type="number" min="0" max="" step="0.01" value="">
							</div>
							<div class="inlineB">
								<p>Toronto</p>
								<input id="torontoInput" type="number" min="0" max="" step="0.01" value="">
							</div>
							<div class="inlineB">
								<p>Kelowna</p>
								<input id="kelownaInput" type="number" min="0" max="" step="0.01" value="">
							</div>
							<div class="inlineB">
								<p></p>
								<button id="set_button">Set</button>
							</div>
						</div>
						<br>
						<div id="setInventoriesMsg" class="phpMsg"></div>
					</div><!-- set_inventories_wrapper -->

				</div><!-- documents_right_side -->
			</div><!-- documents_wrapper -->
		</div><!-- documents_inner -->
	</div>

	<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js" type="text/javascript"></script>
	<script src="https://cdn.datatables.net/buttons/1.2.1/js/dataTables.buttons.min.js" type="text/javascript"></script>
	<script src="<?php getAssetLink('documents-panel-v*.js', 'js'); ?>"></script>
</body>

</html>