<?php
// Let's call this early, so that no unnecessary processing is done in case it's a file download.
add_action('init', 'ci_check_get_for_downloads', 1 );
if( !function_exists('ci_check_get_for_downloads') ):
function ci_check_get_for_downloads()
{
	// Check that the needed GET parameter is set and not empty.
	if( !isset($_GET['force_download']) ) return;
	$file_url = trim(urldecode($_GET['force_download']));
	if( empty($file_url) )
	{
		ci_force_download_throw_404();
		return;
	}

	// Let's check if it's a "local" file. If not, redirect to the external URL.
	if(strpos($file_url, site_url())===false and strpos($file_url, home_url())===false)
	{
		wp_redirect($file_url);
		exit;
	}


	$uploads = wp_upload_dir();
	$base_uploads_url = $uploads['baseurl'];
	$base_uploads_dir = $uploads['basedir'];
	
	// Check that the requested file is supposed to be somewhere inside the local uploads folder.
	if( strpos($file_url, $base_uploads_url)===false )
	{
		ci_force_download_throw_404();
		return;
	}

	// Let's get the relative file path.
	$rel_file_url = mb_str_replace($base_uploads_url, '', $file_url);

	// Resolve the path (in case it contains '..' etc)
	$abs_file_path = realpath($base_uploads_dir . $rel_file_url);
	
	// Check again it's inside the uploads folder, using the local path this time.
	if( $abs_file_path===false or strpos($abs_file_path, $base_uploads_dir)===false)
	{
		ci_force_download_throw_404();
		return;
	}
	
	$file_info = pathinfo($abs_file_path);

	// OK, we are pretty certain that this request is legit. Let's see if we can actually read the file.
	// Oh, and we shouldn't allow .php files
	if( $file_info['extension']=='.php' or !is_readable($abs_file_path) )
		die(__('Access to this file is not permitted.', 'ci_theme'));


	//
	// Finally. Let's send the damn file.
	//
	
	// This is a lie. We just want the browser to force-download.
	header('Content-type: application/octet-stream');
	header('Content-Disposition: attachment; filename="'.$file_info['basename'].'"');
	readfile($abs_file_path); // Completely Safe. Forces browser to download instead of opening the passed download URL.
	exit;
	
}
endif;

if( !function_exists('ci_force_download_throw_404') ):
function ci_force_download_throw_404()
{
	add_action('parse_query','ci_force_download_throw_404_handler', 1);
}
endif;

if( !function_exists('ci_force_download_throw_404_handler') ):
function ci_force_download_throw_404_handler()
{
	global $wp_query;
	$wp_query->set_404();
	status_header( 404 );
}
endif;
?>