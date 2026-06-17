<?php
defined('ABSPATH') || exit;

if (!class_exists('WooCommerce')) {
    echo '<div class="veloria-product-grid__placeholder">' . esc_html__('WooCommerce is required for Product Grid.', 'veloria') . '</div>';
    return;
}

$veloria_settings = $this->get_settings_for_display();

$veloria_parse_ids = static function ($veloria_raw): array {
    if (!is_string($veloria_raw) || trim($veloria_raw) === '') {
        return [];
    }
    $veloria_parts = array_map('trim', explode(',', $veloria_raw));
    $veloria_ids = array_map('absint', $veloria_parts);
    $veloria_ids = array_filter($veloria_ids, static function ($veloria_id) {
        return $veloria_id > 0;
    });
    return array_values(array_unique($veloria_ids));
};

$veloria_limit = isset($veloria_settings['limit']) ? max(1, (int) $veloria_settings['limit']) : 20;
$veloria_categories = [];
if (isset($veloria_settings['categories']) && is_array($veloria_settings['categories'])) {
    $veloria_categories = array_map('absint', $veloria_settings['categories']);
    $veloria_categories = array_values(array_filter($veloria_categories, static function ($veloria_termId) {
        return $veloria_termId > 0;
    }));
}
$veloria_includeIds = $veloria_parse_ids($veloria_settings['include_ids'] ?? '');
$veloria_excludeIds = $veloria_parse_ids($veloria_settings['exclude_ids'] ?? '');
$veloria_orderBy = isset($veloria_settings['orderby']) ? (string) $veloria_settings['orderby'] : 'date';
$veloria_order = isset($veloria_settings['order']) ? strtoupper((string) $veloria_settings['order']) : 'DESC';
$veloria_saleOnly = isset($veloria_settings['sale_only']) && $veloria_settings['sale_only'] === 'yes';
$veloria_featuredOnly = isset($veloria_settings['featured_only']) && $veloria_settings['featured_only'] === 'yes';
$veloria_stockStatus = isset($veloria_settings['stock_status']) ? (string) $veloria_settings['stock_status'] : 'all';

$veloria_queryArgs = [
    'post_type'           => 'product',
    'post_status'         => 'publish',
    'posts_per_page'      => $veloria_limit,
    'ignore_sticky_posts' => true,
    'no_found_rows'       => true,
];

$veloria_taxQuery = [];
if (!empty($veloria_categories)) {
    $veloria_taxQuery[] = [
        'taxonomy' => 'product_cat',
        'field'    => 'term_id',
        'terms'    => $veloria_categories,
        'operator' => 'IN',
    ];
}
if ($veloria_featuredOnly) {
    $veloria_taxQuery[] = [
        'taxonomy' => 'product_visibility',
        'field'    => 'name',
        'terms'    => ['featured'],
    ];
}
if (!empty($veloria_taxQuery)) {
    if (count($veloria_taxQuery) > 1) {
        $veloria_taxQuery['relation'] = 'AND';
    }
    $veloria_queryArgs['tax_query'] = $veloria_taxQuery;
}

$veloria_metaQuery = [];
if ($veloria_stockStatus !== 'all') {
    $veloria_metaQuery[] = [
        'key'   => '_stock_status',
        'value' => $veloria_stockStatus,
    ];
}
if (!empty($veloria_metaQuery)) {
    $veloria_queryArgs['meta_query'] = $veloria_metaQuery;
}

if ($veloria_saleOnly) {
    $veloria_saleIds = wc_get_product_ids_on_sale();
    $veloria_queryArgs['post__in'] = !empty($veloria_saleIds) ? $veloria_saleIds : [0];
}
if (!empty($veloria_includeIds)) {
    $veloria_queryArgs['post__in'] = isset($veloria_queryArgs['post__in'])
        ? array_values(array_intersect($veloria_queryArgs['post__in'], $veloria_includeIds))
        : $veloria_includeIds;
}
if (!empty($veloria_excludeIds)) {
    $veloria_queryArgs['post__not_in'] = $veloria_excludeIds;
}

switch ($veloria_orderBy) {
    case 'price':
        $veloria_queryArgs['orderby'] = 'meta_value_num';
        $veloria_queryArgs['meta_key'] = '_price';
        break;
    case 'popularity':
        $veloria_queryArgs['orderby'] = 'meta_value_num';
        $veloria_queryArgs['meta_key'] = 'total_sales';
        break;
    case 'rating':
        $veloria_queryArgs['orderby'] = 'meta_value_num';
        $veloria_queryArgs['meta_key'] = '_wc_average_rating';
        break;
    case 'title':
    case 'rand':
    case 'menu_order':
    case 'date':
        $veloria_queryArgs['orderby'] = $veloria_orderBy;
        break;
    default:
        $veloria_queryArgs['orderby'] = 'date';
        break;
}
$veloria_queryArgs['order'] = ($veloria_order === 'ASC') ? 'ASC' : 'DESC';

