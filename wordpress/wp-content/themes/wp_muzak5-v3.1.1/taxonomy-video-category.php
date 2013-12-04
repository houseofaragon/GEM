<?php get_header(); ?>
<?php get_template_part('inc_section'); ?>

<div class="row">
	<div class="sixteen columns">				
		
		<ol class="listing group">		
			<?php
				$cols = ci_setting('archive_tpl');
				ci_column_classes($cols, 16, true);
				$i = 1;
			?>

			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<?php
					$video_url 	= get_post_meta($post->ID, 'ci_cpt_videos_url', true);
					$video_type = get_post_meta($post->ID, 'ci_cpt_videos_self', true);
				?>		
				<li class="<?php echo ci_column_classes($cols, 16); ?> columns">
					<div class="latest-item latest-video">
						<a href="<?php echo ( $video_type == 1 ? "#player".$i."-wrap" : $video_url); ?>" data-rel="prettyPhoto">
							<?php
								$attr = array('class'=> "scale-with-grid");
								the_post_thumbnail('ci_media', $attr);
							?>
							<span></span>
						</a>	
	
						<p class="album-info">
							<span class="sub-head"><?php the_title(); ?></span>
						</p>
	
						<?php if ($video_type == 1): ?>
							<div id="player<?php echo $i; ?>-wrap" class="video-player">
								<div id="player<?php echo $i; ?>"><?php _e('Loading the player...', 'ci_theme'); ?></div>
								<script type="text/javascript">
									setupvjw('player<?php echo $i; ?>','<?php echo $video_url; ?>');
								</script>
							</div><!-- /player-wrapp -->
						<?php endif; ?>	
					</div><!-- /latest-album -->
				</li>
			<?php $i++; endwhile; endif; ?>

		</ol><!-- /discography -->

		<?php ci_pagination(); ?>

	</div><!-- /sixteen columns -->
</div><!-- /row -->

<?php get_footer(); ?>