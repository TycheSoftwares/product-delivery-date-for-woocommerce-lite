<?php
/**
 * PRDDD Lite Common Functions
 *
 * @author Tyche Softwares
 * @package Product-Delivery-Date-Lite/Common-Functions
 * @since 1.0
 */

/**
 * Common functions class
 *
 * @since 1.0
 */
class Prdd_Lite_Common {

	/**
	 * This function returns the product ID from WP_Post table.
	 *
	 * @param int $product_id - Product ID.
	 * @return int $product_id product ID
	 * @since 1.0
	 */
	public static function prdd_lite_get_product_id( $product_id ) {
		global $wpdb;
		$duplicate_of = get_post_meta( $product_id, '_icl_lang_duplicate_of', true );
		if ( '' === $duplicate_of || null === $duplicate_of ) {
			$duplicate_of = $product_id;
			$post_time    = get_post( $product_id );
			if ( isset( $post_time->post_date ) ) {
				$results_post_id = $wpdb->get_results( $wpdb->prepare( 'SELECT ID FROM `' . $wpdb->prefix . 'posts` WHERE post_date = %s ORDER BY ID LIMIT 1', $post_time->post_date ) ); // WPCS: db call ok, WPCS: cache ok.
				if ( isset( $results_post_id ) ) {
					$duplicate_of = $results_post_id[0]->ID;
				}
			}
		}
		return $duplicate_of;
	}
}
