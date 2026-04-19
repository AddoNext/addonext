<?php
defined('ABSPATH') || exit;

if (!class_exists('WooCommerce')) {
    echo '<div class="elementrify-product-tabs__placeholder">' . esc_html__('WooCommerce is required.', 'elementrify') . '</div>';
    return;
}

$settings = $this->get_settings_for_display();
$tabs = isset($settings['tabs']) && is_array($settings['tabs']) ? $settings['tabs'] : [];

if ($tabs === []) {
    echo '<div class="elementrify-product-tabs__placeholder">' . esc_html__('Add tabs in the widget settings.', 'elementrify') . '</div>';
    return;
}

$columns = isset($settings['columns']) ? max(1, min(6, (int) $settings['columns'])) : 4;
$columns_tablet = isset($settings['columns_tablet']) ? max(1, min(4, (int) $settings['columns_tablet'])) : 2;
$columns_mobile = isset($settings['columns_mobile']) ? max(1, min(2, (int) $settings['columns_mobile'])) : 1;
$column_gap = isset($settings['column_gap']) ? max(0, min(60, (int) $settings['column_gap'])) : 16;
$row_gap = isset($settings['row_gap']) ? max(0, min(60, (int) $settings['row_gap'])) : 16;

$show_rating = isset($settings['show_rating']) && $settings['show_rating'] === 'yes';
$show_short_desc = isset($settings['show_short_desc']) && $settings['show_short_desc'] === 'yes';
$show_old_price = isset($settings['show_old_price']) && $settings['show_old_price'] === 'yes';
$show_badges = isset($settings['show_badges']) && $settings['show_badges'] === 'yes';
$show_wishlist = isset($settings['show_wishlist']) && $settings['show_wishlist'] === 'yes';
$show_add_to_cart = isset($settings['show_add_to_cart']) && $settings['show_add_to_cart'] === 'yes';
$image_hover_effect = isset($settings['image_hover_effect']) ? (string) $settings['image_hover_effect'] : 'zoom-out';

$cart_url = '';
if (function_exists('wc_get_page_id')) {
    $cart_page_id = (int) wc_get_page_id('cart');
    if ($cart_page_id > 0) {
        $cart_url = (string) get_permalink($cart_page_id);
    }
}
if ($cart_url === '' && function_exists('wc_get_cart_url')) {
    $cart_url = (string) wc_get_cart_url();
}
$home_base = untrailingslashit(home_url());
$cart_base = $cart_url !== '' ? untrailingslashit($cart_url) : '';
if ($cart_base === '' || $cart_base === $home_base) {
    $cart_url = '';
}

