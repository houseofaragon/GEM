<?php if (ci_setting('slider_show') == 'enabled'): ?>

<!-- ########################### SLIDER ########################### -->
<div class="container slider">
	<div class="sixteen columns">
		<div class="flexslider">
			<ul class="slides">
		    
				<?php 
					global $post;
					$slider_no = ci_setting('slider-no');

					$slider = new WP_Query( array( 
						'post_type' => 'cpt_slider', 
						'posts_per_page' => $slider_no
					)); 
				?>

				<?php while ( $slider->have_posts() ) : $slider->the_post(); ?>
					<?php
						$img_id = false;
						$img_url = '';
						$img_id = get_post_thumbnail_id($post->ID);
						$img_info = wp_get_attachment_image_src($img_id, 'ci_home_slider');
						$img_url = $img_info[0];
						
						$slider_text = get_post_meta($post->ID, 'ci_cpt_slider_text', true);
						$slider_video = get_post_meta($post->ID, 'ci_cpt_slider_video', true);
					?>
					<?php if(!empty($img_url)): ?>
						<li>
							<?php $slider_link = get_post_meta($post->ID, 'ci_cpt_slider_url', true); ?>
		
							<?php if ($slider_link != ""): ?>
								<a href="<?php echo $slider_link; ?>">
							<?php endif; ?>
		
								<img src="<?php echo $img_url; ?>" alt="<?php the_title(); ?>" />					  
		
								<?php if ($slider_text != 1): ?>	
									<div class="slide-text">
										<h2><?php the_title(); ?></h2>
									</div>
								<?php endif; ?>
		
							<?php if ($slider_link != ""): ?></a><?php endif; ?>
						</li>
					<?php else: ?>
						<li class="video-slide">
							<?php echo wp_oembed_get($slider_video); ?>
						</li>			    
					<?php endif; ?>

				<?php endwhile; ?>
	
				<?php wp_reset_postdata(); ?>

			</ul>
		</div>
	</div><!-- /sixteen columns -->
</div><!-- /slider -->

<?php endif; ?>