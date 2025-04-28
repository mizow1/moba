<?php
/**
 * Template Name: コラム一覧
 */

get_header(); ?>

<div id="primary" class="site-content">
    <div id="content" role="main">
        <?php
        // ページネーション対応
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        // コラムカテゴリーの投稿を取得
        $args = array(
            'post_type'      => 'post',
            'category_name'  => 'column',    // カテゴリーのスラッグ
            'posts_per_page' => 10,
            'paged'          => $paged,
        );
        $column_query = new WP_Query($args);

        if ( $column_query->have_posts() ) : ?>

            <ul class="column-list">
            <?php while ( $column_query->have_posts() ) : $column_query->the_post(); ?>
                <li class="column-item">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <a href="<?php the_permalink(); ?>" class="column-thumb">
                            <?php the_post_thumbnail('thumbnail'); ?>
                        </a>
                    <?php endif; ?>
                    <div class="column-entry">
                        <h2 class="column-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <div class="column-meta">
                            <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date('Y.m.d'); ?></time>
                        </div>
                        <div class="column-excerpt">
                            <?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?>
                        </div>
                    </div>
                </li>
            <?php endwhile; ?>
            </ul>

            <?php
            // ページナビゲーション
            the_posts_pagination( array(
                'prev_text' => '« 前へ',
                'next_text' => '次へ »',
            ) );
        else : ?>
            <p>現在、コラムは投稿されていません。</p>
        <?php endif;
        wp_reset_postdata(); ?>

    </div><!-- #content -->
</div><!-- #primary -->

<?php get_footer();
