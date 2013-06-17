<?php get_header(); ?>
<div id="content">
	<?php if (have_posts()) : ?>
		<?php the_post(); ?>
		<?php /* If this is a category archive */ if (is_category()) { ?>
			<h4 class="arch-title"><?php printf(__ ( 'Posts from the &#8216;%s&#8217; Category', 'photography' ), single_cat_title('', false)); ?></h4>
		<?php /* If this is a tag archive */ } elseif ( is_tag() ) { ?>
			<h4 class="arch-title"><?php printf(__ ( 'Posts tagged &#8216;%s&#8217;', 'photography' ), single_tag_title('', false)); ?></h4>
		<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
			<h4 class="arch-title"><?php printf( __( 'Archive for %s', 'photography' ), get_the_time(  'F jS, Y', 'photography' ) ); ?></h4>
		<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
			<h4 class="arch-title"><?php printf( __( 'Archive for %s', 'photography' ), get_the_time(  'F Y', 'photography' ) ); ?></h4>
		<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
			<h4 class="arch-title"><?php printf( __( 'Archive for %s', 'photography' ), get_the_time(  'Y', 'photography' ) ); ?></h4>
		<?php /* If this is an author archive */ } elseif (is_author()) { ?>
			<h4 class="arch-title"><?php printf(__ ( 'Posts by %s', 'photography' ), get_the_author() ); ?></h4>
		<?php /* If this is a paged archive */ } elseif ( is_paged() ) { ?>
			<h4 class="arch-title"><?php _e( 'Blog Archives', 'photography' ); ?></h4>
		<?php } ?>
		<?php rewind_posts(); ?>
		<?php while (have_posts()) : the_post(); ?>
			<div id="post-<?php the_ID(); ?>" <?php post_class( 'clear' ); ?>>
				<div class="entry index clear">
	 				<h2 class="title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php printf( __('Permanent Link to %s', 'photography' ), the_title_attribute('echo=0') ); ?>"><?php the_title(); ?></a></h2>
					<?php the_content(__('Read more...', 'photography')); ?>
					<?php edit_post_link( __( 'Edit this', 'photography' ), '<p>', '</p>' ); ?>
					<div class="comments"><?php comments_popup_link( __( 'Leave a comment', 'photography' ),  __( '1 Comment', 'photography' ), _n( '% Comment', '% Comments', get_comments_number(), 'photography' )); ?></div>
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