<?php get_header(); ?>
	<div id="content">
		<?php if (have_posts()) : ?>
			<?php while (have_posts()) : the_post(); ?>
				<div id="post-<?php the_ID(); ?>" <?php post_class( 'clear' ); ?>>
					<?php if ( has_post_thumbnail() ) { ?>
						<a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_post_thumbnail( 'index-feature', array( 'class' => 'index-feature' ) ); ?></a>
					<?php } ?>
					<div class="entry index clear">
						<h2 class="title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php esc_attr( sprintf( __( 'Permanent Link to %s', 'photography' ), the_title_attribute( 'echo=false' ) ) ); ?>"><?php the_title(); ?></a></h2>
						<?php the_content(__('Read more...', 'photography')); ?>
						<?php edit_post_link( __( 'Edit this', 'photography' ), '<p>', '</p>' ); ?>
						<?php if ( have_comments() || comments_open() ) { ?>
							<div class="comments"><?php comments_popup_link( __( 'Leave a comment', 'photography' ),  __( '1 Comment', 'photography' ), __('% Comments', 'photography' )); ?></div>
						<?php } ?>
						<div class="date"><?php the_time(__( 'j M Y' )); ?></div>
					</div><!--end entry-->
				</div><!--end post-->
			<?php endwhile; /* rewind or continue if all posts have been fetched */ ?>
			<div class="navigation index">
				<div class="alignleft"><?php next_posts_link(__ ( '&laquo; Older Entries', 'photography' )); ?></div>
				<div class="alignright"><?php previous_posts_link(__ ( 'Newer Entries &raquo;', 'photography' )); ?></div>
			</div><!--end navigation-->
		<?php endif; ?>
	</div><!--end content-->
<?php get_sidebar(); ?>
<?php get_footer(); ?>