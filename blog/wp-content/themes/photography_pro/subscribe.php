<?php global $photography; ?>
<div class="subscribe clear">
	<ul>
		<?php if ($photography->flickrToggle() == 'true' ) { ?>
			<li>
				<a href="<?php echo esc_attr( $photography->flickr() ); ?>"><img  title="<?php esc_attr_e( 'Flickr', 'photography' ); ?>" src="<?php
					 echo esc_attr( sprintf(
						'%1$s/images/%2$s%3$s',
						get_template_directory_uri(),
						( 'light' == $photography->colorScheme() ? 'light-' : '' ),
						'flw-flickr.png'
					) );
				?>" alt="<?php _e( 'Flickr', 'photography' ); ?>"/></a>
			</li>
		<?php } ?>
		<?php if ( $photography->googlePlusToggle() == 'true' ) { ?>
			<li>
				<a href="<?php echo esc_url( $photography->googlePlus() ); ?>"><img  title="<?php esc_attr_e( 'Google+', 'photography' ); ?>" src="<?php
					 echo esc_url( sprintf(
						'%1$s/images/%2$s',
						get_template_directory_uri(),
						'flw-google-plus.png'
					) );
				?>" alt="<?php _e( 'Google+', 'photography' ); ?>"/></a>
			</li>
		<?php } ?>
		<?php if ($photography->facebookToggle() == 'true' ) { ?>
			<li>
				<a href="<?php echo esc_attr( $photography->facebook() ); ?>"><img  title="<?php esc_attr_e( 'Facebook', 'photography' ); ?>" src="<?php
					 echo esc_attr( sprintf(
						'%1$s/images/%2$s%3$s',
						get_template_directory_uri(),
						( 'light' == $photography->colorScheme() ? 'light-' : '' ),
						'flw-facebook.png'
					) );
				?>" alt="<?php _e( 'Facebook', 'photography' ); ?>"/></a>
			</li>
		<?php } ?>
		<?php if ( $photography->twitterToggle() == 'true' ) { ?>
			<li>
				<a href="http://twitter.com/<?php echo esc_attr( $photography->twitter() ); ?>"><img  title="<?php esc_attr_e( 'Twitter', 'photography' ); ?>" src="<?php
					 echo esc_attr( sprintf(
						'%1$s/images/%2$s%3$s',
						get_template_directory_uri(),
						( 'light' == $photography->colorScheme() ? 'light-' : '' ),
						'flw-twitter.png'
					) );
				?>" alt="<?php _e( 'Twitter', 'photography' ); ?>"/></a>
			</li>
		<?php } ?>
		<li>
			<a href="<?php bloginfo( 'rss2_url' ); ?>"><img  title="<?php esc_attr_e( 'RSS', 'photography' ); ?>" src="<?php
				 echo esc_attr( sprintf(
					'%1$s/images/%2$s%3$s',
					get_template_directory_uri(),
					( 'light' == $photography->colorScheme() ? 'light-' : '' ),
					'flw-rss.png'
				) );
			?>" alt="<?php _e( 'RSS Feed', 'photography' ); ?>"/></a>
		</li>
	</ul>
</div>