$widget_uid = 'ept-' . preg_replace('/[^a-z0-9\-]/i', '', (string) $this->get_id());
$prefix = 'elementrify-product-grid';
$card_effect = preg_replace('/[^a-z\-]/', '', (string) $image_hover_effect);
if ($card_effect === '') {
    $card_effect = 'none';
}
$card_hover_class = $prefix . '__image-wrap--' . $card_effect;
$tabs_grid_vars = sprintf(
    '--ept-cols-desktop:%d;--ept-cols-tablet:%d;--ept-cols-mobile:%d;--ept-col-gap:%dpx;--ept-row-gap:%dpx',
    $columns,
    $columns_tablet,
    $columns_mobile,
    $column_gap,
    $row_gap
);
?>
<div class="elementrify-product-tabs" data-elementrify-product-tabs="1" style="<?php echo esc_attr($tabs_grid_vars); ?>">
    <div class="elementrify-product-tabs__nav" role="tablist" aria-label="<?php esc_attr_e('Product collections', 'elementrify'); ?>">
        <?php foreach ($tabs as $ti => $tab_row) : ?>
            <?php
            if (!is_array($tab_row)) {
                continue;
            }
            $tab_title = isset($tab_row['tab_title']) ? (string) $tab_row['tab_title'] : '';
            if (trim($tab_title) === '') {
                $tab_title = sprintf(/* translators: %d: tab index */ __('Tab %d', 'elementrify'), (int) $ti + 1);
            }
            $tab_btn_id = $widget_uid . '-tab-' . (int) $ti;
            $tab_panel_id = $widget_uid . '-panel-' . (int) $ti;
            $is_first = ((int) $ti === 0);
            ?>
            <button
                type="button"
                class="elementrify-product-tabs__tab elementor-clickable<?php echo $is_first ? ' is-active' : ''; ?>"
                role="tab"
                id="<?php echo esc_attr($tab_btn_id); ?>"
                aria-selected="<?php echo $is_first ? 'true' : 'false'; ?>"
                aria-controls="<?php echo esc_attr($tab_panel_id); ?>"
                tabindex="<?php echo $is_first ? '0' : '-1'; ?>"
            ><?php echo esc_html($tab_title); ?></button>
        <?php endforeach; ?>
    </div>

    <div class="elementrify-product-tabs__panels">
        <?php foreach ($tabs as $ti => $tab_row) : ?>
            <?php
            if (!is_array($tab_row)) {
                continue;
            }
            $query_args = \Elementrify\Widgets\ProductTabs\ProductTabs::build_tab_row_query_args($tab_row);
            $product_query = new \WP_Query($query_args);

            $tab_btn_id = $widget_uid . '-tab-' . (int) $ti;
            $tab_panel_id = $widget_uid . '-panel-' . (int) $ti;
            $is_first = ((int) $ti === 0);

            $footer_text = isset($tab_row['footer_button_text']) ? trim((string) $tab_row['footer_button_text']) : '';
            $footer_link = isset($tab_row['footer_button_link']) && is_array($tab_row['footer_button_link']) ? $tab_row['footer_button_link'] : [];
            $footer_url = isset($footer_link['url']) ? trim((string) $footer_link['url']) : '';
            $rel_parts = [];
            if (!empty($footer_link['nofollow'])) {
                $rel_parts[] = 'nofollow';
            }
            if (!empty($footer_link['is_external'])) {
                $rel_parts[] = 'noopener';
                $rel_parts[] = 'noreferrer';
            }
            ?>
            <div
                class="elementrify-product-tabs__panel<?php echo $is_first ? ' is-active' : ''; ?>"
                id="<?php echo esc_attr($tab_panel_id); ?>"
                role="tabpanel"
                aria-labelledby="<?php echo esc_attr($tab_btn_id); ?>"
                <?php echo $is_first ? '' : 'hidden'; ?>
            >
                <div
                    class="elementrify-product-tabs__grid-shell"
                    <?php echo $cart_url !== '' ? 'data-cart-url="' . esc_url($cart_url) . '"' : ''; ?>
                >
                    <?php if ($product_query->have_posts()) : ?>
                        <div class="elementrify-product-grid elementrify-product-tabs__grid">
                            <?php
                            while ($product_query->have_posts()) :
                                $product_query->the_post();
                                $product = wc_get_product(get_the_ID());
                                if (!$product) {
                                    continue;
                                }
                                $is_on_sale = $product->is_on_sale();
                                $is_featured = $product->is_featured();
                                $card_short_desc = $product->get_short_description();
                                $category_line = wc_get_product_category_list($product->get_id(), ' &bull; ', '', '');
                                $regular_price = $product->get_regular_price();
                                $sale_price = $product->get_sale_price();
                                $current_price = ($sale_price !== '') ? $sale_price : $product->get_price();
                                $rating = (float) $product->get_average_rating();
                                $rating_count = (int) $product->get_rating_count();
                                $permalink = $product->get_permalink();
                                ?>
                                <article class="<?php echo esc_attr($prefix); ?>__card">
                                    <div class="<?php echo esc_attr($prefix); ?>__image-wrap <?php echo esc_attr($card_hover_class); ?>">
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

                                        <?php if ($show_short_desc && $card_short_desc !== '') : ?>
                                            <div class="<?php echo esc_attr($prefix); ?>__desc"><?php echo esc_html(wp_trim_words(wp_strip_all_tags($card_short_desc), 7, '')); ?></div>
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
                                <?php
                            endwhile;
                            wp_reset_postdata();
                            ?>
                        </div>
                    <?php else : ?>
                        <div class="elementrify-product-grid__placeholder"><?php esc_html_e('No products found for this tab.', 'elementrify'); ?></div>
                    <?php endif; ?>
                </div>

                <?php if ($footer_text !== '') : ?>
                    <div class="elementrify-product-tabs__footer">
                        <?php if ($footer_url !== '') : ?>
                            <a class="elementrify-product-tabs__cta" href="<?php echo esc_url($footer_url); ?>"<?php
                            if (!empty($footer_link['is_external'])) {
                                echo ' target="_blank"';
                            }
                            if ($rel_parts !== []) {
                                echo ' rel="' . esc_attr(implode(' ', $rel_parts)) . '"';
                            }
                            ?>>
                                <?php echo esc_html($footer_text); ?>
                            </a>
                        <?php else : ?>
                            <span class="elementrify-product-tabs__cta elementrify-product-tabs__cta--static"><?php echo esc_html($footer_text); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
