<?php get_header(); ?>
	<?php if (have_posts()) : ?>
		<div id="content">
			<?php while (have_posts()) : the_post(); ?>
				<div id="post-<?php the_ID(); ?>" <?php post_class( 'clear single' ); ?>>
					<?php the_post_thumbnail( 'index-feature', array( 'class' => 'index-feature' ) ); ?>
					<div class="entry single clear">
						<div class="post-header clear">
							<h1 class="title"><?php the_title(); ?></h1>
							<?php if ( have_comments() || comments_open() ) { ?>
								<div class="comments"><?php comments_popup_link( __( 'Leave a comment', 'photography' ),  __( '1 Comment', 'photography' ), __( '% Comments', 'photography' )); ?></div>
							<?php } ?>
						</div>
						<?php the_content(); ?>
						<?php edit_post_link( __( 'Edit this', 'photography' ), '<p style="clear:both">', '</p>' ); ?>
						<?php wp_link_pages(); ?>
					</div><!--end entry-->
					<div class="meta clear">
						<div class="cats"><?php _e( 'From', 'photography' ); ?> <?php the_category( ', ' ); ?></div>
						<div class="tags"><?php the_tags(__( 'Tags: ', ', ', '', 'photography' )); ?></div>
					</div><!--end meta-->
					<div class="author"><?php printf( __( 'Posted by %1$s on %2$s', 'photography' ), get_the_author(), get_the_time( get_option( 'date_format' ) ) ); ?></div>
				</div><!--end post-->
			<?php endwhile; ?>
			<?php comments_template( '', true); ?>
		</div><!--end content-->
	<?php endif; ?>
<?php get_sidebar(); ?>
<?php get_footer(); ?>