<?php
/**
 * Register Post Type
 */
if ( ! class_exists( 'OIG_Shortcode_Generator' ) ) {

	class OIG_Shortcode_Generator {

		/**
		 * Start things up
		 */
		public function __construct() {
			add_filter( 'ocean_metaboxes_post_types_scripts', array( $this, 'post_type' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'metabox_scripts' ) );
			add_action( 'butterbean_register', array( $this, 'metabox' ), 10, 2 );
			add_action( 'add_meta_boxes_instagram_shortcodes', array( $this, 'add_meta_box' ) );
		}

		/**
		 * Add post type
		 *
		 * @since 1.0.0
		 */
		public function post_type( $post_type ) {
			$post_type[] = 'instagram_shortcodes';
			return $post_type;
		}

		/**
		 * Load metabox scripts and styles
		 *
		 * @since 1.0.0
		 */
		public function metabox_scripts( $hook ) {

			// Only needed on these admin screens
			if ( $hook != 'edit.php' && $hook != 'post.php' && $hook != 'post-new.php' ) {
				return;
			}

			// Get global post
			global $post;

			// Return if post is not object
			if ( ! is_object( $post ) ) {
				return;
			}

			// Return if wrong post type
			if ( 'instagram_shortcodes' != $post->post_type ) {
				return;
			}

			// Enqueue scripts
			wp_enqueue_script( 'oig-instagram-metabox-script', plugins_url( '/assets/js/metabox.min.js', plugin_dir_path( __FILE__ ) ), array( 'jquery' ), true );

		}

		/**
		 * Register metabox
		 *
		 * @since 1.0.0
		 */
		public function metabox( $butterbean, $post_type ) {

			if ( 'instagram_shortcodes' !== $post_type ) {
				return;
			}

			// Register managers, sections, controls, and settings here.
			$butterbean->register_manager(
				'oig_instagram_settings',
				array(
					'label'     => esc_html__( 'Instagram Settings', 'ocean-instagram' ),
					'post_type' => 'instagram_shortcodes',
					'context'   => 'normal',
					'priority'  => 'high'
				)
			);

			$manager = $butterbean->get_manager( 'oig_instagram_settings' );

			$manager->register_section(
				'oig_instagram_general',
				array(
					'label' => esc_html__( 'General', 'ocean-instagram' ),
					'icon'  => 'dashicons-admin-tools'
				)
			);

			$manager->register_control(
		        'oig_instagram_style', // Same as setting name.
		        array(
		            'section' 		=> 'oig_instagram_general',
		            'type'    		=> 'select',
		            'label'   		=> esc_html__( 'Style', 'ocean-instagram' ),
		            'description'   => esc_html__( 'Select your style.', 'ocean-instagram' ),
					'choices' 		=> array(
						'default' 	=> esc_html__( 'Default Style', 'ocean-instagram' ),
						'widget' 	=> esc_html__( 'Widget Style', 'ocean-instagram' ),
					),
		        )
		    );
			
			$manager->register_setting(
		        'oig_instagram_style', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_key',
		            'default' 			=> 'default',
		        )
		    );

			$manager->register_control(
				'oig_instagram_number',
				array(
					'section'     => 'oig_instagram_general',
					'type'        => 'number',
					'label'       => esc_html__( 'Number of images', 'ocean-instagram' ),
					'description' => esc_html__( 'The number of images you want to show', 'ocean-instagram' ),
					'attr'        => array(
						'step' 	=> '1',
					),
				)
			);

			$manager->register_setting(
				'oig_instagram_number',
				array(
					'sanitize_callback' => array( $this, 'sanitize_absint' ),
					'default'           => '6'
				)
			);

			$manager->register_control(
				'oig_instagram_columns',
				array(
					'section'     => 'oig_instagram_general',
					'type'        => 'number',
					'label'       => esc_html__( 'Number of columns', 'ocean-instagram' ),
					'description' => esc_html__( 'The number of columns you want. Maximum 10.', 'ocean-instagram' ),
					'attr'        => array(
						'min' 	=> '1',
						'max' 	=> '10',
						'step' 	=> '1',
					),
				)
			);

			$manager->register_setting(
				'oig_instagram_columns',
				array(
					'sanitize_callback' => array( $this, 'sanitize_absint' ),
					'default'           => '4'
				)
			);

			$manager->register_control(
				'oig_instagram_likes',
				array(
					'section'     => 'oig_instagram_general',
					'type'        => 'buttonset',
					'label'       => esc_html__( 'Likes', 'ocean-instagram' ),
					'description' => esc_html__( 'Display likes.', 'ocean-instagram' ),
					'choices'     => array(
						'on'      => esc_html__( 'On', 'ocean-instagram' ),
						'off'     => esc_html__( 'Off', 'ocean-instagram' ),
					),
				)
			);

			$manager->register_setting(
				'oig_instagram_likes',
				array(
					'sanitize_callback' => 'sanitize_key',
					'default'           => 'on',
				)
			);

			$manager->register_control(
				'oig_instagram_comments',
				array(
					'section'     => 'oig_instagram_general',
					'type'        => 'buttonset',
					'label'       => esc_html__( 'Comments', 'ocean-instagram' ),
					'description' => esc_html__( 'Display comments.', 'ocean-instagram' ),
					'choices'     => array(
						'on'      => esc_html__( 'On', 'ocean-instagram' ),
						'off'     => esc_html__( 'Off', 'ocean-instagram' ),
					),
				)
			);

			$manager->register_setting(
				'oig_instagram_comments',
				array(
					'sanitize_callback' => 'sanitize_key',
					'default'           => 'on',
				)
			);

			$manager->register_control(
				'oig_instagram_caption',
				array(
					'section'     => 'oig_instagram_general',
					'type'        => 'buttonset',
					'label'       => esc_html__( 'Caption', 'ocean-instagram' ),
					'description' => esc_html__( 'Display caption.', 'ocean-instagram' ),
					'choices'     => array(
						'on'      => esc_html__( 'On', 'ocean-instagram' ),
						'off'     => esc_html__( 'Off', 'ocean-instagram' ),
					),
				)
			);

			$manager->register_setting(
				'oig_instagram_caption',
				array(
					'sanitize_callback' => 'sanitize_key',
					'default'           => 'off',
				)
			);

			$manager->register_control(
				'oig_instagram_caption_length',
				array(
					'section'     => 'oig_instagram_general',
					'type'        => 'number',
					'label'       => esc_html__( 'Limit Caption', 'ocean-instagram' ),
					'description' => esc_html__( 'The number of words you want to show', 'ocean-instagram' ),
					'attr'        => array(
						'step' 	=> '1',
					),
				)
			);

			$manager->register_setting(
				'oig_instagram_caption_length',
				array(
					'sanitize_callback' => array( $this, 'sanitize_absint' ),
					'default'           => '20'
				)
			);

			$manager->register_section(
				'oig_instagram_user',
				array(
					'label' => esc_html__( 'User Infos', 'ocean-instagram' ),
					'icon'  => 'dashicons-admin-users'
				)
			);

			$manager->register_control(
				'oig_instagram_user_picture',
				array(
					'section'     => 'oig_instagram_user',
					'type'        => 'buttonset',
					'label'       => esc_html__( 'Display avatar', 'ocean-instagram' ),
					'description' => esc_html__( 'Display your Instagram avatar.', 'ocean-instagram' ),
					'choices'     => array(
						'on'      => esc_html__( 'On', 'ocean-instagram' ),
						'off'     => esc_html__( 'Off', 'ocean-instagram' ),
					),
				)
			);

			$manager->register_setting(
				'oig_instagram_user_picture',
				array(
					'sanitize_callback' => 'sanitize_key',
					'default'           => 'off',
				)
			);

			$manager->register_control(
				'oig_instagram_user_username',
				array(
					'section'     => 'oig_instagram_user',
					'type'        => 'buttonset',
					'label'       => esc_html__( 'Display username', 'ocean-instagram' ),
					'description' => esc_html__( 'Display your Instagram username.', 'ocean-instagram' ),
					'choices'     => array(
						'on'      => esc_html__( 'On', 'ocean-instagram' ),
						'off'     => esc_html__( 'Off', 'ocean-instagram' ),
					),
				)
			);

			$manager->register_setting(
				'oig_instagram_user_username',
				array(
					'sanitize_callback' => 'sanitize_key',
					'default'           => 'off',
				)
			);

			$manager->register_control(
				'oig_instagram_user_follow',
				array(
					'section'     => 'oig_instagram_user',
					'type'        => 'buttonset',
					'label'       => esc_html__( 'Display follow button', 'ocean-instagram' ),
					'description' => esc_html__( 'Display a follow button.', 'ocean-instagram' ),
					'choices'     => array(
						'on'      => esc_html__( 'On', 'ocean-instagram' ),
						'off'     => esc_html__( 'Off', 'ocean-instagram' ),
					),
				)
			);

			$manager->register_setting(
				'oig_instagram_user_follow',
				array(
					'sanitize_callback' => 'sanitize_key',
					'default'           => 'off',
				)
			);

			$manager->register_control(
				'oig_instagram_user_posts_follow',
				array(
					'section'     => 'oig_instagram_user',
					'type'        => 'buttonset',
					'label'       => esc_html__( 'Display posts and followers', 'ocean-instagram' ),
					'description' => esc_html__( 'Display your posts & followers number.', 'ocean-instagram' ),
					'choices'     => array(
						'on'      => esc_html__( 'On', 'ocean-instagram' ),
						'off'     => esc_html__( 'Off', 'ocean-instagram' ),
					),
				)
			);

			$manager->register_setting(
				'oig_instagram_user_posts_follow',
				array(
					'sanitize_callback' => 'sanitize_key',
					'default'           => 'off',
				)
			);

			$manager->register_control(
				'oig_instagram_user_bio',
				array(
					'section'     => 'oig_instagram_user',
					'type'        => 'buttonset',
					'label'       => esc_html__( 'Display biography', 'ocean-instagram' ),
					'description' => esc_html__( 'Display your Instagram biography.', 'ocean-instagram' ),
					'choices'     => array(
						'on'      => esc_html__( 'On', 'ocean-instagram' ),
						'off'     => esc_html__( 'Off', 'ocean-instagram' ),
					),
				)
			);

			$manager->register_setting(
				'oig_instagram_user_bio',
				array(
					'sanitize_callback' => 'sanitize_key',
					'default'           => 'off',
				)
			);
		
			$manager->register_section(
		        'oig_instagram_styling',
		        array(
		            'label' => esc_html__( 'Styling', 'ocean-instagram' ),
		            'icon'  => 'dashicons-hammer'
		        )
		    );

			$manager->register_control(
				'oig_instagram_item_ratio',
				array(
					'section'     => 'oig_instagram_styling',
					'type'        => 'number',
					'label'       => esc_html__( 'Item ratio', 'ocean-instagram' ),
					'description' => esc_html__( 'The ratio of your images. Maximum 2.', 'ocean-instagram' ),
					'attr'        => array(
						'min' 	=> '0.1',
						'max' 	=> '2',
						'step' 	=> '0.01',
					),
				)
			);

			$manager->register_setting(
				'oig_instagram_item_ratio',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'default'           => '0.66'
				)
			);

			$manager->register_control(
				'oig_instagram_space',
				array(
					'section'     => 'oig_instagram_styling',
					'type'        => 'number',
					'label'       => esc_html__( 'Space between images (px)', 'ocean-instagram' ),
					'description' => esc_html__( 'Space between each images.', 'ocean-instagram' ),
					'attr'        => array(
						'min' 	=> '0',
						'step' 	=> '1',
					),
				)
			);

			$manager->register_setting(
				'oig_instagram_space',
				array(
					'sanitize_callback' => array( $this, 'sanitize_absint' ),
					'default'           => '0'
				)
			);

			$manager->register_control(
		        'oig_instagram_border_radius', // Same as setting name.
		        array(
		            'section' 		=> 'oig_instagram_styling',
		            'type'    		=> 'text',
		            'label'   		=> esc_html__( 'Border Radius', 'ocean-instagram' ),
		            'description'   => esc_html__( 'Enter your custom border radius for the images.  ex: 2px 1px 2px 1px (Top Right Bottom Left).', 'ocean-instagram' ),
		        )
		    );
			
			$manager->register_setting(
		        'oig_instagram_border_radius', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_text_field',
		        )
		    );

		    $manager->register_control(
		        'oig_instagram_overlay_bg', // Same as setting name.
		        array(
		            'section' 		=> 'oig_instagram_styling',
		            'type'    		=> 'color',
		            'label'   		=> esc_html__( 'Overlay Background', 'ocean-instagram' ),
		            'description'   => esc_html__( 'Select a hex color code for the overlay, ex: #2196f3', 'ocean-instagram' ),
		        )
		    );
			
			$manager->register_setting(
		        'oig_instagram_overlay_bg', // Same as control name.
		        array(
		            'sanitize_callback' => 'butterbean_maybe_hash_hex_color',
		        )
		    );

			$manager->register_control(
		        'oig_instagram_overlay_opacity', // Same as setting name.
		        array(
		            'section' 		=> 'oig_instagram_styling',
		            'type'    		=> 'range',
		            'label'   		=> esc_html__( 'Overlay Opacity', 'ocean-instagram' ),
		            'description'   => esc_html__( 'Enter your custom opacity for the overlay. Default is 0.9.', 'ocean-instagram' ),
					'attr'    		=> array(
						'min' 	=> '0.1',
						'max' 	=> '1',
						'step' 	=> '0.1',
					),
		        )
		    );
			
			$manager->register_setting(
		        'oig_instagram_overlay_opacity', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_text_field',
		            'default' 			=> '0.9',
		        )
		    );

		    $manager->register_control(
		        'oig_instagram_overlay_text_color', // Same as setting name.
		        array(
		            'section' 		=> 'oig_instagram_styling',
		            'type'    		=> 'color',
		            'label'   		=> esc_html__( 'Overlay Text', 'ocean-instagram' ),
		            'description'   => esc_html__( 'Select a hex color code for the overlay text, ex: #fff', 'ocean-instagram' ),
		        )
		    );
			
			$manager->register_setting(
		        'oig_instagram_overlay_text_color', // Same as control name.
		        array(
		            'sanitize_callback' => 'butterbean_maybe_hash_hex_color',
		        )
		    );
		
			$manager->register_section(
		        'oig_instagram_tablet_device',
		        array(
		            'label' => esc_html__( 'Tablet Device', 'ocean-instagram' ),
		            'icon'  => 'dashicons-tablet'
		        )
		    );

			$manager->register_control(
				'oig_instagram_tablet_columns',
				array(
					'section'     => 'oig_instagram_tablet_device',
					'type'        => 'number',
					'label'       => esc_html__( 'Number of columns', 'ocean-instagram' ),
					'description' => esc_html__( 'The number of columns you want. Maximum 10.', 'ocean-instagram' ),
					'attr'        => array(
						'min' 	=> '1',
						'max' 	=> '10',
						'step' 	=> '1',
					),
				)
			);

			$manager->register_setting(
				'oig_instagram_tablet_columns',
				array(
					'sanitize_callback' => array( $this, 'sanitize_absint' ),
					'default'           => '3'
				)
			);

			$manager->register_control(
				'oig_instagram_tablet_item_ratio',
				array(
					'section'     => 'oig_instagram_tablet_device',
					'type'        => 'number',
					'label'       => esc_html__( 'Item ratio', 'ocean-instagram' ),
					'description' => esc_html__( 'The ratio of your images. Maximum 2.', 'ocean-instagram' ),
					'attr'        => array(
						'min' 	=> '0.1',
						'max' 	=> '2',
						'step' 	=> '0.01',
					),
				)
			);

			$manager->register_setting(
				'oig_instagram_tablet_item_ratio',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'default'           => '0.66'
				)
			);

			$manager->register_control(
				'oig_instagram_tablet_space',
				array(
					'section'     => 'oig_instagram_tablet_device',
					'type'        => 'number',
					'label'       => esc_html__( 'Space between images (px)', 'ocean-instagram' ),
					'description' => esc_html__( 'Space between each images.', 'ocean-instagram' ),
					'attr'        => array(
						'min' 	=> '0',
						'step' 	=> '1',
					),
				)
			);

			$manager->register_setting(
				'oig_instagram_tablet_space',
				array(
					'sanitize_callback' => array( $this, 'sanitize_absint' ),
					'default'           => '0'
				)
			);
		
			$manager->register_section(
		        'oig_instagram_mobile_device',
		        array(
		            'label' => esc_html__( 'Mobile Device', 'ocean-instagram' ),
		            'icon'  => 'dashicons-smartphone'
		        )
		    );

			$manager->register_control(
				'oig_instagram_mobile_columns',
				array(
					'section'     => 'oig_instagram_mobile_device',
					'type'        => 'number',
					'label'       => esc_html__( 'Number of columns', 'ocean-instagram' ),
					'description' => esc_html__( 'The number of columns you want. Maximum 10.', 'ocean-instagram' ),
					'attr'        => array(
						'min' 	=> '1',
						'max' 	=> '10',
						'step' 	=> '1',
					),
				)
			);

			$manager->register_setting(
				'oig_instagram_mobile_columns',
				array(
					'sanitize_callback' => array( $this, 'sanitize_absint' ),
					'default'           => '1'
				)
			);

			$manager->register_control(
				'oig_instagram_mobile_item_ratio',
				array(
					'section'     => 'oig_instagram_mobile_device',
					'type'        => 'number',
					'label'       => esc_html__( 'Item ratio', 'ocean-instagram' ),
					'description' => esc_html__( 'The ratio of your images. Maximum 2.', 'ocean-instagram' ),
					'attr'        => array(
						'min' 	=> '0.1',
						'max' 	=> '2',
						'step' 	=> '0.01',
					),
				)
			);

			$manager->register_setting(
				'oig_instagram_mobile_item_ratio',
				array(
					'sanitize_callback' => 'sanitize_text_field',
					'default'           => '0.66'
				)
			);

			$manager->register_control(
				'oig_instagram_mobile_space',
				array(
					'section'     => 'oig_instagram_mobile_device',
					'type'        => 'number',
					'label'       => esc_html__( 'Space between images (px)', 'ocean-instagram' ),
					'description' => esc_html__( 'Space between each images.', 'ocean-instagram' ),
					'attr'        => array(
						'min' 	=> '0',
						'step' 	=> '1',
					),
				)
			);

			$manager->register_setting(
				'oig_instagram_mobile_space',
				array(
					'sanitize_callback' => array( $this, 'sanitize_absint' ),
					'default'           => '0'
				)
			);

		}

		/**
		 * Sanitize function for integers
		 *
		 * @since  1.0.0
		 */
		public function sanitize_absint( $value ) {
			return $value && is_numeric( $value ) ? absint( $value ) : '';
		}

		/**
		 * Add shorcode metabox
		 * The $this variable is not used to get the display_meta_box() function because it doesn't work on some hosts.
		 *
		 * @since 1.0.0
		 */
		public function add_meta_box( $post ) {

			add_meta_box(
				'oig-shortcode-metabox',
				esc_html__( 'Shortcode', 'ocean-instagram' ),
				array( $this, 'display_meta_box' ),
				'instagram_shortcodes',
				'side',
				'low'
			);

		}

		/**
		 * Add shorcode metabox
		 *
		 * @since 1.0.0
		 */
		public function display_meta_box( $post ) { ?>

			<input type="text" class="widefat" value='[oceanwp_instagram id="<?php echo $post->ID; ?>"]' readonly />

		<?php
		}

	}

}

new OIG_Shortcode_Generator();
