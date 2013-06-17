<?php
/*
Template Name: Gallery
*/
?>
<?php get_header(); ?>
<div id="gallery" class="clear">
	<?php
		the_post();

		// process the content so the post_gallery filter is applied (and we can pull [gallery] shortcodes)
		apply_filters( 'the_content', get_the_content() );

		$queryargs = array(
			'post_type' => 'gallery',
			'orderby' => 'menu_order',
			'nopaging' => true,
			'order' => 'ASC',
			'post_status' => array( 'publish' )
		);

		// limit to user-requested posts, if they were set via [gallery] shortcode
		if ( ! empty( $photography->gallery_ids ) ) {
			$queryargs['post__in'] = $photography->gallery_ids;
		}

		// Remove password-protected posts from this query
		add_filter( 'posts_where', array( &$photography, 'remove_password_posts' ) );
		query_posts( $queryargs );
		remove_filter( 'posts_where', array( &$photography, 'remove_password_posts' ) );
	?>
	<?php if ( have_posts() ) : ?>
	<ul>
		<?php $count = 0; ?>
		<?php while (have_posts()) : the_post();  ?>
			<?php if (post_password_required()) continue;  $count++; ?>
				<?php
					$gallery_classes = array( 'g-thumb' );
					$has_captions = in_array( $photography->gallery_captions(), array( 'basic', 'fancy' ) );

					if ( $has_captions ) {
						$gallery_classes[] = esc_attr( $photography->gallery_captions() );
					}
					if ( $count % 3 == 1 ) {
						$gallery_classes[] = 'g-first';
					}
					if ( $count % 3 == 0 ) {
						$gallery_classes[] = 'g-last';
					}
				?>
				<li id="g-thumb-<?php echo $count; ?>" class="<?php echo esc_attr( implode( ' ', $gallery_classes ) ); ?>">
					<a href="<?php the_permalink(); ?>">
						<?php
							if ( ( get_post_meta( $post->ID, 'enable_flickr', true ) ) && ( !has_post_thumbnail() ) ) { ?>
								<img height="195" width="280" src="<?php echo $photography->get_flickr_set_primary( $post->ID ); ?>">
							<?php } else {
								the_post_thumbnail( 'gallery-thumb', array( 'class' => 'gallery-thumb', 'title' => NULL ));
							}
						?>
					</a>
					<?php if ( $has_captions ) : ?>
						<h5><?php the_title(); ?></h5>
					<?php endif; ?>
				</li>
		<?php endwhile; ?>
	</ul>
	<?php endif; ?>
	<?php wp_reset_query(); ?>
</div>
<?php edit_post_link( __( 'Edit this page', 'photography' ), '<p>', '</p>' ); ?>
<?php get_footer(); ?>