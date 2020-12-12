<?php
/**
 * Template file for the plugin settings
 *
 * @package Product-Delivery-Date-Lite
 */

?>
<script>
jQuery( document ).ready( function() {
	var tipText = '';
	jQuery( '#tabbed-nav .help_tip' ).each( function(i, el) {
		tipText = el.getAttribute( 'data-tip' );
		el.removeAttribute( 'data-tip' );
		el.setAttribute( 'title', tipText );
	});

	jQuery( '.help_tip' ).addClass( 'fa fa-question-circle tips' ).removeClass( 'help_tip' );
});
</script>

<div id='tabbed-nav' class="tstab-shadows tstab-tabs vertical top-left silver">
	<ul class="tstab-tabs-nav">
		<li class="tstab-tab tstab-first tstab-active" data-link="prdd_lite_date_time"><a id="addnew"> <?php esc_html_e( 'Delivery Options', 'woocommerce-prdd' ); ?> </a></li>
		<li class="tstab-tab tstab-first" data-link="prdd_lite_settings"><a id="settings"><?php esc_html_e( 'Settings', 'woocommerce-prdd' ); ?></a></li>
		<li class="tstab-tab tstab-first" data-link="prdd_lite_date_range_tab"><a id="date_range"> <?php esc_html_e( 'Delivery Time Period', 'woocommerce-prdd' ); ?> </a></li>
		<?php
		do_action( 'prdd_lite_add_tabs', $duplicate_of );
		?>
	</ul>

	<div class="tstab-container">
		<div id="prdd_lite_date_time" class="tstab-content tstab-active">
			<table class="form-table">
				<?php
				do_action( 'prdd_lite_before_enable_delivery', $duplicate_of );
				$plugins_url = plugins_url();
				?>
				<tr>
					<th>
						<label for="prdd_lite_enable_date"> <?php esc_html_e( 'Enable Delivery Date:', 'woocommerce-prdd-lite' ); ?> </label>
					</th>
					<td style="width:380px" >
						<?php
						$prdd_enable_delivery_date = get_post_meta( $duplicate_of, '_woo_prdd_lite_enable_delivery_date', true );
						$enable_date               = '';

						if ( isset( $prdd_enable_delivery_date ) && 'on' === $prdd_enable_delivery_date ) {
							$enable_date = 'checked';
						}

						?>
						<input type="checkbox" id="prdd_lite_enable_date" name="prdd_lite_enable_date" <?php echo esc_attr( $enable_date ); ?> >
					</td>
					<td>
						<img class="help_tip" width="16" height="16" data-tip="<?php esc_attr_e( 'Enable Delivery Date on Products Page', 'woocommerce-prdd-lite' ); ?>" src="<?php echo esc_attr( $plugins_url ); ?>/woocommerce/assets/images/help.png" />
					</td>
				</tr>
			</table>
			<table class="form-table">
				<?php do_action( 'prdd_lite_before_method_select', $duplicate_of ); ?>
				<tr id="prdd_method" >
					<th>
						<label for="prdd_method_select"> <?php esc_html_e( 'Deliver on:', 'woocommerce-prdd' ); ?></label>
					</th>
					<td style="width:380px">
						<?php
						$recurring_delivery = 'checked';
						$recurring_div_show = 'block';
						?>

						<input type="checkbox" name="prdd_recurring_chk" id="prdd_recurring_chk" <?php echo esc_attr( $recurring_delivery ); ?> disabled> <?php esc_html_e( 'Recurring Weekdays', 'woocommerce-prdd' ); ?> </input>

						<input type="checkbox" name="prdd_specific_chk" id="prdd_specific_chk" disabled="disabled" readonly> <?php esc_html_e( 'Specific Dates', 'woocommerce-prdd' ); ?> </input>
						<br>

						<b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>
					</td>
					<td>
						<img class="help_tip" width="16" height="16" data-tip="<?php esc_attr_e( 'Please enable/disable the specific delivery dates and recurring weekdays using these checkboxes. Upon checking them, you shall be able to further select dates or weekdays.', 'woocommerce-prdd' ); ?>" src="<?php echo esc_attr( $plugins_url ); ?>/woocommerce/assets/images/help.png" /><br>
					</td>
				</tr>
			</table>
			<div id="prdd_lite_enable_weekday" name="prdd_lite_enable_weekday" style="display:<?php echo esc_attr( $recurring_div_show ); ?>;">
				<table class="form-table">
					<tr>
						<th>
							<label for="prdd_lite_delivery_days[]"> <?php esc_html_e( 'Delivery Days:', 'woocommerce-prdd' ); ?> </label>
						</th>
						<td style="width:344px">
							<?php
							$prdd_lite_delivery_days = get_post_meta( $duplicate_of, '_woo_prdd_lite_delivery_days', true );
							?>
							<select name="prdd_lite_delivery_days[]" id="prdd_lite_delivery_days" multiple="multiple" style="width:300px">
								<?php
								$weekdays = prdd_lite_get_delivery_arrays( 'prdd_lite_weekdays' );
								foreach ( $weekdays as $n => $day_name ) {
									if ( ( is_array( $prdd_lite_delivery_days ) &&
										count( $prdd_lite_delivery_days ) > 0 &&
										in_array( $day_name, $prdd_lite_delivery_days, true ) ) ||
										'' === $prdd_lite_delivery_days ) {
										echo sprintf( '<option value="%1$s" selected>%1$s</option>', esc_attr( $day_name ) );
									} else {
										echo sprintf( '<option value="%1$s">%1$s</option>', esc_attr( $day_name ) );
									}
								}
								?>
							</select>
						</td>
						<td>
							<img class="help_tip" width="16" height="16" data-tip="<?php esc_html_e( 'Select Weekdays', 'woocommerce-prdd-lite' ); ?>" src="<?php echo esc_attr( $plugins_url ); ?>/woocommerce/assets/images/help.png" />
						</td>
					</tr>
				</table>
				<script type="text/javascript">
					jQuery( document ).ready( function() {
						jQuery( "#prdd_lite_delivery_days" ).select2();
					});
				</script>
			</div> 
			<table class="form-table">
				<tr id="prdd_lockout_date">
					<th>
						<label for="prdd_lockout_date"><?php esc_html_e( 'Maximum deliveries per day:', 'woocommerce-prdd' ); ?></label>
					</th>
					<td style="width:380px">
						<input type="text" name="prdd_lockout_date" id="prdd_lockout_date" value="60" disabled readonly>
						<br>
						<b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>
					</td>
					<td>
						<img class="help_tip" width="16" height="16" data-tip="<?php esc_attr_e( 'Set this field if you want to place a limit on maximum deliveries on any given date. If you can manage up to 15 deliveries in a day, set this value to 15. Once 15 orders have been placed, then that date will not be available for further deliveries.', 'woocommerce-prdd-lite' ); ?>" src="<?php echo esc_attr( $plugins_url ); ?>/woocommerce/assets/images/help.png" />
					</td>
				</tr>
				<?php
				do_action( 'prdd_lite_before_enable_time', $duplicate_of );
				?>
				<tr>
					<th>
						<hr>
					</th>
					<td>
						<hr>
					</td>
					<td>
						<hr>
					</td>
				</tr>
				<tr id="prdd_time" >
					<th>
						<label for="prdd_enable_time"><?php esc_html_e( 'Ask for Delivery Time:', 'woocommerce-prdd' ); ?></label>
					</th>
					<td style="width:380px">
						<input type="checkbox" name="prdd_enable_time" id="prdd_enable_time" disabled readonly>
						<b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>
					</td>
					<td>
						<img class="help_tip" width="16" height="16" data-tip="<?php esc_attr_e( 'Enable time (or time slots) on the product. Add any number of delivery time slots once you have checked this. You can manage the Time Slots using the Manage Dates, Time Slots tab', 'woocommerce-prdd' ); ?>" src="<?php echo esc_attr( $plugins_url ); ?>/woocommerce/assets/images/help.png" />
					</td>
				</tr>
				<?php
				do_action( 'prdd_after_time_enabled', $duplicate_of );
				?>
			</table>
			<div id="time_slot" name="time_slot"></div>
				<p>
					<div id="add_button" name="add_button" >
						<input type="button" class="button-primary" value="<?php esc_html_e( 'Add Time Slot', 'woocommerce-prdd' ); ?>" id="add_another" disabled readonly" >
					</div>
				</p>
		</div>

		<div id="prdd_lite_settings"  class="tstab-content tstab-active" style="display:none;">
			<table class="form-table">
				<tr>
					<th>
						<label for="prdd_lite_delivery_field_mandatory"><?php esc_html_e( 'Mandatory Fields:', 'woocommerce-prdd' ); ?></label>
					</th>
					<td style="width:380px">
						<?php
						$prdd_delivery_field_mandatory             = get_post_meta( $duplicate_of, '_woo_prdd_lite_delivery_field_mandatory', true );
						$enable_lite_prdd_delivery_field_mandatory = '';
						if ( isset( $prdd_delivery_field_mandatory ) && 'on' === $prdd_delivery_field_mandatory ) {
							$enable_lite_prdd_delivery_field_mandatory = 'checked';
						}
						?>
						<input type="checkbox" id="prdd_lite_delivery_field_mandatory" name="prdd_lite_delivery_field_mandatory" <?php echo esc_attr( $enable_lite_prdd_delivery_field_mandatory ); ?> >
					</td>
					<td>
						<img class="help_tip" width="16" height="16" data-tip="<?php esc_attr_e( 'Enable mandatory fields selection on front end product details page.', 'woocommerce-prdd-lite' ); ?>" src="<?php echo esc_attr( $plugins_url ); ?>/woocommerce/assets/images/help.png" style="vertical-align:top;"/>
					</td>
				</tr>

				<?php
				do_action( 'prdd_lite_before_product_holidays', $duplicate_of );
				?>

				<script type="text/javascript">
				jQuery(document).ready(function() {
					var formats = ["d.m.y", "d-m-yyyy","MM d, yy"];
					jQuery( "#prdd_lite_product_holiday" ).datepick({dateFormat: formats[1], multiSelect: 999, monthsToShow: 1, showTrigger: '#calImg'});
				});
				</script>
				<tr>
					<th>
						<label for="prdd_lite_product_holiday"><?php esc_html_e( 'No delivery on these dates:', 'woocommerce-prdd-lite' ); ?></label>
					</th>
					<td style="width:380px">
						<?php
						$prdd_lite_holidays = get_post_meta( $duplicate_of, '_woo_prdd_lite_holidays', true );
						?>
						<textarea rows="4" cols="40" name="prdd_lite_product_holiday" id="prdd_lite_product_holiday"><?php echo esc_html( $prdd_lite_holidays ); ?></textarea>
					</td>
					<td>
						<img class="help_tip" width="16" height="16" style="vertical-align:top;" data-tip="<?php esc_attr_e( 'Select dates for which the delivery will be completely disabled only for this product. Please click on the date in calendar to add or delete the date from the holiday list.', 'woocommerce-prdd-lite' ); ?>" src="<?php echo esc_attr( $plugins_url ); ?>/woocommerce/assets/images/help.png" style="vertical-align:top;"/>
					</td>
				</tr>
				<tr id="inline_calender" style="display:show">
					<th>
						<label for="prdd_enable_inline_calendar"> <?php esc_html_e( 'Show calendar always visible:', 'woocommerce-prdd' ); ?> </label>
					</th>
					<td style="width:380px">
						<input type="checkbox" id="prdd_enable_inline_calendar" name="prdd_enable_inline_calendar" disabled readonly>
						<b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>
					</td>
					<td>
						<img class="help_tip" width="16" height="16" data-tip="<?php esc_attr_e( 'Enable Inline Calendar on Products Page', 'woocommerce-prdd-lite' ); ?>" src="<?php echo esc_attr( $plugins_url ); ?>/woocommerce/assets/images/help.png" />
					</td>
				</tr>

				<tr>
					<th>
						<label for="prdd_hide_add_to_cart_button"><?php esc_html_e( 'Hide Add to Cart button: ', 'woocommerce-prdd' ); ?></label>
					</th>
					<td style="width:380px">
						<input type="checkbox" id="prdd_hide_add_to_cart_button" name="prdd_hide_add_to_cart_button" disabled readonly >
						<b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>
					</td>
					<td>
						<img class="help_tip" width="16" height="16" data-tip="<?php esc_attr_e( 'Hide Add to Cart button on front end product details page until a delivery date and/or time slot is selected.', 'woocommerce-prdd-lite' ); ?>" src="<?php echo esc_attr( $plugins_url ); ?>/woocommerce/assets/images/help.png" style="vertical-align:top;"/>
					</td>
				</tr>
			</table>
		</div>

		<div id="prdd_lite_date_range_tab" class="tstab-content tstab-active" style="display:none;">
			<table class="form-table">
				<?php
				do_action( 'prdd_lite_before_minimum_days', $duplicate_of );
				?>
				<tr>
					<th>
						<label for="prdd_lite_minimum_delivery_time"><?php esc_html_e( 'Minimum Delivery preparation time (in hours):', 'woocommerce-prdd-lite' ); ?></label>
					</th>
					<td style="width:380px">
						<?php
						$prdd_minimum_delivery_time = get_post_meta( $duplicate_of, '_woo_prdd_lite_minimum_delivery_time', true );
						if ( '' === $prdd_minimum_delivery_time ) {
							$prdd_minimum_delivery_time = '0';
						}
						?>
						<input type="text" style="width:90px;" name="prdd_lite_minimum_delivery_time" id="prdd_lite_minimum_delivery_time" value="<?php echo esc_attr( $prdd_minimum_delivery_time ); ?>" >
					</td>
					<td>
						<img class="help_tip" width="16" height="16" data-tip="<?php esc_attr_e( 'Enable Delivery after X number of hours from current time. The customer can select a delivery date that is available only after the minimum hours that are entered here. For example, if you need 1 day advance notice for a delivery, enter 24 here.', 'woocommerce-prdd-lite' ); ?>" src="<?php echo esc_attr( $plugins_url ); ?>/woocommerce/assets/images/help.png" />
					</td>
				</tr>
				<?php
				do_action( 'prdd_lite_before_number_of_dates', $duplicate_of );
				?>
				<tr>
					<th>
						<label for="prdd_lite_maximum_number_days"><?php esc_html_e( 'Number of Dates to choose:', 'woocommerce-prdd' ); ?></label>
					</th>
					<td style="width:380px">
						<?php
						$prdd_maximum_number_days = get_post_meta( $duplicate_of, '_woo_prdd_lite_maximum_number_days', true );
						if ( '' === $prdd_maximum_number_days ) {
							$prdd_maximum_number_days = '30';
						}
						?>
						<input type="text" style="width:90px;" name="prdd_lite_maximum_number_days" id="prdd_lite_maximum_number_days" value="<?php echo esc_attr( $prdd_maximum_number_days ); ?>" >
					</td>
					<td>
						<img class="help_tip" width="16" height="16" data-tip="<?php esc_attr_e( 'The maximum number of delivery dates you want to be available for your customers to choose from. For example, if you take only 2 months delivery in advance, enter 60 here.', 'woocommerce-prdd-lite' ); ?>" src="<?php echo esc_attr( $plugins_url ); ?>/woocommerce/assets/images/help.png" />
					</td>
				</tr>

				<tr>
					<td colspan="3">
						<hr>
					</td>
				</tr>

				<?php
				do_action( 'prdd_lite_before_same_day_delivery', $duplicate_of );
				?>

				<tr>
					<th>
						<label for="prdd_lite_same_day_cut_off"><?php esc_html_e( 'Same day delivery cut-off time:', 'woocommerce-prdd-lite' ); ?></label>
					</th>
					<td style="width:380px">
						<select name="prdd_lite_same_day_cutoff_hrs" id="prdd_lite_same_day_cutoff_hrs" disabled readonly>
							<?php
							printf( "<option value=''>%s</option>\n", esc_html_e( 'Hours', 'woocommerce-prdd' ) );
							?>
						</select>
						<select name="prdd_same_day_cutoff_mins" id="prdd_same_day_cutoff_mins" disabled readonly>
							<?php
							printf( "<option value=''>%s</option>\n", esc_html_e( 'Minutes', 'woocommerce-prdd' ) );
							?>
						</select>
					</td>
					<td>
						<img class="help_tip" width="16" height="16" data-tip="<?php esc_attr_e( 'Disable current day for delivery after the set cut-off time. The cut-off time will be calculated based on the WordPress timezone.', 'woocommerce-prdd-lite' ); ?>" src="<?php echo esc_attr( $plugins_url ); ?>/woocommerce/assets/images/help.png" />
					</td>
				</tr>

				<?php
				do_action( 'prdd_lite_before_next_day_delivery', $duplicate_of );
				?>

				<tr>
					<th>
						<label for="prdd_lite_next_day_cut_off"><?php esc_html_e( 'Next day delivery cut-off time:', 'woocommerce-prdd-lite' ); ?></label>
					</th>
					<td style="width:380px">
						<select name="prdd_lite_next_day_cutoff_hrs" id="prdd_lite_next_day_cutoff_hrs" disabled readonly>
							<?php
							printf( "<option value=''>%s</option>\n", esc_html_e( 'Hours', 'woocommerce-prdd-lite' ) );
							?>
						</select>
						<select name="prdd_next_day_cutoff_mins" id="prdd_next_day_cutoff_mins" disabled readonly>
							<?php
							printf( "<option value=''>%s</option>\n", esc_html_e( 'Minutes', 'woocommerce-prdd-lite' ) );
							?>
						</select>
					</td>
					<td>
						<img class="help_tip" width="16" height="16" data-tip="<?php esc_attr_e( 'Disable next day for delivery after the set cut-off time. The cut-off time will be calculated based on the WordPress timezone.', 'woocommerce-prdd-lite' ); ?>" src="<?php echo esc_attr( $plugins_url ); ?>/woocommerce/assets/images/help.png" />
					</td>
				</tr>

				<tr>
					<th></th>
					<td colspan="2">
						<b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>
					</td>
				</tr>

				<?php
					do_action( 'prdd_lite_before_range_selection_radio', $duplicate_of );
				?>

				<tr class="prdd_delivery_range">
					<th>
						<?php esc_html_e( 'Delivery Type:', 'woocommerce-prdd-lite' ); ?>
					</th>
					<td style="width:380px">
						<?php
						$all_year = 'checked';
						?>
						<input type="radio" name="prdd_date_range_type" id="prdd_date_range_type" value="all_year" <?php esc_attr( $all_year ); ?>><?php ( '&nbsp' . esc_html_e( 'Deliver all year round', 'woocommerce-prdd-lite' ) ); ?> </input>
						<br>
						<input type="radio" name="prdd_date_range_type" id="prdd_date_range_type" value="fixed_range" disabled readonly ><?php ( '&nbsp' . esc_html_e( 'Fixed delivery period by dates ( e.g. March 1 to October 31)', 'woocommerce-prdd-lite' ) ); ?> </input>
						<br>
						<br>
						<b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>
					</td>
					<td>
						<img class="help_tip" width="16" height="16" data-tip="<?php esc_html_e( 'Please choose a delivery type. It could be a specific date range or an all the year round delivery.', 'woocommerce-prdd-lite' ); ?>" src="<?php echo esc_attr( $plugins_url ); ?>/woocommerce/assets/images/help.png"/>
					</td>
				</tr>
				<?php
				do_action( 'prdd_before_start_date_range', $duplicate_of );
				?>

				<tr>
					<th>
						<label for="prdd_lite_start_date_range"><?php esc_html_e( 'Deliveries start on:', 'woocommerce-prdd' ); ?></label>
					</th>
					<td style="width:380px">
						<input type="text" style="width:150px;" name="prdd_lite_start_date_range" id="prdd_lite_start_date_range" disabled readonly >
					</td>
					<td>
						<img class="help_tip" width="16" height="16" data-tip="<?php esc_attr_e( 'The start date of the deliverable block. For e.g. if you want to take deliveries for the period of March to october, then the date here should be March 1', 'woocommerce-prdd-lite' ); ?>" src="<?php echo esc_attr( $plugins_url ); ?>/woocommerce/assets/images/help.png" />
					</td>
				</tr>

				<?php
				do_action( 'prdd_lite_before_end_date_range', $duplicate_of );
				?>

				<tr>
					<th>
						<label for="prdd_lite_end_date_range"><?php esc_html_e( 'Deliveries end on:', 'woocommerce-prdd' ); ?></label>
					</th>
					<td style="width:380px">
						<input type="text" style="width:150px;" name="prdd_lite_end_date_range" id="prdd_lite_end_date_range" disabled readonly >
					</td>
					<td>
						<img class="help_tip"  width="16" height="16" data-tip="<?php esc_attr_e( 'The end date of the deliverable block. For e.g. if you want to take deliveries for the period of March to october, then the date here should be October 31.', 'woocommerce-prdd-lite' ); ?>" src="<?php echo esc_attr( $plugins_url ); ?>/woocommerce/assets/images/help.png" />
					</td>
				</tr>
				<tr>
					<th></th>
					<td colspan="2">
						<b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>
					</td>
				</tr>
				<?php
				do_action( 'prdd_before_recurring_date_range', $duplicate_of );
				?>
			</table>
		</div>
		<?php
		do_action( 'prdd_lite_after_listing_enabled', $duplicate_of );
		?>
	</div>
</div>
