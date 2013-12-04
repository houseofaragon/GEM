<?php
if( !function_exists('shortcode_empty_paragraph_fix') ):
function shortcode_empty_paragraph_fix($content)
{
	$array = array (
		'<p>[' => '[', 
		']</p>' => ']', 
		']<br />' => ']'
	);
	$content = strtr($content, $array);
	return $content;
}
endif;
add_filter('the_content', 'shortcode_empty_paragraph_fix');

if( !function_exists('ci_tracklisting_shortcode_handler') ):
function ci_tracklisting_shortcode_handler($params, $content = '')
{
	if(!empty($content))
		return ci_tracklisting_old_shortcode($params, $content);
	else
		return ci_tracklisting_shortcode($params, $content);
}
endif;

if( !function_exists('ci_tracklisting_shortcode') ):
function ci_tracklisting_shortcode($params, $content = '') {
	extract( shortcode_atts( array(
		'id' => '',
		'slug' => '',
		'limit' => -1,
		'tracks' => '',
		'hide_numbers' => '',
		'hide_buttons' => '',
		'hide_players' => '',
		'hide_titles' => ''
	), $params ) );

	$tracks = empty($tracks) ? '' : explode(',', $tracks);

	global $post;

	// By default, when the shortcode tries to get the tracklisting of any discography item, should be
	// restricted to only published discographies.
	// However, when the discography itself shows its own tracklisting, it should be allowed to do so,
	// no matter what its post status may be.
	$args = array(
		'post_type' => 'cpt_discography',
		'post_status' => 'publish',
		'numberposts' => '1',
		'suppress_filters' => false
	);
	
	if(empty($id) and empty($slug))
	{
		$args['p'] = $post->ID;

		// We are showing the current post's tracklisting (since we didn't get any parameters),
		// so we need to make sure we can retrieve it whatever its post status might be.
		$args['post_status'] = 'any';
	}
	elseif(!empty($id) and $id > 0)
	{
		$args['p'] = $id;

		// Check if the current post's ID matches what was passed.
		// If so, we need to make sure we can retrieve it whatever its post status might be.
		if($post->ID == $args['p'])
			$args['post_status'] = 'any';
	}
	elseif(!empty($slug))
	{
		$args['name'] = sanitize_title_with_dashes($slug, '', 'save');

		// Check if the current post's slug matches what was passed.
		// If so, we need to make sure we can retrieve it whatever its post status might be.
		if($post->post_name == $args['name'])
			$args['post_status'] = 'any';
	}

	$posts = get_posts($args);

	if(count($posts) >= 1)
	{
		$post_id = $posts[0]->ID;
	
		$fields = get_post_meta($post_id, 'ci_cpt_discography_tracks', true);
	
		ob_start();
	
		if(!empty($fields))
		{
			$track_num = 0; // Helps count actual songs (instead of increments of field groups, i.e. 6)
			$outputted = 0; // Helps count actually outputted songs. Used with 'limit' parameter.
			?>
			<ol class="tracklisting">
				<?php for($i=0; $i < count($fields); $i+=6): ?>
					<?php
						$track_num++;
						$i_title = $i;
						$i_subtitle = $i + 1;
						$i_buy = $i + 2;
						$i_play = $i + 3;
						$i_download = $i + 4;
						$i_lyrics = $i + 5;
					
						$track_id = $post_id.'_'.$track_num;
					?>
	
					<?php
						if(is_array($tracks) and !in_array($track_num, $tracks))
							continue;
					?>
	
					<li id="custom_player<?php echo $track_id; ?>" class="track group custom_player<?php echo $track_id; ?>">
					
						<?php if( empty($hide_numbers) ): ?>
							<span class="track-no"><?php echo $track_num; ?></span>
						<?php endif; ?>
	
						<?php if( empty($hide_titles) ): ?>
							<p class="track-info">
								<span class="sub-head"><?php echo $fields[$i_title]; ?></span>
								<?php if(!empty($fields[$i_subtitle])): ?>
									<span class="main-head"><?php echo $fields[$i_subtitle]; ?></span>
								<?php endif; ?>
							</p>
						<?php endif; ?>
	
						<?php if( empty($hide_buttons) ): ?>
							<div class="btns">
								<?php if(!empty($fields[$i_download])): ?>
									<a href="<?php echo add_query_arg('force_download', esc_url($fields[$i_download])); ?>" class="action-btn buy download-track"><?php _e('Download track', 'ci_theme'); ?></a>
								<?php endif; ?>
								<?php if(!empty($fields[$i_buy])): ?>
									<a href="<?php echo esc_url($fields[$i_buy]); ?>" class="action-btn buy buy-track"><?php _e('Buy track', 'ci_theme'); ?></a>
								<?php endif; ?>
								<?php if(!empty($fields[$i_lyrics])): ?>
									<?php $lyrics_id = sanitize_html_class($fields[$i_title].'-lyrics-'.$track_num); ?>
									<a data-rel="prettyPhoto" href="#<?php echo $lyrics_id; ?>" title="<?php echo sprintf(_x('%s Lyrics', 'song name lyrics', 'ci_theme'), $fields[$i_title]); ?>" class="action-btn buy"><?php _e('Lyrics', 'ci_theme'); ?></a>
									<div id="<?php echo $lyrics_id; ?>" class="track-lyrics-hold"><?php echo wpautop($fields[$i_lyrics]); ?></div>
								<?php endif; ?>
							</div>
						<?php endif; ?>
	
						<?php if( empty($hide_players) ): ?>
							<?php if((substr_left($fields[$i_play], 25)=='http://api.soundcloud.com') or (substr_left($fields[$i_play], 26)=='https://api.soundcloud.com')): ?>
								<a href="#track<?php echo $track_id; ?>" class="track-listen sc"><?php _e('Listen','ci_theme'); ?></a>
								<div id="track<?php echo $track_id; ?>" class="track-audio">
									<?php echo do_shortcode('[soundcloud width="100%" url="'.esc_url($fields[$i_play]).'" iframe="true" /]'); ?>
								</div>
							<?php else: ?>
								<?php if(ci_setting('jwplayer_active')=='enabled'): ?>
									<a class="track-listen" href="#track<?php echo $track_id; ?>"><?php _e('Listen', 'ci_theme'); ?></a>
									<div id="track<?php echo $track_id; ?>" class="track-audio jw">
										<div id="player<?php echo $track_id; ?>"><?php _e('Loading Player','ci_theme'); ?></div>
										<script type="text/javascript">
											jQuery(document).ready(function($) {
												var idPlayer = player<?php echo $track_id; ?>;
												setupjw("player<?php echo $track_id; ?>", "<?php echo $fields[$i_play]; ?>");
											});
										</script>
									</div>
								<?php else: ?>
									<a class="sm2_button" href="<?php echo esc_url($fields[$i_play]); ?>"><?php echo $fields[$i_title]; ?></a>
								<?php endif; ?>
							<?php endif; ?>
						<?php endif; ?>
	
					</li>
	
					<?php
						if($limit > 0)
						{
							$outputted++;
							if($outputted >= $limit)
								break;
						}
					?>
				<?php endfor; ?>
			</ol>
			<?php
		}
		
		$output = ob_get_clean();
	}
	else
	{
		$output = apply_filters('ci_tracklisting_shortcode_error_msg', __('Cannot show track listings from non-public, non-published posts.', 'ci_theme'));
	}
	
	return $output;
}
endif;
add_shortcode('tracklisting', 'ci_tracklisting_shortcode_handler');



