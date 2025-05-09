<?php 
// phpcs:disable
/**
 * Welcome Page Class
 *
 * Displays on plugin activation or updation
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Prdd_Lite_TS_Welcome Class
 *
 * A general class for About page.
 *
 * @since 7.7
 */

class Prdd_Lite_TS_Welcome {

	/**
	 * @var string The capability users should have to view the page
	 */
	public $minimum_capability = 'manage_options';
	
	/**
	 * @var string The name of the plugin
	 * @access public
	 */
	public static $plugin_name = "";

	/**
	 * @var string Unique prefix of the plugin
	 * @access public 
	 */

	public static $plugin_prefix = '';

	/**
	 * @var Plugin Context
	 * @access public
	 */

	public static $plugin_context = '';

	/**
	 * @var string Folder of the plugin
	 * @access public
	 */
	public static $plugin_folder = '';

	/**
	 * @var string Plugin live version
	 * @access public
	 */

	 public static $plugin_version = '';

	 /**
	  * @var string Plugin previous version
	  * @access public
	  */
	public static $previous_plugin_version = '';
	/**
	 * @var string Plugin Url
	 * @access public
	 */
	public static $plugin_url = '';
	/**
	 * @var string Template base path
	 * @access public
	 */
	public static $template_base = '';
	/**
	 * @var string Plugin dir name with plugin file name
	 * @access public
	 */
	public static $plugin_file_path = '';

	/**
	 * @var tring Page Header text
	 * @access public
	 */
	public static $ts_welcome_header_text = '';
	/**
	 * Get things started
	 *
	 * @since 7.7
	 */
	public function __construct( $ts_plugin_name = '', $ts_plugin_prefix = '', $ts_plugin_context = '', $ts_plugin_folder_name = '', $ts_plugin_dir_name = '' , $ts_previous_version = '', $welcome_header_text = '' ) {
		self::$plugin_name    		  = $ts_plugin_name;
		self::$plugin_prefix  	      = $ts_plugin_prefix;
		self::$plugin_context 		  = $ts_plugin_context;
		self::$plugin_folder    	  = $ts_plugin_folder_name;
		self::$plugin_file_path       = $ts_plugin_dir_name;
		self::$ts_welcome_header_text = $welcome_header_text;

		//Update plugin
		add_action( 'admin_init', array( &$this, 'ts_update_db_check' ) );

		add_action( 'admin_menu', array( $this, 'admin_menus' ) );
		add_action( 'admin_head', array( $this, 'admin_head' ) );

		if ( !isset( $_GET[ 'page' ] ) || 
		( isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] != self::$plugin_prefix . '-pro-about' ) ) {
			add_action( 'admin_init', array( $this, 'ts_pro_welcome' ) );
		}

		self::$plugin_version 		   = $this->ts_get_version();
		
