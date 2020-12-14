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
$prdd_lite_update_checker = '2.5.0';

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
			define( 'PRDD_LITE_MAX_PRODUCTS_FOR_MIGRATION', '10' );
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

			add_action( 'wp_ajax_prdd_lite_update_database', array( &$this, 'prdd_lite_update_database_callback' ) );
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
			update_option( 'woocommerce_prdd_lite_db_version', '2.5.0' );
			// Check if installed for the first time.
			add_option( 'prdd_lite_installed', 'yes' );

			add_option( 'prdd_lite_language', 'en-GB' );
			add_option( 'prdd_lite_date_format', 'mm/dd/y' );
			add_option( 'prdd_lite_months', '1' );
			add_option( 'prdd_lite_calendar_day', '1' );
			add_option( 'prdd_lite_theme', 'smoothness' );
			add_option( 'prdd_lite_global_holidays', '' );
			add_option( 'prdd_lite_enable_rounding', '' );
			add_option( 'prdd_is_data_migrated', '' );
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
				update_option( 'woocommerce_prdd_lite_db_version', '2.5.0' );
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

			if ( ! get_option( 'prdd_is_data_migrated' ) ) {
				add_option( 'prdd_is_data_migrated', '' );
			}

			$prdd_is_data_migrated = get_option( 'prdd_is_data_migrated' );
			if ( 'done' !== $prdd_is_data_migrated ) {
				$args         = array(
					'post_type'      => 'product',
					'post_status'    => 'any',
					'posts_per_page' => 1,
					'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
						array(
							'key'     => '_woo_prdd_lite_enable_delivery_date',
							'value'   => 'on',
							'compare' => '=',
						),
					),
				);
				$get_products = new WP_Query( $args );

				if ( $get_products->have_posts() && 'done' !== $prdd_is_data_migrated ) {
					update_option( 'prdd_is_data_migrated', 'yes' );
					add_action( 'admin_notices', array( &$this, 'prdd_admin_notice_for_migration' ) );
				}
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

			// Below files are only to be included on prdd database update page.
			wp_register_script( 'prdd-lite-update-script', plugins_url() . '/product-delivery-date-for-woocommerce-lite/js/prdd-lite-update-script.js', array( 'jquery' ), $plugin_version_number, false );
			wp_enqueue_script( 'prdd-lite-update-script' );
			wp_localize_script(
				'prdd-lite-update-script',
				'prdd_lite_ajax_data',
				array(
					'max_product' => PRDD_LITE_MAX_PRODUCTS_FOR_MIGRATION,
					'ajax_url'    => admin_url( 'admin-ajax.php' ),
					'prdd_nonce'  => wp_create_nonce( 'ajax-nonce' ),
				)
			);
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

		/**
		 * This function change the meta_keys to make compliant with pro plugin.
		 *
		 * @since 2.3.0
		 */
		public function prdd_admin_notice_for_migration() {
			$class   = 'notice notice-info is-dismissible';
			$message = __( '<span id="prdd-update-response">We have made some backend changes to Product Delivery Date Lite plugin. Please update the database by clicking the “Update Database” button for a smoother experience of the plugin. <input style="margin-top: 10px;display: block;" type="button" id="prdd-update-yes" class="button button-primary" value="Update database"  /></span> <span id="prdd-update-status" style="display: none;"></span>', 'woocommerce-prdd-lite' );
			printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); //phpcs:ignore
		}

		/**
		 * This function update the meta_keys to make compliant with pro plugin.
		 *
		 * @since 2.3.0
		 */
		public function prdd_lite_update_database_callback() {
			check_ajax_referer( 'ajax-nonce', 'prdd_nonce' );
			if ( isset( $_POST['is_update'] ) ) {
				$is_update = sanitize_key( $_POST['is_update'] );
			}

			if ( 'yes' === $is_update ) {
				if ( isset( $_POST['page'] ) ) {
					$page = sanitize_key( $_POST['page'] );
				}
				if ( ! $page ) {
					$page = 1;
				}
				$args         = array(
					'post_type'      => 'product',
					'post_status'    => 'any',
					'paged'          => $page,
					'posts_per_page' => PRDD_LITE_MAX_PRODUCTS_FOR_MIGRATION,
					'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
						array(
							'key'     => '_woo_prdd_lite_enable_delivery_date',
							'value'   => 'on',
							'compare' => '=',
						),
					),
				);
				$get_products = new WP_Query( $args );
				$total_page   = $get_products->max_num_pages;
				if ( $get_products->have_posts() ) {
					global $wpdb;
					while ( $get_products->have_posts() ) {
						$get_products->the_post();
						$post_id                            = get_the_ID();
						$enable_date                        = get_post_meta( $post_id, '_woo_prdd_lite_enable_delivery_date', true );
						$prdd_minimum_delivery_time         = get_post_meta( $post_id, '_woo_prdd_lite_minimum_delivery_time', true );
						$prdd_maximum_number_days           = get_post_meta( $post_id, '_woo_prdd_lite_maximum_number_days', true );
						$_delivery_days                     = get_post_meta( $post_id, '_woo_prdd_lite_delivery_days', true );
						$prdd_lite_delivery_field_mandatory = get_post_meta( $post_id, '_woo_prdd_lite_delivery_field_mandatory', true );
						$prdd_lite_holidays                 = get_post_meta( $post_id, '_woo_prdd_lite_holidays', true );

						$delivery_days      = array(
							'Sunday'    => 0,
							'Monday'    => 1,
							'Tuesday'   => 2,
							'Wednesday' => 3,
							'Thursday'  => 4,
							'Friday'    => 5,
							'Saturday'  => 6,
						);
						$delivery_day_array = array();
						if ( ! empty( $_delivery_days ) ) {
							foreach ( $_delivery_days as $key => $value ) {
								$delivery_day_array[ 'prdd_weekday_' . $delivery_days[ $value ] ] = 'on';
							}
						}

						$prdd_settings = array();
						if ( $enable_date ) {
							$prdd_settings['prdd_enable_date'] = $enable_date;
						}
						if ( $prdd_minimum_delivery_time ) {
							$prdd_settings['prdd_minimum_number_days'] = $prdd_minimum_delivery_time;
						}
						if ( $prdd_maximum_number_days ) {
							$prdd_settings['prdd_maximum_number_days'] = $prdd_maximum_number_days;
						}
						if ( ! empty( $delivery_day_array ) ) {
							$prdd_settings['prdd_recurring_chk'] = 'on';
							$prdd_settings['prdd_recurring']     = $delivery_day_array;
						}
						if ( $prdd_lite_delivery_field_mandatory ) {
							$prdd_settings['prdd_delivery_field_mandatory'] = $prdd_lite_delivery_field_mandatory;
						}
						if ( $prdd_lite_holidays ) {
							$prdd_settings['prdd_product_holiday'] = $prdd_lite_holidays;
						}
						if ( ! empty( $prdd_settings ) ) {
							update_post_meta( $post_id, 'woocommerce_prdd_settings', $prdd_settings );
						}

						$is_has_children = get_post_meta( $post_id, '_children', true );
						if ( ! empty( $is_has_children ) ) {
							$post_id_arr = implode( ',', $is_has_children );
						} else {
							$post_id_arr = implode( ',', array( $post_id ) );
						}
						$sql        = "SELECT order_items.order_id FROM {$wpdb->prefix}woocommerce_order_items as order_items LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID WHERE posts.post_type = 'shop_order' AND order_items.order_item_type = 'line_item' AND order_item_meta.meta_key = '_product_id' AND order_item_meta.meta_value IN ( $post_id_arr )";
						$get_orders = $wpdb->get_col( $sql ); // phpcs:ignore
						if ( ! empty( $get_orders ) ) {
							foreach ( $get_orders as $order_id ) {
								$order = wc_get_order( $order_id );
								foreach ( $order->get_items() as $item_id => $item ) {
									$_prdd_lite_date = $item->get_meta( '_prdd_lite_date' );
									if ( $_prdd_lite_date ) {
										wc_add_order_item_meta( $item_id, '_prdd_date', $_prdd_lite_date );
										wc_delete_order_item_meta( $item_id, '_prdd_lite_date', $_prdd_lite_date );
									}
								}
							}
						}
					}
					wp_reset_postdata();
				}
				$prdd_is_data_migrated = get_option( 'prdd_is_data_migrated' );
				if ( 'done' !== $prdd_is_data_migrated ) {
					$woocommerce_prdd_global_settings = array();
					if ( get_option( 'prdd_lite_language' ) ) {
						$woocommerce_prdd_global_settings['prdd_language'] = get_option( 'prdd_lite_language' );
					}
					if ( get_option( 'prdd_lite_date_format' ) ) {
						$woocommerce_prdd_global_settings['prdd_date_format'] = get_option( 'prdd_lite_date_format' );
					}
					if ( get_option( 'prdd_lite_months' ) ) {
						$woocommerce_prdd_global_settings['prdd_months'] = get_option( 'prdd_lite_months' );
					}
					if ( get_option( 'prdd_lite_calendar_day' ) ) {
						$woocommerce_prdd_global_settings['prdd_calendar_day'] = get_option( 'prdd_lite_calendar_day' );
					}
					if ( get_option( 'prdd_lite_theme' ) ) {
						$woocommerce_prdd_global_settings['prdd_themes'] = get_option( 'prdd_lite_theme' );
					}
					if ( get_option( 'prdd_lite_global_holidays' ) ) {
						$woocommerce_prdd_global_settings['prdd_global_holidays'] = get_option( 'prdd_lite_global_holidays' );
					}
					if ( get_option( 'prdd_lite_enable_rounding' ) ) {
						$woocommerce_prdd_global_settings['prdd_enable_rounding'] = get_option( 'prdd_lite_enable_rounding' );
					}
					if ( ! empty( $woocommerce_prdd_global_settings ) ) {
						update_option( 'woocommerce_prdd_global_settings', wp_json_encode( $woocommerce_prdd_global_settings ) );
					}
				}
				update_option( 'prdd_is_data_migrated', 'done' );
				echo wp_json_encode( array( 'total_page' => $total_page ) );
				die;
			}
		}
	}
}
$prdd_lite_woocommerce = new Prdd_Lite_Woocommerce();
