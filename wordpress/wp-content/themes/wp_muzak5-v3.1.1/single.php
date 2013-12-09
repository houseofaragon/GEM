<?php get_header(); ?>
<?php get_template_part('inc_section'); ?>

<div class="row">
	<div class="sixteen columns">				
		
		<div class="twelve columns content alpha">
		
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	
				<article <?php post_class('inner-post'); ?>>
				
					<div class="post-intro">
						<h2><a href="<?php the_permalink(); ?>" title="<?php echo __('Permalink to', 'ci_theme').' '.esc_attr(get_the_title()); ?>"><?php the_title(); ?></a></h2>							
						<?php the_post_thumbnail('ci_home_listing_long', array('class' => 'scale-with-grid')); ?>

					</div><!-- /intro -->
		
					<div class="post-body hyphenate group responsive-content">
						<p class="meta"><time class="post-date" datetime="<?php echo get_the_date('Y-m-d'); ?>"><?php echo get_the_date(); ?></time> <span class="bull">&bull;</span> <a href="<?php comments_link(); ?>"><?php comments_number(); ?></a></p>
						<?php the_content(); ?>
					</div>
	
					<?php comments_template(); ?> 
				</article><!-- /single -->
				
			<?php endwhile; endif; ?>
		
		</div><!-- /twelve columns -->
		
		<aside class="four columns sidebar omega">
			<?php dynamic_sidebar('blog-sidebar'); ?>
		</aside><!-- /sidebar -->
		
	</div><!-- /sixteen columns -->
</div><!-- /row -->		

<?php get_footer(); ?>