<?php get_header(); ?>
<?php get_template_part('inc_section'); ?>

<div class="row">

	<?php if (ci_setting('artists_isotope') != 'enabled'): ?>
		<div class="sixteen columns">
	<?php endif; ?>

	<?php if (ci_setting('artists_isotope') == 'enabled'): ?>
		<ul class="filters-nav group">
			<li><a href="#filter" class="selected action-btn buy" data-filter="*"><?php _e('All Artists', 'ci_theme'); ?></a></li>
			<?php 
				$args = array('hide_empty' => 1);
				$cats = get_terms('artist-category', $args); 
			?>
			<?php foreach ( $cats as $cat ): ?>
				<li><a href="#filter" data-filter=".<?php echo $cat->slug; ?>" class="action-btn buy"><?php echo $cat->name; ?></a></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
	
	<ol class="listing group <?php if (ci_setting('artists_isotope') == 'enabled'): ?> filter-container filter-container-artists <?php endif; ?>">		
		<?php
			global $paged;
			if(ci_setting('artists_isotope')=='enabled')
			{
				query_posts(array(
					'post_type' => 'cpt_artists',
					'posts_per_page' => -1
				));
			}
			else
			{
				query_posts(array(
					'post_type' => 'cpt_artists',
					'posts_per_page' => ci_setting('artists_per_page'),
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

		<?php if ( have_posts() ) : while ( have_posts() ) :the_post(); ?>
			<?php $cats = wp_get_object_terms($post->ID, 'artist-category'); ?>
			
			<?php if (ci_setting('artists_isotope') == 'enabled'): ?>
				<li class="<?php echo ci_column_classes_isotope($cols, 16); ?> columns <?php foreach ( $cats as $cat ) : echo $cat->slug.' '; endforeach; ?>">
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
	
	<?php if (ci_setting('artists_isotope') != 'enabled'): ?>
		</div>
	<?php endif; ?>

</div><!-- /row -->		

<?php get_footer(); ?>