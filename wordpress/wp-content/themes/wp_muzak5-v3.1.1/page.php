<?php get_header(); ?>
<?php get_template_part('inc_section'); ?>

<div class="row">
	<div class="sixteen columns">
		
		<div class="twelve columns content alpha">
		
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	
				<article <?php post_class('static'); ?>>
		
					<div class="post-body hyphenate">
						<?php the_post_thumbnail('ci_fullwidth', array('class' => 'scale-with-grid img-fullwidth')); ?>
						<?php ci_e_content(); ?>
					</div>
	
					<?php comments_template(); ?> 
				</article><!-- /single -->
				
			<?php endwhile; endif; ?>
		
		</div><!-- /twelve columns -->
		
		<aside class="four columns sidebar omega">
			<?php
			if ( woocommerce_enabled() and ( is_cart() or is_checkout() or is_woocommerce() or is_account_page() ) ) {
				dynamic_sidebar('shop-sidebar');
			} else {
				dynamic_sidebar('pages-sidebar');
			}
			?>
		</aside><!-- /sidebar -->
		
	</div><!-- /sixteen columns -->
</div><!-- /row -->		

<?php get_footer(); ?>