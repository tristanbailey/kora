<?php get_header(); ?>
	<div id="content">
		<?php if (have_posts()) : ?>
			<?php while (have_posts()) : the_post(); ?>
				<div class="entry page clear">
					<h1 class="title"><?php the_title(); ?></h1>
					<?php the_content(); ?>
					<?php edit_post_link( __( 'Edit this', 'photography' ), '<p style="clear:both">', '</p>' ); ?>
				</div><!--end entry-->
			<?php endwhile; /* rewind or continue if all posts have been fetched */ ?>
			<?php comments_template( '', true); ?>
		<?php endif; ?>
	</div><!--end content-->
<?php get_sidebar(); ?>
<?php get_footer(); ?>