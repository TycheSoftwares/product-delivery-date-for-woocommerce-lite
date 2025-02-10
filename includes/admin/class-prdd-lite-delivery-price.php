<?php
/**
 * PRDD Delivery charges for weekdays/dates.
 *
 * @author  Tyche Softwares
 * @package Product-Delivery-Date-Lite/Delivery-Charges
 * @since   2.0
 */

/**
 * Localization
 */
load_plugin_textdomain( 'prdd_lite_delivery_price', false, dirname( plugin_basename( __FILE__ ) ) . '/' );

if ( ! class_exists( 'Prdd_Lite_Delivery_Price' ) ) {

	/**
	 * Weekday delivery charges class.
	 *
	 * @since 1.0
	 */
	class Prdd_Lite_Delivery_Price {

		/**
		 * Default constructor
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_filter( 'prdd_addon_add_cart_item_data', array( &$this, 'special_delivery_add_to_cart' ), 10, 4 );
			add_filter( 'prdd_get_cart_item_from_session', array( &$this, 'get_special_delivery_cart_item_from_session' ), 10, 2 );
			add_filter( 'prdd_get_item_data', array( &$this, 'get_item_data' ), 10, 2 );
			add_action( 'prdd_update_order', array( &$this, 'order_item_meta' ), 10, 2 );
		}

		/**
		 * Returns delivery charges added for the selected delivery date for the product.
		 *
		 * @param int|string $product_id Product ID.
		 * @param string     $delivery_date Selected delivery date.
		 *
		 * @return string Delivery charges.
		 *
		 * @since 1.0
		 */
		public function get_price( $product_id, $delivery_date ) {
			$weekdays                = prdd_get_delivery_arrays( 'prdd_weekdays' );
			$delivery_special_prices = get_post_meta( $product_id, 'delivery_special_price', true );
			$special_delivery_price  = 0;
			if ( is_array( $delivery_special_prices ) && count( $delivery_special_prices ) > 0 ) {
				foreach ( $delivery_special_prices as $key => $values ) {
					list( $year, $month, $day ) = explode( '-', $delivery_date );
					$delivery_day               = gmdate( 'l', mktime( 0, 0, 0, $month, $day, $year ) ); // phpcs:ignore
					if ( isset( $values['delivery_special_weekday'] ) && isset( $weekdays[ $values['delivery_special_weekday'] ] ) && $delivery_day === $weekdays[ $values['delivery_special_weekday'] ] ) {
						$special_delivery_price = $values['delivery_special_price'];
					}
				}
				foreach ( $delivery_special_prices as $key => $values ) {
					list( $year, $month, $day ) = explode( '-', $delivery_date );
					if ( $values['delivery_special_date'] === $delivery_date ) {
						$special_delivery_price = $values['delivery_special_price'];
					}
				}
			}
			return $special_delivery_price;
		}

		/**
		 * Add delivery charges to the cart item when add to cart button is clicked
		 *
		 * @hook prdd_addon_add_cart_item_data
		 *
		 * @globals resource $woocommerce WooCommerce object.
		 *
		 * @param array      $cart_arr WooCommerce cart array.
		 * @param int|string $product_id Product ID.
		 * @param int|string $variation_id Variation ID for the selected attributes.
		 * @param array      $cart_item_meta Cart item meta array.
		 *
		 * @return array WooCommerce cart array.
		 *
		 * @since 1.0
		 */
		public function special_delivery_add_to_cart( $cart_arr, $product_id, $variation_id, $cart_item_meta ) {
			global $woocommerce;

			$date_array = array();
			foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $values ) {
				if ( isset( $values['prdd_delivery'][0]['hidden_date'] ) ) {
					$date_array[] = $values['prdd_delivery'][0]['hidden_date'];
				}
			}

			$prdd_settings   = get_post_meta( $product_id, 'woocommerce_prdd_settings', true );
			$global_settings = json_decode( get_option( 'woocommerce_prdd_global_settings' ) );
			$product         = wc_get_product( $product_id );
			if ( version_compare( get_option( 'woocommerce_version' ), '3.0.0', '>=' ) ) {
				$product_type = $product->get_type();
			} else {
				$product_type = $product->product_type;
			}

			if ( array_key_exists( 'composite_parent', $cart_item_meta ) && '' !== $cart_item_meta['composite_parent'] ) {
				$parent_product = WC()->cart->cart_contents[ $cart_item_meta['composite_parent'] ]['data'];
				$component_data = $parent_product->get_component_data( $cart_item_meta['composite_item'] );

				$composite_data = $cart_item_meta['composite_data'][ $cart_item_meta['composite_item'] ];

				if ( isset( $composite_data['product_id'] ) && '' !== $composite_data['product_id'] ) {
					$composite_product = wc_get_product( $composite_data['product_id'] );
				}

				if ( isset( $component_data['priced_individually'] ) && 'yes' === $component_data['priced_individually'] ) {
					if ( isset( $composite_data['variation_id'] ) && '' !== $composite_data['variation_id'] ) {
						$composite_variation = wc_get_product( $composite_data['variation_id'] );
						$cart_arr['price']   = $composite_variation->get_price();
						if ( isset( $composite_data['discount'] ) && '' !== $composite_data['discount'] ) {
							$cart_arr['price'] = $cart_arr['price'] - ( $cart_arr['price'] * $composite_data['discount'] / 100 );
						}
					} else {
						$cart_arr['price'] = $composite_product->get_price();
					}
				}
			} else {
				$weekdays = prdd_get_delivery_arrays( 'prdd_weekdays' );

				$delivery_date = '';
				if ( isset( $cart_arr['hidden_date'] ) ) {
					$delivery_date = $cart_arr['hidden_date'];
				}

				$special_delivery_price = self::prdd_get_special_delivery_price( $product_id, $delivery_date, $date_array );
				$price                  = prdd_common::prdd_get_price( $product_id, $variation_id, $product_type );

				$wpa_options_price = prdd_common::woo_product_addons_compatibility_cart( $cart_item_meta );
				$price             = $price + $wpa_options_price;

				if ( ! ( isset( $cart_arr['time_slot_charges'] ) ) ) {
					if ( isset( $special_delivery_price ) && $special_delivery_price != '' && $special_delivery_price != 0 ) { // phpcs:ignore
						$price                              = $price + $special_delivery_price;
						$cart_arr['special_delivery_price'] = $special_delivery_price;
					}

					$cart_arr['price'] = $price;
					if ( isset( $global_settings->prdd_enable_rounding ) && 'on' === $global_settings->prdd_enable_rounding && isset( $cart_arr['price'] ) ) {
						if ( isset( $cart_arr['price'] ) ) {
							$cart_arr['price'] = round( $cart_arr['price'] );
						}
					}
				}
			}

			return $cart_arr;
		}

		/**
		 * Returns delivery charges added for the selected delivery date for the product.
		 *
		 * @param int|string $product_id Product ID.
		 * @param string     $delivery_date Selected delivery date.
		 * @param array      $date_array Array of dates for which charges are already added to the cart.
		 *
		 * @return string Delivery charges.
		 *
		 * @since 1.0
		 */
		public static function prdd_get_special_delivery_price( $product_id, $delivery_date, $date_array ) {
			$global_settings         = json_decode( get_option( 'woocommerce_prdd_global_settings' ) );
			$delivery_special_prices = get_post_meta( $product_id, 'delivery_special_price', true );
			$special_delivery_price  = '';
			$include_price           = 'Yes';
			if ( isset( $global_settings->prdd_disable_price_calculation_on_dates ) && 'on' === $global_settings->prdd_disable_price_calculation_on_dates ) {
				if ( in_array( $delivery_date, $date_array, true ) ) {
					$include_price = 'No';
				}
			}

			if ( is_array( $delivery_special_prices ) && count( $delivery_special_prices ) > 0 && 'Yes' === $include_price ) {
				// strtotime does not support all date formats. hence it is suggested to use the "DateTime date_create_from_format" function.

				$date_formats    = prdd_get_delivery_arrays( 'prdd_date_formats' );
				$date_format_set = $date_formats[ $global_settings->prdd_date_format ];
				$date_formatted  = date_create_from_format( 'j-n-Y', $delivery_date );
				$date            = '';
				if ( isset( $date_formatted ) && '' !== $date_formatted ) {
					$date = date_format( $date_formatted, 'Y-m-d' );
				}

				$delivery_day     = gmdate( 'w', strtotime( $date ) ); // phpcs:ignore
				$delivery_weekday = 'prdd_weekday_' . $delivery_day;
				foreach ( $delivery_special_prices as $key => $values ) {
					if ( isset( $values['delivery_special_weekday'] ) && $values['delivery_special_weekday'] === $delivery_weekday ) {
						$special_delivery_price = $values['delivery_special_price'];
					} elseif ( isset( $values['delivery_special_date'] ) && $values['delivery_special_date'] === $date ) {
						$special_delivery_price = $values['delivery_special_price'];
					}
				}
			}
			return $special_delivery_price;
		}

		/**
		 * This function adjust the prices calculated from the plugin in the cart session.
		 *
		 * @hook prdd_get_cart_item_from_session
		 *
		 * @param array $cart_item Cart item data.
		 * @param array $values $_POST values.
		 *
		 * @return array Cart item data with delivery details.
		 * @since 1.0
		 */
		public function get_special_delivery_cart_item_from_session( $cart_item, $values ) {
			if ( isset( $values['prdd_delivery'] ) ) {
				$cart_item['prdd_delivery'] = $values['prdd_delivery'];
				$prdd_settings              = get_post_meta( $cart_item['product_id'], 'woocommerce_prdd_settings', true );
				if ( isset( $prdd_settings['prdd_enable_time'] ) && 'on' !== $prdd_settings['prdd_enable_time'] ) {
					if ( '' !== $cart_item['prdd_delivery'][0]['date'] ) {
						$cart_item = $this->add_cart_item( $cart_item );
					}
				}
			}
			return $cart_item;
		}

		/**
		 * This function adjust the extra prices for the product with the price calculated from the plugin.
		 *
		 * @globals resource $wpdb WordPress object
		 *
		 * @param array $cart_item Cart item array.
		 * @return array Cart item array with adjusted price.
		 *
		 * @since 1.0
		 */
		public function add_cart_item( $cart_item ) {
			global $wpdb;
			$product_type = 'simple';
			if ( '' !== $cart_item['variation_id'] ) {
				$product_type = 'variable';
			}
			$price = prdd_common::prdd_get_price( $cart_item['product_id'], $cart_item['variation_id'], $product_type );
			// Adjust price if addons are set.
			if ( isset( $cart_item['prdd_delivery'] ) ) {
				$extra_cost = 0;
				foreach ( $cart_item['prdd_delivery'] as $addon ) {
					if ( isset( $addon['price'] ) && $addon['price'] > 0 ) {
						$extra_cost += $addon['price'];
					}
				}

				if ( version_compare( get_option( 'woocommerce_version' ), '3.0.0', '>=' ) ) {
					$cart_item['data']->set_price( $extra_cost );
				} else {
					$extra_cost = $extra_cost - $price;
					$cart_item['data']->adjust_price( $extra_cost );
				}
			}
			return $cart_item;
		}

		/**
		 * This function adds the special pricing data on the Cart and Checkout page.
		 *
		 * @hook prdd_get_item_data
		 *
		 * @param array $other_data Additional data to be displayed.
		 * @param array $cart_item Cart item data.
		 *
		 * @return array Additional data to be displayed with delivery fields.
		 * @since 1.0
		 */
		public function get_item_data( $other_data, $cart_item ) {
			$global_settings = json_decode( get_option( 'woocommerce_prdd_global_settings' ) );
			$currency_symbol = get_woocommerce_currency_symbol();
			if ( isset( $cart_item['prdd_delivery'] ) ) {
				$price = '';
				foreach ( $cart_item['prdd_delivery'] as $delivery ) {
					if ( isset( $delivery['special_delivery_price'] ) ) {
						if ( isset( $global_settings->prdd_enable_rounding ) && 'on' === $global_settings->prdd_enable_rounding ) {
							$delivery['special_delivery_price'] = round( $delivery['special_delivery_price'] );
						} else {
							$delivery['special_delivery_price'] = $delivery['special_delivery_price'];
						}
						$delivery_charges = get_option( 'delivery_item-cart-charges' );
						$price           .= "$currency_symbol" . $delivery['special_delivery_price'];
					}
				}
			}

			if ( isset( $delivery['special_delivery_price'] ) ) {
				$other_data[] = array(
					'name'    => get_option( 'delivery_item-cart-charges' ),
					'display' => $price,
				);
			}
			return $other_data;
		}

		/**
		 * Add Delivery Charges as order item meta when order is placed.
		 *
		 * @hook prdd_update_order
		 *
		 * @globals resource $wpdb WordPress object.
		 *
		 * @param array    $values Cart item values.
		 * @param resource $order Order object.
		 *
		 * @since 1.0
		 */
		public function order_item_meta( $values, $order ) {
			global $wpdb;
			$product_id = $values['product_id'];
			$quantity   = $values['quantity'];
			if ( isset( $values['prdd_delivery'] ) ) {
				$delivery = $values['prdd_delivery'];
			} else {
				$delivery = array();
			}
			$saved_settings  = json_decode( get_option( 'woocommerce_prdd_global_settings' ) );
			$order_item_id   = $order->order_item_id;
			$order_id        = $order->order_id;
			$currency_symbol = get_woocommerce_currency_symbol();
			if ( isset( $delivery[0]['special_delivery_price'] ) && '' !== $delivery[0]['special_delivery_price'] && ! isset( $delivery[0]['time_slot_charges'] ) ) {
				wc_add_order_item_meta( $order_item_id, get_option( 'delivery_item-meta-charges' ), $currency_symbol . $delivery[0]['special_delivery_price'] );
			}

			if ( version_compare( WOOCOMMERCE_VERSION, '2.5' ) > 0 ) {
				// Code where the Delivery dates are not displayed in the customer new order email from WooCommerce version 2.5.
				$cache_key       = WC_Cache_Helper::get_cache_prefix( 'orders' ) . 'item_meta_array_' . $order_item_id;
				$item_meta_array = wp_cache_get( $cache_key, 'orders' );
				if ( false !== $item_meta_array ) {
					$metadata = $wpdb->get_results( $wpdb->prepare( "SELECT meta_key, meta_value, meta_id FROM {$wpdb->prefix}woocommerce_order_itemmeta WHERE order_item_id = %d AND meta_key = %s ORDER BY meta_id", absint( $order_item_id ), get_option( 'delivery_item-meta-charges' ) ) ); // phpcs:ignore
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
	} // EOF Class
} // EOF if class exist
$prdd_lite_delivery_price = new Prdd_Lite_Delivery_Price();
