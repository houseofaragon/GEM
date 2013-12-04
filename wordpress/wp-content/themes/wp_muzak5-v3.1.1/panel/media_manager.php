<?php
//
// Various media functions and Media Manager support for the CSSIgniter Panel options and custom fields.
//
add_image_size( 'ci_featgal_small_thumb', 100, 100, true );

if( !function_exists('ci_enqueue_media_manager_scripts')):
/**
 * Enqueues version-depended Media Manager scripts.
 */
function ci_enqueue_media_manager_scripts()
{
	global $wp_version;
	if(version_compare($wp_version, '3.5', '<'))
	{
		wp_enqueue_script('ci-media-manager-3-3');
	}
	if(version_compare($wp_version, '3.5', '>='))
	{
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('ci-media-manager-3-5');
	}

	$settings = array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'tUseThisFile' => __('Use this file', 'ci_theme'),
		'tUpdateGallery' => __('Update gallery', 'ci_theme'),
		'tLoading' => __('Loading...', 'ci_theme'),
		'tPreviewUnavailable' => __('Gallery preview not available.', 'ci_theme'),
		'tRemoveFromGallery' => __('Remove from gallery', 'ci_theme'),
	);
	wp_localize_script('ci-media-manager-3-5', 'ciMediaManager', $settings );

}
endif;

if( !function_exists('ci_featgal_update_meta') ):
/**
 * Looks for gallery custom fields in an array, sanitizes and stores them in post meta.
 * Uses substr() so return values are the same.
 *
 * @param int $post_id The post ID where the gallery's custom fields should be stored.
 * @param array $POST An array that contains gallery custom field values. Usually $_POST should be passed.
 * @param int $gid The gallery ID (instance). Only needed when a post has more than one galleries. Defaults to 1.
 * @return void|bool Nothing on success, boolean false on invalid parameters.
 */
function ci_featgal_update_meta($post_id, $POST, $gid = 1)
{
	if( absint( $post_id ) < 1 )
	{
		return false;
	}

	if( ! is_array($POST) )
	{
		return false;
	}

	$gid = absint($gid);
	if($gid < 1)
	{
		$gid = 1;
	}

	$f_ids = 'ci_featured_gallery_'.$gid;
	$f_rand = 'ci_featured_gallery_rand_'.$gid;

	$ids = array();
	$ids_string = '';
	$rand_string = '';
	if( ! empty( $POST[$f_ids] ) )
	{
		$ids = explode(',', $POST[$f_ids]);
		$ids = array_filter($ids);

		if( count($ids) > 0 )
		{
			$ids = array_map('intval', $ids);
			$ids = array_map('abs', $ids);
			$ids_string = implode(',', $ids);
		}
	}
	
	if( ! empty($POST[$f_rand]) and $POST[$f_rand] == 'rand' )
	{
		$rand_string = 'rand';
	}

	update_post_meta($post_id, $f_ids, $ids_string);
	update_post_meta($post_id, $f_rand, $rand_string);

}
endif;


if( !function_exists('ci_featgal_print_meta_html') ):
/**
 * Creates the necessary gallery HTML code for use in metaboxes.
 *
 * @param int $post_id The post ID where the gallery's default values should be loaded from. If empty, the global $post object's ID is used.
 * @param int $gid The gallery ID (instance). Only needed when a post has more than one galleries. Defaults to 1.
 * @return void
 */
function ci_featgal_print_meta_html($post_id = false, $gid = 1)
{
	if($post_id == false)
	{
		global $post;
		$post_id = $post->ID;
	}

	$gid = absint($gid);
	if($gid < 1)
	{
		$gid = 1;
	}
	
	$ids = get_post_meta($post_id, 'ci_featured_gallery_'.$gid , true);
	$rand = get_post_meta($post_id, 'ci_featured_gallery_rand_'.$gid , true);

	if( empty($ids) )
	{
		$args = array(
			'post_type' => 'attachment',
			'posts_per_page' => -1,
			'post_status' => 'inherit',
			'post_parent' => $post_id,
			'order' => 'ASC',
			'orderby' => 'menu_order'
		);
		$attachments = get_posts($args);
		
		$temp_ids = array();
		foreach($attachments as $attachment)
		{
			$temp_ids[] = $attachment->ID;
		}
		$ids = implode(',', $temp_ids);
	}

	?>
	<div class="ci-media-manager-gallery">
		<input type="button" class="ci-upload-to-gallery button" value="<?php _e('Add Images', 'ci_theme'); ?>" />
		<input type="hidden" class="ci-upload-to-gallery-ids" name="ci_featured_gallery_<?php echo $gid; ?>" value="<?php echo esc_attr($ids); ?>" />
		<p><label class="ci-upload-to-gallery-random"><input type="checkbox" name="ci_featured_gallery_rand_<?php echo $gid; ?>" value="rand" <?php checked($rand, 'rand'); ?> /> <?php _e('Randomize order', 'ci_theme'); ?></label></p>
		<div class="ci-upload-to-gallery-preview group">
			<?php
				$images = ci_featgal_get_images($ids);
				if($images !== false and is_array($images))
				{
					foreach( $images as $image )
					{
						?>
						<div class="thumb">
							<img src="<?php echo esc_url($image['url']); ?>" data-img-id="<?php echo esc_attr($image['id']); ?>">
							<a href="#" class="close media-modal-icon" title="<?php echo esc_attr( __('Remove from gallery', 'ci_theme') ); ?>"></a>
						</div>
						<?php
					}
				}
			?>
		</div>
	</div>
	<?php
}
endif;


