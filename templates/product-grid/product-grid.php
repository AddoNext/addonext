<?php
defined('ABSPATH') || exit;

if (!class_exists('WooCommerce')) {
    echo '<div class="addonext-product-grid__placeholder">' . esc_html__('WooCommerce is required for Product Grid.', 'addonext') . '</div>';
    return;
}

$addonext_settings = $this->get_settings_for_display();

$addonext_parse_ids = static function ($addonext_raw): array {
    if (!is_string($addonext_raw) || trim($addonext_raw) === '') {
        return [];
    }
    $addonext_parts = array_map('trim', explode(',', $addonext_raw));
    $addonext_ids = array_map('absint', $addonext_parts);
    $addonext_ids = array_filter($addonext_ids, static function ($addonext_id) {
        return $addonext_id > 0;
    });
    return array_values(array_unique($addonext_ids));
};

$addonext_limit = isset($addonext_settings['limit']) ? max(1, (int) $addonext_settings['limit']) : 20;
$addonext_categories = [];
if (isset($addonext_settings['categories']) && is_array($addonext_settings['categories'])) {
    $addonext_categories = array_map('absint', $addonext_settings['categories']);
    $addonext_categories = array_values(array_filter($addonext_categories, static function ($addonext_termId) {
        return $addonext_termId > 0;
    }));
}
$addonext_includeIds = $addonext_parse_ids($addonext_settings['include_ids'] ?? '');
$addonext_excludeIds = $addonext_parse_ids($addonext_settings['exclude_ids'] ?? '');
$addonext_orderBy = isset($addonext_settings['orderby']) ? (string) $addonext_settings['orderby'] : 'date';
$addonext_order = isset($addonext_settings['order']) ? strtoupper((string) $addonext_settings['order']) : 'DESC';
$addonext_saleOnly = isset($addonext_settings['sale_only']) && $addonext_settings['sale_only'] === 'yes';
$addonext_featuredOnly = isset($addonext_settings['featured_only']) && $addonext_settings['featured_only'] === 'yes';
$addonext_stockStatus = isset($addonext_settings['stock_status']) ? (string) $addonext_settings['stock_status'] : 'all';

$addonext_queryArgs = [
    'post_type'           => 'product',
    'post_status'         => 'publish',
    'posts_per_page'      => $addonext_limit,
    'ignore_sticky_posts' => true,
    'no_found_rows'       => true,
];

$addonext_taxQuery = [];
if (!empty($addonext_categories)) {
    $addonext_taxQuery[] = [
        'taxonomy' => 'product_cat',
        'field'    => 'term_id',
        'terms'    => $addonext_categories,
        'operator' => 'IN',
    ];
}
if ($addonext_featuredOnly) {
    $addonext_taxQuery[] = [
        'taxonomy' => 'product_visibility',
        'field'    => 'name',
        'terms'    => ['featured'],
    ];
}
if (!empty($addonext_taxQuery)) {
    if (count($addonext_taxQuery) > 1) {
        $addonext_taxQuery['relation'] = 'AND';
    }
    $addonext_queryArgs['tax_query'] = $addonext_taxQuery;
}

$addonext_metaQuery = [];
if ($addonext_stockStatus !== 'all') {
    $addonext_metaQuery[] = [
        'key'   => '_stock_status',
        'value' => $addonext_stockStatus,
    ];
}
if (!empty($addonext_metaQuery)) {
    $addonext_queryArgs['meta_query'] = $addonext_metaQuery;
}

if ($addonext_saleOnly) {
    $addonext_saleIds = wc_get_product_ids_on_sale();
    $addonext_queryArgs['post__in'] = !empty($addonext_saleIds) ? $addonext_saleIds : [0];
}
if (!empty($addonext_includeIds)) {
    $addonext_queryArgs['post__in'] = isset($addonext_queryArgs['post__in'])
        ? array_values(array_intersect($addonext_queryArgs['post__in'], $addonext_includeIds))
        : $addonext_includeIds;
}
if (!empty($addonext_excludeIds)) {
    $addonext_queryArgs['post__not_in'] = $addonext_excludeIds;
}

switch ($addonext_orderBy) {
    case 'price':
        $addonext_queryArgs['orderby'] = 'meta_value_num';
        $addonext_queryArgs['meta_key'] = '_price';
        break;
    case 'popularity':
        $addonext_queryArgs['orderby'] = 'meta_value_num';
        $addonext_queryArgs['meta_key'] = 'total_sales';
        break;
    case 'rating':
        $addonext_queryArgs['orderby'] = 'meta_value_num';
        $addonext_queryArgs['meta_key'] = '_wc_average_rating';
        break;
    case 'title':
    case 'rand':
    case 'menu_order':
    case 'date':
        $addonext_queryArgs['orderby'] = $addonext_orderBy;
        break;
    default:
        $addonext_queryArgs['orderby'] = 'date';
        break;
}
$addonext_queryArgs['order'] = ($addonext_order === 'ASC') ? 'ASC' : 'DESC';

