<?php global $photography; ?>
<div id="sidebar">
	<?php if ($photography->followDisable() != 'true' ) { ?>
		<?php get_template_part( 'subscribe' ); ?>
	<?php } ?>
		<ul>
		<?php if ( ! dynamic_sidebar( 'sidebar' ) ) : ?>
			<li class="widget widget_recent_entries">
				<h2 class="widgettitle"><?php _e( 'Recent Articles', 'photography' ); ?></h2>
				<ul>
					<?php $side_posts = get_posts( 'numberposts=10' );
						foreach($side_posts as $post) : ?>
						<li><a href= "<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</li>
			<li class="widget widget_meta">
				<h2 class="widgettitle"><?php _e( 'Archives', 'photography' ); ?></h2>
				<ul>
					<?php wp_get_archives(); ?>
				</ul>
			</li>
		<?php endif; ?>
		</ul>
</div><!--end sidebar-->