<?php
/**
 * PRDDD General Delivery Settings
 *
 * @author Tyche Softwares
 * @package Product-Delivery-Date-Pro-for-WooCommerce/Admin/Settings/General
 * @since 1.0
 * @category Classes
 */

/**
 * Class for Delivery Settings.
 */
class Prdd_Lite_Delivery_Settings {

	/**
	 * Callback for settings section.
	 */
	public static function prdd_lite_delivery_settings_section_callback() {}

	/**
	 * Callback - Language Settings.
	 *
	 * @param array $args - Setting.
	 */
	public static function prdd_lite_language_callback( $args ) {

		$language_selected = get_option( 'prdd_lite_language', '' );

		if ( '' === $language_selected ) {
			$language_selected = 'en-GB';
		}

		$languages = prdd_lite_get_delivery_arrays( 'prdd_lite_languages' );

		echo '<select id="prdd_lite_language" name="prdd_lite_language">';
		foreach ( $languages as $key => $value ) {
			$sel = '';
			if ( $key === $language_selected ) {
				$sel = 'selected';
			}
			printf( "<option value='%s' %s>%s</option>\n", esc_attr( $key ), esc_attr( $sel ), esc_attr( $value ) );
		}
		echo '</select>';

		$html = '<label for="prdd_lite_language"> ' . $args[0] . '</label>';
		esc_html( $html );
	}

	/**
	 * Callback - Date Format Setting.
	 *
	 * @param array $args - Setting.
	 */
	public static function prdd_lite_date_format_callback( $args ) {
		$date_format = get_option( 'prdd_lite_date_format' );

		echo '<select id="prdd_lite_date_format" name="prdd_lite_date_format">';
		$date_formats = prdd_lite_get_delivery_arrays( 'prdd_lite_date_formats' );
		foreach ( $date_formats as $k => $format ) {
			printf(
				"<option %s value='%s'>%s</option>\n",
				selected( $k, $date_format, false ),
				esc_attr( $k ),
				date( $format ) // phpcs:ignore
			);
		}
		echo '</select>';

		$html = '<label for="prdd_lite_date_format"> ' . $args[0] . '</label>';
		esc_html( $html );
	}

	/**
	 * Callback - Number of Months to display
	 *
	 * @param array $args - Setting.
	 */
	public static function prdd_lite_months_callback( $args ) {
		$prdd_months = get_option( 'prdd_lite_months' );
		$no_months_1 = '';
		$no_months_2 = '';

		if ( 1 == $prdd_months ) { //phpcs:ignore
			$no_months_1 = 'selected';
			$no_months_2 = '';
		} elseif ( 2 == $prdd_months ) { //phpcs:ignore
			$no_months_2 = 'selected';
			$no_months_1 = '';
		}

		printf(
			'<select id="prdd_lite_months" name="prdd_lite_months">
			<option %s value="1"> 1 </option>
			<option %s value="2"> 2 </option>
			</select>',
			esc_attr( $no_months_1 ),
			esc_attr( $no_months_2 )
		);

		$html = '<label for="prdd_lite_months"> ' . $args[0] . '</label>';
		esc_html( $html );
	}

	/**
	 * Callback - Calendar Start Day
	 *
	 * @param array $args - Setting.
	 */
	public static function prdd_lite_calendar_day_callback( $args ) {

		$day_selected = get_option( 'prdd_lite_calendar_day', '' );

		if ( '' === $day_selected ) {
			$day_selected = get_option( 'start_of_week' );
		}

		echo '<select id="prdd_lite_calendar_day" name="prdd_lite_calendar_day">';
		$days = prdd_lite_get_delivery_arrays( 'prdd_lite_days' );
		foreach ( $days as $key => $value ) {
			$sel = '';
			if ( $key == $day_selected ) { // phpcs:ignore
				$sel = ' selected ';
			}
			printf( "<option value='%s' %s>%s</option>\n", esc_attr( $key ), esc_attr( $sel ), esc_attr__( $value, 'woocommerce-prdd-lite' ) ); // phpcs:ignore
		}
		echo '</select>';
		$html = '<label for="prdd_lite_calendar_day"> ' . $args[0] . '</label>';
		esc_html( $html );
	}

