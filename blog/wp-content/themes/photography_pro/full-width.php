<?php
/*
Template Name: Full width (no sidebar)
*/
?>
<?php get_header(); ?>
<div id="content" class="full-width">
	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
			<?php the_post_thumbnail( 'page', array( 'class' => 'page-image' ) ); ?>
			<div class="entry page clear">
				<h1 class="title"><?php the_title(); ?></h1>
				<?php the_content(); ?>
				<?php edit_post_link( __( 'Edit this page', 'photography' ), '<p>', '</p>' ); ?>
			</div><!--end entry-->
		<?php endwhile; ?>
		<?php comments_template( '', true); ?>
	<?php endif; ?>
</div><!--end content-->
<?php get_footer(); ?>