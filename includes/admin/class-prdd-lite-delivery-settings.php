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

		echo '<label for="prdd_lite_language"> ' . wp_kses_post( $args[0] ) . '</label>';
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

		echo '<label for="prdd_lite_date_format"> ' . wp_kses_post( $args[0] ) . '</label>';
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

		echo '<label for="prdd_lite_months"> ' . wp_kses_post( $args[0] ) . '</label>';

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

		echo '<label for="prdd_lite_calendar_day"> ' . wp_kses_post( $args[0] ) . '</label>';
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

		echo '<label for="prdd_lite_theme"> ' . wp_kses_post( $args[0] ) . '</label>';
	}

	/**
	 * Callback - Global Holidays
	 *
	 * @param array $args - Setting.
	 */
	public static function prdd_lite_global_holidays_callback( $args ) {
		echo '<textarea rows="4" cols="80" name="prdd_lite_global_holidays" id="prdd_lite_global_holidays"></textarea>
        <div id="prdd_lite_switcher"></div>';

		echo '<label for="prdd_lite_global_holidays"> ' . wp_kses_post( $args[0] ) . '</label>';
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

		echo '<label for="prdd_lite_enable_rounding"> ' . wp_kses_post( $args[0] ) . '</label>';
	}

	/**
	 * Callback - Deleting
	 *
	 * @param array $args - Setting.
	 */
	public static function prdd_lite_enable_delete_order_item_callback( $args ) {
		$prdd_enable_deleting = get_option( 'prdd_lite_enable_delete_order_item', '' );
		$deleting             = '';
		if ( isset( $prdd_enable_deleting ) && 'on' === $prdd_enable_deleting ) {
			$deleting = 'checked';
		}
		echo '<input type="checkbox" id="prdd_lite_enable_delete_order_item" name="prdd_lite_enable_delete_order_item" ' . esc_attr( $deleting ) . '/>';

		echo '<label for="prdd_lite_enable_delete_order_item">' . wp_kses_post( $args[0] ) . '</label>';
	}

	/**
	 * Call back for displaying tracking checkbox option for reset tracking.
	 *
	 * @param array $args Additional data for the settings field.
	 *
	 */
	public static function ts_rereset_tracking_callback( $args ) {
		$wcap_restrict_domain_address = get_option( 'wcap_restrict_domain_address' );
		$nonce                        = wp_create_nonce( 'ts_nonce_action' );
		$domain_value                 = isset( $wcap_restrict_domain_address ) ? esc_attr( $wcap_restrict_domain_address ) : '';
		// Next, we update the name attribute to access this element's ID in the context of the display options array.
		// We also access the show_header element of the options collection in the call to the checked() helper function.
		$ts_action = 'admin.php?page=woocommerce_prdd_lite_page&ts_action=reset_tracking&nonce=' . $nonce;
		printf( '<a href="' . $ts_action . '" class="button button-large reset_tracking">Reset</a>' );
	
		// Here, we'll take the first argument of the array and add it to a label next to the checkbox.
		echo '<label for="wcap_restrict_domain_address_label"> ' . $args[0] . '</label>';
	}
}
