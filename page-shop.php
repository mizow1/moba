<?php
/**
 * Page Tempalte
 *
 * @package Welcart
 * @subpackage Welcart_Basic
 */

get_header();
?>

	<div id="primary" class="site-content">
		<div id="content" role="main">
			<h1 class="page-title"><?php the_title(); ?></h1>

			<div class="item-list-container">
				<?php
				// 商品カテゴリIDを取得（デフォルトは全商品）
				$term_id = usces_get_cat_id('item');
				// 表示する商品数
				$number = 12;
				// 商品一覧パーツを呼び出し
				include locate_template('parts-item-list.php');
				?>
			</div><!-- .item-list-container -->

		</div><!-- #content -->
	</div><!-- #primary -->

<?php
// get_sidebar( 'other' );
get_footer();