$veloria_productQuery = new \WP_Query($veloria_queryArgs);

$veloria_columns = isset($veloria_settings['columns']) ? max(1, min(6, (int) $veloria_settings['columns'])) : 4;
$veloria_columnsTablet = isset($veloria_settings['columns_tablet']) ? max(1, min(4, (int) $veloria_settings['columns_tablet'])) : 2;
$veloria_columnsMobile = isset($veloria_settings['columns_mobile']) ? max(1, min(2, (int) $veloria_settings['columns_mobile'])) : 1;
$veloria_columnGap = isset($veloria_settings['column_gap']) ? max(0, min(60, (int) $veloria_settings['column_gap'])) : 16;
$veloria_rowGap = isset($veloria_settings['row_gap']) ? max(0, min(60, (int) $veloria_settings['row_gap'])) : 16;

$veloria_showRating = isset($veloria_settings['show_rating']) && $veloria_settings['show_rating'] === 'yes';
$veloria_showShortDesc = isset($veloria_settings['show_short_desc']) && $veloria_settings['show_short_desc'] === 'yes';
$veloria_showOldPrice = isset($veloria_settings['show_old_price']) && $veloria_settings['show_old_price'] === 'yes';
$veloria_showBadges = isset($veloria_settings['show_badges']) && $veloria_settings['show_badges'] === 'yes';
$veloria_showWishlist = isset($veloria_settings['show_wishlist'])
    ? ($veloria_settings['show_wishlist'] === 'yes')
    : (isset($veloria_settings['show_actions']) && $veloria_settings['show_actions'] === 'yes');
$veloria_showAddToCart = isset($veloria_settings['show_add_to_cart'])
    ? ($veloria_settings['show_add_to_cart'] === 'yes')
    : (isset($veloria_settings['show_actions']) && $veloria_settings['show_actions'] === 'yes');
$veloria_hoverEffect = isset($veloria_settings['image_hover_effect']) ? (string) $veloria_settings['image_hover_effect'] : 'zoom-out';
$veloria_hoverClass = 'veloria-product-grid__image-wrap--' . preg_replace('/[^a-z\\-]/', '', $veloria_hoverEffect);

$veloria_cartUrl = '';
if (function_exists('wc_get_page_id')) {
    $veloria_cartPageId = (int) wc_get_page_id('cart');
    if ($veloria_cartPageId > 0) {
        $veloria_cartUrl = (string) get_permalink($veloria_cartPageId);
    }
}
if ($veloria_cartUrl === '' && function_exists('wc_get_cart_url')) {
    $veloria_cartUrl = (string) wc_get_cart_url();
}
$veloria_homeBase = untrailingslashit(home_url());
$veloria_cartBase = $veloria_cartUrl !== '' ? untrailingslashit($veloria_cartUrl) : '';
if ($veloria_cartBase === '' || $veloria_cartBase === $veloria_homeBase) {
    $veloria_cartUrl = '';
}

