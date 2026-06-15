<?php
defined('ABSPATH') || exit;

if (!class_exists('WooCommerce')) {
    echo '<div class="veloria-product-grid__placeholder">' . esc_html__('WooCommerce is required for Product Grid.', 'veloria') . '</div>';
    return;
}

$settings = $this->get_settings_for_display();

$parse_ids = static function ($raw): array {
    if (!is_string($raw) || trim($raw) === '') {
        return [];
    }
    $parts = array_map('trim', explode(',', $raw));
    $ids = array_map('absint', $parts);
    $ids = array_filter($ids, static function ($id) {
        return $id > 0;
    });
    return array_values(array_unique($ids));
};

$limit = isset($settings['limit']) ? max(1, (int) $settings['limit']) : 20;
$categories = [];
if (isset($settings['categories']) && is_array($settings['categories'])) {
    $categories = array_map('absint', $settings['categories']);
    $categories = array_values(array_filter($categories, static function ($termId) {
        return $termId > 0;
    }));
}
$includeIds = $parse_ids($settings['include_ids'] ?? '');
$excludeIds = $parse_ids($settings['exclude_ids'] ?? '');
$orderBy = isset($settings['orderby']) ? (string) $settings['orderby'] : 'date';
$order = isset($settings['order']) ? strtoupper((string) $settings['order']) : 'DESC';
$saleOnly = isset($settings['sale_only']) && $settings['sale_only'] === 'yes';
$featuredOnly = isset($settings['featured_only']) && $settings['featured_only'] === 'yes';
$stockStatus = isset($settings['stock_status']) ? (string) $settings['stock_status'] : 'all';

$queryArgs = [
    'post_type'           => 'product',
    'post_status'         => 'publish',
    'posts_per_page'      => $limit,
    'ignore_sticky_posts' => true,
    'no_found_rows'       => true,
];

$taxQuery = [];
if (!empty($categories)) {
    $taxQuery[] = [
        'taxonomy' => 'product_cat',
        'field'    => 'term_id',
        'terms'    => $categories,
        'operator' => 'IN',
    ];
}
if ($featuredOnly) {
    $taxQuery[] = [
        'taxonomy' => 'product_visibility',
        'field'    => 'name',
        'terms'    => ['featured'],
    ];
}
if (!empty($taxQuery)) {
    if (count($taxQuery) > 1) {
        $taxQuery['relation'] = 'AND';
    }
    $queryArgs['tax_query'] = $taxQuery;
}

$metaQuery = [];
if ($stockStatus !== 'all') {
    $metaQuery[] = [
        'key'   => '_stock_status',
        'value' => $stockStatus,
    ];
}
if (!empty($metaQuery)) {
    $queryArgs['meta_query'] = $metaQuery;
}

if ($saleOnly) {
    $saleIds = wc_get_product_ids_on_sale();
    $queryArgs['post__in'] = !empty($saleIds) ? $saleIds : [0];
}
if (!empty($includeIds)) {
    $queryArgs['post__in'] = isset($queryArgs['post__in'])
        ? array_values(array_intersect($queryArgs['post__in'], $includeIds))
        : $includeIds;
}
if (!empty($excludeIds)) {
    $queryArgs['post__not_in'] = $excludeIds;
}

switch ($orderBy) {
    case 'price':
        $queryArgs['orderby'] = 'meta_value_num';
        $queryArgs['meta_key'] = '_price';
        break;
    case 'popularity':
        $queryArgs['orderby'] = 'meta_value_num';
        $queryArgs['meta_key'] = 'total_sales';
        break;
    case 'rating':
        $queryArgs['orderby'] = 'meta_value_num';
        $queryArgs['meta_key'] = '_wc_average_rating';
        break;
    case 'title':
    case 'rand':
    case 'menu_order':
    case 'date':
        $queryArgs['orderby'] = $orderBy;
        break;
    default:
        $queryArgs['orderby'] = 'date';
        break;
}
$queryArgs['order'] = ($order === 'ASC') ? 'ASC' : 'DESC';

