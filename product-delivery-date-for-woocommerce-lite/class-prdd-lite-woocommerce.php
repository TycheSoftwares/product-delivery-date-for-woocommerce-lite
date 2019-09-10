<?php
/**
 * Main Class File.
 *
 * @package Product-Delivery-Date-Lite
 */

/**
 * Load all the necessary files
 */
require_once 'includes/class-prdd-privacy-policy-lite.php';
require_once 'includes/admin/class-prdd-lite-meta-box.php';
require_once 'includes/class-prdd-lite-common.php';
require_once 'includes/prdd-lite-config.php';
require_once 'includes/admin/class-prdd-lite-delivery-price.php';
require_once 'includes/admin/class-prdd-lite-estimate-delivery.php';
require_once 'includes/admin/class-prdd-lite-delivery-settings.php';
require_once 'includes/admin/class-prdd-lite-global-menu.php';
require_once 'includes/class-prdd-lite-process.php';
require_once 'includes/class-prdd-lite-validation.php';

global $prdd_lite_update_checker;
$prdd_lite_update_checker = '2.0';

/**
 * This function checks Product delivery date plugin is active or not.
 *
 * @since 1.0
 */
function is_prdd_lite_active() {
	if ( is_plugin_active( 'product-delivery-date-lite/product-delivery-date-lite.php' ) ) {
		return true;
	} else {
		return false;
	}
}
/**
 * This function is used for strings translation of the plugin in different languages.
 *
 * @hook init
 * @since 1.0
 */
function prdd_lite_update_po_file() {
	$domain = 'woocommerce-prdd-lite';
	$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
	$loaded = load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '-' . $locale . '.mo' );
	if ( $loaded ) {
		return $loaded;
	} else {
		load_plugin_textdomain( $domain, false, basename( dirname( __FILE__ ) ) . '/languages/' );
	}
}

