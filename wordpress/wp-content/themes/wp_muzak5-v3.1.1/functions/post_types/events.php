<?php
//
// events post type related functions.
//
add_action( 'init', 'ci_create_cpt_events' );

if( !function_exists('ci_create_cpt_events') ):
function ci_create_cpt_events()
{
	$labels = array(
		'name' => _x('Events', 'post type general name', 'ci_theme'),
		'singular_name' => _x('Events Item', 'post type singular name', 'ci_theme'),
		'add_new' => __('New Events Item', 'ci_theme'),
		'add_new_item' => __('Add New Events Item', 'ci_theme'),
		'edit_item' => __('Edit Events Item', 'ci_theme'),
		'new_item' => __('New Events Item', 'ci_theme'),
		'view_item' => __('View Events Item', 'ci_theme'),
		'search_items' => __('Search Events Items', 'ci_theme'),
		'not_found' =>  __('No Events Items found', 'ci_theme'),
		'not_found_in_trash' => __('No Events Items found in the trash', 'ci_theme'), 
		'parent_item_colon' => __('Parent Events Item:', 'ci_theme')
	);

	$args = array(
		'labels' => $labels,
		'singular_label' => __('Events Item', 'ci_theme'),
		'public' => true,
		'show_ui' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'has_archive' => false,
		'rewrite' => array('slug' => 'events'),
		'menu_position' => 5,
		'supports' => array('title', 'editor', 'thumbnail')		
	);

	register_post_type( 'cpt_events' , $args );

}
endif;

add_action( 'load-post.php', 'events_meta_boxes_setup' );
add_action( 'load-post-new.php', 'events_meta_boxes_setup' ); 

if( !function_exists('events_meta_boxes_setup') ):
function events_meta_boxes_setup() {
	add_action( 'add_meta_boxes', 'events_add_meta_boxes' );
	add_action( 'save_post', 'events_save_meta', 10, 2 );
}
endif;

if( !function_exists('events_add_meta_boxes') ):
function events_add_meta_boxes() {
	add_meta_box( 'events-box', __( 'Events Settings', 'ci_theme' ), 'events_score_meta_box', 'cpt_events', 'normal', 'high' );
}
endif;

if( !function_exists('events_score_meta_box') ):
function events_score_meta_box( $object, $box )
{
	ci_prepare_metabox('cpt_events');
	?>
	<div class="postbox" style="margin-top:20px">
		<h3><?php _e('Event details', 'ci_theme'); ?></h3>
		<div class="inside">
			<?php
				$recurrent = get_post_meta($object->ID, 'ci_cpt_event_recurrent', true);
				$recurrence = get_post_meta($object->ID, 'ci_cpt_event_recurrence', true);

				ci_metabox_input('ci_cpt_events_venue', __('Event Venue. For example: Ushuaia', 'ci_theme'));
				ci_metabox_input('ci_cpt_events_location', __('Event Location. For example: Ibiza, Spain', 'ci_theme'));
				ci_metabox_checkbox('ci_cpt_event_recurrent', 'enabled', __('Recurrent Event', 'ci_theme'));
			?>

			<div id="event_recurrent">
				<?php ci_metabox_input('ci_cpt_event_recurrence', __('Event Recurrence. You may use &lt;span class="date"> and &lt;span class="time"> to denote dates and times respectively. (e.g. <b>Every &lt;span class="date">TUE&lt;/span> &lt;span class="time">22:00&lt;/span></b>)', 'ci_theme')); ?>
			</div>

			<div id="event_datetime">
				<?php
					ci_metabox_input('ci_cpt_events_date', __('Event Date. Use the Date Picker (Click inside the field).', 'ci_theme'));
					ci_metabox_input('ci_cpt_events_time', __('Event Time (e.g. <b>21:00</b>)', 'ci_theme'));
				?>
			</div>			
		</div><!-- /inside -->
	</div><!-- /postbox -->
	
	<div class="postbox" style="margin-top:20px">
		<h3><?php _e('Event status', 'ci_theme'); ?></h3>
		<div class="inside">
			<?php
				$options = array(
					'' => '&nbsp;',
					'buy' => __('Tickets Available', 'ci_theme'),
					'sold' => __('Sold Out', 'ci_theme'),
					'canceled' => __('Cancelled', 'ci_theme'),
					'watch' => __('Watch Live', 'ci_theme'),
				);
				ci_metabox_dropdown('ci_cpt_events_status', $options, __('Select the event status.', 'ci_theme'));

				ci_metabox_input('ci_cpt_events_button', __('Set the button text like "Buy now" or "Join us" etc.', 'ci_theme'));
				ci_metabox_input('ci_cpt_events_tickets', __('If the event is still available enter the URL where someone can buy tickets.', 'ci_theme'), array('esc_func' => 'esc_url'));
				ci_metabox_input('ci_cpt_events_live', __('Is there a live streaming available from the event? Enter it here.', 'ci_theme'), array('esc_func' => 'esc_url'));
			?>
		</div><!-- /inside -->
	</div><!-- /postbox -->
	
	<div class="postbox" style="margin-top:20px">
		<h3><?php _e('Event Map', 'ci_theme'); ?></h3>
		<div class="inside">

			<p><?php _e('Enter a place or address and press "Search". Alternatively, you can drag the marker to the desired position, or double click on the map to set a new location.', 'ci_theme'); ?></p>

			<fieldset class="gllpLatlonPicker">
				<input type="text" class="gllpSearchField">
				<input type="button" class="button gllpSearchButton" value="<?php _e('Search place/address', 'ci_theme'); ?>">

				<div class="gllpMap">Google Maps</div>

				<input type="hidden" class="gllpZoom" value="8"/>

				<?php
					ci_metabox_input('ci_cpt_events_lat', __('Location Latitude.', 'ci_theme'), array('input_class' => 'widefat gllpLatitude'));
					ci_metabox_input('ci_cpt_events_lon', __('Location Longitude.', 'ci_theme'), array('input_class' => 'widefat gllpLongitude'));
				?>

				<input type="button" class="button gllpUpdateButton" value="Update map">

			</fieldset>

		</div><!-- /inside -->
	</div><!-- /postbox -->
	<?php 
}
endif;

