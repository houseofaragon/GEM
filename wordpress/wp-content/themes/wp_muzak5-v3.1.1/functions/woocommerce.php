<?php if(woocommerce_enabled()):

	// Skip the default woocommerce styling and use our boilerplate.
	define('WOOCOMMERCE_USE_CSS', false);
	
	add_action( 'wp_enqueue_scripts', 'ci_woocommerce_boilerplate', 10 );
	if( !function_exists('ci_woocommerce_boilerplate') ):
	function ci_woocommerce_boilerplate()
	{
		// Skip the default woocommerce styling and use our boilerplate.
		wp_enqueue_style('ci-woocommerce', get_child_or_parent_file_uri('/css/ci_woocommerce.css'));
	}
	endif;

	// Change number of columns in product loop
	add_filter('loop_shop_columns', 'ci_loop_show_columns');
	if( !function_exists('ci_loop_show_columns') ):
	function ci_loop_show_columns() {
		return 3;
	}
	endif;



	/*
	 * Unhook the following functions as they are either not needed, or needed in a place where a hook is not available
	 * therefore called directly from the template files.
	 */

	// Remove result count, e.g. "Showing 1–10 of 22 results"
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );

	// We don't need breadcrumbs.
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );

	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

	// We don't need the Rating and Add to Cart button.
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
	//remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

	// Remove sale flash and thumbnail from woocommerce_before_shop_loop_item_title as they are hardcoded into the
	// content-product.php template since there is no satisfying hook.
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );

	// We don't need the coupon form in the checkout page. It's included in the cart page.
	remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );

	// Move the add to cart button where it should be.
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
	add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 10 );

	// We need to remove the single product title as there is no appropriate hook for it and we are adding it
	// directly into single-product.php
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );

	// Re-arrange the categories after the price.
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
	add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 15 );
	
	// Remove cross-sells as they don't really look alright.
	// If you need cross-sells, comment the remove_action() line and uncomment the function that follows.
	remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
	/*
	// Let's change the cross-sells column class of each item, as it will otherwise be 'six'
	add_filter('ci_product_item_columns_class', 'ci_product_item_columns_class_cross_sells');
	function ci_product_item_columns_class_cross_sells($class)
	{
		if(is_cart())
			return 'three';
	}
	*/



	// Remove the add_to_cart_button class from the shop loop items, as it triggers an AJAX request
	// that can't be otherwise overridden.
	add_filter('add_to_cart_class', 'ci_woocommerce_remove_add_to_cart_class');
	if( !function_exists('ci_woocommerce_remove_add_to_cart_class') ):
	function ci_woocommerce_remove_add_to_cart_class($class)
	{
		return '';
	}
	endif;

	if ( ! function_exists( 'woocommerce_get_product_thumbnail' ) ) {
		function woocommerce_get_product_thumbnail( $size = 'shop_catalog', $placeholder_width = 0, $placeholder_height = 0  ) {
			global $post;
			if ( has_post_thumbnail() )
				return get_the_post_thumbnail( $post->ID, $size, array('class' => 'scale-with-grid') );
			elseif ( woocommerce_placeholder_img_src() )
				return woocommerce_placeholder_img( $size );
		}
	}


	// Add scale-with-grid class to the placeholder image html.
	// Applies to product images
	add_filter('woocommerce_placeholder_img', 'ci_woocommerce_add_placeholder_image_class');
	if( !function_exists('ci_woocommerce_add_placeholder_image_class') ):
	function ci_woocommerce_add_placeholder_image_class($html)
	{
		$classes = array('scale-with-grid');

		$class_str = ' class="' . implode(' ', $classes) . '" />';
		$html = str_replace(' />', $class_str, $html);
		return $html;
	}
	endif;

	// Override function so that we can add scale-with-grid in subcategory images.
	if ( ! function_exists( 'woocommerce_subcategory_thumbnail' ) ):
	function woocommerce_subcategory_thumbnail( $category ) {
		global $woocommerce;

		$small_thumbnail_size  	= apply_filters( 'single_product_small_thumbnail_size', 'shop_catalog' );
		$dimensions    			= $woocommerce->get_image_size( $small_thumbnail_size );
		$thumbnail_id  			= get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true  );

		if ( $thumbnail_id ) {
			$image = wp_get_attachment_image_src( $thumbnail_id, $small_thumbnail_size  );
			$image = $image[0];
		} else {
			$image = woocommerce_placeholder_img_src();
		}

		if ( $image )
			echo '<img src="' . $image . '" class="scale-with-grid" alt="' . $category->name . '" width="' . $dimensions['width'] . '" height="' . $dimensions['height'] . '" />';
	}
	endif;


	// Override the related products function so that it outputs the right number of products and columns
	if ( !function_exists( 'woocommerce_output_related_products' ) ):
	function woocommerce_output_related_products() {
		woocommerce_related_products( 3, 3 );
	}
	endif;

	// Override the default upsells function, so that we don't have to unhook and rehook using a different function.
	// This way we can easily control visibility through the eCommerce Options tab.
	if ( ! function_exists( 'woocommerce_upsell_display' ) ):
	function woocommerce_upsell_display( $posts_per_page = '-1', $columns = 3, $orderby = 'rand' ) {
		woocommerce_get_template( 'single-product/up-sells.php', array(
				'posts_per_page'  => $posts_per_page,
				'orderby'    => $orderby,
				'columns'    => $columns
			) );
	}
	endif;

endif; // woocommerce_enabled() ?>