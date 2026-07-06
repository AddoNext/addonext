<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

if (!class_exists('WooCommerce')) {
    echo '<div class="addonext-product-filter"><p class="addonext-product-filter__placeholder">' . esc_html__('WooCommerce is required for this widget.', 'addonext') . '</p></div>';
    return;
}

global $addonext_wp;

$addonext_settings = $this->get_settings_for_display();
$addonext_id_prefix = 'addonext-pf-' . $this->get_id();

$addonext_panel_title = isset($addonext_settings['panel_title']) ? (string) $addonext_settings['panel_title'] : __('Filter Products', 'addonext');
$addonext_clear_all_text = isset($addonext_settings['clear_all_text']) ? (string) $addonext_settings['clear_all_text'] : __('Clear All', 'addonext');
$addonext_price_section_title = isset($addonext_settings['price_section_title']) ? (string) $addonext_settings['price_section_title'] : __('Price Range', 'addonext');
$addonext_search_section_title = isset($addonext_settings['search_section_title']) ? trim((string) $addonext_settings['search_section_title']) : '';
$addonext_product_search_label = isset($addonext_settings['product_search_label']) ? (string) $addonext_settings['product_search_label'] : __('Search products', 'addonext');
$addonext_product_search_placeholder = isset($addonext_settings['product_search_placeholder']) ? (string) $addonext_settings['product_search_placeholder'] : __('Search by name…', 'addonext');
$addonext_show_product_search = !isset($addonext_settings['show_product_search']) || $addonext_settings['show_product_search'] === 'yes';
$addonext_show_product_search_label = isset($addonext_settings['show_product_search_label']) && $addonext_settings['show_product_search_label'] === 'yes';
$addonext_category_section_title = isset($addonext_settings['category_section_title']) ? (string) $addonext_settings['category_section_title'] : __('Categories', 'addonext');
$addonext_tag_section_title = isset($addonext_settings['tag_section_title']) ? (string) $addonext_settings['tag_section_title'] : __('Tags', 'addonext');
$addonext_brand_section_title = isset($addonext_settings['brand_section_title']) ? (string) $addonext_settings['brand_section_title'] : __('Brand', 'addonext');
$addonext_rating_section_title = isset($addonext_settings['rating_section_title']) ? (string) $addonext_settings['rating_section_title'] : __('Rating', 'addonext');
$addonext_color_section_title = isset($addonext_settings['color_section_title']) ? (string) $addonext_settings['color_section_title'] : __('Color', 'addonext');
$addonext_apply_text = isset($addonext_settings['apply_text']) ? (string) $addonext_settings['apply_text'] : __('Apply Filters', 'addonext');

$addonext_show_category = !isset($addonext_settings['show_category']) || $addonext_settings['show_category'] === 'yes';
$addonext_show_tag = !isset($addonext_settings['show_tag']) || $addonext_settings['show_tag'] === 'yes';
$addonext_show_brand = !isset($addonext_settings['show_brand']) || $addonext_settings['show_brand'] === 'yes';
$addonext_show_rating = !isset($addonext_settings['show_rating']) || $addonext_settings['show_rating'] === 'yes';
$addonext_show_color = isset($addonext_settings['show_color']) && $addonext_settings['show_color'] === 'yes';

$addonext_brand_taxonomy = isset($addonext_settings['brand_taxonomy']) ? sanitize_key((string) $addonext_settings['brand_taxonomy']) : '';
if ($addonext_brand_taxonomy === '') {
    $addonext_brand_taxonomy = 'pa_brand';
}
$addonext_color_taxonomy = isset($addonext_settings['color_taxonomy']) ? sanitize_key((string) $addonext_settings['color_taxonomy']) : '';
if ($addonext_color_taxonomy === '') {
    $addonext_color_taxonomy = 'pa_color';
}

if ($addonext_show_brand && !taxonomy_exists($addonext_brand_taxonomy)) {
    foreach (['product_brand', 'pwb-brand', 'pa_brand', 'berocket_brand', 'yith_product_brand'] as $addonext_try_brand_tax) {
        if (taxonomy_exists($addonext_try_brand_tax)) {
            $addonext_brand_taxonomy = $addonext_try_brand_tax;
            break;
        }
    }
}


