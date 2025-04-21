<?php
/**
 * Template Name: 新着情報一覧
 *
 * @package Welcart
 * @subpackage Welcart_Basic
 */

get_header();
?>

<div id="primary" class="site-content">
    <div id="content" role="main">
        <div class="entry-content">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <h1 class="page-title"><?php the_title(); ?></h1>
                <?php the_content(); ?>
            <?php endwhile; endif; ?>
            
            <?php 
            display_post_list(20, null, true);
            ?>
        </div>
    </div>
</div>

<?php
// get_sidebar( 'other' );
get_footer();
