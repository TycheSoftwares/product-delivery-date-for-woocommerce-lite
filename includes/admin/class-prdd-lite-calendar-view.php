<?php
/**
 * PRDD Calendar View
 *
 * @author  Tyche Softwares
 * @package Product-Delivery-Date-Lite/Calendar View
 * @since   3.3.0
 */

if ( ! class_exists( 'Prdd_Lite_Calendar_View' ) ) {

	/**
	 * Calendar View
	 *
	 * @since 3.3.0
	 */
	class Prdd_Lite_Calendar_View {

		/**
		 * Calendar View Page.
		 */
		public static function prdd_lite_calendar_view_page() {
			?>
			<div class="wrap">
				<div style="display: flex; align-items: center; margin-bottom: 20px;">
					<h1><?php esc_html_e( 'Calendar View', 'woocommerce-prdd-lite' ); ?></h1>
					<a style="margin-left: 8px;" href="<?php echo admin_url( 'admin.php?page=woocommerce_prdd_lite_history_page' ); ?>"class="button button-primary">
						<?php esc_html_e( 'List View', 'woocommerce-prdd-lite' ); ?>
					</a>
				</div>
				
				<div id="prdd-calendar-loader" style="display: flex; align-items: center; justify-content: center; min-height: 100px;">
					<div style="text-align: center;">
						<div class="prdd-spinner"></div>
						<p style="margin-top: 15px; color: #666;"><?php esc_html_e( 'Loading calendar...', 'woocommerce-prdd-lite' ); ?></p>
					</div>
				</div>
				
				<div id="prdd-calendar"></div>
			</div>
			<?php
		}

		/**
		 * Preparing event data for the calendar view.
		 */
		public static function prdd_adminevent_event_jsons() {

			if ( isset( $_GET['action'] ) && $_GET['action'] == 'prdd-adminend-events-jsons' ) { // phpcs:ignore

				if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ), 'ajax-nonce' ) ) {
					echo '<p style="color: red;">Security check failed</p>';
					wp_die();
				}
				global $wpdb;

				$data        = array();
				$order       = array();
				$event_start = '';
				$event_end   = '';

				if ( isset( $_GET['start'] ) ) {
					$event_start = gmdate( 'Y-m-d', strtotime( sanitize_text_field( wp_unslash( $_GET['start'] ) ) ) );
				}

				if ( isset( $_GET['end'] ) ) {
					$event_end = gmdate( 'Y-m-d', strtotime( sanitize_text_field( wp_unslash( $_GET['end'] ) ) ) );
				}
				$query_get_order_item_ids = 'SELECT order_item_id FROM `' . $wpdb->prefix . "woocommerce_order_itemmeta` WHERE meta_key = '_prdd_date' and meta_value > '$event_start' and meta_value < '$event_end'";
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL
				$get_order_item_ids = $wpdb->get_results( $query_get_order_item_ids ); // nosemgrep:audit.php.wp.security.sqli.input-in-sinks
				$order_post_status  = '';

				if ( is_array( $get_order_item_ids ) && count( $get_order_item_ids ) > 0 ) {
					foreach ( $get_order_item_ids as $item_key => $item_value ) {
						$time_slot      = '';
						$date           = '';
						$product_id     = '';
						$from_time      = '';
						$to_time        = '';
						$value          = new stdClass();
						$query_order_id = 'SELECT order_id FROM `' . $wpdb->prefix . 'woocommerce_order_items` WHERE order_item_id = %d';
						// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL
						$get_order_id = $wpdb->get_results( $wpdb->prepare( $query_order_id, $item_value->order_item_id ) );
						$order        = wc_get_order( $get_order_id[0]->order_id );

						if ( $order ) {
							if ( version_compare( get_option( 'woocommerce_version ' ), '3.0.0', '>=' ) ) {
								if ( is_object( $order ) ) {
									$order_post_status = $order->get_status();
									if ( $order_post_status != 'trash' ) {
										$order_post_status = 'wc-' . $order_post_status;
									}
								}
							} else {
								$order_post_status = $order->post_status;
							}

							if ( isset( $order_post_status ) && ( $order_post_status != '' ) && ( $order_post_status != 'wc-cancelled' ) && ( $order_post_status != 'wc-refunded' ) && ( $order_post_status != 'trash' ) && ( $order_post_status != 'wc-failed' ) ) {
								$query_get_dates = 'SELECT meta_value, meta_key FROM `' . $wpdb->prefix . 'woocommerce_order_itemmeta`
																			WHERE meta_key IN (%s,%s,%s)
																			AND order_item_id = %d';
								// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL
								$get_dates = $wpdb->get_results( $wpdb->prepare( $query_get_dates, '_prdd_date', '_prdd_time_slot', '_product_id', $item_value->order_item_id ) );
								foreach ( $get_dates as $k => $v ) {
									if ( $v->meta_key === '_prdd_date' ) {
										$date = $v->meta_value;
									}
									if ( $v->meta_key === '_prdd_time_slot' ) {
										$time_slot = $v->meta_value;
									}
									if ( $v->meta_key === '_product_id' ) {
										$product_id = $v->meta_value;
									}
								}
								$value->post_id            = $product_id;
								$value->delivery_date      = $date;
								$value->delivery_timestamp = strtotime( $date );
								$product_name              = html_entity_decode( get_the_title( $product_id ) , ENT_COMPAT, 'UTF-8' );
								$time_slot_arr = explode( ' - ', $time_slot );
								if ( isset( $time_slot_arr[0] ) && $time_slot_arr[0] != '' ) {
									$from_time        = $time_slot_arr[0];
									$value->from_time = $from_time;
								}
								if ( isset( $time_slot_arr[1] ) && $time_slot_arr[1] != '' ) {
									$to_time = $time_slot_arr[1];
									$value->to_time = $to_time;
								}
								if ( $from_time != '' && $to_time != '' ) { 
									$date_from_time      = $date;
									$date_from_time     .= ' ' . $from_time;
									$post_from_timestamp = strtotime( $date_from_time );
									$post_from_date      = gmdate( 'Y-m-d H:i:s', $post_from_timestamp );

									$date_to_time      = $date;
									$date_to_time     .= ' ' . $to_time;
									$post_to_timestamp = strtotime( $date_to_time );
									$post_to_date      = gmdate( 'Y-m-d H:i:s', $post_to_timestamp );

									array_push(
										$data,
										array(
											'id'    => $get_order_id[0]->order_id,
											'title' => $product_name,
											'start' => $post_from_date,
											'end'   => $post_to_date,
											'value' => $value,
										)
									);
								} else if ( $from_time != '' ) {
									$date_from_time      = $date;
									$date_from_time     .= ' ' . $from_time;
									$post_from_timestamp = strtotime( $date_from_time );
									$post_from_date      = gmdate( 'Y-m-d H:i:s', $post_from_timestamp );

									$time     = strtotime( $from_time );
									$end_time = gmdate( 'H:i', strtotime( '+30 minutes', $time ) );

									$date_to_time      = $date;
									$date_to_time     .= ' ' . $end_time;
									$post_to_timestamp = strtotime( $date_to_time );
									$post_to_date      = gmdate ( 'Y-m-d H:i:s', $post_to_timestamp );

									array_push(
										$data,
										array(
											'id'    => $get_order_id[0]->order_id,
											'title' => $product_name,
											'start' => $post_from_date,
											'end'   => $post_to_date,
											'value' => $value,
										)
									);
								} else {
									array_push(
										$data,
										array(
											'id'    => $get_order_id[0]->order_id,
											'title' => $product_name,
											'start' => $date,
											'end'   => $date,
											'value' => $value,
										)
									);
								}
							}
						}
					}
				}
				echo json_encode( $data ); // phpcs:ignore
				exit;
			}
		}

		/**
		 * Show event details in the popop when clicked on calendar event.
		 */
		public static function prdd_calender_content() {

			if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ), 'ajax-nonce' ) ) {
				echo '<p style="color: red;">Security check failed</p>';
				wp_die();
			}

			$content                 = '';
			$date_format_set         = '';
			$delivery_date_formatted = '';
			$date_formats            = prdd_lite_get_delivery_arrays( 'prdd_lite_date_formats' );
			$global_settings         = json_decode( get_option( 'woocommerce_prdd_global_settings' ) );
			if ( isset( $global_settings->prdd_date_format ) ) {
				$date_format_set = $date_formats[ $global_settings->prdd_date_format ];
			}

			if ( ! empty( $_REQUEST['order_id'] ) && ! empty( $_REQUEST['event_value'] ) ) {

				$order_id = isset( $_REQUEST['order_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['order_id'] ) ) : 0;
				$order    = wc_get_order( $order_id );
				if ( ! $order ) {
					echo '<p style="color: red;">Order not found.</p>';
					wp_die();
				}
				$order_items              = $order->get_items();
				$attribute_name           = '';
				$attribute_selected_value = '';

				$content  = '<table>';
				$content .= '<tr> <td> <strong>Order: </strong></td><td><a href="post.php?post=' . esc_html( $order->get_id() ) . '&action=edit">#' . esc_html( $order->get_id() ) . '</a></td></tr>';
				$content .= '<tr> <td> <strong>Product Name:</strong></td><td>' . esc_html( get_the_title( $_REQUEST['event_value']['post_id'] ) ) . '</td></tr>';
				$content .= '<tr> <td> <strong>Customer Name:</strong></td><td>' . esc_html( $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() ) . '</td></tr>';

				foreach ( $order_items as $item ) {
					if ( '' !== $item['variation_id'] && isset( $variation_product['_product_attributes'] ) ) {
						$variation_product              = get_post_meta( $item['product_id'] );
						$product_variation_array_string = $variation_product['_product_attributes'];
						$product_variation_array        = unserialize( $product_variation_array_string[0] );
						foreach ( $product_variation_array as $product_variation_key => $product_variation_value ) {
							$item_data = $item->get_data();
							if ( array_key_exists( $product_variation_key, $item_data ) ) {
								$attribute_name           = esc_html( $product_variation_value['name'] );
								$attribute_selected_value = esc_html( $item[ $product_variation_key ] );
								$content                 .= '<tr><td><strong>' . $attribute_name . ':</strong></td><td>' . $attribute_selected_value . '</td></tr>';
							}
						}
					}
				}

				if ( isset( $_REQUEST['event_value']['delivery_date'] ) && '0000-00-00' !== $_REQUEST['event_value']['delivery_date'] ) {
					$date       = strtotime( sanitize_text_field( wp_unslash( $_REQUEST['event_value']['delivery_date'] ) ) );
					$value_date = gmdate( $date_format_set, $date );
					if ( '' !== $delivery_date_formatted ) {
						$value_date = $delivery_date_formatted;
					}
					$content .= '<tr><td><strong>Delivery Date:</strong></td><td>' . esc_html( $value_date ) . '</td></tr>';
				}

				// prdd Time.
				$time = '';
				if ( isset( $_REQUEST['event_value']['from_time'] ) && $_REQUEST['event_value']['from_time'] !== '' && isset( $_REQUEST['event_value']['to_time'] ) && $_REQUEST['event_value']['to_time'] !== '' ) {
					if ( isset( $global_settings->prdd_time_format ) && $global_settings->prdd_time_format == 12 ) {
						$to_time   = '';
						$from_time = gmdate( 'h:i A', strtotime( sanitize_text_field( wp_unslash( $_REQUEST['event_value']['from_time'] ) ) ) );
						$time      = $from_time;
						if ( isset( $_REQUEST['event_value']['to_time'] ) && $_REQUEST['event_value']['to_time'] != '' ) {
							$to_time = gmdate( 'h:i A', strtotime( sanitize_text_field( wp_unslash( $_REQUEST['event_value']['to_time'] ) ) ) );
							$time    = $from_time . ' - ' . $to_time;
						}
					} else {
						$time = sanitize_text_field( wp_unslash( $_REQUEST['event_value']['from_time'] ) ) . ' - ' . sanitize_text_field( wp_unslash( $_REQUEST['event_value']['to_time'] ) );
					}

					$content .= '<tr><td><strong>Time:</strong></td><td>' . esc_html( $time ) . '</td></tr>';
				} else if ( isset( $_REQUEST['event_value']['from_time'] ) && $_REQUEST['event_value']['from_time'] != '' ) {
					if ( isset( $global_settings->prdd_time_format ) && $global_settings->prdd_time_format == 12 ) {
						$to_time   = '';
						$from_time = gmdate( 'h:i A', strtotime( sanitize_text_field( wp_unslash( $_REQUEST['event_value']['from_time'] ) ) ) );
						$time      = $from_time . ' - Open-end';
					} else {
						$time = sanitize_text_field( wp_unslash( $_REQUEST['event_value']['from_time'] ) ) . ' - Open-end';
					}
					$content .= '<tr> <td><strong>Time:</strong></td><td>' . esc_html( $time ) . '</td> </tr>';
				}
				$content .= '</table>';
				if ( isset( $_REQUEST['event_value']['post_id'] ) ) {
					$post_image = get_the_post_thumbnail( sanitize_text_field( wp_unslash( $_REQUEST['event_value']['post_id'] ) ), array( 100, 100 ) );
					if ( ! empty( $post_image ) ) {
						$content = '<div style="float:left; margin:0px 5px 5px 0px; ">' . $post_image . '</div>' . $content;
					}
				}
			}

			// nosemgrep:audit.php.wp.security.xss.unescaped-stored-option.
			echo $content; // nosemgrap
			die();
		}
	}
}
$prdd_lite_calendar_view = new Prdd_Lite_Calendar_View();
