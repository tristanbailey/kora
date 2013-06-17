<?php global $photography; ?>
<div id="copyright">
	<p>
		<?php printf(
			__( 'Copyright &copy; %1$s %2$s. All rights reserved.', 'photography' ),
			date( 'Y' ),
			$photography->copyrightName()
		); ?>
	</p>
	<p>
		<?php
			printf(
				__( '<a href="%1$s">WordPress Photography Theme</a> by <a href="%2$s">The Theme Foundry</a>', 'photography' ),
				'http://thethemefoundry.com/photography/',
				'http://thethemefoundry.com/'
			);
		?>
	</p>
</div><!--end copyright-->
</div><!--end wrapper-->
<?php if ( is_single() && get_post_type() == 'gallery' ) :
global $wp_query;
	$post_id = $wp_query->get_queried_object_id();
	?>
	<script>
		var photos = [
			<?php
				$photos = get_post_meta( $post_id, 'enable_flickr', true ) ? $photography->get_flickr_images( $post_id ) : $photography->get_gallery_images( $post_id, false );
				echo implode(',', $photos);
			?>];
		Galleria.loadTheme('<?php echo get_template_directory_uri(); ?>/javascripts/galleria-theme/galleria.classic.js');
		jQuery('#gallery-single').galleria({
			data_source: photos,
			transition: 'slide',
			image_crop: <?php echo get_post_meta( $post_id, 'image_crop', true ) ? 'true' : 'false';?>,
			autoplay: <?php echo get_post_meta( $post_id, 'auto_play', true ) ? 'true' : 'false';?>,
			<?php $gallery_height = intval( get_post_meta( $post_id, 'image_height', true ) ); ?>
			height: <?php echo $gallery_height > 0 ? esc_js( $gallery_height ) : '540'; ?> + 80
		});

		// KEYBOARD SHORTCUTS/NAVIGATION
		jQuery(document).keydown(function(e) {
			if ( ! e.ctrlKey && ! e.altKey && ! e.shiftKey && ! e.metaKey) {
				if (e.which == 37) {  // Left arrow key code
					jQuery('.galleria-image-nav-left').click();
				} else if (e.which == 39) {  // Right arrow key code
					jQuery('.galleria-image-nav-right').click();
				}
			}
		});
	</script>
<?php endif; // single gallery ?>
<?php wp_footer(); ?>
</body>
</html>