if ( ! class_exists( 'Prdd_Lite_Woocommerce' ) ) {
	/**
	 * Class for delivery date setting at back end and allowing to select the delivery date on the product page. It displays the delivery date on Cart, Checkout,  Order received page and WooCommerce->Orders page.
	 *
	 * @since 1.0
	 */
	class Prdd_Lite_Woocommerce {
		/**
		 * Constructor function for initializing settings
		 *
		 * @since 1.0
		 */
		public function __construct() {
			self::prdd_lite_load_files();
			register_activation_hook( __FILE__, array( &$this, 'prdd_lite_activate' ) );
			add_action( 'init', 'prdd_lite_update_po_file' );
			add_action( 'admin_init', array( &$this, 'prdd_lite_update_db_check' ) );

			add_filter( 'plugin_row_meta', array( &$this, 'prdd_lite_plugin_row_meta' ), 10, 2 );

			// Add Meta box for the Product Delivery Date Settings on the product edit page.
			define( 'PRDD_LITE_DELIVERIES_TEMPLATE_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/templates/' );
			add_action( 'add_meta_boxes', array( 'Prdd_Lite_Meta_Box_Class', 'prdd_lite_box' ), 10 );
			add_action( 'admin_footer', array( 'Prdd_Lite_Meta_Box_Class', 'prdd_lite_print_js' ) );

			add_action( 'woocommerce_process_product_meta', array( 'Prdd_Lite_Meta_Box_Class', 'prdd_lite_process_box' ), 1, 2 );
			add_action( 'woocommerce_duplicate_product', array( 'Prdd_Lite_Meta_Box_Class', 'prdd_lite_product_duplicate' ), 10, 2 );

			// Global Menu.
			add_action( 'admin_menu', array( 'PRDD_Lite_Global_Menu', 'prdd_lite_admin_menu' ) );
			add_action( 'admin_init', array( 'PRDD_Lite_Global_Menu', 'prdd_lite_delivery_settings' ) );
			add_action( 'admin_init', array( 'PRDD_Lite_Global_Menu', 'prdd_delivery_labels' ) );
			add_action( 'admin_init', array( 'PRDD_Lite_Global_Menu', 'prdd_google_calendar_sync_settings' ) );

			add_action( 'admin_enqueue_scripts', array( &$this, 'prdd_lite_my_enqueue_scripts_css' ) );
			add_action( 'admin_enqueue_scripts', array( &$this, 'prdd_lite_my_enqueue_scripts_js' ) );

			add_action( 'woocommerce_before_single_product', array( &$this, 'prdd_lite_front_side_scripts_js' ) );
			add_action( 'woocommerce_before_single_product', array( &$this, 'prdd_lite_front_side_scripts_css' ) );

			add_action( 'woocommerce_before_add_to_cart_button', array( 'Prdd_Lite_Process', 'prdd_lite_after_add_to_cart' ) );
			add_filter( 'woocommerce_add_cart_item_data', array( 'Prdd_Lite_Process', 'prdd_lite_add_cart_item_data' ), 25, 2 );
			add_filter( 'woocommerce_get_cart_item_from_session', array( 'Prdd_Lite_Process', 'prdd_lite_get_cart_item_from_session' ), 25, 3 );
			add_filter( 'woocommerce_get_item_data', array( 'Prdd_Lite_Process', 'prdd_lite_get_item_data' ), 15, 2 );
			add_action( 'woocommerce_checkout_update_order_meta', array( 'Prdd_Lite_Process', 'prdd_lite_order_item_meta' ), 10, 2 );
			add_filter( 'woocommerce_hidden_order_itemmeta', array( 'Prdd_Lite_Process', 'prdd_lite_hidden_order_itemmeta' ), 10, 1 );
			add_filter( 'woocommerce_add_to_cart_validation', array( 'Prdd_Lite_Validation', 'prdd_lite_get_validate_add_cart_item' ), 10, 3 );

			if ( true === is_admin() ) {
				add_filter( 'ts_deativate_plugin_questions', array( 'Prdd_Lite_All_Component', 'prdd_lite_deactivate_add_questions' ), 10, 1 );
				add_filter( 'ts_tracker_data', array( 'Prdd_Lite_All_Component', 'prdd_lite_ts_add_plugin_tracking_data' ), 10, 1 );
				add_filter( 'ts_tracker_opt_out_data', array( 'Prdd_Lite_All_Component', 'prdd_lite_get_data_for_opt_out' ), 10, 1 );
				add_action( 'prdd_lite_add_meta_footer', array( &$this, 'prdd_lite_review_text' ), 10, 1 );
			}
		}

		/**
		 * It will load all the files needed for the plugin.
		 */
		public static function prdd_lite_load_files() {

			if ( true === is_admin() ) {
				include_once 'includes/prdd-lite-component.php';
			}
		}

		/**
		 * This function detects when the product delivery date plugin is activated.
		 *
		 * @hook register_activation_hook
		 *
		 * @since 1.0
		 */
		public function prdd_lite_activate() {
			update_option( 'woocommerce_prdd_lite_db_version', '2.0' );
			// Check if installed for the first time.
			add_option( 'prdd_lite_installed', 'yes' );

			add_option( 'prdd_lite_language', 'en-GB' );
			add_option( 'prdd_lite_date_format', 'mm/dd/y' );
			add_option( 'prdd_lite_months', '1' );
			add_option( 'prdd_lite_calendar_day', '1' );
			add_option( 'prdd_lite_theme', 'smoothness' );
			add_option( 'prdd_lite_global_holidays', '' );
			add_option( 'prdd_lite_enable_rounding', '' );
		}

		/**
		 * This function is used to updating the version number in the database when the plugin is updated.
		 *
		 * @hook admin_init
		 *
		 * @since 1.3
		 */
		public function prdd_lite_update_db_check() {
			$prdd_plugin_version = get_option( 'woocommerce_prdd_lite_db_version' );
			if ( $prdd_plugin_version !== $this->get_plugin_version() ) {
				update_option( 'woocommerce_prdd_lite_db_version', '2.0' );
			}

			if ( ! get_option( 'prdd_lite_language' ) ) {
				add_option( 'prdd_lite_language', 'en-GB' );
			}

			if ( ! get_option( 'prdd_lite_date_format' ) ) {
				add_option( 'prdd_lite_date_format', 'mm/dd/y' );
			}

			if ( ! get_option( 'prdd_lite_months' ) ) {
				add_option( 'prdd_lite_months', '1' );
			}

			if ( ! get_option( 'prdd_lite_calendar_day' ) ) {
				add_option( 'prdd_lite_calendar_day', '1' );
			}
			if ( ! get_option( 'prdd_lite_theme' ) ) {
				add_option( 'prdd_lite_theme', 'smoothness' );
			}

			if ( ! get_option( 'prdd_lite_global_holidays' ) ) {
				add_option( 'prdd_lite_global_holidays', '' );
			}

			if ( ! get_option( 'prdd_lite_enable_rounding' ) ) {
				add_option( 'prdd_lite_enable_rounding', '' );
			}

		}

		/**
		 * Show row meta on the plugin screen.
		 *
		 * @param mixed $links Plugin Row Meta.
		 * @param mixed $file  Plugin Base file.
		 * @return array
		 * @since   2.0
		 */
		public static function prdd_lite_plugin_row_meta( $links, $file ) {
			$plugin_base_name = plugin_basename( __FILE__ );
			if ( $file === $plugin_base_name ) {
				$row_meta = array(
					'upgrade_to_pro' => '<a href="' . esc_url( apply_filters( 'woocommerce_prdd_lite_support_url', 'https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=prddupgradetopro&utm_medium=link&utm_campaign=ProductDeliveryDateLite' ) ) . '" title="' . esc_attr( __( 'Go Pro', 'woocommerce-prdd-lite' ) ) . '">' . __( 'Premium version', 'woocommerce-prdd-lite' ) . '</a>',
				);
				return array_merge( $links, $row_meta );
			}
			return (array) $links;
		}

		/**
		 * This function returns the product delivery date plugin version number.
		 *
		 * @since 1.3
		 */
		public function get_plugin_version() {
			$plugin_data    = get_plugin_data( __FILE__ );
			$plugin_version = $plugin_data['Version'];
			return $plugin_version;
		}

		/**
		 * This function returns the Product Delivery Date Lite plugin version number.
		 *
		 * @return string Version of the plugin
		 * @since 1.0
		 */
		public static function prdd_get_version() {
			$plugin_version         = '';
			$prddd_lite_plugin_dir  = dirname( __FILE__ );
			$prddd_lite_plugin_dir .= '/product-delivery-date-for-woocommerce-lite.php';

			$plugin_data = get_file_data( $prddd_lite_plugin_dir, array( 'Version' => 'Version' ) );
			if ( ! empty( $plugin_data['Version'] ) ) {
				$plugin_version = $plugin_data['Version'];
			}
			return $plugin_version;
		}

		/**
		 * This function include css files required for admin side.
		 *
		 * @hook admin_enqueue_scripts
		 *
		 * @since 2.0
		 */
		public function prdd_lite_my_enqueue_scripts_css() {
			$plugin_version_number = get_option( 'woocommerce_prdd_lite_db_version' );

			if ( 'product' === get_post_type() || ( isset( $_GET['page'], $_GET['action'] ) && // phpcs:ignore WordPress.Security.NonceVerification
			'woocommerce_prdd_lite_page' === $_GET['page'] && // phpcs:ignore WordPress.Security.NonceVerification
			'bulk_product_settings' === $_GET['action'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				wp_enqueue_style( 'prdd', plugins_url( '/css/prdd.css', __FILE__ ), '', $plugin_version_number, false );
				wp_enqueue_style( 'prdd-datepick', plugins_url( '/css/jquery.datepick.css', __FILE__ ), '', $plugin_version_number, false );
				wp_enqueue_style( 'prdd-lite-tabstyle-1', plugins_url( '/css/zozo.tabs.min.css', __FILE__ ), '', $plugin_version_number, false );
				wp_enqueue_style( 'prdd-lite-tabstyle-2', plugins_url( '/css/style.css', __FILE__ ), '', $plugin_version_number, false );
			}

			if ( isset( $_GET['page'] ) && 'woocommerce_prdd_lite_page' === $_GET['page'] ) { // phpcs:ignore WordPress.Security.NonceVerification
				wp_enqueue_style( 'prdd-woocommerce_admin_styles', plugins_url() . '/woocommerce/assets/css/admin.css', '', $plugin_version_number, false );
				wp_enqueue_style( 'datepicker', plugins_url( '/css/datepicker.css', __FILE__ ), '', $plugin_version_number, false );
			}
		}

		/**
		 * This function includes js files required for admin side.
		 *
		 * @hook admin_enqueue_scripts
		 *
		 * @since 2.0
		 */
		public function prdd_lite_my_enqueue_scripts_js() {
			$plugin_version_number = get_option( 'woocommerce_prdd_lite_db_version' );

			if ( 'product' === get_post_type() || ( isset( $_GET['page'], $_GET['action'] ) && // phpcs:ignore WordPress.Security.NonceVerification
			'woocommerce_prdd_lite_page' === $_GET['page'] && // phpcs:ignore WordPress.Security.NonceVerification
			'bulk_product_settings' === $_GET['action'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification

				wp_register_script( 'prdd-lite-multiDatepicker', plugins_url() . '/product-delivery-date-for-woocommerce-lite/js/jquery-ui.multidatespicker.js', '', $plugin_version_number, false );
				wp_enqueue_script( 'prdd-lite-multiDatepicker' );

				wp_register_script( 'prdd-lite-datepick', plugins_url() . '/product-delivery-date-for-woocommerce-lite/js/jquery.datepick.js', '', $plugin_version_number, false );
				wp_enqueue_script( 'prdd-lite-datepick' );

				wp_enqueue_script( 'prdd-lite-tabsjquery', plugins_url() . '/product-delivery-date-for-woocommerce-lite/js/zozo.tabs.min.js', '', $plugin_version_number, false );
			}

			// Below files are only to be included on prdd settings page.
			if ( isset( $_GET['page'] ) && 'woocommerce_prdd_lite_page' === $_GET['page'] ) { // phpcs:ignore WordPress.Security.NonceVerification

				wp_register_script( 'multiDatepicker', plugins_url() . '/product-delivery-date-for-woocommerce-lite/js/jquery-ui.multidatespicker.js', '', $plugin_version_number, false );
				wp_enqueue_script( 'multiDatepicker' );

				wp_enqueue_script( 'prdd-themeswitcher', plugins_url( '/js/jquery.themeswitcher.min.js', __FILE__ ), array( 'jquery', 'jquery-ui-datepicker' ), $plugin_version_number, false );

				wp_enqueue_script( 'prdd-lang', plugins_url( '/js/i18n/jquery-ui-i18n.js', __FILE__ ), '', $plugin_version_number, false );

				$current_language = get_option( 'prdd_lite_language' );

				if ( '' === $current_language ) {
					$current_language = 'en-GB';
				}
				wp_enqueue_script( "$current_language", plugins_url( "/js/i18n/jquery.ui.datepicker-$current_language.js", __FILE__ ), array( 'jquery', 'jquery-ui-datepicker' ), $plugin_version_number, true );
			}
		}



		/**
		 * This function includes js files required for frontend.
		 *
		 * @hook woocommerce_before_single_product
		 *
		 * @since 1.0
		 */
		public function prdd_lite_front_side_scripts_js() {
			global $post;
			if ( is_product() || is_page() ) {
				$prdd_settings = get_post_meta( $post->ID, '_woo_prdd_lite_enable_delivery_date', true );
				if ( isset( $prdd_settings ) && 'on' === $prdd_settings ) {
					$plugin_version_number = get_option( 'woocommerce_prdd_lite_db_version' );
					wp_enqueue_script( 'jquery' );
					wp_enqueue_script( 'jquery-ui-datepicker' );
					wp_enqueue_script( 'jquery-ui-core' );
					wp_register_script( 'select2', plugins_url() . '/woocommerce/assets/js/select2/select2.min.js', array( 'jquery-ui-widget', 'jquery-ui-core' ), $plugin_version_number, false );
					wp_enqueue_script( 'select2' );

					$current_language = get_option( 'prdd_lite_language' );

					if ( '' === $current_language ) {
						$current_language = 'en-GB';
					}
					wp_enqueue_script( "$current_language", plugins_url( "/js/i18n/jquery.ui.datepicker-$current_language.js", __FILE__ ), array( 'jquery', 'jquery-ui-datepicker' ), $plugin_version_number, true );

				}
			}
		}

		/**
		 * This function includes CSS files required for frontend.
		 *
		 * @hook woocommerce_before_single_product
		 *
		 * @since 1.0
		 */
		public function prdd_lite_front_side_scripts_css() {
			global $post;
			if ( is_product() || is_page() ) {
				$prdd_settings = get_post_meta( $post->ID, '_woo_prdd_lite_enable_delivery_date', true );
				if ( isset( $prdd_settings ) && 'on' === $prdd_settings ) {
					$plugin_version_number = get_option( 'woocommerce_prdd_lite_db_version' );

					$calendar_theme     = get_option( 'prdd_lite_theme' );
					$calendar_theme_sel = '';
					if ( '' !== $calendar_theme ) {
						$calendar_theme_sel = $calendar_theme;
					}
					if ( '' === $calendar_theme_sel ) {
						$calendar_theme_sel = 'smoothness';
					}

					wp_register_style( 'prdd-jquery-ui', plugins_url( '/css/themes/' . $calendar_theme_sel . '/jquery-ui.css', __FILE__ ), '', $plugin_version_number, false );
					wp_enqueue_style( 'prdd-jquery-ui' );

					wp_enqueue_style( 'datepicker', plugins_url( '/css/datepicker.css', __FILE__ ), '', $plugin_version_number, false );
				}
			}
		}

		/**
		 * This function adds the review note in the Product Delivery Date metabox under product page.
		 *
		 * @since 1.9
		 */
		public function prdd_lite_review_text() {
			?>
			<tr> <td></td> </tr>
			<tr> 
				<td colspan="2">
					<p>
					If you love Product Delivery Date for WooCommerce - LITE, then please leave us a <a href="https://wordpress.org/support/plugin/product-delivery-date-for-woocommerce-lite/reviews/?rate=5#new-post" target="_blank" data-rated="Thanks :)">★★★★★</a>
					rating. Thank you in advance. &#9786;
				</p>
				</td>
			<tr>
			<?php
		}
	}
}
$prdd_lite_woocommerce = new Prdd_Lite_Woocommerce();
