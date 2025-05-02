<?php
// 商品一覧パーツ
// $number, $term_id などは呼び出し元で定義しておくこと
if (!isset($number)) $number = 12;
if (!isset($term_id)) $term_id = usces_get_cat_id('item');

$item_args = array(
    'cat' => $term_id,
    'posts_per_page' => $number,
    'paged' => get_query_var('paged') ? get_query_var('paged') : 1
);
$item_query = new WP_Query($item_args);

if ($item_query->have_posts()) :
?>
<div class="item-list">
    <?php while ($item_query->have_posts()) : $item_query->the_post(); usces_the_item(); $post_id = get_the_ID(); ?>
    <article id="post-<?php echo $post_id; ?>" class="item-box">
        <a href="<?php the_permalink(); ?>">
            <div class="itemimg">
                <?php usces_the_itemImage(0, 768, 768); ?>
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
    // ページネーション（必要なら呼び出し元で設置）
else :
?>
<p class="no-items"><?php _e('商品が見つかりませんでした。', 'welcart'); ?></p>
<?php
endif;
wp_reset_postdata();
