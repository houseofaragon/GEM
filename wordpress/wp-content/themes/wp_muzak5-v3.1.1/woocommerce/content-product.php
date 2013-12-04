<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

// Ensure visibility
if ( ! $product->is_visible() )
	return;

// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array('columns', 'product');
if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] )
	$classes[] = 'alpha';
if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] )
	$classes[] = 'omega';


if ( $woocommerce_loop['columns']  == 4 )
	$classes[] = apply_filters('ci_product_item_columns_class', 'three');
elseif ( $woocommerce_loop['columns'] == 3 )
	$classes[] = apply_filters('ci_product_item_columns_class', 'four');
elseif ( $woocommerce_loop['columns'] == 2 )
	$classes[] = apply_filters('ci_product_item_columns_class', 'six');

?>
<li class="<?php echo implode(' ', $classes); ?>">
<?php /* <li <?php post_class( $classes ); ?>> */ ?>

	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

	<div class="latest-item">

		<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
			<?php
				woocommerce_show_product_loop_sale_flash();
				woocommerce_template_loop_product_thumbnail();
			?>
		</a>

		<p class="album-info">

			<span class="sub-head"><?php echo get_the_term_list($post->ID, 'product_cat', '', ', ', ''); ?></span>	

			<?php
				/**
				 * woocommerce_before_shop_loop_item_title hook
				 *
				 */
				do_action( 'woocommerce_before_shop_loop_item_title' );
			?>
	
			<span class="main-head"><?php the_title(); ?></span>
	
			<?php
				/**
				 * woocommerce_after_shop_loop_item_title hook
				 *
				 * @hooked woocommerce_template_loop_price - 10
				 */
				do_action( 'woocommerce_after_shop_loop_item_title' );
			?>
	

	</div><!-- .latest-item -->

	<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>

</li>