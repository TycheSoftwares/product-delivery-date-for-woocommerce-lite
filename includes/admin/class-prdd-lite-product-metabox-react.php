<?php
/**
 * React based Product Delivery Date metabox on the product edit page.
 *
 * The React app keeps its state in a hidden JSON field which is decoded on
 * product save and handed to Prdd_Lite_Meta_Box_Class::prdd_lite_process_box()
 * through a $_POST adapter, so the stored data format stays unchanged.
 *
 * @package Product-Delivery-Date-Lite/Admin
 * @since   4.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class PRDD_Lite_Product_Metabox_React.
 */
class PRDD_Lite_Product_Metabox_React {

	/**
	 * Nonce action of the metabox form.
	 */
	const NONCE_ACTION = 'prdd_lite_product_metabox';

	/**
	 * Nonce field name of the metabox form.
	 */
	const NONCE_NAME = 'prdd_lite_metabox_nonce';

	/**
	 * Hidden field holding the JSON state of the React metabox.
	 */
	const DATA_FIELD = 'prdd_metabox_data';

	/**
	 * Script handle of the metabox bundle.
	 */
	const HANDLE = 'prdd-lite-product-metabox';

	/**
	 * Legacy metabox hooks which are no longer fired by the React metabox.
	 *
	 * @var array
	 */
	private static $legacy_hooks = array(
		'prdd_lite_add_tabs',
		'prdd_lite_before_enable_delivery',
		'prdd_lite_before_method_select',
		'prdd_lite_before_enable_time',
		'prdd_after_time_enabled',
		'prdd_lite_before_product_holidays',
		'prdd_lite_before_minimum_days',
		'prdd_lite_before_number_of_dates',
		'prdd_before_recurring_date_range',
		'prdd_lite_after_listing_enabled',
	);

	/**
	 * Hooks.
	 */
	public static function init() {
		add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_box' ), 10 );
		add_action( 'woocommerce_process_product_meta', array( __CLASS__, 'process_product_meta' ), 1, 2 );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue' ), 20 );
	}

	/**
	 * Adds the metabox to the product edit page.
	 */
	public static function add_meta_box() {
		add_meta_box(
			'woocommerce-prdd-lite',
			__( 'Product Delivery Date', 'product-delivery-date-for-woocommerce-lite' ),
			array( __CLASS__, 'render_meta_box' ),
			'product',
			'normal',
			'core'
		);
	}

	/**
	 * Weekdays of the plugin, prdd_weekday_N => English name.
	 *
	 * @return array
	 */
	private static function weekdays() {
		$weekdays = prdd_lite_get_delivery_arrays( 'prdd_lite_weekdays' );
		return is_array( $weekdays ) ? $weekdays : array();
	}

	/**
	 * Renders the metabox root, the hidden state field and the nonce.
	 *
	 * @param WP_Post $post Post.
	 */
	public static function render_meta_box( $post ) {
		foreach ( self::$legacy_hooks as $hook ) {
			if ( has_action( $hook ) ) {
				_deprecated_hook( esc_html( $hook ), '4.0.0', 'prdd_lite_product_metabox_data', esc_html__( 'The Product Delivery Date metabox is rendered with React. Use the prdd-lite.productMetabox.tabs / prdd-lite.productMetabox.fields JS filters instead.', 'product-delivery-date-for-woocommerce-lite' ) );
			}
		}

		if ( '' !== locate_template( 'product-delivery-date-for-woocommerce-lite/prdd-lite-delivery-settings-meta-box.php' ) ) {
			_deprecated_file( 'prdd-lite-delivery-settings-meta-box.php', '4.0.0', '', esc_html__( 'Template overrides of the product metabox are no longer used.', 'product-delivery-date-for-woocommerce-lite' ) );
		}
		?>
		<div id="prdd-product-metabox-root" class="prdd-react-page" data-bulk="no" data-product-id="<?php echo absint( $post->ID ); ?>"></div>
		<input type="hidden" name="<?php echo esc_attr( self::DATA_FIELD ); ?>" id="<?php echo esc_attr( self::DATA_FIELD ); ?>" value="" />
		<?php
		wp_nonce_field( self::NONCE_ACTION, self::NONCE_NAME );

		do_action( 'prdd_lite_add_meta_footer' );
	}