$addonext_lower_price_limit = isset($addonext_settings['min_price']) ? max(0, (int) $addonext_settings['min_price']) : 0;
$addonext_upper_price_limit = isset($addonext_settings['max_price']) ? max($addonext_lower_price_limit + 1, (int) $addonext_settings['max_price']) : 500;
$addonext_default_min_price = isset($addonext_settings['default_min_price']) ? max($addonext_lower_price_limit, (int) $addonext_settings['default_min_price']) : $addonext_lower_price_limit;
$addonext_default_max_price = isset($addonext_settings['default_max_price']) ? max($addonext_default_min_price, (int) $addonext_settings['default_max_price']) : $addonext_upper_price_limit;
$addonext_default_max_price = min($addonext_upper_price_limit, $addonext_default_max_price);

$addonext_current_min_price = isset($_GET['min_price']) ? max($addonext_lower_price_limit, (int) wp_unslash($_GET['min_price'])) : $addonext_default_min_price;
$addonext_current_max_price = isset($_GET['max_price']) ? min($addonext_upper_price_limit, (int) wp_unslash($_GET['max_price'])) : $addonext_default_max_price;
if ($addonext_current_min_price > $addonext_current_max_price) {
    $addonext_current_min_price = $addonext_current_max_price;
}

$addonext_brand_param_name = 'filter_' . wc_attribute_taxonomy_slug($addonext_brand_taxonomy);
$addonext_color_param_name = 'filter_' . wc_attribute_taxonomy_slug($addonext_color_taxonomy);
$addonext_selected_brand_slugs = isset($_GET[$addonext_brand_param_name]) ? array_filter(array_map('sanitize_title', explode(',', (string) wp_unslash($_GET[$addonext_brand_param_name])))) : [];
$addonext_selected_color_slugs = isset($_GET[$addonext_color_param_name]) ? array_filter(array_map('sanitize_title', explode(',', (string) wp_unslash($_GET[$addonext_color_param_name])))) : [];
$addonext_selected_ratings = isset($_GET['rating_filter']) ? array_map('intval', array_filter(explode(',', (string) wp_unslash($_GET['rating_filter'])))) : [];

$addonext_parse_slug_list = static function ($addonext_key): array {
    if (!isset($_GET[$addonext_key])) {
        return [];
    }
    $addonext_raw = wp_unslash($_GET[$addonext_key]);
    if (is_array($addonext_raw)) {
        return array_values(array_filter(array_map('sanitize_title', $addonext_raw)));
    }
    return array_filter(array_map('sanitize_title', explode(',', (string) $addonext_raw)));
};

$addonext_selected_cat_slugs = $addonext_parse_slug_list('product_cat');
$addonext_selected_tag_slugs = $addonext_parse_slug_list('product_tag');

$addonext_brand_hide_empty = isset($addonext_settings['brand_hide_empty_terms']) && $addonext_settings['brand_hide_empty_terms'] === 'yes';
$addonext_brand_terms = [];
if ($addonext_show_brand && taxonomy_exists($addonext_brand_taxonomy)) {
    $addonext_brand_terms = get_terms([
        'taxonomy' => $addonext_brand_taxonomy,
        'hide_empty' => $addonext_brand_hide_empty,
        'orderby' => 'name',
        'order' => 'ASC',
    ]);
    if (is_wp_error($addonext_brand_terms)) {
        $addonext_brand_terms = [];
    }
}
$addonext_color_hide_empty = isset($addonext_settings['color_hide_empty_terms']) && $addonext_settings['color_hide_empty_terms'] === 'yes';
$addonext_color_terms = [];
if ($addonext_show_color && taxonomy_exists($addonext_color_taxonomy)) {
    $addonext_color_terms = get_terms([
        'taxonomy' => $addonext_color_taxonomy,
        'hide_empty' => $addonext_color_hide_empty,
        'orderby' => 'name',
        'order' => 'ASC',
    ]);
    if (is_wp_error($addonext_color_terms)) {
        $addonext_color_terms = [];
    }
}
$addonext_category_terms = $addonext_show_category ? get_terms(['taxonomy' => 'product_cat', 'hide_empty' => true]) : [];
$addonext_tag_terms = $addonext_show_tag ? get_terms(['taxonomy' => 'product_tag', 'hide_empty' => true]) : [];

