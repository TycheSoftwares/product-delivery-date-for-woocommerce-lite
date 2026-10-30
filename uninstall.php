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

/**
 * Deletes the data of the plugin on the current site, when the site enabled
 * deleting it.
 */
function prdd_lite_uninstall_site() {
	global $wpdb;

	if ( 'on' !== get_option( 'prdd_lite_enable_delete_order_item', '' ) ) {
		return;
	}

	// Product Settings.
	delete_post_meta_by_key( '_woo_prdd_lite_enable_delivery_date' );
	delete_post_meta_by_key( '_woo_prdd_lite_minimum_delivery_time' );
	delete_post_meta_by_key( '_woo_prdd_lite_maximum_number_days' );
	delete_post_meta_by_key( '_woo_prdd_lite_delivery_days' );
	delete_post_meta_by_key( '_woo_prdd_lite_delivery_field_mandatory' );
	delete_post_meta_by_key( '_woo_prdd_lite_holidays' );

	// Delivery dates of the order items.
	$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}woocommerce_order_itemmeta WHERE meta_key = %s", '_prdd_lite_date' ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

	// Options Data.
	$options = array(
		'woocommerce_prdd_lite_db_version',
		'prdd_lite_allow_tracking',
		'prdd_lite_pro_welcome_page_shown',
		'prdd_lite_pro_welcome_page_shown_time',
		'prdd_lite_installed',
		'prdd_lite_language',
		'prdd_lite_date_format',
		'prdd_lite_months',
		'prdd_lite_calendar_day',
		'prdd_lite_theme',
		'prdd_lite_global_holidays',
		'prdd_lite_enable_rounding',
		'prdd_lite_time_format',
		'prdd_add_to_calendar',
		'prdd_add_to_email',
		'prdd_global_selection',
		'prdd_availability_display',
		'prdd_disable_price_calculation_on_dates',
		'prdd_enable_delivery_edit',
		'prdd_enable_delivery_reschedule',
		'prdd_delivery_reschedule_days',
		'prdd_lite_enable_delete_order_item',
		'prdd_is_data_migrated',
	);
	foreach ( $options as $option ) {
		delete_option( $option );
	}
}

if ( is_multisite() ) {
	foreach ( get_sites( array( 'fields' => 'ids', 'number' => 0 ) ) as $prdd_lite_blog_id ) {
		switch_to_blog( $prdd_lite_blog_id );
		prdd_lite_uninstall_site();
		restore_current_blog();
	}
} else {
	prdd_lite_uninstall_site();
}