if( !function_exists('ci_tracklisting_old_shortcode') ):
function ci_tracklisting_old_shortcode($params, $content = '')
{
	return '<ol class="tracklisting">' . do_shortcode($content) . '</ol>';
}
endif;

if( !function_exists('track') ):
function track($params, $content = '') {
	extract( shortcode_atts( array(
		'track_no' => '1',
		'title'	 => 'Track title',
		'subtitle' => 'Track subtitle',
		'type'	 => 'soundcloud',
		'buy_url'	 => '',
		'download_url' => ''
	), $params ) );

	STATIC $i = 0;
	$i++;
	$p = "";
	$b = "";
	$d = "";
	$s = "";
	$m = "";
	$t = "";


	if ($download_url != "") {
		$download_url = add_query_arg('force_download', $download_url);
		$d = '<a href="'. $download_url .'" class="action-btn buy download-track">' . __('Download track','ci_theme') . '</a>';
	}

	if ($buy_url != "") {
		$b = '<a href="'. $buy_url .'" class="action-btn buy buy-track">' . __('Buy track','ci_theme') . '</a>';
	}

	if ('soundcloud' == strtolower($type)) {
		$t = "custom_soundcloud";
		$p =	'<div class="btns">' . $d . $b . '</div><a href="#track' . $track_no . '" class="track-listen sc">' . __('Listen','ci_theme') . '</a>'.
				'<div id="track'. $track_no . '" class="track-audio">' .
					do_shortcode($content) .
				'</div>';
	}
	else {
		$t = "custom_player";
		$p =	'<div class="btns">' . $d . $b . '</div>';

		if(ci_setting('jwplayer_active')=='enabled')
		{
			$p .= '<a href="#track' . $track_no . '" class="track-listen">' . __('Listen','ci_theme') . '</a>' .
					'<div id="track' . $track_no . '" class="track-audio jw">' .
						'<div id="player' . $track_no . '">' . __('Loading Player','ci_theme') . '</div>' .
						'<script type="text/javascript">' .
							'jQuery(document).ready(function($) {' . 
								'var idPlayer = player' . $track_no . '; ' .
								'setupjw("player' . $track_no . '","' . do_shortcode($content) . '");' .
							'});' . 
						'</script>' .
					'</div>';
		}
		else
		{
			$p .= '<a class="sm2_button" href="'.do_shortcode($content).'">'.$title.'</a>';
		}
	}

	if ($subtitle != "")
		$s = '<span class="main-head">' . $subtitle . '</span>';

	return
		'<li id="' . $t . $i . '" class="track group ' . $t . $i . '">' .  
			'<span class="track-no">' . $track_no . '</span>' .
			'<p class="track-info">' .
				'<span class="sub-head">' . $title . '</span>' .
				$s .
			'</p>' . $p .
		'</li>'; 
}
endif;
add_shortcode('track', 'track');