$addonext_productQuery = new \WP_Query($addonext_queryArgs);

$addonext_columns = isset($addonext_settings['columns']) ? max(1, min(6, (int) $addonext_settings['columns'])) : 4;
$addonext_columnsTablet = isset($addonext_settings['columns_tablet']) ? max(1, min(4, (int) $addonext_settings['columns_tablet'])) : 2;
$addonext_columnsMobile = isset($addonext_settings['columns_mobile']) ? max(1, min(2, (int) $addonext_settings['columns_mobile'])) : 1;
$addonext_columnGap = isset($addonext_settings['column_gap']) ? max(0, min(60, (int) $addonext_settings['column_gap'])) : 16;
$addonext_rowGap = isset($addonext_settings['row_gap']) ? max(0, min(60, (int) $addonext_settings['row_gap'])) : 16;

$addonext_showRating = isset($addonext_settings['show_rating']) && $addonext_settings['show_rating'] === 'yes';
$addonext_showShortDesc = isset($addonext_settings['show_short_desc']) && $addonext_settings['show_short_desc'] === 'yes';
$addonext_showOldPrice = isset($addonext_settings['show_old_price']) && $addonext_settings['show_old_price'] === 'yes';
$addonext_showBadges = isset($addonext_settings['show_badges']) && $addonext_settings['show_badges'] === 'yes';
$addonext_showWishlist = isset($addonext_settings['show_wishlist'])
    ? ($addonext_settings['show_wishlist'] === 'yes')
    : (isset($addonext_settings['show_actions']) && $addonext_settings['show_actions'] === 'yes');
$addonext_showAddToCart = isset($addonext_settings['show_add_to_cart'])
    ? ($addonext_settings['show_add_to_cart'] === 'yes')
    : (isset($addonext_settings['show_actions']) && $addonext_settings['show_actions'] === 'yes');
$addonext_hoverEffect = isset($addonext_settings['image_hover_effect']) ? (string) $addonext_settings['image_hover_effect'] : 'zoom-out';
$addonext_hoverClass = 'addonext-product-grid__image-wrap--' . preg_replace('/[^a-z\\-]/', '', $addonext_hoverEffect);

$addonext_cartUrl = '';
if (function_exists('wc_get_page_id')) {
    $addonext_cartPageId = (int) wc_get_page_id('cart');
    if ($addonext_cartPageId > 0) {
        $addonext_cartUrl = (string) get_permalink($addonext_cartPageId);
    }
}
if ($addonext_cartUrl === '' && function_exists('wc_get_cart_url')) {
    $addonext_cartUrl = (string) wc_get_cart_url();
}
$addonext_homeBase = untrailingslashit(home_url());
$addonext_cartBase = $addonext_cartUrl !== '' ? untrailingslashit($addonext_cartUrl) : '';
if ($addonext_cartBase === '' || $addonext_cartBase === $addonext_homeBase) {
    $addonext_cartUrl = '';
}

