<form role="search" method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div>
		<label class="screen-reader-text" for="s">Search:</label>
		<input type="text" value="<?php echo get_search_query(); ?>" placeholder="Tls archive, blogs and website" name="s" id="s" />
	</div>
</form>