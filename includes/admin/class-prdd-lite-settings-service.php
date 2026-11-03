<?php
/**
 * Reads and saves the global settings shown on the React admin pages.
 *
 * @package Product-Delivery-Date-Lite/Admin
 * @since 4.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class PRDD_Lite_Settings_Service.
 *
 * The settings stay in the individual prdd_lite_* options read by the front
 * end. Each save also updates the woocommerce_prdd_global_settings mirror,
 * which the Pro version reads when it is installed after Lite.
 */
class PRDD_Lite_Settings_Service {

	/**
	 * Calendar appearance keys of the app mapped to the Lite options and their defaults.
	 *
	 * @var array
	 */
	protected static $appearance_options = array(
		'prdd_language'     => array( 'prdd_lite_language', 'en-GB' ),
		'prdd_date_format'  => array( 'prdd_lite_date_format', 'mm/dd/y' ),
		'prdd_months'       => array( 'prdd_lite_months', '1' ),
		'prdd_calendar_day' => array( 'prdd_lite_calendar_day', '1' ),
		'prdd_themes'       => array( 'prdd_lite_theme', 'smoothness' ),
	);

	/**
	 * Lite config array of each appearance key, used to validate the saved value.
	 *
	 * @var array
	 */
	protected static $appearance_arrays = array(
		'prdd_language'    => 'prdd_lite_languages',
		'prdd_date_format' => 'prdd_lite_date_formats',
		'prdd_themes'      => 'prdd_lite_calendar_themes',
	);

	/**
	 * Returns a value of the request data as a sanitized string.
	 *
	 * @param array  $data          Request data.
	 * @param string $key           Key.
	 * @param string $default_value Default.
	 * @return string
	 */
	protected static function value( $data, $key, $default_value = '' ) {
		if ( ! isset( $data[ $key ] ) || is_array( $data[ $key ] ) ) {
			return $default_value;
		}
		return sanitize_text_field( (string) $data[ $key ] );
	}

	/**
	 * Updates keys of the woocommerce_prdd_global_settings mirror, keeping the
	 * keys saved by the Pro version.
	 *
	 * @param array $values Keys to update.
	 */
	protected static function update_mirror( $values ) {
		$settings = json_decode( (string) get_option( 'woocommerce_prdd_global_settings', '' ), true );
		$settings = is_array( $settings ) ? $settings : array();
		update_option( 'woocommerce_prdd_global_settings', wp_json_encode( array_merge( $settings, $values ) ) );
	}

	/**
	 * Settings screen values.
	 *
	 * @return array
	 */
	public static function get_general_settings() {
		return array(
			'prdd_enable_rounding'            => 'on' === get_option( 'prdd_lite_enable_rounding', '' ) ? 'on' : '',
			// The option stores the opposite: 'on' removes the data on uninstall.
			'prdd_keep_data_on_delete_plugin' => 'on' === get_option( 'prdd_lite_enable_delete_order_item', '' ) ? '' : 'on',
		);
	}

	/**
	 * Saves the Settings screen. Keys missing from the request keep the saved
	 * value, so an incomplete request never turns on removing the data on
	 * uninstall.
	 *
	 * @param array $data Request data.
	 * @return bool
	 */
	public static function save_general_settings( $data ) {
		if ( array_key_exists( 'prdd_enable_rounding', $data ) ) {
			$rounding = 'on' === self::value( $data, 'prdd_enable_rounding' ) ? 'on' : '';
			update_option( 'prdd_lite_enable_rounding', $rounding );
			self::update_mirror( array( 'prdd_enable_rounding' => $rounding ) );
		}

		if ( array_key_exists( 'prdd_keep_data_on_delete_plugin', $data ) ) {
			$keep = 'on' === self::value( $data, 'prdd_keep_data_on_delete_plugin' );
			update_option( 'prdd_lite_enable_delete_order_item', $keep ? '' : 'on' );
		}
		return true;
	}

	/**
	 * Global holidays, comma separated in the d-m-yy jQuery UI format.
	 *
	 * @return array
	 */
	public static function get_holidays() {
		return array(
			'prdd_global_holidays' => (string) get_option( 'prdd_lite_global_holidays', '' ),
		);
	}

