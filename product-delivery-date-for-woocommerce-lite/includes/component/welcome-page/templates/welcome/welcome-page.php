<?php
/**
 * Welcome page on activate or updation of the plugin
 */
?>
<style>
    .feature-section .feature-section-item {
        float:left;
        width:48%;
    }
</style>

<div class="wrap about-wrap">
    <?php echo $get_welcome_header; ?>
    <div style="float:left;width: 80%;">
        <p style="margin-right:20px;font-size: 25px;"><?php
            if( 'yes' == get_option( 'prdd_lite_installed' ) ) {
                printf(
                    __( "Thank you for installing " . $plugin_name . "! As a first time user, welcome! You're well to accept deliveries with customer preferred delivery date." )
                );    
            } else {
                printf(
                    __( "Thank you for updating to the latest version of " . $plugin_name . "! Get ready to explore some exciting features in the recent updates." )
                );
            }
            
        ?>
        </p>
    </div>
    
    <div class="wcal-badge"><img src="<?php echo $badge_url; ?>" style="width:150px;"/></div>

    <p>&nbsp;</p>

    <div class="feature-section clearfix introduction">

        <h3><?php esc_html_e( "Get Started with " . $plugin_name . " ", 'woocommerce-prdd-lite' ); ?></h3>

        <div class="video feature-section-item" style="float:left; padding-right:10px; display:inline-block; max-width:60%;">
            <img src="<?php echo $ts_dir_image_path . 'product-delivery-date-lite.png' ?>"
                alt="<?php esc_attr_e( 'Order Delivery Date Lite', 'woocommerce-prdd-lite' ); ?>" style="width:600px;">
        </div>

        <div class="content feature-section-item last-feature" style="float:right; display:inline-block; max-width:40%;">
            <h3><?php esc_html_e( 'Enable Delivery Date Capture', 'woocommerce-prdd-lite' ); ?></h3>

            <p><?php esc_html_e( 'To start allowing customers to select their preferred delivery date on each product page, simply activate the Enable Delivery Date checkbox in the Product Delivery Date Meta box which gets added on the Add/Edit Product page.', 'woocommerce-prdd-lite' ); ?></p>

            <a href="https://www.tychesoftwares.com/docs/docs/product-delivery-date-for-woocommerce-lite/setup-delivery-date-calendar/?utm_source=wpaboutpage&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank" class="button-secondary">
            <?php esc_html_e( 'Learn More', 'woocommerce-prdd-lite' ); ?>
            <span class="dashicons dashicons-external"></span>
            </a>    
        </div>
    </div>

    <!-- /.intro-section -->

    <div class="content">

    <h3><?php esc_html_e( "Know more about Product Delivery Date Pro", 'woocommerce-prdd-lite' ); ?></h3>

    <p><?php _e( 'The Product Delivery Date Pro plugin gives you features where you can allow customers to choose a Delivery Time along with Date and to Set Recurring Weekdays or Specific Dates or both as Delivery days as compared to Lite Plugin. Here are some other notable features the Pro version provides.', 'woocommerce-prdd-lite' ); ?></p>

    <div class="feature-section clearfix introduction">
        <div class="video feature-section-item" style="float:left;padding-right:10px;">
            <img src="<?php echo $ts_dir_image_path . 'prdd_pro_view_deliveries.png'?>"
                alt="<?php esc_attr_e( 'Product Delivery Date Lite', 'woocommerce-prdd-lite' ); ?>" style="width:500px;">
        </div>

        <div class="content feature-section-item last-feature">
            <h3><?php esc_html_e( 'View All Deliveries', 'woocommerce-prdd-lite' ); ?></h3>

            <p><?php esc_html_e( 'The ability to show all the deliveries with the Customer Details and Delivery Date & Time.', 'woocommerce-prdd-lite' ); ?></p>

            
        </div>
    </div>

    <div class="feature-section clearfix">
        <div class="content feature-section-item">

            <h3><?php esc_html_e( 'Delivery Time along with Delivery Date', 'woocommerce-prdd-lite' ); ?></h3>

                <p><?php esc_html_e( "The provision for allowing Delivery Time along with the Delivery Date on the product page makes the delivery more accurate. Delivering products on customer's preferred date and time improves your customers service.", 'woocommerce-prdd-lite' ); ?></p>
        </div>

        <div class="content feature-section-item last-feature">
            <img src="<?php echo $ts_dir_image_path . 'time_slots.png'; ?>" alt="<?php esc_attr_e( 'Product Delivery Date for WooCommerce - Lite', 'woocommerce-prdd-lite' ); ?>" style="width:450px;">
        </div>
    </div>


    <div class="feature-section clearfix introduction">
        <div class="video feature-section-item" style="float:left;padding-right:10px;">
            <img src="<?php echo $ts_dir_image_path. 'google-calendar-sync.png'; ?>" alt="<?php esc_attr_e( 'Product Delivery Date for WooCommerce - Lite', 'woocommerce-prdd-lite' ); ?>" style="width:450px;">
        </div>

        <div class="content feature-section-item last-feature">
            <h3><?php esc_html_e( 'Synchronise Deliveries with Google Calendar', 'woocommerce-prdd-lite' ); ?></h3>

            <p><?php esc_html_e( 'The ability to synchronise deliveries to the google calendar helps administrator or store manager to manage all the things in a single calendar.', 'woocommerce-prdd-lite' ); ?></p>
            </a>
        </div>
    </div>

    <div class="feature-section clearfix">
        <div class="content feature-section-item">

            <h3><?php esc_html_e( 'Different delivery settings for each product', 'woocommerce-prdd-lite' ); ?></h3>

                <p><?php esc_html_e( 'The Pro version of the plugin allows you to add different delivery settings like Same day cut-off time, Next Day cut-off time or Minimum Delivery Time for each product and product-specific holidays. It also allows you to add different delivery charges for different weekdays and specific dates.', 'woocommerce-prdd-lite' ); ?></p>

                </a>
        </div>

        <div class="content feature-section-item last-feature">
            <img src="<?php echo $ts_dir_image_path . 'delivery_time_period.png'; ?>" alt="<?php esc_attr_e( 'Product Delivery Date for WooCommerce - Lite', 'woocommerce-prdd-lite' ); ?>" style="width:450px;">
        </div>
    </div>

    <a href="https://www.tychesoftwares.com/differences-pro-lite-versions-product-delivery-date-woocommerce-plugin/?utm_source=wpaboutpage&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank" class="button-secondary">
        <?php esc_html_e( 'View full list of differences between Lite & Pro plugin', 'woocommerce-prdd-lite' ); ?>
        <span class="dashicons dashicons-external"></span>
    </a>
    </div>

    <div class="feature-section clearfix">
        <div class="content feature-section-item">
            <h3><?php esc_html_e( 'Getting to Know Tyche Softwares', 'woocommerce-prdd-lite' ); ?></h3>
            <ul class="ul-disc">
                <li><a href="https://tychesoftwares.com/?utm_source=wpaboutpage&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank"><?php esc_html_e( 'Visit the Tyche Softwares Website', 'woocommerce-prdd-lite' ); ?></a></li>
                <li><a href="https://tychesoftwares.com/premium-plugins/?utm_source=wpaboutpage&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank"><?php esc_html_e( 'View all Premium Plugins', 'woocommerce-prdd-lite' ); ?></a>
                <ul class="ul-disc">
                    <li><a href="https://www.tychesoftwares.com/store/premium-plugins/woocommerce-abandoned-cart-pro/?utm_source=wpaboutpage&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Abandoned Cart Pro Plugin for WooCommerce</a></li>
                    <li><a href="https://www.tychesoftwares.com/store/premium-plugins/woocommerce-booking-plugin/?utm_source=wpaboutpage&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Booking & Appointment Plugin for WooCommerce</a></li>
                    <li><a href="https://www.tychesoftwares.com/store/premium-plugins/order-delivery-date-for-woocommerce-pro-21/?utm_source=wpaboutpage&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Order Delivery Date for WooCommerce</a></li>
                    <li><a href="https://www.tychesoftwares.com/store/premium-plugins/product-delivery-date-pro-for-woocommerce/?utm_source=wpaboutpage&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Product Delivery Date for WooCommerce</a></li>
                    <li><a href="https://www.tychesoftwares.com/store/premium-plugins/deposits-for-woocommerce/?utm_source=wpaboutpage&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank">Deposits for WooCommerce</a></li>
                </ul>
                </li>
                <li><a href="https://tychesoftwares.com/about/?utm_source=wpaboutpage&utm_medium=link&utm_campaign=ProductDeliveryDateLite" target="_blank"><?php esc_html_e( 'Meet the team', $plugin_context ); ?></a></li>
            </ul>

        </div>
        
        <div class="content feature-section-item">
            <h3><?php esc_html_e( 'Current Offers', $plugin_context ); ?></h3>
            <p>We do not have any offers going on right now</p>
        </div>

    </div>            
    <!-- /.feature-section -->
</div>