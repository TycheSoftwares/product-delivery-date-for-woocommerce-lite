<?php 
/*
Plugin Name: Product Delivery Date for WooCommerce - Lite
Description: This plugin lets you capture the Delivery Date for each product.
Version: 1.0
Author: Tyche Softwares
Author URI: http://www.tychesoftwares.com/
*/

global $PrddLiteUpdateChecker;
$PrddLiteUpdateChecker = '1.0';

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
			//add_action( 'admin_enqueue_scripts',                  array( &$this, 'prdd_lite_my_enqueue_scripts_css' ) );
			//add_action( 'admin_enqueue_scripts',                  array( &$this, 'prdd_lite_my_enqueue_scripts_js' ) );
			add_action( 'woocommerce_duplicate_product' ,         array( &$this, 'prdd_lite_product_duplicate' ), 10, 2 );
				
			add_action( 'woocommerce_before_single_product',      array( &$this, 'prdd_lite_front_side_scripts_js' ) );
			add_action( 'woocommerce_before_single_product',      array( &$this, 'prdd_lite_front_side_scripts_css' ) );

			add_action( 'woocommerce_before_add_to_cart_button',  array( &$this, 'prdd_lite_after_add_to_cart' ) );
			add_filter( 'woocommerce_add_cart_item_data',         array( &$this, 'prdd_lite_add_cart_item_data' ), 25, 2 );
			//add_filter( 'woocommerce_get_cart_item_from_session', array( &$this, 'prdd_lite_get_cart_item_from_session' ), 25, 2 );
			add_filter( 'woocommerce_get_item_data',              array( &$this, 'prdd_lite_get_item_data' ), 25, 2 );
			add_action( 'woocommerce_checkout_update_order_meta', array( &$this, 'prdd_lite_order_item_meta' ), 10, 2 );
            add_filter( 'woocommerce_hidden_order_itemmeta',      array( &$this, 'prdd_lite_hidden_order_itemmeta' ), 10, 1 );
        }
			
		/**
		* This function detects when the product delivery date plugin is activated
		*/
        function prdd_lite_activate() {
            update_option( 'woocommerce_prdd_lite_db_version', '1.0' );
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
            $enable_date = '';
            if ( isset( $_POST[ 'prdd_lite_enable_date' ] ) ) {
                $enable_date = $_POST[ 'prdd_lite_enable_date' ];
            }
            update_post_meta( $duplicate_of, '_woo_prdd_lite_enable_delivery_date', $enable_date );
		}
			
		/**
        * This function displays the settings for the product in the Product Delivery Date meta box on the admin product page.
		*/
		function prdd_lite_meta_box() {
		    global $post;
		    $duplicate_of = $this->prdd_lite_get_product_id( $post->ID );
		    $prdd_settings = get_post_meta( $duplicate_of, '_woo_prdd_lite_enable_delivery_date', true );
		    ?>
		    <table>
                <tr>
                    <th>
                        <label for="prdd_lite_enable_date"> <?php _e( 'Enable Delivery Date:', 'woocommerce-prdd-lite' );?> </label>
                    </th>
                    <td>
                        <?php 
                        $enable_date = '';
                        if( isset( $prdd_settings ) && $prdd_settings == 'on' ) {
                            $enable_date = 'checked';
                        }
                        ?>
                        <input type="checkbox" id="prdd_lite_enable_date" name="prdd_lite_enable_date" style="margin-left:30px;" <?php echo $enable_date;?> >
                    </td>
                    <td>
                        <img class="help_tip" width="16" height="16" style="margin-left:100px;" data-tip="<?php _e( 'Enable Delivery Date on Products Page', 'woocommerce-prdd' );?>" src="<?php echo plugins_url() ;?>/woocommerce/assets/images/help.png" />
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
            if( isset( $prdd_settings ) && $prdd_settings == "on" ) {
                print ( '<div><label class="delivery_date_label">' . __( "Delivery Date", "woocommerce-prdd" ) . ': </label>
			    <input type="text" id="delivery_calender_lite" name="delivery_calender_lite" class="delivery_calender_lite" style="cursor: text!important;margin-bottom:10px;" readonly/>
                <img src="' . plugins_url() . '/product-delivery-date-for-woocommerce-lite/images/cal.png" width="20" height="20" style="cursor:pointer!important;" id ="delivery_cal_lite"/></div>
                <input type="hidden" id="prdd_lite_hidden_date" name="prdd_lite_hidden_date"/>
                <script type="text/javascript">
					jQuery(document).ready(function() {
                        var formats = ["d.m.y", "d-m-yy","MM d, yy"];
						jQuery.extend( jQuery.datepicker, { afterShow: function(event) {
                            jQuery.datepicker._getInst( event.target ).dpDiv.css( "z-index", 9999 );
                        }});
                        jQuery( "#delivery_calender_lite" ).datepicker({
                            dateFormat: formats[2],
                            minDate: 1,
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

                $query = "SELECT order_item_id,order_id FROM `" . $wpdb->prefix . "woocommerce_order_items`
						WHERE order_id = %s AND order_item_name = %s";
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