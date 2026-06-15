<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

if (!class_exists('WooCommerce')) {
    echo '<div class="veloria-product-carousel__placeholder">' . esc_html__('WooCommerce is required for Product Carousel.', 'veloria') . '</div>';
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

$limit = isset($settings['limit']) ? max(1, (int) $settings['limit']) : 8;
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

$perView = isset($settings['per_view']) ? max(1, min(6, (int) $settings['per_view'])) : 4;
$perViewTablet = isset($settings['per_view_tablet']) ? max(1, min(4, (int) $settings['per_view_tablet'])) : 2;
$perViewMobile = isset($settings['per_view_mobile']) ? max(1, min(2, (int) $settings['per_view_mobile'])) : 1;
$spaceBetween = isset($settings['space_between']) ? max(0, min(60, (int) $settings['space_between'])) : 16;
$showNav = isset($settings['show_nav']) && $settings['show_nav'] === 'yes';
$showDots = isset($settings['show_dots']) && $settings['show_dots'] === 'yes';
$loop = isset($settings['loop']) && $settings['loop'] === 'yes';
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
$hoverEffect = isset($settings['image_hover_effect']) ? (string) $settings['image_hover_effect'] : 'none';
$hoverClass = 'veloria-product-carousel__image-wrap--' . preg_replace('/[^a-z\\-]/', '', $hoverEffect);

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
?>
<div class="veloria-product-carousel-shell"<?php echo $cartUrl !== '' ? ' data-cart-url="' . esc_url($cartUrl) . '"' : ''; ?>>
    <?php if ($showNav) : ?>
        <button class="veloria-product-carousel__nav-btn veloria-product-carousel__nav-btn--prev" type="button" aria-label="<?php esc_attr_e('Previous', 'veloria'); ?>">
            <i class="eicon-chevron-left" aria-hidden="true"></i>
        </button>
        <button class="veloria-product-carousel__nav-btn veloria-product-carousel__nav-btn--next" type="button" aria-label="<?php esc_attr_e('Next', 'veloria'); ?>">
            <i class="eicon-chevron-right" aria-hidden="true"></i>
        </button>
    <?php endif; ?>
    <div class="veloria-product-carousel__viewport">
        <div class="veloria-product-carousel swiper"
            data-slides-desktop="<?php echo esc_attr((string) $perView); ?>"
            data-slides-tablet="<?php echo esc_attr((string) $perViewTablet); ?>"
            data-slides-mobile="<?php echo esc_attr((string) $perViewMobile); ?>"
            data-space-between="<?php echo esc_attr((string) $spaceBetween); ?>"
            data-show-nav="<?php echo esc_attr($showNav ? 'yes' : 'no'); ?>"
            data-show-dots="<?php echo esc_attr($showDots ? 'yes' : 'no'); ?>"
            data-loop="<?php echo esc_attr($loop ? 'yes' : 'no'); ?>">
    <?php if ($productQuery->have_posts()) : ?>
        <div class="swiper-wrapper">
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
                $priceHtml = $product->get_price_html();
                $regularPrice = $product->get_regular_price();
                $salePrice = $product->get_sale_price();
                $currentPrice = ($salePrice !== '') ? $salePrice : $product->get_price();
                $rating = (float) $product->get_average_rating();
                $ratingCount = (int) $product->get_rating_count();
                ?>
                <article class="veloria-product-carousel__card swiper-slide">
                    <div class="veloria-product-carousel__image-wrap <?php echo esc_attr($hoverClass); ?>">
                        <a href="<?php the_permalink(); ?>" class="veloria-product-carousel__image-link">
                            <?php echo wp_kses_post($product->get_image('woocommerce_thumbnail', ['class' => 'veloria-product-carousel__image'])); ?>
                        </a>
                        <?php if ($showBadges && ($isOnSale || $isFeatured)) : ?>
                            <div class="veloria-product-carousel__badges">
                                <?php if ($isOnSale) : ?>
                                    <span class="veloria-product-carousel__badge veloria-product-carousel__badge--sale"><?php esc_html_e('Sale', 'veloria'); ?></span>
                                <?php endif; ?>
                                <?php if ($isFeatured) : ?>
                                    <span class="veloria-product-carousel__badge veloria-product-carousel__badge--new"><?php esc_html_e('Featured', 'veloria'); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="veloria-product-carousel__card-body">
                        <?php if ($showRating) : ?>
                            <div class="veloria-product-carousel__rating">
                                <?php echo wp_kses_post(wc_get_rating_html($rating, $ratingCount)); ?>
                                <span class="veloria-product-carousel__rating-value">(<?php echo esc_html(number_format_i18n($rating, 1)); ?>)</span>
                            </div>
                        <?php endif; ?>

                        <h3 class="veloria-product-carousel__title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>

                        <?php if ($categoryLine !== '') : ?>
                            <div class="veloria-product-carousel__meta"><?php echo wp_kses_post($categoryLine); ?></div>
                        <?php endif; ?>

                        <?php if ($showShortDesc && $shortDesc !== '') : ?>
                            <div class="veloria-product-carousel__desc"><?php echo esc_html(wp_trim_words(wp_strip_all_tags($shortDesc), 7, '')); ?></div>
                        <?php endif; ?>

                        <div class="veloria-product-carousel__price-row">
                            <span class="veloria-product-carousel__price-main">
                                <span class="veloria-product-carousel__price-current"><?php echo wp_kses_post(wc_price((float) $currentPrice)); ?></span>
                                <?php if ($showOldPrice && $regularPrice !== '' && $salePrice !== '' && (float) $regularPrice > (float) $salePrice) : ?>
                                    <span class="veloria-product-carousel__price-old"><?php echo wp_kses_post(wc_price((float) $regularPrice)); ?></span>
                                <?php endif; ?>
                            </span>
                            <?php if ($showWishlist || $showAddToCart) : ?>
                                <span class="veloria-product-carousel__actions">
                                    <?php if ($showWishlist) : ?>
                                        <a class="veloria-product-carousel__icon-btn" href="<?php the_permalink(); ?>" aria-label="<?php esc_attr_e('View Product', 'veloria'); ?>">
                                            <i class="far fa-heart" aria-hidden="true"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($showAddToCart) : ?>
                                        <a
                                            class="veloria-product-carousel__icon-btn veloria-product-carousel__icon-btn--cart veloria-product-carousel__cart-btn <?php echo esc_attr($product->supports('ajax_add_to_cart') ? 'add_to_cart_button ajax_add_to_cart' : ''); ?>"
                                            href="<?php echo esc_url($product->add_to_cart_url()); ?>"
                                            data-product_id="<?php echo esc_attr((string) $product->get_id()); ?>"
                                            data-product_sku="<?php echo esc_attr($product->get_sku()); ?>"
                                            data-quantity="1"
                                            rel="nofollow"
                                            aria-label="<?php echo esc_attr($product->add_to_cart_description()); ?>">
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
    <?php if ($showDots) : ?>
        <div class="swiper-pagination veloria-product-carousel__dots"></div>
    <?php endif; ?>
</div>
<?php
wp_reset_postdata();

    }
}
