<?php

/**
 * Product Page Template
 *
 * @package Welcart
 * @subpackage Welcart_Basic
 */
echo "<!--";var_dump('templatename:wc_item_single.php');echo "-->";
get_header();
?>

<div id="primary" class="site-content item_single">
	<div id="content" role="main">

		<?php
		if (have_posts()) :
			the_post();
		?>

			<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

				<div class="item-header">
					<h1 class="item_page_title"><?php the_title(); ?></h1>
				</div><!-- .item-header -->

				<div class="storycontent">

					<?php
					usces_remove_filter();
					usces_the_item();
					usces_have_skus();
					?>

					<div id="itempage">

						<div id="img-box">
							<div class="itemimg">
								<a href="<?php usces_the_itemImageURL(0); ?>" <?php echo apply_filters('usces_itemimg_anchor_rel', null); ?>>
									<?php usces_the_itemImage(0, 335, 335, $post); ?>
								</a>
								<?php do_action('usces_theme_favorite_icon'); ?>
							</div>
							<?php
							$image_ids = usces_get_itemSubImageNums();
							if (! empty($image_ids)) :
							?>
								<div class="itemsubimg">
									<?php foreach ($image_ids as $image_id) : ?>
										<a href="<?php usces_the_itemImageURL($image_id); ?>" <?php echo apply_filters('usces_itemimg_anchor_rel', null); ?>>
											<?php usces_the_itemImage($image_id, 135, 135, $post); ?>
										</a>
									<?php endforeach; ?>
								</div>
							<?php
							endif;
							?>
						</div><!-- #img-box -->

						<div class="detail-box">
							<h2 class="item-name"><?php usces_the_itemName(); ?></h2>
							<div class="itemcode">(<?php usces_the_itemCode(); ?>)</div>
							<?php wel_campaign_message(); ?>
							<div class="item-description">
								<?php the_content(); ?>
							</div>

							<?php if ('continue' === wel_get_item_chargingtype()) : ?>
								<div class="field">
									<table class="dlseller">
										<tr>
											<th><?php esc_html_e('First Withdrawal Date', 'dlseller'); ?></th>
											<td><?php echo esc_html(dlseller_first_charging($post->ID)); ?></td>
										</tr>
										<?php if (0 < (int) $usces_item['dlseller_interval']) : ?>
											<tr>
												<th><?php esc_html_e('Contract Period', 'dlseller'); ?></th>
												<td>
													<?php echo esc_html($usces_item['dlseller_interval']); ?>
													<?php esc_html_e('month (Automatic Updates)', 'welcart_basic'); ?>
												</td>
											</tr>
										<?php endif; ?>
									</table>
								</div>
							<?php endif; ?>
						</div><!-- .detail-box -->

						<div class="item-info">
							<?php
							$item_custom = usces_get_item_custom($post->ID, 'list', 'return');
							if ($item_custom) :
								echo wp_kses_post($item_custom);
							endif;
							?>

							<form action="<?php echo esc_url(USCES_CART_URL); ?>" method="post">

								<?php do { ?>
									<div class="skuform">
										<?php if ('' !== usces_the_itemSkuDisp('return')) : ?>
											<div class="skuname"><?php usces_the_itemSkuDisp(); ?></div>
										<?php endif; ?>

										<?php do_action('usces_theme_item_single_before_options'); ?>

										<?php if (usces_is_options()) : ?>
											<dl class="item-option">
												<?php while (usces_have_options()) : ?>
													<dt><?php usces_the_itemOptName(); ?></dt>
													<dd><?php usces_the_itemOption(usces_getItemOptName(), ''); ?></dd>
												<?php endwhile; ?>
											</dl>
										<?php endif; ?>

										<?php usces_the_itemGpExp(); ?>

										<div class="field">
											<div class="zaikostatus"><?php esc_html_e('stock status', 'usces'); ?> : <?php usces_the_itemZaikoStatus(); ?></div>

											<?php if ('continue' === wel_get_item_chargingtype()) : ?>
												<div class="frequency">
													<span class="field_frequency"><?php dlseller_frequency_name($post->ID, 'amount'); ?></span>
												</div>
											<?php endif; ?>

											<div class="field_price">
												<?php if (usces_the_itemCprice('return') > 0) : ?>
													<span class="field_cprice"><?php usces_the_itemCpriceCr(); ?></span>
												<?php endif; ?>
												<?php usces_the_itemPriceCr(); ?><?php usces_guid_tax(); ?>
											</div>
											<?php usces_crform_the_itemPriceCr_taxincluded(); ?>
										</div>

										<?php if (! usces_have_zaiko()) : ?>
											<div class="itemsoldout"><?php echo apply_filters('usces_filters_single_sku_zaiko_message', __('At present we cannot deal with this product.', 'welcart_basic')); ?></div>
										<?php else : ?>
											<div class="c-box">
												<span class="quantity"><?php esc_html_e('Quantity', 'usces'); ?><?php usces_the_itemQuant(); ?></span><span class="unit"><?php usces_the_itemSkuUnit(); ?></span>
												<span class="cart-button"><?php usces_the_itemSkuButton('&#xf07a;&nbsp;&nbsp;' . __('Add to Shopping Cart', 'usces'), 0); ?></span>
											</div>
										<?php endif; ?>
										<div class="error_message"><?php usces_singleitem_error_message($post->ID, usces_the_itemSku('return')); ?></div>
									</div><!-- .skuform -->
								<?php } while (usces_have_skus()); ?>

								<?php do_action('usces_action_single_item_inform'); ?>
							</form>

							<?php do_action('usces_action_single_item_outform'); ?>

						</div><!-- .item-info -->

						<?php usces_assistance_item($post->ID, __('An article concerned', 'usces')); ?>

					</div><!-- #itemspage -->
				</div><!-- .storycontent -->
			</article>

		<?php else : ?>

			<p><?php esc_html_e('Sorry, no posts matched your criteria.', 'usces'); ?></p>

		<?php endif; ?>


		<h2 class="recommend_item">その他おすすめ商品</h2>
		<div class="item-list-container">
			<?php
			$number = 6; // 表示数
			$term_id = usces_get_cat_id('item');
			include locate_template('parts-item-list.php');
			?>
		</div><!-- .item-list-container -->

	</div><!-- #content -->
</div><!-- #primary -->



<?php
// get_sidebar();
get_footer();
