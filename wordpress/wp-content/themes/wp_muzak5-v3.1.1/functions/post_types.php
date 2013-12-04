<?php
//
// Include all custom post types here (one custom post type per file)
//
add_action('after_setup_theme', 'ci_load_custom_post_type_files');
if( !function_exists('ci_load_custom_post_type_files') ):
function ci_load_custom_post_type_files()
{
	$cpt_files = apply_filters('load_custom_post_type_files', array(
		'functions/post_types/slider',
		'functions/post_types/events',
		'functions/post_types/discography',
		'functions/post_types/videos',
		'functions/post_types/galleries',
		'functions/post_types/artists'
	));
	foreach($cpt_files as $cpt_file) get_template_part($cpt_file);
}
endif;


add_action( 'init', 'ci_tax_create_taxonomies');
if( !function_exists('ci_tax_create_taxonomies') ):
function ci_tax_create_taxonomies() {
	//
	// Create all taxonomies here.
	//

	// Discography > Sections Taxonomy
	$labels = array(
		'name' => _x( 'Discography Section', 'Discography Section', 'ci_theme' ),
		'singular_name' => _x( 'Section', 'Discography Section', 'ci_theme' ),
		'search_items' =>  __( 'Search Discography Section', 'ci_theme' ),
		'all_items' => __( 'All Discography Sections', 'ci_theme' ),
		'parent_item' => __( 'Parent Discography Section', 'ci_theme' ),
		'parent_item_colon' => __( 'Parent Discography Section:', 'ci_theme' ),
		'edit_item' => __( 'Edit Discography Section', 'ci_theme' ), 
		'update_item' => __( 'Update Discography Section', 'ci_theme' ),
		'add_new_item' => __( 'Add New Discography Section', 'ci_theme' ),
		'new_item_name' => __( 'New Genre Discography Section', 'ci_theme' ),
		'menu_name' => __( 'Discography Section', 'ci_theme' ),
	);

	register_taxonomy('section', array('cpt_discography'), array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'section' ),
	));

	// Artists > Category
	$labels = array(
		'name' => _x( 'Artists Categories', 'Artists Categories', 'ci_theme' ),
		'singular_name' => _x( 'Artists Category', 'Artists Category', 'ci_theme' ),
		'search_items' =>  __( 'Search Artists Categories', 'ci_theme' ),
		'all_items' => __( 'All Artists Categories', 'ci_theme' ),
		'parent_item' => __( 'Parent Artists Categories', 'ci_theme' ),
		'parent_item_colon' => __( 'Parent Artists Categories:', 'ci_theme' ),
		'edit_item' => __( 'Edit Artists Categories', 'ci_theme' ), 
		'update_item' => __( 'Update Artists Categories', 'ci_theme' ),
		'add_new_item' => __( 'Add New Artists Categories', 'ci_theme' ),
		'new_item_name' => __( 'New Genre Artists Categories', 'ci_theme' ),
		'menu_name' => __( 'Artists Categories', 'ci_theme' ),
	);

	register_taxonomy('artist-category',array('cpt_artists'), array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'artist-category' ),
	));

	// Video > Category
	$labels = array(
		'name' => _x( 'Video Categories', 'Video Categories', 'ci_theme' ),
		'singular_name' => _x( 'Video Category', 'Video Category', 'ci_theme' ),
		'search_items' =>  __( 'Search Video Categories', 'ci_theme' ),
		'all_items' => __( 'All Video Categories', 'ci_theme' ),
		'parent_item' => __( 'Parent Video Categories', 'ci_theme' ),
		'parent_item_colon' => __( 'Parent Video Categories:', 'ci_theme' ),
		'edit_item' => __( 'Edit Video Categories', 'ci_theme' ), 
		'update_item' => __( 'Update Video Categories', 'ci_theme' ),
		'add_new_item' => __( 'Add New Video Categories', 'ci_theme' ),
		'new_item_name' => __( 'New Genre Video Categories', 'ci_theme' ),
		'menu_name' => __( 'Video Categories', 'ci_theme' ),
	);

	register_taxonomy('video-category',array('cpt_videos'), array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'video-category' ),
	));


	// Galleries > Category
	$labels = array(
		'name' => _x( 'Gallery Categories', 'Gallery Categories', 'ci_theme' ),
		'singular_name' => _x( 'Gallery Category', 'Gallery Category', 'ci_theme' ),
		'search_items' =>  __( 'Search Gallery Categories', 'ci_theme' ),
		'all_items' => __( 'All Gallery Categories', 'ci_theme' ),
		'parent_item' => __( 'Parent Gallery Category', 'ci_theme' ),
		'parent_item_colon' => __( 'Parent Gallery Category:', 'ci_theme' ),
		'edit_item' => __( 'Edit Gallery Category', 'ci_theme' ), 
		'update_item' => __( 'Update Gallery Category', 'ci_theme' ),
		'add_new_item' => __( 'Add New Gallery Category', 'ci_theme' ),
		'new_item_name' => __( 'New Genre Gallery Category', 'ci_theme' ),
		'menu_name' => __( 'Gallery Categories', 'ci_theme' ),
	);

	register_taxonomy('gallery-category',array('cpt_galleries'), array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'gallery-category' ),
	));


}
endif;

add_action('admin_enqueue_scripts', 'ci_load_post_scripts');
if( !function_exists('ci_load_post_scripts') ):
function ci_load_post_scripts($hook)
{
	//
	// Add here all scripts and styles, to load on all admin pages.
	//
	wp_enqueue_style('ci-admin-icons', get_child_or_parent_file_uri('/css/admin-icons.css'));
	
	if('post.php' == $hook or 'post-new.php' == $hook)
	{
		//
		// Add here all scripts and styles, specific to post edit screens.
		//
		
		ci_enqueue_media_manager_scripts();

		wp_enqueue_script('jquery-gmaps-latlon-picker');

		wp_enqueue_style('jquery-ui-style');
		wp_enqueue_style('jquery-ui-timepicker');

		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-slider');
		wp_enqueue_script('jquery-ui-timepicker');

		wp_enqueue_style('ci-post-edit-screens');
		wp_enqueue_script('ci-post-edit-scripts');

	}
}
endif;

add_filter('request', 'ci_feed_request');
if( !function_exists('ci_feed_request') ):
function ci_feed_request($qv) {
	if (isset($qv['feed']) && !isset($qv['post_type'])){

		$qv['post_type'] = array();
		$qv['post_type'] = get_post_types($args = array(
			'public'   => true,
			'_builtin' => false
		));
		$qv['post_type'][] = 'post';
	}
	return $qv;
}
endif;
?>