<?php
/**
 * Plugin Name: Product Delivery Date for WooCommerce - Lite
 * Description: This plugin lets you capture the Delivery Date for each product.
 * Version: 3.1.0
 * Author: Tyche Softwares
 * Author URI: https://www.tychesoftwares.com/
 * Requires PHP: 7.3
 * WC requires at least: 5.0.0
 * WC tested up to: 10.0.4
 * Text Domain: woocommerce-prdd-lite
 * Requires Plugins: woocommerce
 * Domain Path: /languages/
 *
 * @package Product-Delivery-Date-Lite
 */

defined( 'ABSPATH' ) || exit;
// phpcs:disable
add_action( 'before_woocommerce_init',
    function () {
        if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
            $file = __FILE__;
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', $file, true );
        }
    } );

if ( ! class_exists( 'Prdd_Lite_Woocommerce' ) ) {
	include_once 'class-prdd-lite-woocommerce.php';
}
