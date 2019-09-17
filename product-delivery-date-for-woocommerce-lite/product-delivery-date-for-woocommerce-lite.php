<?php
/**
 * Plugin Name: Product Delivery Date for WooCommerce - Lite
 * Description: This plugin lets you capture the Delivery Date for each product.
 * Version: 2.1.1
 * Author: Tyche Softwares
 * Author URI: https://www.tychesoftwares.com/
 * Requires PHP: 5.6
 * WC requires at least: 3.0.0
 * WC tested up to: 3.7.0
 * Text Domain: woocommerce-prdd-lite
 * Domain Path: /languages/
 *
 * @package Product-Delivery-Date-Lite
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Prdd_Lite_Woocommerce' ) ) {
	include_once 'class-prdd-lite-woocommerce.php';
}
