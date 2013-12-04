<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html <?php language_attributes(); ?>> <!--<![endif]-->
<head>

	<!-- Basic Page Needs
	================================================== -->
	<meta charset="utf-8">
	<title><?php ci_e_title(); ?></title>

	<!-- Mobile Specific Metas 
	================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<?php // JS files are loaded via /theme_functions/scripts.php ?>

	<?php // CSS files are loaded via /theme_functions/styles.php ?>

	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<?php get_template_part('inc_mobile_nav'); ?>

<div id="wrap">
	<div class="container">
		
		<!-- ########################### HEADER ########################### -->
		<header id="header" class="group">

			<hgroup id="logo" class="four columns <?php logo_class(); ?>">
				<?php ci_e_logo('<h1>', '</h1>'); ?>
			</hgroup>

			<nav id="nav" class="nav twelve columns">
				<?php 
					if(has_nav_menu('ci_main_menu'))
						wp_nav_menu( array(
							'theme_location' 	=> 'ci_main_menu',
							'fallback_cb' 		=> '',
							'container' 		=> '',
							'menu_id' 			=> 'navigation',
							'menu_class' 		=> 'sf-menu group'
						));
					else
						wp_page_menu();
				?>
			</nav><!-- /nav -->

		</header><!-- /header -->	