<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

// Increase loop count
$woocommerce_loop['loop']++;


// Extra post classes
$classes = array('columns', 'product', 'product-category');
if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] )
	$classes[] = 'alpha';
if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] )
	$classes[] = 'omega';


if ( $woocommerce_loop['columns']  == 4 )
	$classes[] = 'three';
elseif ( $woocommerce_loop['columns'] == 3 )
	$classes[] = 'four';
elseif ( $woocommerce_loop['columns'] == 2 )
	$classes[] = 'six';

?>
<li class="<?php echo implode(' ', $classes); ?>">

	<?php do_action( 'woocommerce_before_subcategory', $category ); ?>

	<div class="latest-item">

		<a href="<?php echo get_term_link( $category->slug, 'product_cat' ); ?>">
	
			<?php
				/**
				 * woocommerce_before_subcategory_title hook
				 *
				 * @hooked woocommerce_subcategory_thumbnail - 10
				 */
				do_action( 'woocommerce_before_subcategory_title', $category );
			?>
			<p class="album-info">

			<span class="main-head">
				<?php
					echo $category->name;
	
					if ( $category->count > 0 )
						echo apply_filters( 'woocommerce_subcategory_count_html', ' <mark class="count">(' . $category->count . ')</mark>', $category );
				?>
			</span>
	
			<?php
				/**
				 * woocommerce_after_subcategory_title hook
				 */
				do_action( 'woocommerce_after_subcategory_title', $category );
			?>
	
		</a>
		</p>

	</div><!-- .latest-item -->

	<?php do_action( 'woocommerce_after_subcategory', $category ); ?>

</li>