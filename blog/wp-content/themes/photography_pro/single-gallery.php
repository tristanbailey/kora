<?php get_header(); ?>
<?php while (have_posts()) : the_post(); ?>
	<?php if ( ! post_password_required() ) : ?>
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
	<?php if ( post_password_required() || (!post_password_required() && !get_post_meta( get_the_ID(), 'disable_gallery_meta', true )) ) : ?>
		<div id="post-<?php the_ID(); ?>" class="post gallery clear g-single <?php if ( post_password_required() ) echo 'g-protect'; ?>">
			<div class="entry">
				<h1 class="title"><?php the_title(); ?></h1>
				<?php the_content(); ?>
				<?php edit_post_link( __( 'Edit this', 'photography' ), '<p>', '</p>' ); ?>
			</div><!--end entry-->
		</div><!--end post-->
	<?php endif; ?>
<?php endwhile; ?>
<?php get_footer(); ?>