$productQuery = new \WP_Query($queryArgs);

$columns = isset($settings['columns']) ? max(1, min(6, (int) $settings['columns'])) : 4;
$columnsTablet = isset($settings['columns_tablet']) ? max(1, min(4, (int) $settings['columns_tablet'])) : 2;
$columnsMobile = isset($settings['columns_mobile']) ? max(1, min(2, (int) $settings['columns_mobile'])) : 1;
$columnGap = isset($settings['column_gap']) ? max(0, min(60, (int) $settings['column_gap'])) : 16;
$rowGap = isset($settings['row_gap']) ? max(0, min(60, (int) $settings['row_gap'])) : 16;

$showRating = isset($settings['show_rating']) && $settings['show_rating'] === 'yes';
$showShortDesc = isset($settings['show_short_desc']) && $settings['show_short_desc'] === 'yes';
$showOldPrice = isset($settings['show_old_price']) && $settings['show_old_price'] === 'yes';
$showBadges = isset($settings['show_badges']) && $settings['show_badges'] === 'yes';
$showWishlist = isset($settings['show_wishlist'])
    ? ($settings['show_wishlist'] === 'yes')
    : (isset($settings['show_actions']) && $settings['show_actions'] === 'yes');
$showAddToCart = isset($settings['show_add_to_cart'])
    ? ($settings['show_add_to_cart'] === 'yes')
    : (isset($settings['show_actions']) && $settings['show_actions'] === 'yes');
$hoverEffect = isset($settings['image_hover_effect']) ? (string) $settings['image_hover_effect'] : 'zoom-out';
$hoverClass = 'veloria-product-grid__image-wrap--' . preg_replace('/[^a-z\\-]/', '', $hoverEffect);

$cartUrl = '';
if (function_exists('wc_get_page_id')) {
    $cartPageId = (int) wc_get_page_id('cart');
    if ($cartPageId > 0) {
        $cartUrl = (string) get_permalink($cartPageId);
    }
}
if ($cartUrl === '' && function_exists('wc_get_cart_url')) {
    $cartUrl = (string) wc_get_cart_url();
}
$homeBase = untrailingslashit(home_url());
$cartBase = $cartUrl !== '' ? untrailingslashit($cartUrl) : '';
if ($cartBase === '' || $cartBase === $homeBase) {
    $cartUrl = '';
}

