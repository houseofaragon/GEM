<?php

register_nav_menus(
	array(
	  'ci_main_menu' => __('Main Menu', 'ci_theme')
	)
);

// Add ID and Class attributes to the first <ul> occurence in wp_page_menu
if( !function_exists('mainmenu_add_ul_atributes') ):
function mainmenu_add_ul_atributes($menu, $args) {
	$menu = preg_replace('/<ul>/', '<ul id="navigation" class="sf-menu group">', $menu, 1);
	// Remove the wrapping div
	$menu = str_replace(array('<div class="' . esc_attr($args['menu_class']) . '">', '</div>'), '', $menu);
	return $menu;
}
endif;
add_filter('wp_page_menu','mainmenu_add_ul_atributes', 10, 2);


?>