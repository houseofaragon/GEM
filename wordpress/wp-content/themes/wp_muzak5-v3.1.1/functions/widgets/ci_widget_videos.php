<?php 
/**
 * Recent videos Widgets.
 */
if( !class_exists('CI_Videos') ):

class CI_Videos extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'ci_videos_widget', // Base ID
			'-= CI Latest Videos =-', // Name
			array( 'description' => __( 'Display your latest videos', 'ci_theme' ), ),
			array( /* 'width' => 300, 'height' => 400 */ )
		);
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$videos_no = $instance['videos_no'];

		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
			
			$latest_videos = new WP_Query( 
				array( 
					'post_type' => 'cpt_videos',
					'posts_per_page' => $videos_no)); 					
			$i = 1; 
			while ( $latest_videos->have_posts() ) : $latest_videos->the_post();
			global $post;
			$video_url 	= get_post_meta($post->ID, 'ci_cpt_videos_url', true);
			$video_type = get_post_meta($post->ID, 'ci_cpt_videos_self', true);
		    
		    ?>
			<div class="latest-item latest-video">
				<a href="<?php
							 if ($video_type == 1):
							  echo "#wplayer" . $i . "-wrap";
							 else:
							  echo $video_url;
							 endif; ?>" data-rel="prettyPhoto">
					<?php
						$attr = array('class'=> "scale-with-grid");
						the_post_thumbnail('ci_media', $attr);
					?>
					<span></span>
				</a>	
				<p class="album-info">
					<span class="sub-head"><?php the_title(); ?></span>
				</p>
				<?php if ($video_type == 1): ?>
				<div id="wplayer<?php echo $i; ?>-wrap" class="video-player">
					<div id="wplayer<?php echo $i; ?>"><?php _e('Loading the player...', 'ci_theme'); ?></div>
					<script type="text/javascript">
						jwplayer('wplayer<?php echo $i; ?>').setup({
							autostart: false,
							file: "<?php echo $video_url; ?>",
							width:"500",
							flashplayer: "<?php echo get_template_directory_uri() . "/jwplayer/player.swf" ?>"
						});												
					</script>
				</div><!-- /player-wrapp -->																			
				<?php endif; ?>	
			</div><!-- /latest-album -->		    			
			<?php
			endwhile; wp_reset_postdata();
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] 		= strip_tags( $new_instance['title'] );
		$instance['videos_no'] 	= strip_tags( $new_instance['videos_no'] );
		return $instance;
	}

	function form($instance){
		$instance 	= wp_parse_args( (array) $instance, array('title'=>'', 'videos_no'=>'') );
		$title 		= htmlspecialchars($instance['title']);
		$videos_no 	= htmlspecialchars($instance['videos_no']);
		echo '<p><label>' . __('Title:', 'ci_theme') . '</label><input id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . esc_attr($title) . '" class="widefat" /></p>';
		echo '<p><label>' . __('Videos Number:', 'ci_theme') . '</label><input id="' . $this->get_field_id('videos_no') . '" name="' . $this->get_field_name('videos_no') . '" type="text" value="' . esc_attr($videos_no) . '" class="widefat" /></p>';
	} // form

} // class CI_videos

register_widget('CI_Videos');

endif; // !class_exists
?>