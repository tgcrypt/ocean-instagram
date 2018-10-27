<?php
/**
 * Instagram Settings
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
class OIG_Settings {

	/**
	 * Start things up
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
		add_action( 'admin_init', array( $this, 'register_setting' ) );
		add_action( 'admin_init', array( $this, 'setting_section' ) );
	}

	/**
	 * Add new menu page
	 *
	 * @since 1.0.0
	 */
	public function add_menu_page() {

		add_menu_page(
			esc_html__( 'Instagram Settings', 'ocean-instagram' ),
			esc_html__( 'Instagram', 'ocean-instagram' ),
			'manage_options',
			'oceanwp-instagram-settings',
			array( $this, 'create_admin_page' ),
			plugins_url( 'ocean-instagram/assets/img/instagram.svg' ),
			120
		);

		add_submenu_page(
			'oceanwp-instagram-settings',
			esc_html__( 'Instagram Settings', 'ocean-instagram' ),
			esc_html__( 'Settings', 'ocean-instagram' ),
			'manage_options',
			'oceanwp-instagram-settings'
		);

	}

	/**
	 * Register a setting and its sanitization callback.
	 *
	 * @since 1.0.0
	 */
	public function register_setting() {
		register_setting( 'oig_instagram_setting', 'oig_instagram_settings_options', array( $this, 'instagram_setting_validate' ) );
	}

	/**
	 * Register the setting sections and fields.
	 *
	 * @since 1.0.0
	 */
	public function setting_section() {

		// Setting section
		add_settings_section(
			'oig_instagram_settings_section',
			'',
			'__return_false',
			'oceanwp-instagram-settings'
		);

		// Setting field
		add_settings_field(
			'oig_access_token',
			esc_html__( 'Access Token', 'ocean-instagram' ),
			array( $this, 'access_token_field' ),
			'oceanwp-instagram-settings',
			'oig_instagram_settings_section'
		);
	}

	/**
	 * Access token field
	 *
	 * @since 1.0.0
	 */
	public function access_token_field() {
		$setting = get_option( 'oig_instagram_settings_options' );
		?>
		<input class="regular-text code" id="oig-access-token" name="oig_instagram_settings_options[oig_access_token]" value="<?php echo esc_attr( $setting['oig_access_token'] ) ?>" type="text">
		<?php
	}

	/**
	 * Settings page output
	 *
	 * @since 1.0.0
	 */
	public function create_admin_page() {
		?>
		<div class="wrap">

			<h2><?php _e( 'Instagram Settings', 'ocean-instagram' ); ?></h2>

			<div class="ocean-instagram-setting-wrapper postbox">

				<div class="inside">
					<div class="main">
						<h2><?php esc_html_e( 'Connect Instagram', 'ocean-instagram' ); ?></h2>

						<p><?php echo sprintf( esc_html__( 'Before get started, please %1$sfollow this article%2$s to get your Access Token.', 'ocean-instagram' ), '<a href="http://docs.oceanwp.org/article/487-how-to-get-instagram-access-token" target="_blank">', '</a>' ); ?></p>

						<form method="post" action="options.php">
							<?php settings_fields( 'oig_instagram_setting' ); ?>
							<?php do_settings_sections( 'oceanwp-instagram-settings' ); ?>
							<?php submit_button( esc_attr__( 'Update Settings', 'ocean-instagram' ), 'primary large' ); ?>
						</form>
					</div>
				</div>

			</div>

		</div>
		<?php
	}

	/**
	 * Validates/sanitizes the plugins settings after they've been submitted.
	 *
	 * @since  1.0.0
	 */
	public function instagram_setting_validate( $setting ) {
		$setting['oig_access_token'] = sanitize_text_field( $setting['oig_access_token'] );
		return $setting;
	}

}

new OIG_Settings();