<?php 
	get_template_part('panel/constants');

	load_theme_textdomain( 'ci_theme', get_template_directory() . '/lang' );

	// This is the main options array. Can be accessed as a global in order to reduce function calls.
	$ci = get_option(THEME_OPTIONS);
	$ci_defaults = array();

	// The $content_width needs to be before the inclusion of the rest of the files, as it is used inside of some of them.
	if ( ! isset( $content_width ) ) $content_width = 640;

	//
	// Let's bootstrap the theme.
	//
	get_template_part('panel/bootstrap');

	get_template_part('functions/shortcodes');
	get_template_part('functions/woocommerce');
	get_template_part('functions/downloads_handler');


	//
	// Define our various image sizes.
	//
	add_theme_support( 'post-thumbnails' );
	//set_post_thumbnail_size( 300, 150, true );
	add_image_size('ci_home_slider', 940, 470, true);
	add_image_size('ci_home_listing_short', 460, 300, true);
	add_image_size('ci_home_listing_long', 700, 457, true);
	add_image_size('ci_discography', 438, 438, true);
	add_image_size('ci_event', 438, 9999, false);
	add_image_size('ci_event_thumb', 60, 73, true);	
	add_image_size('ci_media', 438, 246, true);
	add_image_size('ci_fullwidth', 940, 400, true);


    // Let WooCommerce know that we support it.
    add_theme_support('woocommerce');

	// Let the theme know that we have WP-PageNavi styled.
	add_ci_theme_support('wp_pagenavi');

	// Let the user choose a color scheme on each post individually.
	add_ci_theme_support('post-color-scheme', array('page', 'post', 'product'));


	// Set image sizes also for woocommerce.
	// Run only when the theme or WooCommerce is activated.
	add_action('ci_theme_activated', 'ci_woocommerce_image_dimensions');
	register_activation_hook( WP_PLUGIN_DIR.'/woocommerce/woocommerce.php', 'ci_woocommerce_image_dimensions' );
	if( !function_exists('ci_woocommerce_image_dimensions') ):
	function ci_woocommerce_image_dimensions()
	{
		// Image sizes
		update_option('shop_thumbnail_image_size', array(
			'width' => '100',
			'height' => '100',
			'crop' => 1
		));
		update_option('shop_catalog_image_size', array(
			'width' => '480',
			'height' => '480',
			'crop' => 1
		));
		update_option('shop_single_image_size', array(
			'width' => '480',
			'height' => '480',
			'crop' => 1
		));
	}
	endif;


/*

	// Don't use default styles for galleries
	add_filter( 'use_default_gallery_style', '__return_false' );

	// Remove width and height attributes from the <img> tag.
	// Remove also when an image is sent to the editor. When the user resizes the image from the handles, width and height
	// are re-inserted, so expected behaviour is not lost.
	add_filter('post_thumbnail_html', 'ci_remove_thumbnail_dimensions');
	add_filter('image_send_to_editor', 'ci_remove_thumbnail_dimensions');
	function ci_remove_thumbnail_dimensions($html)
	{
		$html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
		return $html;
	}

*/


	//
	// Columns helper for Isotope
	//
	if( !function_exists('ci_column_classes_isotope') ):
	function ci_column_classes_isotope($cols_number, $parent_cols=16, $reset=false)
	{
		$classes = ci_column_classes($cols_number, $parent_cols, $reset);
		$classes = str_replace(array('alpha', 'omega'), '', $classes);
		return $classes;
	}
	endif;
	
	//
	// Date helper
	//
	if( !function_exists('ci_the_month') ):
	function ci_the_month($m) {
		$t = mktime(0, 0, 0, $m, 1, 2000);					    
		return date_i18n("M", $t);
	}
	endif;
	
	add_filter( 'wp_get_attachment_link', 'ci_pretty');
	if( !function_exists('ci_pretty') ):
	function ci_pretty($content) {
		$content = preg_replace("/<a/" , "<a rel=\"prettyPhoto[slides]\"", $content, 1);
		return $content;
	}
	endif;
	
?>