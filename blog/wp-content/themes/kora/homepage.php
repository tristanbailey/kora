<?php
/*
Template Name: Homepage
*/
?>
<?php get_header(); ?>
    <div class="row">
      <div class="main_content col span_6" role="main" style="padding:0px ! important; margin:0px ! important; background: none;">

  <?php while (have_posts()) : the_post(); ?>
    <div id="home-photo">
      <div id="slideshow">
        <?php
        if ( $images = $photography->get_gallery_images( $post->ID, true )) {
          foreach( $images as $image ) {
            echo wp_get_attachment_image( $image->ID, 'gallery-full' );
          }
        }
        ?>
      </div>
      <div class="frame fr-home"></div>
    </div>
    <?php #edit_post_link( __( 'Edit this page', 'photography' ), '<p>', '</p>' ); ?>
  <?php endwhile; /* rewind or continue if all posts have been fetched */ ?>
        </div><!-- #main_content -->
    </div><!-- #row -->

<?php get_footer(); ?>