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
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content', get_post_format() );

				$defaults = array(
					'before'         => '<div class="link-pages">',
					'after'          => '</div>',
					'link_before'    => '',
					'link_after'     => '',
					'next_or_number' => 'number',
					'separator'      => ' ',
					'echo'           => 1,
				);
				wp_link_pages( $defaults );

				posts_nav_link( ' &#8212; ', __( '&laquo; Newer Posts' ), __( 'Older Posts &raquo;' ) );

			endwhile;
		else :
			?>

			<p><?php esc_html_e( 'Sorry, no posts matched your criteria.', 'usces' ); ?></p>

			<?php
		endif;
		?>

<?php 
if(is_single()){
	// 前後の記事への導線を表示
	// 投稿（post）のみに限定する
	if(get_post_type() === 'post' && function_exists('get_previous_post') && function_exists('get_next_post')){
		// 同じ投稿タイプ（post）のみを取得
		$prev_post = get_previous_post(true, '', 'category');
		$next_post = get_next_post(true, '', 'category');
		
		// 前後の投稿が存在する場合のみ表示
		if($prev_post || $next_post){
			?>
			<div class="post-navigation">
				<?php if($prev_post && get_post_type($prev_post->ID) === 'post'): ?>
				<div class="post-navigation-prev">
					<a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>" rel="prev">
						<span class="nav-label">前の記事</span>
						<span class="nav-title"><?php echo esc_html(get_the_title($prev_post->ID)); ?></span>
					</a>
				</div>
				<?php else: ?>
				<div class="post-navigation-prev empty"></div>
				<?php endif; ?>
				
				<?php if($next_post && get_post_type($next_post->ID) === 'post'): ?>
				<div class="post-navigation-next">
					<a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>" rel="next">
						<span class="nav-label">次の記事</span>
						<span class="nav-title"><?php echo esc_html(get_the_title($next_post->ID)); ?></span>
					</a>
				</div>
				<?php else: ?>
				<div class="post-navigation-next empty"></div>
				<?php endif; ?>
			</div>
			<?php
		}
	}
}
 ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php
// get_sidebar( 'other' );
get_footer();
