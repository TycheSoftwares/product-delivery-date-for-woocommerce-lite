<?php
/**
 * Product Delivery Date Product Settings
 *
 * @author Tyche Softwares
 * @package Product-Delivery-Date-Lite/Admin/Settings
 * @since 1.0
 * @category Classes
 */

/**
 * Meta Box Class.
 */
class Prdd_Lite_Meta_Box_Class {

	/**
	 * This function adds a meta box for delivery settings on product page.
	 *
	 * @hook add_meta_boxes
	 *
	 * @since 1.0
	 */
	public static function prdd_lite_box() {
		add_meta_box(
			'woocommerce-prdd-lite',
			__( 'Product Delivery Date', 'woocommerce-prdd-lite' ),
			array( 'Prdd_Lite_Meta_Box_Class', 'prdd_lite_meta_box' ),
			'product',
			'normal',
			'core'
		);
	}

	/**
	 * Display the settings in vertical tabs
	 *
	 * @since 1.0
	 */
	public static function prdd_lite_print_js() {
		if ( 'product' === get_post_type() ) {
			?>
			<script type="text/javascript">
			jQuery( document ).ready( function () {
				jQuery( "#tabbed-nav" ).zozoTabs({
					theme: "silver",
					orientation: "vertical",
					position: "top-left",
					size: "medium",
						animation: {
						easing: "easeInOutExpo",
						duration: 400,
						effects: "slideV"
					},
				});
			});
			</script>
			<?php
		}
	}

	/**
	 * This function displays the settings for the product in the Product Delivery Date meta box on the admin product page.
	 *
	 * @since 1.0
	 */
	public static function prdd_lite_meta_box() {
		global $post;
		$duplicate_of = Prdd_Lite_Common::prdd_lite_get_product_id( $post->ID );
		wc_get_template(
			'prdd-lite-delivery-settings-meta-box.php',
			array(
				'duplicate_of' => $duplicate_of,
				'is_product'   => 'yes',
			),
			'product-delivery-date-for-woocommerce-lite/',
			PRDD_LITE_DELIVERIES_TEMPLATE_PATH
		);

		do_action( 'prdd_lite_add_meta_footer' );
	}

	/**
	 * This function updates the delivery settings for each product in the wp_postmeta table of the database. It will be called when update/publish button clicked on admin side.
	 *
	 * @hook woocommerce_process_product_meta
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post WP_Post object.
	 * @since 1.0
	 */
	public static function prdd_lite_process_box( $post_id, $post ) {
		$duplicate_of = Prdd_Lite_Common::prdd_lite_get_product_id( $post_id );

		$enable_date                        = '';
		$prdd_minimum_delivery_time         = '';
		$prdd_maximum_number_days           = '';
		$prdd_lite_delivery_field_mandatory = '';
		$prdd_lite_holidays                 = '';

		if ( isset( $_POST['prdd_lite_enable_date'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			$enable_date = sanitize_text_field( wp_unslash( $_POST['prdd_lite_enable_date'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
		}

		if ( isset( $_POST['prdd_lite_minimum_delivery_time'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			$prdd_minimum_delivery_time = sanitize_text_field( wp_unslash( $_POST['prdd_lite_minimum_delivery_time'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
		}

		if ( isset( $_POST['prdd_lite_maximum_number_days'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			$prdd_maximum_number_days = sanitize_text_field( wp_unslash( $_POST['prdd_lite_maximum_number_days'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
		}

		if ( isset( $_POST['prdd_lite_delivery_field_mandatory'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			$prdd_lite_delivery_field_mandatory = sanitize_text_field( wp_unslash( $_POST['prdd_lite_delivery_field_mandatory'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
		}

		if ( isset( $_POST['prdd_lite_product_holiday'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			$prdd_lite_holidays = sanitize_text_field( wp_unslash( $_POST['prdd_lite_product_holiday'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
		}

		// sanitize the weekday values.
		$_delivery_days = isset( $_POST['prdd_lite_delivery_days'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['prdd_lite_delivery_days'] ) ) : array(); // phpcs:ignore WordPress.Security.NonceVerification

		update_post_meta( $duplicate_of, '_woo_prdd_lite_enable_delivery_date', $enable_date );
		update_post_meta( $duplicate_of, '_woo_prdd_lite_minimum_delivery_time', $prdd_minimum_delivery_time );
		update_post_meta( $duplicate_of, '_woo_prdd_lite_maximum_number_days', $prdd_maximum_number_days );
		update_post_meta( $duplicate_of, '_woo_prdd_lite_delivery_days', $_delivery_days );
		update_post_meta( $duplicate_of, '_woo_prdd_lite_delivery_field_mandatory', $prdd_lite_delivery_field_mandatory );
		update_post_meta( $duplicate_of, '_woo_prdd_lite_holidays', $prdd_lite_holidays );
	}

	/**
	 * This function duplicates the delivery settings of the original product to the new product.
	 *
	 * @hook woocommerce_duplicate_product
	 *
	 * @param int     $new_id new post ID.
	 * @param WP_Post $post Post object.
	 * @since 1.0
	 */
	public function prdd_lite_product_duplicate( $new_id, $post ) {
		global $wpdb;
		$old_id        = $post->ID;
		$prdd_settings = get_post_meta( $old_id, '_woo_prdd_lite_enable_delivery_date', true );
		update_post_meta( $new_id, '_woo_prdd_lite_enable_delivery_date', $prdd_settings );

		$prdd_minimum_delivery_time = get_post_meta( $old_id, '_woo_prdd_lite_minimum_delivery_time', true );
		update_post_meta( $new_id, '_woo_prdd_lite_minimum_delivery_time', $prdd_minimum_delivery_time );

		$prdd_maximum_number_days = get_post_meta( $old_id, '_woo_prdd_lite_maximum_number_days', true );
		update_post_meta( $new_id, '_woo_prdd_lite_maximum_number_days', $prdd_maximum_number_days );

		$prdd_lite_delivery_field_mandatory = get_post_meta( $old_id, '_woo_prdd_lite_delivery_field_mandatory', true );
		update_post_meta( $new_id, '_woo_prdd_lite_delivery_field_mandatory', $prdd_lite_delivery_field_mandatory );

		$prdd_lite_holidays = get_post_meta( $old_id, '_woo_prdd_lite_holidays', true );
		update_post_meta( $new_id, '_woo_prdd_lite_holidays', $prdd_lite_holidays );
	}
}//end class
?>
