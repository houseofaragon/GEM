<?php get_header(); ?>
<?php get_template_part('inc_section'); ?>

<div class="row">
	<div class="sixteen columns">

		<ol class="listing group">
			<?php
				$cols = ci_setting('archive_tpl');
				ci_column_classes($cols, 16, true);
			?>
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<?php
					$gal_location = get_post_meta($post->ID, 'ci_cpt_galleries_location', true);
					$gal_venue = get_post_meta($post->ID, 'ci_cpt_galleries_venue', true);
				 ?>
				<li class="<?php echo ci_column_classes($cols, 16); ?> columns">
					<div class="latest-item gallery-item">
						<a href="<?php the_permalink(); ?>">
							<?php
								$attr = array('class'=> "scale-with-grid");
								the_post_thumbnail('ci_media', $attr);
							?>
						</a>

						<p class="album-info">
							<span class="sub-head"><?php echo $gal_venue; ?></span>
							<span class="main-head"><?php echo $gal_location; ?></span>
							<span class="album-actions"><a href="<?php the_permalink(); ?>" class="action-btn view"><?php _e('View set','ci_theme'); ?></a></span>
						</p>
					</div><!-- /gallery-item -->
				</li>
			<?php endwhile; endif; ?>

		</ol><!-- /discography -->

		<?php ci_pagination(); ?>

	</div><!-- /sixteen columns -->
</div><!-- /row -->

<?php get_footer(); ?>