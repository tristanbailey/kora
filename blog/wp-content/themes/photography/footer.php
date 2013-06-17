<?php global $photography; ?>
<div id="copyright">
	<p>
		<?php printf(
			__( 'Copyright &copy; %1$s %2$s. All rights reserved.', 'photography' ),
			date( 'Y' ),
			$photography->copyrightName()
		); ?>
	</p>
	<p>
		<?php
			printf(
				__( '<a href="%1$s">WordPress Photography Theme</a> by <a href="%2$s">The Theme Foundry</a>', 'photography' ),
				'http://thethemefoundry.com/photography/',
				'http://thethemefoundry.com/'
			);
		?>
	</p>
</div><!--end copyright-->
</div><!--end wrapper-->
<?php wp_footer(); ?>
</body>
</html>