if( !function_exists('ci_columns') ):
function ci_columns($params, $content = '') {
	return
		'<div class="row">' . 
			do_shortcode($content) . 
		'</div>';
}
endif;
add_shortcode('columns','ci_columns');


if( !function_exists('ci_column') ):
function ci_column($params, $content = '') {	
	extract( shortcode_atts( array(
		'position' => '',
		'number' => 'eight'
	), $params ) );

	return
		'<div class="columns '. $position . ' ' . $number .'">' . 
			do_shortcode($content) . 
		'</div>';
}
endif;
add_shortcode('column','ci_column');

if( !function_exists('ci_column_one') ):
function ci_column_one($params, $content = '') {	
	extract( shortcode_atts( array(
		'position' => ''
	), $params ) );

	return
		'<div class="columns one ' . $position .'">' . 
			do_shortcode($content) . 
		'</div>';
}
endif;
add_shortcode('column_one','ci_column_one');

if( !function_exists('ci_column_two') ):
function ci_column_two($params, $content = '') {	
	extract( shortcode_atts( array(
		'position' => ''
	), $params ) );

	return
		'<div class="columns two ' . $position .'">' . 
			do_shortcode($content) . 
		'</div>';
}
endif;
add_shortcode('column_two','ci_column_two');

if( !function_exists('ci_column_three') ):
function ci_column_three($params, $content = '') {	
	extract( shortcode_atts( array(
		'position' => ''
	), $params ) );

	return
		'<div class="columns three ' . $position .'">' . 
			do_shortcode($content) . 
		'</div>';
}
endif;
add_shortcode('column_three','ci_column_three');

if( !function_exists('ci_column_four') ):
function ci_column_four($params, $content = '') {	
	extract( shortcode_atts( array(
		'position' => ''
	), $params ) );

	return
		'<div class="columns four ' . $position .'">' . 
			do_shortcode($content) . 
		'</div>';
}
endif;
add_shortcode('column_four','ci_column_four');

if( !function_exists('ci_column_five') ):
function ci_column_five($params, $content = '') {	
	extract( shortcode_atts( array(
		'position' => ''
	), $params ) );

	return
		'<div class="columns five ' . $position .'">' . 
			do_shortcode($content) . 
		'</div>';
}
endif;
add_shortcode('column_five','ci_column_five');

