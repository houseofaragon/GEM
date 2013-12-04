<?php
//
// discography post type related functions.
//
add_action( 'init', 'ci_create_cpt_discography' );

if( !function_exists('ci_create_cpt_discography') ):
function ci_create_cpt_discography()
{
	$labels = array(
		'name' => _x('Discography', 'post type general name', 'ci_theme'),
		'singular_name' => _x('Discography Item', 'post type singular name', 'ci_theme'),
		'add_new' => __('Add New', 'ci_theme'),
		'add_new_item' => __('Add New Discography Item', 'ci_theme'),
		'edit_item' => __('Edit Discography Item', 'ci_theme'),
		'new_item' => __('New Discography Item', 'ci_theme'),
		'view_item' => __('View Discography Item', 'ci_theme'),
		'search_items' => __('Search Discography Items', 'ci_theme'),
		'not_found' =>  __('No Discography Items found', 'ci_theme'),
		'not_found_in_trash' => __('No Discography Items found in the trash', 'ci_theme'), 
		'parent_item_colon' => __('Parent Discography Item:', 'ci_theme')
	);

	$args = array(
		'labels' => $labels,
		'singular_label' => __('Discography Item', 'ci_theme'),
		'public' => true,
		'show_ui' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'has_archive' => true,
		'rewrite' => array('slug' => 'discography'),
		'menu_position' => 5,
		'supports' => array('title', 'editor', 'thumbnail')		
	);

	register_post_type( 'cpt_discography' , $args );

}
endif;

add_action( 'load-post.php', 'discography_meta_boxes_setup' );
add_action( 'load-post-new.php', 'discography_meta_boxes_setup' ); 

if( !function_exists('discography_meta_boxes_setup') ):
function discography_meta_boxes_setup() {
	add_action( 'add_meta_boxes', 'discography_add_meta_boxes' );
	add_action( 'save_post', 'discography_save_meta', 10, 2 );
}
endif;

if( !function_exists('discography_add_meta_boxes') ):
function discography_add_meta_boxes() {
	add_meta_box( 'discography-box', __( 'Discography Settings', 'ci_theme' ), 'discography_score_meta_box', 'cpt_discography', 'normal', 'high' );
}
endif;

