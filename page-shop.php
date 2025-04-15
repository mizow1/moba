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
				
				// 商品一覧を取得するための引数
				$item_args = array(
					'cat' => $term_id,
					'posts_per_page' => $number,
					'paged' => get_query_var('paged') ? get_query_var('paged') : 1
				);
				
				// 商品クエリを実行
				$item_query = new WP_Query($item_args);
				
				// 商品がある場合
				if ($item_query->have_posts()) :
					?>
					<div class="item-list">
						<?php
						while ($item_query->have_posts()) :
							$item_query->the_post();
							usces_the_item(); // Welcart商品データ準備
							$post_id = get_the_ID();
							?>
							<article id="post-<?php echo $post_id; ?>" class="item-box">
								<a href="<?php the_permalink(); ?>">
									<div class="itemimg">
										<?php usces_the_itemImage(0, 300, 300); ?>
									</div>
									<div class="item-info-wrap">
										<div class="itemname"><?php usces_the_itemName(); ?></div>
										<div class="itemprice">
											<?php usces_the_firstPriceCr(); ?>
											<?php usces_guid_tax(); ?>
										</div>
									</div>
								</a>
							</article>
						<?php endwhile; ?>
					</div>

					<?php
					// ページネーション
					if ($item_query->max_num_pages > 1) : ?>
						<div class="pagination">
							<?php
							$big = 999999999;
							echo paginate_links(array(
								'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
								'format' => '?paged=%#%',
								'current' => max(1, get_query_var('paged')),
								'total' => $item_query->max_num_pages,
								'prev_text' => '&laquo;',
								'next_text' => '&raquo;'
							));
							?>
						</div>
					<?php endif; ?>

				<?php
				// 商品がない場合
				else : ?>
					<p class="no-items"><?php _e('商品が見つかりませんでした。', 'welcart'); ?></p>
				<?php
				endif;
				
				// クエリをリセット
				wp_reset_postdata();
				?>
			</div><!-- .item-list-container -->

		</div><!-- #content -->
	</div><!-- #primary -->

<?php
// get_sidebar( 'other' );
get_footer();
