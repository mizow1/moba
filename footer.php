<?php

/**
 * Footer Template
 *
 * @package Welcart
 * @subpackage Welcart_Basic
 */

?>

</div><!-- .main_inner -->
</div><!-- #main -->

<?php if (! wp_is_mobile()) : ?>

	<div id="toTop" class="wrap fixed"><a href="#masthead"><i class="fa fa-chevron-circle-up"></i></a></div>

<?php endif; ?>

<footer id="colophon" role="contentinfo">
	<div class="wrapper">


		<img src="<?php echo esc_url(get_stylesheet_directory_uri('/')); ?>/img/logo2.svg" alt="MASTERS OF BEEF ASSOCIATION" class="footer_logo">
		<nav id="site-info" class="footer-navigation">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer',
					'menu_class'     => 'footer-menu cf',
				)
			);
			?>
		</nav>
		<?php get_template_part('template-parts/sns'); ?>
	</div><!-- /.wrapper -->

	<p class="copyright"><?php usces_copyright(); ?></p>

</footer><!-- #colophon -->

<?php wp_footer(); ?>
</body>

</html>
<?php global $template;
$template_name = basename($template, '.php');
echo "<!--";
var_dump('templatename', $template_name);
echo "-->";
?>