if( !function_exists('discography_score_meta_box') ):
function discography_score_meta_box( $object, $box )
{
	ci_prepare_metabox('cpt_discography');
	?>
	<div class="postbox" style="margin-top:20px">
		<h3><?php _e('Album details', 'ci_theme'); ?></h3>
		<div class="inside">
			<?php
				ci_metabox_input('ci_cpt_discography_date', __('Release Date.', 'ci_theme'));
				ci_metabox_input('ci_cpt_discography_label', __('Recording Label.', 'ci_theme'));
				ci_metabox_input('ci_cpt_discography_cat_no', __('Catalog Number.', 'ci_theme'));
			?>
		</div><!-- /inside -->
	</div><!-- /postbox -->
	
	<div class="postbox" style="margin-top:20px">
		<h3><?php _e('Purchase details', 'ci_theme'); ?></h3>
		<div class="inside">
			<?php
				ci_metabox_input('ci_cpt_discography_status', __('Album Status. For example: "This album is now available"', 'ci_theme'));
				ci_metabox_input('ci_cpt_discography_purchase_text', __('Purchase text. For example: "You can purchase this album from" OR "Pre-order this album now"', 'ci_theme'));
				ci_metabox_input('ci_cpt_discography_purchase_text_from1', __('Purchase from text #1. For example: "iTunes"', 'ci_theme'));
				ci_metabox_input('ci_cpt_discography_purchase_text_url1', __('Purchase from URL #1.', 'ci_theme'), array('esc_func' => 'esc_url'));
				ci_metabox_input('ci_cpt_discography_purchase_text_from2', __('Purchase from text #2. For example: "Amazon"', 'ci_theme'));
				ci_metabox_input('ci_cpt_discography_purchase_text_url2', __('Purchase from URL #2.', 'ci_theme'), array('esc_func' => 'esc_url'));
			?>
		</div><!-- /inside -->
	</div><!-- /postbox -->

	<div class="postbox" style="margin-top:20px">
		<h3><?php _e('Tracks Details', 'ci_theme'); ?></h3>
		<div class="inside">
			<p><?php _e('You may add the tracks of your release, along with related information such as a Download URL, Buy URL and lyrics. Press the <em>Add Track</em> button to add a new track, and individually the <em>Remove me</em> button to delete a track.', 'ci_theme'); ?></p>
			<p><?php echo sprintf(__('You can use a SoundCloud URL in place of the Play URL, assuming you have the SoundCloud shortcode available by installing the <a href="%s">SoundCloud Shortcode plugin</a>.', 'ci_theme'), 'http://wordpress.org/plugins/soundcloud-shortcode/'); ?></p>
			<div id="ci_repeating_tracks">
				<a href="#" class="tracks-add-field button"><?php _e('Add Track', 'ci_theme'); ?></a>
				<table class="tracks postbox">
					<thead>
					 <tr>
						<th class="cell-centered"><?php _e('Track No.', 'ci_theme'); ?></th>
						<th><?php _e('Title', 'ci_theme'); ?></th>
						<th><?php _e('Subtitle', 'ci_theme'); ?></th>
						<th><?php _e('Buy URL', 'ci_theme'); ?></th>
						<th><?php _e('Play URL', 'ci_theme'); ?></th>
						<th><?php _e('Download URL', 'ci_theme'); ?></th>
						<th class="cell-centered"><?php _e('Remove', 'ci_theme'); ?></th>
					 </tr>
					</thead>
						<?php
							$fields = get_post_meta($object->ID, 'ci_cpt_discography_tracks', true);
							if (!empty($fields) and is_array($fields)) 
							{
								for( $i = 0; $i < count($fields); $i+=6 )
								{
									// Let's initialize an array, in order to avoid out of bound errors.
									$track = array();
									for( $k = 0; $k < 6; $k++)
									{
										if( !empty( $fields[ $i + $k ] ) )
											$track[$k] = $fields[ $i + $k ];
										else
											$track[$k] = '';
									}
									?>
									<tbody class="track-group">
										<tr>
											<td class="cell-centered" rowspan="2"><?php _e('Track #', 'ci_theme'); ?><span class="track-num"><?php echo ($i/6)+1; ?></span></td>
											<td class="tracks-field"><input type="text" name="ci_cpt_discography_tracks[]" placeholder="<?php _e('Title', 'ci_theme'); ?>" value="<?php echo esc_attr($track[0]); ?>" /></td>
											<td class="tracks-field"><input type="text" name="ci_cpt_discography_tracks[]" placeholder="<?php _e('Subtitle', 'ci_theme'); ?>" value="<?php echo esc_attr($track[1]); ?>" /></td>
											<td class="tracks-field"><input type="text" name="ci_cpt_discography_tracks[]" placeholder="<?php _e('Buy URL', 'ci_theme'); ?>" value="<?php echo esc_url($track[2]); ?>" /></td>
											<td class="tracks-field"><div class="wp-media-buttons"><input type="text" name="ci_cpt_discography_tracks[]" placeholder="<?php _e('Play URL', 'ci_theme'); ?>" value="<?php echo esc_url($track[3]); ?>" class="uploaded with-button" /><a href="#" class="ci-upload ci-upload-track button add_media"><span class="wp-media-buttons-icon"></span></a></div></td>
											<td class="tracks-field"><input type="text" name="ci_cpt_discography_tracks[]" placeholder="<?php _e('Download URL', 'ci_theme'); ?>" value="<?php echo esc_url($track[4]); ?>" /></td>
											<td class="tracks-field cell-centered"><a href="#" class="tracks-remove button insert-media add-media"><?php _e('Remove me', 'ci_theme'); ?></a></td>
										</tr>
										<tr>
											<td class="tracks-field" colspan="6"><textarea placeholder="<?php _e('Song Lyrics', 'ci_theme'); ?>" name="ci_cpt_discography_tracks[]"><?php echo esc_textarea($track[5]); ?></textarea></td>
										</tr>
									</tbody>
									<?php
								}
							}
						?>
				</table>
				<a href="#" class="tracks-add-field button"><?php _e('Add Track', 'ci_theme'); ?></a>

			</div>
			<p><?php _e('Once you add your tracks you may display them by using the <strong>[tracklisting]</strong> shortcode. By default, it will display the tracks of the current discography item. You may also display the track listing of any discography item in any other post/page or widget (that supports shortcodes) by passing the <em>ID</em> or <em>slug</em> parameter to the shortcode. E.g. <strong>[tracklisting id="25"]</strong> or <strong>[tracklisting slug="the-division-bell"]</strong>', 'ci_theme'); ?></p>
			<p><?php _e('You can also selectively display tracks, by passing their track number (counting from 1), separated by a comma, like this <strong>[tracklisting tracks="2,5,8"]</strong> and can limit the total number of tracks displayed like <strong>[tracklisting limit="3"]</strong>', 'ci_theme'); ?></p>
			<p><?php _e('Of course, you can mix and match the parameters, so the following is totally valid: <strong>[tracklisting slug="the-division-bell" tracks="2,5,8" limit="2"]</strong>', 'ci_theme'); ?></p>
			<p><?php _e('The older syntax, <strong>[tracklisting][track][/track][/tracklisting]</strong> is still supported but not prefered.', 'ci_theme'); ?></p>
		</div><!-- /inside -->
	</div><!-- /postbox -->

	<?php 
}
endif;

if( !function_exists('discography_save_meta') ):
function discography_save_meta( $post_id, $post ) {
	
	if ( !ci_can_save_meta('cpt_discography') ) return;
		
	update_post_meta($post_id, "ci_cpt_discography_date", sanitize_text_field($_POST["ci_cpt_discography_date"]) );
	update_post_meta($post_id, "ci_cpt_discography_label", sanitize_text_field($_POST["ci_cpt_discography_label"]) );
	update_post_meta($post_id, "ci_cpt_discography_cat_no", sanitize_text_field($_POST["ci_cpt_discography_cat_no"]) );
	update_post_meta($post_id, "ci_cpt_discography_status", sanitize_text_field($_POST["ci_cpt_discography_status"]) );
	update_post_meta($post_id, "ci_cpt_discography_purchase_text", sanitize_text_field($_POST["ci_cpt_discography_purchase_text"]) );
	update_post_meta($post_id, "ci_cpt_discography_purchase_text_from1", sanitize_text_field($_POST["ci_cpt_discography_purchase_text_from1"]) );
	update_post_meta($post_id, "ci_cpt_discography_purchase_text_from2", sanitize_text_field($_POST["ci_cpt_discography_purchase_text_from2"]) );
	
	update_post_meta($post_id, "ci_cpt_discography_purchase_text_url1", esc_url_raw($_POST["ci_cpt_discography_purchase_text_url1"]) );
	update_post_meta($post_id, "ci_cpt_discography_purchase_text_url2", esc_url_raw($_POST["ci_cpt_discography_purchase_text_url2"]) );

	update_post_meta($post_id, "ci_cpt_discography_tracks", (isset($_POST["ci_cpt_discography_tracks"]) ? $_POST["ci_cpt_discography_tracks"] : '') );

}
endif;
?>