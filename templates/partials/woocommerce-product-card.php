<?php
defined('ABSPATH') || exit;

/**
 * Product Grid card markup.
 *
 * @var \WC_Product $product
 * @var string      $prefix BEM prefix, e.g. elementrify-product-grid
 * @var string      $image_hover_effect none|zoom-in|zoom-out|jump|tilt
 * @var bool        $show_rating
 * @var bool        $show_short_desc
 * @var bool        $show_old_price
 * @var bool        $show_badges
 * @var bool        $show_wishlist
 * @var bool        $show_add_to_cart
 */

if (!isset($product) || !($product instanceof \WC_Product)) {
    return;
}

$prefix = isset($prefix) ? preg_replace('/[^a-z0-9\-_]/', '', (string) $prefix) : 'elementrify-product-grid';
if ($prefix === '') {
    $prefix = 'elementrify-product-grid';
}

$effect = isset($image_hover_effect) ? (string) $image_hover_effect : 'zoom-out';
$effect = preg_replace('/[^a-z\-]/', '', $effect);
if ($effect === '') {
    $effect = 'none';
}

$hover_modifier = $prefix . '__image-wrap--' . $effect;
$show_rating = !empty($show_rating);
$show_short_desc = !empty($show_short_desc);
$show_old_price = !empty($show_old_price);
$show_badges = !empty($show_badges);
$show_wishlist = !empty($show_wishlist);
$show_add_to_cart = !empty($show_add_to_cart);

$is_on_sale = $product->is_on_sale();
$is_featured = $product->is_featured();
$short_desc = $product->get_short_description();
$category_line = wc_get_product_category_list($product->get_id(), ' &bull; ', '', '');
$regular_price = $product->get_regular_price();
$sale_price = $product->get_sale_price();
$current_price = ($sale_price !== '') ? $sale_price : $product->get_price();
$rating = (float) $product->get_average_rating();
$rating_count = (int) $product->get_rating_count();
$permalink = $product->get_permalink();
?>
<article class="<?php echo esc_attr($prefix); ?>__card">
    <div class="<?php echo esc_attr($prefix); ?>__image-wrap <?php echo esc_attr($hover_modifier); ?>">
        <a href="<?php echo esc_url($permalink); ?>" class="<?php echo esc_attr($prefix); ?>__image-link">
            <?php echo wp_kses_post($product->get_image('woocommerce_thumbnail', ['class' => $prefix . '__image'])); ?>
        </a>
        <?php if ($show_badges && ($is_on_sale || $is_featured)) : ?>
            <div class="<?php echo esc_attr($prefix); ?>__badges">
                <?php if ($is_on_sale) : ?>
                    <span class="<?php echo esc_attr($prefix); ?>__badge <?php echo esc_attr($prefix); ?>__badge--sale"><?php esc_html_e('Sale', 'elementrify'); ?></span>
                <?php endif; ?>
                <?php if ($is_featured) : ?>
                    <span class="<?php echo esc_attr($prefix); ?>__badge <?php echo esc_attr($prefix); ?>__badge--new"><?php esc_html_e('Featured', 'elementrify'); ?></span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="<?php echo esc_attr($prefix); ?>__card-body">
        <?php if ($show_rating) : ?>
            <div class="<?php echo esc_attr($prefix); ?>__rating">
                <?php echo wp_kses_post(wc_get_rating_html($rating, $rating_count)); ?>
                <span class="<?php echo esc_attr($prefix); ?>__rating-value">(<?php echo esc_html(number_format_i18n($rating, 1)); ?>)</span>
            </div>
        <?php endif; ?>

        <h3 class="<?php echo esc_attr($prefix); ?>__title">
            <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($product->get_name()); ?></a>
        </h3>

        <?php if ($category_line !== '') : ?>
            <div class="<?php echo esc_attr($prefix); ?>__meta"><?php echo wp_kses_post($category_line); ?></div>
        <?php endif; ?>

        <?php if ($show_short_desc && $short_desc !== '') : ?>
            <div class="<?php echo esc_attr($prefix); ?>__desc"><?php echo esc_html(wp_trim_words(wp_strip_all_tags($short_desc), 7, '')); ?></div>
        <?php endif; ?>

        <div class="<?php echo esc_attr($prefix); ?>__price-row">
            <span class="<?php echo esc_attr($prefix); ?>__price-main">
                <span class="<?php echo esc_attr($prefix); ?>__price-current"><?php echo wp_kses_post(wc_price((float) $current_price)); ?></span>
                <?php if ($show_old_price && $regular_price !== '' && $sale_price !== '' && (float) $regular_price > (float) $sale_price) : ?>
                    <span class="<?php echo esc_attr($prefix); ?>__price-old"><?php echo wp_kses_post(wc_price((float) $regular_price)); ?></span>
                <?php endif; ?>
            </span>
            <?php if ($show_wishlist || $show_add_to_cart) : ?>
                <span class="<?php echo esc_attr($prefix); ?>__actions">
                    <?php if ($show_wishlist) : ?>
                        <a class="<?php echo esc_attr($prefix); ?>__icon-btn" href="<?php echo esc_url($permalink); ?>" aria-label="<?php esc_attr_e('View Product', 'elementrify'); ?>">
                            <i class="far fa-heart" aria-hidden="true"></i>
                        </a>
                    <?php endif; ?>
                    <?php if ($show_add_to_cart) : ?>
                        <a
                            class="<?php echo esc_attr($prefix); ?>__icon-btn <?php echo esc_attr($prefix); ?>__icon-btn--cart <?php echo esc_attr($prefix); ?>__cart-btn <?php echo esc_attr($product->supports('ajax_add_to_cart') ? 'add_to_cart_button ajax_add_to_cart' : ''); ?>"
                            href="<?php echo esc_url($product->add_to_cart_url()); ?>"
                            data-product_id="<?php echo esc_attr((string) $product->get_id()); ?>"
                            data-product_sku="<?php echo esc_attr($product->get_sku()); ?>"
                            data-quantity="1"
                            rel="nofollow"
                            aria-label="<?php echo esc_attr($product->add_to_cart_description()); ?>">
                            <i class="fas fa-shopping-cart" aria-hidden="true"></i>
                        </a>
                        <a class="<?php echo esc_attr($prefix); ?>__cart-tooltip" href="#"><?php esc_html_e('View cart', 'elementrify'); ?></a>
                    <?php endif; ?>
                </span>
            <?php endif; ?>
        </div>
    </div>
</article>