$addonext_current_url = home_url(add_query_arg([], $addonext_wp->request ?? ''));
$addonext_query_args = $_GET;
unset(
    $addonext_query_args[$addonext_brand_param_name],
    $addonext_query_args[$addonext_color_param_name],
    $addonext_query_args['rating_filter'],
    $addonext_query_args['addonext_min_rating'],
    $addonext_query_args['min_price'],
    $addonext_query_args['max_price'],
    $addonext_query_args['product_cat'],
    $addonext_query_args['product_tag'],
    $addonext_query_args['paged'],
    $addonext_query_args['s'],
    $addonext_query_args['post_type'],
    $addonext_query_args['addonext_pf']
);
$addonext_clear_url = add_query_arg($addonext_query_args, $addonext_current_url);

$addonext_min_price_id = $addonext_id_prefix . '-min-price';
$addonext_max_price_id = $addonext_id_prefix . '-max-price';
$addonext_range_min_id = $addonext_id_prefix . '-range-min';
$addonext_range_max_id = $addonext_id_prefix . '-range-max';
$addonext_rating_hidden_id = $addonext_id_prefix . '-rating-hidden';
$addonext_product_search_id = $addonext_id_prefix . '-product-search';

$addonext_current_search = isset($_GET['s']) ? sanitize_text_field(wp_unslash((string) $_GET['s'])) : '';

$addonext_initial_rating_ui = 0.0;
if (isset($_GET['addonext_min_rating']) && is_numeric($_GET['addonext_min_rating'])) {
    $addonext_initial_rating_ui = min(5, max(0, round((float) wp_unslash($_GET['addonext_min_rating']) * 2) / 2));
} elseif (!empty($addonext_selected_ratings)) {
    $addonext_initial_rating_ui = min(5, max(0, (float) max($addonext_selected_ratings)));
}

