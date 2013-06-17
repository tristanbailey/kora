<form method="get" id="search-form" action="<?php echo home_url('/'); ?>/">
	<div>
		<label for="s"><?php _e( 'Search', 'photography' ); ?></label>
		<input type="text" name="s" id="s" class="search"/>
		<input type="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'photography' ); ?>" />
	</div>
</form>