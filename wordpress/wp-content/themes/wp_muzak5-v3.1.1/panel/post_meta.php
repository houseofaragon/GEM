<?php
//
// Various wrapping functions for easier custom fields creation.
//

if( !function_exists('ci_prepare_metabox') ):
function ci_prepare_metabox($post_type)
{
	wp_nonce_field( basename( __FILE__ ), $post_type.'_nonce' );
}
endif;

if( !function_exists('ci_can_save_meta') ):
function ci_can_save_meta($post_type)
{
	global $post;
	
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
		return false;

	if (isset($_POST['post_view']) and $_POST['post_view']=='list')
		return false;

	if ( !isset($_POST['post_type']) or $_POST['post_type'] != $post_type)
		return false;

	if ( !isset( $_POST[$post_type.'_nonce'] ) or !wp_verify_nonce( $_POST[$post_type.'_nonce'], basename( __FILE__ ) ) )
		return false;

	$post_type_obj = get_post_type_object( $post->post_type );
	if ( !current_user_can( $post_type_obj->cap->edit_post, $post->ID ) )
		return false;

	return true;
}
endif;


if( !function_exists('ci_metabox_gallery') ):
function ci_metabox_gallery($gid = 1)
{
	global $post;
	$post_id = $post->ID;

	ci_featgal_print_meta_html($post_id, $gid);
}
endif;

if( !function_exists('ci_metabox_gallery_save') ):
function ci_metabox_gallery_save($POST, $gid = 1)
{
	global $post;
	$post_id = $post->ID;
	
	ci_featgal_update_meta($post_id, $POST, $gid);
}
endif;

if( !function_exists('ci_metabox_input') ):
function ci_metabox_input($fieldname, $label, $params=array())
{
	global $post;

	$defaults = array(
		'label_class' => '',
		'input_class' => 'widefat',
        'input_type' => 'text',
		'esc_func' => 'esc_attr',
		'before' => '<p>',
		'after' => '</p>'
	);
	$params = wp_parse_args( $params, $defaults );
	
	$value = get_post_meta($post->ID, $fieldname, true);
	$value = call_user_func($params['esc_func'], $value);

	echo $params['before'];
	?>
		<label for="<?php echo esc_attr($fieldname); ?>" class="<?php echo esc_attr($params['label_class']); ?>"><?php echo $label; ?></label>
		<input id="<?php echo esc_attr($fieldname); ?>" type="<?php echo $params['input_type']; ?>" name="<?php echo esc_attr($fieldname); ?>" value="<?php echo $value; ?>" class="<?php echo esc_attr($params['input_class']); ?>" />
	<?php
	echo $params['after'];

}
endif;

if( !function_exists('ci_metabox_dropdown') ):
function ci_metabox_dropdown($fieldname, $options, $label, $params=array())
{
	global $post;
	$options = (array)$options;

	$defaults = array(
		'before' => '<p>',
		'after' => '</p>'
	);
	$params = wp_parse_args( $params, $defaults );


	$value = get_post_meta($post->ID, $fieldname, true);

	echo $params['before'];
	?>
		<label for="<?php echo esc_attr($fieldname); ?>"><?php echo $label; ?></label><br>
		<select id="<?php echo esc_attr($fieldname); ?>" name="<?php echo esc_attr($fieldname); ?>">
			<?php foreach($options as $opt_val => $opt_label): ?>
				<option value="<?php echo esc_attr($opt_val); ?>" <?php selected($value, $opt_val); ?>><?php echo $opt_label; ?></option>
			<?php endforeach; ?>
		</select>
	<?php
	echo $params['after'];
}
endif;

if( !function_exists('ci_metabox_radio') ):
// $fieldname is the actual name="" attribute common to all radios in the group.
// $optionname is the id of the radio, so that the label can be associated with it.
function ci_metabox_radio($fieldname, $optionname, $optionval, $label, $params=array())
{
	global $post;

	$defaults = array(
		'before' => '<p>',
		'after' => '</p>'
	);
	$params = wp_parse_args( $params, $defaults );

	$value = get_post_meta($post->ID, $fieldname, true);

	echo $params['before'];
	?>
		<input type="radio" class="radio" id="<?php echo esc_attr($optionname); ?>" name="<?php echo esc_attr($fieldname); ?>" value="<?php echo esc_attr($optionval); ?>" <?php checked($value, $optionval); ?> />
		<label for="<?php echo esc_attr($optionname); ?>" class="radio"><?php echo $label; ?></label>
	<?php
	echo $params['after'];
}
endif;

if( !function_exists('ci_metabox_checkbox') ):
function ci_metabox_checkbox($fieldname, $value, $label, $params=array())
{
	global $post;

	$defaults = array(
		'before' => '<p>',
		'after' => '</p>'
	);
	$params = wp_parse_args( $params, $defaults );

	$checked = get_post_meta($post->ID, $fieldname, true);

	echo $params['before'];
	?>
	<input type="checkbox" id="<?php echo esc_attr($fieldname); ?>" class="check" name="<?php echo esc_attr($fieldname); ?>" value="<?php echo esc_attr($value); ?>" <?php checked($checked, $value); ?> />
	<label for="<?php echo esc_attr($fieldname); ?>"><?php echo $label; ?></label>
	<?php
	echo $params['after'];
}
endif;

?>