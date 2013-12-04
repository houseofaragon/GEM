<?php
/*
Template Name: Events
*/
?>

<?php get_header(); ?>
<?php get_template_part('inc_section'); ?>

<div class="row">
	<div class="sixteen columns">

		<div class="twelve columns content events alpha">

			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

				<?php
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
						'posts_per_page' => -1,
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

					$future_events = merge_wp_queries($recurrent_params, $date_params);

					global $post;
				?>

				<?php if (ci_setting('events_map_show') == 'enabled'): ?>
					<h3 class="widget-title"><?php _e('Event Map', 'ci_theme'); ?></h3>
					<div id="map"></div>

					<script type="text/javascript">
						var locations = [
							<?php
							while ( $future_events->have_posts() ) : $future_events->the_post();
								$map_date 		= explode("-",get_post_meta($post->ID, 'ci_cpt_events_date', true));
								$map_time 		= get_post_meta($post->ID, 'ci_cpt_events_time', true);
								$map_location 	= get_post_meta($post->ID, 'ci_cpt_events_location', true);
								$map_venue 		= get_post_meta($post->ID, 'ci_cpt_events_venue', true);
								$map_status 	= get_post_meta($post->ID, 'ci_cpt_events_status', true);
								$map_lat	 	= get_post_meta($post->ID, 'ci_cpt_events_lat', true);
								$map_lon	 	= get_post_meta($post->ID, 'ci_cpt_events_lon', true);
								$map_wording	= get_post_meta($post->ID, 'ci_cpt_events_button', true);
								$recurrent 		= get_post_meta($post->ID, 'ci_cpt_event_recurrent', true);
								$recurrence 	= get_post_meta($post->ID, 'ci_cpt_event_recurrence', true);

								$map_url = "#";
								
								switch ($map_status) {
								case "buy":
									if ($map_wording == "") $map_wording = __('Buy Tickets','ci_theme'); 
									$map_url = get_post_meta($post->ID, 'ci_cpt_events_tickets', true);
									break;
								case "sold":
									if ($map_wording == "") $map_wording = __('Sold Out','ci_theme'); 
									break;
								case "canceled":
									if ($map_wording == "") $map_wording = __('Canceled','ci_theme'); 
									break;
								case "watch":
									if ($map_wording == "") $map_wording = __('Watch Live','ci_theme');
									$map_url = get_post_meta($post->ID, 'ci_cpt_events_live', true); 
									break;
								}

								if($recurrent=='enabled'){
									?>['<h3><?php echo addslashes($map_venue); ?></h3><h4><?php echo addslashes($map_location); ?></h4><p><?php echo $recurrence; ?></p><a href="<?php echo $map_url; ?>"><?php echo esc_attr($map_wording); ?></a><p><?php  ?></p>', <?php echo $map_lat; ?>, <?php echo $map_lon; ?>],<?php
								}
								else {
									?>['<h3><?php echo addslashes($map_venue); ?></h3><h4><?php echo addslashes($map_location); ?></h4><p><?php echo $map_date[2] . "-" . ci_the_month($map_date[1]) . "-" . $map_date[0]; ?>, <?php echo $map_time; ?></p><a href="<?php echo $map_url; ?>"><?php echo esc_attr($map_wording); ?></a><p><?php  ?></p>', <?php echo $map_lat; ?>, <?php echo $map_lon; ?>],<?php
								}
							endwhile;
							wp_reset_postdata(); ?>
						];

						var map = new google.maps.Map(document.getElementById('map'), {
							zoom: 2,
							center: new google.maps.LatLng(0, 0),
							mapTypeId: google.maps.MapTypeId.ROADMAP
						});

						var infowindow = new google.maps.InfoWindow();

						var marker, i;

						for (i = 0; i < locations.length; i++) {  
							marker = new google.maps.Marker({
								position: new google.maps.LatLng(locations[i][1], locations[i][2]),
								map: map
							});

							google.maps.event.addListener(marker, 'click', (function(marker, i) {
								return function() {
									infowindow.setContent(locations[i][0]);
									infowindow.open(map, marker);
								}
							})(marker, i));
						}
					</script>

					<?php $future_events->rewind_posts(); ?>
				<?php endif; ?>

				<h3 class="widget-title"><?php _e('Upcoming Events', 'ci_theme'); ?></h3>
				<ul class="tour-dates current-dates">
					<?php while ( $future_events->have_posts() ) : $future_events->the_post(); ?>
						<?php
							$event_date 	= explode("-",get_post_meta($post->ID, 'ci_cpt_events_date', true));
							$event_time 	= get_post_meta($post->ID, 'ci_cpt_events_time', true);
							$event_location = get_post_meta($post->ID, 'ci_cpt_events_location', true);
							$event_venue 	= get_post_meta($post->ID, 'ci_cpt_events_venue', true);
							$event_status 	= get_post_meta($post->ID, 'ci_cpt_events_status', true);
							$event_wording	= get_post_meta($post->ID, 'ci_cpt_events_button', true);
							$recurrent 		= get_post_meta($post->ID, 'ci_cpt_event_recurrent', true);
							$recurrence 	= get_post_meta($post->ID, 'ci_cpt_event_recurrence', true);
							$event_url		= "#";

							switch ($event_status) {
							case "buy":
								if ($event_wording == "") $event_wording = __('Buy Tickets','ci_theme'); 
								$event_url = get_post_meta($post->ID, 'ci_cpt_events_tickets', true);
								break;
							case "sold":
								if ($event_wording == "") $event_wording = __('Sold Out','ci_theme'); 
								break;
							case "canceled":
								if ($event_wording == "") $event_wording = __('Canceled','ci_theme'); 
								break;
							case "watch":
								if ($event_wording == "") $event_wording = __('Watch Live','ci_theme');
								$event_url = get_post_meta($post->ID, 'ci_cpt_events_live', true); 
								break;
							}
						?>
						<li class="group">
							<?php if($recurrent=='enabled'): ?>
								<p class="tour-date"><?php echo $recurrence; ?></p>
							<?php else: ?>
								<p class="tour-date"><span class="day"><?php echo $event_date[2]; ?></span><?php echo ci_the_month($event_date[1]); ?> <span class="year"><?php echo $event_date[0]; ?></span></p>
							<?php endif; ?>

							<div class="tour-thumb">
								<?php $event_thumb_url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large', true); ?>
								<a href="<?php echo $event_thumb_url[0]; ?>" data-rel="prettyPhoto">
									<?php the_post_thumbnail('ci_event_thumb'); ?>
								</a>
							</div>

							<div class="tour-place">
								<span class="sub-head"><?php if (!empty($post->post_content)): ?><a href="<?php the_permalink(); ?>"><?php endif; ?><?php echo $event_venue; ?><?php if (!empty($post->post_content)): ?></a><?php endif; ?></span>
								<span class="main-head"><?php echo $event_location; ?></span>
								<?php if (!empty($event_status)): ?><a href="<?php echo $event_url; ?>" class="action-btn <?php echo $event_status; ?>"><?php echo $event_wording; ?></a><?php endif; ?>
							</div>
						</li>
					<?php endwhile; wp_reset_postdata(); ?>
				</ul><!-- /tour-dates -->

				<?php if (ci_setting('events_past') == 'enabled'): ?>
					<h3 class="widget-title"><?php _e('Past Events', 'ci_theme'); ?></h3>
					<ul class="tour-dates current-dates">
						<?php
							$old_events = new WP_Query( array( 
								'post_type' => 'cpt_events',
								'meta_key' => 'ci_cpt_events_date',
								'meta_value' => date_i18n('Y-m-d'),
								'meta_compare' => '<',
								'orderby' => 'meta_value',
								'order' => 'ASC',
								'posts_per_page' => -1
							)); 
						?>

						<?php while ( $old_events->have_posts() ) : $old_events->the_post(); ?>
							<?php
								$event_date 	= explode("-",get_post_meta($post->ID, 'ci_cpt_events_date', true));
								$event_location = get_post_meta($post->ID, 'ci_cpt_events_location', true);
								$event_venue 	= get_post_meta($post->ID, 'ci_cpt_events_venue', true);
								$event_status 	= get_post_meta($post->ID, 'ci_cpt_events_status', true);
								$event_wording	= get_post_meta($post->ID, 'ci_cpt_events_button', true);
								$event_url		= "#";

								switch ($event_status) {
								case "buy":
									if ($event_wording == "") $event_wording = __('Buy Tickets','ci_theme'); 
									$event_url = get_post_meta($post->ID, 'ci_cpt_events_tickets', true);
									break;
								case "sold":
									if ($event_wording == "") $event_wording = __('Sold Out','ci_theme'); 
									break;
								case "canceled":
									if ($event_wording == "") $event_wording = __('Canceled','ci_theme'); 
									break;
								case "watch":
									if ($event_wording == "") $event_wording = __('Watch Live','ci_theme');
									$event_url = get_post_meta($post->ID, 'ci_cpt_events_live', true); 
									break;
								}
							?>
							<li class="group">
								<p class="tour-date"><span class="day"><?php echo $event_date[2]; ?></span><?php echo ci_the_month($event_date[1]); ?> <span class="year"><?php echo $event_date[0]; ?></span></p>
								<div class="tour-thumb">
									<?php $event_thumb_url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large', true); ?>
									<a href="<?php echo $event_thumb_url[0]; ?>" data-rel="prettyPhoto">
										<?php the_post_thumbnail('ci_event_thumb'); ?>
									</a>
								</div>
								<div class="tour-place">
									<span class="sub-head"><a href="<?php the_permalink(); ?>"><?php echo $event_venue; ?></a></span>
									<span class="main-head"><?php echo $event_location; ?></span>
									<a href="<?php echo $event_url; ?>" class="action-btn <?php echo $event_status; ?>"><?php echo $event_wording; ?></a>
								</div>
							</li>
						<?php endwhile; wp_reset_postdata();  ?>
					</ul><!-- /tour-dates -->
				<?php endif; ?>

			<?php endwhile; endif; ?>

		</div><!-- /twelve columns -->

		<aside class="four columns sidebar omega">
			<?php dynamic_sidebar('events-sidebar'); ?>
		</aside><!-- /sidebar -->

	</div><!-- /sixteen columns -->
</div><!-- /row -->

<?php get_footer(); ?>