<?php
defined('ABSPATH') || exit;

if (!class_exists('WooCommerce')) {
    echo '<div class="addonext-product-tabs__placeholder">' . esc_html__('WooCommerce is required.', 'addonext') . '</div>';
    return;
}

$addonext_settings = $this->get_settings_for_display();
$addonext_tabs = isset($addonext_settings['tabs']) && is_array($addonext_settings['tabs']) ? $addonext_settings['tabs'] : [];

if ($addonext_tabs === []) {
    echo '<div class="addonext-product-tabs__placeholder">' . esc_html__('Add tabs in the widget settings.', 'addonext') . '</div>';
    return;
}

$addonext_columns = isset($addonext_settings['columns']) ? max(1, min(6, (int) $addonext_settings['columns'])) : 4;
$addonext_columns_tablet = isset($addonext_settings['columns_tablet']) ? max(1, min(4, (int) $addonext_settings['columns_tablet'])) : 2;
$addonext_columns_mobile = isset($addonext_settings['columns_mobile']) ? max(1, min(2, (int) $addonext_settings['columns_mobile'])) : 1;
$addonext_column_gap = isset($addonext_settings['column_gap']) ? max(0, min(60, (int) $addonext_settings['column_gap'])) : 16;
$addonext_row_gap = isset($addonext_settings['row_gap']) ? max(0, min(60, (int) $addonext_settings['row_gap'])) : 16;

$addonext_show_rating = isset($addonext_settings['show_rating']) && $addonext_settings['show_rating'] === 'yes';
$addonext_show_short_desc = isset($addonext_settings['show_short_desc']) && $addonext_settings['show_short_desc'] === 'yes';
$addonext_show_old_price = isset($addonext_settings['show_old_price']) && $addonext_settings['show_old_price'] === 'yes';
$addonext_show_badges = isset($addonext_settings['show_badges']) && $addonext_settings['show_badges'] === 'yes';
$addonext_show_wishlist = isset($addonext_settings['show_wishlist']) && $addonext_settings['show_wishlist'] === 'yes';
$addonext_show_add_to_cart = isset($addonext_settings['show_add_to_cart']) && $addonext_settings['show_add_to_cart'] === 'yes';
$addonext_image_hover_effect = isset($addonext_settings['image_hover_effect']) ? (string) $addonext_settings['image_hover_effect'] : 'zoom-out';

$addonext_cart_url = '';
if (function_exists('wc_get_page_id')) {
    $addonext_cart_page_id = (int) wc_get_page_id('cart');
    if ($addonext_cart_page_id > 0) {
        $addonext_cart_url = (string) get_permalink($addonext_cart_page_id);
    }
}
if ($addonext_cart_url === '' && function_exists('wc_get_cart_url')) {
    $addonext_cart_url = (string) wc_get_cart_url();
}
$addonext_home_base = untrailingslashit(home_url());
$addonext_cart_base = $addonext_cart_url !== '' ? untrailingslashit($addonext_cart_url) : '';
if ($addonext_cart_base === '' || $addonext_cart_base === $addonext_home_base) {
    $addonext_cart_url = '';
}

