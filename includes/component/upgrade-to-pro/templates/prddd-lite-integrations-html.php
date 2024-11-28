<?php
$icon_info = plugins_url( 'product-delivery-date-for-woocommerce-lite/includes/component/upgrade-to-pro/assets/images/icon-info.png' );
?>

<div id="content" class="prddd-calendar-sync">
	<div class="prddd-lite-settings-inner-section">
		<div id="secondary-nav-wrap" class="ordd-content-area">
			<div class="container cw-full secondary-nav">
				<div class="row">
					<div class="col-md-12">
						<div class="secondary-nav-wrap">
							<ul>
								<li class="current-menu-item"><a href="#/" aria-current="page" class="router-link-exact-active router-link-active">Google Sync </a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
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
							Saving Changes...</div>
					</div>
					<div class="prdd_events_loader" style="display: none;">
						<div class="prdd_events_loader_wrapper">
							Loading...</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="prdd-page-head phw-btn">
								<div class="float-left prdd-container">
									<h1>Google Calendar Sync</h1>
									<p>Stay connected with your store by syncing it with the Google Calendar. Your customers can have their own fair share by adding the delivery info to their own GC.</p>
								</div>
								<div class="float-right">
									<button type="button">Save Settings</button>
								</div>
							</div>
							<div class="wbc-accordion">
								<div id="wbc-accordion" class="panel-group prdd-accordian">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h2 data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" class="panel-title">
												General Settings                                    </h2></div>
										<div id="collapseOne" class="panel-collapse collapse show">
											<div class="panel-body">
												<div class="tbl-mod-1">
													<div class="tm1-row flx-center">
														<div class="col-left">
															<label>Event Location:</label>
														</div>
														<div class="col-right">
															<div class="rc-flx-wrap flx-aln-center"><img src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Enter the text that will be used as location field in the calendar event. If left empty, website description is sent instead.
		Note: You can use ADDRESS, FULL_ADDRESS and CITY placeholders which will be replaced by their real values." class="tt-info">
																<input type="text" name="ts_calendar_event_location" id="ts_calendar_event_location" class="ib-xl">
															</div>
														</div>
													</div>
													<div class="tm1-row flx-center">
														<div class="col-left">
															<label>Event Summary(name):</label>
														</div>
														<div class="col-right">
															<div class="rc-flx-wrap flx-aln-center"><img src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="You can use the following placeholders which will be replaced by their real values: SITE_NAME, CLIENT, PRODUCTS, PRODUCT_WITH_QTY, ORDER_DATE_TIME, ORDER_DATE, ORDER_NUMBER, PRICE, PHONE, NOTE, ADDRESS, FULL_ADDRESS , EMAIL (Client's email)"
																class="tt-info">
																<input type="text" name="ts_calendar_event_summary" id="ts_calendar_event_summary" class="ib-xl">
															</div>
														</div>
													</div>
													<div class="tm1-row">
														<div class="col-left">
															<label>Event Description:</label>
														</div>
														<div class="col-right">
															<div class="rc-flx-wrap"><img src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="You can use the following placeholders which will be replaced by their real values: SITE_NAME, CLIENT, PRODUCTS, PRODUCT_WITH_QTY, ORDER_DATE_TIME, ORDER_DATE, ORDER_NUMBER, PRICE, PHONE, NOTE, ADDRESS, FULL_ADDRESS , EMAIL (Client's email)"
																class="tt-info aw-text">
																<textarea name="ts_calendar_event_description" id="ts_calendar_event_description" class="ta-sm"></textarea>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="panel panel-default">
										<div class="panel-heading">
											<h2 data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" class="panel-title">
												Customer Add to Calendar Button Settings                                    </h2></div>
										<div id="collapseTwo" class="panel-collapse collapse show">
											<div class="panel-body">
												<div class="tbl-mod-1">
													<div class="tm1-row">
														<div class="col-left">
															<label>Show Add to Calendar Button on Order Received Page:</label>
														</div>
														<div class="col-right">
															<div class="rc-flx-wrap flx-aln-center"><img src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Show Add to Calendar button on the Order Received page for the customers."
																class="tt-info">
																<label class="el-switch el-switch-green">
																	<input type="checkbox" name="prdd_add_to_calendar_order_received_page" id="prdd_add_to_calendar_order_received_page" true-value="on" false-value=""> <span class="el-switch-style"></span></label>
															</div>
														</div>
													</div>
													<div class="tm1-row">
														<div class="col-left">
															<label>Show Add to Calendar Button in The Customer Notification Email:</label>
														</div>
														<div class="col-right">
															<div class="rc-flx-wrap flx-aln-center"><img src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Show Add to Calendar button in the Customer notification email."
																class="tt-info">
																<label class="el-switch el-switch-green">
																	<input type="checkbox" name="prdd_add_to_calendar_customer_email" id="prdd_add_to_calendar_customer_email" true-value="on" false-value=""> <span class="el-switch-style"></span></label>
															</div>
														</div>
													</div>
													<div class="tm1-row">
														<div class="col-left">
															<label>Show Add to Calendar Button on My Account:</label>
														</div>
														<div class="col-right">
															<div class="rc-flx-wrap flx-aln-center"><img src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Show Add to Calendar button on My account page for the customers."
																class="tt-info">
																<label class="el-switch el-switch-green">
																	<input type="checkbox" name="prdd_add_to_calendar_my_account_page" id="prdd_add_to_calendar_my_account_page" true-value="on" false-value=""> <span class="el-switch-style"></span></label>
															</div>
														</div>
													</div>
													<div class="tm1-row">
														<div class="col-left">
															<label>Show Add To Calendar Button on Delivery Calendar Page:</label>
														</div>
														<div class="col-right">
															<div class="rc-flx-wrap flx-aln-center"><img src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Shows the 'Add to Calendar' button on the Order Received page. On clicking the button, an ICS file will be downloaded."
																class="tt-info">
																<label class="el-switch el-switch-green">
																	<input type="checkbox" name="prdd_admin_add_to_calendar_delivery_calendar" id="prdd_admin_add_to_calendar_delivery_calendar" true-value="on" false-value=""> <span class="el-switch-style"></span></label>
															</div>
														</div>
													</div>
													<div class="tm1-row">
														<div class="col-left">
															<label>Show Add To Calendar Button in New Order Email Notification:</label>
														</div>
														<div class="col-right">
															<div class="rc-flx-wrap flx-aln-center"><img src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Show 'Add to Calendar' button in the New Order email notification."
																class="tt-info">
																<label class="el-switch el-switch-green">
																	<input type="checkbox" name="prdd_admin_add_to_calendar_email_notification" id="prdd_admin_add_to_calendar_email_notification" true-value="on" false-value=""> <span class="el-switch-style"></span></label>
															</div>
														</div>
													</div>
													<div class="tm1-row">
														<div class="col-left">
															<label>Send Delivery Information As Attachments (ICS Files) In Email Notifications:</label>
														</div>
														<div class="col-right">
															<div class="rc-flx-wrap flx-aln-center"><img src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Allow customers to export deliveries as ICS file after placing an order. Sends ICS files as attachments in email notifications."
																class="tt-info">
																<label class="el-switch el-switch-green">
																	<input type="checkbox" name="prdd_attachment" id="prdd_attachment" true-value="on" false-value=""> <span class="el-switch-style"></span></label>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="panel panel-default">
										<div class="panel-heading">
											<h2 data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" class="panel-title">
												Admin Calendar Sync Settings                                    </h2></div>
										<div id="collapseThree" class="panel-collapse collapse show google-calendar-sync">
											<div class="panel-body">
												<div class="tbl-mod-1">
													<div class="tm1-row">
														<div class="col-left">
															<label>Integration Mode:</label>
														</div>
														<div class="col-right">
															<div class="rc-flx-wrap flx-aln-center ro-wrap"><img src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Select method of integration.
		'Sync Automatically' will add the events to the Google calendar, which is set in the 'Calendar to be used' field, automatically when a customer places an order.
		'Sync Manually' will add an 'Add to Calendar' button in emails received by admin on New customer order and on the Delivery Calendar page.
		'Disabled' will disable the integration with Google Calendar." class="tt-info">
																<div class="rb-flx-style">
																	<div class="el-radio el-radio-green">
																		<input type="radio" id="7_1" true-value="on" false-value="" value="directly">
																		<label for="7_1" class="el-radio-style"></label>
																	</div>
																	<label for="7_1">Sync Automatically</label>
																</div>
																<div class="rb-flx-style">
																	<div class="el-radio el-radio-green">
																		<input type="radio" id="7_2" true-value="on" false-value="" value="manually">
																		<label for="7_2" class="el-radio-style"></label>
																	</div>
																	<label for="7_2">Sync Manually</label>
																</div>
																<div class="rb-flx-style">
																	<div class="el-radio el-radio-green">
																		<input type="radio" id="7_3" true-value="on" false-value="" value="disabled">
																		<label for="7_3" class="el-radio-style"></label>
																	</div>
																	<label for="7_3">Disabled</label>
																</div>
															</div>
														</div>
													</div>
													<div class="rb_opt_val_3" style="display: none;">
														<div class="tm1-row">
															<div class="col-full">
																<label>Instructions:</label> To set up Google Calendar API, please click on "Show me how" link and carefully follow these steps: <a href="javascript:;" class="link-wul"> Show me how</a></div>
														</div>
														<div class="description ts-info_target api-instructions" style="display: none;">
															<ul style="list-style-type: decimal;">
																<li>Google Calendar API requires php V5.3+ and some php extensions. </li>
																<li>Go to Google APIs console by clicking <a href="https://code.google.com/apis/console/" target="_blank">https://code.google.com/apis/console/</a>. Login to your Google account if you are not already
																	logged in.</li>
																<li>Create a new project using the left side pane. Click on 'Home' option. Name the project "Deliveries" (or use your chosen name instead).</li>
																<li>Click on API Manager from left side pane.</li>
																<li>Click "Calendar API" under Google Apps APIs and Enable the API.</li>
																<li>Go to "Credentials" menu in the left side pane and click on "New Credentials" dropdown.</li>
																<li>Click on "OAuth client ID" option. Then click on Configure consent screen.</li>
																<li>Enter a Product Name, e.g. Product Delivery Date, inside the opening pop-up. Click Save.</li>
																<li>Select "Web Application" option, enter the Web client name and create the client ID.</li>
																<li>Click on New Credentials dropdown and select "Service account key".</li>
																<li>Click "Service account" and select "New service account" and enter the name. Now select key type as "P12" and create the service account.</li>
																<li>A file with extension .p12 will be downloaded.</li>
																<li>Using your FTP client program, copy this key file to folder: /home/rotiredasi1385/web/inventive-mosquito-2ccb96.instawp.xyz/public_html/wp-content/plugins/product-delivery-date/includes/gcal/key/
																	. This file is required as you will grant access to your Google Calendar account even if you are not online. So this file serves as a proof of your consent to access to your Google calendar account.
																	Note: This file cannot be uploaded in any other way. If you do not have FTP access, ask the website admin to do it for you.</li>
																<li>Enter the name of the key file to "Key file name" setting of Order Delivery Date. Exclude the extention .p12.</li>
																<li>Copy "Email address" setting from Manage service account of Google apis console and paste it to "Service account email address" setting of Product Delivery Date.</li>
																<li>Open your Google Calendar by clicking this link: <a href="https://www.google.com/calendar/render" target="_blank">https://www.google.com/calendar/render</a></li>
																<li>Create a new Calendar by selecting "my Calendars &gt; Create new calendar" on left side pane. <b>Try NOT to use your primary calendar.</b></li>
																<li>Give a name to the new calendar, e.g. Product Delivery Date calendar. <b>Check that Calendar Time Zone setting matches with time zone setting of your WordPress website.</b> Otherwise there will be
																	a time shift.</li>
																<li>Paste already copied "Email address" setting from Manage service account of Google apis console to "Person" field under "Share with specific person".</li>
																<li>Set "Permission Settings" of this person as "Make changes to events".</li>
																<li>Click "Add Person".</li>
																<li>Click "Create Calendar".</li>
																<li>Select the created calendar and click "Calendar settings".</li>
																<li>Copy "Calendar ID" value on Calendar Address row.</li>
																<li>Paste this value to "Calendar to be used" field.</li>
																<li>Click "Save Settings".</li>
																<li>After these stages, you have set up Google Calendar API. To test the connection, click the "Test Connection" link.</li>
																<li>If you get a success message, you should see a test event inserted to the Google Calendar and you are ready to go. If you get an error message, double check your settings.</li>
															</ul>
														</div>
														<div class="tm1-row flx-center">
															<div class="col-left">
																<label>Key File Name:</label>
															</div>
															<div class="col-right">
																<div class="rc-flx-wrap flx-aln-center"><img src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Enter key file name here without extention, e.g. ab12345678901234567890-privatekey."
																	class="tt-info">
																	<input type="text" name="ts_calendar_key_file_name" id="ts_calendar_key_file_name" class="ib-xl">
																</div>
															</div>
														</div>
														<div class="tm1-row flx-center">
															<div class="col-left">
																<label>Service Account Email Address:</label>
															</div>
															<div class="col-right">
																<div class="rc-flx-wrap flx-aln-center"><img src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Enter Service account email address here, e.g. 1234567890@developer.gserviceaccount.com."
																	class="tt-info">
																	<input type="text" name="ts_calendar_service_acc_email_address" id="ts_calendar_service_acc_email_address" class="ib-xl">
																</div>
															</div>
														</div>
														<div class="tm1-row">
															<div class="col-left">
																<label>Calendar to Be Used</label>
															</div>
															<div class="col-right">
																<div class="row-box-1">
																	<div class="rb1-left"><img src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Enter the ID of the calendar in which your deliveries will be saved, e.g. abcdefg1234567890@group.calendar.google.com."
																		class="tt-info"></div>
																	<div class="rb1-right">
																		<div class="rb1-row">
																			<input type="text" name="ts_calendar_id" id="ts_calendar_id" class="ib-xl">
																		</div>
																		
																		<div class="rb1-row"><span class="response_message_text error-msg"></span></div>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="rb_opt_val_3" style="display: none;">
														<div class="tm1-row">
															<div class="col-full">
																It will add an "Add to Calendar" button in emails received by admin on New customer order and on the Delivery Calendar page. </div>
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
	</div>
	<?php do_action( 'prdd_lite_after_settings_page_form' ); ?>
</div>