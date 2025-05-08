<?php
/**
 * Category Template
 *
 * @package Welcart
 * @subpackage Welcart_Basic
 */

// 投稿リストデザインを適用したいカテゴリーのスラッグ一覧
$post_category_slugs = array('column', 'news'); // 記事用の一覧デザインを適用したい記事カテゴリーのスラッグを追記する

get_header();
?>

<section id="primary" class="site-content">
    <div id="content" role="main">


        <?php
        // 投稿リストデザインを適用するカテゴリー（column, newsなど）
        if (is_category($post_category_slugs)) {
            $cat = get_queried_object();
            echo '<h1 class="page-title">' . esc_html($cat->name) . '</h1>';
            if (isset($cat->term_id)) {
                display_post_list(10, $cat->term_id, true);
            }
            // ページネーションはdisplay_post_list内で出力するので、ここでは何も出さない
        } 
        // Welcart商品カテゴリーの場合
        else if (usces_is_cat_of_item(get_query_var('cat'))) {
            if (have_posts()) {
                ?>
                <div class="cat-il type-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <div class="itemimg">
                            <a href="<?php the_permalink(); ?>">
                                <?php usces_the_itemImage(0, 300, 300); ?>
                                <?php do_action('usces_theme_favorite_icon'); ?>
                            </a>
                            <?php welcart_basic_campaign_message(); ?>
                        </div>
                        <div class="itemprice">
                            <?php usces_the_firstPriceCr(); ?><?php usces_guid_tax(); ?>
                        </div>
                        <?php usces_crform_the_itemPriceCr_taxincluded(); ?>
                        <?php if (!usces_have_zaiko_anyone()) : ?>
                            <div class="itemsoldout">
                                <?php welcart_basic_soldout_label(get_the_ID()); ?>
                            </div>
                        <?php endif; ?>
                        <div class="itemname"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php usces_the_itemName(); ?></a></div>
                    </article>
                <?php endwhile; ?>
                </div><!-- .cat-il -->
                <?php
            }
        } 
        // その他のカテゴリー（デフォルト表示）
        else {
            if (have_posts()) {
                ?>
                <div class="post-li">
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <p><time datetime="<?php the_time('c'); ?>"><?php the_time(__('Y/m/d')); ?></time></p>
                        <div class="post-title">
                            <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php printf(esc_attr__('Permalink to %s', 'welcart_basic'), the_title_attribute('echo=0')); ?>">
                                <?php the_title(); ?>
                            </a>
                        </div>
                        <?php the_excerpt(); ?>
                    </article>
                <?php endwhile; ?>
                </div><!-- .post-li -->
                <?php
            }
        }
        ?>

        <?php
        // Welcart商品カテゴリー、通常記事カテゴリーの場合のみページネーションを出力
        if (!is_category($post_category_slugs)) : ?>
        <div class="pagination_wrapper">
            <?php
            $args = array(
                'type'      => 'list',
                'prev_text' => __(' &laquo; ', 'welcart_basic'),
                'next_text' => __(' &raquo; ', 'welcart_basic'),
            );
            echo paginate_links($args);
            ?>
        </div><!-- .pagenation-wrapper -->
        <?php endif; ?>

    </div><!-- #content -->
</section><!-- #primary -->

<?php
// get_sidebar();
get_footer();
