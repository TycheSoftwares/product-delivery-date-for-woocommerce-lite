<?php
/**
 * Product Delivery Date Lite Global Settings
 *
 * @author Tyche Softwares
 * @package Product-Delivery-Date-Pro-for-WooCommerce
 * @since 1.7
 */

/**
 * Include the files needed.
 */
require_once 'class-prdd-lite-delivery-settings.php';
require_once 'class-prdd-view-deliveries-lite.php';

/**
 * Global Menu
 */
class PRDD_Lite_Global_Menu {

	/**
	 * Add Menu page
	 */
	public static function prdd_lite_admin_menu() {
		add_menu_page( 'Product Delivery Date', 'Product Delivery Date', 'manage_woocommerce', 'woocommerce_prdd_lite_page', array( 'PRDD_Lite_Global_Menu', 'prdd_lite_global_menu_page' ) );

		$page = add_submenu_page( 'woocommerce_prdd_lite_page', __( 'View Deliveries', 'woocommerce-prdd-lite' ), __( 'View Deliveries', 'woocommerce-prdd-lite' ), 'manage_woocommerce', 'woocommerce_prdd_lite_history_page', array( 'Prdd_View_Deliveries_Lite', 'prdd_lite_woocommerce_prdd_history_page' ) );

		$page = add_submenu_page( 'woocommerce_prdd_lite_page', __( 'Settings', 'woocommerce-prdd-lite' ), __( 'Settings', 'woocommerce-prdd-lite' ), 'manage_woocommerce', 'woocommerce_prdd_lite_page', array( 'PRDD_Lite_Global_Menu', 'prdd_lite_global_menu_page' ) );

		remove_submenu_page( 'woocommerce_prdd_lite_page', 'woocommerce_prdd_lite_page' );
		do_action( 'prdd_lite_add_submenu' );

		if ( isset( $_POST['option_page'] ) && 'prdd_lite_settings' === $_POST['option_page'] ) {
			$woocommerce_prdd_global_settings                         = array();
			$woocommerce_prdd_global_settings['prdd_language']        = $_POST['prdd_lite_language'];
			$woocommerce_prdd_global_settings['prdd_date_format']     = $_POST['prdd_lite_date_format'];
			$woocommerce_prdd_global_settings['prdd_months']          = $_POST['prdd_lite_months'];
			$woocommerce_prdd_global_settings['prdd_calendar_day']    = $_POST['prdd_lite_calendar_day'];
			$woocommerce_prdd_global_settings['prdd_themes']          = $_POST['prdd_lite_theme'];
			$woocommerce_prdd_global_settings['prdd_global_holidays'] = $_POST['prdd_lite_global_holidays'];
			$woocommerce_prdd_global_settings['prdd_enable_rounding'] = isset( $_POST['prdd_lite_enable_rounding'] ) ? $_POST['prdd_lite_enable_rounding'] : '';
			update_option( 'woocommerce_prdd_global_settings', wp_json_encode( $woocommerce_prdd_global_settings ) );
		}
	}

