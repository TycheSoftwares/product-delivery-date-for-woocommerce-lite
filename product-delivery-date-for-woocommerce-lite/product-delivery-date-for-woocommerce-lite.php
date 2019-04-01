<?php 
/**
 * Plugin Name: Product Delivery Date for WooCommerce - Lite
 * Description: This plugin lets you capture the Delivery Date for each product.
 * Version: 1.9 
 * Author: Tyche Softwares
 * Author URI: https://www.tychesoftwares.com/
 * Requires PHP: 5.6
 * WC requires at least: 3.0.0
 * WC tested up to: 3.4.0
 * Text Domain: woocommerce-prdd-lite
 * Domain Path: /languages/
 *
 * @package Product-Delivery-Date-Lite
 */

include_once( 'includes/class-prdd-privacy-policy-lite.php' );

global $PrddLiteUpdateChecker;
$PrddLiteUpdateChecker = '1.9';

/**
 * This function checks Product delivery date plugin is active or not.
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
 * @hook init
 * @since 1.0
 *
 */
function prdd_lite_update_po_file() {
    $domain = 'woocommerce-prdd-lite';
    $locale = apply_filters( 'plugin_locale', get_locale(), $domain );
    if( $loaded = load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '-' . $locale . '.mo' ) ) {
        return $loaded;
    } else {
        load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
    }
}