if( !function_exists('ci_column_six') ):
function ci_column_six($params, $content = '') {	
	extract( shortcode_atts( array(
		'position' => ''
	), $params ) );

	return
		'<div class="columns six ' . $position .'">' . 
			do_shortcode($content) . 
		'</div>';
}
endif;
add_shortcode('column_six','ci_column_six');

if( !function_exists('ci_column_seven') ):
function ci_column_seven($params, $content = '') {	
	extract( shortcode_atts( array(
		'position' => ''
	), $params ) );

	return
		'<div class="columns seven ' . $position .'">' . 
			do_shortcode($content) . 
		'</div>';
}
endif;
add_shortcode('column_seven','ci_column_seven');

if( !function_exists('ci_column_eight') ):
function ci_column_eight($params, $content = '') {	
	extract( shortcode_atts( array(
		'position' => ''
	), $params ) );

	return
		'<div class="columns eight ' . $position .'">' . 
			do_shortcode($content) . 
		'</div>';
}
endif;
add_shortcode('column_eight','ci_column_eight');

if( !function_exists('ci_column_nine') ):
function ci_column_nine($params, $content = '') {	
	extract( shortcode_atts( array(
		'position' => ''
	), $params ) );

	return
		'<div class="columns nine ' . $position .'">' . 
			do_shortcode($content) . 
		'</div>';
}
endif;
add_shortcode('column_nine','ci_column_nine');

if( !function_exists('ci_column_ten') ):
function ci_column_ten($params, $content = '') {	
	extract( shortcode_atts( array(
		'position' => ''
	), $params ) );

	return
		'<div class="columns ten ' . $position .'">' . 
			do_shortcode($content) . 
		'</div>';
}
endif;
add_shortcode('column_ten','ci_column_ten');

if( !function_exists('ci_column_eleven') ):
function ci_column_eleven($params, $content = '') {	
	extract( shortcode_atts( array(
		'position' => ''
	), $params ) );

	return
		'<div class="columns eleven ' . $position .'">' . 
			do_shortcode($content) . 
		'</div>';
}
endif;
add_shortcode('column_eleven','ci_column_eleven');

if( !function_exists('ci_column_twelve') ):
function ci_column_twelve($params, $content = '') {	
	extract( shortcode_atts( array(
		'position' => ''
	), $params ) );

	return
		'<div class="columns twelve ' . $position .'">' . 
			do_shortcode($content) . 
		'</div>';
}
endif;
add_shortcode('column_twelve','ci_column_twelve');

if( !function_exists('ci_column_thirteen') ):
function ci_column_thirteen($params, $content = '') {	
	extract( shortcode_atts( array(
		'position' => ''
	), $params ) );

	return
		'<div class="columns thirteen ' . $position .'">' . 
			do_shortcode($content) . 
		'</div>';
}
endif;
add_shortcode('column_thirteen','ci_column_thirteen');

if( !function_exists('ci_column_fourteen') ):
function ci_column_fourteen($params, $content = '') {	
	extract( shortcode_atts( array(
		'position' => ''
	), $params ) );

	return
		'<div class="columns fourteen ' . $position .'">' . 
			do_shortcode($content) . 
		'</div>';
}
endif;
add_shortcode('column_fourteen','ci_column_fourteen');

if( !function_exists('ci_column_fifteen') ):
function ci_column_fifteen($params, $content = '') {	
	extract( shortcode_atts( array(
		'position' => ''
	), $params ) );

	return
		'<div class="columns fifteen ' . $position .'">' . 
			do_shortcode($content) . 
		'</div>';
}
endif;
add_shortcode('column_fifteen','ci_column_fifteen');

if( !function_exists('ci_column_sixteen') ):
function ci_column_sixteen($params, $content = '') {	
	extract( shortcode_atts( array(
		'position' => ''
	), $params ) );

	return
		'<div class="columns sixteen ' . $position .'">' . 
			do_shortcode($content) . 
		'</div>';
}
endif;
add_shortcode('column_sixteen','ci_column_sixteen');

?>