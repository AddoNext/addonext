<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

if (!class_exists('WooCommerce')) {
    echo '<div class="veloria-product-carousel__placeholder">' . esc_html__('WooCommerce is required for Product Carousel.', 'veloria') . '</div>';
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

$veloria_limit = isset($veloria_settings['limit']) ? max(1, (int) $veloria_settings['limit']) : 8;
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

$veloria_perView = isset($veloria_settings['per_view']) ? max(1, min(6, (int) $veloria_settings['per_view'])) : 4;
$veloria_perViewTablet = isset($veloria_settings['per_view_tablet']) ? max(1, min(4, (int) $veloria_settings['per_view_tablet'])) : 2;
$veloria_perViewMobile = isset($veloria_settings['per_view_mobile']) ? max(1, min(2, (int) $veloria_settings['per_view_mobile'])) : 1;
$veloria_spaceBetween = isset($veloria_settings['space_between']) ? max(0, min(60, (int) $veloria_settings['space_between'])) : 16;
$veloria_showNav = isset($veloria_settings['show_nav']) && $veloria_settings['show_nav'] === 'yes';
$veloria_showDots = isset($veloria_settings['show_dots']) && $veloria_settings['show_dots'] === 'yes';
$veloria_loop = isset($veloria_settings['loop']) && $veloria_settings['loop'] === 'yes';
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
$veloria_hoverEffect = isset($veloria_settings['image_hover_effect']) ? (string) $veloria_settings['image_hover_effect'] : 'none';
$veloria_hoverClass = 'veloria-product-carousel__image-wrap--' . preg_replace('/[^a-z\\-]/', '', $veloria_hoverEffect);

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
?>
<div class="veloria-product-carousel-shell"<?php echo $veloria_cartUrl !== '' ? ' data-cart-url="' . esc_url($veloria_cartUrl) . '"' : ''; ?>>
    <?php if ($veloria_showNav) : ?>
        <button class="veloria-product-carousel__nav-btn veloria-product-carousel__nav-btn--prev" type="button" aria-label="<?php esc_attr_e('Previous', 'veloria'); ?>">
            <i class="eicon-chevron-left" aria-hidden="true"></i>
        </button>
        <button class="veloria-product-carousel__nav-btn veloria-product-carousel__nav-btn--next" type="button" aria-label="<?php esc_attr_e('Next', 'veloria'); ?>">
            <i class="eicon-chevron-right" aria-hidden="true"></i>
        </button>
    <?php endif; ?>
    <div class="veloria-product-carousel__viewport">
        <div class="veloria-product-carousel swiper"
            data-slides-desktop="<?php echo esc_attr((string) $veloria_perView); ?>"
            data-slides-tablet="<?php echo esc_attr((string) $veloria_perViewTablet); ?>"
            data-slides-mobile="<?php echo esc_attr((string) $veloria_perViewMobile); ?>"
            data-space-between="<?php echo esc_attr((string) $veloria_spaceBetween); ?>"
            data-show-nav="<?php echo esc_attr($veloria_showNav ? 'yes' : 'no'); ?>"
            data-show-dots="<?php echo esc_attr($veloria_showDots ? 'yes' : 'no'); ?>"
            data-loop="<?php echo esc_attr($veloria_loop ? 'yes' : 'no'); ?>">
    <?php if ($veloria_productQuery->have_posts()) : ?>
        <div class="swiper-wrapper">
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
                $veloria_priceHtml = $veloria_product->get_price_html();
                $veloria_regularPrice = $veloria_product->get_regular_price();
                $veloria_salePrice = $veloria_product->get_sale_price();
                $veloria_currentPrice = ($veloria_salePrice !== '') ? $veloria_salePrice : $veloria_product->get_price();
                $veloria_rating = (float) $veloria_product->get_average_rating();
                $veloria_ratingCount = (int) $veloria_product->get_rating_count();
                ?>
                <article class="veloria-product-carousel__card swiper-slide">
                    <div class="veloria-product-carousel__image-wrap <?php echo esc_attr($veloria_hoverClass); ?>">
                        <a href="<?php the_permalink(); ?>" class="veloria-product-carousel__image-link">
                            <?php echo wp_kses_post($veloria_product->get_image('woocommerce_thumbnail', ['class' => 'veloria-product-carousel__image'])); ?>
                        </a>
                        <?php if ($veloria_showBadges && ($veloria_isOnSale || $veloria_isFeatured)) : ?>
                            <div class="veloria-product-carousel__badges">
                                <?php if ($veloria_isOnSale) : ?>
                                    <span class="veloria-product-carousel__badge veloria-product-carousel__badge--sale"><?php esc_html_e('Sale', 'veloria'); ?></span>
                                <?php endif; ?>
                                <?php if ($veloria_isFeatured) : ?>
                                    <span class="veloria-product-carousel__badge veloria-product-carousel__badge--new"><?php esc_html_e('Featured', 'veloria'); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="veloria-product-carousel__card-body">
                        <?php if ($veloria_showRating) : ?>
                            <div class="veloria-product-carousel__rating">
                                <?php echo wp_kses_post(wc_get_rating_html($veloria_rating, $veloria_ratingCount)); ?>
                                <span class="veloria-product-carousel__rating-value">(<?php echo esc_html(number_format_i18n($veloria_rating, 1)); ?>)</span>
                            </div>
                        <?php endif; ?>

                        <h3 class="veloria-product-carousel__title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>

                        <?php if ($veloria_categoryLine !== '') : ?>
                            <div class="veloria-product-carousel__meta"><?php echo wp_kses_post($veloria_categoryLine); ?></div>
                        <?php endif; ?>

                        <?php if ($veloria_showShortDesc && $veloria_shortDesc !== '') : ?>
                            <div class="veloria-product-carousel__desc"><?php echo esc_html(wp_trim_words(wp_strip_all_tags($veloria_shortDesc), 7, '')); ?></div>
                        <?php endif; ?>

                        <div class="veloria-product-carousel__price-row">
                            <span class="veloria-product-carousel__price-main">
                                <span class="veloria-product-carousel__price-current"><?php echo wp_kses_post(wc_price((float) $veloria_currentPrice)); ?></span>
                                <?php if ($veloria_showOldPrice && $veloria_regularPrice !== '' && $veloria_salePrice !== '' && (float) $veloria_regularPrice > (float) $veloria_salePrice) : ?>
                                    <span class="veloria-product-carousel__price-old"><?php echo wp_kses_post(wc_price((float) $veloria_regularPrice)); ?></span>
                                <?php endif; ?>
                            </span>
                            <?php if ($veloria_showWishlist || $veloria_showAddToCart) : ?>
                                <span class="veloria-product-carousel__actions">
                                    <?php if ($veloria_showWishlist) : ?>
                                        <a class="veloria-product-carousel__icon-btn" href="<?php the_permalink(); ?>" aria-label="<?php esc_attr_e('View Product', 'veloria'); ?>">
                                            <i class="far fa-heart" aria-hidden="true"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($veloria_showAddToCart) : ?>
                                        <a
                                            class="veloria-product-carousel__icon-btn veloria-product-carousel__icon-btn--cart veloria-product-carousel__cart-btn <?php echo esc_attr($veloria_product->supports('ajax_add_to_cart') ? 'add_to_cart_button ajax_add_to_cart' : ''); ?>"
                                            href="<?php echo esc_url($veloria_product->add_to_cart_url()); ?>"
                                            data-product_id="<?php echo esc_attr((string) $veloria_product->get_id()); ?>"
                                            data-product_sku="<?php echo esc_attr($veloria_product->get_sku()); ?>"
                                            data-quantity="1"
                                            rel="nofollow"
                                            aria-label="<?php echo esc_attr($veloria_product->add_to_cart_description()); ?>">
                                            <i class="fas fa-shopping-cart" aria-hidden="true"></i>
                                        </a>
                                        <a class="veloria-product-carousel__cart-tooltip" href="#"><?php esc_html_e('View cart', 'veloria'); ?></a>
                                    <?php endif; ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    <?php else : ?>
        <div class="veloria-product-carousel__placeholder"><?php esc_html_e('No products found for selected filters.', 'veloria'); ?></div>
    <?php endif; ?>
        </div>
    </div>
    <?php if ($veloria_showDots) : ?>
        <div class="swiper-pagination veloria-product-carousel__dots"></div>
    <?php endif; ?>
</div>
<?php
wp_reset_postdata();

    }
}
