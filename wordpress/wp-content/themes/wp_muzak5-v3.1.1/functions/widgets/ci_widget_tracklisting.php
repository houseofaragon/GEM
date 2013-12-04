<?php
/**
 * Tracklisting Widget.
 */
if( !class_exists('CI_Tracklisting') ):

class CI_Tracklisting extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'ci_tracklisting_widget', // Base ID
			'-= CI Album Tracklisting =-', // Name
			array( 'description' => __( 'Display an album optionally using the tracklisting shortcode to add songs.', 'ci_theme' ), ),
			array( /*'width'=> 400, 'height'=> 350*/ )
		);

	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$disc_no = $instance['disc_no'];

		echo $before_widget;

		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;

		global $post;
		$old_post = $post;
		$post = get_post($disc_no);
		setup_postdata($post);

		$album_date	= explode("-", get_post_meta($post->ID, 'ci_cpt_discography_date', true));
		?>
		<div class="latest-album latest-item">
			<a class="item-thumb" href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail('ci_discography', array('class'=> "scale-with-grid")); ?>
			</a>
			<div class="album-info">
				<p class="main-head"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
				<p class="sub-head"><?php _e('Release date:','ci_theme'); ?> <?php echo $album_date[2]; ?>-<?php echo ci_the_month($album_date[1]); ?>-<?php echo $album_date[0]; ?></p>
			</div>
		</div><!-- /latest-album -->

		<?php
			$show_tracks = $instance['show_tracks'];
			if( $show_tracks == 'enabled' )
			{
				$hide_numbers = $instance['hide_numbers'] == 'enabled' ? ' hide_numbers="true" ' : '';
				$hide_players = $instance['hide_players'] == 'enabled' ? ' hide_players="true" ' : '';
	
				echo do_shortcode('[tracklisting id="' . $disc_no . '" hide_buttons="true" ' . $hide_numbers . $hide_players . ']');
			}
		?>
		
		<?php

		$post = $old_post;
		setup_postdata($post);
		
		echo $after_widget;
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] 		= strip_tags( $new_instance['title'] );
		$instance['disc_no'] 	= intval( $new_instance['disc_no'] );
		$instance['show_tracks'] = $new_instance['show_tracks'];
		$instance['hide_numbers'] = $new_instance['hide_numbers'];
		$instance['hide_players'] = $new_instance['hide_players'];
		return $instance;
	}

	function form($instance){
		$instance 	= wp_parse_args( (array) $instance, array('title'=>'', 'disc_no'=>'', 'show_tracks'=>'enabled', 'hide_players'=>'', 'hide_numbers'=>''));
		$title 		= htmlspecialchars($instance['title']);
		$disc_no = intval($instance['disc_no']);
		$showtracks = $instance['show_tracks'];
		$hide_numbers = $instance['hide_numbers'];
		$hide_players = $instance['hide_players'];

		echo '<p><label>' . __('Title:','ci_theme') . '</label><input id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" class="widefat" /></p>';
		?>

		<p>
			<label for="<?php echo $this->get_field_id('disco_no'); ?>"><?php _e( 'Select Discography Item:','ci_theme' ); ?></label>
			<?php wp_dropdown_posts(array(
				'post_type' => 'cpt_discography',
				'selected' => $disc_no,
				'class' => 'widefat'
			), $this->get_field_name('disc_no')); ?>
		</p>
		<p>
			<input type="checkbox" value="enabled" id="<?php echo $this->get_field_id('show_tracks'); ?>" name="<?php echo $this->get_field_name('show_tracks'); ?>" <?php checked($showtracks, 'enabled'); ?>> <label for="<?php echo $this->get_field_id('show_tracks'); ?>"><?php _e('Show track listing?', 'ci_theme'); ?></label><br />
			<input type="checkbox" value="enabled" id="<?php echo $this->get_field_id('hide_numbers'); ?>" name="<?php echo $this->get_field_name('hide_numbers'); ?>" <?php checked($hide_numbers, 'enabled'); ?>> <label for="<?php echo $this->get_field_id('hide_numbers'); ?>"><?php _e('Hide track numbers', 'ci_theme'); ?></label><br />
			<input type="checkbox" value="enabled" id="<?php echo $this->get_field_id('hide_players'); ?>" name="<?php echo $this->get_field_name('hide_players'); ?>" <?php checked($hide_players, 'enabled'); ?>> <label for="<?php echo $this->get_field_id('hide_players'); ?>"><?php _e('Hide player buttons', 'ci_theme'); ?></label>
		</p>
		<p><?php _e('Please note that the "Show track listing" option above, only works for discography items that have their tracks registered through the <strong>Discography Settings - Track details</strong> pane.', 'ci_theme'); ?></p>
	<?php
	} // form

} // class CI_Tracklisting

register_widget('CI_Tracklisting');

endif;
?>