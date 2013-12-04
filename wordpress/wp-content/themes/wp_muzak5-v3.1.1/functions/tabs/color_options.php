<?php global $ci, $ci_defaults, $load_defaults; ?>
<?php if ($load_defaults===TRUE): ?>
<?php
	add_filter('ci_panel_tabs', 'ci_add_tab_color_options', 40);
	if( !function_exists('ci_add_tab_color_options') ):
		function ci_add_tab_color_options($tabs) 
		{ 
			$tabs[sanitize_key(basename(__FILE__, '.php'))] = __('Color Options', 'ci_theme'); 
			return $tabs; 
		}
	endif;

	// Default values for options go here.
	// $ci_defaults['option_name'] = 'default_value';
	// or
	// load_panel_snippet( 'snippet_name' );
	$ci_defaults['bg_semi'] = '';
	$ci_defaults['bg_semi_level'] = '0.5';

	load_panel_snippet('custom_background');
	load_panel_snippet('color_scheme');

	add_action('wp_head', 'ci_bg_semi', 110);
	if( !function_exists('ci_bg_semi') ):
	function ci_bg_semi() {
		if (ci_setting('bg_semi')=='enabled'): ?>
			<style type="text/css">
				#wrap > .container, #footer-wrap > .container { background: rgba(0,0,0,<?php ci_e_setting('bg_semi_level'); ?>); }
				#wrap > .container > .row { margin-bottom:0; }
			</style>
		<?php endif;
	}
	endif;
	
?>
<?php else: ?>

	<fieldset class="set">
		<p class="guide"><?php _e('Enable semi-transparent background behind main content', 'ci_theme'); ?></p>
		<?php ci_panel_checkbox('bg_semi', 'enabled', __('Enable semi-transparent background', 'ci_theme')); ?>

		<p class="guide" style="margin-top:20px"><?php _e('Transparency level (Values: 0.1 to 1)', 'ci_theme'); ?></p>
		<?php ci_panel_input('bg_semi_level', __('Transparency level', 'ci_theme'));  ?>	
	</fieldset>

	<?php load_panel_snippet('color_scheme'); ?>

	<?php load_panel_snippet('custom_background'); ?>

<?php endif; ?>