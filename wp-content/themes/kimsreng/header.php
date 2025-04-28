<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Kimsreng
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'kimsreng' ); ?></a>


    <div class="annountcement-bar">
		<div class="container">
			<div class="row">
				<div class="col-md-4 d-flex justify-content-start">
					<ul class="annountcement-bar__list">
						<li>
							<i class="bi bi-telephone rounded-circle"></i>
							<a href="tel:012345678" class="text-decoration-none">+0122345678</a>
						</li>
						<li>
							<i class="bi bi-envelope rounded-circle"></i>
							<a href="mailto:natkimsreng@gmail.com" class="text-decoration-none">natkimsreng@gmail.com</a>
						</li>
					</ul>
				</div>
				<div class="col-md-8 d-flex justify-content-end">
				<ul class="annountcement-bar__list">
						<li>
							<i class="bi bi-truck rounded-circle"></i>Free Shipping
						</li>
						<li>
							<i class="bi bi-clock-history rounded-circle"></i>30 Days Return
						</li>
						<li>
							<i class="bi bi-person rounded-circle"></i>24/7 Support
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<header id="masthead" class="site-header">
	    <div class="container pt-2 pb-2">
			<div class="row align-items-center">
				<div class="col site-header__logo d-flex justify-content-center justify-content-md-start">
					<?php the_custom_logo(); ?>
				</div>
			</div>
		</div>
		<div class="site-branding">

		</div><!-- .site-branding -->

		<nav id="site-navigation" class="main-navigation bg-primary">
			<div class="container d-felx justify-content-center">
			<div class="row">
				<div class="col-12 d-flex justify-content-center">
				<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
					<i class="bi bi-list"></i>
				    <?php esc_html_e( 'Primary Menu', 'kimsreng' ); ?>
			    </button>
				
				</div>
				<div class="col-12 text-center d-flex justify-content-center">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'menu-1',
							'menu_id'        => 'primary-menu',
						)
					);
					?>
				</div>
			</div>
			</div>
		</nav><!-- #site-navigation -->
	</header><!-- #masthead -->
