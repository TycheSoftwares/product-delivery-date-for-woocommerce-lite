<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Ts_Upgrade_To_Pro_Prdd
 *
 * @since 1.0.0
 */
class Ts_Upgrade_To_Pro_Prdd {

	/**
	 * The capability users should have to view the page.
	 *
	 * @var string $minimum_capability
	 */
	public static $minimum_capability = 'manage_options';

	/**
	 * Plugin name.
	 *
	 * @var string
	 * @access public
	 */
	public static $plugin_name = '';

	/**
	 * Plugin prefix.
	 *
	 * @var string
	 * @access public
	 */
	public static $plugin_prefix = '';

	/**
	 * Plugins page path.
	 *
	 * @var string
	 * @access public
	 */
	public static $plugin_page = '';

	/**
	 * Plugins plugin local.
	 *
	 * @var string
	 * @access public
	 */
	public static $plugin_locale = '';

	/**
	 * Plugin folder name.
	 *
	 * @var string
	 * @access public
	 */
	public static $plugin_folder = '';

	/**
	 * Plugin url.
	 *
	 * @var string
	 * @access public
	 */
	public static $plugin_url = '';

	/**
	 * Slug on Main menu.
	 *
	 * @var string
	 * @access public
	 */
	public static $plugin_slug = '';

	/**
	 * Slug for Upgrade to Pro submenu.
	 *
	 * @var string
	 * @access public
	 */
	public static $ts_utp_submenu_slug = '';

	/**
	 * Item ID of Trial Version Download.
	 *
	 * @var int
	 * @access public
	 */
	public static $ts_item_id = 700698;

	/**
	 * Option name of Pro Version License.
	 *
	 * @var string
	 * @access public
	 */
	public static $ts_license_key_option_name = 'edd_sample_license_key_odd_woo';

	/**
	 * Initialization of hooks.
	 *
	 * @param string $ts_plugin_mame Name of the Plugin.
	 * @param string $ts_plugin_prefix Prefix of the Plugin.
	 * @param string $ts_plugin_page Settings page of Plugin.
	 * @param string $ts_plugin_locale Locale of the Plugin.
	 * @param string $ts_plugin_folder_name Plugin folder name.
	 * @param string $ts_plugin_slug Plugin Slug.
	 * @param string $utp_submenu_slug Submenu Slug.
	 */
	public function __construct( $ts_plugin_mame = '', $ts_plugin_prefix = '', $ts_plugin_page = '', $ts_plugin_locale = '', $ts_plugin_folder_name = '', $ts_plugin_slug = '', $utp_submenu_slug = '' ) {

		self::$plugin_name         = $ts_plugin_mame;
		self::$plugin_prefix       = $ts_plugin_prefix;
		self::$plugin_page         = $ts_plugin_page;
		self::$plugin_locale       = $ts_plugin_locale;
		self::$plugin_slug         = $ts_plugin_slug;
		self::$ts_utp_submenu_slug = ( '' === $utp_submenu_slug ) ? self::$plugin_slug : $utp_submenu_slug;

		add_action( self::$plugin_prefix . '_add_submenu', array( &$this, 'ts_add_submenu' ) );
		add_action( 'admin_notices', array( &$this, 'ts_lite_trial_purchase_notices' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'ts_custom_notice_style' ) );
		add_action( 'admin_head', array( &$this, 'ts_add_submenu_class' ) );

		add_action( 'wp_ajax_prdd_lite_dismiss_upgrade_to_pro', array( &$this, 'dismiss_upgrade_to_pro_notice' ) );

		self::$plugin_folder = $ts_plugin_folder_name;
		self::$plugin_url = $this->ts_get_plugin_url();
	}

	/**
	 * Called when the dismiss icon is clicked on the notice.
	 */
	public function dismiss_upgrade_to_pro_notice() {
		if ( current_user_can( 'manage_woocommerce' ) && isset( $_POST['security'] ) && ( isset( $_POST['security'] ) && wp_verify_nonce( sanitize_key( $_POST['security'] ), 'tracking_notice' ) ) ) {// phpcs:ignore
			if ( isset( $_POST['upgrade_to_pro_type'] ) ) {
				$type = sanitize_text_field( wp_unslash( $_POST['upgrade_to_pro_type'] ) );
				switch ( $type ) {
					case 'purchase':
						update_option( 'prddd_lite_upgrade_to_pro_notice_dismissed', 'yes' );
						break;
					case 'expired':
						update_option( 'prddd_lite_upgrade_to_pro_notice_expired_dismissed', 'yes' );
						break;
					default:
						break;
				}
			}
			return 'success';
		} else {
			die( 'Security check failed' );
		}
	}

	/**
	 * Adding class to Upgrade to Pro submenu to apply styling.
	 *
	 * @access public
	 * @since  7.7
	 * @return void
	 */
	public function ts_add_submenu_class() {
		global $submenu;

		if ( isset( $submenu['woocommerce_prdd_lite_page'] ) ) {
			$submenu['woocommerce_prdd_lite_page'][3][] = 'prddd-upgrade-to-pro-additional-class'; // phpcs:ignore.
		}
	}