$addonext_rating_hidden_value = '';
if ($addonext_initial_rating_ui > 0) {
    $addonext_is_whole = abs(fmod($addonext_initial_rating_ui, 1.0)) < 0.001;
    $addonext_rating_hidden_value = $addonext_is_whole
        ? (string) (int) round($addonext_initial_rating_ui)
        : number_format($addonext_initial_rating_ui, 1, '.', '');
}
?>
<form class="addonext-product-filter" method="get" action="<?php echo esc_url($addonext_current_url); ?>">
    <input type="hidden" name="addonext_pf" value="1" />
    <div class="addonext-product-filter__head">
        <h3 class="addonext-product-filter__title"><?php echo esc_html($addonext_panel_title); ?></h3>
        <a class="addonext-product-filter__clear" href="<?php echo esc_url($addonext_clear_url); ?>"><?php echo esc_html($addonext_clear_all_text); ?></a>
    </div>

    <?php if ($addonext_show_product_search) : ?>
        <div class="addonext-product-filter__section addonext-product-filter__section--search">
            <?php if ($addonext_search_section_title !== '') : ?>
                <h4 class="addonext-product-filter__section-title"><?php echo esc_html($addonext_search_section_title); ?></h4>
            <?php endif; ?>
            <div class="addonext-product-filter__search-fields">
                <?php if ($addonext_show_product_search_label) : ?>
                    <label class="addonext-product-filter__search-label" for="<?php echo esc_attr($addonext_product_search_id); ?>"><?php echo esc_html($addonext_product_search_label); ?></label>
                <?php endif; ?>
                <input
                    id="<?php echo esc_attr($addonext_product_search_id); ?>"
                    class="addonext-product-filter__search-input"
                    type="search"
                    name="s"
                    value="<?php echo esc_attr($addonext_current_search); ?>"
                    placeholder="<?php echo esc_attr($addonext_product_search_placeholder); ?>"
                    autocomplete="off"
                    <?php if (!$addonext_show_product_search_label) : ?>
                        aria-label="<?php
                        echo esc_attr(
                            $addonext_product_search_placeholder !== ''
                                ? $addonext_product_search_placeholder
                                : ($addonext_product_search_label !== '' ? $addonext_product_search_label : __('Search', 'addonext'))
                        );
                        ?>"
                    <?php endif; ?>
                />
            </div>
            <input type="hidden" name="post_type" value="product" />
        </div>
    <?php endif; ?>

    <div class="addonext-product-filter__section">
        <h4 class="addonext-product-filter__section-title"><?php echo esc_html($addonext_price_section_title); ?></h4>
        <div class="addonext-product-filter__dual-range">
            <div class="addonext-product-filter__range-rail" aria-hidden="true"></div>
            <div class="addonext-product-filter__range-fill" aria-hidden="true"></div>
            <input
                id="<?php echo esc_attr($addonext_range_min_id); ?>"
                class="addonext-product-filter__range addonext-product-filter__range--min"
                type="range"
                min="<?php echo esc_attr((string) $addonext_lower_price_limit); ?>"
                max="<?php echo esc_attr((string) $addonext_upper_price_limit); ?>"
                value="<?php echo esc_attr((string) $addonext_current_min_price); ?>"
                aria-label="<?php esc_attr_e('Minimum price', 'addonext'); ?>"
                data-min-input="<?php echo esc_attr($addonext_min_price_id); ?>"
                data-max-input="<?php echo esc_attr($addonext_max_price_id); ?>"
                data-role="min"
            />
            <input
                id="<?php echo esc_attr($addonext_range_max_id); ?>"
                class="addonext-product-filter__range addonext-product-filter__range--max"
                type="range"
                min="<?php echo esc_attr((string) $addonext_lower_price_limit); ?>"
                max="<?php echo esc_attr((string) $addonext_upper_price_limit); ?>"
                value="<?php echo esc_attr((string) $addonext_current_max_price); ?>"
                aria-label="<?php esc_attr_e('Maximum price', 'addonext'); ?>"
                data-min-input="<?php echo esc_attr($addonext_min_price_id); ?>"
                data-max-input="<?php echo esc_attr($addonext_max_price_id); ?>"
                data-role="max"
            />
        </div>
        <div class="addonext-product-filter__price-inputs">
            <input id="<?php echo esc_attr($addonext_min_price_id); ?>" type="number" name="min_price" min="<?php echo esc_attr((string) $addonext_lower_price_limit); ?>" max="<?php echo esc_attr((string) $addonext_upper_price_limit); ?>" value="<?php echo esc_attr((string) $addonext_current_min_price); ?>" />
            <span class="addonext-product-filter__dash">-</span>
            <input id="<?php echo esc_attr($addonext_max_price_id); ?>" type="number" name="max_price" min="<?php echo esc_attr((string) $addonext_lower_price_limit); ?>" max="<?php echo esc_attr((string) $addonext_upper_price_limit); ?>" value="<?php echo esc_attr((string) $addonext_current_max_price); ?>" />
        </div>
    </div>

    <?php if ($addonext_show_category) : ?>
        <div class="addonext-product-filter__section">
            <h4 class="addonext-product-filter__section-title"><?php echo esc_html($addonext_category_section_title); ?></h4>
            <?php if (!is_wp_error($addonext_category_terms) && !empty($addonext_category_terms)) : ?>
                <?php foreach ($addonext_category_terms as $addonext_cat_term) : ?>
                    <label class="addonext-product-filter__option">
                        <input type="checkbox" name="product_cat[]" value="<?php echo esc_attr($addonext_cat_term->slug); ?>" <?php checked(in_array($addonext_cat_term->slug, $addonext_selected_cat_slugs, true)); ?> />
                        <span><?php echo esc_html($addonext_cat_term->name); ?> (<?php echo esc_html((string) $addonext_cat_term->count); ?>)</span>
                    </label>
                <?php endforeach; ?>
            <?php else : ?>
                <p class="addonext-product-filter__empty-note"><?php esc_html_e('No categories found.', 'addonext'); ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($addonext_show_tag) : ?>
        <div class="addonext-product-filter__section">
            <h4 class="addonext-product-filter__section-title"><?php echo esc_html($addonext_tag_section_title); ?></h4>
            <?php if (!is_wp_error($addonext_tag_terms) && !empty($addonext_tag_terms)) : ?>
                <?php foreach ($addonext_tag_terms as $addonext_tag_term) : ?>
                    <label class="addonext-product-filter__option">
                        <input type="checkbox" name="product_tag[]" value="<?php echo esc_attr($addonext_tag_term->slug); ?>" <?php checked(in_array($addonext_tag_term->slug, $addonext_selected_tag_slugs, true)); ?> />
                        <span><?php echo esc_html($addonext_tag_term->name); ?> (<?php echo esc_html((string) $addonext_tag_term->count); ?>)</span>
                    </label>
                <?php endforeach; ?>
            <?php else : ?>
                <p class="addonext-product-filter__empty-note"><?php esc_html_e('No tags found.', 'addonext'); ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($addonext_show_brand) : ?>
        <div class="addonext-product-filter__section">
            <h4 class="addonext-product-filter__section-title"><?php echo esc_html($addonext_brand_section_title); ?></h4>
            <?php if (!taxonomy_exists($addonext_brand_taxonomy)) : ?>
                <p class="addonext-product-filter__empty-note"><?php esc_html_e('This brand taxonomy does not exist. In the widget, open Brand taxonomy and choose the one your store uses (for example pa_brand or pwb-brand).', 'addonext'); ?></p>
            <?php elseif (!empty($addonext_brand_terms)) : ?>
                <?php foreach ($addonext_brand_terms as $addonext_brand_term) : ?>
                    <label class="addonext-product-filter__option">
                        <input type="checkbox" value="<?php echo esc_attr($addonext_brand_term->slug); ?>" data-filter-target="<?php echo esc_attr($addonext_brand_param_name); ?>" <?php checked(in_array($addonext_brand_term->slug, $addonext_selected_brand_slugs, true)); ?> />
                        <span><?php echo esc_html($addonext_brand_term->name); ?> (<?php echo esc_html((string) $addonext_brand_term->count); ?>)</span>
                    </label>
                <?php endforeach; ?>
            <?php else : ?>
                <p class="addonext-product-filter__empty-note"><?php esc_html_e('No brand terms returned. In the widget, turn off "Hide brands with no products", or assign products to your brand terms.', 'addonext'); ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($addonext_show_rating) : ?>
        <div class="addonext-product-filter__section">
            <h4 class="addonext-product-filter__section-title"><?php echo esc_html($addonext_rating_section_title); ?></h4>
            <div
                class="addonext-product-filter__rating-stars-wrap"
                data-initial-rating="<?php echo esc_attr((string) $addonext_initial_rating_ui); ?>"
                data-text-none="<?php echo esc_attr(__('No minimum rating', 'addonext')); ?>"
                data-text-hover="<?php echo esc_attr(__('%s stars', 'addonext')); ?>"
                data-text-selected="<?php echo esc_attr(__('%s stars & up', 'addonext')); ?>"
            >
                <div class="addonext-product-filter__rating-stars" role="group" aria-label="<?php echo esc_attr(__('Minimum rating', 'addonext')); ?>">
                    <?php
                    for ($addonext_star = 1; $addonext_star <= 5; $addonext_star += 1) {
                        $addonext_half_val = $addonext_star - 0.5;
                        $addonext_full_val = (float) $addonext_star;
                        ?>
                        <span class="addonext-product-filter__star-pair" data-star="<?php echo esc_attr((string) $addonext_star); ?>">
                            <span class="addonext-product-filter__star-skin" aria-hidden="true">
                                <span class="addonext-product-filter__star-bg">★</span>
                                <span class="addonext-product-filter__star-fill">★</span>
                            </span>
                            <button
                                type="button"
                                class="addonext-product-filter__star-half addonext-product-filter__star-half--left"
                                data-value="<?php echo esc_attr((string) $addonext_half_val); ?>"
                                aria-label="<?php echo esc_attr(sprintf(/* translators: %s: rating like 3.5 */ __('%s stars out of 5', 'addonext'), (string) $addonext_half_val)); ?>"
                            ></button>
                            <button
                                type="button"
                                class="addonext-product-filter__star-half addonext-product-filter__star-half--right"
                                data-value="<?php echo esc_attr((string) $addonext_full_val); ?>"
                                aria-label="<?php echo esc_attr(sprintf(/* translators: %s: rating like 4 */ __('%s stars out of 5', 'addonext'), (string) $addonext_full_val)); ?>"
                            ></button>
                        </span>
                        <?php
                    }
                    ?>
                </div>
                <span class="addonext-product-filter__rating-caption" id="<?php echo esc_attr($addonext_id_prefix); ?>-rating-caption"></span>
                <input
                    type="hidden"
                    name="addonext_min_rating"
                    id="<?php echo esc_attr($addonext_rating_hidden_id); ?>"
                    class="addonext-product-filter__rating-hidden"
                    value="<?php echo esc_attr($addonext_rating_hidden_value); ?>"
                    <?php disabled($addonext_rating_hidden_value === ''); ?>
                />
            </div>
        </div>
    <?php endif; ?>

    <?php if ($addonext_show_color) : ?>
        <div class="addonext-product-filter__section">
            <h4 class="addonext-product-filter__section-title"><?php echo esc_html($addonext_color_section_title); ?></h4>
            <?php if (!taxonomy_exists($addonext_color_taxonomy)) : ?>
                <p class="addonext-product-filter__empty-note"><?php esc_html_e('This color taxonomy does not exist. In the widget, open Color taxonomy and choose the attribute your store uses (for example pa_color).', 'addonext'); ?></p>
            <?php elseif (!is_wp_error($addonext_color_terms) && !empty($addonext_color_terms)) : ?>
                <div class="addonext-product-filter__colors">
                    <?php foreach ($addonext_color_terms as $addonext_color_term) : ?>
                        <?php
                        $addonext_color_slug = $addonext_color_term->slug;
                        $addonext_color_hex = sanitize_hex_color((string) get_term_meta($addonext_color_term->term_id, 'color', true));
                        if (!$addonext_color_hex) {
                            $addonext_fallback = str_replace(['pa_', 'color-', 'colour-'], '', $addonext_color_slug);
                            $addonext_color_hex = sanitize_hex_color((string) $addonext_fallback) ?: '#d1d5db';
                        }
                        ?>
                        <label class="addonext-product-filter__color-option" title="<?php echo esc_attr($addonext_color_term->name); ?>">
                            <input type="checkbox" value="<?php echo esc_attr($addonext_color_slug); ?>" data-filter-target="<?php echo esc_attr($addonext_color_param_name); ?>" <?php checked(in_array($addonext_color_slug, $addonext_selected_color_slugs, true)); ?> />
                            <span data-color="<?php echo esc_attr($addonext_color_hex); ?>"></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p class="addonext-product-filter__empty-note"><?php esc_html_e('No color terms returned. In the widget, turn off "Hide colors with no products", or assign products to your color terms.', 'addonext'); ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php
    foreach ($_GET as $addonext_query_key => $addonext_query_value) {
        if (in_array($addonext_query_key, [$addonext_brand_param_name, $addonext_color_param_name, 'rating_filter', 'addonext_min_rating', 'min_price', 'max_price', 'product_cat', 'product_tag', 'paged', 's', 'post_type', 'addonext_pf'], true)) {
            continue;
        }
        if (is_array($addonext_query_value)) {
            foreach ($addonext_query_value as $addonext_item_value) {
                echo '<input type="hidden" name="' . esc_attr($addonext_query_key) . '[]" value="' . esc_attr((string) wp_unslash($addonext_item_value)) . '" />';
            }
            continue;
        }
        echo '<input type="hidden" name="' . esc_attr($addonext_query_key) . '" value="' . esc_attr((string) wp_unslash($addonext_query_value)) . '" />';
    }
    ?>
    <button class="addonext-product-filter__apply" type="submit"><?php echo esc_html($addonext_apply_text); ?></button>
</form>

<?php
    }
}
