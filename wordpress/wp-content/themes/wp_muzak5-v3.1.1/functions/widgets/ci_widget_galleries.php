<?php 
/**
 * Recent galleries Widgets.
 */
if( !class_exists('CI_Galleries') ):

class CI_Galleries extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'ci_galleries_widget', // Base ID
			'-= CI Latest Photo Galleries =-', // Name
			array( 'description' => __( 'Display your latest photo galleries', 'ci_theme' ), ),
			array( /* 'width' => 300, 'height' => 400 */ )
		);
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$galleries_no 	= $instance['galleries_no'];

		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
			
			$latest_galleries = new WP_Query( 
				array( 
					'post_type' => 'cpt_galleries',
					'posts_per_page' => $galleries_no)); 					
			$i = 1; 
			while ( $latest_galleries->have_posts() ) : $latest_galleries->the_post();
			global $post;
			$gal_location 	= get_post_meta($post->ID, 'ci_cpt_galleries_location', true);
			$gal_venue 		= get_post_meta($post->ID, 'ci_cpt_galleries_venue', true);		    
		    ?>
			<div class="latest-item gallery-item">
				<a href="<?php the_permalink(); ?>">
					<?php
						$attr = array('class'=> "scale-with-grid");
						the_post_thumbnail('ci_media', $attr);
					?>
				</a>	
				<p class="album-info">
					<span class="sub-head"><?php echo $gal_venue; ?></span>
					<span class="main-head"><?php echo $gal_location; ?></span>
					<span class="album-actions"><a href="<?php the_permalink(); ?>" class="action-btn view"><?php _e('View set','ci_theme'); ?></a></span>
				</p>	
			</div><!-- /gallery-item -->		    			
			<?php
			endwhile; wp_reset_postdata();
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] 		= strip_tags( $new_instance['title'] );
		$instance['galleries_no'] 	= strip_tags( $new_instance['galleries_no'] );
		return $instance;
	}

	function form($instance){
		$instance 	= wp_parse_args( (array) $instance, array('title'=>'', 'galleries_no'=>'') );
		$title 		= htmlspecialchars($instance['title']);
		$galleries_no 	= htmlspecialchars($instance['galleries_no']);
		echo '<p><label>' . __('Title:','ci_theme') . '</label><input id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . esc_attr($title) . '" class="widefat" /></p>';
		echo '<p><label>' . __('Galleries Number:','ci_theme') . '</label><input id="' . $this->get_field_id('galleries_no') . '" name="' . $this->get_field_name('galleries_no') . '" type="text" value="' . esc_attr($galleries_no) . '" class="widefat" /></p>';
	} // form

} // class CI_galleries

register_widget('CI_Galleries');

endif; // !class_exists
?>