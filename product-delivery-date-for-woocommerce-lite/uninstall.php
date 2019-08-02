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
    // get main site's table prefix
    $blog_list = wp_get_sites();
    foreach( $blog_list as $blog_list_key => $blog_list_value ) {
    	if( $blog_list_value[ 'blog_id' ] > 1 ) {
			$sub_site_prefix = $wpdb->prefix . $blog_list_value[ 'blog_id' ]."_";
            
            //Enable Delivery Date Checkbox
            $sql_table_post_meta = "DELETE FROM `" . $sub_site_prefix . "postmeta` WHERE meta_key='_woo_prdd_lite_enable_delivery_date'";    
            $wpdb->query( $sql_table_post_meta );
            
            //Minimum Delivery Date 
            $sql_table_post_meta_min_delivery_time = "DELETE FROM `" . $sub_site_prefix . "postmeta` WHERE meta_key='_woo_prdd_lite_minimum_delivery_time'";    
            $wpdb->query( $sql_table_post_meta_min_delivery_time );
            
            //Number of Dates to Choose
            $sql_table_post_meta_max_number_days = "DELETE FROM `" . $sub_site_prefix . "postmeta` WHERE meta_key='_woo_prdd_lite_maximum_number_days'";    
            $wpdb->query( $sql_table_post_meta_max_number_days );

            // Delivery Days
            $sql_table_post_meta_max_number_days = "DELETE FROM `" . $sub_site_prefix . "postmeta` WHERE meta_key='_woo_prdd_lite_delivery_days'";    
            $wpdb->query( $sql_table_post_meta_max_number_days );

            // Mandatory Fields
            $sql_table_post_meta_max_number_days = "DELETE FROM `" . $sub_site_prefix . "postmeta` WHERE meta_key='_woo_prdd_lite_delivery_field_mandatory'";    
            $wpdb->query( $sql_table_post_meta_max_number_days );

            // Product Level Holidays
            $sql_table_post_meta_max_number_days = "DELETE FROM `" . $sub_site_prefix . "postmeta` WHERE meta_key='_woo_prdd_lite_holidays'";    
            $wpdb->query( $sql_table_post_meta_max_number_days );
        }
    }
} else {

    // Product Settings
    delete_post_meta_by_key( '_woo_prdd_lite_enable_delivery_date' );
    delete_post_meta_by_key( '_woo_prdd_lite_minimum_delivery_time' );
    delete_post_meta_by_key( '_woo_prdd_lite_maximum_number_days' );
    delete_post_meta_by_key( '_woo_prdd_lite_delivery_days' );
    delete_post_meta_by_key( '_woo_prdd_lite_delivery_field_mandatory' );
    delete_post_meta_by_key( '_woo_prdd_lite_holidays' );
}

// Options Data
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
