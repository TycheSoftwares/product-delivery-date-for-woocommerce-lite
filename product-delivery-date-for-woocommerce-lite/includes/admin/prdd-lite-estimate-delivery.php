<?php

/**
 * PRDDD Display estimated delivery details on product page
 *
 * @author Tyche Softwares
 * @package Product-Delivery-Date-Lite/Frontend
 * @since 2.4
 */

/**
 * Estimated delivery date feature class
 *
 * @since 2.0
 */
class prdd_lite_estimate_delivery {

    /**
     * Default Constructor
     * 
     * @since 2.4
     */
	public function __construct() {
		add_action( 'prdd_lite_before_method_select', array( &$this, 'prdd_lite_before_method_select' ), 10, 1 );
	}

    /**
     * Estimated delivery date feature settings on edit product page.
     * 
     * @hook prdd_before_method_select
     * 
     * @param int $product_id Product ID
     *
     * @since 2.0
     */

	public function prdd_lite_before_method_select( $product_id ) {
        ?>
        <tr class="prdd_lite_delivery_options">
            <th>
                <?php _e( 'Delivery Option:', 'woocommerce-prdd' ); ?>
            </th>
            <td style="width:380px">
                <input type="radio" name="prdd_delivery_options" id="prdd_delivery_options" value="delivery_calendar" checked disabled readonly /><?php _e( 'Calendar', 'woocommerce-prdd-lite' ) ;?> </input>
               <input type="radio" name="prdd_delivery_options" id="prdd_delivery_options" value="text_block" disabled readonly/><?php _e( 'Text block', 'woocommerce-prdd-lite' ) ;?> </input>
               <br>
               <b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>
            </td>
            <td>
                <img class="help_tip" width="16" height="16" data-tip="<?php _e( 'Choose the delivery date option to be displayed on the product page.', 'woocommerce-prdd-lite' ); ?>" src="<?php echo plugins_url() ;?>/woocommerce/assets/images/help.png"/>
    		</td>
		</tr>
        <?php
	}
}

$prdd_lite_estimate_delivery = new prdd_lite_estimate_delivery();