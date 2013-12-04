<?php
//
// artists post type related functions.
//
add_action( 'init', 'ci_create_cpt_artists' );

if( !function_exists('ci_create_cpt_artists') ):
function ci_create_cpt_artists()
{
	$labels = array(
		'name' => _x('Artists', 'post type general name', 'ci_theme'),
		'singular_name' => _x('Artist', 'post type singular name', 'ci_theme'),
		'add_new' => __('Add New', 'ci_theme'),
		'add_new_item' => __('Add New artist', 'ci_theme'),
		'edit_item' => __('Edit artists', 'ci_theme'),
		'new_item' => __('New artist', 'ci_theme'),
		'view_item' => __('View artists', 'ci_theme'),
		'search_items' => __('Search artists', 'ci_theme'),
		'not_found' =>  __('No artists found', 'ci_theme'),
		'not_found_in_trash' => __('No artists found in the trash', 'ci_theme'), 
		'parent_item_colon' => __('Parent artists Item:', 'ci_theme')
	);

	$args = array(
		'labels' => $labels,
		'singular_label' => __('Artists Item', 'ci_theme'),
		'public' => true,
		'show_ui' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'has_archive' => true,
		'rewrite' => array('slug' => 'artists'),
		'menu_position' => 5,
		'supports' => array('title', 'editor', 'thumbnail')		
	);

	register_post_type( 'cpt_artists' , $args );

}
endif;
?>