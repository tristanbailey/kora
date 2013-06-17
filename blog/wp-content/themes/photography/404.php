<?php get_header(); ?>
	<div class="post single file-not-found">
		<h1 class="title"><?php _e( '404: Page Not Found', 'photography' ); ?></h1>
		<div class="entry">
			<p><?php _e( 'The URL you typed no longer exists. It might have been moved or deleted, or perhaps you mistyped it. We suggest searching the site:', 'photography' ); ?></p>
			<?php get_search_form(); ?>
		</div><!--end entry-->
	</div><!--end post-->
<?php get_footer(); ?>