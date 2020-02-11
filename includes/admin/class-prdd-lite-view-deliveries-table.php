<?php
/**
 * Product Delivery Date Pro for WooCommerce
 *
 * This file is used for displaying all delivery Date & Time with customer name, product name & Amount under View Deliveries menu.
 *
 * @author  Tyche Softwares
 * @package Product-Delivery-Date-Lite/View-Deliveries
 * @since   2.0
 */

// Load WP_List_Table if not loaded.
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}
/**
 * Class for displaying the delivery date and time.
 *
 * @since 2.0
 */
class PRDD_Lite_View_Deliveries_Table extends WP_List_Table {
	/**
	 * Number of results to show per page
	 *
	 * @var string
	 * @since 2.0
	 */
	public $per_page = 30;

	/**
	 * URL of this page
	 *
	 * @var string
	 * @since 2.0
	 */
	public $base_url;

	/**
	 * Total number of deliveries
	 *
	 * @var int
	 * @since 2.0
	 */
	public $total_count;

	/**
	 * Total number of deliveries from today onwards
	 *
	 * @var int
	 * @since 2.0
	 */
	public $future_count;

	/**
	 * Total number of deliveries today
	 *
	 * @var int
	 * @since 2.0
	 */
	public $today_delivery_count;

	/**
	 * Total number of deliveries for tomorrow
	 *
	 * @var int
	 * @since 2.0
	 */
	public $tomorrow_delivery_count;

