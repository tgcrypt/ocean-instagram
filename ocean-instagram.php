<?php
/**
 * Plugin Name:         Ocean Instagram
 * Plugin URI:          https://oceanwp.org/extension/ocean-instagram/
 * Description:         Display Instagram feed in a beautiful way.
 * Version:             1.0.3
 * Author:              OceanWP
 * Author URI:          https://oceanwp.org/
 * Requires at least:   4.6.0
 * Tested up to:        4.9.7
 *
 * Text Domain: ocean-instagram
 * Domain Path: /languages/
 *
 * @package Ocean_Instagram
 * @category Core
 * @author OceanWP
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns the main instance of Ocean_Instagram to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Ocean_Instagram
 */
function Ocean_Instagram() {
	return Ocean_Instagram::instance();
} // End Ocean_Instagram()

Ocean_Instagram();

/**
 * Main Ocean_Instagram Class
 *
 * @class Ocean_Instagram
 * @version	1.0.0
 * @since 1.0.0
 * @package	Ocean_Instagram
 */
final class Ocean_Instagram {
	/**
	 * Ocean_Instagram The single instance of Ocean_Instagram.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $token;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $version;

	// Admin - Start
	/**
	 * The admin object.
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $admin;

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct() {
		$this->token         = 'ocean-instagram';
		$this->plugin_url    = plugin_dir_url( __FILE__ );
		$this->plugin_path   = plugin_dir_path( __FILE__ );
		$this->version       = '1.0.3';

		// Define plugin constant
		define( 'OIG_PATH', $this->plugin_path );
		define( 'OIG_URL', $this->plugin_url );

		// Function when the plugin is activated.
		register_activation_hook( __FILE__, array( $this, 'install' ) );

		// Load the localization file.
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Plugin setup.
		add_action( 'init', array( $this, 'setup' ) );
		add_action( 'init', array( $this, 'updater' ), 1 );
	}

	/**
	 * Initialize License Updater.
	 * Load Updater initialize.
	 * @return void
	 */
	public function updater() {

		// Plugin Updater Code
		if( class_exists( 'OceanWP_Plugin_Updater' ) ) {
			$license	= new OceanWP_Plugin_Updater( __FILE__, 'Instagram', $this->version, 'OceanWP' );
		}
	}

	/**
	 * Main Ocean_Instagram Instance
	 *
	 * Ensures only one instance of Ocean_Instagram is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Ocean_Instagram()
	 * @return Main Ocean_Instagram instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) )
			self::$_instance = new self();
		return self::$_instance;
	} // End instance()

	/**
	 * Load the localization file.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'ocean-instagram', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
	}

	/**
	 * Installation.
	 * Runs on activation. Logs the version number and assigns a notice message to a WordPress option.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function install() {
		$this->_log_version_number();
	}

	/**
	 * Log the plugin version number.
	 * @access  private
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number() {
		// Log the version number.
		update_option( $this->token . '-version', $this->version );
	}

	/**
	 * Setup all the things.
	 * Only executes if OceanWP or a child theme using OceanWP as a parent is active and the extension specific filter returns true.
	 * @return void
	 */
	public function setup() {
		$theme = wp_get_theme();

		if ( 'OceanWP' == $theme->name || 'oceanwp' == $theme->template ) {
			require_once( OIG_PATH . '/admin/class-register-cpt.php' );
			require_once( OIG_PATH . '/admin/class-instagram-settings.php' );
			require_once( OIG_PATH . '/admin/class-shortcode-generator.php' );
			require_once( OIG_PATH . '/includes/class-instagram-api.php' );
			require_once( OIG_PATH . '/includes/class-instagram-shortcode.php' );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ), 999 );
		}
	}

	/**
	 * Load admin scripts
	 *
	 * @since 1.0.0
	 */
	public static function admin_scripts( $hook_suffix ) {

		if ( 'toplevel_page_oceanwp-instagram-settings' == $hook_suffix ) {
			wp_enqueue_style( 'oig-style', plugins_url( '/assets/css/admin.css', __FILE__ ) );
		}

	}

	/**
	 * Load frontend scripts
	 *
	 * @since 1.0.0
	 */
	public static function frontend_scripts() {

		// Front-end style
		wp_enqueue_style( 'oig-style', plugins_url( '/assets/css/style.min.css', __FILE__ ) );

		// Load custom js
		wp_enqueue_script( 'oig-js-script', plugins_url( '/assets/js/main.min.js', __FILE__ ), array( 'jquery' ), null, true );

		// If rtl
		if ( is_RTL() ) {
			wp_enqueue_style( 'oig-style-rtl', plugins_url( '/assets/css/rtl.css', __FILE__ ) );
		}

	}

} // End Class
