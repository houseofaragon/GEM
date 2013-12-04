<?php global $ci, $ci_defaults, $load_defaults, $content_width; ?>
<?php if ($load_defaults===TRUE): ?>
<?php
	add_filter('ci_panel_tabs', 'ci_add_tab_artists_options', 50);
	if( !function_exists('ci_add_tab_artists_options') ):
		function ci_add_tab_artists_options($tabs) 
		{ 
			$tabs[sanitize_key(basename(__FILE__, '.php'))] = __('Artists Options', 'ci_theme'); 
			return $tabs; 
		}
	endif;
	
	// Default values for options go here.
	// $ci_defaults['option_name'] = 'default_value';
	// or
	// load_panel_snippet( 'snippet_name' );

	$ci_defaults['artists_per_page'] = '16';
	$ci_defaults['artists_isotope'] = '';
	
	// Intercepts the request and injects the appropriate posts_per_page parameter according to the request.
	add_filter( 'pre_get_posts', 'ci_artists_taxonomy_paging_request' );
	if( !function_exists('ci_artists_taxonomy_paging_request') ):
	function ci_artists_taxonomy_paging_request( $query )
	{
		//We don't want to mess other post types or with the admin panel.
		if( !is_tax('artist-category') or is_admin() ) return;
	
		// Don't mess with the posts if the query is explicit.
		if (!isset($query->query_vars['posts_per_page']))
		{
			$num_of_pages = intval(ci_setting('artists_per_page'));
			// Assign a number only if a number was found, otherwise, disable pagination.
			if ($num_of_pages > 0)
				$query->set( 'posts_per_page', $num_of_pages );
			else
				$query->set( 'posts_per_page', -1 );
		}

		return $query;
	}
	endif;

?>
<?php else: ?>
	
	<fieldset class="set">
		<p class="guide"><?php _e('Would you like to enable category filters? (Isotope effect)', 'ci_theme'); ?></p>
		<?php ci_panel_checkbox('artists_isotope', 'enabled', __('Enable category filters', 'ci_theme')); ?>
	</fieldset>
		
	<fieldset class="set">
		<p class="guide"><?php _e('Set the number of artists per page on the listing page', 'ci_theme'); ?></p>
		<?php ci_panel_input('artists_per_page', __('Number of artists per page', 'ci_theme')); ?>
	</fieldset>
	
<?php endif; ?>