if( !function_exists('events_save_meta') ):
function events_save_meta( $post_id, $post ) {
	
	if ( !ci_can_save_meta('cpt_events') ) return;

	update_post_meta($post_id, "ci_cpt_event_recurrent", ci_sanitize_checkbox($_POST["ci_cpt_event_recurrent"], 'enabled'));
	update_post_meta($post_id, "ci_cpt_event_recurrence", sanitize_text_field($_POST["ci_cpt_event_recurrence"]));

	if( ci_sanitize_checkbox($_POST["ci_cpt_event_recurrent"], 'enabled') == 'enabled' )
	{
		// Since it's a recurring event, we need to delete date and time information, so 
		// that it won't interfere with wp_query queries.
		delete_post_meta($post_id, "ci_cpt_events_date");
		delete_post_meta($post_id, "ci_cpt_events_time");
	}
	else
	{
		update_post_meta($post_id, "ci_cpt_events_date", sanitize_text_field($_POST["ci_cpt_events_date"]) );
		update_post_meta($post_id, "ci_cpt_events_time", sanitize_text_field($_POST["ci_cpt_events_time"]) );
	}

	update_post_meta($post_id, "ci_cpt_events_venue", sanitize_text_field($_POST["ci_cpt_events_venue"]) );
	update_post_meta($post_id, "ci_cpt_events_location", sanitize_text_field($_POST["ci_cpt_events_location"]) );
	update_post_meta($post_id, "ci_cpt_events_button", sanitize_text_field($_POST["ci_cpt_events_button"]) );

	update_post_meta($post_id, "ci_cpt_events_status", sanitize_key($_POST["ci_cpt_events_status"]) );

	update_post_meta($post_id, "ci_cpt_events_lon", sanitize_text_field($_POST["ci_cpt_events_lon"]) );
	update_post_meta($post_id, "ci_cpt_events_lat", sanitize_text_field($_POST["ci_cpt_events_lat"]) );

	update_post_meta($post_id, "ci_cpt_events_tickets", esc_url_raw($_POST["ci_cpt_events_tickets"]) );
	update_post_meta($post_id, "ci_cpt_events_live", esc_url_raw($_POST["ci_cpt_events_live"]) );

}
endif;

//
// Event post type custom admin list
//
add_filter('manage_edit-cpt_events_columns', 'ci_cpt_event_edit_columns');
add_action('manage_posts_custom_column',  'ci_cpt_event_custom_columns');

if( !function_exists('ci_cpt_event_edit_columns') ):
function ci_cpt_event_edit_columns($columns)
{
	$new_columns = array(
		"cb" => $columns['cb'],
		"title" => __('Event Name', 'ci_theme'),
		"event_location" => __("Event location", 'ci_theme'),
		"event_date" => __("Event Date", 'ci_theme'),
		"date" => $columns['date']
	);

	return $new_columns;
}
endif;

if( !function_exists('ci_cpt_event_custom_columns') ):
function ci_cpt_event_custom_columns($column)
{
	global $post;

	switch ($column)
	{
		case "event_location":
			echo get_post_meta($post->ID, 'ci_cpt_events_location', true);
			break;
		case "event_date":
			echo get_post_meta($post->ID, 'ci_cpt_events_date', true);
			break;
		
	}
}
endif;

if( !function_exists('printable_event_cpt_datetime') ):
function printable_event_cpt_datetime()
{
	global $post;

	$recurrent = get_post_meta($post->ID, 'ci_cpt_event_recurrent', true)=='enabled' ? true : false;
	$recurrence = get_post_meta($post->ID, 'ci_cpt_event_recurrence', true);
	$event_date = get_post_meta( $post->ID, 'ci_cpt_events_date', true );
	$event_time = get_post_meta( $post->ID, 'ci_cpt_events_time', true );

	if($recurrent)
	{
		return $recurrence;
	}
	else
	{
		$dt = new DateTime();
		$dt->setDate( substr( $event_date, 0, 4 ), substr( $event_date, 5, 2 ), substr( $event_date, 8, 2 ) );
		$dt->setTime( substr( $event_time, 0, 2 ), substr( $event_time, 3, 2 ) );
		$datetime = date_i18n( get_option('date_format').' - '.get_option('time_format'), strtotime($dt->format( 'F j Y, H:i' )) );
		return $datetime;	
	}

}
endif;

if( !function_exists('attribute_event_cpt_datetime') ):
function attribute_event_cpt_datetime()
{
	global $post;

	$recurrent = get_post_meta($post->ID, 'ci_cpt_event_recurrent', true)=='enabled' ? true : false;
	$event_date = get_post_meta( $post->ID, 'ci_cpt_events_date', true );
	$event_time = get_post_meta( $post->ID, 'ci_cpt_events_time', true );

	if($recurrent)
	{
		return '';
	}
	else
	{
		return $event_date . ' ' . $event_time;
	}

}
endif;

?>