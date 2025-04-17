<?php
/**
 * Product Delivery Date Lite Global Settings
 *
 * @author Tyche Softwares
 * @package Product-Delivery-Date-Pro-for-WooCommerce
 * @since 1.7
 */
// phpcs:disable
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
		add_menu_page(
			'Product Delivery Date',
			'Product Delivery Date',
			'manage_woocommerce',// phpcs:ignore
			'woocommerce_prdd_lite_page',
			array(
				'PRDD_Lite_Global_Menu',
				'prdd_lite_global_menu_page',
			)
		);

		$page = add_submenu_page(
			'woocommerce_prdd_lite_page',
			__( 'View Deliveries', 'woocommerce-prdd-lite' ),
			__( 'View Deliveries', 'woocommerce-prdd-lite' ),
			'manage_woocommerce',// phpcs:ignore
			'woocommerce_prdd_lite_history_page',
			array(
				'Prdd_View_Deliveries_Lite',
				'prdd_lite_woocommerce_prdd_history_page',
			)
		);

		$page = add_submenu_page(
			'woocommerce_prdd_lite_page',
			__( 'Settings', 'woocommerce-prdd-lite' ),
			__( 'Settings', 'woocommerce-prdd-lite' ),
			'manage_woocommerce', // phpcs:ignore
			'woocommerce_prdd_lite_page',
			array(
				'PRDD_Lite_Global_Menu',
				'prdd_lite_global_menu_page',
			)
		);

		remove_submenu_page( 'woocommerce_prdd_lite_page', 'woocommerce_prdd_lite_page' );
		do_action( 'prdd_lite_add_submenu' );

		if ( isset( $_POST['option_page'] ) && 'prdd_lite_settings' === $_POST['option_page'] ) {// phpcs:ignore
			$woocommerce_prdd_global_settings                         = array();
			$woocommerce_prdd_global_settings['prdd_language']        = $_POST['prdd_lite_language'];// phpcs:ignore
			$woocommerce_prdd_global_settings['prdd_date_format']     = $_POST['prdd_lite_date_format'];// phpcs:ignore
			$woocommerce_prdd_global_settings['prdd_months']          = $_POST['prdd_lite_months'];// phpcs:ignore
			$woocommerce_prdd_global_settings['prdd_calendar_day']    = $_POST['prdd_lite_calendar_day'];// phpcs:ignore
			$woocommerce_prdd_global_settings['prdd_themes']          = $_POST['prdd_lite_theme'];// phpcs:ignore
			$woocommerce_prdd_global_settings['prdd_global_holidays'] = $_POST['prdd_lite_global_holidays'];// phpcs:ignore
			$woocommerce_prdd_global_settings['prdd_enable_rounding'] = isset( $_POST['prdd_lite_enable_rounding'] ) ? $_POST['prdd_lite_enable_rounding'] : ''; // phpcs:ignore
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
			<a href="admin.php?page=woocommerce_prdd_lite_page&action=settings" class="nav-tab <?php echo esc_attr( $active_settings ); ?>"> <?php esc_html_e( 'Global Delivery Settings', 'woocommerce-prdd-lite' ); ?> </a>
			<a href="admin.php?page=woocommerce_prdd_lite_page&action=labels" class="nav-tab <?php echo esc_attr( $active_labels ); ?>"> <?php esc_html_e( 'Field Labels', 'woocommerce-prdd-lite' ); ?> </a>
			<a href="admin.php?page=woocommerce_prdd_lite_page&action=prdd_google_calendar_sync" class="nav-tab <?php echo esc_attr( $active_google_sync ); ?>"> <?php esc_html_e( 'Integrations', 'woocommerce-prdd-lite' ); ?> </a>
			<a href="admin.php?page=woocommerce_prdd_lite_page&action=bulk_product_settings" class="nav-tab <?php echo esc_attr( $active_bulk_product_settings ); ?>"> <?php esc_html_e( 'Bulk Product Settings', 'woocommerce-prdd-lite' ); ?> </a>
			<?php do_action( 'prdd_lite_add_settings_tab' ); ?>
		</h2>
		<?php
		do_action( 'prdd_lite_add_tab_content' );

		switch ( $action ) {
			case 'labels':
				Ts_Upgrade_To_Pro_Prdd::prddd_lite_show_settings_modal( 'prddd-lite-field-labels-html.php' );
				break;
			case 'prdd_google_calendar_sync':
			case 'upgrade_to_pro_page':
				Ts_Upgrade_To_Pro_Prdd::prddd_lite_show_settings_modal( 'prddd-lite-integrations-html.php' );
				break;
			case 'bulk_product_settings':
				Ts_Upgrade_To_Pro_Prdd::prddd_lite_show_settings_modal( 'prddd-lite-bulk-product-settings-html.php' );
				break;
			case 'settings':
			default:
				print( '<div id="content">
				<form method="post" action="options.php">' );
					settings_fields( 'prdd_lite_settings' );
					do_settings_sections( 'prdd_lite_settings_page' );
					submit_button( __( 'Save Settings', 'woocommerce-prdd-lite' ), 'primary', 'save', true );
				print( '</form>
			</div>' );
				break;
		}
	}

	/**
	 * Plugin Settings.
	 */
	public static function prdd_lite_delivery_settings() {

		add_settings_section(
			'prdd_lite_delivery_settings_section',      // ID used to identify this section and with which to register options.
			__( 'Settings', 'woocommerce-prdd-lite' ),       // Title to be displayed on the administration page.
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_lite_delivery_settings_section_callback' ),     // Callback used to render the description of the section.
			'prdd_lite_settings_page'               // Page on which to add this section of options.
		);

		add_settings_field(
			'prdd_lite_language',
			__( 'Language', 'woocommerce-prdd-lite' ),
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_lite_language_callback' ),
			'prdd_lite_settings_page',
			'prdd_lite_delivery_settings_section',
			array( __( 'Choose the language for your delivery calendar.', 'woocommerce-prdd-lite' ) )
		);

		add_settings_field(
			'prdd_lite_date_format',
			__( 'Date Format', 'woocommerce-prdd-lite' ),
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_lite_date_format_callback' ),
			'prdd_lite_settings_page',
			'prdd_lite_delivery_settings_section',
			array( __( 'The format in which the delivery date appears to the customers on the product page once the date is selected.', 'woocommerce-prdd-lite' ) )
		);

		add_settings_field(
			'prdd_lite_months',
			__( 'Number of months to show in calendar', 'woocommerce-prdd-lite' ),
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_lite_months_callback' ),
			'prdd_lite_settings_page',
			'prdd_lite_delivery_settings_section',
			array( __( 'The number of months to be shown on the calendar. If the delivery dates spans across 2 months, then dates of 2 months can be shown simultaneously without the need to press Next or Back buttons.', 'woocommerce-prdd-lite' ) )
		);

		add_settings_field(
			'prdd_lite_calendar_day',
			__( 'First Day on Calendar', 'woocommerce-prdd-lite' ),
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_lite_calendar_day_callback' ),
			'prdd_lite_settings_page',
			'prdd_lite_delivery_settings_section',
			array( __( 'Choose the first day of week displayed on the Delivery Date calendar.', 'woocommerce-prdd-lite' ) )
		);

		add_settings_field(
			'prdd_lite_theme',
			__( 'Preview Theme & Language', 'woocommerce-prdd-lite' ),
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_lite_theme_callback' ),
			'prdd_lite_settings_page',
			'prdd_lite_delivery_settings_section',
			array( __( 'Select the theme for the calendar. You can choose a theme which blends with the design of your website.', 'woocommerce-prdd-lite' ) )
		);

		add_settings_field(
			'prdd_lite_global_holidays',
			__( 'No delivery on these dates', 'woocommerce-prdd-lite' ),
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_lite_global_holidays_callback' ),
			'prdd_lite_settings_page',
			'prdd_lite_delivery_settings_section',
			array( __( 'Select dates for which the delivery will be completely disabled for all the products in your WooCommerce store. <br> The dates selected here will be unavailable for all products. Please click on the date in calendar to add or delete the date from the list.', 'woocommerce-prdd-lite' ) )
		);

		add_settings_field(
			'prdd_lite_enable_rounding',
			__( 'Enable Rounding of Prices', 'woocommerce-prdd-lite' ),
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_lite_enable_rounding_callback' ),
			'prdd_lite_settings_page',
			'prdd_lite_delivery_settings_section',
			array( __( 'Rounds the Price to the nearest Integer value.', 'woocommerce-prdd-lite' ) )
		);

		add_settings_field(
			'prdd_lite_enable_delete_order_item',
			__( 'Remove Data on Uninstall?', 'woocommerce-prdd-lite' ),
			array( 'Prdd_Lite_Delivery_Settings', 'prdd_lite_enable_delete_order_item_callback' ),
			'prdd_lite_settings_page',
			'prdd_lite_delivery_settings_section',
			array( __( 'Enable this setting if you want to completely remove Product Delivery Date Lite data when plugin is deleted.', 'woocommerce-prdd-lite' ) )
		);

		add_settings_field(
			'ts_reset_tracking',
			__( 'Reset usage tracking', 'woocommerce-prdd-lite' ),
			array( 'Prdd_Lite_Delivery_Settings', 'ts_rereset_tracking_callback' ),
			'prdd_lite_settings_page',
			'prdd_lite_delivery_settings_section',
			array( __( 'This will reset your usage tracking settings, causing it to show the opt-in banner again and not sending any data.', 'woocommerce-prdd-lite' ) )
		);

		register_setting(
			'prdd_lite_settings',
			'ts_reset_tracking'
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
}
