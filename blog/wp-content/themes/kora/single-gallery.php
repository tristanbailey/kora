<?php get_header(); ?>
		<div class="row">
			<div class="main_content col span_4" role="main">

<?php while (have_posts()) : the_post(); ?>
	<?php if ( ! post_password_required() ) : ?>
		<h1 class="title"><?php the_title(); ?></h1>
		<div id="gallery-single">
			<?php
				if ( get_post_meta( get_the_ID(), 'enable_flickr', true ) ) {
					if ( $images = $photography->get_flickr_images_static( get_the_ID() ) ) { ?>
						<div class="static-image-container">
							<?php foreach ( (array) $images['photoset']['photo'] as $image) { ?>
									<img src="<?php echo esc_attr( $flickr->buildPhotoURL( $image, "small" ) ); ?>" alt="<?php echo esc_attr( $image['title'] ); ?>" />
							<?php } ?>
						</div>
						<?php
					}

				} else {
					if ( $images = $photography->get_gallery_images( get_the_ID(), true )) { ?>
						<div class="static-image-container">
							<?php foreach( $images as $image ) {
								echo wp_get_attachment_image( $image->ID, 'medium' );
							} ?>
						</div>
						<?php
					}
				}
			?>
		</div>
	<?php endif; ?>
<?php endwhile; ?>

			</div><!-- #main_content -->
			<div class="related_content col span_2">
				<?php get_sidebar(); ?>
			</div>
		</div><!-- #row -->

<?php get_footer(); ?>