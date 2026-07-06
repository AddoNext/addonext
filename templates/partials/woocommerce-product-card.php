<?php
defined('ABSPATH') || exit;

/**
 * Product Grid card markup.
 *
 * @var \WC_Product $addonext_product
 * @var string      $addonext_prefix BEM prefix, e.g. addonext-product-grid
 * @var string      $addonext_image_hover_effect none|zoom-in|zoom-out|jump|tilt
 * @var bool        $addonext_show_rating
 * @var bool        $addonext_show_short_desc
 * @var bool        $addonext_show_old_price
 * @var bool        $addonext_show_badges
 * @var bool        $addonext_show_wishlist
 * @var bool        $addonext_show_add_to_cart
 */

if (!isset($addonext_product) || !($addonext_product instanceof \WC_Product)) {
    return;
}

$addonext_prefix = isset($addonext_prefix) ? preg_replace('/[^a-z0-9\-_]/', '', (string) $addonext_prefix) : 'addonext-product-grid';
if ($addonext_prefix === '') {
    $addonext_prefix = 'addonext-product-grid';
}

$addonext_effect = isset($addonext_image_hover_effect) ? (string) $addonext_image_hover_effect : 'zoom-out';
$addonext_effect = preg_replace('/[^a-z\-]/', '', $addonext_effect);
if ($addonext_effect === '') {
    $addonext_effect = 'none';
}

$addonext_hover_modifier = $addonext_prefix . '__image-wrap--' . $addonext_effect;
$addonext_show_rating = !empty($addonext_show_rating);
$addonext_show_short_desc = !empty($addonext_show_short_desc);
$addonext_show_old_price = !empty($addonext_show_old_price);
$addonext_show_badges = !empty($addonext_show_badges);
$addonext_show_wishlist = !empty($addonext_show_wishlist);
$addonext_show_add_to_cart = !empty($addonext_show_add_to_cart);

