<?php
/**
 * Product Delivery Date Frontend processes.
 *
 * @author Tyche Softwares
 * @package Product-Delivery-Date-Lite/Frontend
 * @since 1.0
 */

/**
 * Include the files as needed.
 */
require_once 'class-prdd-lite-common.php';

/**
 * Frontend single product page processes.
 *
 * @since 1.0
 */
class Prdd_Lite_Process {
	/**
	 * This function add the Delivery Date fields on the frontend product page as per the settings selected when Enable Delivery Date is enabled.
	 *
	 * @hook woocommerce_before_add_to_cart_button
	 *
	 * @since 1.0
	 */
	public static function prdd_lite_after_add_to_cart() {
		global $post, $wpdb, $woocommerce;
		$duplicate_of               = Prdd_Lite_Common::prdd_lite_get_product_id( $post->ID );
		$prdd_settings              = get_post_meta( $duplicate_of, '_woo_prdd_lite_enable_delivery_date', true );
		$prdd_minimum_delivery_time = get_post_meta( $duplicate_of, '_woo_prdd_lite_minimum_delivery_time', true );

		$prdd_maximum_number_days = get_post_meta( $duplicate_of, '_woo_prdd_lite_maximum_number_days', true );
		$prdd_lite_delivery_days  = get_post_meta( $duplicate_of, '_woo_prdd_lite_delivery_days', true );

		$prdd_delivery_field_mandatory = get_post_meta( $duplicate_of, '_woo_prdd_lite_delivery_field_mandatory', true );

		if ( '' === $prdd_maximum_number_days || 'null' == $prdd_maximum_number_days ) { // phpcs:ignore loose comparison ok.
			$prdd_maximum_number_days = '30';
		}
		$current_time = current_time( 'timestamp' );
		if ( '' !== $prdd_minimum_delivery_time && 0 !== $prdd_minimum_delivery_time ) {
			$advance_seconds = $prdd_minimum_delivery_time * 60 * 60;
			// now we need the first available weekday for delivery as minimum time is to be calculated from thereon.
			$weekday = gmdate( 'l', $current_time ); // phpcs:ignore
			while ( ! in_array( $weekday, $prdd_lite_delivery_days, true ) ) { // this weekday is unavailable for delivery.
				$current_time += 86400; // add 1 day to it.
				$weekday       = gmdate( 'l', $current_time ); // phpcs:ignore
			}
			$cut_off_timestamp = $current_time + $advance_seconds;
			$cut_off_date      = gmdate( 'd-m-Y', $cut_off_timestamp ); // phpcs:ignore
			$min_date          = gmdate( 'j-n-Y', strtotime( $cut_off_date ) ); // phpcs:ignore
		} else {
			$min_date = gmdate( 'j-n-Y', $current_time ); // phpcs:ignore
		}

		self::prdd_localize_global_settings();

		print( '<input type="hidden" name="prdd_lite_hidden_minimum_delivery_time" id="prdd_lite_hidden_minimum_delivery_time" value="' . esc_attr( $min_date ) . '">' );
		$plugins_url = plugins_url();
		if ( isset( $prdd_settings ) && 'on' === $prdd_settings ) {

			print ( '<div class="delivery_date_label_container" style="position: relative;"><label class="delivery_date_label">' . esc_html_e( 'Delivery Date:', 'woocommerce-prdd-lite' ) . '</label>' );

			if ( 'on' === $prdd_delivery_field_mandatory ) {
				print( '<abbr class="required" title="required" style="color: red;font-weight: 800;border: none;">*</abbr>' );
			}
			print( '<img src="' . esc_attr( $plugins_url ) . '/product-delivery-date-for-woocommerce-lite/images/cal.png" width="20" height="20" style="cursor:pointer!important; position:absolute; top:50%; right:5%; transform: translateY(-50%);" id ="delivery_cal_lite"/>
            <input type="text" id="delivery_calender_lite" name="delivery_calender_lite" class="delivery_calender_lite" style="cursor:text!important;display:block;  margin-bottom:20px; width:100%;" readonly/>
            </div>
            <input type="hidden" id="prdd_lite_hidden_date" name="prdd_lite_hidden_date"/>' );
		}

	}

	/**
	 * Localize the settings to be used on front end
	 */
	public static function prdd_localize_global_settings() {
		global $post, $wpdb, $woocommerce;
		$plugin_version_number = get_option( 'woocommerce_prdd_lite_db_version' );

		$duplicate_of             = Prdd_Lite_Common::prdd_lite_get_product_id( $post->ID );
		$prdd_maximum_number_days = get_post_meta( $duplicate_of, '_woo_prdd_lite_maximum_number_days', true );
		$prdd_lite_delivery_days  = get_post_meta( $duplicate_of, '_woo_prdd_lite_delivery_days', true );

		if ( '' === $prdd_maximum_number_days || 'null' == $prdd_maximum_number_days ) { // phpcs:ignore
			$prdd_maximum_number_days = '30';
		}

		$date_format       = get_option( 'prdd_lite_date_format' );
		$prdd_months       = get_option( 'prdd_lite_months' );
		$day_selected      = get_option( 'prdd_lite_calendar_day' );
		$language_selected = get_option( 'prdd_lite_language' );

		$additional_data      = array();
		$book_global_holidays = '';

		$global_holidays = get_option( 'prdd_lite_global_holidays' );
		if ( '' !== $global_holidays ) {
			$book_global_holidays = substr( $global_holidays, 0, strlen( $global_holidays ) );
			$book_global_holidays = '"' . str_replace( ',', '","', $book_global_holidays ) . '"';
		}

		$product_holidays = get_post_meta( $duplicate_of, '_woo_prdd_lite_holidays', true );
		if ( '' !== $product_holidays ) {
			if ( '' !== $book_global_holidays ) {
				$book_global_holidays .= ',';
			}
			$book_global_holidays .= '"' . str_replace( ',', '","', $product_holidays ) . '"';
		}

		$additional_data['holidays'] = $book_global_holidays;

		wp_register_script( 'prdd-lite-process-functions', plugins_url() . '/product-delivery-date-for-woocommerce-lite/js/initialize-datepicker.js', '', $plugin_version_number, true );

		wp_localize_script(
			'prdd-lite-process-functions',
			'prdd_lite_params',
			array(
				'prdd_maximum_number_days' => $prdd_maximum_number_days,
				'prdd_lite_delivery_days'  => $prdd_lite_delivery_days,
				'date_format'              => $date_format,
				'prdd_months'              => $prdd_months,
				'first_day'                => $day_selected,
				'language_selected'        => $language_selected,
				'additional_data'          => wp_json_encode( $additional_data ),
			)
		);

		wp_enqueue_script( 'prdd-lite-process-functions' );

	}

	/**
	 * When "Add to cart" button is clicked on product page, this function returns the cart_item_meta with the delivery details of the product.
	 *
	 * @hook woocommerce_add_cart_item_data
	 *
	 * @param object $cart_item_meta - Meta Data for the Cart Items.
	 * @param int    $product_id product's ID.
	 * @return array $cart_item_meta cart_item_meta array with the delivery details.
	 * @since 1.0
	 */
	public static function prdd_lite_add_cart_item_data( $cart_item_meta, $product_id ) {
		global $wpdb;
		$duplicate_of = Prdd_Lite_Common::prdd_lite_get_product_id( $product_id );
		if ( isset( $_POST['delivery_calender_lite'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification
			$date_disp = sanitize_text_field( wp_unslash( $_POST['delivery_calender_lite'] ) ); //phpcs:ignore WordPress.Security.NonceVerification
		}
		if ( isset( $_POST['prdd_lite_hidden_date'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification
			$hidden_date = sanitize_text_field( wp_unslash( $_POST['prdd_lite_hidden_date'] ) ); //phpcs:ignore WordPress.Security.NonceVerification
		}

		$cart_arr = array();
		if ( isset( $date_disp ) ) {
			$cart_arr['delivery_date'] = $date_disp;
		}
		if ( isset( $hidden_date ) ) {
			$cart_arr['delivery_hidden_date'] = $hidden_date;
		}
		$cart_item_meta['prdd_lite_delivery'][] = $cart_arr;
		return $cart_item_meta;
	}

	/**
	 * Round the cart item price if the rounding prices setting is enabled
	 *
	 * @param object $cart_item - Cart Item Data.
	 * @param array  $values - Cart Item data Values.
	 * @param string $cart_item_key - Cart Item Key.
	 */
	public static function prdd_lite_get_cart_item_from_session( $cart_item, $values, $cart_item_key ) {
		if ( isset( $cart_item['prdd_lite_delivery'] ) ) {
			$price = $cart_item['data']->get_price();

			if ( 'on' === get_option( 'prdd_lite_enable_rounding', '' ) ) {
				$cart_item['data']->set_price( round( $price ) );
			}
		}

		return $cart_item;
	}

	/**
	 * This function displays the Delivery details on cart page, checkout page.
	 *
	 * @hook woocommerce_get_item_data
	 *
	 * @param array $other_data - Array which contains item data.
	 * @param array $cart_item - Delivery details and product details.
	 * @return array $other_data - Array with delivery date and delivery date field name.
	 * @since 1.0
	 */
	public static function prdd_lite_get_item_data( $other_data, $cart_item ) {
		if ( isset( $cart_item['prdd_lite_delivery'] ) ) {
			$duplicate_of = Prdd_Lite_Common::prdd_lite_get_product_id( $cart_item['product_id'] );
			foreach ( $cart_item['prdd_lite_delivery'] as $delivery ) {
				$name = __( 'Delivery Date', 'woocommerce-prdd-lite' );
				if ( isset( $delivery['delivery_date'] ) && '' !== $delivery['delivery_date'] ) {
					$other_data[] = array(
						'name'    => $name,
						'display' => $delivery['delivery_date'],
					);
				}
				$other_data = apply_filters( 'prdd_get_item_data', $other_data, $cart_item );
			}
		}
		return $other_data;
	}

	/**
	 * This function adds the delivery hidden fields with product array.
	 *
	 * @hook woocommerce_hidden_order_itemmeta
	 *
	 * @param array $arr - Array for Item Meta.
	 * @return array $arr - Return after adding plugin data.
	 * @since 1.0
	 */
	public static function prdd_lite_hidden_order_itemmeta( $arr ) {
		$arr[] = '_prdd_lite_date';
		$arr[] = '_prdd_date';
		return $arr;
	}

	/**
	 * This function updates the database for the delivery details and adds delivery fields on the Order Received page & WooCommerce->Orders page when an order is placed for WooCommerce version greater than 2.0.
	 *
	 * @hook woocommerce_checkout_update_order_meta
	 *
	 * @param int   $item_meta - Order ID.
	 * @param array $cart_item - Product Details.
	 * @since 1.0
	 */
	public static function prdd_lite_order_item_meta( $item_meta, $cart_item ) {
		if ( version_compare( WOOCOMMERCE_VERSION, '2.0.0' ) < 0 ) {
			return;
		}
		// Add the fields.
		global $wpdb, $woocommerce;
		// default the variables.
		$sub_query      = '';
		$order_item_ids = array();

		foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $values ) {
			$_product = $values['data'];
			if ( isset( $values['prdd_lite_delivery'] ) ) {
				$delivery = $values['prdd_lite_delivery'];
			}
			$quantity   = $values['quantity'];
			$post_id    = Prdd_Lite_Common::prdd_lite_get_product_id( $values['product_id'] );
			$post_title = $_product->get_title();

			// Fetch line item.
			if ( count( $order_item_ids ) > 0 ) {
				$order_item_ids_to_exclude = implode( ',', $order_item_ids );
				$sub_query                 = ' AND order_item_id NOT IN ( ' . $order_item_ids_to_exclude . ')';
			}

			$query   = 'SELECT order_item_id, order_id FROM `' . $wpdb->prefix . 'woocommerce_order_items`
					              WHERE order_id = %s AND order_item_name LIKE %s' . $sub_query;
			$results = $wpdb->get_results( $wpdb->prepare( $query, $item_meta, $post_title . '%' ) ); // phpcs:ignore unprepared SQL ok, phpcs:ignore WordPress.DB.DirectDatabaseQuery.

			$order_item_ids[] = $results[0]->order_item_id;
			$order_id         = $results[0]->order_id;
			$order_obj        = new WC_order( $order_id );
			$details          = array();
			$product_ids      = array();
			$order_items      = $order_obj->get_items();

			if ( isset( $values['prdd_lite_delivery'] ) ) {
				$prdd_settings = get_post_meta( $post_id, '_woo_prdd_lite_enable_delivery_date', true );
				$details       = array();
				if ( isset( $delivery[0]['delivery_date'] ) && '' !== $delivery[0]['delivery_date'] ) {
					$name        = 'Delivery Date';
					$date_select = $delivery[0]['delivery_date'];
					wc_add_order_item_meta( $results[0]->order_item_id, $name, sanitize_text_field( $date_select, true ) );
				}
				if ( array_key_exists( 'delivery_hidden_date', $delivery[0] ) && '' !== $delivery[0]['delivery_hidden_date'] ) {
					$date_booking = gmdate( 'Y-m-d', strtotime( $delivery[0]['delivery_hidden_date'] ) ); // phpcs:ignore
					wc_add_order_item_meta( $results[0]->order_item_id, '_prdd_lite_date', sanitize_text_field( $date_booking, true ) );
					wc_add_order_item_meta( $results[0]->order_item_id, '_prdd_date', sanitize_text_field( $date_booking, true ) );
				}
			}

			if ( version_compare( WOOCOMMERCE_VERSION, '2.5' ) < 0 ) {
				continue;
			} else {
				// Code where the Delivery dates are not displayed in the customer new order email from WooCommerce version 2.5.
				$cache_key       = WC_Cache_Helper::get_cache_prefix( 'orders' ) . 'item_meta_array_' . $results[0]->order_item_id;
				$item_meta_array = wp_cache_get( $cache_key, 'orders' );
				if ( false !== $item_meta_array ) {
					$metadata = $wpdb->get_results( $wpdb->prepare( "SELECT meta_key, meta_value, meta_id FROM {$wpdb->prefix}woocommerce_order_itemmeta WHERE order_item_id = %d AND meta_key IN (%s,%s) ORDER BY meta_id", absint( $results[0]->order_item_id ), 'Delivery Date', '_prdd_lite_date' ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.
					foreach ( $metadata as $metadata_row ) {
						$item_meta_array[ $metadata_row->meta_id ] = (object) array(
							'key'   => $metadata_row->meta_key,
							'value' => $metadata_row->meta_value,
						);
					}
					wp_cache_set( $cache_key, $item_meta_array, 'orders' );
				}
			}
		}
	}

}
