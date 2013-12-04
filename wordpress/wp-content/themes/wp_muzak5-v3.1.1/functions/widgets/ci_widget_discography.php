<?php 
/**
 * Recent discography Widgets.
 */
if( !class_exists('CI_Discography') ):

class CI_Discography extends WP_Widget {

	public function __construct() {
		parent::__construct(
	 		'ci_discography_widget', // Base ID
			'-= CI Latest Albums =-', // Name
			array( 'description' => __( 'Display your latest albums', 'ci_theme' ) ),
			array( /* 'width' => 300, 'height' => 400 */ )
		);
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$disc_no 	= $instance['disc_no'];

		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
			
			$latest_discography = new WP_Query( array( 
				'post_type' => 'cpt_discography',
				'posts_per_page' => $disc_no
			)); 					

			while ( $latest_discography->have_posts() ) : $latest_discography->the_post();
				global $post;
				$album_date	= explode("-", get_post_meta($post->ID, 'ci_cpt_discography_date', true));
				?>
	
				<div id="latest-album" class="latest-item">
					<a href="<?php the_permalink(); ?>">
						<?php the_post_thumbnail('ci_discography', array('class'=> "scale-with-grid")); ?>
					</a>
					<p class="album-info">
						<span class="sub-head"><?php _e('Release date:','ci_theme'); ?> <?php echo $album_date[2]; ?>-<?php echo ci_the_month($album_date[1]); ?>-<?php echo $album_date[0]; ?></span>
						<span class="main-head"><?php the_title(); ?></span>
						<span class="album-actions">
							<a href="<?php the_permalink(); ?>" class="action-btn buy"><?php _e('Learn more','ci_theme'); ?></a>
						</span>
					</p>	
				</div><!-- /latest-album -->
				
				<?php
			endwhile; wp_reset_postdata();
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] 		= strip_tags( $new_instance['title'] );
		$instance['disc_no'] 	= strip_tags( $new_instance['disc_no'] );
		return $instance;
	}

	function form($instance){
		$instance 	= wp_parse_args( (array) $instance, array('title'=>'', 'disc_no'=>''));
		$title 		= htmlspecialchars($instance['title']);
		$disc_no 	= htmlspecialchars($instance['disc_no']);
		echo '<p><label>' . __('Title:', 'ci_theme') . '</label><input id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . esc_attr($title) . '" class="widefat" /></p>';
		echo '<p><label>' . __('Albums Number:', 'ci_theme') . '</label><input id="' . $this->get_field_id('disc_no') . '" name="' . $this->get_field_name('disc_no') . '" type="text" value="' . esc_attr($disc_no) . '" class="widefat" /></p>';
	} // form

} // class CI_discography

register_widget('CI_Discography');

endif; // !class_exists
?>