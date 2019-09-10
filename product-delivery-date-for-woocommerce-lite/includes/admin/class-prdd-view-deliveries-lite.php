<?php
/**
 * Product Delivery Date View Deliveries Menu
 *
 * @author Tyche Softwares
 * @package Product-Delivery-Date-Lite/Admin/View-Deliveries
 * @since 2.0
 * @category Classes
 */

/**
 * Global Deliveries View.
 */
class Prdd_View_Deliveries_Lite {

	/**
	 * Add a page in the global menu called 'View Deliveries' and displays all the deliveries
	 *
	 * @since 1.0
	 */
	public static function prdd_lite_woocommerce_prdd_history_page() {
		if ( isset( $_GET['action'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			$action = sanitize_text_field( wp_unslash( $_GET['action'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
		} else {
			$action = '';
		}

		if ( 'history' === $action || '' === $action ) {
			$active_settings = 'nav-tab-active';
		}

		if ( 'history' === $action || '' === $action ) {
			global $wpdb;
			include_once 'class-prdd-lite-view-deliveries-table.php';
			$prdd_table = new PRDD_Lite_View_Deliveries_Table();
			$prdd_table->prdd_lite_prepare_items();

			$lite_history_page = admin_url( 'admin.php?page=woocommerce_prdd_lite_history_page' );
			?>
			<div class="wrap">
				<h2><?php esc_html_e( 'All Deliveries', 'woocommerce-prdd-lite' ); ?></h2>

				<?php do_action( 'prdd_lite_page_top' ); ?>
				<form id="prdd-lite-view-deliveries" method="get" action="<?php esc_attr( $lite_history_page ); ?>">

					<p id="prdd_lite_add_order">
						<button class="button-secondary" disabled>
							<?php esc_html_e( 'Calendar View', 'woocommerce-prdd-lite' ); ?>
						</button>

						<b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro</a></i></b>

						<?php
						if ( ! isset( $_GET['prdd_view'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
							?>

							<button style="float:right;" class="button-secondary" disabled><?php esc_html_e( 'Print', 'woocommerce-prdd-lite' ); ?></button>
							<button style="float:right;" class="button-secondary" disabled><?php esc_html_e( 'CSV', 'woocommerce-prdd-lite' ); ?></button>
							<br>
							<span style="float:right;">
								<b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro</a></i></b>&nbsp;
							</span>
						<?php } ?>
					</p>

					<input type="hidden" name="page" value="woocommerce_prdd_lite_history_page" />
					<?php
						$prdd_table->views();
						$prdd_table->display();
					?>
				</form>
				<?php do_action( 'prdd_lite_page_bottom' ); ?>
			</div>
			<?php
		}
	}
}
$view_deliveries_lite = new Prdd_View_Deliveries_Lite();