	/**
	 * Saves the metabox on product save.
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post    Post.
	 */
	public static function process_product_meta( $post_id, $post = null ) {
		// No nonce means the React metabox was not on the page (quick edit, REST, imports): keep the settings untouched.
		if ( ! isset( $_POST[ self::NONCE_NAME ] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST[ self::NONCE_NAME ] ) ), self::NONCE_ACTION ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// The JSON is decoded and every value is sanitized in build_legacy_post().
		$raw = isset( $_POST[ self::DATA_FIELD ] ) ? wp_unslash( $_POST[ self::DATA_FIELD ] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		if ( '' === $raw ) {
			return;
		}

		$data = json_decode( $raw, true );
		if ( ! is_array( $data ) ) {
			return;
		}

		self::save_product_settings( $post_id, $data, $post );
	}

	/**
	 * Saves the settings of a product through Prdd_Lite_Meta_Box_Class::prdd_lite_process_box().
	 *
	 * @param int          $product_id Product ID.
	 * @param array        $settings   Settings in the React state shape.
	 * @param WP_Post|null $post       Post.
	 */
	public static function save_product_settings( $product_id, $settings, $post = null ) {
		$post = $post instanceof WP_Post ? $post : get_post( $product_id );
		if ( ! $post ) {
			return;
		}

		// phpcs:disable WordPress.Security.NonceVerification
		$backup_post = $_POST;

		try {
			// prdd_lite_process_box() unslashes the values it reads from $_POST.
			$_POST = wp_slash( self::build_legacy_post( (array) $settings ) );

			Prdd_Lite_Meta_Box_Class::prdd_lite_process_box( $product_id, $post );

			do_action( 'prdd_lite_after_product_settings_saved', $product_id, $settings );
		} finally {
			$_POST = $backup_post;
		}
		// phpcs:enable WordPress.Security.NonceVerification
	}

	/**
	 * Comma separated dates normalized to the j-n-Y format the front end
	 * compares against. Values that are not d-m-Y dates are dropped.
	 *
	 * @param string $value Dates.
	 * @return string
	 */
	private static function normalize_dates( $value ) {
		$dates = array();
		foreach ( explode( ',', (string) $value ) as $date ) {
			$date = trim( $date );
			if ( preg_match( '/^(\d{1,2})-(\d{1,2})-(\d{4})$/', $date, $parts ) && checkdate( (int) $parts[2], (int) $parts[1], (int) $parts[3] ) ) {
				$dates[] = (int) $parts[1] . '-' . (int) $parts[2] . '-' . $parts[3];
			}
		}
		return implode( ',', array_unique( $dates ) );
	}

	/**
	 * Converts the React state to the $_POST array of the Lite metabox form.
	 * Only the settings available in Lite are saved.
	 *
	 * @param array $data React state.
	 * @return array
	 */
	private static function build_legacy_post( $data ) {
		$text = static function ( $key ) use ( $data ) {
			if ( ! isset( $data[ $key ] ) || is_array( $data[ $key ] ) ) {
				return '';
			}
			return sanitize_text_field( (string) $data[ $key ] );
		};
		$is_on = static function ( $key ) use ( $data ) {
			return isset( $data[ $key ] ) && ( 'on' === $data[ $key ] || true === $data[ $key ] );
		};

		$legacy = array(
			'prdd_lite_minimum_delivery_time' => $text( 'prdd_minimum_number_days' ),
			'prdd_lite_maximum_number_days'   => $text( 'prdd_maximum_number_days' ),
			'prdd_lite_product_holiday'       => self::normalize_dates( $text( 'prdd_product_holiday' ) ),
		);

		if ( $is_on( 'prdd_enable_date' ) ) {
			$legacy['prdd_lite_enable_date'] = 'on';
		}
		if ( $is_on( 'prdd_delivery_field_mandatory' ) ) {
			$legacy['prdd_lite_delivery_field_mandatory'] = 'on';
		}

		// The Lite form posted the English weekday names.
		$weekdays = self::weekdays();
		$days     = array();
		if ( isset( $data['prdd_weekdays'] ) && is_array( $data['prdd_weekdays'] ) ) {
			foreach ( $weekdays as $key => $name ) {
				if ( in_array( $key, $data['prdd_weekdays'], true ) ) {
					$days[] = $name;
				}
			}
		}
		$legacy['prdd_lite_delivery_days'] = $days;

		// Fields added by extensions through the prdd-lite.productMetabox.fields JS filter.
		if ( isset( $data['extra'] ) && is_array( $data['extra'] ) ) {
			$extra  = map_deep( $data['extra'], 'sanitize_text_field' );
			$legacy = array_merge( $extra, $legacy );
		}

		return (array) apply_filters( 'prdd_lite_product_metabox_legacy_post', $legacy, $data );
	}

	/**
	 * Data needed to render the metabox. Settings of the Pro version get the
	 * Pro defaults, they are shown disabled or in the locked tabs.
	 *
	 * @param int $product_id Product ID, 0 for the locked Bulk Product Settings preview.
	 * @return array
	 */
	public static function get_form_data( $product_id ) {
		$post_id    = absint( $product_id );
		$product_id = $post_id ? (int) Prdd_Lite_Common::prdd_lite_get_product_id( $post_id ) : 0;
		$weekdays   = self::weekdays();

		$meta = static function ( $key ) use ( $product_id ) {
			$value = $product_id ? get_post_meta( $product_id, $key, true ) : '';
			return is_scalar( $value ) ? (string) $value : '';
		};

		// Never saved: every weekday is selected, as on the earlier metabox.
		$days     = $product_id ? get_post_meta( $product_id, '_woo_prdd_lite_delivery_days', true ) : '';
		$selected = array_keys( $weekdays );
		if ( is_array( $days ) ) {
			$selected = array_keys( array_intersect( $weekdays, $days ) );
		}

		$minimum  = $meta( '_woo_prdd_lite_minimum_delivery_time' );
		$maximum  = $meta( '_woo_prdd_lite_maximum_number_days' );
		$holidays = array_filter( array_map( 'trim', explode( ',', $meta( '_woo_prdd_lite_holidays' ) ) ) );

		$values = array(
			// Settings of the Lite version.
			'prdd_enable_date'                  => 'on' === $meta( '_woo_prdd_lite_enable_delivery_date' ) ? 'on' : '',
			'prdd_delivery_field_mandatory'     => 'on' === $meta( '_woo_prdd_lite_delivery_field_mandatory' ) ? 'on' : '',
			'prdd_product_holiday'              => implode( ',', $holidays ),
			'prdd_minimum_number_days'          => '' === $minimum ? '0' : $minimum,
			'prdd_maximum_number_days'          => '' === $maximum ? '30' : $maximum,
			'prdd_weekdays'                     => array_values( $selected ),
			// Lite delivers on recurring weekdays through a calendar.
			'prdd_recurring_chk'                => 'on',
			'prdd_delivery_options'             => 'delivery_calendar',
			'prdd_specific_chk'                 => '',
			// Settings of the Pro version.
			'prdd_enable_inline_calendar'       => '',
			'prdd_deliery_date_label'           => '',
			'prdd_time_date_label'              => '',
			'prdd_hide_add_to_cart_button'      => '',
			'prdd_hide_delivery_fields_on_shop' => '',
			'prdd_default_date_for_delivery_disabled_product' => '',
			'prdd_enable_time'                  => '',
			'prdd_lockout_date'                 => '0',
			'nextday_charge'                    => '0',
			'prdd_same_day_cutoff_hrs'          => '',
			'prdd_same_day_cutoff_mins'         => '',
			'prdd_same_day_cutoff_hrs_free'     => '',
			'prdd_same_day_cutoff_mins_free'    => '',
			'prdd_same_day_cut_off_fee'         => '0',
			'prdd_next_day_cutoff_hrs'          => '',
			'prdd_next_day_cutoff_mins'         => '',
			'prdd_date_range_type'              => 'all_year',
			'prdd_start_date_range'             => '',
			'prdd_end_date_range'               => '',
			'prdd_estimate_days'                => '3',
			'prdd_estimate_delivery_display'    => 'days',
			'prdd_specific_date'                => '',
		);

		$recurring_days = array();
		foreach ( $selected as $key ) {
			$recurring_days[] = array(
				'key'     => $key,
				'label'   => __( $weekdays[ $key ], 'product-delivery-date-for-woocommerce-lite' ), // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
				'lockout' => '0',
			);
		}

		$appearance = PRDD_Lite_Settings_Service::get_appearance();
		$data       = array(
			'product_id'             => $product_id,
			'post_id'                => $post_id,
			'is_product'             => $product_id > 0,
			'values'                 => $values,
			'specific_dates'         => array(),
			'recurring_days'         => $recurring_days,
			'specific_timeslots'     => array(),
			'recurring_timeslots'    => array(),
			'has_specific_timeslots' => false,
			'special_prices'         => array(),
			'weekdays'               => PRDD_Lite_Admin_React_Page::weekdays(),
			'first_day'              => $appearance['prdd_calendar_day'],
			'global_holidays'        => PRDD_Lite_Settings_Service::get_global_holidays_list(),
			'currency_symbol'        => html_entity_decode( get_woocommerce_currency_symbol(), ENT_QUOTES, 'UTF-8' ),
			'plugin_url'             => PRDD_LITE_PLUGIN_URL,
		);

		return (array) apply_filters( 'prdd_lite_product_metabox_data', $data, $product_id );
	}

	/**
	 * Enqueues the React metabox on the product edit screen.
	 */
	public static function enqueue() {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
		if ( ! $screen || 'product' !== $screen->id ) {
			return;
		}

		global $post;
		$post_id = $post instanceof WP_Post ? $post->ID : 0;
		$data    = get_current_user_id() ? self::get_form_data( $post_id ) : array();

		// Shared with the admin app: the upgrade modal of the locked tabs reads these.
		$data['pro_installed']     = '' !== get_option( 'prdd_pro_installed', '' );
		$data['upsell_images_url'] = PRDD_LITE_PLUGIN_URL . '/includes/component/upgrade-to-pro/assets/images';

		$asset_file = PRDD_LITE_PLUGIN_PATH . '/build/product-metabox.asset.php';
		$asset      = file_exists( $asset_file ) ? include $asset_file : array(
			'dependencies' => array(),
			'version'      => PRDD_LITE_VERSION,
		);

		PRDD_Lite_Admin_React_Page::register_vendor_scripts();
		$deps = array_merge( (array) $asset['dependencies'], array( 'jquery', 'jquery-ui-datepicker', 'multiDatepicker', 'prdd-choices' ) );

		wp_enqueue_script( self::HANDLE, PRDD_LITE_PLUGIN_URL . '/build/product-metabox.js', array_unique( $deps ), $asset['version'], true );
		wp_add_inline_script( self::HANDLE, 'window.prddLiteProductMetabox = ' . wp_json_encode( $data ) . ';', 'before' );
		wp_set_script_translations( self::HANDLE, 'product-delivery-date-for-woocommerce-lite', PRDD_LITE_PLUGIN_PATH . '/languages' );
	}
}
