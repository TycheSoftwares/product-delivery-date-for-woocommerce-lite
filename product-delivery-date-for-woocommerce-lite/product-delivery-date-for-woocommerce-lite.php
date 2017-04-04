<?php 
/*
Plugin Name: Product Delivery Date for WooCommerce - Lite
Description: This plugin lets you capture the Delivery Date for each product.
Version: 1.4
Author: Tyche Softwares
Author URI: http://www.tychesoftwares.com/
*/

global $PrddLiteUpdateChecker;
$PrddLiteUpdateChecker = '1.4';

register_uninstall_hook( __FILE__, 'prdd_woocommerce_lite_delete');

/* ******************************************************************** 
 * This function will Delete all the records of product delivery date lite plugin
 ******************************************************************************/
function prdd_woocommerce_lite_delete(){
	global $wpdb;
	$sql_table_post_meta = "DELETE FROM `" . $wpdb->prefix . "postmeta` WHERE meta_key='_woo_prdd_lite_enable_delivery_date'";
	$results = $wpdb->get_results ( $sql_table_post_meta );
}

function is_prdd_lite_active() {
	if ( is_plugin_active( 'product-delivery-date-lite/product-delivery-date-lite.php' ) ) {
		return true;
	} else {
		return false;
	}
}

/**
* Localisation
**/

// For language translation
function  prdd_lite_update_po_file(){
    $domain = 'woocommerce-prdd-lite';
    $locale = apply_filters( 'plugin_locale', get_locale(), $domain );
    if ( $loaded = load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '-' . $locale . '.mo' ) ) {
        return $loaded;
    } else {
        load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
    }
}

