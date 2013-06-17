<?php global $photography; ?>
<!DOCTYPE html>
<html <?php language_attributes( 'html' ) ?>>
<head>
	<?php if ( is_front_page() ) : ?>
		<title><?php bloginfo( 'name' ); ?></title>
	<?php elseif ( is_404() ) : ?>
		<title><?php _e( 'Page Not Found |', 'photography' ); ?> | <?php bloginfo( 'name' ); ?></title>
	<?php elseif ( is_search() ) : ?>
		<title><?php printf(__ ("Search results for '%s'", "photography"), get_search_query()); ?> | <?php bloginfo( 'name' ); ?></title>
	<?php else : ?>
		<title><?php wp_title($sep = '' ); ?> | <?php bloginfo( 'name' );?></title>
	<?php endif; ?>

	<!-- Basic Meta Data -->
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="copyright" content="<?php
		esc_attr( sprintf(
			__( 'Design is copyright %1$s The Theme Foundry', 'photography' ),
			date( 'Y' )
		) );
	?>" />

	<!-- Favicon -->
	<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/images/favicon.ico" />

	<!-- WordPress -->
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_head(); ?>
</head>
<body <?php body_class($photography->colorScheme()); ?>>
	<div class="skip-content"><a href="#content"><?php _e( 'Skip to content', 'photography' ); ?></a></div>
	<div class="wrapper clear">
		<div id="header" class="clear">
			<?php if ($photography->logoState() == 'true' ) : ?>
				<div class="logo logo-img">
					<?php $alt = ( $photography->logoAlt() !== '' ) ? $photography->logoAlt() : get_bloginfo( 'name' ); ?>
					<a href="<?php echo home_url( '/' ); ?>"><img src="<?php echo esc_attr( $photography->logoName() ); ?>" alt="<?php echo esc_attr( $alt ); ?>"/></a>
					<?php if ($photography->logoTagline() == 'true' ) : ?>
						<div id="description">
							<?php bloginfo( 'description' ); ?>
						</div><!--end description-->
					<?php endif; ?>
				</div><!--end logo-->
			<?php else : ?>
				<div class="logo">
					<?php
					$logo_markup = is_home() ? '<h1 id="title"><a href="%1$s">%2$s</a></h1>' : '<div id="title"><a href="%1$s">%2$s</a></div>';
					printf(
						$logo_markup,
						home_url( '/' ),
						get_bloginfo( 'name' )
					);
					?>
					<div id="description">
						<?php bloginfo( 'description' ); ?>
					</div><!--end description-->
				</div><!--end logo-->
			<?php endif; ?>
			<?php
			wp_nav_menu(
				array(
					'theme_location'  => 'nav-1',
					'container_id'    => 'navigation',
					'menu_class'      => 'nav',
					'fallback_cb'     => array(&$photography, 'main_menu_fallback')
				)
			);
			?>
		</div><!--end header-->