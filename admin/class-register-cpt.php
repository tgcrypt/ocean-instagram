<?php
/**
 * Register Post Type
 */
if ( ! class_exists( 'OIG_Shortcode_Post_Type' ) ) {

	class OIG_Shortcode_Post_Type {

		/**
		 * Start things up
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'shortcodes_post_type' ), 20 );
			add_action( 'admin_menu', array( $this, 'add_submenu_page' ), 11 );
		}

		/**
		 * Register shortcodes post type
		 *
		 * @since 1.0.0
		 */
		public static function shortcodes_post_type() {

			register_post_type( 'instagram_shortcodes', apply_filters( 'oig_instagram_shortcodes_args', array(
				'labels' => array(
					'name' 					=> esc_html__( 'Shortcodes', 'ocean-instagram' ),
					'singular_name' 		=> esc_html__( 'Shortcode', 'ocean-instagram' ),
					'add_new_item' 			=> esc_html__( 'Add New Shortcode', 'ocean-instagram' ),
					'not_found' 			=> esc_html__( 'No shortcodes found.', 'ocean-instagram' ),
					'not_found_in_trash' 	=> esc_html__( 'No shortcodes found in trash', 'ocean-instagram' )
				),
				'public' 					=> true,
				'hierarchical'          	=> false,
				'show_ui'               	=> true,
				'show_in_menu' 				=> false,
				'show_in_admin_bar'     	=> false,
				'show_in_nav_menus'     	=> false,
				'can_export'            	=> true,
				'has_archive'           	=> false,
				'exclude_from_search'   	=> true,
				'publicly_queryable'    	=> false,
				'capability_type' 			=> 'post',
				'menu_position' 			=> 20,
				'supports' 					=> array( 'title' )
			) ) );

		}

		/**
		 * Add the post type menu
		 *
		 * @since 1.0.0
		 */
		public function add_submenu_page() {
			add_submenu_page(
				'oceanwp-instagram-settings',
				esc_html__( 'Shortcodes', 'ocean-instagram' ),
				esc_html__( 'Shortcodes', 'ocean-instagram' ),
				'manage_options',
				'edit.php?post_type=instagram_shortcodes'
			);
		}

	}

}

new OIG_Shortcode_Post_Type();
