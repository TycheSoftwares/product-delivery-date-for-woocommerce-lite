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
		<li class="tstab-tab tstab-first tstab-active" data-link="prdd_lite_date_time"><a id="addnew"> <?php esc_html_e( 'Delivery Options', 'woocommerce-prdd-lite' ); ?> </a></li>
		<li class="tstab-tab tstab-first" data-link="prdd_lite_settings"><a id="settings"><?php esc_html_e( 'Settings', 'woocommerce-prdd-lite' ); ?></a></li>
		<li class="tstab-tab tstab-first" data-link="prdd_lite_date_range_tab"><a id="date_range"> <?php esc_html_e( 'Delivery Time Period', 'woocommerce-prdd-lite' ); ?> </a></li>
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
				
			</table>
			<div id="prdd_lite_enable_weekday" name="prdd_lite_enable_weekday">
				<table class="form-table">
					<tr>
						<th>
							<label for="prdd_lite_delivery_days[]"> <?php esc_html_e( 'Delivery Days:', 'woocommerce-prdd-lite' ); ?> </label>
						</th>
						<td style="width:380px">
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
										echo sprintf( '<option value="%1$s" selected>%1$s</option>', esc_attr( $day_name ) );// phpcs:ignore
									} else {
										echo sprintf( '<option value="%1$s">%1$s</option>', esc_attr( $day_name ) );// phpcs:ignore
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
				<?php
				do_action( 'prdd_lite_before_enable_time', $duplicate_of );
				do_action( 'prdd_after_time_enabled', $duplicate_of );
				?>
			</table>
		</div>

		<div id="prdd_lite_settings"  class="tstab-content tstab-active" style="display:none;">
			<table class="form-table">
				<tr>
					<th>
						<label for="prdd_lite_delivery_field_mandatory"><?php esc_html_e( 'Mandatory Fields:', 'woocommerce-prdd-lite' ); ?></label>
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
						<label for="prdd_lite_maximum_number_days"><?php esc_html_e( 'Number of Dates to choose:', 'woocommerce-prdd-lite' ); ?></label>
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