if ( !class_exists( 'woocommerce_prdd_lite' ) ) {
    /**
     * Class for delivery date setting at back end and allowing to select the delivery date on the product page. It displays the delivery date on Cart, Checkout,  Order received page and WooCommerce->Orders page.
     * 
     * @since 1.0
     */
    class woocommerce_prdd_lite {
        /**
         * Constructor function for initializing settings
         * 
         * @since 1.0
         */
        public function __construct() {
            self::prdd_lite_load_files (); 
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

            if ( true === is_admin() ) {
                add_filter ( 'ts_deativate_plugin_questions',     array( &$this, 'prdd_lite_deactivate_add_questions' ), 10, 1 );
                add_filter( 'ts_tracker_data',                    array( &$this, 'prdd_lite_ts_add_plugin_tracking_data' ), 10, 1 );
				add_filter( 'ts_tracker_opt_out_data',            array( &$this, 'prdd_lite_get_data_for_opt_out' ), 10, 1 );
                add_action ( 'prdd_lite_add_meta_footer',         array( &$this, 'prdd_lite_review_text' ), 10, 1 );
                
            }
        }

        /**
         * It will load all the files needed for the plugin.
         */
        public static function prdd_lite_load_files () {

            if ( true === is_admin() ) {
                include_once ( 'includes/prdd-lite-component.php' );
            }
        }
			
		/**
		 * This function detects when the product delivery date plugin is activated.
         * 
         * @hook register_activation_hook
         *
         * @since 1.0
		 */
        function prdd_lite_activate() {
            update_option( 'woocommerce_prdd_lite_db_version', '1.9' );
            //Check if installed for the first time.
            add_option( 'prdd_lite_installed', 'yes' );
        }

        /**
         * This function is used to updating the version number in the database when the plugin is updated.
         * 
         * @hook admin_init
         *
         * @since 1.3
         */
        function prdd_lite_update_db_check() {
            $prdd_plugin_version = get_option( 'woocommerce_prdd_lite_db_version' );
            if ( $prdd_plugin_version != $this->get_plugin_version() ) {
                update_option( 'woocommerce_prdd_lite_db_version', '1.9' );
            }
        }
        
        /**
         * This function returns the product delivery date plugin version number.
         *
         * @since 1.3
         */
        function get_plugin_version() {
            $plugin_data    = get_plugin_data( __FILE__ );
            $plugin_version = $plugin_data[ 'Version' ];
            return $plugin_version;
        }

        /**
         * It will add the question for the deactivate popup modal
         * @return array $prdd_lite_add_questions All questions.
         */
        public static function prdd_lite_deactivate_add_questions ( $prdd_lite_add_questions ) {

            $prdd_lite_add_questions = array(
                0 => array(
                    'id'                => 4,
                    'text'              => __( "Minimum Delivery Time (in hours) is not working as expected.", "woocommerce-prdd-lite" ),
                    'input_type'        => '',
                    'input_placeholder' => ''
                    ), 
                1 =>  array(
                    'id'                => 5,
                    'text'              => __( "I need delivery time along with the delivery date.", "woocommerce-prdd-lite" ),
                    'input_type'        => '',
                    'input_placeholder' => ''
                ),
                2 => array(
                    'id'                => 6,
                    'text'              => __( "I want deliveries on some specific dates only.", "woocommerce-prdd-lite" ),
                    'input_type'        => '',
                    'input_placeholder' => ''
                ),
                3 => array(
                    'id'                => 7,
                    'text'              => __( "I have purchased the Pro version of the Plugin.", "woocommerce-prdd-lite" ),
                    'input_type'        => '',
                    'input_placeholder' => ''
                )

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
         * 
         */

        public static function prdd_lite_ts_add_plugin_tracking_data ( $data ) {
            if ( isset( $_GET[ 'prdd_lite_tracker_optin' ] ) && isset( $_GET[ 'prdd_lite_tracker_nonce' ] ) && wp_verify_nonce( $_GET[ 'prdd_lite_tracker_nonce' ], 'prdd_lite_tracker_optin' ) ) {

                $plugin_data[ 'ts_meta_data_table_name' ]   = 'ts_tracking_prdd_lite_meta_data';
                $plugin_data[ 'ts_plugin_name' ]		    = 'Product Delivery Date for WooCommerce - Lite';
                
                // Store count info
                $plugin_data[ 'deliveries_count' ]          = self::ts_get_deliveries_counts();
                
                // Get all plugin options info
                $plugin_data[ 'deliverable_products' ]      = self::ts_get_deliverable_products();
                $plugin_data[ 'prdd_lite_plugin_version' ]  = self::prdd_get_version();
                $plugin_data[ 'prdd_lite_allow_tracking' ]  = get_option ( 'prdd_lite_allow_tracking' );
                $data[ 'plugin_data' ]                      = $plugin_data;
            }
            return $data;
        }

        /**
         * It will return the total orders count which have the delivery dates.
         * 
         */
        public static function ts_get_deliveries_counts() {
            global $wpdb;
            $order_count = 0;
            $orddd_query = "SELECT count( order_item_id ) AS deliveries_count FROM `" . $wpdb->prefix . "woocommerce_order_itemmeta` WHERE meta_key = %s AND order_item_id IN ( SELECT a.order_item_id FROM `" . $wpdb->prefix . "woocommerce_order_items` AS a, `" . $wpdb->prefix . "posts` AS b WHERE a.order_id = b.ID AND b.post_type = 'shop_order' AND post_status NOT IN ('wc-cancelled', 'wc-refunded', 'trash', 'wc-failed' ) )";
            $results = $wpdb->get_results( $wpdb->prepare( $orddd_query, '_prdd_lite_date' ) );

            if( isset( $results[0] ) ) {
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
            $orddd_query = "SELECT count(a.ID) AS deliverable_products FROM `" . $wpdb->prefix . "posts` AS a, `" . $wpdb->prefix . "postmeta` AS b WHERE a.post_type = 'product' AND a.post_status = 'publish' AND a.ID = b.post_id AND b.meta_key = '_woo_prdd_lite_enable_delivery_date' AND b.meta_value = 'on'";
            $results = $wpdb->get_results( $orddd_query );
            if( isset( $results[0] ) ) {
                $product_count += $results[0]->deliverable_products;    
            }
            return $product_count;
        }
        /**
         * This function returns the Product Delivery Date Lite plugin version number.
         *     
         * @return string Version of the plugin
         * @since 3.3
         */
        public static function prdd_get_version() {
            $plugin_version = '';
            $prddd_lite_plugin_dir =  dirname (__FILE__) ;
            $prddd_lite_plugin_dir .= '/product-delivery-date-for-woocommerce-lite.php';

            $plugin_data = get_file_data( $prddd_lite_plugin_dir, array( 'Version' => 'Version' ) );
            if ( ! empty( $plugin_data['Version'] ) ) {
                $plugin_version = $plugin_data[ 'Version' ];
            }
            return $plugin_version;
        }

        /**
         * Tracking data to send when No, thanks. button is clicked.
         *
         * @hook ts_tracker_opt_out_data
         *
         * @param array $params Parameters to pass for tracking data.
         *
         * @return array Data to track when opted out.
         * 
         */
        public static function prdd_lite_get_data_for_opt_out ( $params ) {
            $plugin_data[ 'ts_meta_data_table_name']   = 'ts_tracking_prdd_lite_meta_data';
            $plugin_data[ 'ts_plugin_name' ]		   = 'Product Delivery Date for WooCommerce - Lite';
            
            // Store count info
            $params[ 'plugin_data' ]  				   = $plugin_data;
            
            return $params;
        }
        
		/**
         * This function adds a meta box for delivery settings on product page.
         *
         * @hook add_meta_boxes
         *
         * @since 1.0
         */
        function prdd_lite_box() {
            add_meta_box( 'woocommerce-prdd-lite', __( 'Product Delivery Date', 'woocommerce-prdd-lite' ), array( &$this, 'prdd_lite_meta_box' ), 'product', 'normal', 'core' );
        }
			
        /**
         * This function updates the delivery settings for each product in the wp_postmeta table of the database. It will be called when update/publish button clicked on admin side.
         *  
         * @hook woocommerce_process_product_meta
         *
         * @param int $post_id Post ID
         * @param WP_Post $post WP_Post object
         * @since 1.0
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
            update_post_meta( $duplicate_of, '_woo_prdd_lite_delivery_days', $_POST[ 'prdd_lite_delivery_days' ] );
		}
			
		/**
         * This function displays the settings for the product in the Product Delivery Date meta box on the admin product page.
		 *
         * @since 1.0
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
                        <b><label for="prdd_lite_delivery_days[]"> <?php _e( 'Delivery days Available:', 'woocommerce-prdd-lite' );?> </label></b>
                    </td>
                    <td>
                        <?php
                        $prdd_lite_delivery_days = get_post_meta( $duplicate_of, '_woo_prdd_lite_delivery_days', true );
                        ?>
                        <select name="prdd_lite_delivery_days[]" id="prdd_lite_delivery_days" class="js-example-basic-multiple" multiple="multiple">
                          <option value="Sunday">Sunday</option>
                          <option value="Monday">Monday</option>
                          <option value="Tuesday">Tuesday</option>
                          <option value="Wednesday">Wednesday</option>
                          <option value="Thursday">Thursday</option>
                          <option value="Friday">Friday</option>
                          <option value="Saturday">Saturday</option>
                        </select>

                    </td>
                    <td>
                        <img class="help_tip" width="16" height="16" data-tip="<?php _e( 'The days in week on which you want to deliver the product. e.g You dont want to deliver the products on Sundays then uncheck sundays.' );?>" src="<?php echo plugins_url() ;?>/woocommerce/assets/images/help.png" />
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
                <?php do_action ( 'prdd_lite_add_meta_footer' ); ?>
		    </table>
            <script type="text/javascript">
                jQuery( document ).ready( function() {
        
                    if ( jQuery( ".js-example-basic-multiple" ).length > 0 )
                        jQuery( ".js-example-basic-multiple" ).select2();
                    var data = <?php echo json_encode( $prdd_lite_delivery_days ) ?>;
                    if( data ) {
                        jQuery( ".js-example-basic-multiple" ).val( data );
                        jQuery( ".js-example-basic-multiple" ).trigger( 'change.select2' );
                    }else{
                        jQuery( ".js-example-basic-multiple" ).val( [ 'Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday' ] );
                        jQuery( ".js-example-basic-multiple" ).trigger( 'change.select2' );   
                    }

                });
            </script>
		    <?php 
		}

        /**
         * This function duplicates the delivery settings of the original product to the new product.
         *
         * @hook woocommerce_duplicate_product
         * 
         * @param int $new_id new post ID
         * @param WP_Post $post Post object.
         * @since 1.0
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
         * 
         * @hook woocommerce_before_single_product
         *
         * @since 1.0
		 */
        function prdd_lite_front_side_scripts_js() {
            global $post;
			if( is_product() || is_page() ) {
                $prdd_settings = get_post_meta( $post->ID, '_woo_prdd_lite_enable_delivery_date' , true );
			    if ( isset( $prdd_settings ) && $prdd_settings == 'on' ) {
                    $plugin_version_number = get_option( 'woocommerce_prdd_lite_db_version' );
                    wp_enqueue_script( 'jquery' );
                    wp_enqueue_script( 'jquery-ui-datepicker' );
                    wp_enqueue_script( 'jquery-ui-core' );            
                    wp_register_script( 'select2', plugins_url() . '/woocommerce/assets/js/select2/select2.min.js', array( 'jquery-ui-widget', 'jquery-ui-core' ) );
                    wp_enqueue_script( 'select2' );
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
        function prdd_lite_front_side_scripts_css() {
            global $post;
			if( is_product() || is_page() ) {
                $prdd_settings = get_post_meta( $post->ID, '_woo_prdd_lite_enable_delivery_date', true );
			    if ( isset( $prdd_settings ) && $prdd_settings == 'on' ) {
                    $plugin_version_number = get_option( 'woocommerce_prdd_lite_db_version' );
                    
			        $calendar_theme_sel = 'smoothness';
                    wp_register_style( 'prdd-jquery-ui', plugins_url( '/css/themes/' . $calendar_theme_sel . '/jquery-ui.css', __FILE__ ) , '', $plugin_version_number, false );
                    wp_enqueue_style( 'prdd-jquery-ui' );
                }
			}
        }
        
        /**
         * This function add the Delivery Date fields on the frontend product page as per the settings selected when Enable Delivery Date is enabled.
         * 
         * @hook woocommerce_before_add_to_cart_button
         *
         * @since 1.0
         */
        function prdd_lite_after_add_to_cart() {
            global $post, $wpdb, $woocommerce;
            $duplicate_of               = $this->prdd_lite_get_product_id( $post->ID );
            $prdd_settings              = get_post_meta( $duplicate_of, '_woo_prdd_lite_enable_delivery_date', true );
            $prdd_minimum_delivery_time = get_post_meta( $duplicate_of, '_woo_prdd_lite_minimum_delivery_time', true );
          
            $prdd_maximum_number_days   = get_post_meta( $duplicate_of, '_woo_prdd_lite_maximum_number_days', true );
            $prdd_lite_delivery_days    = get_post_meta( $duplicate_of, '_woo_prdd_lite_delivery_days', true );
          
            if( $prdd_maximum_number_days == '' || $prdd_maximum_number_days == 'null' ) {
                $prdd_maximum_number_days = '30';
            }
            $current_time = current_time( 'timestamp' );
            if( $prdd_minimum_delivery_time != '' && $prdd_minimum_delivery_time != 0 ) {
                $advance_seconds   = $prdd_minimum_delivery_time *60 *60;
                // now we need the first available weekday for delivery as minimum time is to be calculated from thereon.
                $weekday = date( 'l', $current_time );
                while( ! in_array( $weekday, $prdd_lite_delivery_days ) ) { // this weekday is unavailable for delivery
                    $current_time += 86400; // add 1 day to it
                    $weekday = date( 'l', $current_time );
                }
                $cut_off_timestamp = $current_time + $advance_seconds;
                $cut_off_date      = date( "d-m-Y", $cut_off_timestamp );
                $min_date          = date( "j-n-Y", strtotime( $cut_off_date ) );
            } else {
                $min_date          = date( "j-n-Y", $current_time );
            }
            print( '<input type="hidden" name="prdd_lite_hidden_minimum_delivery_time" id="prdd_lite_hidden_minimum_delivery_time" value="' . $min_date . '">' );
            if( isset( $prdd_settings ) && $prdd_settings == "on" ) {
                print ( '<div style="width:70%; position:relative;"><label class="delivery_date_label">' . __( "Delivery Date", "woocommerce-prdd-lite" ) . ': </label>
			    <img src="' . plugins_url() . '/product-delivery-date-for-woocommerce-lite/images/cal.png" width="20" height="20" style="cursor:pointer!important; position:absolute; top:50%; right:5%;" id ="delivery_cal_lite"/>
                <input type="text" id="delivery_calender_lite" name="delivery_calender_lite" class="delivery_calender_lite" style="cursor:text!important;display:block;  margin-bottom:20px; width:100%;" readonly/>
                </div>
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
                            beforeShowDay: function( date ) {
                                var weekday = [ "Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday" ];
                                var a = new Date( date );
                                var enable_days = ' . json_encode($prdd_lite_delivery_days) . ';
                                if(enable_days){
                                    if( jQuery.inArray( weekday[a.getDay()], enable_days ) > -1 ) {
                                        return [ true ];
                                    }else{
                                        return [ false ];
                                    }
                                }else{
                                    return [ true ];
                                }
                            },
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
         * When "Add to cart" button is clicked on product page, this function returns the cart_item_meta with the delivery details of the product.
         * 
         * @hook woocommerce_add_cart_item_data
         *
         * @param int $product_id product's ID
         * @return array $cart_item_meta cart_item_meta array with the delivery details
         * @since 1.0
         */
        function prdd_lite_add_cart_item_data( $cart_item_meta, $product_id ) {
            global $wpdb;
            $duplicate_of = $this->prdd_lite_get_product_id( $product_id );
            if( isset( $_POST[ 'delivery_calender_lite' ] ) ) {
                $date_disp = $_POST[ 'delivery_calender_lite' ];
            }
            if( isset( $_POST[ 'prdd_lite_hidden_date' ] ) ) {
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
         *
         * @hook woocommerce_get_item_data
         *
         * @param array $cart_item Delivery details and product details 
         * @return array $other_data array with delivery date and delivery date field name
         * @since 1.0
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

        /**
         * This function adds the delivery hidden fields with product array.
         *
         * @hook woocommerce_hidden_order_itemmeta
         *
         * @return array $arr array of hidden delivery date
         * @since 1.0
         */
        function prdd_lite_hidden_order_itemmeta( $arr ) {
            $arr[] = '_prdd_lite_date';
            return $arr;
        }
        
        /**
         * This function updates the database for the delivery details and adds delivery fields on the Order Received page & WooCommerce->Orders page when an order is placed for WooCommerce version greater than 2.0.
         *
         * @hook woocommerce_checkout_update_order_meta
         *
         * @param int $item_meta Order ID
         * @param array $cart_item product details
         * @since 1.0
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
                $quantity     = $values[ 'quantity' ];
                $post_id      = $this->prdd_lite_get_product_id( $values[ 'product_id' ] );
                $post_title   = $_product->get_title();

                // Fetch line item
                if( count( $order_item_ids ) > 0 ) {
                    $order_item_ids_to_exclude = implode( ",", $order_item_ids );
                    $sub_query = " AND order_item_id NOT IN ( " . $order_item_ids_to_exclude . ")";
                }
                $query            = "SELECT order_item_id, order_id FROM `" . $wpdb->prefix . "woocommerce_order_items`
						              WHERE order_id = %s AND order_item_name LIKE %s" . $sub_query;
                $results          = $wpdb->get_results( $wpdb->prepare( $query, $item_meta, $post_title . '%' ) );
              
                $order_item_ids[] = $results[0]->order_item_id;
                $order_id         = $results[0]->order_id;
                $order_obj        = new WC_order( $order_id );
                $details          = $product_ids = array();
                $order_items      = $order_obj->get_items();
                
                if( isset( $values[ 'prdd_lite_delivery' ] ) ) {
                    $prdd_settings = get_post_meta( $post_id, '_woo_prdd_lite_enable_delivery_date', true );
                    $details       = array();
                    if( isset( $delivery[0][ 'delivery_date' ] ) && $delivery[0][ 'delivery_date' ] != "" ) {
                        $name        = "Delivery Date";
                        $date_select = $delivery[0][ 'delivery_date' ];
                        wc_add_order_item_meta( $results[0]->order_item_id, $name, sanitize_text_field( $date_select, true ) );
                    }
                    if( array_key_exists( 'delivery_hidden_date', $delivery[0] ) && $delivery[0][ 'delivery_hidden_date' ] != "" ) {
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
                        $metadata    = $wpdb->get_results( $wpdb->prepare( "SELECT meta_key, meta_value, meta_id FROM {$wpdb->prefix}woocommerce_order_itemmeta WHERE order_item_id = %d AND meta_key IN (%s,%s) ORDER BY meta_id", absint( $results[ 0 ]->order_item_id ), "Delivery Date", '_prdd_lite_date' ) );
                        foreach ( $metadata as $metadata_row ) {
                            $item_meta_array[ $metadata_row->meta_id ] = (object) array( 'key' => $metadata_row->meta_key, 'value' => $metadata_row->meta_value );
                        }
                        wp_cache_set( $cache_key, $item_meta_array, 'orders' );
                    }
                }
            }
        }
        
        /**
         * This function returns the product ID from WP_Post table.
         *
         * @return int $product_id product ID
         * @since 1.0
         */
        function prdd_lite_get_product_id( $product_id ) {
            global $wpdb;
            $duplicate_of     = get_post_meta( $product_id, '_icl_lang_duplicate_of', true );
            if( $duplicate_of == '' && $duplicate_of == null ) {
                $duplicate_of = $product_id;
                $post_time    = get_post( $product_id );
                if ( isset( $post_time->post_date ) ) {
                    $id_query = "SELECT ID FROM `" . $wpdb->prefix . "posts` WHERE post_date = %s ORDER BY ID LIMIT 1";
                    $results_post_id = $wpdb->get_results( $wpdb->prepare( $id_query, $post_time->post_date ) );
                    if( isset( $results_post_id ) ) {
                        $duplicate_of = $results_post_id[0]->ID;
                    }
                }
            }
            return $duplicate_of;
        }
        
        /**
         * This function adds the review note in the Product Delivery Date metabox under product page.
         *
         * @since 1.9
         */
        function prdd_lite_review_text() {
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
$woocommerce_prdd_lite = new woocommerce_prdd_lite();
