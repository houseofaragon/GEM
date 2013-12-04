<?php get_header(); ?>
<?php get_template_part('inc_section'); ?>

<div class="row">

	<?php if (ci_setting('video_isotope') != 'enabled'): ?>
		<div class="sixteen columns">
	<?php endif; ?>

	<?php if (ci_setting('video_isotope') == 'enabled'): ?>
		<ul class="filters-nav group">
			<li><a href="#filter" class="selected action-btn buy" data-filter="*"><?php _e('All Videos', 'ci_theme'); ?></a></li>
			<?php 
				$args = array('hide_empty' => 1);
				$sections = get_terms('video-category', $args); 
			?>
			<?php foreach ( $sections as $section ): ?>
				<li><a href="#filter" data-filter=".<?php echo $section->slug; ?>" class="action-btn buy"><?php echo $section->name; ?></a></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<ol class="listing group <?php if (ci_setting('video_isotope') == 'enabled'): ?> filter-container filter-container-videos <?php endif; ?>">	
		<?php
			global $paged;
			if(ci_setting('video_isotope')=='enabled')
			{
				query_posts(array(
					'post_type' => 'cpt_videos',
					'posts_per_page' => -1
				));
			}
			else
			{
				query_posts(array(
					'post_type' => 'cpt_videos',
					'posts_per_page' => ci_setting('video_per_page'),
					'paged' => $paged
				));
			}
		?>

		<?php
			$cols = ci_setting('archive_tpl');
			if (ci_setting('artists_isotope') == 'enabled')
				ci_column_classes_isotope($cols, 16, true);
			else
				ci_column_classes($cols, 16, true);

			$i = 1;
		?>

		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<?php
				$video_url = get_post_meta($post->ID, 'ci_cpt_videos_url', true);
				$video_type = get_post_meta($post->ID, 'ci_cpt_videos_self', true);
				$sections = wp_get_object_terms($post->ID, 'video-category');
			?>

			<?php if (ci_setting('video_isotope') == 'enabled'): ?>
				<li class="<?php echo ci_column_classes_isotope($cols, 16); ?> columns <?php foreach ( $sections as $section ) : echo $section->slug.' '; endforeach; ?>">
			<?php else: ?>
				<li class="<?php echo ci_column_classes($cols, 16); ?> columns">
			<?php endif; ?>

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
	<?php wp_reset_query(); ?>

	<?php if (ci_setting('video_isotope') != 'enabled'): ?>
		</div>
	<?php endif; ?>

</div><!-- /row -->

<?php get_footer(); ?>