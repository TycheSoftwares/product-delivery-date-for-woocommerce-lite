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
		}
		return $duplicate_of;
	}
}