$gridShellStyle = sprintf(
    '--epg-cols-desktop:%d;--epg-cols-tablet:%d;--epg-cols-mobile:%d;--epg-col-gap:%dpx;--epg-row-gap:%dpx;',
    $columns,
    $columnsTablet,
    $columnsMobile,
    $columnGap,
    $rowGap
);
?>
<div
    class="veloria-product-grid-shell"
    style="<?php echo esc_attr($gridShellStyle); ?>"
    <?php echo $cartUrl !== '' ? 'data-cart-url="' . esc_url($cartUrl) . '"' : ''; ?>>
    <?php if ($productQuery->have_posts()) : ?>
        <div class="veloria-product-grid">
            <?php while ($productQuery->have_posts()) : $productQuery->the_post(); ?>
                <?php
                $product = wc_get_product(get_the_ID());
                if (!$product) {
                    continue;
                }

                $isOnSale = $product->is_on_sale();
                $isFeatured = $product->is_featured();
                $shortDesc = $product->get_short_description();
                $categoryLine = wc_get_product_category_list($product->get_id(), ' &bull; ', '', '');
                $regularPrice = $product->get_regular_price();
                $salePrice = $product->get_sale_price();
                $currentPrice = ($salePrice !== '') ? $salePrice : $product->get_price();
                $rating = (float) $product->get_average_rating();
                $ratingCount = (int) $product->get_rating_count();
                ?>
                <article class="veloria-product-grid__card">
                    <div class="veloria-product-grid__image-wrap <?php echo esc_attr($hoverClass); ?>">
                        <a href="<?php the_permalink(); ?>" class="veloria-product-grid__image-link">
                            <?php echo wp_kses_post($product->get_image('woocommerce_thumbnail', ['class' => 'veloria-product-grid__image'])); ?>
                        </a>
                        <?php if ($showBadges && ($isOnSale || $isFeatured)) : ?>
                            <div class="veloria-product-grid__badges">
                                <?php if ($isOnSale) : ?>
                                    <span class="veloria-product-grid__badge veloria-product-grid__badge--sale"><?php esc_html_e('Sale', 'veloria'); ?></span>
                                <?php endif; ?>
                                <?php if ($isFeatured) : ?>
                                    <span class="veloria-product-grid__badge veloria-product-grid__badge--new"><?php esc_html_e('Featured', 'veloria'); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="veloria-product-grid__card-body">
                        <?php if ($showRating) : ?>
                            <div class="veloria-product-grid__rating">
                                <?php echo wp_kses_post(wc_get_rating_html($rating, $ratingCount)); ?>
                                <span class="veloria-product-grid__rating-value">(<?php echo esc_html(number_format_i18n($rating, 1)); ?>)</span>
                            </div>
                        <?php endif; ?>

                        <h3 class="veloria-product-grid__title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>

                        <?php if ($categoryLine !== '') : ?>
                            <div class="veloria-product-grid__meta"><?php echo wp_kses_post($categoryLine); ?></div>
                        <?php endif; ?>

                        <?php if ($showShortDesc && $shortDesc !== '') : ?>
                            <div class="veloria-product-grid__desc"><?php echo esc_html(wp_trim_words(wp_strip_all_tags($shortDesc), 7, '')); ?></div>
                        <?php endif; ?>

                        <div class="veloria-product-grid__price-row">
                            <span class="veloria-product-grid__price-main">
                                <span class="veloria-product-grid__price-current"><?php echo wp_kses_post(wc_price((float) $currentPrice)); ?></span>
                                <?php if ($showOldPrice && $regularPrice !== '' && $salePrice !== '' && (float) $regularPrice > (float) $salePrice) : ?>
                                    <span class="veloria-product-grid__price-old"><?php echo wp_kses_post(wc_price((float) $regularPrice)); ?></span>
                                <?php endif; ?>
                            </span>
                            <?php if ($showWishlist || $showAddToCart) : ?>
                                <span class="veloria-product-grid__actions">
                                    <?php if ($showWishlist) : ?>
                                        <a class="veloria-product-grid__icon-btn" href="<?php the_permalink(); ?>" aria-label="<?php esc_attr_e('View Product', 'veloria'); ?>">
                                            <i class="far fa-heart" aria-hidden="true"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($showAddToCart) : ?>
                                        <a
                                            class="veloria-product-grid__icon-btn veloria-product-grid__icon-btn--cart veloria-product-grid__cart-btn <?php echo esc_attr($product->supports('ajax_add_to_cart') ? 'add_to_cart_button ajax_add_to_cart' : ''); ?>"
                                            href="<?php echo esc_url($product->add_to_cart_url()); ?>"
                                            data-product_id="<?php echo esc_attr((string) $product->get_id()); ?>"
                                            data-product_sku="<?php echo esc_attr($product->get_sku()); ?>"
                                            data-quantity="1"
                                            rel="nofollow"
                                            aria-label="<?php echo esc_attr($product->add_to_cart_description()); ?>">
                                            <i class="fas fa-shopping-cart" aria-hidden="true"></i>
                                        </a>
                                        <a class="veloria-product-grid__cart-tooltip" href="#"><?php esc_html_e('View cart', 'veloria'); ?></a>
                                    <?php endif; ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    <?php else : ?>
        <div class="veloria-product-grid__placeholder"><?php esc_html_e('No products found for selected filters.', 'veloria'); ?></div>
    <?php endif; ?>
</div>
<?php
wp_reset_postdata();
