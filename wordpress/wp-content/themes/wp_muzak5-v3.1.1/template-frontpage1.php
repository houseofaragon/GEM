<?php
/*
Template Name: Homepage (Sidebar #1 / Content / Sidebar #2)
*/
?>

<?php get_header(); ?>
<?php get_template_part('inc_slider'); ?>

<!-- ########################### MAIN ########################### -->
	<div class="row">
		<div class="sixteen columns">						
			
			<aside class="four columns alpha sidebar">
				<?php dynamic_sidebar('homepage-sidebar-one'); ?>
			</aside><!-- /sidebar -->

			<div class="eight columns content">

			<?php if (ci_setting('homepage-page-id') == ""): ?>

				<h3 class="widget-title"><?php _e('News','ci_theme'); ?></h3>
				<?php
                global $post;
                $paged = get_query_var('page') ? get_query_var('page') : 1;
                $args = array(
	            	'post_type'=>'post',
            		'paged' => $paged,
   					'posts_per_page' => ci_setting('news-no'),
					'cat' => ci_setting('news-cat')
            	);
                query_posts($args);
                if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<article class="post group">
					<div class="post-intro">
						<?php
							if(ci_setting('featured_single_show')=='enabled') {
								$attr = array('class'=> "scale-with-grid");
								the_post_thumbnail('ci_home_listing_short', $attr);
							}
						?>
						<h2><a href="<?php the_permalink(); ?>" title="<?php echo __('Permalink to', 'ci_theme').' '.esc_attr(get_the_title()); ?>"><?php the_title(); ?></a></h2>
					</div><!-- /intro -->
					<div class="post-body">
						<p class="meta"><time class="post-date" datetime="<?php echo get_the_date('Y-m-d'); ?>"><?php echo get_the_date(); ?></time> <span class="bull">&bull;</span> <a href="<?php comments_link(); ?>"><?php comments_number(); ?></a></p>
						<?php ci_e_content(); ?>
					</div>
				</article><!-- /post -->
				<?php endwhile; endif; ?>

                <?php ci_pagination(); wp_reset_query(); ?>                
                
            <?php else: ?>                                 
                 
                <?php
                $the_page = new WP_Query( 'page_id=' . ci_setting('homepage-page-id') );
                if ( $the_page->have_posts() ) : while ( $the_page->have_posts() ) : $the_page->the_post(); ?>
				<h3 class="widget-title"><?php the_title(); ?></h3>
				<article class="post group">
					<div class="post-intro">
						<?php
							if(ci_setting('featured_single_show')=='enabled') {
								$attr = array('class'=> "scale-with-grid");
								the_post_thumbnail('ci_home_listing_short', $attr);
							}
						?>						
					</div><!-- /intro -->
					<div class="post-body post-page-id">
						<?php the_content(); ?>
					</div>
				</article><!-- /post -->
				<?php endwhile; endif; wp_reset_query(); ?>
                	            
            <?php endif; ?>                

			</div>

			<aside class="four columns omega sidebar">
				<?php dynamic_sidebar('homepage-sidebar-two'); ?>
			</aside><!-- /sidebar -->

		</div><!-- /sixteen columns -->
	</div><!-- /row -->

<?php get_footer(); ?>