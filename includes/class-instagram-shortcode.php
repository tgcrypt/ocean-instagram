<?php
/**
 * Instagram Shortcode
 */

if ( ! class_exists( 'OIG_Instagram_Shortcode' ) ) {

	class OIG_Instagram_Shortcode {

		/**
		 * @var OIG_Settings_API
		 */
		protected $api;

		/**
		 * Start things up
		 */
		public function __construct() {
			add_shortcode( 'oceanwp_instagram', array( $this, 'instagram_shortcode' ) );
			$this->api = OIG_Settings_API::getInstance();
		}

		/**
		 * Build the front end
		 *
		 * @since 1.0.0
		 */
		public function instagram_display( $id ) {

			// Variables
			$style  				= get_post_meta( $id, 'oig_instagram_style', true );
			$style   				= $style ? esc_attr( $style ) : 'default';
			$number  				= get_post_meta( $id, 'oig_instagram_number', true );
			$number   				= $number ? absint( $number ) : '6';
			$columns  				= get_post_meta( $id, 'oig_instagram_columns', true );
			$columns  				= $columns ? absint( $columns ) : '4';
			$likes    				= get_post_meta( $id, 'oig_instagram_likes', true );
			$likes    				= $likes ? esc_attr( $likes ) : 'on';
			$comments 				= get_post_meta( $id, 'oig_instagram_comments', true );
			$comments 				= $comments ? esc_attr( $comments ) : 'on';
			$caption  				= get_post_meta( $id, 'oig_instagram_caption', true );
			$caption  				= $caption ? esc_attr( $caption ) : 'off';
			$length   				= get_post_meta( $id, 'oig_instagram_caption_length', true );
			$length   				= $length ? absint( $length ) : '20';
			$ratio  				= get_post_meta( $id, 'oig_instagram_item_ratio', true );
			$ratio  				= $ratio ? esc_attr( $ratio ) : '0.66';
			$space  				= get_post_meta( $id, 'oig_instagram_space', true );
			$space  				= $space ? absint( $space ) : '0';
			$radius  				= get_post_meta( $id, 'oig_instagram_border_radius', true );
			$radius  				= $radius ? esc_attr( $radius ) : '0';
			$overlay_bg  			= get_post_meta( $id, 'oig_instagram_overlay_bg', true );
			$overlay_bg  			= $overlay_bg ? esc_attr( $overlay_bg ) : '#2196f3';
			$opacity  				= get_post_meta( $id, 'oig_instagram_overlay_opacity', true );
			$opacity  				= $opacity ? esc_attr( $opacity ) : '0.9';
			$overlay_text  			= get_post_meta( $id, 'oig_instagram_overlay_text_color', true );
			$overlay_text  			= $overlay_text ? esc_attr( $overlay_text ) : '#ffffff';
			$tablet_columns  		= get_post_meta( $id, 'oig_instagram_tablet_columns', true );
			$tablet_columns  		= $tablet_columns ? absint( $tablet_columns ) : '3';
			$tablet_ratio  			= get_post_meta( $id, 'oig_instagram_tablet_item_ratio', true );
			$tablet_ratio  			= $tablet_ratio ? esc_attr( $tablet_ratio ) : '';
			$tablet_space  			= get_post_meta( $id, 'oig_instagram_tablet_space', true );
			$tablet_space  			= $tablet_space ? absint( $tablet_space ) : '';
			$mobile_columns  		= get_post_meta( $id, 'oig_instagram_mobile_columns', true );
			$mobile_columns  		= $mobile_columns ? absint( $mobile_columns ) : '1';
			$mobile_ratio  			= get_post_meta( $id, 'oig_instagram_mobile_item_ratio', true );
			$mobile_ratio  			= $mobile_ratio ? esc_attr( $mobile_ratio ) : '';
			$mobile_space  			= get_post_meta( $id, 'oig_instagram_mobile_space', true );
			$mobile_space  			= $mobile_space ? absint( $mobile_space ) : '';

			// User infos
			$picture  				= get_post_meta( $id, 'oig_instagram_user_picture', true );
			$picture  				= $picture ? esc_attr( $picture ) : 'off';
			$username  				= get_post_meta( $id, 'oig_instagram_user_username', true );
			$username  				= $username ? esc_attr( $username ) : 'off';
			$follow  				= get_post_meta( $id, 'oig_instagram_user_follow', true );
			$follow  				= $follow ? esc_attr( $follow ) : 'off';
			$posts  				= get_post_meta( $id, 'oig_instagram_user_posts_follow', true );
			$posts  				= $posts ? esc_attr( $posts ) : 'off';
			$bio  					= get_post_meta( $id, 'oig_instagram_user_bio', true );
			$bio  					= $bio ? esc_attr( $bio ) : 'off';

			// Display the media data
			$media = $this->api->get_media( $number, $id );
			$media = $media['data'];

			// Display the user data
			$infos = $this->api->get_infos( $id );
			$infos = $infos['data'][0];

			// If no like icon
			if ( 'on' != $likes ) {
				$data_class = ' no-likes';
			}

			// Wrap classes
			$wrap_classes = array( 'ocean-instagram-wrap', 'clr' );

			// Style
			$wrap_classes[] = $style .'-style';

			// Turn classes into space seperated string
			$wrap_classes = implode( ' ', $wrap_classes );

			// Inner classes
			$classes = array( 'ocean-instagram-items', 'clr' );

			// Columns
			$classes[] = 'col-'. $columns;

			// Turn classes into space seperated string
			$classes = implode( ' ', $classes ); ?>

			<div id="oig-<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $wrap_classes ); ?>">

				<?php if ( $picture == 'on' || $username == 'on' || $follow == 'on' || $posts == 'on' || $bio == 'on' ) : ?>

					<div class="ocean-instagram-top">

						<?php if ( 'default' == $style ) { ?>

							<?php if ( $picture == 'on' ) : ?>

								<div class="ocean-instagram-picture">
									<img src="<?php echo esc_url( $infos['avatar'] ); ?>" alt="<?php echo esc_attr( $infos['name'] ); ?>">
								</div>

							<?php endif; ?>

							<div class="ocean-instagram-infos">

								<?php if ( $username == 'on' || $follow == 'on' ) : ?>

									<div class="ocean-instagram-username">

										<?php if ( $username == 'on' ) : ?>

											<h2><?php echo esc_attr( $infos['username'] ); ?></h2>

										<?php endif; ?>

										<?php if ( $follow == 'on' ) : ?>

											<div class="ocean-instagram-follow">
												<a class="ocean-instagram-subscribe" href="https://instagram.com/<?php echo esc_attr( $infos['username'] ); ?>/" target="_blank"><?php esc_html_e( 'Follow', 'ocean-instagram' ); ?></a>
											</div>

										<?php endif; ?>

									</div>

								<?php endif; ?>

								<?php if ( $posts == 'on' ) : ?>

									<ul class="ocean-instagram-posts">
										<li><span><?php echo esc_attr( $infos['posts'] ); ?></span> <?php esc_html_e( 'posts', 'ocean-instagram' ); ?></li>
										<li><span><?php echo esc_attr( $infos['followed_by'] ); ?></span> <?php esc_html_e( 'followers', 'ocean-instagram' ); ?></li>
										<li><span><?php echo esc_attr( $infos['follows'] ); ?></span> <?php esc_html_e( 'following', 'ocean-instagram' ); ?></li>
									</ul>

								<?php endif; ?>

								<?php if ( $bio == 'on' ) : ?>

									<div class="ocean-instagram-bio">

										<?php if ( ! empty( $infos['name'] ) ) : ?>
											<h2><?php echo esc_attr( $infos['name'] ); ?></h2>
										<?php endif; ?>

										<?php if ( ! empty( $infos['bio'] ) ) : ?>
											<span><?php echo esc_attr( $infos['bio'] ); ?></span>
										<?php endif; ?>

										<?php if ( ! empty( $infos['website'] ) ) : ?>
											<a href="<?php echo esc_url( $infos['website'] ); ?>" target="_blank"><?php echo esc_attr( $infos['website'] ); ?></a>
										<?php endif; ?>

									</div>

								<?php endif; ?>

							</div>

						<?php } else if ( 'widget' == $style ) { ?>

							<a class="ocean-instagram-header" href="https://instagram.com/<?php echo esc_attr( $infos['username'] ); ?>/" target="_blank">
								<?php if ( $picture == 'on' ) : ?>
									<img src="<?php echo esc_url( $infos['avatar'] ); ?>" alt="<?php echo esc_attr( $infos['name'] ); ?>">
								<?php endif; ?>

								<?php if ( $username == 'on' ) : ?>
									<span class="ocean-instagram-name"><?php echo esc_attr( $infos['username'] ); ?></span>
								<?php endif; ?>

								<span class="ocean-instagram-header-logo"></span>
							</a>

							<div class="ocean-instagram-panel">
								<?php if ( $posts == 'on' ) : ?>

									<ul class="ocean-instagram-posts">
										<li><span><?php echo esc_attr( $infos['posts'] ); ?></span><?php esc_html_e( 'posts', 'ocean-instagram' ); ?></li>
										<li><span><?php echo esc_attr( $infos['followed_by'] ); ?></span><?php esc_html_e( 'followers', 'ocean-instagram' ); ?></li>
										<li><span><?php echo esc_attr( $infos['follows'] ); ?></span><?php esc_html_e( 'following', 'ocean-instagram' ); ?></li>
									</ul>

								<?php endif; ?>

								<?php if ( $follow == 'on' ) : ?>
									<a class="ocean-instagram-subscribe" href="https://instagram.com/<?php echo esc_attr( $infos['username'] ); ?>/" target="_blank"><?php esc_html_e( 'Follow', 'ocean-instagram' ); ?></a>
								<?php endif; ?>

							</div>

							<?php if ( $bio == 'on' ) : ?>

								<div class="ocean-instagram-bio">

									<?php if ( ! empty( $infos['name'] ) ) : ?>
										<h2><?php echo esc_attr( $infos['name'] ); ?></h2>
									<?php endif; ?>

									<?php if ( ! empty( $infos['bio'] ) ) : ?>
										<span><?php echo esc_attr( $infos['bio'] ); ?></span>
									<?php endif; ?>

									<?php if ( ! empty( $infos['website'] ) ) : ?>
										<a href="<?php echo esc_url( $infos['website'] ); ?>" target="_blank"><?php echo esc_attr( $infos['website'] ); ?></a>
									<?php endif; ?>

								</div>

							<?php endif; ?>

						<?php } ?>

					</div>

					<?php if ( 'default' == $style ) { ?>

						<?php if ( $bio == 'on' ) : ?>

							<div class="ocean-instagram-bio ocean-instagram-hide">

								<?php if ( ! empty( $infos['name'] ) ) : ?>
									<h2><?php echo esc_attr( $infos['name'] ); ?></h2>
								<?php endif; ?>

								<?php if ( ! empty( $infos['bio'] ) ) : ?>
									<span><?php echo esc_attr( $infos['bio'] ); ?></span>
								<?php endif; ?>

								<?php if ( ! empty( $infos['website'] ) ) : ?>
									<a href="<?php echo esc_url( $infos['website'] ); ?>" target="_blank"><?php echo esc_url( $infos['website'] ); ?></a>
								<?php endif; ?>

							</div>

						<?php endif; ?>

						<?php if ( $posts == 'on' ) : ?>

							<ul class="ocean-instagram-posts ocean-instagram-hide">
								<li><span><?php echo esc_attr( $infos['posts'] ); ?></span> <?php esc_html_e( 'posts', 'ocean-instagram' ); ?></li>
								<li><span><?php echo esc_attr( $infos['followed_by'] ); ?></span><?php esc_html_e( 'followers', 'ocean-instagram' ); ?></li>
								<li><span><?php echo esc_attr( $infos['follows'] ); ?></span><?php esc_html_e( 'following', 'ocean-instagram' ); ?></li>
							</ul>

						<?php endif; ?>

					<?php } ?>

				<?php endif; ?>

				<div class="<?php echo esc_attr( $classes ); ?>">

					<?php foreach ( $media as $item ) : ?>

						<div class="ocean-instagram-item">

							<a id="<?php echo esc_attr( $item['id'] ); ?>" class="ocean-instagram-url" href="<?php echo esc_url( $item['url'] ); ?>" target="_blank">

								<div class="ocean-instagram-image">
									<img class="ocean-instagram-img" src="<?php echo esc_url( $item['img_std_res'] ); ?>" width="<?php echo esc_attr( $item['img_std_res_width'] ); ?>" height="<?php echo esc_attr( $item['img_std_res_height'] ); ?>" />
								</div>

								<?php if ( 'widget' != $style && ( $likes == 'on' || $comments == 'on' || $caption == 'on' ) ) : ?>

									<div class="ocean-instagram-data<?php echo esc_attr( $data_class ); ?>">

										<div class="ocean-instagram-data-inner">

											<?php if ( $likes == 'on' ) : ?>
												<div class="ocean-instagram-counter ocean-instagram-likes">
													<div class="ocean-instagram-icon ocean-instagram-icon-likes">
														<svg viewBox="0 0 24 24" width="24" height="24">
													        <path d="M17.7,1.5c-2,0-3.3,0.5-4.9,2.1c0,0-0.4,0.4-0.7,0.7c-0.3-0.3-0.7-0.7-0.7-0.7c-1.6-1.6-3-2.1-5-2.1C2.6,1.5,0,4.6,0,8.3 c0,4.2,3.4,7.1,8.6,11.5c0.9,0.8,1.9,1.6,2.9,2.5c0.1,0.1,0.3,0.2,0.5,0.2s0.3-0.1,0.5-0.2c1.1-1,2.1-1.8,3.1-2.7 c4.8-4.1,8.5-7.1,8.5-11.4C24,4.6,21.4,1.5,17.7,1.5z M14.6,18.6c-0.8,0.7-1.7,1.5-2.6,2.3c-0.9-0.7-1.7-1.4-2.5-2.1 c-5-4.2-8.1-6.9-8.1-10.5c0-3.1,2.1-5.5,4.9-5.5c1.5,0,2.6,0.3,3.8,1.5c1,1,1.2,1.2,1.2,1.2C11.6,5.9,11.7,6,12,6.1 c0.3,0,0.5-0.2,0.7-0.4c0,0,0.2-0.2,1.2-1.3c1.3-1.3,2.1-1.5,3.8-1.5c2.8,0,4.9,2.4,4.9,5.5C22.6,11.9,19.4,14.6,14.6,18.6z"></path>
													    </svg>
														<em><?php echo esc_attr( $item['likes'] ); ?></em>
													</div>
												</div>
											<?php endif; ?>

											<?php if ( $comments == 'on' ) : ?>
												<div class="ocean-instagram-counter ocean-instagram-comments">
													<div class="ocean-instagram-icon ocean-instagram-icon-comments">
														<svg viewBox="0 0 24 24" width="24" height="24">
													        <path d="M1,11.9C1,17.9,5.8,23,12,23c1.9,0,3.7-1,5.3-1.8l5,1.3l0,0c0.1,0,0.1,0,0.2,0c0.4,0,0.6-0.3,0.6-0.6c0-0.1,0-0.1,0-0.2 l-1.3-4.9c0.9-1.6,1.4-2.9,1.4-4.8C23,5.8,18,1,12,1C5.9,1,1,5.9,1,11.9z M2.4,11.9c0-5.2,4.3-9.5,9.5-9.5c5.3,0,9.6,4.2,9.6,9.5 c0,1.7-0.5,3-1.3,4.4l0,0c-0.1,0.1-0.1,0.2-0.1,0.3c0,0.1,0,0.1,0,0.1l0,0l1.1,4.1l-4.1-1.1l0,0c-0.1,0-0.1,0-0.2,0 c-0.1,0-0.2,0-0.3,0.1l0,0c-1.4,0.8-3.1,1.8-4.8,1.8C6.7,21.6,2.4,17.2,2.4,11.9z"></path>
													    </svg>
														<em><?php echo esc_attr( $item['comments'] ); ?></em>
													</div>
												</div>
											<?php endif; ?>

											<?php if ( $caption == 'on' ) : ?>
												<div class="ocean-instagram-caption">
													<?php echo esc_attr( wp_trim_words( $item['caption'], $length ) ); ?>
												</div>
											<?php endif; ?>

										</div>

									</div>

								<?php endif; ?>

							</a>

						</div>

					<?php endforeach; ?>

				</div>

			</div>

			<?php
			// Custom style
			if ( '4' != $columns
				|| '0.66' != $ratio
				|| '0' != $space
				|| '0' != $radius
				|| '#2196f3' != $overlay_bg
				|| '0.9' != $opacity
				|| '#ffffff' != $overlay_text
				|| '3' != $tablet_columns
				|| ! empty( $tablet_ratio )
				|| ! empty( $tablet_space )
				|| '1' != $mobile_columns
				|| ! empty( $mobile_ratio )
				|| ! empty( $mobile_space ) ) :

				// Define css var
				$css = '';
				$tablet_css = '';
				$mobile_css = '';

				// Columns
				if ( ! empty( $columns ) && '4' != $columns ) {
					$css .= '#oig-'. $id .' .ocean-instagram-items.col-'. $columns .' .ocean-instagram-item{width: calc( 100% / '. $columns .');}';
				}

				// Ratio
				if ( ! empty( $ratio ) && '0.66' != $ratio ) {
					$css .= '#oig-'. $id .' .ocean-instagram-url{padding-bottom: calc( '. $ratio .' * 100% );}';
				}

				// Space
				if ( ! empty( $space ) && '0' != $space ) {
					$css .= '#oig-'. $id .' .ocean-instagram-items .ocean-instagram-item{border: '. $space .'px solid transparent;}';

					// If it is not the widget style
					if ( 'widget' != $style ) {
						$css .= '#oig-'. $id .' .ocean-instagram-items{margin: 0 -'. $space .'px;}';
					}
				}

				// Border radius
				if ( ! empty( $radius ) && '0' != $radius ) {
					$css .= '#oig-'. $id .' .ocean-instagram-image{border-radius: '. $radius .';}';
				}

				// Overlay background
				if ( ! empty( $overlay_bg ) && '#2196f3' != $overlay_bg ) {
					$css .= '#oig-'. $id .' .ocean-instagram-image:after{background-color: '. $overlay_bg .';}';
				}

				// Overlay opacity
				if ( ! empty( $opacity ) && '0.9' != $opacity ) {
					$css .= '#oig-'. $id .' .ocean-instagram-url:hover .ocean-instagram-image:after{opacity: '. $opacity .';}';
				}

				// Overlay text color
				if ( ! empty( $overlay_text ) && '#ffffff' != $overlay_text ) {
					$css .= '#oig-'. $id .' .ocean-instagram-data-inner{color: '. $overlay_text .';}';
					$css .= '#oig-'. $id .' .ocean-instagram-icon svg{fill: '. $overlay_text .';}';
				}

				// Tablet columns
				if ( ! empty( $tablet_columns ) && '3' != $tablet_columns ) {
					$tablet_css .= '#oig-'. $id .' .ocean-instagram-items .ocean-instagram-item{width: calc( 100% / '. $tablet_columns .') !important;}';
				}

				// Tablet ratio
				if ( ! empty( $tablet_ratio ) ) {
					$tablet_css .= '#oig-'. $id .' .ocean-instagram-url{padding-bottom: calc( '. $tablet_ratio .' * 100% );}';
				}

				// Tablet space
				if ( ! empty( $tablet_space ) ) {
					$tablet_css .= '#oig-'. $id .' .ocean-instagram-items .ocean-instagram-item{border: '. $tablet_space .'px solid transparent;}';
					$tablet_css .= '#oig-'. $id .' .ocean-instagram-items{margin: 0 -'. $tablet_space .'px;}';
				}

				// Tablet css
				if ( ! empty( $tablet_css ) ) {
					$css .= '@media (max-width: 768px) {'. $tablet_css .'}';
				}

				// Mobile columns
				if ( ! empty( $mobile_columns ) && '1' != $mobile_columns ) {
					$mobile_css .= '#oig-'. $id .' .ocean-instagram-items .ocean-instagram-item{width: calc( 100% / '. $mobile_columns .') !important;}';
				}

				// Mobile ratio
				if ( ! empty( $mobile_ratio ) ) {
					$mobile_css .= '#oig-'. $id .' .ocean-instagram-url{padding-bottom: calc( '. $mobile_ratio .' * 100% );}';
				}

				// Mobile space
				if ( ! empty( $mobile_space ) ) {
					$mobile_css .= '#oig-'. $id .' .ocean-instagram-items .ocean-instagram-item{border: '. $mobile_space .'px solid transparent;}';
					$mobile_css .= '#oig-'. $id .' .ocean-instagram-items{margin: 0 -'. $mobile_space .'px;}';
				}

				// Mobile css
				if ( ! empty( $mobile_css ) ) {
					$css .= '@media (max-width: 480px) {'. $mobile_css .'}';
				}
					
				// Return CSS
				if ( ! empty( $css ) ) { ?>
					<style type="text/css"><?php echo wp_strip_all_tags( oceanwp_minify_css( $css ) ); ?></style>
				<?php } ?>

			<?php endif; ?>

		<?php
		}

		/**
		 * Registers the function as a shortcode
		 *
		 * @since 1.0.0
		 */
		public function instagram_shortcode( $atts, $content = null ) {

			// Attributes
			$atts = shortcode_atts( array(
				'id' => '',
			), $atts, 'oceanwp_instagram' );

			ob_start();

			if ( $atts[ 'id' ] ) {
				$this->instagram_display( $atts[ 'id' ] );
			}

			return ob_get_clean();

		}

	}

}
new OIG_Instagram_Shortcode();
