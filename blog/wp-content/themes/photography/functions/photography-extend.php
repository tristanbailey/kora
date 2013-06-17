<?php

/*
----- Table of Contents

	1.  Load other functions
	2.  Theme specific variables
	3.  Add gallery custom fields
	4.  Gallery Functions
				I.    Set labels
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
				XVIII.Create Gallery post type
	5.  Register sidebar
	6.  Theme admin panel
	7.  Navigation Function
	8.  Define theme options
	9.  Theme option return functions
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


if (!class_exists( 'Photography' )) {
	class Photography extends TTFCore {

		/*---------------------------------------------------------
			2. Theme specific variables
		------------------------------------------------------------ */
		function Photography () {

			$this->themename = "Photography";
			$this->themeurl = "http://thethemefoundry.com/photography/";
			$this->shortname = "photography";
			$this->domain = $this->shortname;

			add_action( 'init', array(&$this, 'registerMenus' ));
			add_action( 'setup_theme_photography', array(&$this, 'setOptions' ) );
			add_action( 'wp_enqueue_scripts', array( &$this, 'enqueueClientFiles' ));

			parent::TTFCore();
		}

		/*---------------------------------------------------------
			3. Add gallery custom fields
		------------------------------------------------------------ */

		/*---------------------------------------------------------
			4. Gallery functions
		------------------------------------------------------------ */

		/*---------------------------------------------------------
			I. Set labels
		------------------------------------------------------------ */

		/*---------------------------------------------------------
			II. Add option to re-attach images
		------------------------------------------------------------ */

		/*---------------------------------------------------------
			III. Display contextual help for Galleries
		------------------------------------------------------------ */

		/*---------------------------------------------------------
			IV. Image max width
		------------------------------------------------------------ */
		function addContentWidth() {
			global $content_width;
			if ( ! isset( $content_width ) ) {
				$content_width = 618;
			}
		}

		/*---------------------------------------------------------
			V. Set image sizes
		------------------------------------------------------------ */
		function addImageSize() {
			add_image_size( 'index-feature', 628, 250, true );
		}

		/*---------------------------------------------------------
			VI. Gallery Custom Fields
		------------------------------------------------------------ */

		/*---------------------------------------------------------
			VII. Flickr Custom Fields
		------------------------------------------------------------ */

		/*---------------------------------------------------------
			VIII. Save Custom Fields
		------------------------------------------------------------ */

		/*---------------------------------------------------------
			IX. Get Flickr Gallery images size
		------------------------------------------------------------ */

		/*---------------------------------------------------------
			X. Remove "Protected" from post title of protected galleries
		------------------------------------------------------------ */

		/*---------------------------------------------------------
			XI. Filter upload columns
		------------------------------------------------------------ */

		/*---------------------------------------------------------
			XII. Gallery update messages
		------------------------------------------------------------ */

		/*---------------------------------------------------------
			XIII. Get Flickr User ID
		------------------------------------------------------------ */

		/*---------------------------------------------------------
			XIV. Build Flickr images URLs
		------------------------------------------------------------ */

		/*---------------------------------------------------------
			XV. Get images from Flickr set
		------------------------------------------------------------ */

		/*---------------------------------------------------------
			XVI. Get Flickr set primary image
		------------------------------------------------------------ */

		/*---------------------------------------------------------
			XVII. Get gallery images
		------------------------------------------------------------ */

		/*---------------------------------------------------------
			XVIII. Create Gallery post type
		------------------------------------------------------------ */

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
					"name" => __( 'Custom Logo Image <span>insert your custom logo image in the header</span>', 'photography' ),
					"type" => "subhead"),

				array(
					"name" => __( 'Enable custom logo image', 'photography' ),
					"id" => $this->shortname."_logo",
					"desc" => __( 'Check to use a custom logo in the header.', 'photography' ),
					"std" => "false",
					"pro" => 'true',
					"type" => "checkbox"),

				array(
					"name" => __( 'Logo URL', 'photography' ),
					"id" => $this->shortname."_logo_img",
					"desc" => sprintf( __( 'Upload an image or enter an URL for your image.', 'photography' ), '<code>' . STYLESHEETPATH . '/images/</code>' ),
					"std" => '',
					"pro" => 'true',
					"upload" => true,
					"class" => "logo-image-input",
					"type" => "upload"),

				array(
					"name" => __( 'Logo image <code>&lt;alt&gt;</code> tag', 'photography' ),
					"id" => $this->shortname."_logo_img_alt",
					"desc" => __( 'Specify the <code>&lt;alt&gt;</code> tag for your logo image.', 'photography' ),
					"std" => '',
					"pro" => 'true',
					"type" => "text"),

				array(
					"name" => __( 'Display tagline', 'photography' ),
					"id" => $this->shortname."_tagline",
					"desc" => __( 'Check to show your tagline below your logo.', 'photography' ),
					"std" => '',
					"pro" => 'true',
					"type" => "checkbox"),

				array(
					"name" => __( 'Flickr <span>enable Flickr gallery support</span>', 'photography' ),
					"type" => "subhead"),

				array(
					"name" => __( 'Flickr username', 'photography' ),
					"id" => $this->shortname."_flickr_user_name",
					"desc" => __( 'Specify your Flickr username (found in your URL): http://flickr.com/photos/<strong>username</strong>', 'photography' ),
					"std" => '',
					"pro" => 'true',
					"type" => "text"),

				array(
					"name" => __( 'Flickr API key', 'photography' ),
					"id" => $this->shortname."_flickr_api_key",
					"desc" => __( 'Specify your Flickr API Key.', 'photography' ),
					"std" => '',
					"pro" => 'true',
					"type" => "text"),

				array(
					"name" => __( 'Flickr API secret', 'photography' ),
					"id" => $this->shortname."_flickr_api_secret",
					"desc" => __( 'Specify your Flickr API Secret.', 'photography' ),
					"std" => '',
					"pro" => 'true',
					"type" => "text"),

				array(
					"name" => __( 'Color Scheme <span>choose your color scheme</span>', 'photography' ),
					"type" => "subhead"),

				array(
					"name" => __( 'Color scheme', 'photography' ),
					"desc" => __( 'Choose your color scheme.', 'photography' ),
					"id" => $this->shortname."_color",
					"std" => "default",
					"pro" => 'true',
					"type" => "select",
					"options" => array(
						"default" => __( 'Dark (default)', 'photography' ),
						"light" => __( 'Light', 'photography' ))),

				array(
					"name" => __( 'Subscribe Links <span>control the subscribe links</span>', 'photography' ),
					"type" => "subhead"),

				array(
					"name" => __( 'Enable Twitter', 'photography' ),
					"id" => $this->shortname."_twitter_toggle",
					"desc" => __( 'Hip to Twitter? Check this box. Please set your Twitter username in the Twitter menu.', 'photography' ),
					"std" => '',
					"pro" => 'true',
					"type" => "checkbox"),

				array(
					"name" => __( 'Enable Facebook', 'photography' ),
					"id" => $this->shortname."_facebook_toggle",
					"desc" => __( 'Check this box to show a link to your Facebook page.', 'photography' ),
					"std" => '',
					"pro" => 'true',
					"type" => "checkbox"),

				array(
					"name" => __( 'Enable Flickr', 'photography' ),
					"id" => $this->shortname."_flickr_toggle",
					"desc" => __( 'Check this box to show a link to Flickr.', 'photography' ),
					"std" => '',
					"pro" => 'true',
					"type" => "checkbox"),

				array(
					"name" => __( 'Disable all', 'photography' ),
					"id" => $this->shortname."_follow_disable",
					"desc" => __( 'Check this box to hide all follow icons (including RSS). This option overrides any other settings.', 'photography' ),
					"std" => '',
					"pro" => 'true',
					"type" => "checkbox"),

				array(
					"name" => __( 'Twitter name', 'photography' ),
					"id" => $this->shortname."_twitter",
					"desc" => __( 'Enter your Twitter name.', 'photography' ),
					"std" => '',
					"pro" => 'true',
					"type" => "text"),

				array(
					"name" => __( 'Facebook link', 'photography' ),
					"id" => $this->shortname."_facebook",
					"desc" => __( 'Enter your Facebook link.', 'photography' ),
					"std" => '',
					"pro" => 'true',
					"type" => "text"),

				array(
					"name" => __( 'Flickr link', 'photography' ),
					"id" => $this->shortname."_flickr",
					"desc" => __( 'Enter your Flickr link.', 'photography' ),
					"std" => '',
					"pro" => 'true',
					"type" => "text"),

				array(
					"name" => __( 'Footer <span>customize your footer</span>', 'photography' ),
					"type" => "subhead"),

				array(
					"name" => __( 'Copyright notice', 'photography' ),
					"id" => $this->shortname."_copyright_name",
					"desc" => __( 'Your name or the name of your business.', 'photography' ),
					"std" => __( 'Your Name Here', 'photography' ),
					"type" => "text"),

				array(
					"name" => __( 'Stats code', 'photography' ),
					"id" => $this->shortname."_stats_code",
					"desc" => __( 'If you use Google Analytics or need any other tracking script in your footer just copy and paste it here. The script will be inserted before the closing <code>&#60;/body&#62;</code> tag.', 'photography' ),
					"std" => '',
					"type" => "textarea",
					"options" => array(
						"rows" => "5",
						"cols" => "40") ),

			);
		}

		/*---------------------------------------------------------
			9. Theme option return functions
		------------------------------------------------------------ */

		/*---------------------------------------------------------
			I. Logo functions
		------------------------------------------------------------ */

		/*---------------------------------------------------------
			II. Flickr functions
		------------------------------------------------------------ */

		/*---------------------------------------------------------
			III. Color functions
		------------------------------------------------------------ */

		/*---------------------------------------------------------
			IV. Subscribe functions
		------------------------------------------------------------ */

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
						wp_list_pages( 'title_li=&number=4' );
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
			}
		}
	}
}

/* SETTING EVERYTHING IN MOTION */
function load_photography_pro_theme() {
	$GLOBALS['photography'] = new Photography;
}

add_action( 'after_setup_theme', 'load_photography_pro_theme' );