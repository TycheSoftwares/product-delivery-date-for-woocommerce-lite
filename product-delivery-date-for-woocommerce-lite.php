<?php
/**
 * Plugin Name: Product Delivery Date for WooCommerce - Lite
 * Description: This plugin lets you capture the Delivery Date for each product.
 * Version: 3.3.2
 * Author: Tyche Softwares
 * Author URI: https://www.tychesoftwares.com/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 6.2
 * Requires PHP: 7.4
 * WC requires at least: 5.0.0
 * WC tested up to: 11.1.0
 * Text Domain: product-delivery-date-for-woocommerce-lite
 * Requires Plugins: woocommerce
 * Domain Path: /languages/
 *
 * @package Product-Delivery-Date-Lite
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'PRDD_LITE_VERSION' ) ) {
	define( 'PRDD_LITE_VERSION', '3.3.2' );
}
if ( ! defined( 'PRDD_LITE_PLUGIN_FILE' ) ) {
	define( 'PRDD_LITE_PLUGIN_FILE', __FILE__ );
}
if ( ! defined( 'PRDD_LITE_PLUGIN_PATH' ) ) {
	define( 'PRDD_LITE_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
}
if ( ! defined( 'PRDD_LITE_PLUGIN_URL' ) ) {
	define( 'PRDD_LITE_PLUGIN_URL', untrailingslashit( plugins_url( '', __FILE__ ) ) );
}

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
