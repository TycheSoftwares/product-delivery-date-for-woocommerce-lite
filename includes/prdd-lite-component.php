<?php
/**
 * It will Add all the Boilerplate component when we activate the plugin.
 *
 * @author  Tyche Softwares
 * @package Product-Delivery-Date-Lite/Admin/Component
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! class_exists( 'Prdd_Lite_All_Component' ) ) {
	/**
	 * It will Add all the Boilerplate component when we activate the plugin.
	 */
	class Prdd_Lite_All_Component {

		/**
		 * It will Add all the Boilerplate component when we activate the plugin.
		 */
		public function __construct() {

			$is_admin = is_admin();

			if ( true === $is_admin ) {

				require_once 'component/woocommerce-check/ts-woo-active.php';

				require_once 'component/tracking-data/ts-tracking.php';
				require_once 'component/faq-support/ts-faq-support.php';
				require_once 'component/pro-notices-in-lite/ts-pro-notices.php';

				$prdd_lite_plugin_name = self::ts_get_plugin_name();

				$prdd_lite_locale = self::ts_get_plugin_locale();

				$prdd_lite_file_name          = 'product-delivery-date-for-woocommerce-lite/product-delivery-date-for-woocommerce-lite.php';
				$prdd_lite_plugin_prefix      = 'prdd_lite';
				$prdd_lite_lite_plugin_prefix = 'prdd_lite';
				$prdd_lite_plugin_folder_name = 'product-delivery-date-for-woocommerce-lite/';
				$prdd_lite_plugin_dir_name    = dirname( untrailingslashit( plugin_dir_path( __FILE__ ) ) ) . '/product-delivery-date-for-woocommerce-lite.php';
				$prdd_lite_plugin_url         = dirname( untrailingslashit( plugins_url( '/', __FILE__ ) ) );

				$prdd_lite_get_previous_version = get_option( 'woocommerce_prdd_lite_db_version' );

				$prdd_lite_blog_post_link = 'https://www.tychesoftwares.com/docs/docs/product-delivery-date-for-woocommerce-lite/usage-tracking/';

				$prdd_lite_plugins_page  = 'admin.php?page=woocommerce_prdd_lite_page';
				$prdd_lite_plugin_slug   = 'product-delivery-date-for-woocommerce-lite';
				$prdd_lite_pro_file_name = 'product-delivery-date/product-delivery-date.php';

				new Prdd_Lite_TS_Woo_Active( $prdd_lite_plugin_name, $prdd_lite_file_name, $prdd_lite_locale );

				new Prdd_Lite_TS_tracking( $prdd_lite_plugin_prefix, $prdd_lite_plugin_name, $prdd_lite_blog_post_link, $prdd_lite_locale, $prdd_lite_plugin_url, 'plugins.php', '', '', '', $prdd_lite_file_name );

				new Prdd_Lite_TS_Tracker( $prdd_lite_plugin_prefix, $prdd_lite_plugin_name );

				$ts_pro_faq = self::prdd_lite_get_faq();
				new Prdd_Lite_TS_Faq_Support( $prdd_lite_plugin_name, $prdd_lite_plugin_prefix, $prdd_lite_plugins_page, $prdd_lite_locale, $prdd_lite_plugin_folder_name, $prdd_lite_plugin_slug, $ts_pro_faq );

				$ts_pro_notices = self::prdd_lite_get_notice_text();
				new Prdd_Lite_ts_pro_notices( $prdd_lite_plugin_name, $prdd_lite_lite_plugin_prefix, $prdd_lite_plugin_prefix, $ts_pro_notices, $prdd_lite_file_name, $prdd_lite_pro_file_name );

			}
		}

		 /**
		  * It will retrun the plguin name.
		  *
		  * @return string $ts_plugin_name Name of the plugin
		  */
		public static function ts_get_plugin_name() {
			$ordd_plugin_dir  = dirname( dirname( __FILE__ ) );
			$ordd_plugin_dir .= '/product-delivery-date-for-woocommerce-lite.php';

			$ts_plugin_name = '';
			$plugin_data    = get_file_data( $ordd_plugin_dir, array( 'name' => 'Plugin Name' ) );
			if ( ! empty( $plugin_data['name'] ) ) {
				$ts_plugin_name = $plugin_data['name'];
			}
			return $ts_plugin_name;
		}

		/**
		 * It will retrun the Plugin text Domain
		 *
		 * @return string $ts_plugin_domain Name of the Plugin domain
		 */
		public static function ts_get_plugin_locale() {
			$ordd_plugin_dir  = dirname( dirname( __FILE__ ) );
			$ordd_plugin_dir .= '/product-delivery-date-for-woocommerce-lite.php';

			$ts_plugin_domain = '';
			$plugin_data      = get_file_data( $ordd_plugin_dir, array( 'domain' => 'Text Domain' ) );
			if ( ! empty( $plugin_data['domain'] ) ) {
				$ts_plugin_domain = $plugin_data['domain'];
			}
			return $ts_plugin_domain;
		}

		/**
		 * It will Display the notices in the admin dashboard for the pro vesion of the plugin.
		 *
		 * @return array $ts_pro_notices All text of the notices
		 */
		public static function prdd_lite_get_notice_text() {
			$ts_pro_notices = array();

			$prdd_lite_locale = self::ts_get_plugin_locale();

			$message_first = wp_kses_post( __( 'Thank you for using Product Delivery Date for WooCommerce - Lite! Now make your deliveries more accurate by allowing customers to select their preferred delivery time from Product Delivery Date Pro for WooCommerce. <strong><a target="_blank" href= "https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/checkout?edd_action=add_to_cart&download_id=16&utm_source=wpnotice&utm_medium=first&utm_campaign=ProductDeliveryDateLitePlugin">Get it now!</a></strong>', $prdd_lite_locale ) );

			$message_two = wp_kses_post( __( 'Never login to your admin to check your deliveries by syncing the delivery dates to the Google Calendar from Product Delivery Date Pro for WooCommerce. <strong><a target="_blank" href= "https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/checkout?edd_action=add_to_cart&download_id=16&utm_source=wpnotice&utm_medium=first&utm_campaign=ProductDeliveryDateLitePlugin">Get it now!</a></strong>', $prdd_lite_locale ) );

			$message_three = wp_kses_post( __( 'You can now view all your deliveries in list view or in calendar view from Product Delivery Date Pro for WooCommerce. <strong><a target="_blank" href= "https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/checkout?edd_action=add_to_cart&download_id=16&utm_source=wpnotice&utm_medium=first&utm_campaign=ProductDeliveryDateLitePlugin">Get it now!</a></strong>.', $prdd_lite_locale ) );

			$message_four = wp_kses_post( __( 'Allow your customers to pay extra for delivery for certain Weekdays/Dates from Product Delivery Date Pro for WooCommerce. <strong><a target="_blank" href= "https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/checkout?edd_action=add_to_cart&download_id=16&utm_source=wpnotice&utm_medium=first&utm_campaign=ProductDeliveryDateLitePlugin">Have it now!</a></strong>.', $prdd_lite_locale ) );

			$message_five = wp_kses_post( __( 'Customers can now edit the Delivery date & time on cart and checkout page or they can reschedule the deliveries for the already placed orders from Product Delivery Date Pro for WooCommerce. <strong><a target="_blank" href= "https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/checkout?edd_action=add_to_cart&download_id=16&utm_source=wpnotice&utm_medium=first&utm_campaign=ProductDeliveryDateLitePlugin">Have it now!</a></strong>.', $prdd_lite_locale ) );

			$prdd_wcal_lite_link = 'https://www.tychesoftwares.com/store/premium-plugins/woocommerce-abandoned-cart-pro/checkout?edd_action=add_to_cart&download_id=20&utm_source=wpnotice&utm_medium=sixth&utm_campaign=ProductDeliveryDateLitePlugin';

			$message_six = wp_kses_post( __( 'Boost your sales by recovering up to 60% of the abandoned carts with our Abandoned Cart Pro for WooCommerce plugin. It allows you to capture guest customer\'s email address on the shop page using Add to cart pop modal.<strong><a target="_blank" href= "' . $prdd_wcal_lite_link . '"> Install it now.</a></strong>', $prdd_lite_locale ) );

			$_link         = 'https://www.tychesoftwares.com/store/premium-plugins/order-delivery-date-for-woocommerce-pro-21/checkout?edd_action=add_to_cart&download_id=16&utm_source=wpnotice&utm_medium=seventh&utm_campaign=ProductDeliveryDateLitePlugin';
			$message_seven = wp_kses_post(
				__(
					'Allow your customers to select the Delivery Date & Time on the Checkout Page using our Order Delivery Date Pro for WooCommerce Plugin. <br> 
            <strong><a target="_blank" href= "' . $_link . '">Shop now</a></strong> & be one of the 20 customers to get 20% discount on the plugin price. Use the code "ORDPRO20". Hurry!!',
					$prdd_lite_locale
				)
			);

			$_link         = 'https://www.tychesoftwares.com/store/premium-plugins/woocommerce-booking-plugin/checkout?edd_action=add_to_cart&download_id=22&utm_source=wpnotice&utm_medium=eight&utm_campaign=ProductDeliveryDateLitePlugin';
			$message_eight = wp_kses_post( __( ' Allow your customers to book an appointment or rent an apartment with our Booking and Appointment for WooCommerce plugin. You can also sell your product as a resource or integrate with a few Vendor plugins. <br>Shop now & Save 20% on the plugin with the code "BKAP20". Only for first 20 customers. <strong><a target="_blank" href= "' . $_link . '">Have it now!</a></strong>', $prdd_lite_locale ) );

			$_link        = 'https://www.tychesoftwares.com/store/premium-plugins/deposits-for-woocommerce/checkout?edd_action=add_to_cart&download_id=286371&utm_source=wpnotice&utm_medium=eight&utm_campaign=ProductDeliveryDateLitePlugin';
			$message_nine = wp_kses_post(
				__(
					' Allow your customers to pay deposits on products using our Deposits for WooCommerce plguin. <br>
            <strong><a target="_blank" href= "' . $_link . '">Purchase now</a></strong> & Grab 20% discount with the code "DFWP20". The discount code is valid only for the first 20 customers.',
					$prdd_lite_locale
				)
			);

			$ts_pro_notices = array(
				1 => $message_first,
				2 => $message_two,
				3 => $message_three,
				4 => $message_four,
				5 => $message_five,
				6 => $message_six,
				7 => $message_seven,
				8 => $message_eight,
				9 => $message_nine,
			);

			return $ts_pro_notices;
		}

		/**
		 * It will contain all the FAQ which need to be display on the FAQ page.
		 *
		 * @return array $ts_faq All questions and answers.
		 */
		public static function prdd_lite_get_faq() {

			$ts_faq = array();

			$ts_faq = array(
				1  => array(
					'question' => 'How to make Product delivery date field a required field on the Product Page?',
					'answer'   => 'Currently, it is not possible in the Product Delivery Date for WooCommerce – Lite plugin to make "Delivery Date" field as "Required" field on the Product page. This is possible in the <a href = "https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=wprepo&utm_medium=faqlink&utm_campaign=ProductDeliveryDateLite" rel="nofollow" target="_blank">Pro </a> version.',
				),
				2  => array(
					'question' => 'Is it possible to add delivery date calendar on checkout page instead of for each product?',
					'answer'   => 'No, it is not possible to add Delivery date calendar on checkout page from Product Delivery Date plugin. However, we do have a plugin named <a href="https://www.tychesoftwares.com/store/premium-plugins/order-delivery-date-for-woocommerce-pro-21/?utm_source=wprepo&amp;utm_medium=demolink&amp;utm_campaign=ProductDeliveryDateLite" rel="nofollow" target="_blank">Order Delivery Date for WooCommerce Pro</a> and <a href="https://wordpress.org/plugins/order-delivery-date-for-woocommerce/" target="_blank">Lite</a> version which you can use to add a Delivery Date on the WooCommerce checkout page.',
				),
				3  => array(
					'question' => 'Can the customer enter the preferred delivery time for the product?',
					'answer'   => 'Currently, there is no provision for entering the delivery time in the free version. This is possible in the Pro version. <a href = "https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=wprepo&utm_medium=faqlink&utm_campaign=ProductDeliveryDateLite" rel="nofollow"> View Demo </a>',
				),
				4  => array(
					'question' => 'Can we change the language of the delivery date calendar?',
					'answer'   => 'Yes, from version 2.0 you can choose different language for the delivery date calendar.',
				),
				5  => array(
					'question' => 'Is it possible to add extra charges for weekdays or specific dates?',
					'answer'   => 'Currently, it is not possible to add the extra charges for deliveries on weekdays or for specific dates in the free version. However, this feature is available in the <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=wprepo&utm_medium=faqlink&utm_campaign=ProductDeliveryDateLite" rel="nofollow" target="_blank">Product Delivery Date Pro for WooCommerce plugin</a>.',
				),
				6  => array(
					'question' => 'Can we translate Delivery Date label on product page?',
					'answer'   => 'Yes, you can translate the Delivery Date label for the product page. To translate the strings, you need to generate ".po" and ".mo" files in your respective language. These files then need to be added to the following path: "product-delivery-date-for-woocommerce-lite/languages"',
				),

				7  => array(
					'question' => 'Is it possible to edit the selected delivery date for the already placed WooCommerce orders?',
					'answer'   => 'Currently, it is not possible to edit the selected delivery date for the WooCommerce orders in the free version. However, this feature is available in the <a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=wprepo&utm_medium=faqlink&utm_campaign=ProductDeliveryDateLite" rel="nofollow" target="_blank">Product Delivery Date Pro for WooCommerce plugin</a>. The admin, as well as the customers, can edit the delivery date for the already placed WooCommerce orders.',
				),
				8  => array(
					'question' => 'Can we change the "Delivery Date" label to something else, such as "Choose a date" or "Date to deliver"?',
					'answer'   => 'Currently, it is not possible to change the Delivery Date label in the free version. However, this feature is available in the <a href="https://www.tychesoftwares.com/store/premium-plugins/order-delivery-date-for-woocommerce-pro-21/?utm_source=wprepo&amp;utm_medium=faqlink&amp;utm_campaign=OrderDeliveryDateLite" rel="nofollow" target="_blank">Product Delivery Date Pro for WooCommerce plugin</a>.',
				),
				9  => array(
					'question' => 'Will Delivery Date Calendar on the product page work on the mobile devices?',
					'answer'   => 'Yes, Delivery Date Calendar on the product page will work on the mobile devices.',
				),
				10 => array(
					'question' => 'Difference between Lite and Pro version of the plugin.',
					'answer'   => 'You can refer <strong><a href="https://www.tychesoftwares.com/differences-pro-lite-versions-product-delivery-date-woocommerce-plugin/?utm_source=wprepo&utm_medium=faqlink&utm_campaign=ProductDeliveryDateLite" title="Lite and Pro version Difference" rel="nofollow" target="_blank">here</a>.',
				),
			);

			return $ts_faq;
		}

		 /**
		  * It will add the question for the deactivate popup modal
		  *
		  * @return array $prdd_lite_add_questions All questions.
		  */
		public static function prdd_lite_deactivate_add_questions( $prdd_lite_add_questions ) {

			$prdd_lite_add_questions = array(
				0 => array(
					'id'                => 4,
					'text'              => __( 'Minimum Delivery Time (in hours) is not working as expected.', 'woocommerce-prdd-lite' ),
					'input_type'        => '',
					'input_placeholder' => '',
				),
				1 => array(
					'id'                => 5,
					'text'              => __( 'I need delivery time along with the delivery date.', 'woocommerce-prdd-lite' ),
					'input_type'        => '',
					'input_placeholder' => '',
				),
				2 => array(
					'id'                => 6,
					'text'              => __( 'I want deliveries on some specific dates only.', 'woocommerce-prdd-lite' ),
					'input_type'        => '',
					'input_placeholder' => '',
				),
				3 => array(
					'id'                => 7,
					'text'              => __( 'I have purchased the Pro version of the Plugin.', 'woocommerce-prdd-lite' ),
					'input_type'        => '',
					'input_placeholder' => '',
				),

			);
			return $prdd_lite_add_questions;
		}

		/**
		 * Plugin's data to be tracked when Allow option is choosed.
		 *
		 * @hook ts_tracker_data
		 *
		 * @param array $data Contains the data to be tracked.
		 *
		 * @return array Plugin's data to track.
		 */

		public static function prdd_lite_ts_add_plugin_tracking_data( $data ) {
			if ( isset( $_GET['prdd_lite_tracker_optin'] ) && isset( $_GET['prdd_lite_tracker_nonce'] ) && wp_verify_nonce( $_GET['prdd_lite_tracker_nonce'], 'prdd_lite_tracker_optin' ) ) {

				$plugin_data['ts_meta_data_table_name'] = 'ts_tracking_prdd_lite_meta_data';
				$plugin_data['ts_plugin_name']          = 'Product Delivery Date for WooCommerce - Lite';

				// Store count info
				$plugin_data['deliveries_count'] = self::ts_get_deliveries_counts();

				// Get all plugin options info
				$plugin_data['deliverable_products']     = self::ts_get_deliverable_products();
				$plugin_data['prdd_lite_plugin_version'] = self::prdd_get_version();
				$plugin_data['prdd_lite_allow_tracking'] = get_option( 'prdd_lite_allow_tracking' );
				$data['plugin_data']                     = $plugin_data;
			}
			return $data;
		}

		/**
		 * It will return the total orders count which have the delivery dates.
		 */
		public static function ts_get_deliveries_counts() {
			global $wpdb;
			$order_count = 0;
			$orddd_query = 'SELECT count( order_item_id ) AS deliveries_count FROM `' . $wpdb->prefix . 'woocommerce_order_itemmeta` WHERE meta_key = %s AND order_item_id IN ( SELECT a.order_item_id FROM `' . $wpdb->prefix . 'woocommerce_order_items` AS a, `' . $wpdb->prefix . "posts` AS b WHERE a.order_id = b.ID AND b.post_type = 'shop_order' AND post_status NOT IN ('wc-cancelled', 'wc-refunded', 'trash', 'wc-failed' ) )";
			$results     = $wpdb->get_results( $wpdb->prepare( $orddd_query, '_prdd_lite_date' ) );

			if ( isset( $results[0] ) ) {
				$order_count = $results[0]->deliveries_count;
			}
			return $order_count;
		}

		/**
		 * It will retrun the total product which i=have the product delivery dates setting enabled.
		 */
		public static function ts_get_deliverable_products() {
			global $wpdb;
			$product_count = 0;
			$orddd_query   = 'SELECT count(a.ID) AS deliverable_products FROM `' . $wpdb->prefix . 'posts` AS a, `' . $wpdb->prefix . "postmeta` AS b WHERE a.post_type = 'product' AND a.post_status = 'publish' AND a.ID = b.post_id AND b.meta_key = '_woo_prdd_lite_enable_delivery_date' AND b.meta_value = 'on'";
			$results       = $wpdb->get_results( $orddd_query );
			if ( isset( $results[0] ) ) {
				$product_count += $results[0]->deliverable_products;
			}
			return $product_count;
		}

		/**
		 * Tracking data to send when No, thanks. button is clicked.
		 *
		 * @hook ts_tracker_opt_out_data
		 *
		 * @param array $params Parameters to pass for tracking data.
		 *
		 * @return array Data to track when opted out.
		 */
		public static function prdd_lite_get_data_for_opt_out( $params ) {
			$plugin_data['ts_meta_data_table_name'] = 'ts_tracking_prdd_lite_meta_data';
			$plugin_data['ts_plugin_name']          = 'Product Delivery Date for WooCommerce - Lite';

			// Store count info
			$params['plugin_data'] = $plugin_data;

			return $params;
		}
	}
	$Prdd_Lite_All_Component = new Prdd_Lite_All_Component();
}