	/**
	 * Global Menu Page
	 */
	public static function prdd_lite_global_menu_page() {
		if ( isset( $_GET['action'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification
			$action = sanitize_text_field( wp_unslash( $_GET['action'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
		} else {
			$action = '';
		}

		$active_settings              = '';
		$active_labels                = '';
		$active_google_sync           = '';
		$active_bulk_product_settings = '';

		switch ( $action ) {
			case 'settings':
			case '':
				$active_settings = 'nav-tab-active';
				break;
			case 'labels':
				$active_labels = 'nav-tab-active';
				break;
			case 'prdd_google_calendar_sync':
				$active_google_sync = 'nav-tab-active';
				break;
			case 'bulk_product_settings':
				$active_bulk_product_settings = 'nav-tab-active';
				break;
			default:
				$active_settings = '';
				break;
		}

		settings_errors();
		?>
		<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
			<a href="admin.php?page=woocommerce_prdd_lite_page&action=settings" class="nav-tab <?php echo esc_attr( $active_settings ); ?>"> <?php esc_html_e( 'Global Delivery Settings', 'woocommerce-prdd' ); ?> </a>
			<a href="admin.php?page=woocommerce_prdd_lite_page&action=labels" class="nav-tab <?php echo esc_attr( $active_labels ); ?>"> <?php esc_html_e( 'Field Labels', 'woocommerce-prdd' ); ?> </a>
			<a href="admin.php?page=woocommerce_prdd_lite_page&action=prdd_google_calendar_sync" class="nav-tab <?php echo esc_attr( $active_google_sync ); ?>"> <?php esc_html_e( 'Google Calendar Sync', 'woocommerce-prdd' ); ?> </a>
			<?php do_action( 'prdd_lite_add_settings_tab' ); ?>
			<a href="admin.php?page=woocommerce_prdd_lite_page&action=bulk_product_settings" class="nav-tab <?php echo esc_attr( $active_bulk_product_settings ); ?>"> <?php esc_html_e( 'Bulk Product Settings', 'woocommerce-prdd' ); ?> </a>
		</h2>
		<?php
		do_action( 'prdd_lite_add_tab_content' );
		if ( 'labels' === $action ) {
			print( '<div id="content">
                <form method="post" action="options.php">' );
					settings_fields( 'prdd_labels' );
					do_settings_sections( 'prdd_labels_page' );
					submit_button( __( 'Save Settings', 'woocommerce-prdd' ), 'primary', 'save', true );
				print( '</form>
            </div>' );
		}

		if ( 'settings' === $action || '' === $action ) {
			print( '<div id="content">
                <form method="post" action="options.php">' );
					settings_fields( 'prdd_lite_settings' );
					do_settings_sections( 'prdd_lite_settings_page' );
					submit_button( __( 'Save Settings', 'woocommerce-prdd' ), 'primary', 'save', true );
				print( '</form>
            </div>' );
		}

		if ( 'prdd_google_calendar_sync' === $action ) {
			print( '<div id="content">
                <form method="post" action="options.php">' );
					settings_fields( 'ts_google_calendar_sync' );
					do_settings_sections( 'ts_google_calendar_sync_page' );
					submit_button( __( 'Save Settings', 'woocommerce-prdd' ), 'primary', 'save', true );
				print( '</form>
            </div>' );
		}

		if ( 'bulk_product_settings' === $action ) {
			$plugin_version_number = get_option( 'woocommerce_prdd_db_version' );
			wp_register_script( 'select2', plugins_url() . '/woocommerce/assets/js/select2/select2.min.js', array( 'jquery', 'jquery-ui-widget', 'jquery-ui-core' ), $plugin_version_number, false );
			wp_enqueue_script( 'select2' );

			$args    = array(
				'post_type'      => array( 'product' ),
				'posts_per_page' => -1,
				'post_status'    => array( 'publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash' ),
			);
			$product = get_posts( $args );

			?>
			<div class="wrap">
				<h5>Add delivery settings for multiple products together. Selected settings will be shown on the selected edit product place.</h5>
				<b><i>Upgrade to <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date Pro for WooCommerce</a> to enable the setting.</i></b>
				<form method="post" action="<?php echo esc_attr( get_admin_url() ); ?>/admin.php?page=woocommerce_prdd_page&action=bulk_product_settings&action_type=save" style="opacity:0.5">
					<div id="prdd_product_list">
						<table class="form-table">
							<tr>
								<th>
									<label for="prdd_product_list"> <?php esc_html_e( 'Products:', 'woocommerce-prdd-lite' ); ?> </label>
								</th>
								<td style="width:fit-content" >
									<select id="prdd_products" name="prdd_products[]" class="chosen_select" style="width: 300px" multiple="multiple" placeholder="Select a Product" disabled>
										<option value="all_products"><?php esc_html_e( 'All Products', 'woocommerce-prdd-lite' ); ?></option>
										<?php
										foreach ( $product as $pkey => $pval ) {
											printf( esc_html( "<option value='%s'>%s</option>" ), esc_attr( $pval->ID ), esc_attr( $pval->post_title ) );
										}
										?>
									</select>
								</td>
							</tr>
						</table>
					</div>
					<script type="text/javascript">
					jQuery( document ).ready( function ($) {
						jQuery( ".chosen_select" ).select2();
						$('.tstab-content').wrapInner('<div class="tstab-content-inner"></div>');
                        $(document).on('click', '.tstab-tab', function(){
                            data_link = $(this).data("link");
                            cur_data_link = $('.tstab-tab.tstab-active').data("link");
                            if ( cur_data_link !== data_link ) {
                                $('.tstab-content').removeClass('tstab-active').hide();
                                $("#"+data_link).addClass('tstab-active').css('position', 'relative').fadeIn('slow');
                                $('.tstab-tab').removeClass('tstab-active');
                                $(this).addClass('tstab-active');
                            }
                        });

						jQuery("#tabbed-nav li").addClass("z-disabled");
					});
					</script>
					<div id="prdd_product_settings" >
						<?php
						wc_get_template(
							'prdd-lite-delivery-settings-meta-box.php',
							array(
								'prdd_settings' => array(),
								'duplicate_of'  => '',
								'is_product'    => 'no',
							),
							'product-delivery-date-for-woocommerce-lite/',
							PRDD_LITE_DELIVERIES_TEMPLATE_PATH
						);
						?>
					</div> 
					<?php
					submit_button( __( 'Save Settings', 'woocommerce-prdd' ), 'primary', 'save', true );
					?>
				</form>
			</div>
			<?php
		}
	}

	/**
	 * Plugin Settings.
	 */
	public static function prdd_lite_delivery_settings() {

		add_settings_section(
			'prdd_lite_delivery_settings_section',      // ID used to identify this section and with which to register options.
			__( 'Settings', 'woocommerce-prdd' ),       // Title to be displayed on the administration page.
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_lite_delivery_settings_section_callback' ),     // Callback used to render the description of the section.
			'prdd_lite_settings_page'               // Page on which to add this section of options.
		);

		add_settings_field(
			'prdd_lite_language',
			__( 'Language', 'woocommerce-prdd' ),
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_lite_language_callback' ),
			'prdd_lite_settings_page',
			'prdd_lite_delivery_settings_section',
			array( __( 'Choose the language for your delivery calendar.', 'woocommerce-prdd' ) )
		);

		add_settings_field(
			'prdd_lite_date_format',
			__( 'Date Format', 'woocommerce-prdd' ),
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_lite_date_format_callback' ),
			'prdd_lite_settings_page',
			'prdd_lite_delivery_settings_section',
			array( __( 'The format in which the delivery date appears to the customers on the product page once the date is selected.', 'woocommerce-prdd' ) )
		);

		add_settings_field(
			'prdd_lite_months',
			__( 'Number of months to show in calendar', 'woocommerce-prdd' ),
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_lite_months_callback' ),
			'prdd_lite_settings_page',
			'prdd_lite_delivery_settings_section',
			array( __( 'The number of months to be shown on the calendar. If the delivery dates spans across 2 months, then dates of 2 months can be shown simultaneously without the need to press Next or Back buttons.', 'woocommerce-prdd' ) )
		);

		add_settings_field(
			'prdd_lite_calendar_day',
			__( 'First Day on Calendar', 'woocommerce-prdd' ),
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_lite_calendar_day_callback' ),
			'prdd_lite_settings_page',
			'prdd_lite_delivery_settings_section',
			array( __( 'Choose the first day of week displayed on the Delivery Date calendar.', 'woocommerce-prdd' ) )
		);

		add_settings_field(
			'prdd_lite_theme',
			__( 'Preview Theme & Language', 'woocommerce-prdd' ),
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_lite_theme_callback' ),
			'prdd_lite_settings_page',
			'prdd_lite_delivery_settings_section',
			array( __( 'Select the theme for the calendar. You can choose a theme which blends with the design of your website.', 'woocommerce-prdd' ) )
		);

		add_settings_field(
			'prdd_lite_global_holidays',
			__( 'No delivery on these dates', 'woocommerce-prdd' ),
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_lite_global_holidays_callback' ),
			'prdd_lite_settings_page',
			'prdd_lite_delivery_settings_section',
			array( __( 'Select dates for which the delivery will be completely disabled for all the products in your WooCommerce store. <br> The dates selected here will be unavailable for all products. Please click on the date in calendar to add or delete the date from the list.', 'woocommerce-prdd' ) )
		);

		add_settings_field(
			'prdd_lite_enable_rounding',
			__( 'Enable Rounding of Prices', 'woocommerce-prdd' ),
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_lite_enable_rounding_callback' ),
			'prdd_lite_settings_page',
			'prdd_lite_delivery_settings_section',
			array( __( 'Rounds the Price to the nearest Integer value.', 'woocommerce-prdd' ) )
		);

		add_settings_field(
			'prdd_lite_enable_delete_order_item',
			__( 'Remove Data on Uninstall?', 'woocommerce-prdd' ),
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_lite_enable_delete_order_item_callback' ),
			'prdd_lite_settings_page',
			'prdd_lite_delivery_settings_section',
			array( __( 'Enable this setting if you want to completely remove Product Delivery Date Lite data when plugin is deleted.', 'woocommerce-prdd' ) )
		);

		add_settings_field(
			'prdd_lite_time_format',
			__( 'Time Format', 'woocommerce-prdd' ),
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_lite_time_format_callback' ),
			'prdd_lite_settings_page',
			'prdd_lite_delivery_settings_section',
			array( __( 'The format in which the delivery time appears to the customers on the product page once the date is selected.', 'woocommerce-prdd' ) )
		);

		add_settings_field(
			'prdd_add_to_calendar',
			__( 'Show "Add to Calendar" button on Order Received page', 'woocommerce-prdd' ),
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_add_to_calendar_callback' ),
			'prdd_lite_settings_page',
			'prdd_lite_delivery_settings_section',
			array( __( 'Shows the \'Add to Calendar\' button on the Order Received page. On clicking the button, an ICS file will be downloaded.', 'woocommerce-prdd' ) )
		);

		add_settings_field(
			'prdd_add_to_email',
			__( 'Send delivery information as attachments (ICS files) in email notifications', 'woocommerce-prdd' ),
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_add_to_email_callback' ),
			'prdd_lite_settings_page',
			'prdd_lite_delivery_settings_section',
			array( __( 'Allow customers to export deliveries as ICS file after placing an order. Sends ICS files as attachments in email notifications.', 'woocommerce-prdd' ) )
		);

		add_settings_field(
			'prdd_global_selection',
			__( 'Duplicate dates from first product in the cart to other products:', 'woocommerce-prdd' ),
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_global_selection_callback' ),
			'prdd_lite_settings_page',
			'prdd_lite_delivery_settings_section',
			array( __( 'Please select this checkbox if you want to select the date globally for All products once selected for a product and added to cart.', 'woocommerce-prdd' ) )
		);

		add_settings_field(
			'prdd_availability_display',
			__( 'Enable Availability Display on the Product page:', 'woocommerce-prdd' ),
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_availability_display_callback' ),
			'prdd_lite_settings_page',
			'prdd_lite_delivery_settings_section',
			array( __( 'Please select this checkbox if you want to display the number of deliveries available for a given product on a given date and time.', 'woocommerce-prdd' ) )
		);

		add_settings_field(
			'prdd_disable_price_calculation_on_dates',
			__( 'Apply one-time delivery charges for multiple products:', 'woocommerce-prdd' ),
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_disable_price_calculation_on_dates_callback' ),
			'prdd_lite_settings_page',
			'prdd_lite_delivery_settings_section',
			array( __( 'Select this checkbox to restrict delivery charges to apply only once for multiple products with the same delivery date in the cart.', 'woocommerce-prdd' ) )
		);

		add_settings_field(
			'prdd_enable_delivery_edit',
			__( 'Allow Deliveries to be editable:', 'woocommerce-prdd' ),
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_allow_deliveries_callback' ),
			'prdd_lite_settings_page',
			'prdd_lite_delivery_settings_section',
			array( __( 'Enabling this option will allow Deliveries to be editable from Cart and Checkout page', 'woocommerce-prdd' ) )
		);

		add_settings_field(
			'prdd_enable_delivery_reschedule',
			__( 'Allow Deliveries to be reschedulable:', 'woocommerce-prdd' ),
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_allow_reschedulable_callback' ),
			'prdd_lite_settings_page',
			'prdd_lite_delivery_settings_section',
			array( __( 'Enabling this option will allow Deliveries to be reschedulable from My Account page', 'woocommerce-prdd' ) )
		);

		add_settings_field(
			'prdd_delivery_reschedule_days',
			__( 'Minimum number of days for rescheduling:', 'woocommerce-prdd' ),
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_reschedulable_days_callback' ),
			'prdd_lite_settings_page',
			'prdd_lite_delivery_settings_section',
			array( __( 'Minimum number of days before the delivery date, after which Delivery cannot be rescheduled.', 'woocommerce-prdd' ) )
		);

		register_setting(
			'prdd_lite_settings',
			'prdd_lite_language'
		);

		register_setting(
			'prdd_lite_settings',
			'prdd_lite_date_format'
		);

		register_setting(
			'prdd_lite_settings',
			'prdd_lite_time_format'
		);

		register_setting(
			'prdd_lite_settings',
			'prdd_lite_months'
		);

		register_setting(
			'prdd_lite_settings',
			'prdd_lite_calendar_day'
		);

		register_setting(
			'prdd_lite_settings',
			'prdd_lite_theme'
		);

		register_setting(
			'prdd_lite_settings',
			'prdd_lite_global_holidays'
		);

		register_setting(
			'prdd_lite_settings',
			'prdd_lite_enable_rounding'
		);

		register_setting(
			'prdd_lite_settings',
			'prdd_lite_enable_delete_order_item'
		);

		register_setting(
			'prdd_lite_settings',
			'prdd_add_to_calendar'
		);

		register_setting(
			'prdd_lite_settings',
			'prdd_add_to_email'
		);

		register_setting(
			'prdd_lite_settings',
			'prdd_global_selection'
		);

		register_setting(
			'prdd_lite_settings',
			'prdd_availability_display'
		);

		register_setting(
			'prdd_lite_settings',
			'prdd_disable_price_calculation_on_dates'
		);

		register_setting(
			'prdd_lite_settings',
			'prdd_enable_delivery_edit'
		);

		register_setting(
			'prdd_lite_settings',
			'prdd_enable_delivery_reschedule'
		);

		register_setting(
			'prdd_lite_settings',
			'prdd_delivery_reschedule_days'
		);
	}

	/**
	 * Label Settings
	 */
	public static function prdd_delivery_labels() {
		add_settings_section(
			'prdd_delivery_product_page_labels_section',        // ID used to identify this section and with which to register options.
			__( 'Labels on product page', 'woocommerce-prdd' ),     // Title to be displayed on the administration page.
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_delivery_product_page_labels_section_callback' ),       // Callback used to render the description of the section.
			'prdd_labels_page'              // Page on which to add this section of options.
		);

		add_settings_field(
			'delivery_date-label',
			__( 'Delivery Date:', 'woocommerce-prdd' ),
			array( 'Prdd_Lite_Delivery_Settings', 'delivery_date_label_callback' ),
			'prdd_labels_page',
			'prdd_delivery_product_page_labels_section',
			array( __( 'Delivery Date label on product page.', 'woocommerce-prdd' ) )
		);

		add_settings_field(
			'delivery_time-label',
			__( 'Delivery Time:', 'woocommerce-prdd' ),
			array( 'Prdd_Lite_Delivery_Settings', 'delivery_time_label_callback' ),
			'prdd_labels_page',
			'prdd_delivery_product_page_labels_section',
			array( __( 'Delivery Time label on the product page.', 'woocommerce-prdd' ) )
		);

		add_settings_field(
			'delivery_time-select-option',
			__( 'Choose Time Text:', 'woocommerce-prdd' ),
			array( 'Prdd_Lite_Delivery_Settings', 'delivery_time_select_option_callback' ),
			'prdd_labels_page',
			'prdd_delivery_product_page_labels_section',
			array( __( 'Text for the 1st option of Time Slot dropdown field that instructs the customer to select a time slot.', 'woocommerce-prdd' ) )
		);

		add_settings_section(
			'prdd_delivery_order_received_page_labels_section',     // ID used to identify this section and with which to register options.
			__( 'Labels on order received page and in email notification', 'woocommerce-prdd' ),        // Title to be displayed on the administration page.
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_delivery_order_received_page_labels_section_callback' ),        // Callback used to render the description of the section.
			'prdd_labels_page'              // Page on which to add this section of options.
		);

		add_settings_field(
			'delivery_item-meta-date',
			__( 'Delivery Date:', 'woocommerce-prdd' ),
			array( 'Prdd_Lite_Delivery_Settings', 'delivery_item_meta_date_callback' ),
			'prdd_labels_page',
			'prdd_delivery_order_received_page_labels_section',
			array( __( 'Delivery Date label on the order received page and email notification.', 'woocommerce-prdd' ) )
		);

		add_settings_field(
			'delivery_item-meta-time',
			__( 'Delivery Time:', 'woocommerce-prdd' ),
			array( 'Prdd_Lite_Delivery_Settings', 'delivery_item_meta_time_callback' ),
			'prdd_labels_page',
			'prdd_delivery_order_received_page_labels_section',
			array( __( 'Delivery Time label on the order received page and email notification.', 'woocommerce-prdd' ) )
		);

		add_settings_field(
			'delivery_ics-file-name',
			__( 'ICS File Name:', 'woocommerce-prdd' ),
			array( 'Prdd_Lite_Delivery_Settings', 'delivery_ics_file_name_callback' ),
			'prdd_labels_page',
			'prdd_delivery_order_received_page_labels_section',
			array( __( 'ICS File name.', 'woocommerce-prdd' ) )
		);

		add_settings_field(
			'delivery_item-meta-charges',
			__( 'Delivery Charges:', 'woocommerce-prdd' ),
			array( 'Prdd_Lite_Delivery_Settings', 'delivery_item_meta_charges_callback' ),
			'prdd_labels_page',
			'prdd_delivery_order_received_page_labels_section',
			array( __( 'Delivery Charges label on the order received page and email notification.', 'woocommerce-prdd' ) )
		);

		add_settings_section(
			'prdd_delivery_cart_page_labels_section',       // ID used to identify this section and with which to register options.
			__( 'Labels on Cart & Check-out Page', 'woocommerce-prdd' ),        // Title to be displayed on the administration page.
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_delivery_cart_page_labels_section_callback' ),      // Callback used to render the description of the section.
			'prdd_labels_page'              // Page on which to add this section of options.
		);

		add_settings_field(
			'delivery_item-cart-date',
			__( 'Delivery Date:', 'woocommerce-prdd' ),
			array( 'Prdd_Lite_Delivery_Settings', 'delivery_item_cart_date_callback' ),
			'prdd_labels_page',
			'prdd_delivery_cart_page_labels_section',
			array( __( 'Delivery Date label on the cart and checkout page.', 'woocommerce-prdd' ) )
		);

		add_settings_field(
			'delivery_item-cart-time',
			__( 'Delivery Time:', 'woocommerce-prdd' ),
			array( 'Prdd_Lite_Delivery_Settings', 'delivery_item_cart_time_callback' ),
			'prdd_labels_page',
			'prdd_delivery_cart_page_labels_section',
			array( __( 'Delivery Time label on the cart and checkout page.', 'woocommerce-prdd' ) )
		);

		add_settings_field(
			'delivery_item-cart-charges',
			__( 'Delivery Charges:', 'woocommerce-prdd' ),
			array( 'Prdd_Lite_Delivery_Settings', 'delivery_item_cart_charges_callback' ),
			'prdd_labels_page',
			'prdd_delivery_cart_page_labels_section',
			array( __( 'Delivery Charges label on the cart and checkout page.', 'woocommerce-prdd' ) )
		);

		add_settings_section(
			'prdd_estimate_delivery_section',       // ID used to identify this section and with which to register options.
			__( 'Labels for Estimate Delivery Option', 'woocommerce-prdd' ),        // Title to be displayed on the administration page.
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_estimate_delivery_section_callback' ),        // Callback used to render the description of the section.
			'prdd_labels_page'              // Page on which to add this section of options.
		);

		add_settings_field(
			'prdd_estimate_delivery_header',
			__( 'Estimate Delivery section heading:', 'woocommerce-prdd' ),
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_estimate_delivery_header_callback' ),
			'prdd_labels_page',
			'prdd_estimate_delivery_section'
		);

		add_settings_field(
			'prdd_estimate_delivery_days_text',
			__( 'Estimate Delivery display in days text:', 'woocommerce-prdd' ),
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_estimate_delivery_days_text_callback' ),
			'prdd_labels_page',
			'prdd_estimate_delivery_section',
			array( __( '<br>Use {{business.days}} shortcode to replace it with the number of business days required for delivery', 'woocommerce-prdd' ) )
		);

		add_settings_field(
			'prdd_estimate_delivery_date_text',
			__( 'Estimate Delivery display with specific date text:', 'woocommerce-prdd' ),
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_estimate_delivery_date_text_callback' ),
			'prdd_labels_page',
			'prdd_estimate_delivery_section',
			array( __( '<br>Use {{expected.date}} shortcode to replace it with the expected date of delivery', 'woocommerce-prdd' ) )
		);

		register_setting(
			'prdd_labels',
			'delivery_date-label'
		);

		register_setting(
			'prdd_labels',
			'delivery_time-label'
		);

		register_setting(
			'prdd_labels',
			'delivery_time-select-option'
		);

		register_setting(
			'prdd_labels',
			'delivery_item-meta-date'
		);

		register_setting(
			'prdd_labels',
			'delivery_item-meta-time'
		);

		register_setting(
			'prdd_labels',
			'delivery_ics-file-name'
		);

		register_setting(
			'prdd_labels',
			'delivery_item-meta-charges'
		);

		register_setting(
			'prdd_labels',
			'delivery_item-cart-date'
		);

		register_setting(
			'prdd_labels',
			'delivery_item-cart-time'
		);

		register_setting(
			'prdd_labels',
			'delivery_item-cart-charges'
		);

		register_setting(
			'prdd_labels',
			'prdd_estimate_delivery_header'
		);

		register_setting(
			'prdd_labels',
			'prdd_estimate_delivery_days_text'
		);

		register_setting(
			'prdd_labels',
			'prdd_estimate_delivery_date_text'
		);
	}

	/** Google Sync Settings (only for pro) */

	/**
	 * Add Google Sync Settings tab in Global settings
	 *
	 * @since 2.3
	 */
	public static function prdd_google_calendar_sync_settings() {
		add_settings_section(
			'ts_calendar_sync_general_settings_section',
			__( 'General Settings', 'ts' ),
			array( 'Prdd_Lite_Delivery_Settings', 'ts_calendar_sync_general_settings_callback' ),
			'ts_google_calendar_sync_page'
		);

		add_settings_field(
			'ts_calendar_event_location',
			__( 'Event Location', 'ts' ),
			array( 'Prdd_Lite_Delivery_Settings', 'ts_calendar_event_location_callback' ),
			'ts_google_calendar_sync_page',
			'ts_calendar_sync_general_settings_section',
			array( __( '<br>Enter the text that will be used as location field in event of the Calendar. If left empty, website description is sent instead. <br><i>Note: You can use ADDRESS, FULL_ADDRESS and CITY placeholders which will be replaced by their real values.</i>', 'ts' ) )
		);

		add_settings_field(
			'ts_calendar_event_summary',
			__( 'Event summary (name)', 'ts' ),
			array( 'Prdd_Lite_Delivery_Settings', 'ts_calendar_event_summary_callback' ),
			'ts_google_calendar_sync_page',
			'ts_calendar_sync_general_settings_section'
		);

		add_settings_field(
			'ts_calendar_event_description',
			__( 'Event Description', 'ts' ),
			array( 'Prdd_Lite_Delivery_Settings', 'ts_calendar_event_description_callback' ),
			'ts_google_calendar_sync_page',
			'ts_calendar_sync_general_settings_section',
			array( __( '<br>For the above 2 fields, you can use the following placeholders which will be replaced by their real values:&nbsp;SITE_NAME, CLIENT, PRODUCTS, PRODUCT_WITH_QTY, ORDER_DATE_TIME, ORDER_DATE, ORDER_NUMBER, PRICE, PHONE, NOTE, ADDRESS, FULL_ADDRESS , EMAIL (Client\'s email)	', 'ts' ) )
		);

		add_settings_section(
			'prdd_calendar_sync_customer_settings_section',
			__( 'Customer Add to Calendar button Settings', 'ts' ),
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_calendar_sync_customer_settings_section_callback' ),
			'ts_google_calendar_sync_page'
		);

		add_settings_field(
			'prdd_add_to_calendar_order_received_page',
			__( 'Show Add to Calendar button on Order received page', 'ts' ),
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_add_to_calendar_order_received_page_callback' ),
			'ts_google_calendar_sync_page',
			'prdd_calendar_sync_customer_settings_section',
			array( __( 'Show Add to Calendar button on the Order Received page for the customers.', 'ts' ) )
		);

		add_settings_field(
			'prdd_add_to_calendar_customer_email',
			__( 'Show Add to Calendar button in the Customer notification email', 'ts' ),
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_add_to_calendar_customer_email_callback' ),
			'ts_google_calendar_sync_page',
			'prdd_calendar_sync_customer_settings_section',
			array( __( 'Show Add to Calendar button in the Customer notification email.', 'ts' ) )
		);

		add_settings_field(
			'prdd_add_to_calendar_my_account_page',
			__( 'Show Add to Calendar button on My account', 'ts' ),
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_add_to_calendar_my_account_page_callback' ),
			'ts_google_calendar_sync_page',
			'prdd_calendar_sync_customer_settings_section',
			array( __( 'Show Add to Calendar button on My account page for the customers.', 'ts' ) )
		);

		add_settings_field(
			'prdd_calendar_in_same_window',
			__( 'Open Calendar in Same Window', 'ts' ),
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_calendar_in_same_window_callback' ),
			'ts_google_calendar_sync_page',
			'prdd_calendar_sync_customer_settings_section',
			array( __( 'As default, the Calendar is opened in a new tab or window. If you check this option, user will be redirected to the Calendar from the same page, without opening a new tab or window.', 'ts' ) )
		);

		add_settings_section(
			'ts_calendar_sync_admin_settings_section',
			__( 'Admin Calendar Sync Settings', 'ts' ),
			array( 'Prdd_Lite_Delivery_Settings', 'ts_calendar_sync_admin_settings_section_callback' ),
			'ts_google_calendar_sync_page'
		);

		add_settings_field(
			'ts_calendar_sync_integration_mode',
			__( 'Integration Mode', 'ts' ),
			array( 'Prdd_Lite_Delivery_Settings', 'ts_calendar_sync_integration_mode_callback' ),
			'ts_google_calendar_sync_page',
			'ts_calendar_sync_admin_settings_section',
			array( __( '<br>Select method of integration. "Sync Automatically" will add the events to the Google calendar, which is set in the "Calendar to be used" field, automatically when a customer places an order. <br>"Sync Manually" will add an "Add to Calendar" button in emails received by admin on New customer order and on the Delivery Calendar page.<br>"Disabled" will disable the integration with Google Calendar.', 'ts' ) )
		);

		add_settings_field(
			'ts_calendar_key_file_name',
			__( 'Key file name', 'ts' ),
			array( 'Prdd_Lite_Delivery_Settings', 'ts_calendar_key_file_name_callback' ),
			'ts_google_calendar_sync_page',
			'ts_calendar_sync_admin_settings_section',
			array( __( '<br>Enter key file name here without extention, e.g. ab12345678901234567890-privatekey.', 'ts' ) )
		);

		add_settings_field(
			'ts_calendar_service_acc_email_address',
			__( 'Service account email address', 'ts' ),
			array( 'Prdd_Lite_Delivery_Settings', 'ts_calendar_service_acc_email_address_callback' ),
			'ts_google_calendar_sync_page',
			'ts_calendar_sync_admin_settings_section',
			array( __( '<br>Enter Service account email address here, e.g. 1234567890@developer.gserviceaccount.com.', 'ts' ) )
		);

		add_settings_field(
			'ts_calendar_id',
			__( 'Calendar to be used', 'ts' ),
			array( 'Prdd_Lite_Delivery_Settings', 'ts_calendar_id_callback' ),
			'ts_google_calendar_sync_page',
			'ts_calendar_sync_admin_settings_section',
			array( __( '<br>Enter the ID of the calendar in which your deliveries will be saved, e.g. abcdefg1234567890@group.calendar.google.com.', 'ts' ) )
		);

		add_settings_field(
			'ts_calendar_test_connection',
			'',
			array( 'Prdd_Lite_Delivery_Settings', 'ts_calendar_test_connection_callback' ),
			'ts_google_calendar_sync_page',
			'ts_calendar_sync_admin_settings_section'
		);

		add_settings_field(
			'prdd_admin_add_to_calendar_delivery_calendar',
			__( 'Show Add to Calendar button on Delivery Calendar page', 'ts' ),
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_admin_add_to_calendar_delivery_calendar_callback' ),
			'ts_google_calendar_sync_page',
			'ts_calendar_sync_admin_settings_section',
			array( __( 'Show "Add to Calendar" button on the Product Delivery Date -> View Deliveries.<br><i>Note: This button can be used to export the already placed orders with future deliveries from the current date to the calendar used above.</i>', 'ts' ) )
		);

		add_settings_field(
			'prdd_admin_add_to_calendar_email_notification',
			__( 'Show Add to Calendar button in New Order email notification', 'ts' ),
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_admin_add_to_calendar_email_notification_callback' ),
			'ts_google_calendar_sync_page',
			'ts_calendar_sync_admin_settings_section',
			array( __( 'Show "Add to Calendar" button in the New Order email notification.', 'ts' ) )
		);

		register_setting(
			'ts_google_calendar_sync',
			'ts_calendar_event_location'
		);

		register_setting(
			'ts_google_calendar_sync',
			'ts_calendar_event_summary'
		);

		register_setting(
			'ts_google_calendar_sync',
			'ts_calendar_event_description'
		);

		register_setting(
			'ts_google_calendar_sync',
			'prdd_add_to_calendar_order_received_page'
		);

		register_setting(
			'ts_google_calendar_sync',
			'prdd_add_to_calendar_customer_email'
		);

		register_setting(
			'ts_google_calendar_sync',
			'prdd_add_to_calendar_my_account_page'
		);

		register_setting(
			'ts_google_calendar_sync',
			'prdd_calendar_in_same_window'
		);

		register_setting(
			'ts_google_calendar_sync',
			'ts_calendar_sync_integration_mode'
		);

		register_setting(
			'ts_google_calendar_sync',
			'ts_calendar_details_1'
		);

		register_setting(
			'ts_google_calendar_sync',
			'prdd_admin_add_to_calendar_email_notification'
		);

		register_setting(
			'ts_google_calendar_sync',
			'prdd_admin_add_to_calendar_delivery_calendar'
		);
	}



}
