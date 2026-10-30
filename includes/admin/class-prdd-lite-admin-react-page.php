<?php
/**
 * Hosts the React powered Product Delivery Date admin pages.
 *
 * @package Product-Delivery-Date-Lite/Admin
 * @since 4.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Renders the root element of the admin app and enqueues its bundle.
 */
class PRDD_Lite_Admin_React_Page {

	/**
	 * Admin page slug.
	 */
	const PAGE = 'woocommerce_prdd_lite_page';

	/**
	 * Slug of the earlier View Deliveries page, redirected to the app.
	 */
	const HISTORY_PAGE = 'woocommerce_prdd_lite_history_page';

	/**
	 * Script handle of the admin bundle.
	 */
	const HANDLE = 'prdd-lite-admin-settings';

	/**
	 * Registers the hooks.
	 */
	public static function init() {
		add_action( 'admin_init', array( __CLASS__, 'maybe_redirect' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue' ), 20 );
	}

	/**
	 * URL of a route of the React app, e.g. '/settings'.
	 *
	 * @param string $route Hash route.
	 * @return string
	 */
	public static function url( $route = '' ) {
		$url = admin_url( 'admin.php?page=' . self::PAGE );
		return '' === $route ? $url : $url . '#' . $route;
	}

	/**
	 * Current `page` query arg.
	 *
	 * @return string
	 */
	protected static function current_page() {
		return isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}

	/**
	 * Whether the current request is for the plugin admin page.
	 *
	 * @return bool
	 */
	public static function is_page() {
		return is_admin() && self::PAGE === self::current_page();
	}

	/**
	 * Sends the earlier View Deliveries page, bookmarked or linked from
	 * elsewhere, to the View Deliveries screen of the app.
	 */
	public static function maybe_redirect() {
		if ( self::HISTORY_PAGE !== self::current_page() || wp_doing_ajax() || ! current_user_can( 'manage_woocommerce' ) ) { // phpcs:ignore WordPress.WP.Capabilities.Unknown
			return;
		}

		$args = array();
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['section'] ) && 'calendar_view' === sanitize_text_field( wp_unslash( $_GET['section'] ) ) ) {
			$args['prdd_view'] = 'prdd_calender';
		}
		foreach ( array( 'status', 'paged', 'orderby', 'order' ) as $key ) {
			if ( isset( $_GET[ $key ] ) && '' !== $_GET[ $key ] ) {
				$args[ $key ] = sanitize_text_field( wp_unslash( $_GET[ $key ] ) );
			}
		}
		// phpcs:enable WordPress.Security.NonceVerification.Recommended

		wp_safe_redirect( self::url( '/view-deliveries' . ( $args ? '?' . http_build_query( $args ) : '' ) ) );
		exit;
	}

	/**
	 * Renders the admin page.
	 */
	public static function render() {
		?>
		<h2 class="prdd-page-title" style="margin-top: 15px;"><?php esc_html_e( 'Product Delivery Date Settings', 'product-delivery-date-for-woocommerce-lite' ); ?></h2>
		<?php
		settings_errors();
		?>
		<div id="prdd-admin-settings-root" class="prdd-react-page"></div>
		<?php
	}

	/**
	 * Registers the third party scripts used by the React bundles.
	 */
	public static function register_vendor_scripts() {
		$version = PRDD_LITE_VERSION;
		$url     = PRDD_LITE_PLUGIN_URL;

		if ( ! wp_script_is( 'multiDatepicker', 'registered' ) ) {
			wp_register_script( 'multiDatepicker', $url . '/js/jquery-ui.multidatespicker.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-datepicker', 'jquery-ui-widget', 'jquery-ui-position', 'jquery-ui-selectmenu' ), $version, false );
		}
		if ( ! wp_script_is( 'prdd-choices', 'registered' ) ) {
			wp_register_script( 'prdd-choices', $url . '/assets/js/admin/choices.min.js', array(), $version, true );
		}
		if ( ! wp_script_is( 'select2', 'registered' ) && defined( 'WC_PLUGIN_FILE' ) ) {
			wp_register_script( 'select2', plugins_url( 'assets/js/select2/select2.min.js', WC_PLUGIN_FILE ), array( 'jquery', 'jquery-ui-widget', 'jquery-ui-core' ), $version, false );
		}

