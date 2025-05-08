<?php
/**
 * Template Name: コラム一覧
 */

get_header(); ?>

<div id="primary" class="site-content">
    <div id="content" role="main">
        <?php
        display_post_list(10, 'column', true);
        ?>
    </div><!-- #content -->
</div><!-- #primary -->

<?php get_footer();