$addonext_is_on_sale = $addonext_product->is_on_sale();
$addonext_is_featured = $addonext_product->is_featured();
$addonext_short_desc = $addonext_product->get_short_description();
$addonext_category_line = wc_get_product_category_list($addonext_product->get_id(), ' &bull; ', '', '');
$addonext_regular_price = $addonext_product->get_regular_price();
$addonext_sale_price = $addonext_product->get_sale_price();
$addonext_current_price = ($addonext_sale_price !== '') ? $addonext_sale_price : $addonext_product->get_price();
$addonext_rating = (float) $addonext_product->get_average_rating();
$addonext_rating_count = (int) $addonext_product->get_rating_count();
$addonext_permalink = $addonext_product->get_permalink();
?>
<article class="<?php echo esc_attr($addonext_prefix); ?>__card">
    <div class="<?php echo esc_attr($addonext_prefix); ?>__image-wrap <?php echo esc_attr($addonext_hover_modifier); ?>">
        <a href="<?php echo esc_url($addonext_permalink); ?>" class="<?php echo esc_attr($addonext_prefix); ?>__image-link">
            <?php echo wp_kses_post($addonext_product->get_image('woocommerce_thumbnail', ['class' => $addonext_prefix . '__image'])); ?>
        </a>
        <?php if ($addonext_show_badges && ($addonext_is_on_sale || $addonext_is_featured)) : ?>
            <div class="<?php echo esc_attr($addonext_prefix); ?>__badges">
                <?php if ($addonext_is_on_sale) : ?>
                    <span class="<?php echo esc_attr($addonext_prefix); ?>__badge <?php echo esc_attr($addonext_prefix); ?>__badge--sale"><?php esc_html_e('Sale', 'addonext'); ?></span>
                <?php endif; ?>
                <?php if ($addonext_is_featured) : ?>
                    <span class="<?php echo esc_attr($addonext_prefix); ?>__badge <?php echo esc_attr($addonext_prefix); ?>__badge--new"><?php esc_html_e('Featured', 'addonext'); ?></span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="<?php echo esc_attr($addonext_prefix); ?>__card-body">
        <?php if ($addonext_show_rating) : ?>
            <div class="<?php echo esc_attr($addonext_prefix); ?>__rating">
                <?php echo wp_kses_post(wc_get_rating_html($addonext_rating, $addonext_rating_count)); ?>
                <span class="<?php echo esc_attr($addonext_prefix); ?>__rating-value">(<?php echo esc_html(number_format_i18n($addonext_rating, 1)); ?>)</span>
            </div>
        <?php endif; ?>

        <h3 class="<?php echo esc_attr($addonext_prefix); ?>__title">
            <a href="<?php echo esc_url($addonext_permalink); ?>"><?php echo esc_html($addonext_product->get_name()); ?></a>
        </h3>

        <?php if ($addonext_category_line !== '') : ?>
            <div class="<?php echo esc_attr($addonext_prefix); ?>__meta"><?php echo wp_kses_post($addonext_category_line); ?></div>
        <?php endif; ?>

        <?php if ($addonext_show_short_desc && $addonext_short_desc !== '') : ?>
            <div class="<?php echo esc_attr($addonext_prefix); ?>__desc"><?php echo esc_html(wp_trim_words(wp_strip_all_tags($addonext_short_desc), 7, '')); ?></div>
        <?php endif; ?>

        <div class="<?php echo esc_attr($addonext_prefix); ?>__price-row">
            <span class="<?php echo esc_attr($addonext_prefix); ?>__price-main">
                <span class="<?php echo esc_attr($addonext_prefix); ?>__price-current"><?php echo wp_kses_post(wc_price((float) $addonext_current_price)); ?></span>
                <?php if ($addonext_show_old_price && $addonext_regular_price !== '' && $addonext_sale_price !== '' && (float) $addonext_regular_price > (float) $addonext_sale_price) : ?>
                    <span class="<?php echo esc_attr($addonext_prefix); ?>__price-old"><?php echo wp_kses_post(wc_price((float) $addonext_regular_price)); ?></span>
                <?php endif; ?>
            </span>
            <?php if ($addonext_show_wishlist || $addonext_show_add_to_cart) : ?>
                <span class="<?php echo esc_attr($addonext_prefix); ?>__actions">
                    <?php if ($addonext_show_wishlist) : ?>
                        <a class="<?php echo esc_attr($addonext_prefix); ?>__icon-btn" href="<?php echo esc_url($addonext_permalink); ?>" aria-label="<?php esc_attr_e('View Product', 'addonext'); ?>">
                            <i class="far fa-heart" aria-hidden="true"></i>
                        </a>
                    <?php endif; ?>
                    <?php if ($addonext_show_add_to_cart) : ?>
                        <a
                            class="<?php echo esc_attr($addonext_prefix); ?>__icon-btn <?php echo esc_attr($addonext_prefix); ?>__icon-btn--cart <?php echo esc_attr($addonext_prefix); ?>__cart-btn <?php echo esc_attr($addonext_product->supports('ajax_add_to_cart') ? 'add_to_cart_button ajax_add_to_cart' : ''); ?>"
                            href="<?php echo esc_url($addonext_product->add_to_cart_url()); ?>"
                            data-product_id="<?php echo esc_attr((string) $addonext_product->get_id()); ?>"
                            data-product_sku="<?php echo esc_attr($addonext_product->get_sku()); ?>"
                            data-quantity="1"
                            rel="nofollow"
                            aria-label="<?php echo esc_attr($addonext_product->add_to_cart_description()); ?>">
                            <i class="fas fa-shopping-cart" aria-hidden="true"></i>
                        </a>
                        <a class="<?php echo esc_attr($addonext_prefix); ?>__cart-tooltip" href="#"><?php esc_html_e('View cart', 'addonext'); ?></a>
                    <?php endif; ?>
                </span>
            <?php endif; ?>
        </div>
    </div>
</article>
