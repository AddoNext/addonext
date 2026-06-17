<?php
defined('ABSPATH') || exit;

if (!class_exists('WooCommerce')) {
    echo '<div class="veloria-product-tabs__placeholder">' . esc_html__('WooCommerce is required.', 'veloria') . '</div>';
    return;
}

$veloria_settings = $this->get_settings_for_display();
$veloria_tabs = isset($veloria_settings['tabs']) && is_array($veloria_settings['tabs']) ? $veloria_settings['tabs'] : [];

if ($veloria_tabs === []) {
    echo '<div class="veloria-product-tabs__placeholder">' . esc_html__('Add tabs in the widget settings.', 'veloria') . '</div>';
    return;
}

$veloria_columns = isset($veloria_settings['columns']) ? max(1, min(6, (int) $veloria_settings['columns'])) : 4;
$veloria_columns_tablet = isset($veloria_settings['columns_tablet']) ? max(1, min(4, (int) $veloria_settings['columns_tablet'])) : 2;
$veloria_columns_mobile = isset($veloria_settings['columns_mobile']) ? max(1, min(2, (int) $veloria_settings['columns_mobile'])) : 1;
$veloria_column_gap = isset($veloria_settings['column_gap']) ? max(0, min(60, (int) $veloria_settings['column_gap'])) : 16;
$veloria_row_gap = isset($veloria_settings['row_gap']) ? max(0, min(60, (int) $veloria_settings['row_gap'])) : 16;

$veloria_show_rating = isset($veloria_settings['show_rating']) && $veloria_settings['show_rating'] === 'yes';
$veloria_show_short_desc = isset($veloria_settings['show_short_desc']) && $veloria_settings['show_short_desc'] === 'yes';
$veloria_show_old_price = isset($veloria_settings['show_old_price']) && $veloria_settings['show_old_price'] === 'yes';
$veloria_show_badges = isset($veloria_settings['show_badges']) && $veloria_settings['show_badges'] === 'yes';
$veloria_show_wishlist = isset($veloria_settings['show_wishlist']) && $veloria_settings['show_wishlist'] === 'yes';
$veloria_show_add_to_cart = isset($veloria_settings['show_add_to_cart']) && $veloria_settings['show_add_to_cart'] === 'yes';
$veloria_image_hover_effect = isset($veloria_settings['image_hover_effect']) ? (string) $veloria_settings['image_hover_effect'] : 'zoom-out';

$veloria_cart_url = '';
if (function_exists('wc_get_page_id')) {
    $veloria_cart_page_id = (int) wc_get_page_id('cart');
    if ($veloria_cart_page_id > 0) {
        $veloria_cart_url = (string) get_permalink($veloria_cart_page_id);
    }
}
if ($veloria_cart_url === '' && function_exists('wc_get_cart_url')) {
    $veloria_cart_url = (string) wc_get_cart_url();
}
$veloria_home_base = untrailingslashit(home_url());
$veloria_cart_base = $veloria_cart_url !== '' ? untrailingslashit($veloria_cart_url) : '';
if ($veloria_cart_base === '' || $veloria_cart_base === $veloria_home_base) {
    $veloria_cart_url = '';
}