		// Calendar view of the View Deliveries screen.
		if ( ! wp_script_is( 'lang-all-js', 'registered' ) ) {
			wp_register_script( 'lang-all-js', $url . '/js/fullcalendar/lib/locales-all.min.js', array(), $version, false );
		}
		if ( ! wp_script_is( 'full-js', 'registered' ) ) {
			wp_register_script( 'full-js', $url . '/js/fullcalendar/lib/main.min.js', array(), $version, false );
		}
		if ( ! wp_script_is( 'prdd-images-loaded', 'registered' ) ) {
			wp_register_script( 'prdd-images-loaded', $url . '/js/imagesloaded.pkg.min.js', array(), $version, false );
		}
		if ( ! wp_script_is( 'prdd-qtip', 'registered' ) ) {
			wp_register_script( 'prdd-qtip', $url . '/js/jquery.qtip.min.js', array( 'jquery', 'prdd-images-loaded' ), $version, false );
		}

		// jQuery UI datepicker translations, used by the calendar preview of the Calendar Appearance
		// screen. The selected language loads last, so it is the default, as on the earlier page.
		$language = get_option( 'prdd_lite_language', 'en-GB' );
		if ( ! array_key_exists( $language, (array) prdd_lite_get_delivery_arrays( 'prdd_lite_languages' ) ) ) {
			$language = 'en-GB';
		}
		wp_register_script( 'prdd-lite-datepicker-i18n-all', $url . '/js/i18n/jquery-ui-i18n.js', array( 'jquery', 'jquery-ui-datepicker' ), $version, false );
		wp_register_script( 'prdd-lite-datepicker-i18n', $url . '/js/i18n/jquery.ui.datepicker-' . $language . '.js', array( 'prdd-lite-datepicker-i18n-all' ), $version, false );
	}

	/**
	 * Weekdays as value/label pairs.
	 *
	 * @return array
	 */
	public static function weekdays() {
		$list = array();
		foreach ( (array) prdd_lite_get_delivery_arrays( 'prdd_lite_weekdays' ) as $key => $label ) {
			$list[] = array(
				'value' => $key,
				'label' => __( $label, 'product-delivery-date-for-woocommerce-lite' ), // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
			);
		}
		return $list;
	}

	/**
	 * Data of the View Deliveries screen.
	 *
	 * @return array
	 */
	protected static function view_deliveries_config() {
		$statuses = array();
		foreach ( wc_get_order_statuses() as $key => $label ) {
			$statuses[] = array(
				'value' => str_replace( 'wc-', '', $key ),
				'label' => $label,
			);
		}

		$nonce = wp_create_nonce( 'ajax-nonce' );

		return array(
			'ajax_url'       => admin_url( 'admin-ajax.php', is_ssl() ? 'https' : 'http' ),
			'events_url'     => add_query_arg(
				array(
					'action' => 'prdd-adminend-events-jsons',
					'nonce'  => $nonce,
				),
				admin_url( 'admin.php' )
			),
			'nonce'          => $nonce,
			'holidays'       => PRDD_Lite_Settings_Service::get_global_holidays_list(),
			'language'       => get_option( 'prdd_lite_language', 'en-GB' ),
			'order_statuses' => $statuses,
		);
	}

	/**
	 * Data of the locked Bulk Product Settings preview, the defaults of the
	 * product metabox.
	 *
	 * @return array
	 */
	protected static function bulk_preview() {
		return PRDD_Lite_Product_Metabox_React::get_form_data( 0 );
	}

	/**
	 * Data passed to the admin app.
	 *
	 * @return array
	 */
	public static function get_config() {
		$submenu = array(
			'home'         => array(
				'welcome' => __( 'Welcome', 'product-delivery-date-for-woocommerce-lite' ),
				'faq'     => __( 'FAQ', 'product-delivery-date-for-woocommerce-lite' ),
			),
			'settings'     => array(
				'settings'        => __( 'Settings', 'product-delivery-date-for-woocommerce-lite' ),
				'block_time_slot' => __( 'Block Time Slot', 'product-delivery-date-for-woocommerce-lite' ),
				'holidays'        => __( 'Holidays', 'product-delivery-date-for-woocommerce-lite' ),
			),
			'appearance'   => array(
				'appearance'  => __( 'Calendar Appearance', 'product-delivery-date-for-woocommerce-lite' ),
				'field_label' => __( 'Field Labels', 'product-delivery-date-for-woocommerce-lite' ),
			),
			'integrations' => array(
				'google'        => __( 'Google Sync', 'product-delivery-date-for-woocommerce-lite' ),
				'subscriptions' => __( 'Subscriptions', 'product-delivery-date-for-woocommerce-lite' ),
			),
		);

		$appearance = PRDD_Lite_Settings_Service::get_appearance();

		$config = array(
			'plugin_url'         => PRDD_LITE_PLUGIN_URL,
			'admin_url'          => admin_url(),
			'page_url'           => self::url(),
			'version'            => PRDD_LITE_VERSION,
			'submenu'            => $submenu,
			'support_url'        => 'https://wordpress.org/support/plugin/product-delivery-date-for-woocommerce-lite/',
			'review_url'         => 'https://wordpress.org/support/plugin/product-delivery-date-for-woocommerce-lite/reviews/?rate=5#new-post',
			'themes_url'         => PRDD_LITE_PLUGIN_URL . '/css/themes/',
			'upsell_images_url'  => PRDD_LITE_PLUGIN_URL . '/includes/component/upgrade-to-pro/assets/images',
			'pro_installed'      => '' !== get_option( 'prdd_pro_installed', '' ),
			'reset_tracking_url' => add_query_arg(
				array(
					'ts_action' => 'reset_tracking',
					'nonce'     => wp_create_nonce( 'ts_nonce_action' ),
				),
				self::url()
			),
			'weekdays'           => self::weekdays(),
			'first_day'          => $appearance['prdd_calendar_day'],
			'global_holidays'    => PRDD_Lite_Settings_Service::get_global_holidays_list(),
			'currency_symbol'    => html_entity_decode( get_woocommerce_currency_symbol(), ENT_QUOTES, 'UTF-8' ),
			'view_deliveries'    => self::view_deliveries_config(),
			'bulk_preview'       => self::bulk_preview(),
		);

		return (array) apply_filters( 'prdd_lite_admin_config', $config );
	}

	/**
	 * Enqueues the admin bundle on the plugin page.
	 */
	public static function enqueue() {
		if ( ! self::is_page() ) {
			return;
		}

		self::register_vendor_scripts();

		$asset_file = PRDD_LITE_PLUGIN_PATH . '/build/admin-settings.asset.php';
		$asset      = file_exists( $asset_file ) ? include $asset_file : array(
			'dependencies' => array(),
			'version'      => PRDD_LITE_VERSION,
		);

		$deps = array_merge(
			(array) $asset['dependencies'],
			array( 'jquery', 'jquery-ui-datepicker', 'multiDatepicker', 'prdd-choices', 'select2', 'full-js', 'lang-all-js', 'prdd-images-loaded', 'prdd-qtip', 'prdd-lite-datepicker-i18n' )
		);

		wp_enqueue_script( self::HANDLE, PRDD_LITE_PLUGIN_URL . '/build/admin-settings.js', array_unique( $deps ), $asset['version'], true );
		wp_add_inline_script( self::HANDLE, 'window.prddLiteAdmin = ' . wp_json_encode( self::get_config() ) . ';', 'before' );
		wp_set_script_translations( self::HANDLE, 'product-delivery-date-for-woocommerce-lite', PRDD_LITE_PLUGIN_PATH . '/languages' );
	}
}
