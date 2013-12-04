<?php
add_filter('ci_panel_logo_url', 'ci_theme_panel_logo_url', 10, 2);
if( !function_exists('ci_theme_panel_logo_url') ):
function ci_theme_panel_logo_url($url, $path)
{
	if($path == '/panel/img/logo.png')
		return get_child_or_parent_file_uri('/images/panel_logo.png');
	else
		return $url;
}
endif;

add_filter('ci_panel_menu_title', 'ci_theme_panel_menu_title_change', 10, 2);
if( !function_exists('ci_theme_panel_menu_title_change') ):
function ci_theme_panel_menu_title_change($menu_title, $whitelabeled)
{
	// We always want it to say "Theme Settings", independently of white-label status.
	return __('Theme Settings', 'ci_theme');
}
endif;

?>