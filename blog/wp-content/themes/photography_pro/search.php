<?php get_header(); ?>
<div id="content">
	<h4 class="arch-title"><?php printf( __("Search results for '%s'", "photography"), get_search_query() ); ?></h4>
		<?php if (have_posts()) : ?>
			<?php while (have_posts()) : the_post(); ?>
				<div id="post-<?php the_ID(); ?>" <?php post_class( 'clear' ); ?>>
						<div class="entry index clear">
						<h2 class="title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
						<?php the_content(__('Read more...', 'photography')); ?>
						<div class="comments"><?php comments_popup_link( __( 'Leave a comment', 'photography' ),  __( '1 Comment', 'photography' ), _n ( '% Comment', '% Comments', get_comments_number (),'photography' )); ?></div>
						<div class="date"><?php the_time(__( 'j M Y' )); ?></div>
						<!--<?php edit_post_link(__( 'Edit', 'photography' )); ?>-->
					</div><!--end entry-->
				</div><!--end post-->
			<?php endwhile; /* rewind or continue if all posts have been fetched */ ?>
			<div class="navigation index">
				<div class="alignleft"><?php next_posts_link(__ ( '&laquo; Older Entries', 'photography' )); ?></div>
				<div class="alignright"><?php previous_posts_link(__ ( 'Newer Entries &raquo;', 'photography' )); ?></div>
			</div><!--end navigation-->
		<?php else : ?>
			<div class="entry single">
				<p><?php printf( __( 'Sorry your search for "%s" did not turn up any results. Please try again.', 'photography' ), get_search_query()); ?></p>
				<?php get_search_form(); ?>
			</div>
		<?php endif; ?>
</div><!--end content-->
<?php get_sidebar(); ?>
<?php get_footer(); ?>