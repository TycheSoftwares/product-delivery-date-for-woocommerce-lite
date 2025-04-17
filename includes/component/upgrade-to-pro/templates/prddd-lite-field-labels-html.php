<?php
// phpcs:disable
$icon_info = plugins_url( 'product-delivery-date-for-woocommerce-lite/includes/component/upgrade-to-pro/assets/images/icon-info.png' );
?>

<div id="content" class="prddd-shipping-based">
	<div class="prddd-lite-settings-inner-section">
		<div class="prdd-content-area">
			<div class="container fas-page-wrap">
				<div id="save_message" class="container-fluid pl-info-wrap" style="display: none;">
					<div class="row">
						<div class="col-md-12">
							<div role="alert" class="alert alert-success alert-dismissible fade show">
								Settings Saved.
								<button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
							</div>
						</div>
					</div>
				</div>
				<div id="prdd_events_loader" style="display: none;">
					<div class="prdd_events_loader_wrapper">
						</div>
				</div>
				<div class="prdd_events_loader" style="display: none;">
					<div class="prdd_events_loader_wrapper">
						Loading...</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="prdd-page-head phw-btn">
							<div class="float-left">
								<h1>Field Labels</h1>
								<p>Set up the field labels of delivery fields the way you want it!</p>
							</div>
							<div class="float-right">
								<button type="button">Save Settings</button>
							</div>
						</div>
						<div class="wbc-accordion">
							<div id="wbc-accordion" class="panel-group prdd-accordian">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" class="panel-title">
												Labels on Product Page                                    </h4></div>
									<div id="collapseOne" class="panel-collapse collapse show">
										<div class="panel-body">
											<div class="tbl-mod-1">
												<div class="tm1-row">
													<div class="col-left">
														<label>Delivery Date:</label>
													</div>
													<div class="col-right">
														<div class="rc-flx-wrap flx-aln-center"><img src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Delivery Date label on product page."
															class="tt-info">
															<input type="text" name="delivery_date_label" id="delivery_date_label" class="ib-md">
														</div>
													</div>
												</div>
												<div class="tm1-row">
													<div class="col-left">
														<label>Delivery Time:</label>
													</div>
													<div class="col-right">
														<div class="rc-flx-wrap flx-aln-center"><img src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Delivery Time label on the product page."
															class="tt-info">
															<input type="text" name="delivery_time_label" id="delivery_time_label" class="ib-md">
														</div>
													</div>
												</div>
												<div class="tm1-row">
													<div class="col-left">
														<label>Choose Time Text:</label>
													</div>
													<div class="col-right">
														<div class="rc-flx-wrap flx-aln-center"><img src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Text for the 1st option of Time Slot dropdown field that instructs the customer to select a time slot."
															class="tt-info">
															<input type="text" name="delivery_time_select_option" id="delivery_time_select_option" class="ib-md">
														</div>
													</div>
												</div>
												<div class="tm1-row">
													<div class="col-left">
														<label>Delivery Date Placeholder:</label>
													</div>
													<div class="col-right">
														<div class="rc-flx-wrap flx-aln-center"><img src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Placeholder for delivery date field."
															class="tt-info">
															<input type="text" name="delivery_placeholder_option" id="delivery_placeholder_option" class="ib-md">
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" class="panel-title">
												Labels on Order Received page &amp; in email notification                                    </h4></div>
									<div id="collapseTwo" class="panel-collapse collapse show">
										<div class="panel-body">
											<div class="tbl-mod-1">
												<div class="tm1-row">
													<div class="col-left">
														<label>Delivery Date:</label>
													</div>
													<div class="col-right">
														<div class="rc-flx-wrap flx-aln-center"><img src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Delivery Date label on the order received page and email notification."
															class="tt-info">
															<input type="text" name="delivery_item_meta_date" id="delivery_item_meta_date" class="ib-md">
														</div>
													</div>
												</div>
												<div class="tm1-row">
													<div class="col-left">
														<label>Delivery Time:</label>
													</div>
													<div class="col-right">
														<div class="rc-flx-wrap flx-aln-center"><img src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Delivery Time label on the order received page and email notification."
															class="tt-info">
															<input type="text" name="delivery_item_meta_time" id="delivery_item_meta_time" class="ib-md">
														</div>
													</div>
												</div>
												<div class="tm1-row">
													<div class="col-left">
														<label>ICS File Name:</label>
													</div>
													<div class="col-right">
														<div class="rc-flx-wrap flx-aln-center"><img src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="ICS File name."
															class="tt-info">
															<input type="text" name="delivery_ics_file_name" id="delivery_ics_file_name" class="ib-md">
														</div>
													</div>
												</div>
												<div class="tm1-row">
													<div class="col-left">
														<label>Delivery Charges:</label>
													</div>
													<div class="col-right">
														<div class="rc-flx-wrap flx-aln-center"><img src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Delivery Charges label on the order received page and email notification."
															class="tt-info">
															<input type="text" name="delivery_item_meta_charges" id="delivery_item_meta_charges" class="ib-md">
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" class="panel-title">
												Labels on Cart &amp; Check-out Page                                    </h4></div>
									<div id="collapseThree" class="panel-collapse collapse show">
										<div class="panel-body">
											<div class="tbl-mod-1">
												<div class="tm1-row">
													<div class="col-left">
														<label>Delivery Date:</label>
													</div>
													<div class="col-right">
														<div class="rc-flx-wrap flx-aln-center"><img src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Delivery Date label on the cart and checkout page."
															class="tt-info">
															<input type="text" name="delivery_item_cart_date" id="delivery_item_cart_date" class="ib-md">
														</div>
													</div>
												</div>
												<div class="tm1-row">
													<div class="col-left">
														<label>Delivery Time:</label>
													</div>
													<div class="col-right">
														<div class="rc-flx-wrap flx-aln-center"><img src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Delivery Time label on the cart and checkout page."
															class="tt-info">
															<input type="text" name="delivery_item_cart_time" id="delivery_item_cart_time" class="ib-md">
														</div>
													</div>
												</div>
												<div class="tm1-row">
													<div class="col-left">
														<label>Delivery Charges:</label>
													</div>
													<div class="col-right">
														<div class="rc-flx-wrap flx-aln-center"><img src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Delivery Charges label on the cart and checkout page."
															class="tt-info">
															<input type="text" name="delivery_item_cart_charges" id="delivery_item_cart_charges" class="ib-md">
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" class="panel-title">
												Labels for Estimated Delivery Option                                    </h4></div>
									<div id="collapseFour" class="panel-collapse collapse show">
										<div class="panel-body">
											<div class="tbl-mod-1">
												<div class="tm1-row">
													<div class="col-left">
														<label>Estimated Delivery Section Heading:</label>
													</div>
													<div class="col-right">
														<div class="rc-flx-wrap flx-aln-center"><img src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Estimated Delivery Section Heading"
															class="tt-info">
															<input type="text" name="prdd_estimate_delivery_header" id="prdd_estimate_delivery_header" class="ib-md">
														</div>
													</div>
												</div>
												<div class="tm1-row">
													<div class="col-left">
														<label>Estimated Delivery Display In Days Text:</label>
													</div>
													<div class="col-right">
														<div class="rc-flx-wrap"><img class="tt-info" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Use {{business.days}} shortcode to replace it with the number of business days required for delivery">
															<textarea name="prdd_estimate_delivery_days_text" id="prdd_estimate_delivery_days_text" class="ta-sm"></textarea>
														</div>
													</div>
												</div>
												<div class="tm1-row">
													<div class="col-left">
														<label>Estimated Delivery Display with Specific Date Text:</label>
													</div>
													<div class="col-right">
														<div class="rc-flx-wrap"><img class="tt-info" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Use {{expected.date}} shortcode to replace it with the expected date of delivery">
															<textarea name="prdd_estimate_delivery_date_text" id="prdd_estimate_delivery_date_text" class="ta-sm"></textarea>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="ss-foot">
							<button type="button">Save Settings</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php do_action( 'prdd_lite_after_settings_page_form' ); ?>
</div>

