<?php
/**
 * REST endpoints of the Settings, Holidays and Calendar Appearance screens.
 *
 * @package Product-Delivery-Date-Lite/Admin/API
 * @since   4.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class PRDD_Lite_Admin_API_Settings.
 */
class PRDD_Lite_Admin_API_Settings extends PRDD_Lite_Admin_API {

	/**
	 * Registers the routes.
	 */
	public function register_routes() {
		$sections = array(
			'general'    => array( 'get_general_settings', 'save_general_settings' ),
			'holidays'   => array( 'get_holidays', 'save_holidays' ),
			'appearance' => array( 'get_appearance', 'save_appearance' ),
		);

		foreach ( $sections as $route => $callbacks ) {
			register_rest_route(
				self::REST_NAMESPACE,
				'settings/' . $route,
				array(
					array(
						'methods'             => WP_REST_Server::READABLE,
						'callback'            => function () use ( $route, $callbacks ) {
							return $this->get_section( $route, $callbacks[0] );
						},
						'permission_callback' => array( $this, 'admin_permission' ),
					),
					array(
						'methods'             => WP_REST_Server::CREATABLE,
						'callback'            => function ( $request ) use ( $callbacks ) {
							call_user_func( array( 'PRDD_Lite_Settings_Service', $callbacks[1] ), $this->body( $request ) );
							return $this->success( array(), $this->saved_message() );
						},
						'permission_callback' => array( $this, 'admin_permission' ),
					),
				)
			);
		}
	}

	/**
	 * Returns one settings section.
	 *
	 * @param string $route    Section.
	 * @param string $callback Service method.
	 * @return WP_REST_Response
	 */
	protected function get_section( $route, $callback ) {
		$data = call_user_func( array( 'PRDD_Lite_Settings_Service', $callback ) );

		if ( 'appearance' === $route ) {
			$data['options'] = PRDD_Lite_Settings_Service::get_appearance_options();
		}

		return $this->success( $data );
	}
}