$veloria_gridShellStyle = sprintf(
    '--epg-cols-desktop:%d;--epg-cols-tablet:%d;--epg-cols-mobile:%d;--epg-col-gap:%dpx;--epg-row-gap:%dpx;',
    $veloria_columns,
    $veloria_columnsTablet,
    $veloria_columnsMobile,
    $veloria_columnGap,
    $veloria_rowGap
);
?>
<div
    class="veloria-product-grid-shell"
    style="<?php echo esc_attr($veloria_gridShellStyle); ?>"
    <?php echo $veloria_cartUrl !== '' ? 'data-cart-url="' . esc_url($veloria_cartUrl) . '"' : ''; ?>>
    <?php if ($veloria_productQuery->have_posts()) : ?>
        <div class="veloria-product-grid">
            <?php while ($veloria_productQuery->have_posts()) : $veloria_productQuery->the_post(); ?>
                <?php
                $veloria_product = wc_get_product(get_the_ID());
                if (!$veloria_product) {
                    continue;
                }

                $veloria_isOnSale = $veloria_product->is_on_sale();
                $veloria_isFeatured = $veloria_product->is_featured();
                $veloria_shortDesc = $veloria_product->get_short_description();
                $veloria_categoryLine = wc_get_product_category_list($veloria_product->get_id(), ' &bull; ', '', '');
                $veloria_regularPrice = $veloria_product->get_regular_price();
                $veloria_salePrice = $veloria_product->get_sale_price();
                $veloria_currentPrice = ($veloria_salePrice !== '') ? $veloria_salePrice : $veloria_product->get_price();
                $veloria_rating = (float) $veloria_product->get_average_rating();
                $veloria_ratingCount = (int) $veloria_product->get_rating_count();
                ?>
                <article class="veloria-product-grid__card">
                    <div class="veloria-product-grid__image-wrap <?php echo esc_attr($veloria_hoverClass); ?>">
                        <a href="<?php the_permalink(); ?>" class="veloria-product-grid__image-link">
                            <?php echo wp_kses_post($veloria_product->get_image('woocommerce_thumbnail', ['class' => 'veloria-product-grid__image'])); ?>
                        </a>
                        <?php if ($veloria_showBadges && ($veloria_isOnSale || $veloria_isFeatured)) : ?>
                            <div class="veloria-product-grid__badges">
                                <?php if ($veloria_isOnSale) : ?>
                                    <span class="veloria-product-grid__badge veloria-product-grid__badge--sale"><?php esc_html_e('Sale', 'veloria'); ?></span>
                                <?php endif; ?>
                                <?php if ($veloria_isFeatured) : ?>
                                    <span class="veloria-product-grid__badge veloria-product-grid__badge--new"><?php esc_html_e('Featured', 'veloria'); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="veloria-product-grid__card-body">
                        <?php if ($veloria_showRating) : ?>
                            <div class="veloria-product-grid__rating">
                                <?php echo wp_kses_post(wc_get_rating_html($veloria_rating, $veloria_ratingCount)); ?>
                                <span class="veloria-product-grid__rating-value">(<?php echo esc_html(number_format_i18n($veloria_rating, 1)); ?>)</span>
                            </div>
                        <?php endif; ?>

                        <h3 class="veloria-product-grid__title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>

                        <?php if ($veloria_categoryLine !== '') : ?>
                            <div class="veloria-product-grid__meta"><?php echo wp_kses_post($veloria_categoryLine); ?></div>
                        <?php endif; ?>

                        <?php if ($veloria_showShortDesc && $veloria_shortDesc !== '') : ?>
                            <div class="veloria-product-grid__desc"><?php echo esc_html(wp_trim_words(wp_strip_all_tags($veloria_shortDesc), 7, '')); ?></div>
                        <?php endif; ?>

                        <div class="veloria-product-grid__price-row">
                            <span class="veloria-product-grid__price-main">
                                <span class="veloria-product-grid__price-current"><?php echo wp_kses_post(wc_price((float) $veloria_currentPrice)); ?></span>
                                <?php if ($veloria_showOldPrice && $veloria_regularPrice !== '' && $veloria_salePrice !== '' && (float) $veloria_regularPrice > (float) $veloria_salePrice) : ?>
                                    <span class="veloria-product-grid__price-old"><?php echo wp_kses_post(wc_price((float) $veloria_regularPrice)); ?></span>
                                <?php endif; ?>
                            </span>
                            <?php if ($veloria_showWishlist || $veloria_showAddToCart) : ?>
                                <span class="veloria-product-grid__actions">
                                    <?php if ($veloria_showWishlist) : ?>
                                        <a class="veloria-product-grid__icon-btn" href="<?php the_permalink(); ?>" aria-label="<?php esc_attr_e('View Product', 'veloria'); ?>">
                                            <i class="far fa-heart" aria-hidden="true"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($veloria_showAddToCart) : ?>
                                        <a
                                            class="veloria-product-grid__icon-btn veloria-product-grid__icon-btn--cart veloria-product-grid__cart-btn <?php echo esc_attr($veloria_product->supports('ajax_add_to_cart') ? 'add_to_cart_button ajax_add_to_cart' : ''); ?>"
                                            href="<?php echo esc_url($veloria_product->add_to_cart_url()); ?>"
                                            data-product_id="<?php echo esc_attr((string) $veloria_product->get_id()); ?>"
                                            data-product_sku="<?php echo esc_attr($veloria_product->get_sku()); ?>"
                                            data-quantity="1"
                                            rel="nofollow"
                                            aria-label="<?php echo esc_attr($veloria_product->add_to_cart_description()); ?>">
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