$addonext_widget_uid = 'ept-' . preg_replace('/[^a-z0-9\-]/i', '', (string) $this->get_id());
$addonext_prefix = 'addonext-product-grid';
$addonext_card_effect = preg_replace('/[^a-z\-]/', '', (string) $addonext_image_hover_effect);
if ($addonext_card_effect === '') {
    $addonext_card_effect = 'none';
}
$addonext_card_hover_class = $addonext_prefix . '__image-wrap--' . $addonext_card_effect;
$addonext_tabs_grid_vars = sprintf(
    '--ept-cols-desktop:%d;--ept-cols-tablet:%d;--ept-cols-mobile:%d;--ept-col-gap:%dpx;--ept-row-gap:%dpx',
    $addonext_columns,
    $addonext_columns_tablet,
    $addonext_columns_mobile,
    $addonext_column_gap,
    $addonext_row_gap
);
?>
<div class="addonext-product-tabs" data-addonext-product-tabs="1" style="<?php echo esc_attr($addonext_tabs_grid_vars); ?>">
    <div class="addonext-product-tabs__nav" role="tablist" aria-label="<?php esc_attr_e('Product collections', 'addonext'); ?>">
        <?php foreach ($addonext_tabs as $addonext_ti => $addonext_tab_row) : ?>
            <?php
            if (!is_array($addonext_tab_row)) {
                continue;
            }
            $addonext_tab_title = isset($addonext_tab_row['tab_title']) ? (string) $addonext_tab_row['tab_title'] : '';
            if (trim($addonext_tab_title) === '') {
                $addonext_tab_title = sprintf(/* translators: %d: tab index */ __('Tab %d', 'addonext'), (int) $addonext_ti + 1);
            }
            $addonext_tab_btn_id = $addonext_widget_uid . '-tab-' . (int) $addonext_ti;
            $addonext_tab_panel_id = $addonext_widget_uid . '-panel-' . (int) $addonext_ti;
            $addonext_is_first = ((int) $addonext_ti === 0);
            ?>
            <button
                type="button"
                class="addonext-product-tabs__tab elementor-clickable<?php echo $addonext_is_first ? ' is-active' : ''; ?>"
                role="tab"
                id="<?php echo esc_attr($addonext_tab_btn_id); ?>"
                aria-selected="<?php echo $addonext_is_first ? 'true' : 'false'; ?>"
                aria-controls="<?php echo esc_attr($addonext_tab_panel_id); ?>"
                tabindex="<?php echo $addonext_is_first ? '0' : '-1'; ?>"
            ><?php echo esc_html($addonext_tab_title); ?></button>
        <?php endforeach; ?>
    </div>

    <div class="addonext-product-tabs__panels">
        <?php foreach ($addonext_tabs as $addonext_ti => $addonext_tab_row) : ?>
            <?php
            if (!is_array($addonext_tab_row)) {
                continue;
            }
            $addonext_query_args = \Addonext\Widgets\ProductTabs\ProductTabs::build_tab_row_query_args($addonext_tab_row);
            $addonext_product_query = new \WP_Query($addonext_query_args);

            $addonext_tab_btn_id = $addonext_widget_uid . '-tab-' . (int) $addonext_ti;
            $addonext_tab_panel_id = $addonext_widget_uid . '-panel-' . (int) $addonext_ti;
            $addonext_is_first = ((int) $addonext_ti === 0);

            $addonext_footer_text = isset($addonext_tab_row['footer_button_text']) ? trim((string) $addonext_tab_row['footer_button_text']) : '';
            $addonext_footer_link = isset($addonext_tab_row['footer_button_link']) && is_array($addonext_tab_row['footer_button_link']) ? $addonext_tab_row['footer_button_link'] : [];
            $addonext_footer_url = isset($addonext_footer_link['url']) ? trim((string) $addonext_footer_link['url']) : '';
            $addonext_rel_parts = [];
            if (!empty($addonext_footer_link['nofollow'])) {
                $addonext_rel_parts[] = 'nofollow';
            }
            if (!empty($addonext_footer_link['is_external'])) {
                $addonext_rel_parts[] = 'noopener';
                $addonext_rel_parts[] = 'noreferrer';
            }
            ?>
            <div
                class="addonext-product-tabs__panel<?php echo $addonext_is_first ? ' is-active' : ''; ?>"
                id="<?php echo esc_attr($addonext_tab_panel_id); ?>"
                role="tabpanel"
                aria-labelledby="<?php echo esc_attr($addonext_tab_btn_id); ?>"
                <?php echo $addonext_is_first ? '' : 'hidden'; ?>
            >
                <div
                    class="addonext-product-tabs__grid-shell"
                    <?php echo $addonext_cart_url !== '' ? 'data-cart-url="' . esc_url($addonext_cart_url) . '"' : ''; ?>
                >
                    <?php if ($addonext_product_query->have_posts()) : ?>
                        <div class="addonext-product-grid addonext-product-tabs__grid">
                            <?php
                            while ($addonext_product_query->have_posts()) :
                                $addonext_product_query->the_post();
                                $addonext_product = wc_get_product(get_the_ID());
                                if (!$addonext_product) {
                                    continue;
                                }
                                $addonext_is_on_sale = $addonext_product->is_on_sale();
                                $addonext_is_featured = $addonext_product->is_featured();
                                $addonext_card_short_desc = $addonext_product->get_short_description();
                                $addonext_category_line = wc_get_product_category_list($addonext_product->get_id(), ' &bull; ', '', '');
                                $addonext_regular_price = $addonext_product->get_regular_price();
                                $addonext_sale_price = $addonext_product->get_sale_price();
                                $addonext_current_price = ($addonext_sale_price !== '') ? $addonext_sale_price : $addonext_product->get_price();
                                $addonext_rating = (float) $addonext_product->get_average_rating();
                                $addonext_rating_count = (int) $addonext_product->get_rating_count();
                                $addonext_permalink = $addonext_product->get_permalink();
                                ?>
                                <article class="<?php echo esc_attr($addonext_prefix); ?>__card">
                                    <div class="<?php echo esc_attr($addonext_prefix); ?>__image-wrap <?php echo esc_attr($addonext_card_hover_class); ?>">
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

                                        <?php if ($addonext_show_short_desc && $addonext_card_short_desc !== '') : ?>
                                            <div class="<?php echo esc_attr($addonext_prefix); ?>__desc"><?php echo esc_html(wp_trim_words(wp_strip_all_tags($addonext_card_short_desc), 7, '')); ?></div>
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
                                <?php
                            endwhile;
                            wp_reset_postdata();
                            ?>
                        </div>
                    <?php else : ?>
                        <div class="addonext-product-grid__placeholder"><?php esc_html_e('No products found for this tab.', 'addonext'); ?></div>
                    <?php endif; ?>
                </div>

                <?php if ($addonext_footer_text !== '') : ?>
                    <div class="addonext-product-tabs__footer">
                        <?php if ($addonext_footer_url !== '') : ?>
                            <a class="addonext-product-tabs__cta" href="<?php echo esc_url($addonext_footer_url); ?>"<?php
                            if (!empty($addonext_footer_link['is_external'])) {
                                echo ' target="_blank"';
                            }
                            if ($addonext_rel_parts !== []) {
                                echo ' rel="' . esc_attr(implode(' ', $addonext_rel_parts)) . '"';
                            }
                            ?>>
                                <?php echo esc_html($addonext_footer_text); ?>
                            </a>
                        <?php else : ?>
                            <span class="addonext-product-tabs__cta addonext-product-tabs__cta--static"><?php echo esc_html($addonext_footer_text); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
