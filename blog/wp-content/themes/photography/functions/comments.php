<?php

// Template for pingbacks/trackbacks
function photography_list_pings( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	?>
	<li id="comment-<?php comment_ID(); ?>"><?php comment_author_link(); ?>
	<?php
}

// Dark (default) color scheme comments require a special gravatar
function photography_custom_comment ( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>" >
		<div class="comment-box clear">
			<div class="c-grav">
				<?php $com_url = get_template_directory_uri() . '/images/gravatar.png';
					echo get_avatar( $comment, 36, $com_url );
				?>
			</div>
			<div class="c-body">
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<p><?php _e( '<em><strong>Please Note:</strong> Your comment is awaiting moderation.</em>', 'photography' ); ?></p>
				<?php endif; ?>
				<div class="c-date"><?php comment_date( 'm/j/Y' ); ?></div>
				<div class="c-author"><?php comment_author_link(); ?></div>
				<?php comment_text(); ?>
				<?php comment_type( ( '' ), __( 'Trackback', 'photography' ), __( 'Pingback', 'photography' ) ); ?>
				<?php echo comment_reply_link( array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ); ?>
				<?php edit_comment_link( 'edit','<p>','</p>' ); ?>
			</div><!--end c-body-->
		</div><!--end comment-box-->
<?php
}