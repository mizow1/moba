<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
<style>
.fixed-header-nav {
    position: fixed !important;
    top: 0;
    left: 0;
    width: 100vw;
    z-index: 1000;
    background: rgba(29, 29, 29, 0.85);
    transition: background 0.3s, box-shadow 0.3s;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
</style>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width, user-scalable=no">
	<meta name="format-detection" content="telephone=no" />
	<title><?php moba_custom_title_tag(); ?></title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Shippori+Mincho&family=Zen+Old+Mincho&display=swap" rel="stylesheet">
	<script src="<?php echo esc_url(get_stylesheet_directory_uri('/')); ?>/js/flex_ratio.js"></script>
	<?php wp_head(); ?>
<script src="<?php echo esc_url(get_stylesheet_directory_uri('/')); ?>/js/header.js"></script>

</head>

<body <?php body_class(); ?>>

	<?php wp_body_open(); ?>

	<div class="header_bg"></div>
	<button class="hamburger" aria-label="メニューを開く/閉じる" aria-controls="site-navigation" aria-expanded="false">
    <span></span>
    <span></span>
    <span></span>
</button>
<header id="masthead" class="site-header" role="banner">

		<div class="inner cf">
			<?php get_template_part( 'template-parts/sns' ); ?>

			<?php if (is_home() || is_front_page()) : ?>
				<h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home">
						<img src="<?php echo esc_url(get_stylesheet_directory_uri('/')); ?>/img/logo.svg" alt="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>">
					</a></h1>
			<?php else : ?>
				<div class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home">
						<img src="<?php echo esc_url(get_stylesheet_directory_uri('/')); ?>/img/logo.svg" alt="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>">
					</a></div>
			<?php endif; ?>
			<div class="site-description">
				牛肉の新しい地平。
			</div>


		</div><!-- .inner -->




	</header><!-- #masthead -->

	<?php if (! welcart_basic_is_cart_page()) : ?>

		<nav id="site-navigation" class="main-navigation" role="navigation">
						<?php
			wp_nav_menu(
				array(
					'theme_location'  => 'header',
					'container_class' => 'nav-menu-open',
					'menu_class'      => 'header-nav-container cf',
				)
			);
			?>
		</nav><!-- #site-navigation -->

	<?php endif; ?>

	<?php
	if (is_front_page() || is_home() || welcart_basic_is_cart_page() || welcart_basic_is_member_page()) {
		$class = 'one-column';
	} else {
		$class = 'two-column right-set';
	}
	?>
	<div id="main" class="wrapper <?php echo esc_attr($class); ?>">
		<div class="main_inner">
			