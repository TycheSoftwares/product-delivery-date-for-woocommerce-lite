<?php
/**
 * Product Delivery Date for WooCommerce - Lite
 *
 * When Product Delivery Date Lite plugin uninstalls, it will delete all settings & tables for the plugin. 
 *
 * @author   Tyche Softwares
 * @package  Product-delivery-date-lite/Uninstall
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
            $wpdb->get_results( $sql_table_post_meta );
            //Minimum Delivery Date 
            $sql_table_post_meta_min_delivery_time = "DELETE FROM `" . $sub_site_prefix . "postmeta` WHERE meta_key='_woo_prdd_lite_minimum_delivery_time'";    
            $wpdb->get_results( $sql_table_post_meta_min_delivery_time );
            //Number of Dates to Choose
            $sql_table_post_meta_max_number_days = "DELETE FROM `" . $sub_site_prefix . "postmeta` WHERE meta_key='_woo_prdd_lite_maximum_number_days'";    
            $wpdb->get_results( $sql_table_post_meta_max_number_days );
        }
    }
} else {
	$sql_table_post_meta = "DELETE FROM `" . $wpdb->prefix . "postmeta` WHERE meta_key='_woo_prdd_lite_enable_delivery_date'";    
    $wpdb->get_results( $sql_table_post_meta );
    //Minimum Delivery Date 
    $sql_table_post_meta_min_delivery_time = "DELETE FROM `" . $wpdb->prefix . "postmeta` WHERE meta_key='_woo_prdd_lite_minimum_delivery_time'";    
    $wpdb->get_results( $sql_table_post_meta_min_delivery_time );
    //Number of Dates to Choose
    $sql_table_post_meta_max_number_days = "DELETE FROM `" . $wpdb->prefix . "postmeta` WHERE meta_key='_woo_prdd_lite_maximum_number_days'";    
    $wpdb->get_results( $sql_table_post_meta_max_number_days );
}