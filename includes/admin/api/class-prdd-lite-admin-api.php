<?php
/**
 * Base class of the prdd-lite/admin/v1 REST API used by the React admin pages.
 *
 * @package Product-Delivery-Date-Lite/Admin/API
 * @since   4.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class PRDD_Lite_Admin_API.
 */
abstract class PRDD_Lite_Admin_API {

	/**
	 * REST namespace. It differs from the Pro one, so both versions can be active.
	 */
	const REST_NAMESPACE = 'prdd-lite/admin/v1';

	/**
	 * Hooks the route registration.
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Registers the routes of the controller.
	 */
	abstract public function register_routes();

	/**
	 * Default permission check for the admin settings endpoints.
	 *
	 * Cookie authenticated requests are validated against the wp_rest nonce
	 * by WordPress core before this runs, so a missing or invalid nonce leaves
	 * the request without a user and this check fails.
	 *
	 * @return bool|WP_Error
	 */
	public function admin_permission() {
		if ( current_user_can( 'manage_woocommerce' ) ) { // phpcs:ignore WordPress.WP.Capabilities.Unknown
			return true;
		}

		return new WP_Error(
			'prdd_lite_rest_forbidden',
			__( 'Sorry, you are not allowed to do that.', 'product-delivery-date-for-woocommerce-lite' ),
			array( 'status' => rest_authorization_required_code() )
		);
	}

	/**
	 * Returns a success response.
	 *
	 * @param mixed  $data    Response data.
	 * @param string $message Message.
	 * @return WP_REST_Response
	 */
	protected function success( $data = array(), $message = '' ) {
		return rest_ensure_response(
			array(
				'success' => true,
				'data'    => $data,
				'message' => $message,
			)
		);
	}

	/**
	 * Returns an error response.
	 *
	 * @param string $message Message.
	 * @param int    $status  HTTP status.
	 * @param string $code    Error code.
	 * @return WP_Error
	 */
	protected function error( $message, $status = 400, $code = 'prdd_lite_rest_error' ) {
		return new WP_Error( $code, $message, array( 'status' => $status ) );
	}

	/**
	 * Returns the JSON body of the request as an array.
	 *
	 * @param WP_REST_Request $request Request.
	 * @return array
	 */
	protected function body( $request ) {
		$params = $request->get_json_params();
		if ( ! is_array( $params ) ) {
			$params = $request->get_body_params();
		}
		return is_array( $params ) ? $params : array();
	}

	/**
	 * Default saved message.
	 *
	 * @return string
	 */
	protected function saved_message() {
		return __( 'Settings saved.', 'product-delivery-date-for-woocommerce-lite' );
	}
}