/**
* woocommerce_prdd_lite class
**/
if ( !class_exists( 'woocommerce_prdd_lite' ) ) {
    class woocommerce_prdd_lite {
        public function __construct() {
            //Initialize settings
            register_activation_hook( __FILE__,                   array( &$this, 'prdd_lite_activate' ) );
			add_action( 'init',                                   'prdd_lite_update_po_file' );
			add_action( 'add_meta_boxes',                         array( &$this, 'prdd_lite_box' ), 10 );
			add_action( 'woocommerce_process_product_meta',       array( &$this, 'prdd_lite_process_box' ), 1, 2 );
			add_action( 'woocommerce_duplicate_product' ,         array( &$this, 'prdd_lite_product_duplicate' ), 10, 2 );
			add_action( 'admin_init',                             array( &$this, 'prdd_lite_update_db_check' ) );
				
			add_action( 'woocommerce_before_single_product',      array( &$this, 'prdd_lite_front_side_scripts_js' ) );
			add_action( 'woocommerce_before_single_product',      array( &$this, 'prdd_lite_front_side_scripts_css' ) );

			add_action( 'woocommerce_before_add_to_cart_button',  array( &$this, 'prdd_lite_after_add_to_cart' ) );
			add_filter( 'woocommerce_add_cart_item_data',         array( &$this, 'prdd_lite_add_cart_item_data' ), 25, 2 );
			add_filter( 'woocommerce_get_item_data',              array( &$this, 'prdd_lite_get_item_data' ), 25, 2 );
			add_action( 'woocommerce_checkout_update_order_meta', array( &$this, 'prdd_lite_order_item_meta' ), 10, 2 );
            add_filter( 'woocommerce_hidden_order_itemmeta',      array( &$this, 'prdd_lite_hidden_order_itemmeta' ), 10, 1 );
        }
			
		/**
		* This function detects when the product delivery date plugin is activated
		*/
        function prdd_lite_activate() {
            update_option( 'woocommerce_prdd_lite_db_version', '1.4' );
        }

        function prdd_lite_update_db_check() {
            $prdd_plugin_version = get_option( 'woocommerce_prdd_lite_db_version' );
            if ( $prdd_plugin_version != $this->get_plugin_version() ) {
                update_option( 'woocommerce_prdd_lite_db_version', '1.4' );
            }
        }
        
        /**
         * This function returns the product delivery date plugin version number
         */
        function get_plugin_version() {
            $plugin_data    = get_plugin_data( __FILE__ );
            $plugin_version = $plugin_data[ 'Version' ];
            return $plugin_version;
        }
        
		/**
        * This function adds a meta box for delivery settings on product page.
        */
        function prdd_lite_box() {
            add_meta_box( 'woocommerce-prdd-lite', __( 'Product Delivery Date', 'woocommerce-prdd-lite' ), array( &$this, 'prdd_lite_meta_box' ), 'product', 'normal', 'core' );
        }
			
        /**
        * This function updates the delivery settings for each
		* product in the wp_postmeta table in the database .
		* It will be called when update / publish button clicked on admin side.
		*/
        function prdd_lite_process_box( $post_id, $post ) {
            $duplicate_of = $this->prdd_lite_get_product_id( $post_id );
            $enable_date = $prdd_minimum_delivery_time = $prdd_maximum_number_days = '';
            if ( isset( $_POST[ 'prdd_lite_enable_date' ] ) ) {
                $enable_date = $_POST[ 'prdd_lite_enable_date' ];
            }
            
            if ( isset( $_POST[ 'prdd_lite_minimum_delivery_time' ] ) ) {
                $prdd_minimum_delivery_time = $_POST[ 'prdd_lite_minimum_delivery_time' ];
            }
            
            if ( isset( $_POST[ 'prdd_lite_maximum_number_days' ] ) ) {
                $prdd_maximum_number_days = $_POST[ 'prdd_lite_maximum_number_days' ];
            }
            
            update_post_meta( $duplicate_of, '_woo_prdd_lite_enable_delivery_date', $enable_date );
            update_post_meta( $duplicate_of, '_woo_prdd_lite_minimum_delivery_time', $prdd_minimum_delivery_time );
            update_post_meta( $duplicate_of, '_woo_prdd_lite_maximum_number_days', $prdd_maximum_number_days );
		}
			
		/**
        * This function displays the settings for the product in the Product Delivery Date meta box on the admin product page.
		*/
		function prdd_lite_meta_box() {
		    global $post;
		    $duplicate_of = $this->prdd_lite_get_product_id( $post->ID );
		    $prdd_enable_delivery_date = get_post_meta( $duplicate_of, '_woo_prdd_lite_enable_delivery_date', true );
		    ?>
		    <table>
                <tr>
                    <td>
                        <b><label for="prdd_lite_enable_date"> <?php _e( 'Enable Delivery Date:', 'woocommerce-prdd-lite' );?> </label></b>
                    </td>
                    <td>
                        <?php 
                        $enable_date = '';
                        if( isset( $prdd_enable_delivery_date ) && $prdd_enable_delivery_date == 'on' ) {
                            $enable_date = 'checked';
                        }
                        ?>
                        <input type="checkbox" id="prdd_lite_enable_date" name="prdd_lite_enable_date" <?php echo $enable_date;?> >
                    </td>
                    <td>
                        <img class="help_tip" width="16" height="16" data-tip="<?php _e( 'Enable Delivery Date on Products Page', 'woocommerce-prdd-lite' );?>" src="<?php echo plugins_url() ;?>/woocommerce/assets/images/help.png" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <b><label for="prdd_lite_minimum_delivery_time"> <?php _e( 'Minimum Delivery preparation time (in hours):', 'woocommerce-prdd-lite' );?> </label></b>
                    </td>
                    <td>
                        <?php 
                       	$prdd_minimum_delivery_time = get_post_meta( $duplicate_of, '_woo_prdd_lite_minimum_delivery_time', true );
                       	if ( $prdd_minimum_delivery_time == "" ) {
                       	    $prdd_minimum_delivery_time = "0";
                       	}
                       	?>
                        <input type="text" id="prdd_lite_minimum_delivery_time" name="prdd_lite_minimum_delivery_time" value="<?php echo $prdd_minimum_delivery_time; ?>" >
                    </td>
                    <td>
                        <img class="help_tip" width="16" height="16" data-tip="<?php _e( 'Enable Delivery after X number of hours from current WordPress time. The customer can select a delivery date that is available only after the minimum hours that are entered here. For example, if you need one day\'s advance notice for a delivery, enter 24 here.', 'woocommerce-prdd-lite' );?>" src="<?php echo plugins_url() ;?>/woocommerce/assets/images/help.png" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <b><label for="prdd_lite_maximum_number_days"> <?php _e( 'Number of Dates to choose:', 'woocommerce-prdd-lite' );?> </label></b>
                    </td>
                    <td>
                        <?php
                        $prdd_maximum_number_days = get_post_meta( $duplicate_of, '_woo_prdd_lite_maximum_number_days', true );
                        if ( $prdd_maximum_number_days == "" ) {
                            $prdd_maximum_number_days = "30";
                        }	
                        ?>
                        <input type="text" name="prdd_lite_maximum_number_days" id="prdd_lite_maximum_number_days" value="<?php echo sanitize_text_field( $prdd_maximum_number_days, true );?>" >
                    </td>
                    <td>
                        <img class="help_tip" width="16" height="16" data-tip="<?php _e( 'The maximum number of delivery dates available for your customers to choose deliveries from. For example, if you take only 2 months delivery in advance, enter 60 here.', 'woocommerce-prdd-lite' );?>" src="<?php echo plugins_url() ;?>/woocommerce/assets/images/help.png" />
                    </td>
                </tr>
		    </table>
		    <?php 
		}

        /**
        * This function duplicates the delivery settings of the original product to the new product.
		*/
        function prdd_lite_product_duplicate( $new_id, $post ) {
            global $wpdb;
			$old_id = $post->ID;
			$prdd_settings = get_post_meta( $old_id, '_woo_prdd_lite_enable_delivery_date' , true );
			update_post_meta( $new_id, '_woo_prdd_lite_enable_delivery_date', $prdd_settings );
			
			$prdd_minimum_delivery_time = get_post_meta( $old_id, '_woo_prdd_lite_minimum_delivery_time' , true );
			update_post_meta( $new_id, '_woo_prdd_lite_minimum_delivery_time', $prdd_minimum_delivery_time );
			
			$prdd_maximum_number_days = get_post_meta( $old_id, '_woo_prdd_lite_maximum_number_days' , true );
			update_post_meta( $new_id, '_woo_prdd_lite_maximum_number_days', $prdd_maximum_number_days );
        }
			
        /**
        * This function includes js files required for frontend.
		*/
        function prdd_lite_front_side_scripts_js() {
            global $post;
			if( is_product() || is_page() ) {
                $prdd_settings = get_post_meta( $post->ID, '_woo_prdd_lite_enable_delivery_date' , true );
			    if ( isset( $prdd_settings ) && $prdd_settings == 'on' ) {
                    $plugin_version_number = get_option( 'woocommerce_prdd_lite_db_version' );
                    wp_enqueue_script( 'jquery' );
                    wp_enqueue_script( 'jquery-ui-datepicker' );
                    wp_deregister_script( 'jqueryui' );
                    wp_enqueue_script( 'jqueryui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js', '', '', false );
                }
            }
        }
				
        /**
        * This function includes css files required for frontend.
		*/
        function prdd_lite_front_side_scripts_css() {
            global $post;
			if( is_product() || is_page() ) {
                $prdd_settings = get_post_meta( $post->ID, '_woo_prdd_lite_enable_delivery_date', true );
			    if ( isset( $prdd_settings ) && $prdd_settings == 'on' ) {
                    $plugin_version_number = get_option( 'woocommerce_prdd_lite_db_version' );
                    
			        $calendar_theme_sel = 'smoothness';
			        wp_enqueue_style( 'prdd-jquery-ui', "//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/$calendar_theme_sel/jquery-ui.css" , '', $plugin_version_number, false );
                }
			}
        }
        
        /**
        * This function add the Delivery Date fields on the frontend product page 
        * as per the settings selected when Enable Delivery Date is enabled.
        */
        function prdd_lite_after_add_to_cart() {
            global $post, $wpdb, $woocommerce;
            $duplicate_of = $this->prdd_lite_get_product_id( $post->ID );
            $prdd_settings = get_post_meta( $duplicate_of, '_woo_prdd_lite_enable_delivery_date', true );
            $prdd_minimum_delivery_time = get_post_meta( $duplicate_of, '_woo_prdd_lite_minimum_delivery_time', true );
            $prdd_maximum_number_days = get_post_meta( $duplicate_of, '_woo_prdd_lite_maximum_number_days', true );
            if( $prdd_maximum_number_days == '' || $prdd_maximum_number_days == 'null' ) {
                $prdd_maximum_number_days = '30';
            }
            $current_time = current_time( 'timestamp' );
            if( $prdd_minimum_delivery_time != '' && $prdd_minimum_delivery_time != 0 ) {
                $advance_seconds = $prdd_minimum_delivery_time *60 *60;
                $cut_off_timestamp = $current_time + $advance_seconds;
                $cut_off_date = date( "d-m-Y", $cut_off_timestamp );
                $min_date = date( "j-n-Y", strtotime( $cut_off_date ) );
            } else {
                $min_date = date( "j-n-Y", $current_time );
            }
            print( '<input type="hidden" name="prdd_lite_hidden_minimum_delivery_time" id="prdd_lite_hidden_minimum_delivery_time" value="' . $min_date . '">' );
            if( isset( $prdd_settings ) && $prdd_settings == "on" ) {
                print ( '<div><label class="delivery_date_label">' . __( "Delivery Date", "woocommerce-prdd-lite" ) . ': </label>
			    <input type="text" id="delivery_calender_lite" name="delivery_calender_lite" class="delivery_calender_lite" style="cursor: text!important;margin-bottom:10px;" readonly/>
                <img src="' . plugins_url() . '/product-delivery-date-for-woocommerce-lite/images/cal.png" width="20" height="20" style="cursor:pointer!important;" id ="delivery_cal_lite"/></div>
                <input type="hidden" id="prdd_lite_hidden_date" name="prdd_lite_hidden_date"/>
                <script type="text/javascript">
					jQuery(document).ready(function() {
                        var formats = ["d.m.y", "d-m-yy","MM d, yy"];
                        var min_date = jQuery( "#prdd_lite_hidden_minimum_delivery_time" ).val();
                        var split_date = min_date.split( "-" );
		                var min_date_to_set = new Date ( split_date[1] + "/" + split_date[0] + "/" + split_date[2] );
						jQuery.extend( jQuery.datepicker, { afterShow: function(event) {
                            jQuery.datepicker._getInst( event.target ).dpDiv.css( "z-index", 9999 );
                        }});
                        jQuery( "#delivery_calender_lite" ).datepicker({
                            dateFormat: formats[2],
                            minDate: min_date_to_set,
                            maxDate: parseInt( ' . $prdd_maximum_number_days . ' )-1,
                            onClose:function( dateStr, inst ) {
                                if ( dateStr != "" ) {
                                    var monthValue = inst.selectedMonth+1;
                                    var dayValue = inst.selectedDay;
                                    var yearValue = inst.selectedYear;
                                    var all = yearValue + "-" + monthValue + "-" + dayValue;
                                    jQuery( "#prdd_lite_hidden_date" ).val( all );
                                }
                            }
						}).focus(function (event){
                            jQuery.datepicker.afterShow(event);
                        });
                        jQuery( "#delivery_cal_lite" ).click(function() {
                            jQuery( "#delivery_calender_lite" ).datepicker( "show" );
                        });
                    });
                </script>');
            }
        }
			
        /**
        * This function returns the cart_item_meta with the delivery details of the product when add to cart button is clicked.
        */
        function prdd_lite_add_cart_item_data( $cart_item_meta, $product_id ){
            global $wpdb;
            $duplicate_of = $this->prdd_lite_get_product_id( $product_id );
            if ( isset( $_POST[ 'delivery_calender_lite' ] ) ) {
                $date_disp = $_POST[ 'delivery_calender_lite' ];
            }
            if ( isset( $_POST[ 'prdd_lite_hidden_date' ] ) ) {
                $hidden_date = $_POST[ 'prdd_lite_hidden_date' ];
            }
            
            $cart_arr = array();
            if ( isset( $date_disp ) ) {
                $cart_arr[ 'delivery_date' ] = $date_disp;
            }
            if ( isset( $hidden_date ) ) {
                $cart_arr[ 'delivery_hidden_date' ] = $hidden_date;
            }
            $cart_item_meta[ 'prdd_lite_delivery' ][] = $cart_arr;
            return $cart_item_meta;
        }
        
        /**
        * This function displays the Delivery details on cart page, checkout page.
        */
        function prdd_lite_get_item_data( $other_data, $cart_item ) {
            if ( isset( $cart_item[ 'prdd_lite_delivery' ] ) ) {
                $duplicate_of = $this->prdd_lite_get_product_id( $cart_item[ 'product_id' ] );
                foreach( $cart_item[ 'prdd_lite_delivery' ] as $delivery ) {
                    $name = __( "Delivery Date", "woocommerce-prdd-lite" );
                    if ( isset( $delivery[ 'delivery_date' ] ) && $delivery[ 'delivery_date' ] != "") {
                        $other_data[] = array(
                            'name'    => $name,
                            'display' => $delivery[ 'delivery_date' ]
                        );
                    }
                    $other_data = apply_filters( 'prdd_get_item_data', $other_data, $cart_item );
                }
            }
            return $other_data;
        }
        
        function prdd_lite_hidden_order_itemmeta( $arr ){
            $arr[] = '_prdd_lite_date';
            $arr[] = '_prdd_lite_time_slot';
            return $arr;
        }
        
        /**
        * This function updates the database for the delivery details and adds delivery fields on the Order Received page,
        * WooCommerce->Orders when an order is placed for WooCommerce version greater than 2.0.
        */
        function prdd_lite_order_item_meta( $item_meta, $cart_item ) {
            if ( version_compare( WOOCOMMERCE_VERSION, "2.0.0" ) < 0 ) {
                return;
            }
            // Add the fields
            global $wpdb, $woocommerce;
            foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $values ) {
                $_product = $values[ 'data' ];
                if ( isset( $values[ 'prdd_lite_delivery' ] ) ) {
                    $delivery = $values[ 'prdd_lite_delivery' ];
                }
                $quantity = $values[ 'quantity' ];
                $post_id = $this->prdd_lite_get_product_id( $values[ 'product_id' ] );
                $post_title = $_product->get_title();

                // Fetch line item
                if ( count( $order_item_ids ) > 0 ) {
                    $order_item_ids_to_exclude = implode( ",", $order_item_ids );
                    $sub_query = " AND order_item_id NOT IN ( " . $order_item_ids_to_exclude . ")";
                }

                $query = "SELECT order_item_id, order_id FROM `" . $wpdb->prefix . "woocommerce_order_items`
						WHERE order_id = %s AND order_item_name LIKE %s" . $sub_query;
                $results = $wpdb->get_results( $wpdb->prepare( $query, $item_meta, $post_title ) );
                $order_item_ids[] = $results[0]->order_item_id;
                $order_id = $results[0]->order_id;
                $order_obj = new WC_order( $order_id );
                $details = $product_ids = array();
                $order_items = $order_obj->get_items();
                
                if ( isset( $values[ 'prdd_lite_delivery' ] ) ) {
                    $prdd_settings = get_post_meta( $post_id, '_woo_prdd_lite_enable_delivery_date', true );
                    $details = array();
                    if ( isset( $delivery[0][ 'delivery_date' ] ) && $delivery[0][ 'delivery_date' ] != "" ) {
                        $name = "Delivery Date";
                        $date_select = $delivery[0][ 'delivery_date' ];
                        wc_add_order_item_meta( $results[0]->order_item_id, $name, sanitize_text_field( $date_select, true ) );
                    }
                    if ( array_key_exists( 'delivery_hidden_date', $delivery[0] ) && $delivery[0][ 'delivery_hidden_date' ] != "" ) {
                        $date_booking = date( 'Y-m-d', strtotime( $delivery[0]['delivery_hidden_date'] ) );
                        wc_add_order_item_meta( $results[0]->order_item_id, '_prdd_lite_date', sanitize_text_field( $date_booking, true ) );
                    }
                }
                
                if ( version_compare( WOOCOMMERCE_VERSION, "2.5" ) < 0 ) {
                    continue;
                } else {
                    // Code where the Delivery dates are not displayed in the customer new order email from WooCommerce version 2.5
                    $cache_key       = WC_Cache_Helper::get_cache_prefix( 'orders' ) . 'item_meta_array_' . $results[ 0 ]->order_item_id;
                    $item_meta_array = wp_cache_get( $cache_key, 'orders' );
                    if ( false !== $item_meta_array ) {
                        $metadata        = $wpdb->get_results( $wpdb->prepare( "SELECT meta_key, meta_value, meta_id FROM {$wpdb->prefix}woocommerce_order_itemmeta WHERE order_item_id = %d AND meta_key IN (%s,%s) ORDER BY meta_id", absint( $results[ 0 ]->order_item_id ), "Delivery Date", '_prdd_lite_date' ) );
                        foreach ( $metadata as $metadata_row ) {
                            $item_meta_array[ $metadata_row->meta_id ] = (object) array( 'key' => $metadata_row->meta_key, 'value' => $metadata_row->meta_value );
                        }
                        wp_cache_set( $cache_key, $item_meta_array, 'orders' );
                    }
                }
            }
        }
        
        function prdd_lite_get_product_id( $product_id ) {
            global $wpdb;
            $duplicate_of = get_post_meta( $product_id, '_icl_lang_duplicate_of', true );
            if( $duplicate_of == '' && $duplicate_of == null ) {
                $duplicate_of = $product_id;
                $post_time = get_post( $product_id );
                if ( isset( $post_time->post_date ) ) {
                    $id_query = "SELECT ID FROM `" . $wpdb->prefix . "posts` WHERE post_date =  %s ORDER BY ID LIMIT 1";
                    $results_post_id = $wpdb->get_results ( $wpdb->prepare( $id_query, $post_time->post_date ) );
                    if( isset( $results_post_id ) ) {
                        $duplicate_of = $results_post_id[0]->ID;
                    }
                }
            }
            return $duplicate_of;
        }
    }		
}
$woocommerce_prdd_lite = new woocommerce_prdd_lite();