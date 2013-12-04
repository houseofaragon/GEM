<?php 
/**
 * Recent Events Widgets.
 */
if( !class_exists('CI_Events') ):

class CI_Events extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'ci_events_widget', // Base ID
			'-= CI Recent Events =-', // Name
			array( 'description' => __( 'Display your recent events', 'ci_theme' ), ),
			array( /* 'width' => 300, 'height' => 400 */ )
		);
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$events_no 	= $instance['events_no'];
		$show_rec 	= $instance['show_recurrent'];

		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
			
			//$latest_events = new WP_Query( array( 
			//	'post_type' => 'cpt_events',
			//	'meta_key' => 'ci_cpt_events_date',
			//	'meta_value' => date_i18n('Y-m-d'),
			//	'meta_compare' => '>=',
			//	'orderby' => 'meta_value',
			//	'order' => 'asc',
			//	'posts_per_page' => $events_no
			//));				

			$recurrent_params = array(
				'post_type' => 'cpt_events',
				'posts_per_page' => -1,
				'meta_key' => 'ci_cpt_event_recurrence',
				'orderby'   => 'meta_value',
				'order'      => 'ASC',
				'meta_query' => array(
					array(
						'key' => 'ci_cpt_event_recurrent',
						'value'	=> 'enabled',
						'compare' => '='
					)
				)
			);

			$date_params = array(
				'post_type' => 'cpt_events',
				'posts_per_page' => $events_no,
				'meta_key' => 'ci_cpt_events_date',
				'orderby'   => 'meta_value',
				'order'      => 'ASC',
				'meta_query' => array(
					array(
						'key' => 'ci_cpt_events_date',
						'value'	=> date_i18n('Y-m-d'),
						'compare' => '>=',
						'type' => 'date'
					)
				)
			);

			if($show_rec=='on')
				$latest_events = merge_wp_queries($recurrent_params, $date_params);
			else
				$latest_events = new WP_Query($date_params);

			echo '<ul class="tour-dates">';
			while ( $latest_events->have_posts() ) : $latest_events->the_post();
				global $post;
				$event_date 	= explode("-",get_post_meta($post->ID, 'ci_cpt_events_date', true));
				$event_location = get_post_meta($post->ID, 'ci_cpt_events_location', true);
				$event_venue 	= get_post_meta($post->ID, 'ci_cpt_events_venue', true);
				$event_status 	= get_post_meta($post->ID, 'ci_cpt_events_status', true);
				$event_wording	= get_post_meta($post->ID, 'ci_cpt_events_button', true);
				$recurrent = get_post_meta($post->ID, 'ci_cpt_event_recurrent', true);
				$recurrence = get_post_meta($post->ID, 'ci_cpt_event_recurrence', true);
				$event_url		= "#";
				
				switch ($event_status) {
				case "buy":
					if ($event_wording == "") $event_wording 	= __('Buy Tickets','ci_theme'); 
					$event_url		= get_post_meta($post->ID, 'ci_cpt_events_tickets', true);
					break;
				case "sold":
					if ($event_wording == "") $event_wording 	= __('Sold Out','ci_theme'); 
					break;
				case "canceled":
					if ($event_wording == "") $event_wording 	= __('Canceled','ci_theme'); 
					break;
				case "watch":
					if ($event_wording == "") $event_wording 	= __('Watch Live','ci_theme');
					$event_url		= get_post_meta($post->ID, 'ci_cpt_events_live', true); 
					break;    
				}
				
				?>
				<li class="group">						
					<?php if($recurrent=='enabled'): ?>
						<p class="tour-date"><?php echo $recurrence; ?></p>
					<?php else: ?>
						<p class="tour-date"><span class="day"><?php echo $event_date[2]; ?></span><?php echo ci_the_month($event_date[1]); ?> <span class="year"><?php echo $event_date[0]; ?></span></p>
					<?php endif; ?>
					<div class="tour-place">
						<span class="sub-head"><?php if ($post->post_content != ""): ?><a href="<?php the_permalink(); ?>"><?php endif; ?><?php echo $event_venue; ?><?php if ($post->post_content != ""): ?></a><?php endif; ?></span>
						<span class="main-head"><?php echo $event_location; ?></span>							
						<?php if ($event_status != ""): ?><a href="<?php echo $event_url; ?>" class="action-btn <?php echo $event_status; ?>"><?php echo $event_wording; ?></a><?php endif; ?>
					</div>
				</li>		    			
				<?php
			endwhile; wp_reset_postdata();
			echo '</ul>';

		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] 		= strip_tags( $new_instance['title'] );
		$instance['events_no'] 	= intval( $new_instance['events_no'] ) > 0 ? intval( $new_instance['events_no'] ) : 1;
		$instance['show_recurrent'] = $new_instance['show_recurrent'] == 'on' ? 'on' : '' ;
		return $instance;
	}

	function form($instance){
		$instance 	= wp_parse_args( (array) $instance, array('title'=>'', 'events_no'=>'') );
		$title 		= htmlspecialchars($instance['title']);
		$events_no 	= intval($instance['events_no']);
		$show_rec 	= !empty($instance['show_recurrent']) ? $instance['show_recurrent'] : '' ;
		echo '<p><label>' . __('Title:', 'ci_theme') . '</label><input id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . esc_attr($title) . '" class="widefat" /></p>';
		echo '<p><label>' . __('Events Number:', 'ci_theme') . '</label><input id="' . $this->get_field_id('events_no') . '" name="' . $this->get_field_name('events_no') . '" type="text" value="' . esc_attr($events_no) . '" class="widefat" /></p>';
 		echo '<p><input id="' . $this->get_field_id('show_recurrent') . '" name="' . $this->get_field_name('show_recurrent') . '" type="checkbox" value="on" '.($show_rec=="on" ? 'checked="checked"' : '').' /> <label for="'.$this->get_field_id('show_recurrent').'">' . __('Show recurrent events?', 'ci_theme') . '</label></p>';
		echo '<p>' . __('Recurrent events don\'t count towards the limit you set above. If enabled, the widget will display all recurrents events, and a number of dated events according to the number you set.', 'ci_theme') . '</p>';
	} // form

} // class CI_Events

register_widget('CI_Events');

endif; // !class_exists
?>