$addonext_gridShellStyle = sprintf(
    '--epg-cols-desktop:%d;--epg-cols-tablet:%d;--epg-cols-mobile:%d;--epg-col-gap:%dpx;--epg-row-gap:%dpx;',
    $addonext_columns,
    $addonext_columnsTablet,
    $addonext_columnsMobile,
    $addonext_columnGap,
    $addonext_rowGap
);
?>
<div
    class="addonext-product-grid-shell"
    style="<?php echo esc_attr($addonext_gridShellStyle); ?>"
    <?php echo $addonext_cartUrl !== '' ? 'data-cart-url="' . esc_url($addonext_cartUrl) . '"' : ''; ?>>
    <?php if ($addonext_productQuery->have_posts()) : ?>
        <div class="addonext-product-grid">
            <?php while ($addonext_productQuery->have_posts()) : $addonext_productQuery->the_post(); ?>
                <?php
                $addonext_product = wc_get_product(get_the_ID());
                if (!$addonext_product) {
                    continue;
                }

                $addonext_isOnSale = $addonext_product->is_on_sale();
                $addonext_isFeatured = $addonext_product->is_featured();
                $addonext_shortDesc = $addonext_product->get_short_description();
                $addonext_categoryLine = wc_get_product_category_list($addonext_product->get_id(), ' &bull; ', '', '');
                $addonext_regularPrice = $addonext_product->get_regular_price();
                $addonext_salePrice = $addonext_product->get_sale_price();
                $addonext_currentPrice = ($addonext_salePrice !== '') ? $addonext_salePrice : $addonext_product->get_price();
                $addonext_rating = (float) $addonext_product->get_average_rating();
                $addonext_ratingCount = (int) $addonext_product->get_rating_count();
                ?>
                <article class="addonext-product-grid__card">
                    <div class="addonext-product-grid__image-wrap <?php echo esc_attr($addonext_hoverClass); ?>">
                        <a href="<?php the_permalink(); ?>" class="addonext-product-grid__image-link">
                            <?php echo wp_kses_post($addonext_product->get_image('woocommerce_thumbnail', ['class' => 'addonext-product-grid__image'])); ?>
                        </a>
                        <?php if ($addonext_showBadges && ($addonext_isOnSale || $addonext_isFeatured)) : ?>
                            <div class="addonext-product-grid__badges">
                                <?php if ($addonext_isOnSale) : ?>
                                    <span class="addonext-product-grid__badge addonext-product-grid__badge--sale"><?php esc_html_e('Sale', 'addonext'); ?></span>
                                <?php endif; ?>
                                <?php if ($addonext_isFeatured) : ?>
                                    <span class="addonext-product-grid__badge addonext-product-grid__badge--new"><?php esc_html_e('Featured', 'addonext'); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="addonext-product-grid__card-body">
                        <?php if ($addonext_showRating) : ?>
                            <div class="addonext-product-grid__rating">
                                <?php echo wp_kses_post(wc_get_rating_html($addonext_rating, $addonext_ratingCount)); ?>
                                <span class="addonext-product-grid__rating-value">(<?php echo esc_html(number_format_i18n($addonext_rating, 1)); ?>)</span>
                            </div>
                        <?php endif; ?>

                        <h3 class="addonext-product-grid__title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>

                        <?php if ($addonext_categoryLine !== '') : ?>
                            <div class="addonext-product-grid__meta"><?php echo wp_kses_post($addonext_categoryLine); ?></div>
                        <?php endif; ?>

                        <?php if ($addonext_showShortDesc && $addonext_shortDesc !== '') : ?>
                            <div class="addonext-product-grid__desc"><?php echo esc_html(wp_trim_words(wp_strip_all_tags($addonext_shortDesc), 7, '')); ?></div>
                        <?php endif; ?>

                        <div class="addonext-product-grid__price-row">
                            <span class="addonext-product-grid__price-main">
                                <span class="addonext-product-grid__price-current"><?php echo wp_kses_post(wc_price((float) $addonext_currentPrice)); ?></span>
                                <?php if ($addonext_showOldPrice && $addonext_regularPrice !== '' && $addonext_salePrice !== '' && (float) $addonext_regularPrice > (float) $addonext_salePrice) : ?>
                                    <span class="addonext-product-grid__price-old"><?php echo wp_kses_post(wc_price((float) $addonext_regularPrice)); ?></span>
                                <?php endif; ?>
                            </span>
                            <?php if ($addonext_showWishlist || $addonext_showAddToCart) : ?>
                                <span class="addonext-product-grid__actions">
                                    <?php if ($addonext_showWishlist) : ?>
                                        <a class="addonext-product-grid__icon-btn" href="<?php the_permalink(); ?>" aria-label="<?php esc_attr_e('View Product', 'addonext'); ?>">
                                            <i class="far fa-heart" aria-hidden="true"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($addonext_showAddToCart) : ?>
                                        <a
                                            class="addonext-product-grid__icon-btn addonext-product-grid__icon-btn--cart addonext-product-grid__cart-btn <?php echo esc_attr($addonext_product->supports('ajax_add_to_cart') ? 'add_to_cart_button ajax_add_to_cart' : ''); ?>"
                                            href="<?php echo esc_url($addonext_product->add_to_cart_url()); ?>"
                                            data-product_id="<?php echo esc_attr((string) $addonext_product->get_id()); ?>"
                                            data-product_sku="<?php echo esc_attr($addonext_product->get_sku()); ?>"
                                            data-quantity="1"
                                            rel="nofollow"
                                            aria-label="<?php echo esc_attr($addonext_product->add_to_cart_description()); ?>">
                                            <i class="fas fa-shopping-cart" aria-hidden="true"></i>
                                        </a>
                                        <a class="addonext-product-grid__cart-tooltip" href="#"><?php esc_html_e('View cart', 'addonext'); ?></a>
                                    <?php endif; ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    <?php else : ?>
        <div class="addonext-product-grid__placeholder"><?php esc_html_e('No products found for selected filters.', 'addonext'); ?></div>
    <?php endif; ?>
</div>
<?php
wp_reset_postdata();