$veloria_widget_uid = 'ept-' . preg_replace('/[^a-z0-9\-]/i', '', (string) $this->get_id());
$veloria_prefix = 'veloria-product-grid';
$veloria_card_effect = preg_replace('/[^a-z\-]/', '', (string) $veloria_image_hover_effect);
if ($veloria_card_effect === '') {
    $veloria_card_effect = 'none';
}
$veloria_card_hover_class = $veloria_prefix . '__image-wrap--' . $veloria_card_effect;
$veloria_tabs_grid_vars = sprintf(
    '--ept-cols-desktop:%d;--ept-cols-tablet:%d;--ept-cols-mobile:%d;--ept-col-gap:%dpx;--ept-row-gap:%dpx',
    $veloria_columns,
    $veloria_columns_tablet,
    $veloria_columns_mobile,
    $veloria_column_gap,
    $veloria_row_gap
);
?>
<div class="veloria-product-tabs" data-veloria-product-tabs="1" style="<?php echo esc_attr($veloria_tabs_grid_vars); ?>">
    <div class="veloria-product-tabs__nav" role="tablist" aria-label="<?php esc_attr_e('Product collections', 'veloria'); ?>">
        <?php foreach ($veloria_tabs as $veloria_ti => $veloria_tab_row) : ?>
            <?php
            if (!is_array($veloria_tab_row)) {
                continue;
            }
            $veloria_tab_title = isset($veloria_tab_row['tab_title']) ? (string) $veloria_tab_row['tab_title'] : '';
            if (trim($veloria_tab_title) === '') {
                $veloria_tab_title = sprintf(/* translators: %d: tab index */ __('Tab %d', 'veloria'), (int) $veloria_ti + 1);
            }
            $veloria_tab_btn_id = $veloria_widget_uid . '-tab-' . (int) $veloria_ti;
            $veloria_tab_panel_id = $veloria_widget_uid . '-panel-' . (int) $veloria_ti;
            $veloria_is_first = ((int) $veloria_ti === 0);
            ?>
            <button
                type="button"
                class="veloria-product-tabs__tab elementor-clickable<?php echo $veloria_is_first ? ' is-active' : ''; ?>"
                role="tab"
                id="<?php echo esc_attr($veloria_tab_btn_id); ?>"
                aria-selected="<?php echo $veloria_is_first ? 'true' : 'false'; ?>"
                aria-controls="<?php echo esc_attr($veloria_tab_panel_id); ?>"
                tabindex="<?php echo $veloria_is_first ? '0' : '-1'; ?>"
            ><?php echo esc_html($veloria_tab_title); ?></button>
        <?php endforeach; ?>
    </div>

    <div class="veloria-product-tabs__panels">
        <?php foreach ($veloria_tabs as $veloria_ti => $veloria_tab_row) : ?>
            <?php
            if (!is_array($veloria_tab_row)) {
                continue;
            }
            $veloria_query_args = \Veloria\Widgets\ProductTabs\ProductTabs::build_tab_row_query_args($veloria_tab_row);
            $veloria_product_query = new \WP_Query($veloria_query_args);

            $veloria_tab_btn_id = $veloria_widget_uid . '-tab-' . (int) $veloria_ti;
            $veloria_tab_panel_id = $veloria_widget_uid . '-panel-' . (int) $veloria_ti;
            $veloria_is_first = ((int) $veloria_ti === 0);

            $veloria_footer_text = isset($veloria_tab_row['footer_button_text']) ? trim((string) $veloria_tab_row['footer_button_text']) : '';
            $veloria_footer_link = isset($veloria_tab_row['footer_button_link']) && is_array($veloria_tab_row['footer_button_link']) ? $veloria_tab_row['footer_button_link'] : [];
            $veloria_footer_url = isset($veloria_footer_link['url']) ? trim((string) $veloria_footer_link['url']) : '';
            $veloria_rel_parts = [];
            if (!empty($veloria_footer_link['nofollow'])) {
                $veloria_rel_parts[] = 'nofollow';
            }
            if (!empty($veloria_footer_link['is_external'])) {
                $veloria_rel_parts[] = 'noopener';
                $veloria_rel_parts[] = 'noreferrer';
            }
            ?>
            <div
                class="veloria-product-tabs__panel<?php echo $veloria_is_first ? ' is-active' : ''; ?>"
                id="<?php echo esc_attr($veloria_tab_panel_id); ?>"
                role="tabpanel"
                aria-labelledby="<?php echo esc_attr($veloria_tab_btn_id); ?>"
                <?php echo $veloria_is_first ? '' : 'hidden'; ?>
            >
                <div
                    class="veloria-product-tabs__grid-shell"
                    <?php echo $veloria_cart_url !== '' ? 'data-cart-url="' . esc_url($veloria_cart_url) . '"' : ''; ?>
                >
                    <?php if ($veloria_product_query->have_posts()) : ?>
                        <div class="veloria-product-grid veloria-product-tabs__grid">
                            <?php
                            while ($veloria_product_query->have_posts()) :
                                $veloria_product_query->the_post();
                                $veloria_product = wc_get_product(get_the_ID());
                                if (!$veloria_product) {
                                    continue;
                                }
                                $veloria_is_on_sale = $veloria_product->is_on_sale();
                                $veloria_is_featured = $veloria_product->is_featured();
                                $veloria_card_short_desc = $veloria_product->get_short_description();
                                $veloria_category_line = wc_get_product_category_list($veloria_product->get_id(), ' &bull; ', '', '');
                                $veloria_regular_price = $veloria_product->get_regular_price();
                                $veloria_sale_price = $veloria_product->get_sale_price();
                                $veloria_current_price = ($veloria_sale_price !== '') ? $veloria_sale_price : $veloria_product->get_price();
                                $veloria_rating = (float) $veloria_product->get_average_rating();
                                $veloria_rating_count = (int) $veloria_product->get_rating_count();
                                $veloria_permalink = $veloria_product->get_permalink();
                                ?>
                                <article class="<?php echo esc_attr($veloria_prefix); ?>__card">
                                    <div class="<?php echo esc_attr($veloria_prefix); ?>__image-wrap <?php echo esc_attr($veloria_card_hover_class); ?>">
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

                                        <?php if ($veloria_show_short_desc && $veloria_card_short_desc !== '') : ?>
                                            <div class="<?php echo esc_attr($veloria_prefix); ?>__desc"><?php echo esc_html(wp_trim_words(wp_strip_all_tags($veloria_card_short_desc), 7, '')); ?></div>
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
                                <?php
                            endwhile;
                            wp_reset_postdata();
                            ?>
                        </div>
                    <?php else : ?>
                        <div class="veloria-product-grid__placeholder"><?php esc_html_e('No products found for this tab.', 'veloria'); ?></div>
                    <?php endif; ?>
                </div>

                <?php if ($veloria_footer_text !== '') : ?>
                    <div class="veloria-product-tabs__footer">
                        <?php if ($veloria_footer_url !== '') : ?>
                            <a class="veloria-product-tabs__cta" href="<?php echo esc_url($veloria_footer_url); ?>"<?php
                            if (!empty($veloria_footer_link['is_external'])) {
                                echo ' target="_blank"';
                            }
                            if ($veloria_rel_parts !== []) {
                                echo ' rel="' . esc_attr(implode(' ', $veloria_rel_parts)) . '"';
                            }
                            ?>>
                                <?php echo esc_html($veloria_footer_text); ?>
                            </a>
                        <?php else : ?>
                            <span class="veloria-product-tabs__cta veloria-product-tabs__cta--static"><?php echo esc_html($veloria_footer_text); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
