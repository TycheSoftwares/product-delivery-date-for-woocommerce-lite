<?php
/**
 * Export Delivery date in Dashboard->Tools->Export Personal Data
 *
 * @since 1.8
 * @package Product-Delivery-Date-Lite
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Prdd_Privacy_Policy_Lite' ) ) {

	/**
	 * Export Delivery date in Dashboard->Tools->Export Personal Data.
	 *
	 * @since 1.8
	 */
	class Prdd_Privacy_Policy_Lite {

		/**
		 * Default Constructor
		 *
		 * @since 1.8
		 */
		public function __construct() {
			// Hook into the WP export process.
			add_filter( 'woocommerce_privacy_export_order_personal_data_props', array( &$this, 'prdd_lite_privacy_export_order_personal_data_props' ), 10, 2 );
			add_filter( 'woocommerce_privacy_export_order_personal_data_prop', array( &$this, 'prdd_lite_privacy_export_order_personal_data_prop_callback' ), 10, 3 );
		}

		/**
		 * Adding Delivery Date lable to personal data exporter order table.
		 *
		 * @param array  $props_to_export Array of the order property being exported.
		 * @param object $order WooCommerce Order Post.
		 *
		 * @since 1.8
		 *
		 * @hook woocommerce_privacy_export_order_personal_data_props
		 */
		public static function prdd_lite_privacy_export_order_personal_data_props( $props_to_export, $order ) {
			$my_key_value = array( 'items_delivery_lite' => __( 'Delivery Date', 'woocommerce-prdd-lite' ) );
			$key_pos      = array_search( 'items', array_keys( $props_to_export ), true );
			if ( false !== $key_pos ) {
				$key_pos++;
				$second_array    = array_splice( $props_to_export, $key_pos );
				$props_to_export = array_merge( $props_to_export, $my_key_value, $second_array );
			}
			return $props_to_export;
		}

		/**
		 * Adding Delivery Date value to personal data exporter order table
		 *
		 * @param string $value Column Data Value.
		 * @param string $prop key of the exported data.
		 * @param object $order WooCommerce Order Post.
		 *
		 * @since 1.8
		 *
		 * @hook woocommerce_privacy_export_order_personal_data_props
		 */
		public static function prdd_lite_privacy_export_order_personal_data_prop_callback( $value, $prop, $order ) {
			if ( 'items_delivery_lite' === $prop ) {
				$item_names = array();
				foreach ( $order->get_items() as $item => $item_value ) {
					$product_id   = $item_value['product_id'];
					$value_string = $item_value->get_name() . ' x ' . $item_value->get_quantity();
					$item_meta    = $item_value->get_meta_data();
					foreach ( $item_meta as $meta_data ) {
						if ( '_prdd_lite_date' === $meta_data->key ) {
							$value_string .= ' -- ' . gmdate( 'F j, y', strtotime( $meta_data->value ) );
							$item_names[]  = $value_string;
						}
					}
				}
				$value = implode( ', ', $item_names );
			}
			return $value;
		}
	} // end of class
	$prdd_privacy_policy_lite = new Prdd_Privacy_Policy_Lite();
} // end if
