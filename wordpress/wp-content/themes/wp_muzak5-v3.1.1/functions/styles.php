<?php
add_action('init', 'ci_register_theme_styles');
if( !function_exists('ci_register_theme_styles') ):
function ci_register_theme_styles()
{
	//
	// Register all front-end and admin styles here. 
	// There is no need to register them conditionally, as the enqueueing can be conditional.
	//

	wp_register_style('google-font-open', 'http://fonts.googleapis.com/css?family=Open+Sans:400,700,400italic');
	wp_register_style('ci-skeleton', get_child_or_parent_file_uri('/css/skeleton.css'));
	wp_register_style('flexslider', get_child_or_parent_file_uri('/css/flexslider.css'));
	wp_register_style('mediaqueries', get_child_or_parent_file_uri('/css/mediaqueries.css'));
	wp_register_style('jquery-ui-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
	wp_register_style('jquery-ui-timepicker', get_child_or_parent_file_uri('/css/jquery-ui-timepicker-addon.css'));

	wp_register_style('ci-style', get_stylesheet_uri(), array(
		'google-font-open',
		'ci-skeleton'
	), CI_THEME_VERSION, 'screen');
		
	wp_register_style('ci-color-scheme', get_child_or_parent_file_uri('/colors/'.ci_setting('stylesheet')));

	wp_register_style('ci-extra-panel-styles', get_child_or_parent_file_uri('/css/panel_styles.css'));
	wp_register_style('ci-post-edit-screens', get_child_or_parent_file_uri('/css/post_edit_screens.css'));

}
endif;


add_action('wp_enqueue_scripts', 'ci_enqueue_theme_styles');
if( !function_exists('ci_enqueue_theme_styles') ):
function ci_enqueue_theme_styles()
{
	//
	// Enqueue all (or most) front-end styles here.
	//

	wp_enqueue_style('ci-style');
	
	if (ci_setting('responsive') == 'enabled') {
		wp_enqueue_style('mediaqueries');
	}
	
	wp_enqueue_style('flexslider');	
	wp_enqueue_style('ci-color-scheme');

}
endif;


if( !function_exists('ci_enqueue_admin_theme_styles') ):
add_action('admin_enqueue_scripts','ci_enqueue_admin_theme_styles');
function ci_enqueue_admin_theme_styles() 
{
	global $pagenow, $typenow;

	//
	// Enqueue here styles that are to be loaded on all admin pages.
	//

	if(is_admin() and $pagenow=='themes.php' and isset($_GET['page']) and $_GET['page']=='ci_panel.php')
	{
		//
		// Enqueue here styles that are to be loaded only on CSSIgniter Settings panel.
		//

		wp_enqueue_style('ci-extra-panel-styles');
	}

}
endif;

if( !function_exists('ci_print_ie_lt9_styles') ):
function ci_print_ie_lt9_styles()
{
	?>
	<!--[if lt IE 9]>
		<link rel="stylesheet" href="<?php echo get_child_or_parent_file_uri('/css/ie.css'); ?>" type="text/css" media="all" />
	<![endif]-->
	<?php
}
endif;

?>