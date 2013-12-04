<?php 
//
// Use this file to override theme-specific functions.
// Bottom of functions.php is too late for functions override.
// Make sure to wrap the functions in function_exists() check so that
// child themes get a chance to override as well.
//

if( !function_exists('ci_e_content') ):
/**
 * Echoes the content or the excerpt, depending on user preferences.
 * Also echoes the Read More, if the excerpt is selected.
 * 
 * @access public
 * @return void
 */
function ci_e_content($more_link_text = null, $stripteaser = false)
{
	global $post, $ci;
	if (is_single() or is_page())
		the_content(); 
	else
	{
		if(ci_setting('preview_content')=='enabled')
		{
			the_content($more_link_text, $stripteaser);
		}
		else
		{
			the_excerpt();
			ci_read_more();
		}
	}
}
endif;


?>