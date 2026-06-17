<?php
defined('ABSPATH') || exit;

/**
 * Product Grid card markup.
 *
 * @var \WC_Product $veloria_product
 * @var string      $veloria_prefix BEM prefix, e.g. veloria-product-grid
 * @var string      $veloria_image_hover_effect none|zoom-in|zoom-out|jump|tilt
 * @var bool        $veloria_show_rating
 * @var bool        $veloria_show_short_desc
 * @var bool        $veloria_show_old_price
 * @var bool        $veloria_show_badges
 * @var bool        $veloria_show_wishlist
 * @var bool        $veloria_show_add_to_cart
 */

if (!isset($veloria_product) || !($veloria_product instanceof \WC_Product)) {
    return;
}

$veloria_prefix = isset($veloria_prefix) ? preg_replace('/[^a-z0-9\-_]/', '', (string) $veloria_prefix) : 'veloria-product-grid';
if ($veloria_prefix === '') {
    $veloria_prefix = 'veloria-product-grid';
}

$veloria_effect = isset($veloria_image_hover_effect) ? (string) $veloria_image_hover_effect : 'zoom-out';
$veloria_effect = preg_replace('/[^a-z\-]/', '', $veloria_effect);
if ($veloria_effect === '') {
    $veloria_effect = 'none';
}

$veloria_hover_modifier = $veloria_prefix . '__image-wrap--' . $veloria_effect;
$veloria_show_rating = !empty($veloria_show_rating);
$veloria_show_short_desc = !empty($veloria_show_short_desc);
$veloria_show_old_price = !empty($veloria_show_old_price);
$veloria_show_badges = !empty($veloria_show_badges);
$veloria_show_wishlist = !empty($veloria_show_wishlist);
$veloria_show_add_to_cart = !empty($veloria_show_add_to_cart);