function ci_featgal_get_attachments($post_id = false, $gid = 1, $extra_args = false)
{
	if($post_id == false)
	{
		global $post;
		$post_id = $post->ID;
	}
	else
	{
		$post_id = absint($post_id);
	}

	$gid = absint($gid);
	if($gid < 1)
	{
		$gid = 1;
	}

	$ids = get_post_meta($post_id, 'ci_featured_gallery_'.$gid , true);
	$rand = get_post_meta($post_id, 'ci_featured_gallery_rand_'.$gid , true);

	$ids = explode(',', $ids);
	$ids = array_filter($ids);

	$args = array(
		'post_type' => 'attachment',
		'post_mime_type' => 'image',
		'post_status' => 'any',
		'posts_per_page' => -1,
		
	);

	if( count($ids) > 0 )
	{
		$args['post__in'] = $ids;
		$args['orderby'] = 'post__in';

		if($rand == 'rand')
		{
			$args['orderby'] = 'rand';
		}
	}
	else
	{
		$args['post_parent'] = $post_id;
		$args['order'] = 'ASC';
		$args['orderby'] = 'menu_order';
	}

	if( is_array($extra_args) and count($extra_args) > 0 )
	{
		$args = array_merge($args, $extra_args);
	}
	
	return new WP_Query($args);
}

add_action('wp_ajax_ci_featgal_AJAXPreview', 'ci_featgal_AJAXPreview');
if( !function_exists('ci_featgal_AJAXPreview') ):
/**
 * Reads $_POST["ids"] for a comma separated list of image attachment IDs, prints a JSON array of image URLs and exits.
 * Hooked to wp_ajax_ciMediaManager_galleryPreview for AJAX updating of the galleries' previews.
 */
function ci_featgal_AJAXPreview()
{
	$ids = $_POST['ids'];
	$urls = ci_featgal_get_images($ids);
	if( $urls === false )
	{
		echo 'FAIL';
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
			wp_die();
		else
			die;
	}
	else
	{
		wp_send_json($urls);
	}
}
endif;

if( !function_exists('ci_featgal_get_images') ):
/**
 * Reads $csv for a comma separated list of image attachment IDs. Returns a php array of image URLs and IDs, or false.
 *
 * @param string $csv A comma separated list of image attachment IDs.
 * @return array|bool
 */
function ci_featgal_get_images($csv = false)
{
	$ids = explode(',', $csv);
	$ids = array_filter($ids);

	if( count($ids) > 0 )
	{
		$ids = array_map('intval', $ids);
		$ids = array_map('abs', $ids);
		$urls = array();
		foreach($ids as $id)
		{
			$thumb_file = ci_get_image_src($id, 'ci_featgal_small_thumb');

			$file = parse_url( $thumb_file );
			$file = pathinfo($file['path']);
			$file = basename($file['basename'], '.'.$file['extension']);

			global $_wp_additional_image_sizes;
			$size = $_wp_additional_image_sizes['ci_featgal_small_thumb']['width'] . 'x' . $_wp_additional_image_sizes['ci_featgal_small_thumb']['height'];
			if(substr_right($file, strlen($size))==$size)
			{
				$file = $thumb_file;
			}
			else
			{
				$file = ci_get_image_src($id, 'thumbnail');
			}

			$data = array(
				'id' => $id,
				//'url' => ci_get_image_src($id, 'ci_featgal_small_thumb')
				'url' => $file
			);
			$urls[] = $data;
		}
		return $urls;
	}
	else
	{
		return false;
	}
}
endif;


if( !function_exists('ci_media_sideload_image') ):
/**
 * This is copied and edited from wp-admin/includes/media.php with original function name media_sideload_image() as of WP v3.3
 *
 * Download an image from the specified URL and attach it to a post.
 *
 * @since 2.6.0
 *
 * @param string $file The URL of the image to download
 * @param int $post_id The post ID the media is to be associated with
 * @param string $desc Optional. Description of the image
 * @return int|WP_Error Attachment ID of the uploaded image.
 */
function ci_media_sideload_image($file, $post_id, $desc = null) {
	if ( ! empty($file) ) {
		// Download file to temp location
		$tmp = download_url( $file );

		// Set variables for storage
		// fix file filename for query strings
		preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $file, $matches);
		$file_array['name'] = basename($matches[0]);
		$file_array['tmp_name'] = $tmp;

		// If error storing temporarily, unlink
		if ( is_wp_error( $tmp ) ) {
			@unlink($file_array['tmp_name']);
			$file_array['tmp_name'] = '';
		}

		// do the validation and storage stuff
		$id = media_handle_sideload( $file_array, $post_id, $desc );
		// If error storing permanently, unlink
		if ( is_wp_error($id) ) {
			@unlink($file_array['tmp_name']);
			return $id;
		}

		$src = wp_get_attachment_url( $id );
	}

	// Finally check to make sure the file has been saved, then return the html
	// EDIT: We need the id
	if ( ! empty($src) ) {
		return $id;
		//$alt = isset($desc) ? esc_attr($desc) : '';
		//$html = "<img src='$src' alt='$alt' />";
		//return $html;
	}
}
endif;

?>