	/**
	 * Get things started
	 *
	 * @since 2.0
	 */
	public function __construct() {
		global $status, $page;
		// Set parent defaults.
		parent::__construct(
			array(
				'ajax' => false,                        // Does this table support ajax?
			)
		);
		$this->get_delivery_counts();
		$this->base_url = admin_url( 'admin.php?page=woocommerce_prdd_lite_history_page' );
	}
	/**
	 * Prepare data to be displayed on View Delivery List Page
	 *
	 * @since 2.0
	 */
	public function prdd_lite_prepare_items() {
		$columns               = $this->get_columns();
		$hidden                = array(); // No hidden columns.
		$sortable              = $this->get_sortable_columns();
		$data                  = $this->deliveries_data();
		$status                = isset( $_GET['status'] ) ? sanitize_text_field( wp_unslash( $_GET['status'] ) ) : 'any'; // phpcs:ignore WordPress.Security.NonceVerification
		$this->_column_headers = array( $columns, $hidden, $sortable );
		switch ( $status ) {
			case 'future':
				$total_items = $this->future_count;
				break;
			case 'today_delivery':
				$total_items = $this->today_delivery_count;
				break;
			case 'tomorrow_delivery':
				$total_items = $this->tomorrow_delivery_count;
				break;
			case 'any':
				$total_items = $this->total_count;
				break;
			default:
				$total_items = $this->total_count;
		}
		$this->items = $data;
		$this->set_pagination_args(
			array(
				'total_items' => $total_items,                      // WE have to calculate the total number of items.
				'per_page'    => $this->per_page,                       // WE have to determine how many items to show on a page.
				'total_pages' => ceil( $total_items / $this->per_page ),   // WE have to calculate the total number of pages.
			)
		);
	}
	/**
	 * Get the current Views
	 *
	 * @return array $views Current Views available to be displayed
	 * @since 2.0
	 */
	public function get_views() {
		$current                 = isset( $_GET['status'] ) ? sanitize_text_field( wp_unslash( $_GET['status'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
		$total_count             = '&nbsp;<span class="count">(' . $this->total_count . ')</span>';
		$future_count            = '&nbsp;<span class="count">(' . $this->future_count . ')</span>';
		$today_delivery_count    = '&nbsp;<span class="count">(' . $this->today_delivery_count . ')</span>';
		$tomorrow_delivery_count = '&nbsp;<span class="count">(' . $this->tomorrow_delivery_count . ')</span>';
		$views                   = array(
			'all'               => sprintf( '<a href="%s"%s>%s</a>', remove_query_arg( array( 'status', 'paged' ) ), 'all' === $current || '' === $current ? ' class="current"' : '', __( 'All', 'woocommerce-prdd' ) . $total_count ),
			'future'            => sprintf(
				'<a href="%s"%s>%s</a>',
				add_query_arg(
					array(
						'status' => 'future',
						'paged'  => false,
					)
				),
				'future' === $current ? ' class="current"' : '',
				__( 'Deliveries From Today Onwards', 'woocommerce-prdd' ) . $future_count
			),
			'today_delivery'    => sprintf(
				'<a href="%s"%s>%s</a>',
				add_query_arg(
					array(
						'status' => 'today_delivery',
						'paged'  => false,
					)
				),
				'today_delivery' === $current ? ' class="current"' : '',
				__( 'Today\'s Deliveries', 'woocommerce-prdd' ) . $today_delivery_count
			),
			'tomorrow_delivery' => sprintf(
				'<a href="%s"%s>%s</a>',
				add_query_arg(
					array(
						'status' => 'tomorrow_delivery',
						'paged'  => false,
					)
				),
				'tomorrow_delivery' === $current ? ' class="current"' : '',
				__( 'Tomorrow\'s Deliveries', 'woocommerce-prdd' ) . $tomorrow_delivery_count
			),
		);

		return apply_filters( 'prdd_deliveries_table_views', $views );
	}

	/**
	 * Get Columns to be displayed
	 *
	 * @return array $columns Columns to be displayed
	 * @since 2.0
	 */
	public function get_columns() {
		$columns = array(
			'ID'            => __( 'Order ID', 'woocommerce-prdd' ),
			'name'          => __( 'Customer Name', 'woocommerce-prdd' ),
			'product_name'  => __( 'Product Name', 'woocommerce-prdd' ),
			'delivery_date' => __( 'Delivery Date', 'woocommerce-prdd' ),
			'quantity'      => __( 'Quantity', 'woocommerce-prdd' ),
			'amount'        => __( 'Amount', 'woocommerce-prdd' ),
			'order_date'    => __( 'Order Date', 'woocommerce-prdd' ),
			'actions'       => __( 'Actions', 'woocommerce-prdd' ),
		);
		return apply_filters( 'prdd_lite_view_deliveries_table_columns', $columns );
	}

	/**
	 * Set the columns that can be sorted.
	 *
	 * @return array $columns Columns array with sortable columns set or reset.
	 * @since 2.0
	 */
	public function get_sortable_columns() {
		$columns = array(
			'delivery_date' => array( 'delivery_date', false ),
		);
		return apply_filters( 'prdd_lite_view_deliveries_sortable_columns', $columns );
	}

	/**
	 * Add Advanced Filters - Search Box
	 *
	 * @param string $text Text Searched for.
	 * @param string $input_id ID of the field searched.
	 * @since 1.1
	 * @todo Function is not necessary.
	 */
	public function search_box( $text, $input_id ) {
		if ( empty( $_REQUEST['s'] ) && ! $this->has_items() ) { // phpcs:ignore WordPress.Security.NonceVerification
			return;
		}
		$input_id = $input_id . '-search-input';
		if ( ! empty( $_REQUEST['orderby'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			echo '<input type="hidden" name="orderby" value="' . esc_attr( sanitize_text_field( wp_unslash( $_REQUEST['orderby'] ) ) ) . '" />'; // phpcs:ignore WordPress.Security.NonceVerification
		}

		if ( ! empty( $_REQUEST['order'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			echo '<input type="hidden" name="order" value="' . esc_attr( sanitize_text_field( wp_unslash( $_REQUEST['order'] ) ) ) . '" />'; // phpcs:ignore WordPress.Security.NonceVerification
		}
		?>
		<p class="search-box">
			<?php do_action( 'delivery_search' ); ?>
			<label class="screen-reader-text" for="<?php esc_attr( $input_id ); ?>"><?php esc_html_e( $text, 'woocommerce-prdd-lite' ); // phpcs:ignore ?>:</label>
			<input type="search" id="<?php esc_attr( $input_id ); ?>" name="s" value="<?php _admin_search_query(); ?>" />
			<?php submit_button( $text, 'button', false, false, array( 'ID' => 'search-submit' ) ); ?><br/>
		</p>
		<?php
	}
	/**
	 * Get the total product delivery count and apply at appropriate places
	 *
	 * @since 1.1
	 */
	public function get_delivery_counts() {
		$args = array();
		if ( isset( $_GET['user'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			$args['user'] = urldecode( sanitize_text_field( wp_unslash( $_GET['user'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification
		} elseif ( isset( $_GET['s'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			$args['s'] = urldecode( sanitize_text_field( wp_unslash( $_GET['s'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification
		}

		if ( ! empty( $_GET['start-date'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			$args['start-date'] = urldecode( sanitize_text_field( wp_unslash( $_GET['start-date'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification
		}

		if ( ! empty( $_GET['end-date'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			$args['end-date'] = urldecode( sanitize_text_field( wp_unslash( $_GET['end-date'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification
		}

		$deliveries_count              = $this->prdd_lite_count_deliveries( $args );
		$this->total_count             = $deliveries_count['total_count'];
		$this->future_count            = $deliveries_count['future_count'];
		$this->today_delivery_count    = $deliveries_count['today_delivery_count'];
		$this->tomorrow_delivery_count = $deliveries_count['tomorrow_delivery_count'];
	}
	/**
	 * Get the product deliveries counts
	 *
	 * @param mixed $args Filters added for counts.
	 * @return string $deliveries_count Total deliveries Count.
	 * @since 2.0
	 * @globals mixed $wpdb
	 */
	public function prdd_lite_count_deliveries( $args ) {
		global $wpdb;

		$deliveries_count = array(
			'total_count'             => 0,
			'future_count'            => 0,
			'today_delivery_count'    => 0,
			'tomorrow_delivery_count' => 0,
		);

		// Today's date.
		$current_time  = current_time( 'timestamp' );
		$current_date  = gmdate( 'Y-m-d', $current_time ); // phpcs:ignore
		$tomorrow_date = gmdate( 'Y-m-d', $current_time + 86400 ); // phpcs:ignore

		$get_dates  = array();
		$start_date = '';
		$end_date   = '';
		if ( isset( $args['start-date'] ) ) {
			$start_date = $args['start-date'];
		}

		if ( isset( $args['end-date'] ) ) {
			$end_date = $args['end-date'];
		}

		if ( '' === $start_date || '' === $end_date || '1970-01-01' === $start_date || '1970-01-01' === $end_date ) {

			$get_order_item_ids = $wpdb->get_results( $wpdb->prepare( 'SELECT order_item_id FROM `' . $wpdb->prefix . 'woocommerce_order_itemmeta` WHERE meta_key = %s', '_prdd_lite_date' ) ); // WPCS: db call ok, WPCS: cache ok.

			if ( is_array( $get_order_item_ids ) && count( $get_order_item_ids ) > 0 ) {
				foreach ( $get_order_item_ids as $item_key => $item_value ) {

					$get_order_id = $wpdb->get_results( $wpdb->prepare( 'SELECT order_id FROM `' . $wpdb->prefix . 'woocommerce_order_items` WHERE order_item_id = %d', $item_value->order_item_id ) ); // WPCS: db call ok, WPCS: cache ok.
					if ( false !== get_post_status( $get_order_id[0]->order_id ) ) {
						$order = new WC_Order( $get_order_id[0]->order_id );
					} else {
						continue;
					}

					if ( version_compare( get_option( 'woocommerce_version ' ), '3.0.0', '>=' ) ) {
						$order_post_status = $order->get_status();
						if ( 'trash' !== $order_post_status ) {
							$order_post_status = 'wc-' . $order_post_status;
						}
					} else {
						$order_post_status = $order->post_status;
					}

					if ( isset( $order_post_status ) && ( '' !== $order_post_status ) && ( 'wc-cancelled' !== $order_post_status ) && ( 'wc-refunded' !== $order_post_status ) && ( 'trash' !== $order_post_status ) && ( 'wc-failed' !== $order_post_status ) ) {
						$get_dates[] = $wpdb->get_results( $wpdb->prepare( 'SELECT meta_value, meta_key FROM `' . $wpdb->prefix . 'woocommerce_order_itemmeta` WHERE meta_key = %d AND order_item_id = %d', '_prdd_lite_date', $item_value->order_item_id ) ); // WPCS: cache ok, db call ok.
					}
				}
			}
		}

		foreach ( $get_dates as $key => $value ) {
			if ( count( $value ) > 0 ) {
				foreach ( $value as $k => $v ) {
					if ( '_prdd_lite_date' === $v->meta_key ) {
						$deliveries_count['total_count'] += 1;
						if ( $v->meta_value >= $current_date ) {
							$deliveries_count['future_count'] += 1;
						}
						if ( $v->meta_value === $current_date ) {
							$deliveries_count['today_delivery_count'] += 1;
						} elseif ( $v->meta_value === $tomorrow_date ) {
							$deliveries_count['tomorrow_delivery_count'] += 1;
						}
					}
				}
			}
		}
		return $deliveries_count;
	}
	/**
	 * It will generate the all the product deliveries date and time under View Deliveries section.
	 *
	 * @globals mixed $wpdb
	 * @return array $return_delivery_display Key and value of all the columns
	 * @since  2.0
	 */
	public function deliveries_data() {
		global $wpdb;
		$return_deliveries = array();
		$results           = array();

		if ( isset( $_GET['paged'] ) && sanitize_text_field( wp_unslash( $_GET['paged'] ) ) > 1 ) {  // phpcs:ignore WordPress.Security.NonceVerification
			$page_number = sanitize_text_field( wp_unslash( $_GET['paged'] ) ) - 1; // phpcs:ignore WordPress.Security.NonceVerification
		} else {
			$page_number = 0;
		}

		$per_page = $this->per_page;

		$current_time  = current_time( 'timestamp' );
		$current_date  = gmdate( 'Y-m-d', $current_time ); // phpcs:ignore
		$tomorrow_date = gmdate( 'Y-m-d', $current_time + 86400 ); // phpcs:ignore
		$i             = 0;

		if ( isset( $_GET['paged'] ) && sanitize_text_field( wp_unslash( $_GET['paged'] ) ) > 0 ) { // phpcs:ignore WordPress.Security.NonceVerification
			$start_record = ( sanitize_text_field( wp_unslash( $_GET['paged'] ) ) - 1 ) * $per_page; // phpcs:ignore WordPress.Security.NonceVerification
			$limit        = "LIMIT $start_record, $per_page";
		} else {
			$limit = "LIMIT $per_page";
		}

		if ( isset( $_GET['status'] ) && 'future' === $_GET['status'] ) { // phpcs:ignore WordPress.Security.NonceVerification
			$get_order_item_id = $wpdb->get_results( $wpdb->prepare( "SELECT t1.order_id, GROUP_CONCAT( t2.meta_key, '=' ,t2.meta_value SEPARATOR '&' ) AS meta_values FROM  `" . $wpdb->prefix . "woocommerce_order_items` AS t1, `" . $wpdb->prefix . "woocommerce_order_itemmeta` AS t2 WHERE t2.meta_key IN (%s,%s) AND t2.order_item_id IN ( SELECT order_item_id FROM `" . $wpdb->prefix . "woocommerce_order_itemmeta` WHERE meta_key = %s AND meta_value >= %s ) AND t1.order_item_id = t2.order_item_id AND t1.order_id IN ( SELECT ID FROM `" . $wpdb->prefix . "posts` WHERE post_type = 'shop_order' AND post_status NOT IN ( 'wc-cancelled', 'wc-refunded', 'trash', 'wc-failed', '' ) ) GROUP BY t1.order_item_id HAVING COUNT( DISTINCT t2.meta_key ) = 3 OR COUNT( DISTINCT t2.meta_key ) = 2 ORDER BY t1.order_id DESC $limit", '_product_id', '_prdd_lite_date', '_prdd_lite_date', $current_date ) ); // phpcs:ignore
		} elseif ( isset( $_GET['status'] ) && 'today_delivery' === $_GET['status'] ) { // phpcs:ignore WordPress.Security.NonceVerification
			$get_order_item_id = $wpdb->get_results( $wpdb->prepare( "SELECT t1.order_id, GROUP_CONCAT( t2.meta_key, '=' ,t2.meta_value SEPARATOR '&' ) AS meta_values FROM  `" . $wpdb->prefix . "woocommerce_order_items` AS t1, `" . $wpdb->prefix . "woocommerce_order_itemmeta` AS t2 WHERE t2.meta_key IN (%s,%s) AND t2.order_item_id IN ( SELECT order_item_id FROM `" . $wpdb->prefix . "woocommerce_order_itemmeta` WHERE meta_key = %s AND meta_value = %s ) AND t1.order_item_id = t2.order_item_id AND t1.order_id IN ( SELECT ID FROM `" . $wpdb->prefix . "posts` WHERE post_type = 'shop_order' AND post_status NOT IN ( 'wc-cancelled', 'wc-refunded', 'trash', 'wc-failed', '' ) ) GROUP BY t1.order_item_id HAVING COUNT( DISTINCT t2.meta_key ) = 3 OR COUNT( DISTINCT t2.meta_key ) = 2 ORDER BY t1.order_id DESC $limit", '_product_id', '_prdd_lite_date', '_prdd_lite_date', $current_date ) ); // phpcs:ignore
		} elseif ( isset( $_GET['status'] ) && 'tomorrow_delivery' === $_GET['status'] ) { // phpcs:ignore WordPress.Security.NonceVerification
			$get_order_item_id = $wpdb->get_results( $wpdb->prepare( "SELECT t1.order_id, GROUP_CONCAT( t2.meta_key, '=' ,t2.meta_value SEPARATOR '&' ) AS meta_values FROM  `" . $wpdb->prefix . "woocommerce_order_items` AS t1, `" . $wpdb->prefix . "woocommerce_order_itemmeta` AS t2 WHERE t2.meta_key IN (%s,%s) AND t2.order_item_id IN ( SELECT order_item_id FROM `" . $wpdb->prefix . "woocommerce_order_itemmeta` WHERE meta_key = %s AND meta_value = %s ) AND t1.order_item_id = t2.order_item_id AND t1.order_id IN ( SELECT ID FROM `" . $wpdb->prefix . "posts` WHERE post_type = 'shop_order' AND post_status NOT IN ( 'wc-cancelled', 'wc-refunded', 'trash', 'wc-failed', '' ) ) GROUP BY t1.order_item_id HAVING COUNT( DISTINCT t2.meta_key ) = 3 OR COUNT( DISTINCT t2.meta_key ) = 2 ORDER BY t1.order_id DESC $limit", '_product_id', '_prdd_lite_date', '_prdd_lite_date', $tomorrow_date ) ); // phpcs:ignore
		} else {
			$get_order_item_id = $wpdb->get_results( $wpdb->prepare( "SELECT t1.order_id, GROUP_CONCAT( t2.meta_key, '=' ,t2.meta_value SEPARATOR '&' ) AS meta_values FROM  `" . $wpdb->prefix . "woocommerce_order_items` AS t1, `" . $wpdb->prefix . "woocommerce_order_itemmeta` AS t2 WHERE t2.meta_key IN (%s,%s) AND t1.order_item_id = t2.order_item_id AND t1.order_id IN ( SELECT ID FROM `" . $wpdb->prefix . "posts` WHERE post_type = 'shop_order' AND post_status NOT IN ( 'wc-cancelled', 'wc-refunded', 'trash', 'wc-failed', '' ) ) GROUP BY t1.order_item_id HAVING COUNT( DISTINCT t2.meta_key ) = 3 OR COUNT( DISTINCT t2.meta_key ) = 2 ORDER BY t1.order_id DESC $limit", '_prdd_lite_date', '_product_id' ) ); // phpcs:ignore
		}

		foreach ( $get_order_item_id as $key => $value ) {
			$time_slot         = '';
			$date              = '';
			$selected_quantity = 0;
			$amount            = 0;

			if ( false !== get_post_status( $value->order_id ) ) {
				$order = new WC_Order( $value->order_id );
			} else {
				continue;
			}

			$meta_values = explode( '&', $value->meta_values );
			foreach ( $meta_values as $mk => $mv ) {
				$values = explode( '=', $mv );
				if ( '_prdd_lite_date' === $values[0] ) {
					$date = $values[1];
				}

				if ( '_product_id' === $values[0] ) {
					$product_id = $values[1];
				}
			}

			$return_deliveries[ $i ] = new stdClass();
			if ( version_compare( get_option( 'woocommerce_version ' ), '3.0.0', '>=' ) ) {
				$billing_first_name = $order->get_billing_first_name();
				$billing_last_name  = $order->get_billing_last_name();
			} else {
				$billing_first_name = $order->get_billing_first_name();
				$billing_last_name  = $order->get_billing_last_name();
			}

			$return_deliveries[ $i ]->name = $billing_first_name . ' ' . $billing_last_name;
			$get_quantity                  = $order->get_items();
			// The array needs to be reversed as we r displaying the last item first.
			$get_quantity = array_reverse( $get_quantity, true );
			foreach ( $get_quantity as $k => $v ) {
				$product_exists = 'NO';
				if ( $v['product_id'] == $product_id ) { // phpcs:ignore
					foreach ( $return_deliveries as $deliver_key => $deliver_value ) {
						if ( isset( $deliver_value->ID ) && $deliver_value->ID === $value->order_id && $v['product_id'] === $deliver_value->product_id ) {
							if ( isset( $deliver_value->item_id ) && $k === $deliver_value->item_id ) {
								$product_exists = 'YES';
							}
						}
					}
					if ( 'NO' === $product_exists ) {
						$selected_quantity                = $v['qty'];
						$amount                           = $v['line_total'] + $v['line_tax'];
						$return_deliveries[ $i ]->item_id = $k;
						break;
					}
				}
			}
			$product_name = get_the_title( $product_id );
			// Populate the array.
			$return_deliveries[ $i ]->ID            = $value->order_id;
			$return_deliveries[ $i ]->product_id    = $product_id;
			$return_deliveries[ $i ]->product_name  = $product_name;
			$return_deliveries[ $i ]->delivery_date = $date;
			$return_deliveries[ $i ]->quantity      = $selected_quantity;
			$return_deliveries[ $i ]->amount        = $amount;
			if ( version_compare( get_option( 'woocommerce_version ' ), '3.0.0', '>=' ) ) {
				$order_date_obj = $order->get_date_created();
				$order_date     = $order_date_obj->format( 'Y-m-d' );
			} else {
				$order_date = $order->completed_date;
			}
			$return_deliveries[ $i ]->order_date = $order_date;
			$i++;
		}

		// sort for delivery date.
		if ( isset( $_GET['orderby'] ) && 'delivery_date' === $_GET['orderby'] ) { // phpcs:ignore WordPress.Security.NonceVerification
			if ( isset( $_GET['order'] ) && 'asc' === $_GET['order'] ) { // phpcs:ignore WordPress.Security.NonceVerification
				usort( $return_deliveries, array( __CLASS__, 'prdd_lite_class_delivery_date_asc' ) );
			} else {
				usort( $return_deliveries, array( __CLASS__, 'prdd_lite_class_delivery_date_dsc' ) );
			}
		}
		return apply_filters( 'prdd_lite_deliveries_table_data', $return_deliveries );
	}

	/**
	 * It will sort the ascending data based on the Delivery Date.
	 *
	 * @param array | object $value1 All data of the list.
	 * @param array | object $value2 All data of the list.
	 * @return timestamp
	 * @since  1.1
	 */
	public function prdd_lite_class_delivery_date_asc( $value1, $value2 ) {
		if ( isset( $value1->delivery_date ) && isset( $value2->delivery_date ) ) {
			$date_two           = '';
			$date_one           = '';
			$value_one          = $value1->delivery_date;
			$value_two          = $value2->delivery_date;
			$date_formatted_one = date_create_from_format( 'Y-m-d', $value_one ); // phpcs:ignore
			$date_formatted_two = date_create_from_format( 'Y-m-d', $value_two ); // phpcs:ignore
			if ( isset( $date_formatted_one ) && '' !== $date_formatted_one ) {
				$date_one = date_format( $date_formatted_one, 'Y-m-d' );
			}
			if ( isset( $date_formatted_two ) && '' !== $date_formatted_two ) {
				$date_two = date_format( $date_formatted_two, 'Y-m-d' );
			}
			return strtotime( $date_one ) - strtotime( $date_two );
		} else {
			return 1;
		}
	}
	/**
	 * It will sort the descending data based on the Delivery Date.
	 *
	 * @param  array | object $value1 All data of the list.
	 * @param  array | object $value2 All data of the list.
	 * @return timestamp
	 * @since  1.1
	 */
	public function prdd_lite_class_delivery_date_dsc( $value1, $value2 ) {
		if ( isset( $value1->delivery_date ) && isset( $value2->delivery_date ) ) {
			$date_two           = '';
			$date_one           = '';
			$value_one          = $value1->delivery_date;
			$value_two          = $value2->delivery_date;
			$date_formatted_one = date_create_from_format( 'Y-m-d', $value_one );
			$date_formatted_two = date_create_from_format( 'Y-m-d', $value_two );
			if ( isset( $date_formatted_one ) && '' !== $date_formatted_one ) {
				$date_one = date_format( $date_formatted_one, 'Y-m-d' );
			}
			if ( isset( $date_formatted_two ) && '' !== $date_formatted_two ) {
				$date_two = date_format( $date_formatted_two, 'Y-m-d' );
			}
			return strtotime( $date_two ) - strtotime( $date_one );
		} else {
			return 1;
		}
	}

	/**
	 * It will display the data for Delivery Date.
	 *
	 * @param  array | object $delivery All data of the list.
	 * @param  stirng         $column_name Name of the column.
	 * @return string $value Data of the column
	 * @since  1.1
	 */
	public function column_default( $delivery, $column_name ) {
		switch ( $column_name ) {
			case 'ID':
				$value = '<a href="post.php?post=' . $delivery->ID . '&action=edit">' . $delivery->ID . '</a>';
				break;
			case 'delivery_date':
				$date                 = strtotime( $delivery->delivery_date );
				$date_formats         = prdd_lite_get_delivery_arrays( 'prdd_lite_date_formats' );
				$date_format_selected = get_option( 'prdd_lite_date_format' );
				if ( '' === $date_format_selected ) {
					$date_format_selected = 'mm/dd/y';
				}

				// get the global settings to find the date formats.
				$date_format_set = $date_formats[ $date_format_selected ];
				$value           = gmdate( $date_format_set, $date ); // phpcs:ignore
				break;
			case 'amount':
				$amount          = ! empty( $delivery->amount ) ? $delivery->amount : 0;
				$currency_symbol = get_woocommerce_currency_symbol();
				$value           = $currency_symbol . number_format( $amount, 2 );
				break;
			case 'actions':
				$value = '<a href="post.php?post=' . $delivery->ID . '&action=edit">View Order</a>';
				break;
			default:
				$value = isset( $delivery->$column_name ) ? $delivery->$column_name : '';
				break;
		}
		return apply_filters( 'prdd_lite_delivery_table_column_default', $value, $delivery, $column_name );
	}
}
?>
