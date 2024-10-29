<?php

$icon_info = plugins_url( 'product-delivery-date-for-woocommerce-lite/includes/component/upgrade-to-pro/assets/images/icon-info.svg' );

?>

<div id="content" class="prddd-shipping-based">
	<div class="prddd-lite-settings-inner-section">
		<div id="prdd-bulk-settings-page" class="prdd-content-area">
			<div class="container-fluid bd-page-wrap as-page">
				<div class="row">
					<form id="bulk_product_form_settings" method="post" action="">
						<div class="col-md-12">
							<div class="prdd-page-head phw-btn justify-content-between">
								<div class="float-left prdd-container">
									<h1>Bulk Product Settings</h1>
									<p>Add delivery settings for multiple products together. Once the settings are saved, you will be able to see them &amp; edit them from the Edit product page of the products that you have selected.</p>
								</div>
								<div class="float-right">
									<button type="button" class="prdd-bulk-setting">Save Settings</button>
								</div>
							</div>
						</div>

						<div class="col-md-12">
							<div class="wbc-box">
								<div class="wbc-head d-flex justify-content-between align-items-center">
									<h4>Multiple Products Delivery Settings</h4>
								</div>
								<div class="wbc-content">
									<div class="tbl-mod-1">
										<div class="tm1-row">
											<div class="col-left">
												<label>Products:</label>
											</div>
											<div class="col-right">
												<div class="rc-flx-wrap">
													<img class="tt-info" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Please select product to apply the below settings to">
													<div class="bs-example invisible-field">
														<select id="prdd_products" name="prdd_products[]" style="width: 300px" class="ib-small chosen_select select2-hidden-accessible" placeholder="Select Products and/or Categories" multiple="" tabindex="-1" aria-hidden="true">
														</select><span class="select2 select2-container select2-container--default" dir="ltr" style="width: 300px;"><span class="selection"><span class="select2-selection select2-selection--multiple" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="-1"><ul class="select2-selection__rendered"><li class="select2-search select2-search--inline"><input class="select2-search__field" type="search" tabindex="0" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" role="textbox" aria-autocomplete="list" placeholder="" style="width: 0.75em;"></li></ul></span></span>
														<span
														class="dropdown-wrapper" aria-hidden="true"></span>
															</span>
													</div>
												</div>
											</div>
										</div>
										<div class="tbl-mod-1">
											<div class="tm1-row">
												<div class="col-left">
													<label>Products Categories And Tags:</label>
												</div>
												<div class="col-right">
													<div class="rc-flx-wrap">
														<img class="tt-info" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Please select product categories and tags to apply the below settings to">
														<div class="bs-example invisible-field">
															<select id="prdd_products_categories" name="prdd_products_categories[]" style="width: 300px" class="ib-small chosen_category select2-hidden-accessible" placeholder="Select Products and/or Categories" multiple="" tabindex="-1" aria-hidden="true">
															</select><span class="select2 select2-container select2-container--default" dir="ltr" style="width: 300px;"><span class="selection"><span class="select2-selection select2-selection--multiple" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="-1"><ul class="select2-selection__rendered"><li class="select2-search select2-search--inline"><input class="select2-search__field" type="search" tabindex="0" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" role="textbox" aria-autocomplete="list" placeholder="" style="width: 0.75em;"></li></ul></span></span>
															<span
															class="dropdown-wrapper" aria-hidden="true"></span>
																</span>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="tbl-mod-1">
											<div class="tm1-row">
												<div class="col-left">
													<label>Override Existing product Settings?</label>
												</div>
												<div class="col-right d-flex">
													<div class="rc-flx-wrap flx-aln-center">
														<img class="tt-info" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title=" Please select this checkbox if you want to replace the existing delivery settings with the new ones. If this checkbox is not selected, then it will retain the existing delivery settings on the products &amp; update the new ones in it.">
														<label class="el-switch el-switch-green">
															<input type="checkbox" id="prdd_bulk_override" name="prdd_bulk_override">
															<span class="el-switch-style"></span>
														</label>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div id="prdd-product-page" class="wbc-content  product-setting-section prdd-content-area">
									<div class="tbl-mod-1">
										<div class="tm1-row p-0">
											<div class="col-left">
												<div class="col-sidebar">
													<ul>
														<li>
															<a class="prdd_tabs active" href="javascript:;" onclick="prdd_open_tab( this, 'delivery-option');">
																<svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
																	<path fill-rule="evenodd" clip-rule="evenodd" d="M0.666667 6.66667H4.66667C5.03333 6.66667 5.33333 6.36667 5.33333 6V0.666667C5.33333 0.3 5.03333 0 4.66667 0H0.666667C0.3 0 0 0.3 0 0.666667V6C0 6.36667 0.3 6.66667 0.666667 6.66667ZM0.666667 12H4.66667C5.03333 12 5.33333 11.7 5.33333 11.3333V8.66667C5.33333 8.3 5.03333 8 4.66667 8H0.666667C0.3 8 0 8.3 0 8.66667V11.3333C0 11.7 0.3 12 0.666667 12ZM7.33333 12H11.3333C11.7 12 12 11.7 12 11.3333V6C12 5.63333 11.7 5.33333 11.3333 5.33333H7.33333C6.96667 5.33333 6.66667 5.63333 6.66667 6V11.3333C6.66667 11.7 6.96667 12 7.33333 12ZM6.66667 0.666667V3.33333C6.66667 3.7 6.96667 4 7.33333 4H11.3333C11.7 4 12 3.7 12 3.33333V0.666667C12 0.3 11.7 0 11.3333 0H7.33333C6.96667 0 6.66667 0.3 6.66667 0.666667Z"
																	fill="#A7ACB1"></path>
																</svg>
																Delivery Options <i class="dashicons dashicons-arrow-right-alt2"></i>
															</a>
														</li>
														<li>
															<a class="prdd_tabs" href="javascript:;" onclick="prdd_open_tab( this, 'settings');">
																<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
																	<path fill-rule="evenodd" clip-rule="evenodd" d="M11.9532 7.65398C11.9799 7.44065 11.9999 7.22732 11.9999 7.00065C11.9999 6.77398 11.9799 6.56065 11.9532 6.34732L13.3599 5.24732C13.4866 5.14732 13.5199 4.96732 13.4399 4.82065L12.1066 2.51398C12.0266 2.36732 11.8466 2.31398 11.6999 2.36732L10.0399 3.03398C9.69323 2.76732 9.31989 2.54732 8.91323 2.38065L8.65989 0.613984C8.63989 0.453984 8.49989 0.333984 8.33323 0.333984H5.66656C5.49989 0.333984 5.35989 0.453984 5.33989 0.613984L5.08656 2.38065C4.67989 2.54732 4.30656 2.77398 3.95989 3.03398L2.29989 2.36732C2.14656 2.30732 1.97323 2.36732 1.89323 2.51398L0.559893 4.82065C0.473226 4.96732 0.513226 5.14732 0.639893 5.24732L2.04656 6.34732C2.01989 6.56065 1.99989 6.78065 1.99989 7.00065C1.99989 7.22065 2.01989 7.44065 2.04656 7.65398L0.639893 8.75398C0.513226 8.85398 0.479893 9.03399 0.559893 9.18065L1.89323 11.4873C1.97323 11.634 2.15323 11.6873 2.29989 11.634L3.95989 10.9673C4.30656 11.234 4.67989 11.454 5.08656 11.6207L5.33989 13.3873C5.35989 13.5473 5.49989 13.6673 5.66656 13.6673H8.33323C8.49989 13.6673 8.63989 13.5473 8.65989 13.3873L8.91323 11.6207C9.31989 11.454 9.69323 11.2273 10.0399 10.9673L11.6999 11.634C11.8532 11.694 12.0266 11.634 12.1066 11.4873L13.4399 9.18065C13.5199 9.03399 13.4866 8.85398 13.3599 8.75398L11.9532 7.65398ZM6.99989 9.33398C5.71323 9.33398 4.66656 8.28732 4.66656 7.00065C4.66656 5.71398 5.71323 4.66732 6.99989 4.66732C8.28656 4.66732 9.33323 5.71398 9.33323 7.00065C9.33323 8.28732 8.28656 9.33398 6.99989 9.33398Z"
																	fill="#A7ACB1"></path>
																</svg>
																Settings <i class="dashicons dashicons-arrow-down-alt2"></i>
															</a>
														</li>
														<li>
															<a class="prdd_tabs" href="javascript:;" onclick="prdd_open_tab( this, 'time-period');">
																<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
																	<path fill-rule="evenodd" clip-rule="evenodd" d="M7.00065 0.333984C3.33398 0.333984 0.333984 3.33398 0.333984 7.00065C0.333984 10.6673 3.33398 13.6673 7.00065 13.6673C10.6673 13.6673 13.6673 10.6673 13.6673 7.00065C13.6673 3.33398 10.6673 0.333984 7.00065 0.333984ZM9.36732 9.53398L6.64732 7.86065C6.44732 7.74065 6.32732 7.52732 6.32732 7.29398V4.16732C6.33398 3.89398 6.56065 3.66732 6.83398 3.66732C7.10732 3.66732 7.33398 3.89398 7.33398 4.16732V7.13398L9.89398 8.67398C10.134 8.82065 10.214 9.13398 10.0673 9.37398C9.92065 9.60732 9.60732 9.68065 9.36732 9.53398Z"
																	fill="#A7ACB1"></path>
																</svg>


																Delivery Time Period <i class="dashicons dashicons-arrow-down-alt2"></i>
															</a>
														</li>
														<li>
															<a class="prdd_tabs" href="javascript:;" onclick="prdd_open_tab( this, 'delivery-charges');">
																<svg width="14" height="12" viewBox="0 0 14 12" fill="none" xmlns="http://www.w3.org/2000/svg">
																	<path fill-rule="evenodd" clip-rule="evenodd" d="M12.334 0.666016H1.66732C0.927318 0.666016 0.340651 1.25935 0.340651 1.99935L0.333984 9.99935C0.333984 10.7393 0.927318 11.3327 1.66732 11.3327H12.334C13.074 11.3327 13.6673 10.7393 13.6673 9.99935V1.99935C13.6673 1.25935 13.074 0.666016 12.334 0.666016ZM11.6673 9.99935H2.33398C1.96732 9.99935 1.66732 9.69935 1.66732 9.33268V5.99935H12.334V9.33268C12.334 9.69935 12.034 9.99935 11.6673 9.99935ZM12.334 3.33268H1.66732V1.99935H12.334V3.33268Z"
																	fill="#A7ACB1"></path>
																</svg>

																Delivery Charges <i class="dashicons dashicons-arrow-down-alt2"></i>
															</a>
														</li>
													</ul>
												</div>
											</div>
											<div class="col-right">
												<div class="tbl-mod-1">
													<div class="prdd-tab-content tab-delivery-option">
														<div class="d-flex justify-content-between">
															<h4>Enable Delivery Date</h4>
														</div>
														<div class="tm1-row align-items-center d-b-color">
															<div class="col-left">
																<label>Enable Delivery Date:</label>
															</div>
															<div class="col-right">
																<div class="rc-flx-wrap flx-aln-center">
																	<img class="tt-info" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Enable the ability to capture delivery date on the Single Product Page.">
																	<label class="el-switch el-switch-green">
																		<input type="checkbox" id="prdd_enable_date" name="prdd_enable_date">
																		<span class="el-switch-style"></span>
																	</label>
																</div>
															</div>
														</div>
														<div class="tm1-row">
															<div class="col-left">
																<label>Delivery Option:</label>
															</div>
															<div class="col-right">
																<div class="rc-flx-wrap flx-aln-center ro-wrap">
																	<img class="tt-info" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="The 'Calendar' option displays a datepicker at the front end, whereas the 'Text Block' option displays an estimated time period of delivery based on the setting">
																	<div class="rb-flx-style">
																		<div class="el-radio el-radio-green">
																			<input type="radio" name="prdd_delivery_options" id="prdd_delivery_options_delivery_calendar" value="delivery_calendar" checked="">
																			<label class="el-radio-style" for="prdd_delivery_options_delivery_calendar"></label>
																		</div>
																		<label for="prdd_delivery_options_delivery_calendar">Calendar</label>
																	</div>
																	<div class="rb-flx-style">
																		<div class="el-radio el-radio-green">
																			<input type="radio" name="prdd_delivery_options" id="prdd_delivery_options_text_block" value="text_block">
																			<label class="el-radio-style" for="prdd_delivery_options_text_block"></label>
																		</div>
																		<label for="prdd_delivery_options_text_block">Text Block</label>
																	</div>
																</div>
															</div>
														</div>
														<div class="text_box_sections" style="display:none;">
															<div class="tm1-row">
																<div class="col-left">
																	<label>Minimum Delivery Days required:</label>
																</div>
																<div class="col-right">
																	<div class="rc-flx-wrap flx-aln-center ro-wrap">
																		<img class="tt-info" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Minimum number of days required to deliver an order after submitting it.">
																		<input class="ib-lg" type="text" name="prdd_estimate_days" id="prdd_estimate_days" value="3">
																	</div>
																</div>
															</div>
														</div>
														<div class="text_box_sections" style="display:none;">
															<div class="tm1-row">
																<div class="col-left">
																	<label>Display Delivery details:</label>
																</div>
																<div class="col-right">
																	<div class="rc-flx-wrap flx-aln-center ro-wrap">
																		<img class="tt-info" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Display estimated delivery details based on the number of business days or an estimated delivery date.">
																		<div class="rb-flx-style">
																			<div class="el-radio el-radio-green">
																				<input type="radio" name="prdd_estimate_delivery_display" id="prdd_estimate_business_delivery_display" value="days" checked="">
																				<label class="el-radio-style" for="prdd_estimate_business_delivery_display"></label>
																			</div>
																			<label for="prdd_estimate_business_delivery_display">Business Days</label>
																		</div>
																		<div class="rb-flx-style">
																			<div class="el-radio el-radio-green">
																				<input type="radio" name="prdd_estimate_delivery_display" id="prdd_estimate_specific_delivery_display" value="specific_date">
																				<label class="el-radio-style" for="prdd_estimate_specific_delivery_display"></label>
																			</div>
																			<label for="prdd_estimate_specific_delivery_display">Specific Date</label>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="tm1-row delivery_calendar_sections">
															<div class="col-left">
																<label>Delivery On:</label>
															</div>
															<div class="col-right">
																<div class="rc-flx-wrap flx-aln-center ro-wrap">
																	<img class="tt-info" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Please enable/disable the specific delivery dates and recurring weekdays using these checkboxes. Upon checking them, you shall be able to further select dates or weekdays.">
																	<div class="custom-control custom-checkbox mr-3">
																		<input type="checkbox" class="custom-control-input" name="prdd_specific_chk" id="prdd_specific_chk" onclick="prdd_delivery_method(this)">
																		<label class="custom-control-label" for="prdd_specific_chk">Specific Dates</label>
																	</div>
																	<div class="custom-control custom-checkbox">
																		<input type="checkbox" class="custom-control-input" name="prdd_recurring_chk" id="prdd_recurring_chk" onclick="prdd_delivery_method(this)">
																		<label class="custom-control-label" for="prdd_recurring_chk">Recurring Weekdays</label>
																	</div>
																</div>
															</div>
														</div>
														<div class="delivery_calendar_sections" id="prdd_enable_weekday" style="display: none;">
															<div class="tm1-row">
																<div class="col-left">
																	<label>Delivery Days:</label>
																</div>
																<div class="col-right">
																	<div class="rc-flx-wrap ro-wrap">
																		<img class="tt-info" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Select recurring weekdays for delivery">
																		<div class="choices" data-type="select-multiple" role="combobox" aria-autocomplete="list" aria-haspopup="true" aria-expanded="false" dir="ltr">
																			<div class="choices__inner">
																				<select id="prdd_weekdays" class="prdd_weekdays prdd-multiple ib-small choices__input is-hidden" name="prdd_weekdays[]" placeholder="Select Weekdays" multiple="" tabindex="-1" aria-hidden="true"
																				data-choice="active"></select>
																				<div class="choices__list choices__list--multiple"></div>
																				<input type="text" class="choices__input choices__input--cloned" autocomplete="off" autocapitalize="off" spellcheck="false" role="textbox" aria-autocomplete="list" placeholder="Select Weekdays"
																				style="width: 108px;">
																			</div>
																			<div class="choices__list choices__list--dropdown" aria-expanded="false">
																				<div class="choices__list" dir="ltr" role="listbox" aria-multiselectable="true">
																					<div class="choices__item choices__item--choice choices__item--selectable is-highlighted" data-select-text="Press to select" data-choice="" data-id="1" data-value="prdd_weekday_0"
																					data-choice-selectable="" id="choices--prdd_weekdays-item-choice-1" role="option" aria-selected="true">
																						Sunday
																					</div>
																					<div class="choices__item choices__item--choice choices__item--selectable" data-select-text="Press to select" data-choice="" data-id="2" data-value="prdd_weekday_1" data-choice-selectable=""
																					id="choices--prdd_weekdays-item-choice-2" role="option">
																						Monday
																					</div>
																					<div class="choices__item choices__item--choice choices__item--selectable" data-select-text="Press to select" data-choice="" data-id="3" data-value="prdd_weekday_2" data-choice-selectable=""
																					id="choices--prdd_weekdays-item-choice-3" role="option">
																						Tuesday
																					</div>
																					<div class="choices__item choices__item--choice choices__item--selectable" data-select-text="Press to select" data-choice="" data-id="4" data-value="prdd_weekday_3" data-choice-selectable=""
																					id="choices--prdd_weekdays-item-choice-4" role="option">
																						Wednesday
																					</div>
																					<div class="choices__item choices__item--choice choices__item--selectable" data-select-text="Press to select" data-choice="" data-id="5" data-value="prdd_weekday_4" data-choice-selectable=""
																					id="choices--prdd_weekdays-item-choice-5" role="option">
																						Thursday
																					</div>
																					<div class="choices__item choices__item--choice choices__item--selectable" data-select-text="Press to select" data-choice="" data-id="6" data-value="prdd_weekday_5" data-choice-selectable=""
																					id="choices--prdd_weekdays-item-choice-6" role="option">
																						Friday
																					</div>
																					<div class="choices__item choices__item--choice choices__item--selectable" data-select-text="Press to select" data-choice="" data-id="7" data-value="prdd_weekday_6" data-choice-selectable=""
																					id="choices--prdd_weekdays-item-choice-7" role="option">
																						Saturday
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="delivery_calendar_sections" id="selective_delivery" style="display: none;">
															<div class="tm1-row align-items-center">
																<div class="col-left">
																	<label>Specific Date Delivery:</label>
																</div>
																<div class="col-right">
																	<img class="tt-info" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Select the specific dates that you want to enable for delivery.
		Specific dates can be managed from the &quot;Manage Dates, Time Slots&quot; tab on the left.">
																	<input class="ib-lg hasDatepicker" type="text" name="prdd_specific_date" id="prdd_specific_date">
																</div>
															</div>
														</div>
														<div class="tm1-row align-items-center delivery_calendar_sections">
															<div class="col-left">
																<label>Maximum deliveries per day:</label>
															</div>
															<div class="col-right">
																<div class="rc-flx-wrap flx-aln-center ro-wrap">
																	<img class="tt-info" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Set this field if you want to place a limit on maximum deliveries on any given date. If you can manage up to 15 deliveries in a day, set this value to 15. Once 15 orders have been placed, then that date will not be available for further deliveries. Setting it to blanks or 0 will ensure unlimited deliveries.">
																	<input class="ib-lg" type="text" id="prdd_lockout_date" name="prdd_lockout_date" value="0">
																</div>
															</div>
														</div>
														<div class="tm1-row delivery_calendar_sections">
															<div class="col-left">
																<label>Ask for Delivery Time:</label>
															</div>
															<div class="col-right">
																<div class="rc-flx-wrap flx-aln-center ro-wrap">
																	<img class="tt-info" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Enable time (or time slots) on the product. Add any number of delivery time slots once you have checked this. You can manage the Time Slots using the Manage Dates, Time Slots tab">
																	<label class="el-switch el-switch-green">
																		<input type="checkbox" name="prdd_enable_time" id="prdd_enable_time" onclick="prdd_timeslot(this)">
																		<span class="el-switch-style"></span>
																	</label>
																</div>
															</div>
														</div>
														<div id="time_slot_empty" style="display:none;">
															<div class="tm1-row">
																<div class="col-left">
																	<label>Enter a Time Slot:</label>
																</div>
																<div class="col-right">
																	<table class="prdd-form-table transparent-table">
																		<tbody>
																			<tr>
																				<td>
																					<table>
																						<tbody>
																							<tr class="mb-1">
																								<td>
																									From: </td>
																								<td>
																									<select name="prdd_from_slot_hrs[0]" id="prdd_from_slot_hrs[0]">
																										<option value="">Hours</option>
																										<option value="0">0</option>
																										<option value="1">1</option>
																										<option value="2">2</option>
																										<option value="3">3</option>
																										<option value="4">4</option>
																										<option value="5">5</option>
																										<option value="6">6</option>
																										<option value="7">7</option>
																										<option value="8">8</option>
																										<option value="9">9</option>
																										<option value="10">10</option>
																										<option value="11">11</option>
																										<option value="12">12</option>
																										<option value="13">13</option>
																										<option value="14">14</option>
																										<option value="15">15</option>
																										<option value="16">16</option>
																										<option value="17">17</option>
																										<option value="18">18</option>
																										<option value="19">19</option>
																										<option value="20">20</option>
																										<option value="21">21</option>
																										<option value="22">22</option>
																										<option value="23">23</option>
																									</select>
																									<select name="prdd_from_slot_min[0]" id="prdd_from_slot_min[0]">
																										<option value="00">Minutes</option>
																										<option value="00">00</option>
																										<option value="01">01</option>
																										<option value="02">02</option>
																										<option value="03">03</option>
																										<option value="04">04</option>
																										<option value="05">05</option>
																										<option value="06">06</option>
																										<option value="07">07</option>
																										<option value="08">08</option>
																										<option value="09">09</option>
																										<option value="10">10</option>
																										<option value="11">11</option>
																										<option value="12">12</option>
																										<option value="13">13</option>
																										<option value="14">14</option>
																										<option value="15">15</option>
																										<option value="16">16</option>
																										<option value="17">17</option>
																										<option value="18">18</option>
																										<option value="19">19</option>
																										<option value="20">20</option>
																										<option value="21">21</option>
																										<option value="22">22</option>
																										<option value="23">23</option>
																										<option value="24">24</option>
																										<option value="25">25</option>
																										<option value="26">26</option>
																										<option value="27">27</option>
																										<option value="28">28</option>
																										<option value="29">29</option>
																										<option value="30">30</option>
																										<option value="31">31</option>
																										<option value="32">32</option>
																										<option value="33">33</option>
																										<option value="34">34</option>
																										<option value="35">35</option>
																										<option value="36">36</option>
																										<option value="37">37</option>
																										<option value="38">38</option>
																										<option value="39">39</option>
																										<option value="40">40</option>
																										<option value="41">41</option>
																										<option value="42">42</option>
																										<option value="43">43</option>
																										<option value="44">44</option>
																										<option value="45">45</option>
																										<option value="46">46</option>
																										<option value="47">47</option>
																										<option value="48">48</option>
																										<option value="49">49</option>
																										<option value="50">50</option>
																										<option value="51">51</option>
																										<option value="52">52</option>
																										<option value="53">53</option>
																										<option value="54">54</option>
																										<option value="55">55</option>
																										<option value="56">56</option>
																										<option value="57">57</option>
																										<option value="58">58</option>
																										<option value="59">59</option>
																									</select>
																								</td>
																							</tr>
																							<tr>
																								<td>
																									To: </td>
																								<td>
																									<select name="prdd_to_slot_hrs[0]" id="prdd_to_slot_hrs[0]">
																										<option value="0">Hours</option>
																										<option value="0">0</option>
																										<option value="1">1</option>
																										<option value="2">2</option>
																										<option value="3">3</option>
																										<option value="4">4</option>
																										<option value="5">5</option>
																										<option value="6">6</option>
																										<option value="7">7</option>
																										<option value="8">8</option>
																										<option value="9">9</option>
																										<option value="10">10</option>
																										<option value="11">11</option>
																										<option value="12">12</option>
																										<option value="13">13</option>
																										<option value="14">14</option>
																										<option value="15">15</option>
																										<option value="16">16</option>
																										<option value="17">17</option>
																										<option value="18">18</option>
																										<option value="19">19</option>
																										<option value="20">20</option>
																										<option value="21">21</option>
																										<option value="22">22</option>
																										<option value="23">23</option>
																									</select>
																									<select name="prdd_to_slot_min[0]" id="prdd_to_slot_min[0]">
																										<option value="00">Minutes</option>
																										<option value="00">00</option>
																										<option value="01">01</option>
																										<option value="02">02</option>
																										<option value="03">03</option>
																										<option value="04">04</option>
																										<option value="05">05</option>
																										<option value="06">06</option>
																										<option value="07">07</option>
																										<option value="08">08</option>
																										<option value="09">09</option>
																										<option value="10">10</option>
																										<option value="11">11</option>
																										<option value="12">12</option>
																										<option value="13">13</option>
																										<option value="14">14</option>
																										<option value="15">15</option>
																										<option value="16">16</option>
																										<option value="17">17</option>
																										<option value="18">18</option>
																										<option value="19">19</option>
																										<option value="20">20</option>
																										<option value="21">21</option>
																										<option value="22">22</option>
																										<option value="23">23</option>
																										<option value="24">24</option>
																										<option value="25">25</option>
																										<option value="26">26</option>
																										<option value="27">27</option>
																										<option value="28">28</option>
																										<option value="29">29</option>
																										<option value="30">30</option>
																										<option value="31">31</option>
																										<option value="32">32</option>
																										<option value="33">33</option>
																										<option value="34">34</option>
																										<option value="35">35</option>
																										<option value="36">36</option>
																										<option value="37">37</option>
																										<option value="38">38</option>
																										<option value="39">39</option>
																										<option value="40">40</option>
																										<option value="41">41</option>
																										<option value="42">42</option>
																										<option value="43">43</option>
																										<option value="44">44</option>
																										<option value="45">45</option>
																										<option value="46">46</option>
																										<option value="47">47</option>
																										<option value="48">48</option>
																										<option value="49">49</option>
																										<option value="50">50</option>
																										<option value="51">51</option>
																										<option value="52">52</option>
																										<option value="53">53</option>
																										<option value="54">54</option>
																										<option value="55">55</option>
																										<option value="56">56</option>
																										<option value="57">57</option>
																										<option value="58">58</option>
																										<option value="59">59</option>
																									</select>
																								</td>
																							</tr>
																						</tbody>
																					</table>
																				</td>
																			</tr>
																		</tbody>
																	</table>
																</div>
															</div>
															<div class="tm1-row">
																<div class="col-left">
																	<label> Max deliveries per time slot:</label>
																</div>
																<div class="col-right">
																	<img class="tt-info" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Please enter a number to limit the number of deliveries for this time slot. Setting it to blanks or 0 will ensure unlimited deliveries.">
																	<input type="text" style="width:50px;" name="prdd_lockout_time[0]" id="prdd_lockout_time[0]" value="0">
																	<input type="hidden" id="wapbk_slot_count" name="wapbk_slot_count" value="[0]">
																</div>
															</div>
															<div class="tm1-row">
																<div class="col-left">
																	<label>Delivery charge added for the time slot:</label>
																</div>
																<div class="col-right">
																	<img class="tt-info" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Any additional delivery charges for the time slot can be added here.">
																	<input type="text" style="width:100px;" name="prdd_price_time[0]" id="prdd_price_time[0]" value="">
																	<input type="hidden" id="prdd_slot_count" name="prdd_slot_count" value="[0]">
																</div>
															</div>
														</div>
														<div id="time_slot" name="time_slot" class="delivery_calendar_sections"></div>
														<div class="mt-4 delivery_calendar_sections" id="add_button" name="add_button">
															<input type="button" class="trietary-btn reverse" value="Add Time Slot" id="add_another" onclick="prdd_add_new_div('[0]')" disabled="">
														</div>
													</div>
													<div class="prdd-tab-content tab-settings" style="display: none;">
														<div class="d-flex justify-content-between">
															<h4>Settings</h4>
														</div>
														<div class="tm1-row align-items-center d-b-color">
															<div class="col-left">
																<label>Show calendar always visible:</label>
															</div>
															<div class="col-right">
																<div class="rc-flx-wrap flx-aln-center">
																	<img class="tt-info" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Enable Inline Calendar on Products Page.">
																	<label class="el-switch el-switch-green">
																		<input type="checkbox" id="prdd_enable_inline_calendar" name="prdd_enable_inline_calendar">
																		<span class="el-switch-style"></span>
																	</label>
																</div>
															</div>
														</div>
														<div class="tm1-row align-items-center">
															<div class="col-left">
																<label>Mandatory Fields:</label>
															</div>
															<div class="col-right">
																<div class="rc-flx-wrap flx-aln-center">
																	<img class="tt-info" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Make the selection of a delivery date mandatory on the Product page">
																	<label class="el-switch el-switch-green">
																		<input type="checkbox" id="prdd_delivery_field_mandatory" name="prdd_delivery_field_mandatory" checked="">
																		<span class="el-switch-style"></span>
																	</label>
																</div>
															</div>
														</div>
														<div class="tm1-row align-items-center">
															<div class="col-left">
																<label>Hide Add to Cart button:</label>
															</div>
															<div class="col-right">
																<div class="rc-flx-wrap flx-aln-center">
																	<img class="tt-info" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Hide Add to Cart button on front end product details page until a delivery date and/or time slot is selected.">
																	<label class="el-switch el-switch-green">
																		<input type="checkbox" id="prdd_hide_add_to_cart_button" name="prdd_hide_add_to_cart_button">
																		<span class="el-switch-style"></span>
																	</label>
																</div>
															</div>
														</div>
														<div class="tm1-row align-items-center">
															<div class="col-left">
																<label>Delivery date label:</label>
															</div>
															<div class="col-right">
																<div class="rc-flx-wrap flx-aln-center">
																	<img class="tt-info" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Custom label for delivery date field for this product.">
																	<input class="ib-lg" type="text" id="prdd_deliery_date_label" name="prdd_deliery_date_label" value="">
																</div>
															</div>
														</div>
														<div class="tm1-row align-items-center">
															<div class="col-left">
																<label>Time slot label:</label>
															</div>
															<div class="col-right">
																<div class="rc-flx-wrap flx-aln-center">
																	<img class="tt-info" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Custom label for delivery time field for this product.">
																	<input class="ib-lg" type="text" id="prdd_time_date_label" name="prdd_time_date_label" value="">
																</div>
															</div>
														</div>
														<div class="tm1-row">
															<div class="col-left">
																<label>No delivery on these dates:</label>
															</div>
															<div class="col-right">
																<div class="rc-flx-wrap">
																	<img class="tt-info" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Select dates for which the delivery will be completely disabled only for this product. Please click on the date in calendar to add or delete the date from the holiday list.">
																	<input type="text" class="ib-lg hasDatepicker" name="prdd_product_holiday" id="prdd_product_holiday" value="">
																</div>
															</div>
														</div>
														<div class="tm1-row">
															<div class="col-left">
																<label>Hide Delivery date picker &amp; time slots on Shop/archives page:</label>
															</div>
															<div class="col-right">
																<div class="rc-flx-wrap flx-aln-center">
																	<img class="tt-info" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="If you don't want to show delivery date &amp; time only for this product on Shop/archives page, then please check this setting. The delivery date &amp; time fields will continue to show for other products">
																	<label class="el-switch el-switch-green">
																		<input type="checkbox" id="prdd_hide_delivery_fields_on_shop" name="prdd_hide_delivery_fields_on_shop">
																		<span class="el-switch-style"></span>
																		<p>Works only for Simple products.</p>
																	</label>
																</div>
															</div>
														</div>
														<div class="tm1-row align-items-center">
															<div class="col-left">
																<label>Default date:</label>
															</div>
															<div class="col-right">
																<div class="rc-flx-wrap flx-aln-center">
																	<img class="tt-info" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Default date to add in the order meta data even if delivery is disabled for this product.">
																	<input class="ib-lg datepicker hasDatepicker" type="text" name="prdd_default_date_for_delivery_disabled_product" id="prdd_default_date_for_delivery_disabled_product" value="">
																</div>
															</div>
														</div>
													</div>
													<div class="prdd-tab-content tab-time-period" style="display: none;">
														<div class="d-flex justify-content-between">
															<h4>Delivery Time Period</h4>
														</div>
														<div class="tm1-row align-items-center d-b-color">
															<div class="col-left">
																<label>Minimum Delivery preparation time (in hours):</label>
															</div>
															<div class="col-right">
																<div class="rc-flx-wrap flx-aln-center">
																	<img class="tt-info" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Enable Delivery after X number of hours from current time. The customer can select a delivery date that is available only after the minimum hours that are entered here. For example, if you need 1 day advance notice for a delivery, enter 24 here.">
																	<input class="ib-lg" type="text" id="prdd_minimum_number_days" name="prdd_minimum_number_days" value="0">
																</div>
															</div>
														</div>
														<div class="tm1-row align-items-center">
															<div class="col-left"></div>
															<div class="col-right">
																<div class="rc-flx-wrap flx-aln-center">
																	OR </div>
															</div>
														</div>
														<div class="tm1-row">
															<div class="col-left">
																<label>Same day delivery cut-off time:</label>
															</div>
															<div class="col-right">
																<div class="rc-flx-wrap">
																	<img class="tt-info" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Enable Delivery after X number of hours from current time. The customer can select a delivery date that is available only after the minimum hours that are entered here. For example, if you need 1 day advance notice for a delivery, enter 24 here.">
																	<div>
																		<table class="prdd-form-table transparent-table">
																			<tbody>
																				<tr>
																					<td>
																						<table>
																							<tbody>
																								<tr class="mb-1">
																									<td>
																										<select name="prdd_same_day_cutoff_hrs" id="prdd_same_day_cutoff_hrs">
																											<option value="">Hours</option>
																											<option value="0">0</option>
																											<option value="1">1</option>
																											<option value="2">2</option>
																											<option value="3">3</option>
																											<option value="4">4</option>
																											<option value="5">5</option>
																											<option value="6">6</option>
																											<option value="7">7</option>
																											<option value="8">8</option>
																											<option value="9">9</option>
																											<option value="10">10</option>
																											<option value="11">11</option>
																											<option value="12">12</option>
																											<option value="13">13</option>
																											<option value="14">14</option>
																											<option value="15">15</option>
																											<option value="16">16</option>
																											<option value="17">17</option>
																											<option value="18">18</option>
																											<option value="19">19</option>
																											<option value="20">20</option>
																											<option value="21">21</option>
																											<option value="22">22</option>
																											<option value="23">23</option>
																										</select>
																										<select name="prdd_same_day_cutoff_mins" id="prdd_same_day_cutoff_mins">
																											<option value="">Minutes</option>
																											<option value="00">00</option>
																											<option value="01">01</option>
																											<option value="02">02</option>
																											<option value="03">03</option>
																											<option value="04">04</option>
																											<option value="05">05</option>
																											<option value="06">06</option>
																											<option value="07">07</option>
																											<option value="08">08</option>
																											<option value="09">09</option>
																											<option value="10">10</option>
																											<option value="11">11</option>
																											<option value="12">12</option>
																											<option value="13">13</option>
																											<option value="14">14</option>
																											<option value="15">15</option>
																											<option value="16">16</option>
																											<option value="17">17</option>
																											<option value="18">18</option>
																											<option value="19">19</option>
																											<option value="20">20</option>
																											<option value="21">21</option>
																											<option value="22">22</option>
																											<option value="23">23</option>
																											<option value="24">24</option>
																											<option value="25">25</option>
																											<option value="26">26</option>
																											<option value="27">27</option>
																											<option value="28">28</option>
																											<option value="29">29</option>
																											<option value="30">30</option>
																											<option value="31">31</option>
																											<option value="32">32</option>
																											<option value="33">33</option>
																											<option value="34">34</option>
																											<option value="35">35</option>
																											<option value="36">36</option>
																											<option value="37">37</option>
																											<option value="38">38</option>
																											<option value="39">39</option>
																											<option value="40">40</option>
																											<option value="41">41</option>
																											<option value="42">42</option>
																											<option value="43">43</option>
																											<option value="44">44</option>
																											<option value="45">45</option>
																											<option value="46">46</option>
																											<option value="47">47</option>
																											<option value="48">48</option>
																											<option value="49">49</option>
																											<option value="50">50</option>
																											<option value="51">51</option>
																											<option value="52">52</option>
																											<option value="53">53</option>
																											<option value="54">54</option>
																											<option value="55">55</option>
																											<option value="56">56</option>
																											<option value="57">57</option>
																											<option value="58">58</option>
																											<option value="59">59</option>
																										</select>
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</div>
																</div>
															</div>
														</div>
														<div class="tm1-row">
															<div class="col-left">
																<label>Same day delivery cut-off time for free delivery:</label>
															</div>
															<div class="col-right">
																<div class="rc-flx-wrap">
																	<img class="tt-info" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Allow free delivery on the same day if the order is placed before the set time.">
																	<div>
																		<table class="prdd-form-table transparent-table">
																			<tbody>
																				<tr>
																					<td>
																						<table>
																							<tbody>
																								<tr class="mb-1">
																									<td>
																										<select name="prdd_same_day_cutoff_hrs_free" id="prdd_same_day_cutoff_hrs_free">
																											<option value="">Hours</option>
																											<option value="0">0</option>
																											<option value="1">1</option>
																											<option value="2">2</option>
																											<option value="3">3</option>
																											<option value="4">4</option>
																											<option value="5">5</option>
																											<option value="6">6</option>
																											<option value="7">7</option>
																											<option value="8">8</option>
																											<option value="9">9</option>
																											<option value="10">10</option>
																											<option value="11">11</option>
																											<option value="12">12</option>
																											<option value="13">13</option>
																											<option value="14">14</option>
																											<option value="15">15</option>
																											<option value="16">16</option>
																											<option value="17">17</option>
																											<option value="18">18</option>
																											<option value="19">19</option>
																											<option value="20">20</option>
																											<option value="21">21</option>
																											<option value="22">22</option>
																											<option value="23">23</option>
																										</select>
																										<select name="prdd_same_day_cutoff_mins_free" id="prdd_same_day_cutoff_mins_free">
																											<option value="">Minutes</option>
																											<option value="00">00</option>
																											<option value="01">01</option>
																											<option value="02">02</option>
																											<option value="03">03</option>
																											<option value="04">04</option>
																											<option value="05">05</option>
																											<option value="06">06</option>
																											<option value="07">07</option>
																											<option value="08">08</option>
																											<option value="09">09</option>
																											<option value="10">10</option>
																											<option value="11">11</option>
																											<option value="12">12</option>
																											<option value="13">13</option>
																											<option value="14">14</option>
																											<option value="15">15</option>
																											<option value="16">16</option>
																											<option value="17">17</option>
																											<option value="18">18</option>
																											<option value="19">19</option>
																											<option value="20">20</option>
																											<option value="21">21</option>
																											<option value="22">22</option>
																											<option value="23">23</option>
																											<option value="24">24</option>
																											<option value="25">25</option>
																											<option value="26">26</option>
																											<option value="27">27</option>
																											<option value="28">28</option>
																											<option value="29">29</option>
																											<option value="30">30</option>
																											<option value="31">31</option>
																											<option value="32">32</option>
																											<option value="33">33</option>
																											<option value="34">34</option>
																											<option value="35">35</option>
																											<option value="36">36</option>
																											<option value="37">37</option>
																											<option value="38">38</option>
																											<option value="39">39</option>
																											<option value="40">40</option>
																											<option value="41">41</option>
																											<option value="42">42</option>
																											<option value="43">43</option>
																											<option value="44">44</option>
																											<option value="45">45</option>
																											<option value="46">46</option>
																											<option value="47">47</option>
																											<option value="48">48</option>
																											<option value="49">49</option>
																											<option value="50">50</option>
																											<option value="51">51</option>
																											<option value="52">52</option>
																											<option value="53">53</option>
																											<option value="54">54</option>
																											<option value="55">55</option>
																											<option value="56">56</option>
																											<option value="57">57</option>
																											<option value="58">58</option>
																											<option value="59">59</option>
																										</select>
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</div>
																</div>
															</div>
														</div>
														<div class="tm1-row">
															<div class="col-left">
																<label>Same day delivery cut-off charges:</label>
															</div>
															<div class="col-right">
																<div class="rc-flx-wrap">
																	<img class="tt-info" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Same day delivery charges.">
																	<input class="ib-lg" type="text" id="prdd_same_day_cut_off_fee" name="prdd_same_day_cut_off_fee" value="0">
																</div>
															</div>
														</div>
														<div class="tm1-row">
															<div class="col-left">
																<label>Next day delivery cut-off time:</label>
															</div>
															<div class="col-right">
																<div class="rc-flx-wrap">
																	<img class="tt-info" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Disable next day for delivery after the set cut-off time. The cut-off time will be calculated based on the WordPress timezone.">
																	<div>
																		<table class="prdd-form-table transparent-table">
																			<tbody>
																				<tr>
																					<td>
																						<table>
																							<tbody>
																								<tr class="mb-1">
																									<td>
																										<select name="prdd_next_day_cutoff_hrs" id="prdd_next_day_cutoff_hrs">
																											<option value="">Hours</option>
																											<option value="0">0</option>
																											<option value="1">1</option>
																											<option value="2">2</option>
																											<option value="3">3</option>
																											<option value="4">4</option>
																											<option value="5">5</option>
																											<option value="6">6</option>
																											<option value="7">7</option>
																											<option value="8">8</option>
																											<option value="9">9</option>
																											<option value="10">10</option>
																											<option value="11">11</option>
																											<option value="12">12</option>
																											<option value="13">13</option>
																											<option value="14">14</option>
																											<option value="15">15</option>
																											<option value="16">16</option>
																											<option value="17">17</option>
																											<option value="18">18</option>
																											<option value="19">19</option>
																											<option value="20">20</option>
																											<option value="21">21</option>
																											<option value="22">22</option>
																											<option value="23">23</option>
																										</select>
																										<select name="prdd_next_day_cutoff_mins" id="prdd_next_day_cutoff_mins">
																											<option value="00">Minutes</option>
																											<option value="00">00</option>
																											<option value="01">01</option>
																											<option value="02">02</option>
																											<option value="03">03</option>
																											<option value="04">04</option>
																											<option value="05">05</option>
																											<option value="06">06</option>
																											<option value="07">07</option>
																											<option value="08">08</option>
																											<option value="09">09</option>
																											<option value="10">10</option>
																											<option value="11">11</option>
																											<option value="12">12</option>
																											<option value="13">13</option>
																											<option value="14">14</option>
																											<option value="15">15</option>
																											<option value="16">16</option>
																											<option value="17">17</option>
																											<option value="18">18</option>
																											<option value="19">19</option>
																											<option value="20">20</option>
																											<option value="21">21</option>
																											<option value="22">22</option>
																											<option value="23">23</option>
																											<option value="24">24</option>
																											<option value="25">25</option>
																											<option value="26">26</option>
																											<option value="27">27</option>
																											<option value="28">28</option>
																											<option value="29">29</option>
																											<option value="30">30</option>
																											<option value="31">31</option>
																											<option value="32">32</option>
																											<option value="33">33</option>
																											<option value="34">34</option>
																											<option value="35">35</option>
																											<option value="36">36</option>
																											<option value="37">37</option>
																											<option value="38">38</option>
																											<option value="39">39</option>
																											<option value="40">40</option>
																											<option value="41">41</option>
																											<option value="42">42</option>
																											<option value="43">43</option>
																											<option value="44">44</option>
																											<option value="45">45</option>
																											<option value="46">46</option>
																											<option value="47">47</option>
																											<option value="48">48</option>
																											<option value="49">49</option>
																											<option value="50">50</option>
																											<option value="51">51</option>
																											<option value="52">52</option>
																											<option value="53">53</option>
																											<option value="54">54</option>
																											<option value="55">55</option>
																											<option value="56">56</option>
																											<option value="57">57</option>
																											<option value="58">58</option>
																											<option value="59">59</option>
																										</select>
																									</td>
																								</tr>
																							</tbody>
																						</table>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</div>
																</div>
															</div>
														</div>
														<div class="tm1-row align-items-center">
															<div class="col-left">
																<label>Next day Delivery charge:</label>
															</div>
															<div class="col-right">
																<div class="rc-flx-wrap flx-aln-center">
																	<img class="tt-info" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Charges for next day delivery">
																	<input class="ib-lg" type="text" id="nextday_charge" name="nextday_charge" value="0">
																</div>
															</div>
														</div>
														<div class="tm1-row flx-center">
															<div class="col-left">
																<label>Delivery Type:</label>
															</div>
															<div class="col-right">
																<span class="el-radio el-radio-green">
											<input type="radio" name="prdd_date_range_type" id="prdd_date_range_type" value="fixed_range">
											<label class="el-radio-style" for="prdd_date_range_type"></label>
										</span>
																<label for="prdd_date_range_type" style="display: inline;">Fixed delivery period by dates ( e.g. March 1 to October 31) </label>
																<div style="margin-bottom: 5px;"></div>
																<span class="el-radio el-radio-green">
											<input type="radio" name="prdd_date_range_type" id="prdd_date_range_type_2" value="all_year" checked="">
											<label class="el-radio-style" for="prdd_date_range_type_2"></label>
										</span>
																<label for="prdd_date_range_type_2">Deliver all year round </label>
															</div>
														</div>
														<div class="tm1-row align-items-center">
															<div class="col-left">
																<label>Number of Dates to choose:</label>
															</div>
															<div class="col-right">
																<div class="rc-flx-wrap flx-aln-center">
																	<img class="tt-info" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="The maximum number of delivery dates you want to be available for your customers to choose from. For example, if you take only 2 months delivery in advance, enter 60 here.">
																	<input class="ib-lg" type="text" id="prdd_maximum_number_days" name="prdd_maximum_number_days" value="30">
																</div>
															</div>
														</div>
														<div class="tm1-row align-items-center">
															<div class="col-left"></div>
															<div class="col-right">
																<div class="rc-flx-wrap flx-aln-center">
																	OR </div>
															</div>
														</div>
														<div class="tm1-row align-items-center">
															<div class="col-left">
																<label>Deliveries start on:</label>
															</div>
															<div class="col-right">
																<div class="rc-flx-wrap flx-aln-center">
																	<img class="tt-info" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="The start date of the deliverable block. For e.g. if you want to take deliveries for the period of March to october, then the date here should be March 1">
																	<input class="ib-lg hasDatepicker" type="text" name="prdd_start_date_range" id="prdd_start_date_range" value="">
																</div>
															</div>
														</div>
														<div class="tm1-row align-items-center">
															<div class="col-left">
																<label>Deliveries end on:</label>
															</div>
															<div class="col-right">
																<div class="rc-flx-wrap flx-aln-center">
																	<img class="tt-info" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="The end date of the deliverable block. For e.g. if you want to take deliveries for the period of March to october, then the date here should be October 31.">
																	<input class="ib-lg hasDatepicker" type="text" name="prdd_end_date_range" id="prdd_end_date_range" value="">
																</div>
															</div>
														</div>
													</div>
													<div class="prdd-tab-content tab-delivery-charges" style="display:none;">
														<div class="row">
															<div class="col-md-12">
																<div class="d-flex justify-content-between heading-border mb-20">
																	<h4>Delivery Charges</h4>
																</div>
															</div>
															<div class="col-md-5">
																<label>Select Days:</label>
																<div class="rc-flx-wrap flx-aln-center">
																	<select name="special_delivery_weekday" id="special_delivery_weekday">
																		<option value="">Select Weekday</option>
																		<option value="prdd_weekday_0">Sunday</option>
																		<option value="prdd_weekday_1">Monday</option>
																		<option value="prdd_weekday_2">Tuesday</option>
																		<option value="prdd_weekday_3">Wednesday</option>
																		<option value="prdd_weekday_4">Thursday</option>
																		<option value="prdd_weekday_5">Friday</option>
																		<option value="prdd_weekday_6">Saturday</option>
																	</select>
																	<img class="tt-info ml-2" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Select weekday to set delivery charge for">
																</div>
															</div>
															<div class="col-md-4">
																<label>Select Date:</label>
																<div class="rc-flx-wrap flx-aln-center">
																	<input class="ib-sm hasDatepicker" type="text" name="special_delivery_date" id="special_delivery_date">
																	<img class="tt-info ml-2" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Select date to set delivery charge for">
																</div>
															</div>
															<div class="col-md-3">
																<label>Delivery Charges:</label>
																<div class="rc-flx-wrap flx-aln-center">
																	<input class="ib-sm" type="text" name="special_delivery_price" id="special_delivery_price">
																	<img class="tt-info ml-2" src="<?php echo $icon_info; ?>" alt="Info" data-toggle="tooltip" data-placement="top" title="Set delivery charge for the selected day / date">
																</div>
															</div>
															<div class="col-md-12 mt-3">
																<input type="button" class="trietary-btn reverse" value="Add Delivery Charges" id="save_special_delivery" onclick="prdd_save_special_delivery()">
																<input type="button" class="trietary-btn reverse" value="Cancel" id="cancel_special_delivery" onclick="prdd_cancel_special_delivery_update()" style="display:none;">
																<input type="hidden" name="prdd_special_delivery_id" id="prdd_special_delivery_id">
															</div>
															<div class="col-md-12 mt-3">
																<div id="special_delivery_price_table">
																	<p>
																		Delivery Charges <a style="float:right;" href="javascript:void(0);" id="" class="delete_all_special_delivery" data-nonce="d94ee24145"> Delete All Delivery Charges </a>
																	</p>
																	<table class="wp-list-table widefat fixed posts" cellspacing="0" id="list_special_deliveries" style="border-radius: 5px;">
																		<tbody>
																			<tr>
																				<th> Day / Date </th>
																				<th> Delivery Charges</th>
																				<th> Actions </th>
																			</tr>
																		</tbody>
																	</table>
																	<p></p>
																</div>
															</div>
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
							<div class="col-md-12">
								<button type="button" class="prdd-bulk-setting">Save Settings</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php do_action( 'prdd_lite_after_settings_page_form' ); ?>
</div>

