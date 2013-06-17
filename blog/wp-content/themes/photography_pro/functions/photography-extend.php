<?php

/*
----- Table of Contents

	1.  Load other functions
	2.  Theme specific variables
	3.  Add gallery custom fields
	4.  Gallery Functions
				I.    Register Gallery Post Type
				II.   Add option to re-attach images
				III.  Display contextual help for Galleries
				IV.   Image max width
				V.    Set image sizes
				VI.   Gallery Custom Fields
				VII.  Flickr Custom Fields
				VIII. Save Custom Fields
				IX.   Get Flickr Gallery image size
				X.    Remove "Protected" from post title of protected galleries
				XI.   Filter upload columns
				XII.  Gallery update messages
				XIII. Get Flickr User ID
				XIV.  Build Flickr images URLs
				XV.   Get images from Flickr set
				XVI.  Get Flickr set primary image
				XVII. Get gallery images
				XVIII. Get Gallery IDs for Gallery template
				XIX. Filter password protected posts from the query
	5.  Register sidebar
	6.  Theme admin panel
	7.  Navigation Function
	8.  Define theme options
	9. Theme option return functions
				I.    Logo Functions
				II.   Flickr functions
				III.  Color functions
				IV.   Subscribe Functions
				V.    Footer Functions
	10. Main Menu Fallback
	11. Enqueue Client Files
*/

/*---------------------------------------------------------
	1. Load other functions
------------------------------------------------------------ */

locate_template( array( 'functions' . DIRECTORY_SEPARATOR . 'comments.php' ), true );
locate_template( array( 'functions' . DIRECTORY_SEPARATOR . 'ttf-admin.php' ), true );
locate_template( array( 'functions' . DIRECTORY_SEPARATOR . 'phpFlickr.php' ), true );


