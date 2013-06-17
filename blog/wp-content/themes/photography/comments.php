<?php global $photography; ?>
<?php if ( post_password_required() ) : ?>
	<p class="nocomments"><?php _e( 'This post is password protected. Enter the password to view comments.', 'photography' ); ?></p>
	<?php 
	return; 
endif; ?>
<div id="comments">
<?php if ( have_comments() ) : ?>
	<div class="comment-number clear">
		<span><?php comments_number( __( 'No Comments Yet', 'photography' ), __( '1 Comment', 'photography' ), __( '% Comments', 'photography' )); ?></span>
		<?php if ( comments_open() ) : ?>
			<a id="leavecomment" href="#respond" title="<?php esc_attr_e( 'Post a comment', 'photography' ); ?>"> <?php _e( 'Post a comment', 'photography' ); ?></a>
		<?php endif; ?>
	</div><!--end comment-number-->
	<ol class="commentlist">
		<?php wp_list_comments( 'type=comment&callback=photography_custom_comment' ); ?>
	</ol>

	<div class="navigation">
		<div class="alignleft"><?php next_comments_link(__( '&laquo; Older Comments', 'photography' )); ?></div>
		<div class="alignright"><?php previous_comments_link(__( 'Newer Comments &raquo;', 'photography' )); ?></div>
	</div>
	<?php if ( ! empty($comments_by_type['pings']) ) : ?>
		<h3 class="pinghead"><?php _e( 'Trackbacks &amp; Pingbacks', 'photography' ); ?></h3>
		<ol class="pinglist">
			<?php wp_list_comments( 'type=pings&callback=photography_list_pings' ); ?>
		</ol>

		<div class="navigation">
			<div class="alignleft"><?php next_comments_link(__( '&laquo; Older Pingbacks', 'photography' )); ?></div>
			<div class="alignright"><?php previous_comments_link(__( 'Newer Pingbacks &raquo;', 'photography' )); ?></div>
		</div>
	<?php endif; ?>
	<?php if ( ! comments_open() ) : ?>
		<p class="note"><?php _e( 'Comments are closed.', 'photography' ); ?></p>
	<?php endif; ?>
<?php else : // this is displayed if there are no comments so far ?>
	<?php if ( ! comments_open() ) : ?>
		<?php if ( ! is_page()) : ?>
			<p class="note"><?php _e( 'Comments are closed.', 'photography' ); ?></p>
		<?php endif; ?>
	<?php endif; ?>
<?php endif; ?>
</div><!--end comments-->

<?php

$req = get_option( 'require_name_email' );
$field = '<fieldset><label for="%1$s" class="comment-field">%2$s</label><input class="text-input" type="text" name="%1$s" id="%1$s" value="%3$s" size="22" tabindex="%4$d" />%5$s</fieldset>';
comment_form( array(
	'comment_field' => '<fieldset><label for="comment" class="comment-field"><small>' . _x( 'Comment', 'noun', 'photography' ) . '</small></label><textarea id="comment" name="comment" cols="50" rows="10" aria-required="true" tabindex="4"></textarea></fieldset>',
	'comment_notes_before' => '',
	'comment_notes_after' => sprintf(
		'<p class="guidelines">%3$s</p>' . "\n" . '<p class="comments-rss"><a href="%1$s">%2$s</a></p>',
		esc_attr( get_post_comments_feed_link() ),
		__( 'Comments Feed', 'photography' ),
		__( '<strong>Note:</strong> HTML is allowed. Your email address will <strong>never</strong> be published.', 'photography' )
	),
	'fields' => array(
		'author' => sprintf(
			$field,
			'author',
			__( 'Name', 'photography' ),
			esc_attr( $comment_author ),
			1,
			(
				$req ?
				'<span>' . __( 'required', 'photography' ) . '</span>' :
				''
			)
		),
		'email' => sprintf(
			$field,
			'email',
			__( 'Email', 'photography' ),
			esc_attr( $comment_author_email ),
			2,
			(
				$req ?
				'<span>' . __( 'required', 'photography' ) . '</span>' :
				''
			)
		),
		'url' => sprintf(
			$field,
			'url',
			__( 'Website', 'photography' ),
			esc_attr( $comment_author_url ),
			3,
			''
		),
	),
	'label_submit' => __( 'Submit Comment', 'photography' ),
	'logged_in_as' => '<p class="com-logged-in">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out &raquo;</a>', 'photography' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink() ) ) ) . '</p>',
	'title_reply' => __( 'Leave a comment', 'photography' ),
	'title_reply_to' => __( 'Leave a comment to %s', 'photography' ),
) );

?>