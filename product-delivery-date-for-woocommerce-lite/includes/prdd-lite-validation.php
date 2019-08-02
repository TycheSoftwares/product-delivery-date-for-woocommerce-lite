<?php

/**
 * PRDDD Availability check on product page
 *
 * @author Tyche Softwares
 * @package Product-Delivery-Date-Lite/Frontend
 * @since 2.0
 */

/**
 * Availability check on product page class 
 *
 * @since 2.0
 */

class prdd_lite_validation {

	/**
     * This functions validates the Availability for the selected date and timeslots.
     *
     * @hook woocommerce_add_to_cart_validation
     * 
     * @param bool $passed 
     * @param int $product_id Product ID.
     * @param int $qty 
     *
     * @return bool True if product should be added to cart, else false.
     * @since 2.0
     */
	public static function prdd_lite_get_validate_add_cart_item( $passed, $product_id, $qty ) {

        $duplicate_of = prdd_lite_common::prdd_lite_get_product_id( $product_id );

		$prdd_settings                 = get_post_meta( $duplicate_of, '_woo_prdd_lite_enable_delivery_date' , true );
        $prdd_delivery_field_mandatory = get_post_meta( $duplicate_of, '_woo_prdd_lite_delivery_field_mandatory', true ); 
                        
        if ( ( ( isset( $_POST[ 'prdd_lite_hidden_date' ] ) && 
            $_POST[ 'prdd_lite_hidden_date' ] == "" ) || 
            !isset( $_POST[ 'prdd_lite_hidden_date' ] ) ) && 
            $prdd_delivery_field_mandatory == "on" && 
            $prdd_settings == 'on' ) {
            
                wc_add_notice( "<b>" . __( "Delivery Date", "woocommerce-prdd-lite" ) . "</b>" . __( " is a required field.", "woocommerce-prdd-lite" ), "error" );

                $passed = false;
		} else {
			$passed = true;
		}
		return $passed;
	}
}
?>
