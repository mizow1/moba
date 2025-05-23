<div class="sns_area">
    <ul class="sns_list">
        <li class="sns_item sns_item_fb"><a target="_blank" href="https://www.facebook.com/mastersofbeefassociation/"><i class="fa fa-facebook"></i></a></li>
        <li class="sns_item sns_item_tw"><a target="_blank" href="https://twitter.com/mastersofbeef/"><i class="fa fa-twitter"></i></a></li>
        <li class="sns_item sns_item_ig"><a target="_blank" href="https://www.instagram.com/masters_of_beef_association/"><i class="fa fa-instagram"></i></a></li>

        <?php
        global $usces;
        if (isset($usces) && $usces->get_total_quantity() >= 1) : ?>
            <li class="sns_item sns_item_cart">
                <a href="<?php echo esc_url(USCES_CART_URL); ?>">
                    <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/img/cart.svg'); ?>" alt="カート">
                    <?php if (! defined('WCEX_WIDGET_CART')) : ?>
                        <span class="cart-count">
                            <?php usces_totalquantity_in_cart(); ?>
                        </span>
                    <?php endif; ?>
                </a>
            </li>
        <?php endif; ?>

    </ul>
</div>