	/**
	 * Register the Dashboard Page which is later hidden but this pages
	 * is used to render the Welcome page.
	 *
	 * @access public
	 * @since  7.7
	 * @return void
	 */
	public function ts_custom_notice_style() {

		global $wpefield_version;

		wp_enqueue_style(
			self::$plugin_prefix . '-custom-notice',
			plugins_url( '/assets/css/ts-upgrade-to-pro.css', __FILE__ ),
			'',
			$wpefield_version
		);
	}

	/**
	 * Adds a subment to the main menu of the plugin
	 *
	 * @since 7.7
	 */
	public function ts_add_submenu() {

		$page = add_submenu_page(
			'woocommerce_prdd_lite_page',
			__( 'Upgrade to Pro', 'product-delivery-date-for-woocommerce-lite' ),
			__( 'Upgrade to Pro', 'product-delivery-date-for-woocommerce-lite' ),
			self::$minimum_capability,
			'woocommerce_prdd_lite_page&action=upgrade_to_pro_page',
			array( $this, 'ts_lite_upgrade_to_pro_callback' )
		);
	}

	/**
	 * Upgrade to pro link
	 *
	 * @since 1.5
	 */
	public static function ts_lite_upgrade_to_pro_callback() {}

	/**
	 * Checks if Pro is not being used then show notice for purchasing the Trial version of Prddd Pro.
	 * OR to show the notice for the expired license of the trial.
	 *
	 * @hook admin_init
	 * @since 3.23.0
	 */
	public function ts_lite_trial_purchase_notices() {

		if ( isset( $_GET['page'] ) && 'woocommerce_prdd_lite_page' === $_GET['page'] ) { // phpcs:ignore.

			$message = '';
			$trial   = get_option( 'prddd_edd_license_download_type', '' ); // If trial license is used then we are storing it as trial as this option.

			if ( 'trial' === $trial ) {

				if ( 'yes' === get_option( 'prddd_lite_upgrade_to_pro_notice_expired_dismissed', '' ) ) {
					return;
				}
				$notice_purchase_or_expired = 'prddd-pro-expired-notice';
				$trial_expired              = get_option( 'prddd_deactivated_due_to_trial_expiry', '' );
				$license_key                = trim( get_option( self::$ts_license_key_option_name, '' ) );

				if ( '' !== $license_key ) {
					$renew_link = add_query_arg(
						array(
							'edd_license_key' => $license_key,
							'download_id'     => self::$ts_item_id,
						),
						'https://www.tychesoftwares.com/checkout'
					);
					/* translators: %s: Renew Link */
					$message = sprintf( __( 'Your Woo store is losing its WOW factor. Your Product Delivery Date Pro for WooCommerce license has expired. <a href="%s" target="_blank" class="button">Renew Now</a>', 'product-delivery-date-for-woocommerce-lite' ), $renew_link );
				}
			} elseif ( ! is_plugin_active( 'product-delivery-date/product-delivery-date.php' ) ) {
				if ( 'yes' === get_option( 'prddd_lite_upgrade_to_pro_notice_dismissed', '' ) ) {
					return;
				}
				/* translators: %s: Prddd Trial Version Download page Link */
				if ( '' === get_option( 'edd_sample_license_status_prdd_woo', '' ) ) {
					$notice_purchase_or_expired = 'prddd-upgrade-to-pro-notice';
					/* translators: %s: Link to PRDD Trial */
					$message = sprintf( __( 'Upgrade to the PRO version of Product Delivery Date Pro for WooCommerce plugin for $1! Enjoy all Pro features for 30 days at this insane price. Limited time offer <a href="%s" class="button-primary button button-large" target="_blank"><b>Act now!</b></a>', 'product-delivery-date-for-woocommerce-lite' ), 'https://www.tychesoftwares.com/products/woocommerce-product-delivery-date-pro-plugin-trial/' );
				}
			}

			if ( isset( $_GET['action'] ) && 'upload-plugin' === $_GET['action']  ) { // phpcs:ignore.
				$message = '';
			}

			if ( '' !== $message ) {
				?>
				<div class="<?php echo esc_html( $notice_purchase_or_expired ); ?> prddd-message notice is-dismissible">
					<div class="prddd-content">
						<img class="prddd-site-logo" src="<?php echo esc_url( plugins_url( '/assets/images/tyche-logo.png', __FILE__ ) ); // phpcs:ignore?> ">
						<p><?php echo $message; //phpcs:ignore ?></p>
					</div>
				</div>
					<?php
			}
		}
	}

	/**
	 * This function returns the plugin url
	 *
	 * @access public
	 * @since 7.7
	 * @return string
	 */
	public function ts_get_plugin_url() {
		return plugins_url() . '/' . self::$plugin_folder;
	}
}
