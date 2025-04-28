<?php
/**
 * Moba functions and definitions
 */

// 子テーマ用のスタイルシートを読み込む
function moba_enqueue_styles() {
    // 親テーマのスタイルはstyle.cssで@importされているので、
    // ここではmoba.cssのみを読み込む
    wp_enqueue_style('moba-style', get_stylesheet_directory_uri() . '/css/moba.css', array(), '1.0');
}
add_action('wp_enqueue_scripts', 'moba_enqueue_styles', 20);


//svgアップロード可能にする
function allow_svg_uploads($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'allow_svg_uploads');


// 管理画面の投稿一覧を公開日降順に並び替える
function set_admin_post_order( $query ) {
    // 管理画面かつメインクエリの場合
    if ( is_admin() && $query->is_main_query() ) {
        // 投稿一覧画面の場合
        $screen = get_current_screen();
        if ( isset( $screen ) && $screen->base === 'edit' && $screen->post_type === 'post' ) {
            // orderbyパラメータが明示的に指定されていない場合のみ適用
            if ( !isset( $_GET['orderby'] ) ) {
                $query->set( 'orderby', 'date' );
                $query->set( 'order', 'DESC' );
            }
        }
    }
}
add_action( 'pre_get_posts', 'set_admin_post_order' );

/**
 * 投稿一覧を表示する関数
 *
 * @param int $posts_per_page 表示する投稿数（デフォルト: 4）
 * @param int|string|array $category カテゴリーID、スラッグ、または複数のカテゴリー（デフォルト: null = すべてのカテゴリー）
 * @param bool $show_pagination ページネーションを表示するかどうか（デフォルト: false）
 * @return void
 */
function display_post_list($posts_per_page = 4, $category = null, $show_pagination = false) {
    // 現在のページ番号を取得
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    
    // 引数の設定
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $posts_per_page,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
        'paged' => $paged
    );

    // カテゴリーが指定されている場合は追加
    if ($category) {
        $args['cat'] = $category;
    }
    
    // Welcart商品を除外
    // Welcart商品カテゴリーのIDを取得（例：'item'という名前のカテゴリー）
    $welcart_cat = get_term_by('slug', 'item', 'category');
    if ($welcart_cat) {
        // 除外するカテゴリーIDを設定
        if (!isset($args['category__not_in'])) {
            $args['category__not_in'] = array();
        }
        $args['category__not_in'][] = $welcart_cat->term_id;
    }
    
    // Welcart商品のカスタムフィールドを持つ投稿を除外
    $args['meta_query'] = array(
        'relation' => 'AND',
        array(
            'key' => '_itemCode',
            'compare' => 'NOT EXISTS'
        )
    );

    // クエリの実行
    $the_query = new WP_Query($args);

    // 投稿がある場合
    if ($the_query->have_posts()) :
        echo '<div class="post-list">';
        
        while ($the_query->have_posts()) : $the_query->the_post();
            echo '<div class="post-item">';
            
            // アイキャッチ画像
            if (has_post_thumbnail()) {
                echo '<div class="post-thumbnail">';
                echo '<a href="' . get_permalink() . '">';
                the_post_thumbnail('medium');
                echo '</a>';
                echo '</div>';
            }
            
            
            // 投稿日
            echo '<div class="post-date">' . get_the_date() . '</div>';
            
            // カテゴリー
            $categories = get_the_category();
            if (!empty($categories)) {
                echo '<div class="post-categories">';
                foreach ($categories as $category) {
                    echo '<span class="category-' . $category->slug . '">' . $category->name . '</span>';
                }
                echo '</div>';
            }
            
            // タイトル
            echo '<h3 class="post-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
            
            // 抜粋
            // echo '<div class="post-excerpt">';
            // echo wp_trim_words(get_the_excerpt(), 30, '...');
            // echo '</div>';
            
            echo '</div>';// .post-item
        endwhile;
        
        echo '</div>'; // .post-list
        
        // ページネーションの表示（フラグがtrueの場合のみ）
        if ($show_pagination && $the_query->max_num_pages > 1) {
            $big = 999999999; // need an unlikely integer
            echo '<div class="pagination">';
            echo paginate_links(array(
                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format' => '?paged=%#%',
                'current' => max(1, get_query_var('paged')),
                'total' => $the_query->max_num_pages,
                'prev_text' => '&laquo;',
                'next_text' => '&raquo;'
            ));
            echo '</div>';
        }
        
        // 元のクエリに戻す
        wp_reset_postdata();
    else :
        echo '<p>投稿がありません。</p>';
    endif;
}

// カテゴリー指定時に「未分類」を自動で外す
add_action('save_post', function($post_id) {
    // 投稿タイプと自動保存を除外
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (get_post_type($post_id) !== 'post') return;

    // 現在のカテゴリーIDを取得
    $cats = wp_get_post_categories($post_id);

    // 「未分類」カテゴリーのIDを取得（通常1だが念のため取得）
    $uncat_id = get_cat_ID('未分類');

    // 「未分類」以外が1つでも選択されている場合
    if (in_array($uncat_id, $cats) && count($cats) > 1) {
        // 「未分類」を外して再セット
        $new_cats = array_diff($cats, array($uncat_id));
        wp_set_post_categories($post_id, $new_cats);
    }
});



// デフォルト投稿URLを日付形式にする
function auto_set_post_slug( $data, $postarr ) {
    // 対象となる投稿タイプの配列
    $target_post_types = array('post', 'news','page');
    
    // 対象の投稿タイプかつ、新規投稿または自動生成されたスラッグの場合のみ適用
    if ( in_array($data['post_type'], $target_post_types) && 
        empty($data['post_name']) ) {
        
        // 日時形式（Y-m-d-H-i-s）でスラッグを設定
        $data['post_name'] = current_time('Y-m-d-H-i-s');
    }
    return $data;
}
add_filter( 'wp_insert_post_data', 'auto_set_post_slug', 10, 2 );