	/**
	 * Callback - Calendar Theme
	 *
	 * @param array $args - Setting.
	 */
	public static function prdd_lite_theme_callback( $args ) {
		$prdd_themes = get_option( 'prdd_lite_theme' );

		$language_selected = get_option( 'prdd_lite_language', '' );

		if ( '' === $language_selected ) {
			$language_selected = 'en-GB';
		}

		$holidays        = get_option( 'prdd_lite_global_holidays', '' );
		$global_holidays = '';

		if ( $holidays && '' !== $holidays ) {
			$global_holidays = "addDates: ['" . str_replace( ',', "','", $holidays ) . "']";
		}

		$first_day = 1;
		if ( '' !== get_option( 'prdd_lite_calendar_day', '' ) ) {
			$first_day = get_option( 'prdd_lite_calendar_day' );
		}

		echo '<input type="hidden" name="prdd_lite_theme" id="prdd_lite_theme" value="' . esc_attr( $prdd_themes ) . '">';

		echo '<script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery( "#prdd_lite_new_switcher" ).themeswitcher({
                onclose: function() {
                    var cookie_name = this.cookiename;
                    jQuery( "input#prdd_lite_theme" ).val( jQuery.cookie( cookie_name ) );
                    jQuery( "<link/>", {
                        rel: "stylesheet",
                        type: "text/css",
                        href: "' . esc_js( plugins_url() ) . '/product-delivery-date-for-woocommerce-lite/css/datepicker.css"
                    }).appendTo( "head" );
                },
                imgpath: "' . esc_js( plugins_url() ) . '/product-delivery-date-for-woocommerce-lite/images/",
                loadTheme: "smoothness"
            });
            var date = new Date();
            jQuery.datepicker.setDefaults( jQuery.datepicker.regional[ "en-GB" ] );
            jQuery( "#prdd_lite_new_switcher" ).datepicker( jQuery.datepicker.regional[ "' . esc_js( $language_selected ) . '" ] );

            jQuery( "#prdd_lite_switcher" ).multiDatesPicker({
                dateFormat: "d-m-yy",
                firstDay: ' . esc_js( $first_day ) . ',
                altField: "#prdd_lite_global_holidays",
                ' . $global_holidays . '
            });
            jQuery( function() {
                jQuery.datepicker.setDefaults( jQuery.datepicker.regional[ "" ] );
                jQuery( "#prdd_lite_new_switcher" ).datepicker( jQuery.datepicker.regional[ "en-GB" ] );
               
                jQuery( "#prdd_lite_language" ).change(function() {
                    jQuery( "#prdd_lite_new_switcher" ).datepicker( "option", jQuery.datepicker.regional[ jQuery(this).val() ] );
                });
                jQuery( "#prdd_lite_calendar_day" ).change( function() {
                    jQuery( "#prdd_lite_new_switcher" ).datepicker( "option","firstDay", jQuery(this).val() );
                });
                jQuery( "#prdd_lite_new_switcher" ).datepicker( "option","firstDay", ' . esc_js( $first_day ) . ' );
                jQuery( ".ui-datepicker-inline" ).css( "font-size","1.4em" );
            });
        });
        </script>
        <div id="prdd_lite_new_switcher"></div>';

		$html = '<label for="prdd_lite_theme"> ' . $args[0] . '</label>';
		esc_html( $html );
	}

	/**
	 * Callback - Global Holidays
	 *
	 * @param array $args - Setting.
	 */
	public static function prdd_lite_global_holidays_callback( $args ) {
		echo '<textarea rows="4" cols="80" name="prdd_lite_global_holidays" id="prdd_lite_global_holidays"></textarea>
        <div id="prdd_lite_switcher"></div>';

		$html = '<label for="prdd_lite_global_holidays"> ' . $args[0] . '</label>';
		esc_html( $html );
	}

	/**
	 * Callback - Rounding
	 *
	 * @param array $args - Setting.
	 */
	public static function prdd_lite_enable_rounding_callback( $args ) {
		$prdd_enable_rounding = get_option( 'prdd_lite_enable_rounding', '' );
		$rounding             = '';
		if ( isset( $prdd_enable_rounding ) && 'on' === $prdd_enable_rounding ) {
			$rounding = 'checked';
		}
		echo '<input type="checkbox" id="prdd_lite_enable_rounding" name="prdd_lite_enable_rounding" ' . esc_attr( $rounding ) . '/>';
		$html = '<label for="prdd_lite_enable_rounding"> ' . $args[0] . '</label>';
		esc_html( $html );

	}

	/**
	 * Callback - Time Format Setting.
	 *
	 * @param array $args - Setting.
	 */
	public static function prdd_lite_time_format_callback( $args ) {
		echo '<select id="prdd_lite_time_format" name="prdd_lite_time_format" disabled>';

		$time_formats = prdd_lite_get_delivery_arrays( 'prdd_lite_time_formats' );
		foreach ( $time_formats as $k => $format ) {
			printf(
				"<option %s value='%s'>%s</option>\n",
				selected( $k, 12, false ),
				esc_attr( $k ),
				esc_attr__( $format, 'woocommerce-prdd-lite' ) // phpcs:ignore
			);
		}
		echo '</select>';
		$html = '<label for="prdd_lite_time_format"> ' . $args[0] . '</label>';
		esc_html( $html );

		echo '<br><b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>';

	}

	/**
	 * Callback - Global Selection.
	 *
	 * @param array $args - Setting.
	 */
	public static function prdd_global_selection_callback( $args ) {

		echo '<input type="checkbox" id="prdd_global_selection" name="prdd_global_selection" disabled readonly/>';
		$html = '<label for="prdd_global_selection"> ' . $args[0] . '</label>';
		esc_html( $html );

		echo '<br><b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>';
	}

	/**
	 * Adds a field to display the number of deliveries available for a given product on a given date and time.
	 *
	 * @param array $args Extra arguments containing label & class for the field.
	 * @since 1.0
	 */
	public static function prdd_availability_display_callback( $args ) {

		echo '<input type="checkbox" id="prdd_availability_display" name="prdd_availability_display" disabled/>';

		$html = '<label for="prdd_availability_display"> ' . $args[0] . '</label>';

		esc_html( $html );

		echo '<br><b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>';
	}

	/**
	 * Adds a field to restrict delivery charges to apply only once for multiple products with the same delivery date in the cart.
	 *
	 * @param array $args Extra arguments containing label & class for the field.
	 * @since 1.0
	 */
	public static function prdd_disable_price_calculation_on_dates_callback( $args ) {
		echo '<input type="checkbox" id="prdd_disable_price_calculation_on_dates" name="woocommerce_prdd_global_settings[prdd_disable_price_calculation_on_dates]" disabled readonly/>';

		$html = '<label for="prdd_disable_price_calculation_on_dates"> ' . $args[0] . '</label>';

		esc_html( $html );
		echo '<br><b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>';

	}

	/**
	 * Callback - Add to Calendar
	 *
	 * @param array $args - Setting.
	 */
	public static function prdd_add_to_calendar_callback( $args ) {

		echo '<input type="checkbox" id="prdd_add_to_calendar" name="woocommerce_prdd_global_settings[prdd_export]" disabled readonly/>';
		$html = '<label for="prdd_add_to_calendar"> ' . $args[0] . '</label>';

		esc_html( $html );

		echo '<br><b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>';
	}

	/**
	 * Adds a field to allow customers to export deliveries as ICS file from the email.
	 *
	 * @param array $args Extra arguments containing label & class for the field.
	 * @since 1.0
	 */
	public static function prdd_add_to_email_callback( $args ) {
		echo '<input type="checkbox" id="prdd_add_to_email" name="wprdd_add_to_email" disabled/>';
		$html = '<label for="prdd_add_to_email"> ' . $args[0] . '</label>';
		esc_html( $html );

		echo '<br><b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>';
	}

	/**
	 * Callback - Allow Deliveries
	 *
	 * @param array $args - Setting.
	 */
	public static function prdd_allow_deliveries_callback( $args ) {
		?>
			<input 
				type="checkbox" 
				id="prdd_enable_delivery_edit" 
				name="prdd_enable_delivery_edit"
				disabled
				readonly
			/>
			<label for="prdd_enable_delivery_edit">
				<?php esc_html( $args[0] ); ?>
			</label>

			<br>
			<b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>
		<?php
	}

	/**
	 * Call back for displaying settings option for My Account page
	 *
	 * @param array $args Additional data for the settings field.
	 *
	 * @since 2.4
	 */
	public static function prdd_allow_reschedulable_callback( $args ) {

		?>
			<input 
				type="checkbox" 
				id="prdd_enable_delivery_reschedule" 
				name="prdd_enable_delivery_reschedule"
				disabled
				readonly
			/>
			<label for="prdd_enable_delivery_reschedule">
				<?php esc_html( $args[0] ); ?>
			</label>

			<br>
			<b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>
		<?php
	}

	/**
	 * Call back for displaying settings option for rescheduling period
	 *
	 * @param array $args Additional data for the settings field.
	 *
	 * @since 2.4
	 */
	public static function prdd_reschedulable_days_callback( $args ) {

		?>
			<input 
				type="text" 
				id="prdd_delivery_reschedule_days" 
				name="prdd_delivery_reschedule_days"
				disabled
				readonly
			/>

			<label for="prdd_delivery_reschedule_days">
				<?php esc_html( $args[0] ); ?>
			</label>

			<br>
			<b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>
		<?php
	}

	/** Delivery Labels (only in pro) */

	/**
	 * Section Callback.
	 */
	public static function prdd_delivery_product_page_labels_section_callback() {}


	/**
	 * Adds a field to set the delivery date label on Product page
	 *
	 * @param array $args Extra arguments containing label & class for the field.
	 * @since 1.0
	 */
	public static function delivery_date_label_callback( $args ) {
		echo '<input type="text" name="delivery_date-label" id="delivery_date-label" value="" maxlength="40" disabled readonly/>';
		$html = '<label for="delivery_date-label"> ' . $args[0] . '</label>';
		esc_html( $html );

		echo '<br><b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>';
	}

	/**
	 * Adds a field to set the delivery time label on Product page
	 *
	 * @param array $args Extra arguments containing label & class for the field.
	 * @since 1.0
	 */
	public static function delivery_time_label_callback( $args ) {
		echo '<input type="text" name="delivery_time-label" id="delivery_time-label" value="" maxlength="40" disabled readonly/>';
		$html = '<label for="delivery_time-label"> ' . $args[0] . '</label>';
		esc_html( $html );

		echo '<br><b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>';
	}

	/**
	 * Adds a field to set text for the 1st option of Time Slot dropdown field that instructs the customer to select a time slot.
	 *
	 * @param array $args Extra arguments containing label & class for the field.
	 * @since 1.0
	 */
	public static function delivery_time_select_option_callback( $args ) {
		echo '<input type="text" name="delivery_time-select-option" id="delivery_time-select-option" value="" maxlength="40" disabled readonly/>';
		$html = '<label for="delivery_time-select-option"> ' . $args[0] . '</label>';
		esc_html( $html );

		echo '<br><b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>';
	}

	/**
	 * Section Callback
	 */
	public static function prdd_delivery_order_received_page_labels_section_callback() {}

	/**
	 * Adds a field to set the Delivery Date label on the order received page and email notification
	 *
	 * @param array $args Extra arguments containing label & class for the field.
	 * @since 1.0
	 */
	public static function delivery_item_meta_date_callback( $args ) {

		echo '<input type="text" name="delivery_item-meta-date" id="delivery_item-meta-date" value="" maxlength="40" disabled readonly/>';
		$html = '<label for="delivery_item-meta-date"> ' . $args[0] . '</label>';
		esc_html( $html );

		echo '<br><b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>';
	}

	/**
	 * Adds a field to set the Delivery Time label on the order received page and email notification
	 *
	 * @param array $args Extra arguments containing label & class for the field.
	 * @since 1.0
	 */
	public static function delivery_item_meta_time_callback( $args ) {

		echo '<input type="text" name="delivery_item-meta-time" id="delivery_item-meta-time" value="" maxlength="40" disabled readonly/>';
		$html = '<label for="delivery_item-meta-time"> ' . $args[0] . '</label>';
		esc_html( $html );

		echo '<br><b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>';
	}

	/**
	 * Adds a field to set the ICS File name.
	 *
	 * @param array $args Extra arguments containing label & class for the field.
	 * @since 1.0
	 */
	public static function delivery_ics_file_name_callback( $args ) {
		echo '<input type="text" name="delivery_ics-file-name" id="delivery_ics-file-name" value="" maxlength="40" disabled readonly/>';
		$html = '<label for="delivery_ics-file-name"> ' . $args[0] . '</label>';
		esc_html( $html );

		echo '<br><b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>';
	}

	/**
	 * Adds a field to set Delivery Charges label on the order received page and email notification.
	 *
	 * @param array $args Extra arguments containing label & class for the field.
	 * @since 1.0
	 */
	public static function delivery_item_meta_charges_callback( $args ) {
		echo '<input type="text" name="delivery_item-meta-charges" id="delivery_item-meta-charges" value="" maxlength="40" disabled readonly/>';
		$html = '<label for="delivery_item-meta-charges"> ' . $args[0] . '</label>';
		esc_html( $html );

		echo '<br><b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>';
	}

	/**
	 * Adds a section to set the labels for Cart & Checkout page
	 *
	 * @since 1.0
	 */
	public static function prdd_delivery_cart_page_labels_section_callback() {}

	/**
	 * Adds a field to set Delivery Date label on the cart and checkout page
	 *
	 * @param array $args Extra arguments containing label & class for the field.
	 * @since 1.0
	 */
	public static function delivery_item_cart_date_callback( $args ) {
		echo '<input type="text" name="delivery_item-cart-date" id="delivery_item-cart-date" value="" maxlength="40" disabled readonly/>';
		$html = '<label for="delivery_item-cart-date"> ' . $args[0] . '</label>';
		esc_html( $html );

		echo '<br><b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>';
	}

	/**
	 * Adds a field to set Delivery Time label on the cart and checkout page
	 *
	 * @param array $args Extra arguments containing label & class for the field.
	 * @since 1.0
	 */
	public static function delivery_item_cart_time_callback( $args ) {
		echo '<input type="text" name="delivery_item-cart-time" id="delivery_item-cart-time" value="" maxlength="40" disabled readonly/>';
		$html = '<label for="delivery_item-cart-time"> ' . $args[0] . '</label>';
		esc_html( $html );

		echo '<br><b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>';
	}

	/**
	 * Adds a field to set Delivery Charges label on the cart and checkout page
	 *
	 * @param array $args Extra arguments containing label & class for the field.
	 * @since 1.0
	 */
	public static function delivery_item_cart_charges_callback( $args ) {
		echo '<input type="text" name="delivery_item-cart-charges" id="delivery_item-cart-charges" value="" maxlength="40" disabled readonly/>';
		$html = '<label for="delivery_item-cart-charges"> ' . $args[0] . '</label>';
		esc_html( $html );

		echo '<br><b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>';
	}

	/**
	 * Adds a section to set the labels for Estimate Delivery option
	 *
	 * @since 1.0
	 */
	public static function prdd_estimate_delivery_section_callback() {}

	/**
	 * Adds a field to set the heading for Estimate Delivery Section
	 *
	 * @since 1.0
	 */
	public static function prdd_estimate_delivery_header_callback() {

		echo '<input type="text" name="prdd_estimate_delivery_header" id="prdd_estimate_delivery_header" value="" maxlength="40" disabled readonly/>';

		echo '<br><b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>';
	}

	/**
	 * Adds a field to set the text to display the estimated delivery
	 *
	 * @param array $args Extra arguments containing label & class for the field.
	 * @since 1.0
	 */
	public static function prdd_estimate_delivery_days_text_callback( $args ) {
		echo '<textarea name="prdd_estimate_delivery_days_text" id="prdd_estimate_delivery_days_text" rows="3" cols="40" disabled readonly></textarea>';
		$html = '<label for="prdd_estimate_delivery_days_text"> ' . $args[0] . '</label>';
		esc_html( $html );

		echo '<br><b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>';
	}

	/**
	 * Adds a field to set the text to display the estimated delivery for specific dates
	 *
	 * @param array $args Extra arguments containing label & class for the field.
	 * @since 1.0
	 */
	public static function prdd_estimate_delivery_date_text_callback( $args ) {
		echo '<textarea name="prdd_estimate_delivery_date_text" id="prdd_estimate_delivery_date_text" rows="3" cols="40" disabled readonly></textarea>';
		$html = '<label for="prdd_estimate_delivery_date_text"> ' . $args[0] . '</label>';
		esc_html( $html );

		echo '<br><b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>';
	}

	/** For Google sync settings (Pro) */
	public static function ts_calendar_sync_general_settings_callback() {}

	/**
	 * Callback for adding a field to enter the event location
	 *
	 * @param array $args Extra arguments containing label & class for the field.
	 * @since 2.3
	 */
	public static function ts_calendar_event_location_callback( $args ) {
		echo '<input type="text" name="ts_calendar_event_location" id="ts_calendar_event_location" value="" disabled readonly/>';
		$html = '<label for="ts_calendar_event_location"> ' . $args[0] . '</label>';
		esc_html( $html );

		echo '<br><b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>';
	}

	/**
	 * Callback for adding a field to enter the event summary
	 *
	 * @param array $args Extra arguments containing label & class for the field.
	 * @since 2.3
	 */
	public static function ts_calendar_event_summary_callback( $args ) {
		echo '<input id="ts_calendar_event_summary" name="ts_calendar_event_summary" value="" size="90" type="text" disabled readonly/>';

		echo '<br><b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>';
	}

	/**
	 * Callback for adding a field to enter the event description.
	 *
	 * @param array $args Extra arguments containing label & class for the field.
	 * @since 2.3
	 */
	public static function ts_calendar_event_description_callback( $args ) {
		echo '<textarea id="ts_calendar_event_description" name="ts_calendar_event_description" cols="90" rows="4" disabled readonly></textarea>';
		$html = '<label for="ts_calendar_event_description"> ' . $args[0] . '</label>';
		esc_html( $html );

		echo '<br><b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>';
	}

	/**
	 * Section Callback
	 */
	public static function prdd_calendar_sync_customer_settings_section_callback() {}

	/**
	 * Callback to display Add to Calendar button on the Order Received page
	 *
	 * @param array $args Extra arguments containing label & class for the field.
	 * @since 2.3
	 */
	public static function prdd_add_to_calendar_order_received_page_callback( $args ) {
		echo '<input type="checkbox" name="prdd_add_to_calendar_order_received_page" id="prdd_add_to_calendar_order_received_page" class="day-checkbox" value="on" disabled readonly/>';
		$html = '<label for="prdd_add_to_calendar_order_received_page"> ' . $args[0] . '</label>';
		esc_html( $html );

		echo '<br><b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>';
	}

	/**
	 * Callback to display Add to Calendar button in customer email
	 *
	 * @param array $args Extra arguments containing label & class for the field.
	 * @since 2.3
	 */
	public static function prdd_add_to_calendar_customer_email_callback( $args ) {
		echo '<input type="checkbox" name="prdd_add_to_calendar_customer_email" id="prdd_add_to_calendar_customer_email" class="day-checkbox" value="on" disabled readonly/>';
		$html = '<label for="prdd_add_to_calendar_customer_email"> ' . $args[0] . '</label>';
		esc_html( $html );

		echo '<br><b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>';
	}

	/**
	 * Callback to display Add to Calendar button on the My Account page
	 *
	 * @param array $args Extra arguments containing label & class for the field.
	 * @since 2.3
	 */
	public static function prdd_add_to_calendar_my_account_page_callback( $args ) {
		echo '<input type="checkbox" name="prdd_add_to_calendar_my_account_page" id="prdd_add_to_calendar_my_account_page" class="day-checkbox" value="on" disabled readonly/>';
		$html = '<label for="prdd_add_to_calendar_my_account_page"> ' . $args[0] . '</label>';
		esc_html( $html );

		echo '<br><b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>';
	}

	/**
	 * Callback to open calendar in the same or different window
	 *
	 * @param array $args Extra arguments containing label & class for the field.
	 * @since 2.3
	 */
	public static function prdd_calendar_in_same_window_callback( $args ) {
		echo '<input type="checkbox" name="prdd_calendar_in_same_window" id="prdd_calendar_in_same_window" class="day-checkbox" value="on" disabled readonly />';
		$html = '<label for="prdd_calendar_in_same_window"> ' . $args[0] . '</label>';
		esc_html( $html );

		echo '<br><b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>';
	}

	/**
	 * Section Callback
	 */
	public static function ts_calendar_sync_admin_settings_section_callback() {}

	/**
	 * Callback to select the integration mode - sync manually or automatically
	 *
	 * @param array $args Extra arguments containing label & class for the field.
	 * @since 2.3
	 */
	public static function ts_calendar_sync_integration_mode_callback( $args ) {
		echo '<input type="radio" name="ts_calendar_sync_integration_mode" id="ts_calendar_sync_integration_mode" value="directly" disabled/>' . esc_html__( 'Sync Automatically', 'woocommerce-prdd-lite' ) . '&nbsp;&nbsp;
            <input type="radio" name="ts_calendar_sync_integration_mode" id="ts_calendar_sync_integration_mode" value="manually" disabled/>' . esc_html__( 'Sync Manually', 'woocommerce-prdd-lite' ) . '&nbsp;&nbsp;
            <input type="radio" name="ts_calendar_sync_integration_mode" id="ts_calendar_sync_integration_mode" value="disabled" disabled/>' . esc_html__( 'Disabled', 'woocommerce-prdd-lite' );

		$html = '<label for="ts_calendar_sync_integration_mode"> ' . $args[0] . '</label>';
		esc_html( $html );

		echo '<br><b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>';

	}

	/**
	 * Callback to display the steps to set up Google Calendar API when clicked on 'Show me how' button
	 *
	 * @since 2.3
	 */
	public static function ts_sync_calendar_instructions_callback() {}

	/**
	 * Callback to add a field to enter key file name without extension
	 *
	 * @param array $args Extra arguments containing label & class for the field.
	 * @since 2.3
	 */
	public static function ts_calendar_key_file_name_callback( $args ) {
		echo '<input id="ts_calendar_key_file_name" name= "ts_calendar_details_1[ts_calendar_key_file_name]" value="" size="90" type="text" disabled readonly/>';
		$html = '<label for="ts_calendar_key_file_name"> ' . $args[0] . '</label>';
		esc_html( $html );

		echo '<br><b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>';

	}

	/**
	 * Callback to add a field to enter service account email address
	 *
	 * @param array $args Extra arguments containing label & class for the field.
	 * @since 2.3
	 */
	public static function ts_calendar_service_acc_email_address_callback( $args ) {
		echo '<input id="orddd_calendar_service_acc_email_address" name="ts_calendar_details_1[ts_calendar_service_acc_email_address]" value="" size="90" type="text" disabled readonly/>';
		$html = '<label for="ts_calendar_service_acc_email_address"> ' . $args[0] . '</label>';
		esc_html( $html );

		echo '<br><b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>';

	}

	/**
	 * Callback to add a field to enter the Google Calendar ID
	 *
	 * @param array $args Extra arguments containing label & class for the field.
	 * @since 2.3
	 */
	public static function ts_calendar_id_callback( $args ) {
		echo '<input id="ts_calendar_id" name="ts_calendar_details_1[ts_calendar_id]" value="" size="90" type="text" disabled readonly/>';
		$html = '<label for="ts_calendar_id"> ' . $args[0] . '</label>';
		esc_html( $html );

		echo '<br><b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>';

	}

	/**
	 * Callback to add a link to test the connection with Google API
	 *
	 * @since 2.3
	 */
	public static function ts_calendar_test_connection_callback() {}

	/**
	 * Callback to display 'Add to Calendar' in the email notifications
	 *
	 * @param array $args Extra arguments containing label & class for the field.
	 * @since 2.3
	 */
	public static function prdd_admin_add_to_calendar_email_notification_callback( $args ) {
		echo '<input type="checkbox" name="prdd_admin_add_to_calendar_email_notification" id="prdd_admin_add_to_calendar_email_notification" value="on" disabled readonly />';
		$html = '<label for="prdd_admin_add_to_calendar_email_notification"> ' . $args[0] . '</label>';
		esc_html( $html );

		echo '<br><b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>';

	}

	/**
	 * Callback to display 'Add to Calendar' on the View Deliveries page
	 *
	 * @param array $args Extra arguments containing label & class for the field.
	 * @since 2.3
	 */
	public static function prdd_admin_add_to_calendar_delivery_calendar_callback( $args ) {
		echo '<input type="checkbox" name="prdd_admin_add_to_calendar_delivery_calendar" id="prdd_admin_add_to_calendar_delivery_calendar" value="on" disabled readonly />';
		$html = '<label for="prdd_admin_add_to_calendar_delivery_calendar"> ' . $args[0] . '</label>';
		esc_html( $html );

		echo '<br><b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>';

	}
}
