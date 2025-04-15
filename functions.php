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