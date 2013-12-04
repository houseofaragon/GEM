<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header('shop'); ?>

	<?php woocommerce_template_single_title(); ?>

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

					<?php while ( have_posts() ) : the_post(); ?>
			
						<?php woocommerce_get_template_part( 'content', 'single-product' ); ?>
			
					<?php endwhile; // end of the loop. ?>
		
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