	/**
	 * Saves the global holidays. Dates not in the j-n-Y format the front end
	 * compares against are dropped and returned, so the screen can show them.
	 * A request without the key keeps the saved holidays, an empty value
	 * removes them.
	 *
	 * @param array $data Request data.
	 * @return array Saved holidays and the dropped dates ( invalid_dates ).
	 */
	public static function save_holidays( $data ) {
		if ( ! array_key_exists( 'prdd_global_holidays', $data ) ) {
			return self::get_holidays() + array( 'invalid_dates' => array() );
		}

		$dates   = array();
		$invalid = array();
		foreach ( explode( ',', self::value( $data, 'prdd_global_holidays' ) ) as $date ) {
			$date = trim( $date );
			if ( '' === $date ) {
				continue;
			}
			if ( preg_match( '/^(\d{1,2})-(\d{1,2})-(\d{4})$/', $date, $parts ) && checkdate( (int) $parts[2], (int) $parts[1], (int) $parts[3] ) ) {
				$dates[] = (int) $parts[1] . '-' . (int) $parts[2] . '-' . $parts[3];
			} else {
				$invalid[] = $date;
			}
		}
		$holidays = implode( ',', array_unique( $dates ) );

		update_option( 'prdd_lite_global_holidays', $holidays );
		self::update_mirror( array( 'prdd_global_holidays' => $holidays ) );

		return array(
			'prdd_global_holidays' => $holidays,
			'invalid_dates'        => array_values( array_unique( $invalid ) ),
		);
	}

	/**
	 * Global holidays as a list.
	 *
	 * @return array
	 */
	public static function get_global_holidays_list() {
		$holidays = self::get_holidays()['prdd_global_holidays'];
		return '' === $holidays ? array() : array_values( array_filter( array_map( 'trim', explode( ',', $holidays ) ) ) );
	}

	/**
	 * Calendar appearance values.
	 *
	 * @return array
	 */
	public static function get_appearance() {
		$data = array();
		foreach ( self::$appearance_options as $key => $option ) {
			$value = get_option( $option[0], '' );
			if ( '' === $value || false === $value ) {
				// The old settings page fell back to the WordPress week start.
				$value = 'prdd_calendar_day' === $key ? get_option( 'start_of_week', $option[1] ) : $option[1];
			}
			$data[ $key ] = (string) $value;
		}
		return $data;
	}

	/**
	 * Options of the calendar appearance fields, built from the Lite config arrays.
	 *
	 * @return array
	 */
	public static function get_appearance_options() {
		$lists = array(
			'languages'    => array(),
			'date_formats' => array(),
			'time_formats' => array(),
			'days'         => array(),
			'themes'       => array(),
		);

		// phpcs:disable WordPress.WP.I18n.NonSingularStringLiteralText -- Values are the plugin's translatable config arrays, translated the same way as the old settings page.
		foreach ( (array) prdd_lite_get_delivery_arrays( 'prdd_lite_languages' ) as $key => $value ) {
			$lists['languages'][] = array(
				'value' => (string) $key,
				'label' => __( $value, 'product-delivery-date-for-woocommerce-lite' ),
			);
		}
		foreach ( (array) prdd_lite_get_delivery_arrays( 'prdd_lite_date_formats' ) as $key => $format ) {
			$lists['date_formats'][] = array(
				'value' => (string) $key,
				'label' => gmdate( $format ),
			);
		}
		foreach ( (array) prdd_lite_get_delivery_arrays( 'prdd_lite_time_formats' ) as $key => $format ) {
			$lists['time_formats'][] = array(
				'value' => (string) $key,
				'label' => __( $format, 'product-delivery-date-for-woocommerce-lite' ),
			);
		}
		foreach ( (array) prdd_lite_get_delivery_arrays( 'prdd_lite_days' ) as $key => $value ) {
			$lists['days'][] = array(
				'value' => (string) $key,
				'label' => __( $value, 'product-delivery-date-for-woocommerce-lite' ),
			);
		}
		foreach ( (array) prdd_lite_get_delivery_arrays( 'prdd_lite_calendar_themes' ) as $key => $value ) {
			$lists['themes'][] = array(
				'value' => (string) $key,
				'label' => __( $value, 'product-delivery-date-for-woocommerce-lite' ),
			);
		}
		// phpcs:enable WordPress.WP.I18n.NonSingularStringLiteralText

		return $lists;
	}

	/**
	 * Saves the calendar appearance. Values not in the Lite config arrays
	 * keep the saved value.
	 *
	 * @param array $data Request data.
	 * @return bool
	 */
	public static function save_appearance( $data ) {
		$current = self::get_appearance();
		$mirror  = array();

		foreach ( self::$appearance_options as $key => $option ) {
			$value = self::value( $data, $key, $current[ $key ] );

			if ( isset( self::$appearance_arrays[ $key ] ) ) {
				$allowed = (array) prdd_lite_get_delivery_arrays( self::$appearance_arrays[ $key ] );
				if ( ! array_key_exists( $value, $allowed ) ) {
					$value = $current[ $key ];
				}
			} elseif ( 'prdd_months' === $key ) {
				$value = in_array( $value, array( '1', '2' ), true ) ? $value : $current[ $key ];
			} elseif ( 'prdd_calendar_day' === $key ) {
				$value = preg_match( '/^[0-6]$/', $value ) ? $value : $current[ $key ];
			}

			update_option( $option[0], $value );
			$mirror[ $key ] = $value;
		}

		self::update_mirror( $mirror );
		return true;
	}
}
