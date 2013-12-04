<?php get_header(); ?>
<?php get_template_part('inc_section'); ?>

<div class="row">
	<div class="sixteen columns">				

		<?php
			if ( have_posts() ) : while ( have_posts() ) : the_post();
		?>
		
		<article <?php post_class('album group'); ?>>
		
			<div class="four columns alpha album-cover">
				<div id="latest-album" class="latest-item">
					<?php
						$large_id = get_post_thumbnail_id();
						$large_url = wp_get_attachment_image_src($large_id,'large', true);
					?>
					<a href="<?php echo $large_url[0]; ?>" data-rel="prettyPhoto">
					<?php
						$attr = array('class'=> "scale-with-grid");
						the_post_thumbnail('ci_discography', $attr);
					?>
					</a>
				</div><!-- /latest-album -->				
				<div id="single-sidebar">
					<?php dynamic_sidebar('artist-sidebar'); ?>
				</div>				
			</div><!-- /four columns -->
			
			<div class="twelve columns content omega group responsive-content">		
				<h2><?php the_title(); ?></h2>					
				<?php the_content(); ?>																
			</div><!-- /twelve columns -->			
		
		</article>
		
		<?php endwhile; endif; ?>
		
	</div><!-- /sixteen columns -->
</div><!-- /row -->		

<?php get_footer(); ?>