$veloria_is_on_sale = $veloria_product->is_on_sale();
$veloria_is_featured = $veloria_product->is_featured();
$veloria_short_desc = $veloria_product->get_short_description();
$veloria_category_line = wc_get_product_category_list($veloria_product->get_id(), ' &bull; ', '', '');
$veloria_regular_price = $veloria_product->get_regular_price();
$veloria_sale_price = $veloria_product->get_sale_price();
$veloria_current_price = ($veloria_sale_price !== '') ? $veloria_sale_price : $veloria_product->get_price();
$veloria_rating = (float) $veloria_product->get_average_rating();
$veloria_rating_count = (int) $veloria_product->get_rating_count();
$veloria_permalink = $veloria_product->get_permalink();
?>
<article class="<?php echo esc_attr($veloria_prefix); ?>__card">
    <div class="<?php echo esc_attr($veloria_prefix); ?>__image-wrap <?php echo esc_attr($veloria_hover_modifier); ?>">
        <a href="<?php echo esc_url($veloria_permalink); ?>" class="<?php echo esc_attr($veloria_prefix); ?>__image-link">
            <?php echo wp_kses_post($veloria_product->get_image('woocommerce_thumbnail', ['class' => $veloria_prefix . '__image'])); ?>
        </a>
        <?php if ($veloria_show_badges && ($veloria_is_on_sale || $veloria_is_featured)) : ?>
            <div class="<?php echo esc_attr($veloria_prefix); ?>__badges">
                <?php if ($veloria_is_on_sale) : ?>
                    <span class="<?php echo esc_attr($veloria_prefix); ?>__badge <?php echo esc_attr($veloria_prefix); ?>__badge--sale"><?php esc_html_e('Sale', 'veloria'); ?></span>
                <?php endif; ?>
                <?php if ($veloria_is_featured) : ?>
                    <span class="<?php echo esc_attr($veloria_prefix); ?>__badge <?php echo esc_attr($veloria_prefix); ?>__badge--new"><?php esc_html_e('Featured', 'veloria'); ?></span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="<?php echo esc_attr($veloria_prefix); ?>__card-body">
        <?php if ($veloria_show_rating) : ?>
            <div class="<?php echo esc_attr($veloria_prefix); ?>__rating">
                <?php echo wp_kses_post(wc_get_rating_html($veloria_rating, $veloria_rating_count)); ?>
                <span class="<?php echo esc_attr($veloria_prefix); ?>__rating-value">(<?php echo esc_html(number_format_i18n($veloria_rating, 1)); ?>)</span>
            </div>
        <?php endif; ?>

        <h3 class="<?php echo esc_attr($veloria_prefix); ?>__title">
            <a href="<?php echo esc_url($veloria_permalink); ?>"><?php echo esc_html($veloria_product->get_name()); ?></a>
        </h3>

        <?php if ($veloria_category_line !== '') : ?>
            <div class="<?php echo esc_attr($veloria_prefix); ?>__meta"><?php echo wp_kses_post($veloria_category_line); ?></div>
        <?php endif; ?>

        <?php if ($veloria_show_short_desc && $veloria_short_desc !== '') : ?>
            <div class="<?php echo esc_attr($veloria_prefix); ?>__desc"><?php echo esc_html(wp_trim_words(wp_strip_all_tags($veloria_short_desc), 7, '')); ?></div>
        <?php endif; ?>

        <div class="<?php echo esc_attr($veloria_prefix); ?>__price-row">
            <span class="<?php echo esc_attr($veloria_prefix); ?>__price-main">
                <span class="<?php echo esc_attr($veloria_prefix); ?>__price-current"><?php echo wp_kses_post(wc_price((float) $veloria_current_price)); ?></span>
                <?php if ($veloria_show_old_price && $veloria_regular_price !== '' && $veloria_sale_price !== '' && (float) $veloria_regular_price > (float) $veloria_sale_price) : ?>
                    <span class="<?php echo esc_attr($veloria_prefix); ?>__price-old"><?php echo wp_kses_post(wc_price((float) $veloria_regular_price)); ?></span>
                <?php endif; ?>
            </span>
            <?php if ($veloria_show_wishlist || $veloria_show_add_to_cart) : ?>
                <span class="<?php echo esc_attr($veloria_prefix); ?>__actions">
                    <?php if ($veloria_show_wishlist) : ?>
                        <a class="<?php echo esc_attr($veloria_prefix); ?>__icon-btn" href="<?php echo esc_url($veloria_permalink); ?>" aria-label="<?php esc_attr_e('View Product', 'veloria'); ?>">
                            <i class="far fa-heart" aria-hidden="true"></i>
                        </a>
                    <?php endif; ?>
                    <?php if ($veloria_show_add_to_cart) : ?>
                        <a
                            class="<?php echo esc_attr($veloria_prefix); ?>__icon-btn <?php echo esc_attr($veloria_prefix); ?>__icon-btn--cart <?php echo esc_attr($veloria_prefix); ?>__cart-btn <?php echo esc_attr($veloria_product->supports('ajax_add_to_cart') ? 'add_to_cart_button ajax_add_to_cart' : ''); ?>"
                            href="<?php echo esc_url($veloria_product->add_to_cart_url()); ?>"
                            data-product_id="<?php echo esc_attr((string) $veloria_product->get_id()); ?>"
                            data-product_sku="<?php echo esc_attr($veloria_product->get_sku()); ?>"
                            data-quantity="1"
                            rel="nofollow"
                            aria-label="<?php echo esc_attr($veloria_product->add_to_cart_description()); ?>">
                            <i class="fas fa-shopping-cart" aria-hidden="true"></i>
                        </a>
                        <a class="<?php echo esc_attr($veloria_prefix); ?>__cart-tooltip" href="#"><?php esc_html_e('View cart', 'veloria'); ?></a>
                    <?php endif; ?>
                </span>
            <?php endif; ?>
        </div>
    </div>
</article>
