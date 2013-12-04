<?php global $ci, $ci_defaults, $load_defaults, $content_width; ?>
<?php if ($load_defaults===TRUE): ?>
<?php
	add_filter('ci_panel_tabs', 'ci_add_tab_audio_options', 60);
	if( !function_exists('ci_add_tab_audio_options') ):
		function ci_add_tab_audio_options($tabs) 
		{ 
			$tabs[sanitize_key(basename(__FILE__, '.php'))] = __('Audio Options', 'ci_theme'); 
			return $tabs; 
		}
	endif;

	// Default values for options go here.
	// $ci_defaults['option_name'] = 'default_value';
	// or
	// load_panel_snippet( 'snippet_name' );
	$ci_defaults['jwplayer_active']			= 'disabled';

?>
<?php else: ?>

	<fieldset class="set">
		<p class="guide"><?php _e('This theme uses soundManager2 by default in order to provide support for audio files. If you want to use the JWPlayer instead, you need to go to http://www.longtailvideo.com/players/jw-flv-player/ and download the player. Unzip and place the contents in a folder called jwplayer inside the theme folder (e.g. /wp_muzak5/jwplayer). Then activate the following option. Refer to the documentation for further information.', 'ci_theme'); ?></p>
		<?php ci_panel_checkbox('jwplayer_active', 'enabled', __('Activate JWPlayer', 'ci_theme')); ?>
	</fieldset>

	<fieldset class="set">
		<p class="guide"><?php echo sprintf(__('If you want to use the SoundCloud shortcode you need to download and <a href="%s">install the SoundCloud plugin</a>. Refer to the documentation on how to use it.', 'ci_theme'), 'http://wordpress.org/extend/plugins/soundcloud-shortcode'); ?></p>
	</fieldset>

<?php endif; ?>