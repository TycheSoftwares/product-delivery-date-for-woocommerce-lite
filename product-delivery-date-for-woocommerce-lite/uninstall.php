<?php
/**
 * Product Delivery Date for WooCommerce - Lite
 *
 * When Product Delivery Date Lite plugin uninstalls, it will delete all settings & tables for the plugin.
 *
 * @author   Tyche Softwares
 * @package  Product-Delivery-Date-Lite/Uninstall
 * @since    1.5
 * @category Core
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
global $wpdb;
if ( is_multisite() ) {
	// Get main site's table prefix.
	$blog_list = get_sites();
	foreach ( $blog_list as $blog_list_key => $blog_list_value ) {
		if ( $blog_list_value->blog_id > 1 ) {
			$sub_site_prefix = $wpdb->prefix . $blog_list_value->blog_id . '_';

			// Enable Delivery Date Checkbox.
			$wpdb->query( $wpdb->prepare( "DELETE FROM `%s` WHERE meta_key='_woo_prdd_lite_enable_delivery_date'", $sub_site_prefix . 'postmeta' ) ); // WPCS: db call ok, WPCS: cache ok.

			// Minimum Delivery Date.
			$wpdb->query( $wpdb->prepare( "DELETE FROM `%s` WHERE meta_key='_woo_prdd_lite_minimum_delivery_time'", $sub_site_prefix . 'postmeta' ) ); // WPCS: db call ok, WPCS: cache ok.

			// Number of Dates to Choose.
			$wpdb->query( $wpdb->prepare( "DELETE FROM `%s` WHERE meta_key='_woo_prdd_lite_maximum_number_days'", $sub_site_prefix . 'postmeta' ) ); // WPCS: db call ok, WPCS: cache ok.

			// Delivery Days.
			$wpdb->query( $wpdb->prepare( "DELETE FROM `%s` WHERE meta_key='_woo_prdd_lite_delivery_days'", $sub_site_prefix . 'postmeta' ) ); // WPCS: db call ok, WPCS: cache ok.

			// Mandatory Fields.
			$wpdb->query( $wpdb->prepare( "DELETE FROM `%s` WHERE meta_key='_woo_prdd_lite_delivery_field_mandatory'", $sub_site_prefix . 'postmeta' ) ); // WPCS: db call ok, WPCS: cache ok.

			// Product Level Holidays.
			$wpdb->query( $wpdb->prepare( "DELETE FROM `%s` WHERE meta_key='_woo_prdd_lite_holidays'", $sub_site_prefix . 'postmeta' ) ); // WPCS: db call ok, WPCS: cache ok.
		}
	}
} else {

	// Product Settings.
	delete_post_meta_by_key( '_woo_prdd_lite_enable_delivery_date' );
	delete_post_meta_by_key( '_woo_prdd_lite_minimum_delivery_time' );
	delete_post_meta_by_key( '_woo_prdd_lite_maximum_number_days' );
	delete_post_meta_by_key( '_woo_prdd_lite_delivery_days' );
	delete_post_meta_by_key( '_woo_prdd_lite_delivery_field_mandatory' );
	delete_post_meta_by_key( '_woo_prdd_lite_holidays' );
}

// Options Data.
delete_option( 'woocommerce_prdd_lite_db_version' );
delete_option( 'prdd_lite_allow_tracking' );
delete_option( 'prdd_lite_pro_welcome_page_shown' );
delete_option( 'prdd_lite_pro_welcome_page_shown_time' );
delete_option( 'prdd_lite_installed' );
delete_option( 'prdd_lite_language' );
delete_option( 'prdd_lite_date_format' );
delete_option( 'prdd_lite_months' );
delete_option( 'prdd_lite_calendar_day' );
delete_option( 'prdd_lite_theme' );
delete_option( 'prdd_lite_global_holidays' );
delete_option( 'prdd_lite_enable_rounding' );