		self::$previous_plugin_version = $ts_previous_version;
		self::$plugin_url     		   = $this->ts_get_plugin_url();
		self::$template_base  		   = $this->ts_get_template_path();
		self::$ts_welcome_header_text  = sprintf( esc_html__( 'Welcome to %s %s', self::$plugin_context ), self::$plugin_name, self::$plugin_version );// phpcs:ignore
	}

	/**
     * This function returns the plugin version number.
     *
     * @access public 
     * @since 7.7
     * @return $plugin_version
     */
    public function ts_get_version() {
        $plugin_version = '';
        
		$plugin_data = get_file_data( self::$plugin_file_path, array( 'Version' => 'Version' ) );
        if ( ! empty( $plugin_data['Version'] ) ) {
            $plugin_version = $plugin_data[ 'Version' ];
        }
        return $plugin_version;;
    }

    /**
     * This function returns the plugin url 
     *
     * @access public 
     * @since 7.7
     * @return string
     */
    public function ts_get_plugin_url() {
        return plugins_url() . '/' . self::$plugin_folder . '/';
    }

    /**
    * This function returns the template directory path
    *
    * @access public 
    * @since 7.7
    * @return string
    */
    public function ts_get_template_path() {
		
    	return untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/templates/';
    } 

	/**
	 * Register the Dashboard Page which is later hidden but this pages
	 * is used to render the Welcome page.
	 *
	 * @access public
	 * @since  7.7
	 * @return void
	 */
	public function admin_menus() {
		$display_version = self::$plugin_version;

		// About Page
		add_dashboard_page(
			sprintf( self::$ts_welcome_header_text ),
			esc_html__( 'Welcome to ' . self::$plugin_name, self::$plugin_context ),
			$this->minimum_capability,
			self::$plugin_prefix . '-pro-about',
			array( $this, 'about_screen' )
		);

	}

	/**
	 * Hide Individual Dashboard Pages
	 *
	 * @access public
	 * @since  7.7
	 * @return void
	 */
	public function admin_head() {
		remove_submenu_page( 'index.php', self::$plugin_prefix . '-pro-about' );
	}

	/**
	 * Render About Screen
	 *
	 * @access public
	 * @since  7.7
	 * @return void
	 */
	public function about_screen() {
		$display_version = self::$plugin_version;
		$ts_file_path    = plugin_dir_url( __FILE__ ) ; 
		// Badge for welcome page
		$badge_url = $ts_file_path . '/assets/images/icon-256x256.png';		
		
		ob_start();
        wc_get_template( 'welcome/welcome-page.php', array(
        	'plugin_name'        => self::$plugin_name,
        	'plugin_url'         => self::$plugin_url, 
            'display_version'    => $display_version,
			'badge_url'          => $badge_url,
			'ts_dir_image_path'  => $ts_file_path . '/assets/images/',
			'plugin_context'     => self::$plugin_context,
            'get_welcome_header' => $this->get_welcome_header()
        ),  self::$plugin_folder, self::$template_base );
        echo ob_get_clean();// phpcs:ignore

		add_option( self::$plugin_prefix . '_pro_welcome_page_shown', 'yes' );
		add_option( self::$plugin_prefix . '_pro_welcome_page_shown_time', current_time( 'timestamp' ) );
	}

	/**
	 * The header section for the welcome screen.
	 *
	 * @since 7.7
	 */
	public function get_welcome_header() {
		// Badge for welcome page
		$ts_file_path    = plugin_dir_url( __FILE__ ) ;// phpcs:ignore
		
		// Badge for welcome page
		$badge_url = $ts_file_path . '/assets/images/icon-256x256.png';
		?>
        <h1 class="welcome-h1"><?php echo get_admin_page_title(); // phpcs:ignore ?></h1>
		<?php $this->social_media_elements();
	}

	/**
	 * Social Media Like Buttons
	 *
	 * Various social media elements to Tyche Softwares
	 */
	public function social_media_elements() { 
		ob_start();
		wc_get_template( '/social-media-elements.php', 
						 array(), 
						 self::$plugin_folder, 
						 self::$template_base );
        echo ob_get_clean(); // phpcs:ignore
	}
	/**
	 * Sends user to the Welcome page on first activation of the plugin as well as each
	 * time the plugin is updated is upgraded to a new version
	 *
	 * @access public
	 * @since  7.7
	 *
	 * @return void
	 */
	public function ts_pro_welcome() {

		// Bail if activating from network, or bulk
		if ( is_network_admin() || isset( $_GET[ 'activate-multi' ] ) ) {
			return;
		}

		if( !get_option( self::$plugin_prefix . '_pro_welcome_page_shown' ) ) {
			wp_safe_redirect( admin_url( 'index.php?page=' . self::$plugin_prefix . '-pro-about' ) );
			exit;
		}
	}

	/**
	 *  Executed when the plugin is updated using the Automatic Updater. 
	 */
	public function ts_update_db_check() {

        if ( ( false === self::$plugin_version || false === self::$previous_plugin_version ) &&
              'yes' == get_option( self::$plugin_prefix . '_pro_welcome_page_shown' )
        ) {
            return;
        }
        
        if ( self::$plugin_version != self::$previous_plugin_version ) {
            delete_option( self::$plugin_prefix . '_pro_welcome_page_shown' );
            delete_option( self::$plugin_prefix . '_pro_welcome_page_shown_time' );
        }
    }
}