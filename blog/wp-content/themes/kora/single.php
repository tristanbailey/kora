<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

		<div class="row">
			<div class="main_content col span_4" role="main">

			<?php
			/* Run the loop to output the post.
			 * If you want to overload this in a child theme then include a file
			 * called loop-single.php and that will be used instead.
			 */
			get_template_part( 'loop', 'single' );
			?>

			</div><!-- #main_content -->
			<div class="related_content col span_2">
				<?php get_sidebar(); ?>
			</div>
		</div><!-- #row -->
<?php get_footer(); ?>