if (!class_exists( 'Photography' )) {
	class Photography extends TTFCore {

		public $gallery_ids;

		/*---------------------------------------------------------
			2. Theme specific variables
		------------------------------------------------------------ */
		function Photography () {

			$this->themename = "Photography";
			$this->themeurl = "http://thethemefoundry.com/photography/";
			$this->shortname = "photography";
			$this->domain = $this->shortname;
			$this->image_sizes = array(
				'index-feature' =>  array( 'width' => 628, 'height' => 250, 'crop' => true ),
				'gallery-thumb' =>  array( 'width' => 280, 'height' => 195, 'crop' => true ),
				'gallery-full' =>   array( 'width' => 948, 'height' => 534, 'crop' => true ),
				'page' =>           array( 'width' => 960, 'height' => 460, 'crop' => true ),
				'galleria-thumb' => array( 'width' => 61,  'height' => 42,  'crop' => true ),
				'single-gallery' => array( 'width' => 960, 'height' => 9999, 'crop' => false)
			);

			$this->gallery_ids = array();

			add_action( 'admin_menu', array(&$this, 'action_gallery_custom_fields') );
			add_action( 'contextual_help', array(&$this, 'add_help_text'), 10, 3 );
			add_action( 'init', array(&$this, 'photo_gallery_post_type' ) );
			add_action( 'init', array(&$this, 'registerMenus' ));
			add_action( 'manage_media_custom_column', array(&$this, 'action_media_custom_columns'), 0, 2);
			add_action( 'save_post', array(&$this, 'gallery_save_data'));
			add_action( 'setup_theme_photography', array(&$this, 'setOptions' ) );
			add_action( 'wp_enqueue_scripts', array( &$this, 'enqueueClientFiles' ));

			add_filter( 'manage_upload_columns', array(&$this, 'filter_upload_columns'));
			add_filter( 'post_updated_messages', array(&$this, 'gallery_updated_messages'));
			add_filter( 'the_title', array(&$this, 'filter_the_title_trim'));
			add_filter( 'post_gallery', array( &$this, 'get_page_galleries' ), 10, 2 ); // Filter [gallery] display

			$GLOBALS['flickr'] = new phpFlickr( $this->flickrApiKey() );

			parent::TTFCore();
		}

		/*---------------------------------------------------------
			3. Add gallery custom fields
		------------------------------------------------------------ */
		function action_gallery_custom_fields() {
			add_meta_box( 'flickr-fields', __( 'Flickr', 'photography' ), array(&$this, 'display_flickr_custom_fields'), 'gallery', 'normal', 'high' );
			add_meta_box( 'gallery-fields', __( 'Gallery Options', 'photography' ), array(&$this, 'display_gallery_custom_fields'), 'gallery', 'normal', 'high' );
		}

		/*---------------------------------------------------------
			4. Gallery functions
		------------------------------------------------------------ */

		/*---------------------------------------------------------
			I. Register Gallery Post Type
		------------------------------------------------------------ */
		function photo_gallery_post_type() {
			$labels = array(
				'name' => _x( 'Galleries', 'post type general name', 'photography' ),
				'singular_name' => _x( 'Gallery', 'post type singular name', 'photography' ),
				'add_new' => _x( 'Add New', 'gallery', 'photography' ),
				'add_new_item' => __( 'Add New Gallery', 'photography' ),
				'edit_item' => __( 'Edit Gallery', 'photography' ),
				'new_item' => __( 'New Gallery', 'photography' ),
				'view_item' => __( 'View Gallery', 'photography' ),
				'search_items' => __( 'Search Galleries', 'photography' ),
				'not_found' => __( 'No galleries found', 'photography' ),
				'not_found_in_trash' => __( 'No galleries found in Trash', 'photography' ),
				'parent_item_colon' => ''
			);
			$args = array(
				'labels' => $labels,
				'public' => true,
				'publicly_queryable' => true,
				'show_ui' => true,
				'capability_type' => 'page',
				'menu_position' => 10,
				'query_var' => true,
				'menu_icon' => get_template_directory_uri() . '/images/menu-icon.png',
				'rewrite' => array( 'slug' => 'gallery' ),
				'supports' => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
				'taxonomies' => array( 'category' )
			);

			register_post_type( 'gallery', $args );
		}

		/*---------------------------------------------------------
			II. Add option to re-attach images
		------------------------------------------------------------ */
		function action_media_custom_columns($column_name, $id) {

			$post = get_post($id);

			if($column_name != 'better_parent')
				return;

			if ( $post->post_parent > 0 ) {
				if ( get_post($post->post_parent) ) {
					$title = get_the_title( $post->post_parent );
					if ( empty( $title ) ) {
						$title = __('(no title)', 'photography' );
					}
					$title =_draft_or_post_title($post->post_parent);
				}
				?>
				<strong><a href="<?php echo get_edit_post_link( $post->post_parent ); ?>"><?php echo $title ?></a></strong>, <?php echo get_the_time(__('Y/m/d')); ?>
				<br />
				<a class="hide-if-no-js" onclick="findPosts.open('media[]','<?php echo $post->ID ?>');return false;" href="#the-list"><?php _e('Re-Attach', 'photography'); ?></a></td>

				<?php
			} else {
				?>
				<?php _e('(Unattached)', 'photography'); ?><br />
				<a class="hide-if-no-js" onclick="findPosts.open('media[]','<?php echo $post->ID ?>');return false;" href="#the-list"><?php _e('Attach', 'photography'); ?></a>
				<?php
			}
		}

		/*---------------------------------------------------------
			III. Display contextual help for Galleries
		------------------------------------------------------------ */
		function add_help_text($contextual_help, $screen_id, $screen) {
			//$contextual_help = . var_dump($screen); // use this to help determine $screen->id
			if ('gallery' == $screen->id ) {
				$contextual_help =
					'<p>' . __('Things to remember when adding or editing a gallery:', 'photography') . '</p>' .
					'<ul>' .
					'<li>' . __('Specify the correct genre such as Mystery, or Historic.', 'photography') . '</li>' .
					'<li>' . __('Specify the correct writer of the gallery.	 Remember that the Author module refers to you, the author of this gallery review.', 'photography') . '</li>' .
					'</ul>' .
					'<p>' . __('If you want to schedule the gallery review to be published in the future:', 'photography') . '</p>' .
					'<ul>' .
					'<li>' . __('Under the Publish module, click on the Edit link next to Publish.', 'photography') . '</li>' .
					'<li>' . __('Change the date to the date to actual publish this article, then click on Ok.', 'photography') . '</li>' .
					'</ul>' .
					'<p><strong>' . __('For more information:', 'photography') . '</strong></p>' .
					'<p>' . __('<a href="http://codex.wordpress.org/Posts_Edit_SubPanel" target="_blank">Edit Posts Documentation</a>', 'photography') . '</p>' .
					'<p>' . __('<a href="http://wordpress.org/support/" target="_blank">Support Forums</a>', 'photography') . '</p>' ;
			} elseif ( 'edit-gallery' == $screen->id ) {
				$contextual_help =
					'<p>' . __('This is the help screen displaying the table of galleries.', 'photography') . '</p>' ;
			}
			return $contextual_help;
		}

		/*---------------------------------------------------------
			IV. Image max width
		------------------------------------------------------------ */
		function addContentWidth() {
			global $content_width;
			if ( ! isset( $content_width ) ) {
				$content_width = 628;
			}
		}

		/*---------------------------------------------------------
			V. Set image sizes
		------------------------------------------------------------ */
		function addImageSize() {
			foreach ($this->image_sizes as $name => $values) {
				add_image_size( $name, $values['width'], $values['height'], $values['crop'] );
			}
		}

		/*---------------------------------------------------------
			VI. Gallery Custom Fields
		------------------------------------------------------------ */
		function display_gallery_custom_fields() {
			global $post;
			?>
			<div>
				<?php wp_nonce_field( 'photography-custom-fields', 'photography-custom-fields_wpnonce', false, true ); ?>
				<div style="padding: 15px 0 15px 5px">
					<input type="checkbox" id="disable_gallery_meta" name="disable_gallery_meta" <?php if ( get_post_meta( $post->ID, 'disable_gallery_meta', true ) ) { echo 'checked="checked"'; }?> />
					<label for="disable_gallery_meta"><?php _e( 'Disable the Gallery title and description (appears below the gallery).', 'photography' ); ?></label>
				</div>
				<div style="padding: 0 0 10px 5px">
					<input type="checkbox" id="disable_image_meta" name="disable_image_meta" <?php if ( get_post_meta( $post->ID, 'disable_image_meta', true ) ) { echo 'checked="checked"'; }?> />
					<label for="disable_image_meta"><?php _e( 'Disable image descriptions (appears in the top left corner of each image).', 'photography' ); ?></label>
				</div>
				<div style="padding: 0 0 10px 5px">
					<input type="checkbox" id="image_crop" name="image_crop" <?php if ( get_post_meta( $post->ID, 'image_crop', true ) ) { echo 'checked="checked"'; }?> />
					<label for="image_crop"><?php _e( 'Enable image hard cropping to the stage size.', 'photography' ); ?></label>
				</div>
				<div style="padding: 0 0 10px 5px">
					<input type="checkbox" id="auto_play" name="auto_play" <?php if ( get_post_meta( $post->ID, 'auto_play', true ) ) { echo 'checked="checked"'; }?> />
					<label for="auto_play"><?php _e( 'Enable auto play.', 'photography' ); ?></label>
				</div>
				<div style="padding: 0 0 10px 5px">
					<label for="image_height"><?php _e( 'Set a custom gallery stage height (default is 540px):', 'photography' ); ?></label>
					<input type="text" id="image_height" name="image_height" value="<?php echo get_post_meta( $post->ID, 'image_height', true )?>" />
				</div>
			</div>
			<?php
		}

		/*---------------------------------------------------------
			VII. Flickr Custom Fields
		------------------------------------------------------------ */
		function display_flickr_custom_fields() {
			global $post, $flickr;

			$flickr_user_id = $this->flickrUserId();
			$flickr_api_key = $this->flickrApiKey();

			if ( !empty($flickr_user_id) && !empty($flickr_api_key) ) {
				$flickr_sets = $flickr->photosets_getList( $this->flickrUserId() );
				if ( count( $flickr_sets['photoset'] ) == 0 ) {
					?>
					<div><?php _e( 'You currently have no publicly viewable Flickr sets.', 'photography', 'photography' ); ?></div>
					<?php
				} else {
					?>
					<div>
						<?php wp_nonce_field( 'photography-custom-fields', 'photography-custom-fields_wpnonce', false, true ); ?>
						<div style="padding: 15px 0 15px 5px"><label for="enable_flickr"><?php _e( 'Enable Flickr:', 'photography' ); ?></label> <input type="checkbox" id="enable_flickr" name="enable_flickr" <?php if ( get_post_meta( $post->ID, 'enable_flickr', true ) ) { echo 'checked="checked"'; }?> /></div>
						<div style="padding: 0 0 10px 5px">
							<?php _e( 'Flickr image size:', 'photography' ); ?>
							<select id="flickr_image_size" name="flickr_image_size" >
									<option value="medium" <?php if ( $this->get_flickr_image_size( $post->ID ) == 'medium' ) { echo 'selected="selected"'; }?>>Medium</option>
									<option value="large" <?php if ( $this->get_flickr_image_size( $post->ID ) == 'large' ) { echo 'selected="selected"'; }?>>Large</option>
							</select>
						</div>
						<div style="padding: 0 0 10px 5px">
							<?php _e( 'Flickr set:', 'photography' ); ?>
							<select id="flickr_set" name="flickr_set" >
								<?php foreach ($flickr_sets['photoset'] as $photoset) { ?>
									<option value="<?php echo $photoset["id"]; ?>" <?php if ( (string)get_post_meta( $post->ID, 'flickr_set', true ) == (string)$photoset["id"] ) { echo 'selected="selected"'; }?>><?php echo $photoset["title"]; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<?php
				}
			} else {
				?>
				<div><?php _e( 'You must provide your Flickr user ID and API Key in Appearance &rarr; Photography Options &rarr; Flickr', 'photography' ); ?></div>
			<?php
			}
		}

		/*---------------------------------------------------------
			VIII. Save Custom Fields
		------------------------------------------------------------ */
		function gallery_save_data( $post_id ) {

			if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
				return $post_id;

			if ( !isset( $_POST[ 'photography-custom-fields_wpnonce' ] ) || !wp_verify_nonce( $_POST[ 'photography-custom-fields_wpnonce' ], 'photography-custom-fields' ) )
				return $post_id;

			$flickr_data = array( 'enable_flickr', 'flickr_set', 'flickr_image_size', 'disable_image_meta', 'disable_gallery_meta', 'image_crop', 'auto_play', 'image_height' );

			foreach ( $flickr_data as $field ) {
				if ( isset( $_POST[ $field ] ) && trim( $_POST[ $field ] ) ) {
					update_post_meta( $post_id, $field, $_POST[ $field ] );
				} else {
					delete_post_meta( $post_id, $field );
				}
			}

			return true;
		}

		/*---------------------------------------------------------
			IX. Get Flickr Gallery images size
		------------------------------------------------------------ */
		function get_flickr_image_size( $post_id ) {
			if(get_post_meta( $post_id, 'flickr_image_size', false )){
				return get_post_meta( $post_id, 'flickr_image_size', true );
			}else{
				return 'large';
			}
		}

		/*---------------------------------------------------------
			X. Remove "Protected" from post title of protected galleries
		------------------------------------------------------------ */
		function filter_the_title_trim ( $title ) {
			$findthese = array(
				'Protected: ',
				'Private: '
			);
			$replacewith = array(
				'', // What to replace "Protected:" with
				'' // What to replace "Private:" with
			);
			$title = str_replace($findthese, $replacewith, $title);
			return $title;
		}

		/*---------------------------------------------------------
			XI. Filter upload columns
		------------------------------------------------------------ */
		function filter_upload_columns($columns) {
			unset($columns['parent']);
			$columns['better_parent'] = __( 'Parent', 'photography' );
			return $columns;
		}

		/*---------------------------------------------------------
			XII. Gallery update messages
		------------------------------------------------------------ */
		function gallery_updated_messages( $messages ) {

			global $post;
			$post_id = empty($post->ID) ? 0 : (int) $post->ID;

			$messages['gallery'] = array(
				0 => '', // Unused. Messages start at index 1.
				1 => sprintf(
					__('Gallery updated. <a href="%s">View gallery</a>', 'photography'),
					esc_url( get_permalink($post_id) )
				),
				2 => __('Custom field updated.', 'photography'),
				3 => __('Custom field deleted.', 'photography'),
				4 => __('Gallery updated.', 'photography'),
				5 => isset($_GET['revision']) ? sprintf(
					__('Gallery restored to revision from %s', 'photography'),
					wp_post_revision_title( isset( $_GET['revision'] ) ? (int) $_GET['revision'] : 0, false )
				) : false,
				6 => sprintf(
					__('Gallery published. <a href="%s">View gallery</a>', 'photography'),
					esc_url( get_permalink($post_id) )
				),
				7 => __('Gallery saved.', 'photography'),
				8 => sprintf(
					__('Gallery submitted. <a target="_blank" href="%s">Preview gallery</a>', 'photography'),
					esc_url( add_query_arg( 'preview', 'true', get_permalink($post_id) ) )
				),
				9 => sprintf(
					__('Gallery scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview gallery</a>', 'photography'),
					date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ),
					esc_url( get_permalink($post_id) )
				),
				10 => sprintf(
					__('Gallery draft updated. <a target="_blank" href="%s">Preview gallery</a>', 'photography'),
					esc_url( add_query_arg( 'preview', 'true', get_permalink($post_id) ) )
				),
			);

			return $messages;
		}

		/*---------------------------------------------------------
			XIII. Get Flickr User ID
		------------------------------------------------------------ */
		function getFlickrUserId ( $username ) {
			global $flickr;
			$flickr_user = $flickr->urls_lookupUser( 'http://flickr.com/photos/'.$username );
			return $flickr_user['id'];
		}

		/*---------------------------------------------------------
			XIV. Build Flickr images URLs
		------------------------------------------------------------ */
		function get_flickr_images ( $post_id ) {
			global $flickr;

			$images = $flickr->photosets_getPhotos ( get_post_meta( $post_id, 'flickr_set', true ) );
			$photos = array();
			foreach ( $images['photoset']['photo'] as $image ) {
				$url = $flickr->buildPhotoURL( $image, $this->get_flickr_image_size( $post_id ));
				$thumb = $flickr->buildPhotoURL( $image, "small" );
				$title = get_post_meta( $post_id, 'disable_image_meta', true ) ? '' : addslashes( $image['title'] );
				$photos[] = "{ image: '".$url."', thumb: '".$thumb."', title: '".$title."' }";
			}
			return $photos;
		}

		/*---------------------------------------------------------
			XV. Get images from Flickr set
		------------------------------------------------------------ */
		function get_flickr_images_static ( $post_id ) {
			global $flickr;
			return $flickr->photosets_getPhotos ( get_post_meta( $post_id, 'flickr_set', true ) );
		}

		/*---------------------------------------------------------
			XVI. Get Flickr set primary image
		------------------------------------------------------------ */
		function get_flickr_set_primary ( $post_id ) {
			global $flickr;
			$info = $flickr->photosets_getInfo ( get_post_meta( $post_id, 'flickr_set', true ) );
			return "http://farm".$info['farm'].".static.flickr.com/".$info['server']."/".$info['primary']."_".$info['secret'].".jpg";
		}

		/*---------------------------------------------------------
			XVII. Get gallery images
		------------------------------------------------------------ */
		function get_gallery_images ( $post_id, $homepage = false, $post_type = "attachment", $mime_type = "image" ) {
			$images = get_children( array( 'post_parent' => $post_id, 'post_type' => $post_type, 'post_mime_type' => $mime_type, 'order' => 'ASC',
				'orderby' => 'menu_order' ) );
			if ( $homepage ) {
				return $images;
			}
			$photos = array();
			foreach( $images as $image ) {
				// Will grab the full-size image if single-gallery is not set
				$large_image = wp_get_attachment_image_src( $image->ID, 'single-gallery' );

				$url = $large_image[0];

				$thumb_image = wp_get_attachment_image_src( $image->ID, 'galleria-thumb');

				// we must check the dimensions of the image returned by wp_get_attachment_image_src() because
				// it returns the full image size if the 'galleria-thumb' size is not found
				$galleria_thumb = $this->image_sizes['galleria-thumb'];
				$matches_galleria_dimensions =
					$thumb_image[1] == $galleria_thumb['width'] || $thumb_image[2] == $galleria_thumb['height'];

				if ( ! $thumb_image || ! $matches_galleria_dimensions ) {
					$thumb_image = wp_get_attachment_image_src( $image->ID, 'gallery-thumb' );
				}
				$thumb_url = $thumb_image[0];

				$title = get_post_meta( $post_id, 'disable_image_meta', true ) ? '' : addslashes( $image->post_title );
				$description = get_post_meta( $post_id, 'disable_image_meta', true ) ? '' : addslashes( $image->post_content );

				$photos[] = "{ image: '".$url."', thumb: '".$thumb_url."', title: '".str_replace( array( "\r\n", "\n", "\r" ), "<br />", $title )."', description: '".str_replace( array( "\r\n", "\n", "\r" ), "<br />", $description )."' }";
			}

			return $photos;
		}

		/*---------------------------------------------------------
			XVIII. Get Gallery IDs for Gallery template
		------------------------------------------------------------ */
		function get_page_galleries( $output, $attr ) {
			// only apply this filter if we're on the Gallery template page
			if ( is_page_template( 'gallery.php' ) && isset( $attr['id'] ) ) {
				// add ID to 'galleries' to be pulled
				array_push( $this->gallery_ids, $attr['id'] );

				// prevent gallery from outputting anything
				return " ";
			} else {
				return $output;
			}
		}

		/*---------------------------------------------------------
			XIX. Filter password protected posts from the query
		------------------------------------------------------------ */
		function remove_password_posts( $where = '' ) {
			global $wpdb;
			if ( ! is_admin() && ! is_singular() ) {
				// exclude password protected
				$where .= " AND $wpdb->posts.post_password = ''";
			}

			return $where;
		}

		/*---------------------------------------------------------
			5. Register sidebar
		------------------------------------------------------------ */
		function registerSidebars() {
			register_sidebar(array(
				'name'=> __( 'Sidebar', 'photography' ),
				'id' => 'sidebar',
				'before_widget' => '<li id="%1$s" class="widget %2$s">',
				'after_widget' => '</li>',
				'before_title' => '<h2 class="widgettitle">',
				'after_title' => '</h2>',
			));
		}

		/*---------------------------------------------------------
			6. Theme Admin Panel
		------------------------------------------------------------ */
		function addAdminPage() {
			// global $themename, $shortname, $options;
			if ( current_user_can( 'edit_theme_options' ) && isset( $_GET['page'] ) && $_GET['page'] == 'ttf-admin.php' ) {
				if ( ! empty( $_REQUEST['save-theme-options-nonce'] ) && wp_verify_nonce( $_REQUEST['save-theme-options-nonce'], 'save-theme-options' ) && isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'save' ) {
					foreach ($this->options as $value) {
						if ( array_key_exists('id', $value) ) {
							if ( isset( $_REQUEST[ $value['id'] ] ) ) {
								if( $value['id'] == $this->shortname . '_flickr_user_name' ) {
									global $flickr;
									if ( ! $flickr->api_key && isset( $_REQUEST[ $this->shortname . '_flickr_api_key' ] ) ) {
										$flickr = new phpFlickr( $_REQUEST[ $this->shortname . '_flickr_api_key' ] );
									}
									update_option( $this->shortname . '_flickr_user_id', $this->getFlickrUserId( $_REQUEST[ $value['id'] ] ) );
									update_option( $value['id'], $_REQUEST[ $value['id'] ] );
								} elseif (
									in_array(
										$value['id'],
										array(
											$this->shortname.'_background_color',
											$this->shortname.'_hover_color',
											$this->shortname.'_link_color',
										)
									)
								) {
									$opt_value = preg_match( '/^#([a-zA-Z0-9]){3}$|([a-zA-Z0-9]){6}$/', trim( $_REQUEST[ $value['id'] ] ) ) ? trim( $_REQUEST[ $value['id'] ] ) : '';
									update_option( $value['id'], $opt_value );
								} elseif (
									in_array(
										$value['id'],
										array(
											$this->shortname.'_categories_to_exclude',
											$this->shortname.'_pages_to_exclude',
										)
									)
								) {
									$opt_value = implode(',', array_filter( array_map( 'intval', explode(',', $_REQUEST[ $value['id'] ] ) ) ) );
									update_option( $value['id'], $opt_value );
								} else {
									update_option( $value['id'], $_REQUEST[ $value['id'] ] );
								}
							} else {
								delete_option( $value['id'] );
							}
						}
					}
					wp_redirect("themes.php?page=ttf-admin.php&saved=true");
					exit;
				} else if ( ! empty( $_REQUEST['reset-theme-options-nonce'] ) && wp_verify_nonce( $_REQUEST['reset-theme-options-nonce'], 'reset-theme-options' ) && isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'reset' ) {
					foreach ($this->options as $value) {
						if ( array_key_exists('id', $value) ) {
							delete_option( $value['id'] );
						}
					}
					wp_redirect("themes.php?page=ttf-admin.php&reset=true");
					exit;
				}
			}

			add_theme_page(
				__( 'Theme Options' ),
				__( 'Theme Options' ),
				'edit_theme_options',
				'ttf-admin.php',
				array(&$this, 'adminPage' )
			);
		}

		/*---------------------------------------------------------
			7. Navigation Function
		------------------------------------------------------------ */
		function registerMenus() {
			register_nav_menu( 'nav-1', __( 'Top Navigation', 'photography' ) );
		}

		/*---------------------------------------------------------
			8. Define Theme Options
		------------------------------------------------------------ */
		function setOptions() {

			/*
				OPTION TYPES:
				- checkbox: name, id, desc, std, type
				- radio: name, id, desc, std, type, options
				- text: name, id, desc, std, type
				- colorpicker: name, id, desc, std, type
				- select: name, id, desc, std, type, options
				- textarea: name, id, desc, std, type, options
			*/

			$this->options = array(

				array(
					"name" => __( 'Custom logo image', 'photography' ),
					"type" => "subhead"),

				array(
					"name" => __( 'Enable custom logo image', 'photography' ),
					"id" => $this->shortname."_logo",
					"desc" => __( 'Check to use a custom logo in the header.', 'photography' ),
					"std" => "false",
					"type" => "checkbox"),

				array(
					"name" => __( 'Logo URL', 'photography' ),
					"id" => $this->shortname."_logo_img",
					"desc" => sprintf( __( 'Upload an image or enter an URL for your image.', 'photography' ), '<code>' . STYLESHEETPATH . '/images/</code>' ),
					"std" => '',
					"upload" => true,
					"class" => "logo-image-input",
					"type" => "upload"),

				array(
					"name" => __( 'Logo image <code>&lt;alt&gt;</code> tag', 'photography' ),
					"id" => $this->shortname."_logo_img_alt",
					"desc" => __( 'Specify the <code>&lt;alt&gt;</code> tag for your logo image.', 'photography' ),
					"std" => '',
					"type" => "text"),

				array(
					"name" => __( 'Display tagline', 'photography' ),
					"id" => $this->shortname."_tagline",
					"desc" => __( 'Check to show your tagline below your logo.', 'photography' ),
					"std" => '',
					"type" => "checkbox"),

				array(
					"name" => __( 'Flickr', 'photography' ),
					"type" => "subhead"),

				array(
					"name" => __( 'Flickr username', 'photography' ),
					"id" => $this->shortname."_flickr_user_name",
					"desc" => __( 'Specify your Flickr username (found in your URL): http://flickr.com/photos/<strong>username</strong>', 'photography' ),
					"std" => '',
					"type" => "text"),

				array(
					"name" => __( 'Flickr API key', 'photography' ),
					"id" => $this->shortname."_flickr_api_key",
					"desc" => __( 'Specify your Flickr API Key.', 'photography' ),
					"std" => '',
					"type" => "text"),

				array(
					"name" => __( 'Flickr API secret', 'photography' ),
					"id" => $this->shortname."_flickr_api_secret",
					"desc" => __( 'Specify your Flickr API Secret.', 'photography' ),
					"std" => '',
					"type" => "text"),

				array(
					"name" => __( 'Color scheme', 'photography' ),
					"type" => "subhead"),

				array(
					"name" => __( 'Color scheme', 'photography' ),
					"desc" => __( 'Choose your color scheme.', 'photography' ),
					"id" => $this->shortname."_color",
					"std" => "default",
					"type" => "select",
					"options" => array(
						"default" => __( 'Dark (default)', 'photography' ),
						"light" => __( 'Light', 'photography' ))),

				array(
					"name" => __( 'Gallery display', 'photography' ),
					"type" => "subhead"),

				array(
					"name" => __( 'Captions on Gallery pages', 'photography' ),
					"id" => $this->shortname."_gallery_captions",
					"desc" => __( 'Select the style of captions you\'d like on your Gallery page. <em>Basic</em> adds the caption below the gallery image. <em>Fancy</em> makes the captions appear when you hover on a gallery.', 'photography' ),
					"std" => "off",
					"type" => "select",
					"options" => array(
						"off" => __( 'Off (default)', 'photography' ),
						"basic" => __( 'Basic', 'photography' ),
						"fancy" => __( 'Fancy', 'photography' ))),

				array(
					"name" => __( 'Subscribe links', 'photography' ),
					"type" => "subhead"),

				array(
					"name" => __( 'Enable Twitter', 'photography' ),
					"id" => $this->shortname."_twitter_toggle",
					"desc" => __( 'Hip to Twitter? Check this box. Please set your Twitter username in the Twitter menu.', 'photography' ),
					"std" => '',
					"type" => "checkbox"),

				array(
					"name" => __( 'Enable Facebook', 'photography' ),
					"id" => $this->shortname."_facebook_toggle",
					"desc" => __( 'Check this box to show a link to your Facebook page.', 'photography' ),
					"std" => '',
					"type" => "checkbox"),

				array(
					"name" => __( 'Enable Flickr', 'photography' ),
					"id" => $this->shortname."_flickr_toggle",
					"desc" => __( 'Check this box to show a link to Flickr.', 'photography' ),
					"std" => '',
					"type" => "checkbox"),

				array(
					"name" => __( 'Enable Google+', 'photography' ),
					"id" => $this->shortname."_google_plus_toggle",
					"desc" => __( 'Check this box to show a link to Google+.', 'photography' ),
					"std" => '',
					"type" => "checkbox"),

				array(
					"name" => __( 'Disable all', 'photography' ),
					"id" => $this->shortname."_follow_disable",
					"desc" => __( 'Check this box to hide all follow icons (including RSS). This option overrides any other settings.', 'photography' ),
					"std" => '',
					"type" => "checkbox"),

				array(
					"name" => __( 'Twitter name', 'photography' ),
					"id" => $this->shortname."_twitter",
					"desc" => __( 'Enter your Twitter name.', 'photography' ),
					"std" => '',
					"type" => "text"),

				array(
					"name" => __( 'Facebook link', 'photography' ),
					"id" => $this->shortname."_facebook",
					"desc" => __( 'Enter your Facebook link.', 'photography' ),
					"std" => '',
					"type" => "text"),

				array(
					"name" => __( 'Flickr link', 'photography' ),
					"id" => $this->shortname."_flickr",
					"desc" => __( 'Enter your Flickr link.', 'photography' ),
					"std" => '',
					"type" => "text"),

				array(
					"name" => __( 'Google+ link', 'photography' ),
					"id" => $this->shortname."_google_plus",
					"desc" => __( 'Enter your Google+ link.', 'photography' ),
					"std" => '',
					"type" => "text"),

				array(
					"name" => __( 'Footer', 'photography' ),
					"type" => "subhead"),

				array(
					"name" => __( 'Copyright notice', 'photography' ),
					"id" => $this->shortname."_copyright_name",
					"desc" => __( 'Your name or the name of your business.', 'photography' ),
					"std" => __( 'Your Name Here', 'photography' ),
					"type" => "text")
			);
		}

		/*---------------------------------------------------------
			9. Theme option return functions
		------------------------------------------------------------ */

		/*---------------------------------------------------------
			I. Logo functions
		------------------------------------------------------------ */
		function logoState () {
			return get_option($this->shortname.'_logo' );
		}
		function logoName () {
			return get_option( $this->shortname.'_logo_img' );
		}
		function logoAlt () {
			return get_option($this->shortname.'_logo_img_alt' );
		}
		function logoTagline () {
			return get_option($this->shortname.'_tagline' );
		}

		/*---------------------------------------------------------
			II. Flickr functions
		------------------------------------------------------------ */
		function flickrUsername () {
			return get_option( $this->shortname . '_flickr_user_name' );
		}
		function flickrUserId () {
			return get_option( $this->shortname . '_flickr_user_id' );
		}
		function flickrApiKey () {
			return get_option( $this->shortname . '_flickr_api_key' );
		}
		function flickrApiSecret () {
			return get_option( $this->shortname . '_flickr_api_secret' );
		}

		/*---------------------------------------------------------
			III. Color functions
		------------------------------------------------------------ */
		function colorScheme () {
			return get_option($this->shortname.'_color' );
		}

		/*---------------------------------------------------------
			IV. Gallery display functions
		------------------------------------------------------------ */
		function gallery_captions() {
			return get_option( $this->shortname."_gallery_captions" );
		}

		/*---------------------------------------------------------
			V. Subscribe functions
		------------------------------------------------------------ */
		function twitterToggle() {
			return get_option($this->shortname.'_twitter_toggle' );
		}
		function twitter() {
			return stripslashes( wp_filter_post_kses(get_option($this->shortname.'_twitter' )) );
		}
		function facebook() {
			return stripslashes( wp_filter_post_kses(get_option($this->shortname.'_facebook' )) );
		}
		function facebookToggle() {
			return get_option($this->shortname.'_facebook_toggle' );
		}
		function flickr() {
			return stripslashes( wp_filter_post_kses(get_option($this->shortname.'_flickr' )) );
		}
		function flickrToggle() {
			return get_option($this->shortname.'_flickr_toggle' );
		}
		function googlePlus() {
			return get_option( $this->shortname."_google_plus" );
		}
		function googlePlusToggle() {
			return get_option( $this->shortname."_google_plus_toggle" );
		}
		function followDisable() {
			return get_option($this->shortname.'_follow_disable' );
		}

		/*---------------------------------------------------------
			V. Footer functions
		------------------------------------------------------------ */
		function copyrightName() {
			return stripslashes( wp_filter_post_kses(get_option($this->shortname.'_copyright_name' )) );
		}

		/*---------------------------------------------------------
			10. Main Menu Fallback
		------------------------------------------------------------ */
		function main_menu_fallback() {
			?>
			<div id="navigation">
				<ul class="nav">
					<?php
						wp_list_pages( 'title_li=' );
					?>
				</ul>
			</div>
			<?php
			}

		/*---------------------------------------------------------
			11. Enqueue Client Files
		------------------------------------------------------------ */
		function enqueueClientFiles() {
			global $wp_styles;

			if ( ! is_admin() ) {

				wp_enqueue_style(
					'photography-style',
					get_bloginfo( 'stylesheet_url' ),
					'',
					null
				);

				if ( ($this->colorScheme() == 'light' ) ) {

					wp_enqueue_style(
						'photo-colorscheme-style',
						get_template_directory_uri() . '/stylesheets/light.css',
						'',
						null
					);
				}

				wp_enqueue_style(
					'photography-ie-style',
					get_template_directory_uri() . '/stylesheets/ie.css',
					array( 'photography-style' ),
					null
				);
				$wp_styles->add_data( 'photography-ie-style', 'conditional', 'lt IE 8' );

				if ( is_singular() ) {
					wp_enqueue_script( 'comment-reply' );
				}

				wp_enqueue_script( 'jquery' );

				wp_enqueue_script(
					'photography-theme',
					get_template_directory_uri() . '/javascripts/photography.js',
					array( 'jquery' ),
					null
				);

				if ( is_single() && get_post_type() == 'gallery' ) {
					wp_enqueue_script(
						'photography-galleria',
						get_template_directory_uri() . '/javascripts/galleria.min.js',
						array( 'jquery' ),
						null
					);
				}

				if ( is_page_template( 'homepage.php' ) ) {
					wp_enqueue_script(
						'photography-cycle',
						get_template_directory_uri() . '/javascripts/jquery.cycle.lite.min.js',
						array( 'jquery' ),
						null
					);
				}
			}
		}
	}
}

/* SETTING EVERYTHING IN MOTION */
function load_photography_pro_theme() {
	$GLOBALS['photography'] = new Photography;
}

add_action( 'after_setup_theme', 'load_photography_pro_theme' );