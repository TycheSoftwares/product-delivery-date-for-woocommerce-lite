<?php
/**
 * Product Delivery Date Lite Global Settings
 *
 * @author Tyche Softwares
 * @package Product-Delivery-Date-Lite/Admin
 * @since 1.7
 */

defined( 'ABSPATH' ) || exit;

/**
 * Global Menu
 */
class PRDD_Lite_Global_Menu {

	/**
	 * Add Menu page.
	 *
	 * The pages are rendered by the React app since 4.0.0. The View Deliveries
	 * slug stays registered, so its earlier URL is still allowed and redirected
	 * to the app by PRDD_Lite_Admin_React_Page::maybe_redirect().
	 */
	public static function prdd_lite_admin_menu() {
		add_menu_page(
			'Product Delivery Date',
			__( 'Product Delivery Date', 'product-delivery-date-for-woocommerce-lite' ),
			'manage_woocommerce', // phpcs:ignore WordPress.WP.Capabilities.Unknown
			PRDD_Lite_Admin_React_Page::PAGE,
			array( 'PRDD_Lite_Admin_React_Page', 'render' )
		);

		add_submenu_page(
			PRDD_Lite_Admin_React_Page::PAGE,
			__( 'View Deliveries', 'product-delivery-date-for-woocommerce-lite' ),
			__( 'View Deliveries', 'product-delivery-date-for-woocommerce-lite' ),
			'manage_woocommerce', // phpcs:ignore WordPress.WP.Capabilities.Unknown
			PRDD_Lite_Admin_React_Page::HISTORY_PAGE,
			array( 'PRDD_Lite_Admin_React_Page', 'render' )
		);

		add_submenu_page(
			PRDD_Lite_Admin_React_Page::PAGE,
			__( 'Settings', 'product-delivery-date-for-woocommerce-lite' ),
			__( 'Settings', 'product-delivery-date-for-woocommerce-lite' ),
			'manage_woocommerce', // phpcs:ignore WordPress.WP.Capabilities.Unknown
			PRDD_Lite_Admin_React_Page::PAGE,
			array( 'PRDD_Lite_Admin_React_Page', 'render' )
		);

		remove_submenu_page( PRDD_Lite_Admin_React_Page::PAGE, PRDD_Lite_Admin_React_Page::PAGE );
		do_action( 'prdd_lite_add_submenu' );
	}

	/**
	 * Points the View Deliveries and Settings submenus, and so the top level
	 * menu, to the routes of the React app.
	 *
	 * The submenu slugs stay registered for the page lookup, the links are only
	 * changed when the menu is about to be displayed.
	 *
	 * @hook parent_file
	 *
	 * @param string $parent_file Parent file of the current page.
	 * @return string
	 * @since 4.0.0
	 */
	public static function prdd_lite_submenu_app_links( $parent_file ) {
		global $submenu;

		if ( empty( $submenu[ PRDD_Lite_Admin_React_Page::PAGE ] ) ) {
			return $parent_file;
		}

		$routes = array(
			PRDD_Lite_Admin_React_Page::HISTORY_PAGE => '/view-deliveries',
			PRDD_Lite_Admin_React_Page::PAGE         => '/settings',
		);

		foreach ( $submenu[ PRDD_Lite_Admin_React_Page::PAGE ] as $key => $item ) {
			if ( isset( $item[2], $routes[ $item[2] ] ) ) {
				// The menu prints the URL of a slug without a page hook as it is.
				$submenu[ PRDD_Lite_Admin_React_Page::PAGE ][ $key ][2] = esc_url( PRDD_Lite_Admin_React_Page::url( $routes[ $item[2] ] ) ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			}
		}

		return $parent_file;
	}

	/**
	 * Global Menu Page.
	 *
	 * @deprecated 4.0.0 Use PRDD_Lite_Admin_React_Page::render().
	 */
	public static function prdd_lite_global_menu_page() {
		_deprecated_function( __METHOD__, '4.0.0', 'PRDD_Lite_Admin_React_Page::render' );
		PRDD_Lite_Admin_React_Page::render();
	}

	/**
	 * Plugin Settings.
	 *
	 * @deprecated 4.0.0 The settings are saved by the prdd-lite/admin/v1 REST endpoints.
	 */
	public static function prdd_lite_delivery_settings() {
		_deprecated_function( __METHOD__, '4.0.0' );
	}
}
