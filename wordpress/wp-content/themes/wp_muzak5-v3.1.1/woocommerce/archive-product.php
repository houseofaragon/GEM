<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header('shop'); ?>

	<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>

		<h3 class="section-title"><?php woocommerce_page_title(); ?></h3>

	<?php endif; ?>

	<div class="row">

		<div class="sixteen columns">

			<div class="twelve columns content alpha">

				<?php
					/**
					 * woocommerce_before_main_content hook
					 *
					 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
					 */
					do_action('woocommerce_before_main_content');
				?>

				<?php do_action( 'woocommerce_archive_description' ); ?>
		
				<?php if ( have_posts() ) : ?>
		
					<?php
						/**
						 * woocommerce_before_shop_loop hook
						 *
						 */
						do_action( 'woocommerce_before_shop_loop' );
					?>
		
					<?php woocommerce_product_loop_start(); ?>
		
						<?php woocommerce_product_subcategories(); ?>
		
						<?php while ( have_posts() ) : the_post(); ?>
		
							<?php woocommerce_get_template_part( 'content', 'product' ); ?>
		
						<?php endwhile; // end of the loop. ?>
		
					<?php woocommerce_product_loop_end(); ?>
		
					<?php
						/**
						 * woocommerce_after_shop_loop hook
						 *
						 * @hooked woocommerce_pagination - 10
						 */
						do_action( 'woocommerce_after_shop_loop' );
					?>
		
				<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>
		
					<?php woocommerce_get_template( 'loop/no-products-found.php' ); ?>
		
				<?php endif; ?>

				<?php
					/**
					 * woocommerce_after_main_content hook
					 *
					 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
					 */
					do_action('woocommerce_after_main_content');
				?>

			</div><!-- .twelve .columns .content .alpha -->
		
			<?php
				/**
				 * woocommerce_sidebar hook
				 *
				 * @hooked woocommerce_get_sidebar - 10
				 */
				do_action('woocommerce_sidebar');
			?>

		</div><!-- .sixteen .columns -->

	</div><!-- .row -->

<?php get_footer('shop'); ?>