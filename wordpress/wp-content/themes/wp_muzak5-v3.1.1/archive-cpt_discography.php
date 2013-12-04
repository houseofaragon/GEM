<?php get_header(); ?>
<?php get_template_part('inc_section'); ?>

<div class="row">			

	<?php if (ci_setting('discography_isotope') != 'enabled'): ?>
		<div class="sixteen columns">
	<?php endif; ?>
	
	<?php if (ci_setting('discography_isotope') == 'enabled'): ?>
		<ul class="filters-nav group">
			<li><a href="#filter" class="selected action-btn buy" data-filter="*"><?php _e('All Albums', 'ci_theme'); ?></a></li>
			<?php 
				$args = array('hide_empty' => 1);
				$skills = get_terms('section', $args); 
			?>	
			<?php foreach ( $skills as $skill ): ?>
				<li><a href="#filter" data-filter=".<?php echo $skill->slug; ?>" class="action-btn buy"><?php echo $skill->name; ?></a></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
	
	<ol class="discography group <?php if (ci_setting('discography_isotope') == 'enabled'): ?> filter-container filter-container-discography <?php endif; ?>">		
		<?php
			global $paged;
			if(ci_setting('discography_isotope')=='enabled')
			{
				query_posts(array(
					'post_type' => 'cpt_discography',
					'posts_per_page' => -1
				));
			}
			else
			{
				query_posts(array(
					'post_type' => 'cpt_discography',
					'posts_per_page' => ci_setting('discography_per_page'),
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
		?>

		<?php if (have_posts() ) : while (have_posts() ) : the_post(); ?>
			<?php
				$album_date	= explode("-",get_post_meta($post->ID, 'ci_cpt_discography_date', true));
				$sections = wp_get_object_terms($post->ID, 'section');
			?>
			
			<?php if (ci_setting('discography_isotope') == 'enabled'): ?>
				<li class="<?php echo ci_column_classes_isotope($cols, 16); ?> columns <?php foreach ( $sections as $section ) : echo $section->slug.' '; endforeach; ?>">
			<?php else: ?>
				<li class="<?php echo ci_column_classes($cols, 16); ?> columns">
			<?php endif; ?>	
			
				<div class="latest-item">
					<a href="<?php the_permalink(); ?>">	
						<?php
							$attr = array('class'=> "scale-with-grid");
							the_post_thumbnail('ci_discography', $attr);
						?>	
					</a>	
					<p class="album-info">
						<span class="sub-head"><?php _e('Release date:','ci_theme'); ?> <?php echo $album_date[2]; ?>-<?php echo ci_the_month($album_date[1]); ?>-<?php echo $album_date[0]; ?></span>
						<span class="main-head"><?php the_title(); ?></span>
						<span class="album-actions">
							<a href="<?php the_permalink(); ?>" class="action-btn buy"><?php _e('Learn more','ci_theme'); ?></a>
						</span>
					</p>	
				</div><!-- /latest-album -->
			</li>
		<?php endwhile; endif; ?>
	</ol><!-- /discography -->
	
	<?php ci_pagination(); ?>
	<?php wp_reset_query(); ?>
	
	<?php if (ci_setting('discography_isotope') != 'enabled'): ?>
		</div>
	<?php endif; ?>

</div><!-- /row -->		

<?php get_footer(); ?>