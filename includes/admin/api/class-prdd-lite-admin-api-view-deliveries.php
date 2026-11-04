<?php
/**
 * REST endpoint of the View Deliveries screen.
 *
 * @package Product-Delivery-Date-Lite/Admin/API
 * @since   4.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class PRDD_Lite_Admin_API_View_Deliveries.
 *
 * The deliveries list is still rendered by the PRDD_Lite_View_Deliveries_Table
 * list table, so its columns, views and pagination stay the same.
 */
class PRDD_Lite_Admin_API_View_Deliveries extends PRDD_Lite_Admin_API {

	/**
	 * Query args the list table reads from the request.
	 *
	 * @var array
	 */
	const QUERY_ARGS = array( 'prdd_view', 'status', 'paged', 'orderby', 'order' );

	/**
	 * Registers the routes.
	 */
	public function register_routes() {
		$args = array();
		foreach ( self::QUERY_ARGS as $name ) {
			$args[ $name ] = array(
				'type'              => 'string',
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			);
		}

		register_rest_route(
			self::REST_NAMESPACE,
			'view-deliveries',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_deliveries' ),
				'permission_callback' => array( $this, 'admin_permission' ),
				'args'                => $args,
			)
		);
	}

	/**
	 * Admin page hook suffix, the screen id the list table had on the PHP page.
	 *
	 * @return string
	 */
	protected function hook_suffix() {
		return 'toplevel_page_' . PRDD_Lite_Admin_React_Page::PAGE;
	}

	/**
	 * Renders the deliveries list of the requested view.
	 *
	 * @param WP_REST_Request $request Request.
	 * @return WP_REST_Response
	 */
	public function get_deliveries( $request ) {
		$query = array();
		foreach ( self::QUERY_ARGS as $name ) {
			$value = (string) $request[ $name ];
			if ( '' !== $value ) {
				$query[ $name ] = $value;
			}
		}

		$view = isset( $query['prdd_view'] ) ? $query['prdd_view'] : '';
		if ( 'prdd_calender' === $view ) {
			return $this->success( array( 'html' => '' ) );
		}

		require_once ABSPATH . 'wp-admin/includes/class-wp-screen.php';
		require_once ABSPATH . 'wp-admin/includes/screen.php';
		require_once ABSPATH . 'wp-admin/includes/template.php';

		return $this->success( array( 'html' => $this->render_table( $query ) ) );
	}

	/**
	 * Renders the list table. The list table reads the filters from $_GET and
	 * $_REQUEST, builds its links from the request URI and needs the page hook
	 * suffix, so these are set while the table renders and restored afterwards.
	 *
	 * @param array $query Query args of the request.
	 * @return string
	 */
	protected function render_table( $query ) {
		$page_args = array_merge(
			array(
				'page'   => PRDD_Lite_Admin_React_Page::PAGE,
				'action' => 'view-deliveries',
			),
			$query
		);

		// phpcs:disable WordPress.Security.NonceVerification.Recommended -- Only read back to restore them.
		$backup_get     = $_GET;
		$backup_request = $_REQUEST;
		// phpcs:enable WordPress.Security.NonceVerification.Recommended
		$backup_uri  = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : null; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- Only read back to restore it.
		$backup_hook = array_key_exists( 'hook_suffix', $GLOBALS ) ? $GLOBALS['hook_suffix'] : null;
		$had_hook    = array_key_exists( 'hook_suffix', $GLOBALS );
		$level       = ob_get_level();

		try {
			$_GET                   = array_merge( $_GET, $query ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$_REQUEST               = array_merge( $_REQUEST, $query ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$_SERVER['REQUEST_URI'] = add_query_arg( urlencode_deep( $page_args ), wp_parse_url( admin_url( 'admin.php' ), PHP_URL_PATH ) );
			if ( empty( $GLOBALS['hook_suffix'] ) ) {
				$GLOBALS['hook_suffix'] = $this->hook_suffix(); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			}

			include_once PRDD_LITE_PLUGIN_PATH . '/includes/admin/class-prdd-lite-view-deliveries-table.php';
			$table = new PRDD_Lite_View_Deliveries_Table();

			ob_start();
			$table->prdd_lite_prepare_items();
			$table->views();
			$table->display();
			return ob_get_clean();
		} finally {
			// Drops the buffer when the table threw before it was read.
			while ( ob_get_level() > $level ) {
				ob_end_clean();
			}

			$_GET     = $backup_get;
			$_REQUEST = $backup_request;
			if ( null === $backup_uri ) {
				unset( $_SERVER['REQUEST_URI'] );
			} else {
				$_SERVER['REQUEST_URI'] = $backup_uri;
			}
			if ( $had_hook ) {
				$GLOBALS['hook_suffix'] = $backup_hook; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			} else {
				unset( $GLOBALS['hook_suffix'] );
			}
		}
	}
}
