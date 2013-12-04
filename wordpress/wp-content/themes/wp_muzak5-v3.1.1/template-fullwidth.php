<?php
/*
Template Name: Fullwidth
*/
?>

<?php get_header(); ?>
<?php get_template_part('inc_section'); ?>

<div class="row">
	<div class="sixteen columns">							
		<article class="post static">
			<div class="post-body hyphenate group">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<?php 
					if(ci_setting('featured_single_show')=='enabled') {
						the_post_thumbnail('ci_fullwidth', array('class' => 'scale-with-grid img-fullwidth'));
					}
				?>		
				<?php ci_e_content(); ?>
				<?php wp_link_pages(); ?>				    		                        
				<?php comments_template(); ?> 			                                                                                              
			<?php endwhile; endif; ?>                    
			</div><!-- /post-body -->
		</article>		
	</div><!-- /sixteen.columns -->       
</div><!-- /row -->
<?php get_footer(); ?>