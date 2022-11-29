<?php
/**
 * Plugin Name: Product Delivery Date for WooCommerce - Lite
 * Description: This plugin lets you capture the Delivery Date for each product.
 * Version: 2.6.0
 * Author: Tyche Softwares
 * Author URI: https://www.tychesoftwares.com/
 * Requires PHP: 5.6
 * WC requires at least: 3.0.0
 * WC tested up to: 5.5
 * Text Domain: woocommerce-prdd-lite
 * Domain Path: /languages/
 *
 * @package Product-Delivery-Date-Lite
 */

defined( 'ABSPATH' ) || exit;

add_action( 'before_woocommerce_init', function() {
    if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
        $file = __FILE__;       
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', $file , true );
    }
} );


if ( ! class_exists( 'Prdd_Lite_Woocommerce' ) ) {
	include_once 'class-prdd-lite-woocommerce.php';
}
