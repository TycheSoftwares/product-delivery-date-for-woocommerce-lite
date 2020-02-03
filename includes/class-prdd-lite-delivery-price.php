<?php
/**
 * PRDD Delivery charges for weekdays/dates.
 *
 * @author  Tyche Softwares
 * @package Product-Delivery-Date-Lite/Delivery-Charges
 * @since   2.0
 */

/**
 * Localization
 */
load_plugin_textdomain( 'prdd_lite_delivery_price', false, dirname( plugin_basename( __FILE__ ) ) . '/' );

if ( ! class_exists( 'Prdd_Lite_Delivery_Price' ) ) {

	/**
	 * Weekday delivery charges class.
	 *
	 * @since 1.0
	 */
	class Prdd_Lite_Delivery_Price {

		/**
		 * Default constructor
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_action( 'prdd_lite_add_tabs', array( &$this, 'prdd_lite_delivery_price_tab' ), 4, 1 );
			add_action( 'prdd_lite_after_listing_enabled', array( &$this, 'prdd_lite_delivery_price_show_field_settings' ), 4, 1 );
		}

		/**
		 * Adds Delivery charges tab on edit product page in admin.
		 *
		 * @hook prdd_add_tabs
		 *
		 * @param int|string $product_id Product ID.
		 *
		 * @since 1.0
		 */
		public function prdd_lite_delivery_price_tab( $product_id ) {
			?>
			<li><a id="delivery-charges"> <?php esc_html_e( 'Delivery Charges', 'woocommerce-prdd-lite' ); ?> </a></li>
			<?php
		}

		/**
		 * Adds the Add Delivery charges fields on the Delivery Charges tab.
		 *
		 * @hook prdd_after_listing_enabled
		 *
		 * @globals resource $wpdb WordPress object.
		 * @globals resource $post WordPress post object.
		 *
		 * @param int|string $product_id Product ID.
		 *
		 * @since 2.0
		 */
		public function prdd_lite_delivery_price_show_field_settings( $product_id ) {
			global $post, $wpdb;
			$plugins_url = plugins_url();
			?>
			<div id="special_delivery_page">
				<table class='form-table'>
					<tr id="special_delivery_price_row">
						<th width="35%">
							<label for="special_delivery_price_block"><?php esc_html_e( 'Select Days:', 'woocommerce-prdd' ); ?></label>
						</th>
						<td width="5%">&nbsp;</td>
						<th style="width:175px;">
							<label for="special_delivery_date"><?php esc_html_e( 'Select Date:', 'woocommerce-prdd' ); ?></label>
						</th>
						<td width="1%">&nbsp;</td>
						<th width="20%">
							<label for="special_delivery_price"><?php esc_html_e( 'Delivery Charges:', 'woocommerce-prdd' ); ?></label>
						</th>
					</tr>
					<tr>
						<td>
							<select name="special_delivery_weekday" id="special_delivery_weekday" disabled readonly>
								<option value=""><?php esc_html_e( 'Select Weekday', 'woocommerce-prdd-lite' ); ?></option>
							</select>
							<img class="help_tip" width="16" height="16" data-tip="<?php esc_attr_e( 'Select weekday to set delivery charge for', 'woocommerce-prdd-lite' ); ?>" src="<?php esc_attr( $plugins_url ); ?>/woocommerce/assets/images/help.png"/>
						</td>
						<td width="5%">&nbsp;</td>
						<td>
							<input type="text" name="special_delivery_date" id="special_delivery_date" disabled eadonly style="background-color: white;width: 100px;"> 
							<img class="help_tip" width="16" height="16" data-tip="<?php esc_attr_e( 'Select date to set delivery charge for', 'woocommerce-prdd-lite' ); ?>" src="<?php esc_attr( $plugins_url ); ?>/woocommerce/assets/images/help.png"/>
						</td>
						<td width="1%">&nbsp;</td>
						<td>
							<input type="text" name="special_delivery_price" id="special_delivery_price" disabled readonly style="width: 75px;"> 
							<img class="help_tip" width="16" height="16" data-tip="<?php esc_attr_e( 'Set delivery charge for the selected day / date', 'woocommerce-prdd-lite' ); ?>" src="<?php esc_attr( $plugins_url ); ?>/woocommerce/assets/images/help.png"/>
						</td>
					</tr>
					<tr>
						<td>
							<input type="button" class="button-primary" value="<?php esc_attr_e( 'Add Delivery Charges', 'woocommerce-prdd' ); ?>" id="save_special_delivery" disabled readonly></input>
							<input type="button" class="button-primary" value="Cancel" id="cancel_special_delivery" onclick="prdd_cancel_special_delivery_update()" style="display:none;"></input>
						</td>
						<td colspan="4">
							<input type="hidden" name="prdd_special_delivery_id" id="prdd_special_delivery_id">
						</td>
					</tr>					
				</table>
				<b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable adding delivery charges for weekdays.</i></b>
			</div>
			<?php
		}
	} // EOF Class
} // EOF if class exist
$prdd_lite_delivery_price = new Prdd_Lite_Delivery_Price();
?>
