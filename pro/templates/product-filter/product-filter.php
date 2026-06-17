<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

if (!class_exists('WooCommerce')) {
    echo '<div class="veloria-product-filter"><p class="veloria-product-filter__placeholder">' . esc_html__('WooCommerce is required for this widget.', 'veloria') . '</p></div>';
    return;
}

global $veloria_wp;

$veloria_settings = $this->get_settings_for_display();
$veloria_id_prefix = 'veloria-pf-' . $this->get_id();

$veloria_panel_title = isset($veloria_settings['panel_title']) ? (string) $veloria_settings['panel_title'] : __('Filter Products', 'veloria');
$veloria_clear_all_text = isset($veloria_settings['clear_all_text']) ? (string) $veloria_settings['clear_all_text'] : __('Clear All', 'veloria');
$veloria_price_section_title = isset($veloria_settings['price_section_title']) ? (string) $veloria_settings['price_section_title'] : __('Price Range', 'veloria');
$veloria_search_section_title = isset($veloria_settings['search_section_title']) ? trim((string) $veloria_settings['search_section_title']) : '';
$veloria_product_search_label = isset($veloria_settings['product_search_label']) ? (string) $veloria_settings['product_search_label'] : __('Search products', 'veloria');
$veloria_product_search_placeholder = isset($veloria_settings['product_search_placeholder']) ? (string) $veloria_settings['product_search_placeholder'] : __('Search by name…', 'veloria');
$veloria_show_product_search = !isset($veloria_settings['show_product_search']) || $veloria_settings['show_product_search'] === 'yes';
$veloria_show_product_search_label = isset($veloria_settings['show_product_search_label']) && $veloria_settings['show_product_search_label'] === 'yes';
$veloria_category_section_title = isset($veloria_settings['category_section_title']) ? (string) $veloria_settings['category_section_title'] : __('Categories', 'veloria');
$veloria_tag_section_title = isset($veloria_settings['tag_section_title']) ? (string) $veloria_settings['tag_section_title'] : __('Tags', 'veloria');
$veloria_brand_section_title = isset($veloria_settings['brand_section_title']) ? (string) $veloria_settings['brand_section_title'] : __('Brand', 'veloria');
$veloria_rating_section_title = isset($veloria_settings['rating_section_title']) ? (string) $veloria_settings['rating_section_title'] : __('Rating', 'veloria');
$veloria_color_section_title = isset($veloria_settings['color_section_title']) ? (string) $veloria_settings['color_section_title'] : __('Color', 'veloria');
$veloria_apply_text = isset($veloria_settings['apply_text']) ? (string) $veloria_settings['apply_text'] : __('Apply Filters', 'veloria');

$veloria_show_category = !isset($veloria_settings['show_category']) || $veloria_settings['show_category'] === 'yes';
$veloria_show_tag = !isset($veloria_settings['show_tag']) || $veloria_settings['show_tag'] === 'yes';
$veloria_show_brand = !isset($veloria_settings['show_brand']) || $veloria_settings['show_brand'] === 'yes';
$veloria_show_rating = !isset($veloria_settings['show_rating']) || $veloria_settings['show_rating'] === 'yes';
$veloria_show_color = isset($veloria_settings['show_color']) && $veloria_settings['show_color'] === 'yes';

$veloria_brand_taxonomy = isset($veloria_settings['brand_taxonomy']) ? sanitize_key((string) $veloria_settings['brand_taxonomy']) : '';
if ($veloria_brand_taxonomy === '') {
    $veloria_brand_taxonomy = 'pa_brand';
}
$veloria_color_taxonomy = isset($veloria_settings['color_taxonomy']) ? sanitize_key((string) $veloria_settings['color_taxonomy']) : '';
if ($veloria_color_taxonomy === '') {
    $veloria_color_taxonomy = 'pa_color';
}

if ($veloria_show_brand && !taxonomy_exists($veloria_brand_taxonomy)) {
    foreach (['product_brand', 'pwb-brand', 'pa_brand', 'berocket_brand', 'yith_product_brand'] as $veloria_try_brand_tax) {
        if (taxonomy_exists($veloria_try_brand_tax)) {
            $veloria_brand_taxonomy = $veloria_try_brand_tax;
            break;
        }
    }
}


$veloria_lower_price_limit = isset($veloria_settings['min_price']) ? max(0, (int) $veloria_settings['min_price']) : 0;
$veloria_upper_price_limit = isset($veloria_settings['max_price']) ? max($veloria_lower_price_limit + 1, (int) $veloria_settings['max_price']) : 500;
$veloria_default_min_price = isset($veloria_settings['default_min_price']) ? max($veloria_lower_price_limit, (int) $veloria_settings['default_min_price']) : $veloria_lower_price_limit;
$veloria_default_max_price = isset($veloria_settings['default_max_price']) ? max($veloria_default_min_price, (int) $veloria_settings['default_max_price']) : $veloria_upper_price_limit;
$veloria_default_max_price = min($veloria_upper_price_limit, $veloria_default_max_price);

$veloria_current_min_price = isset($_GET['min_price']) ? max($veloria_lower_price_limit, (int) wp_unslash($_GET['min_price'])) : $veloria_default_min_price;
$veloria_current_max_price = isset($_GET['max_price']) ? min($veloria_upper_price_limit, (int) wp_unslash($_GET['max_price'])) : $veloria_default_max_price;
if ($veloria_current_min_price > $veloria_current_max_price) {
    $veloria_current_min_price = $veloria_current_max_price;
}

$veloria_brand_param_name = 'filter_' . wc_attribute_taxonomy_slug($veloria_brand_taxonomy);
$veloria_color_param_name = 'filter_' . wc_attribute_taxonomy_slug($veloria_color_taxonomy);
$veloria_selected_brand_slugs = isset($_GET[$veloria_brand_param_name]) ? array_filter(array_map('sanitize_title', explode(',', (string) wp_unslash($_GET[$veloria_brand_param_name])))) : [];
$veloria_selected_color_slugs = isset($_GET[$veloria_color_param_name]) ? array_filter(array_map('sanitize_title', explode(',', (string) wp_unslash($_GET[$veloria_color_param_name])))) : [];
$veloria_selected_ratings = isset($_GET['rating_filter']) ? array_map('intval', array_filter(explode(',', (string) wp_unslash($_GET['rating_filter'])))) : [];

$veloria_parse_slug_list = static function ($veloria_key): array {
    if (!isset($_GET[$veloria_key])) {
        return [];
    }
    $veloria_raw = wp_unslash($_GET[$veloria_key]);
    if (is_array($veloria_raw)) {
        return array_values(array_filter(array_map('sanitize_title', $veloria_raw)));
    }
    return array_filter(array_map('sanitize_title', explode(',', (string) $veloria_raw)));
};

$veloria_selected_cat_slugs = $veloria_parse_slug_list('product_cat');
$veloria_selected_tag_slugs = $veloria_parse_slug_list('product_tag');

$veloria_brand_hide_empty = isset($veloria_settings['brand_hide_empty_terms']) && $veloria_settings['brand_hide_empty_terms'] === 'yes';
$veloria_brand_terms = [];
if ($veloria_show_brand && taxonomy_exists($veloria_brand_taxonomy)) {
    $veloria_brand_terms = get_terms([
        'taxonomy' => $veloria_brand_taxonomy,
        'hide_empty' => $veloria_brand_hide_empty,
        'orderby' => 'name',
        'order' => 'ASC',
    ]);
    if (is_wp_error($veloria_brand_terms)) {
        $veloria_brand_terms = [];
    }
}
$veloria_color_hide_empty = isset($veloria_settings['color_hide_empty_terms']) && $veloria_settings['color_hide_empty_terms'] === 'yes';
$veloria_color_terms = [];
if ($veloria_show_color && taxonomy_exists($veloria_color_taxonomy)) {
    $veloria_color_terms = get_terms([
        'taxonomy' => $veloria_color_taxonomy,
        'hide_empty' => $veloria_color_hide_empty,
        'orderby' => 'name',
        'order' => 'ASC',
    ]);
    if (is_wp_error($veloria_color_terms)) {
        $veloria_color_terms = [];
    }
}
$veloria_category_terms = $veloria_show_category ? get_terms(['taxonomy' => 'product_cat', 'hide_empty' => true]) : [];
$veloria_tag_terms = $veloria_show_tag ? get_terms(['taxonomy' => 'product_tag', 'hide_empty' => true]) : [];

$veloria_current_url = home_url(add_query_arg([], $veloria_wp->request ?? ''));
$veloria_query_args = $_GET;
unset(
    $veloria_query_args[$veloria_brand_param_name],
    $veloria_query_args[$veloria_color_param_name],
    $veloria_query_args['rating_filter'],
    $veloria_query_args['veloria_min_rating'],
    $veloria_query_args['min_price'],
    $veloria_query_args['max_price'],
    $veloria_query_args['product_cat'],
    $veloria_query_args['product_tag'],
    $veloria_query_args['paged'],
    $veloria_query_args['s'],
    $veloria_query_args['post_type'],
    $veloria_query_args['veloria_pf']
);
$veloria_clear_url = add_query_arg($veloria_query_args, $veloria_current_url);

$veloria_min_price_id = $veloria_id_prefix . '-min-price';
$veloria_max_price_id = $veloria_id_prefix . '-max-price';
$veloria_range_min_id = $veloria_id_prefix . '-range-min';
$veloria_range_max_id = $veloria_id_prefix . '-range-max';
$veloria_rating_hidden_id = $veloria_id_prefix . '-rating-hidden';
$veloria_product_search_id = $veloria_id_prefix . '-product-search';

$veloria_current_search = isset($_GET['s']) ? sanitize_text_field(wp_unslash((string) $_GET['s'])) : '';

$veloria_initial_rating_ui = 0.0;
if (isset($_GET['veloria_min_rating']) && is_numeric($_GET['veloria_min_rating'])) {
    $veloria_initial_rating_ui = min(5, max(0, round((float) wp_unslash($_GET['veloria_min_rating']) * 2) / 2));
} elseif (!empty($veloria_selected_ratings)) {
    $veloria_initial_rating_ui = min(5, max(0, (float) max($veloria_selected_ratings)));
}

$veloria_rating_hidden_value = '';
if ($veloria_initial_rating_ui > 0) {
    $veloria_is_whole = abs(fmod($veloria_initial_rating_ui, 1.0)) < 0.001;
    $veloria_rating_hidden_value = $veloria_is_whole
        ? (string) (int) round($veloria_initial_rating_ui)
        : number_format($veloria_initial_rating_ui, 1, '.', '');
}
?>
<form class="veloria-product-filter" method="get" action="<?php echo esc_url($veloria_current_url); ?>">
    <input type="hidden" name="veloria_pf" value="1" />
    <div class="veloria-product-filter__head">
        <h3 class="veloria-product-filter__title"><?php echo esc_html($veloria_panel_title); ?></h3>
        <a class="veloria-product-filter__clear" href="<?php echo esc_url($veloria_clear_url); ?>"><?php echo esc_html($veloria_clear_all_text); ?></a>
    </div>

    <?php if ($veloria_show_product_search) : ?>
        <div class="veloria-product-filter__section veloria-product-filter__section--search">
            <?php if ($veloria_search_section_title !== '') : ?>
                <h4 class="veloria-product-filter__section-title"><?php echo esc_html($veloria_search_section_title); ?></h4>
            <?php endif; ?>
            <div class="veloria-product-filter__search-fields">
                <?php if ($veloria_show_product_search_label) : ?>
                    <label class="veloria-product-filter__search-label" for="<?php echo esc_attr($veloria_product_search_id); ?>"><?php echo esc_html($veloria_product_search_label); ?></label>
                <?php endif; ?>
                <input
                    id="<?php echo esc_attr($veloria_product_search_id); ?>"
                    class="veloria-product-filter__search-input"
                    type="search"
                    name="s"
                    value="<?php echo esc_attr($veloria_current_search); ?>"
                    placeholder="<?php echo esc_attr($veloria_product_search_placeholder); ?>"
                    autocomplete="off"
                    <?php if (!$veloria_show_product_search_label) : ?>
                        aria-label="<?php
                        echo esc_attr(
                            $veloria_product_search_placeholder !== ''
                                ? $veloria_product_search_placeholder
                                : ($veloria_product_search_label !== '' ? $veloria_product_search_label : __('Search', 'veloria'))
                        );
                        ?>"
                    <?php endif; ?>
                />
            </div>
            <input type="hidden" name="post_type" value="product" />
        </div>
    <?php endif; ?>

    <div class="veloria-product-filter__section">
        <h4 class="veloria-product-filter__section-title"><?php echo esc_html($veloria_price_section_title); ?></h4>
        <div class="veloria-product-filter__dual-range">
            <div class="veloria-product-filter__range-rail" aria-hidden="true"></div>
            <div class="veloria-product-filter__range-fill" aria-hidden="true"></div>
            <input
                id="<?php echo esc_attr($veloria_range_min_id); ?>"
                class="veloria-product-filter__range veloria-product-filter__range--min"
                type="range"
                min="<?php echo esc_attr((string) $veloria_lower_price_limit); ?>"
                max="<?php echo esc_attr((string) $veloria_upper_price_limit); ?>"
                value="<?php echo esc_attr((string) $veloria_current_min_price); ?>"
                aria-label="<?php esc_attr_e('Minimum price', 'veloria'); ?>"
                data-min-input="<?php echo esc_attr($veloria_min_price_id); ?>"
                data-max-input="<?php echo esc_attr($veloria_max_price_id); ?>"
                data-role="min"
            />
            <input
                id="<?php echo esc_attr($veloria_range_max_id); ?>"
                class="veloria-product-filter__range veloria-product-filter__range--max"
                type="range"
                min="<?php echo esc_attr((string) $veloria_lower_price_limit); ?>"
                max="<?php echo esc_attr((string) $veloria_upper_price_limit); ?>"
                value="<?php echo esc_attr((string) $veloria_current_max_price); ?>"
                aria-label="<?php esc_attr_e('Maximum price', 'veloria'); ?>"
                data-min-input="<?php echo esc_attr($veloria_min_price_id); ?>"
                data-max-input="<?php echo esc_attr($veloria_max_price_id); ?>"
                data-role="max"
            />
        </div>
        <div class="veloria-product-filter__price-inputs">
            <input id="<?php echo esc_attr($veloria_min_price_id); ?>" type="number" name="min_price" min="<?php echo esc_attr((string) $veloria_lower_price_limit); ?>" max="<?php echo esc_attr((string) $veloria_upper_price_limit); ?>" value="<?php echo esc_attr((string) $veloria_current_min_price); ?>" />
            <span class="veloria-product-filter__dash">-</span>
            <input id="<?php echo esc_attr($veloria_max_price_id); ?>" type="number" name="max_price" min="<?php echo esc_attr((string) $veloria_lower_price_limit); ?>" max="<?php echo esc_attr((string) $veloria_upper_price_limit); ?>" value="<?php echo esc_attr((string) $veloria_current_max_price); ?>" />
        </div>
    </div>

    <?php if ($veloria_show_category) : ?>
        <div class="veloria-product-filter__section">
            <h4 class="veloria-product-filter__section-title"><?php echo esc_html($veloria_category_section_title); ?></h4>
            <?php if (!is_wp_error($veloria_category_terms) && !empty($veloria_category_terms)) : ?>
                <?php foreach ($veloria_category_terms as $veloria_cat_term) : ?>
                    <label class="veloria-product-filter__option">
                        <input type="checkbox" name="product_cat[]" value="<?php echo esc_attr($veloria_cat_term->slug); ?>" <?php checked(in_array($veloria_cat_term->slug, $veloria_selected_cat_slugs, true)); ?> />
                        <span><?php echo esc_html($veloria_cat_term->name); ?> (<?php echo esc_html((string) $veloria_cat_term->count); ?>)</span>
                    </label>
                <?php endforeach; ?>
            <?php else : ?>
                <p class="veloria-product-filter__empty-note"><?php esc_html_e('No categories found.', 'veloria'); ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($veloria_show_tag) : ?>
        <div class="veloria-product-filter__section">
            <h4 class="veloria-product-filter__section-title"><?php echo esc_html($veloria_tag_section_title); ?></h4>
            <?php if (!is_wp_error($veloria_tag_terms) && !empty($veloria_tag_terms)) : ?>
                <?php foreach ($veloria_tag_terms as $veloria_tag_term) : ?>
                    <label class="veloria-product-filter__option">
                        <input type="checkbox" name="product_tag[]" value="<?php echo esc_attr($veloria_tag_term->slug); ?>" <?php checked(in_array($veloria_tag_term->slug, $veloria_selected_tag_slugs, true)); ?> />
                        <span><?php echo esc_html($veloria_tag_term->name); ?> (<?php echo esc_html((string) $veloria_tag_term->count); ?>)</span>
                    </label>
                <?php endforeach; ?>
            <?php else : ?>
                <p class="veloria-product-filter__empty-note"><?php esc_html_e('No tags found.', 'veloria'); ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($veloria_show_brand) : ?>
        <div class="veloria-product-filter__section">
            <h4 class="veloria-product-filter__section-title"><?php echo esc_html($veloria_brand_section_title); ?></h4>
            <?php if (!taxonomy_exists($veloria_brand_taxonomy)) : ?>
                <p class="veloria-product-filter__empty-note"><?php esc_html_e('This brand taxonomy does not exist. In the widget, open Brand taxonomy and choose the one your store uses (for example pa_brand or pwb-brand).', 'veloria'); ?></p>
            <?php elseif (!empty($veloria_brand_terms)) : ?>
                <?php foreach ($veloria_brand_terms as $veloria_brand_term) : ?>
                    <label class="veloria-product-filter__option">
                        <input type="checkbox" value="<?php echo esc_attr($veloria_brand_term->slug); ?>" data-filter-target="<?php echo esc_attr($veloria_brand_param_name); ?>" <?php checked(in_array($veloria_brand_term->slug, $veloria_selected_brand_slugs, true)); ?> />
                        <span><?php echo esc_html($veloria_brand_term->name); ?> (<?php echo esc_html((string) $veloria_brand_term->count); ?>)</span>
                    </label>
                <?php endforeach; ?>
            <?php else : ?>
                <p class="veloria-product-filter__empty-note"><?php esc_html_e('No brand terms returned. In the widget, turn off "Hide brands with no products", or assign products to your brand terms.', 'veloria'); ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($veloria_show_rating) : ?>
        <div class="veloria-product-filter__section">
            <h4 class="veloria-product-filter__section-title"><?php echo esc_html($veloria_rating_section_title); ?></h4>
            <div
                class="veloria-product-filter__rating-stars-wrap"
                data-initial-rating="<?php echo esc_attr((string) $veloria_initial_rating_ui); ?>"
                data-text-none="<?php echo esc_attr(__('No minimum rating', 'veloria')); ?>"
                data-text-hover="<?php echo esc_attr(__('%s stars', 'veloria')); ?>"
                data-text-selected="<?php echo esc_attr(__('%s stars & up', 'veloria')); ?>"
            >
                <div class="veloria-product-filter__rating-stars" role="group" aria-label="<?php echo esc_attr(__('Minimum rating', 'veloria')); ?>">
                    <?php
                    for ($veloria_star = 1; $veloria_star <= 5; $veloria_star += 1) {
                        $veloria_half_val = $veloria_star - 0.5;
                        $veloria_full_val = (float) $veloria_star;
                        ?>
                        <span class="veloria-product-filter__star-pair" data-star="<?php echo esc_attr((string) $veloria_star); ?>">
                            <span class="veloria-product-filter__star-skin" aria-hidden="true">
                                <span class="veloria-product-filter__star-bg">★</span>
                                <span class="veloria-product-filter__star-fill">★</span>
                            </span>
                            <button
                                type="button"
                                class="veloria-product-filter__star-half veloria-product-filter__star-half--left"
                                data-value="<?php echo esc_attr((string) $veloria_half_val); ?>"
                                aria-label="<?php echo esc_attr(sprintf(/* translators: %s: rating like 3.5 */ __('%s stars out of 5', 'veloria'), (string) $veloria_half_val)); ?>"
                            ></button>
                            <button
                                type="button"
                                class="veloria-product-filter__star-half veloria-product-filter__star-half--right"
                                data-value="<?php echo esc_attr((string) $veloria_full_val); ?>"
                                aria-label="<?php echo esc_attr(sprintf(/* translators: %s: rating like 4 */ __('%s stars out of 5', 'veloria'), (string) $veloria_full_val)); ?>"
                            ></button>
                        </span>
                        <?php
                    }
                    ?>
                </div>
                <span class="veloria-product-filter__rating-caption" id="<?php echo esc_attr($veloria_id_prefix); ?>-rating-caption"></span>
                <input
                    type="hidden"
                    name="veloria_min_rating"
                    id="<?php echo esc_attr($veloria_rating_hidden_id); ?>"
                    class="veloria-product-filter__rating-hidden"
                    value="<?php echo esc_attr($veloria_rating_hidden_value); ?>"
                    <?php disabled($veloria_rating_hidden_value === ''); ?>
                />
            </div>
        </div>
    <?php endif; ?>

    <?php if ($veloria_show_color) : ?>
        <div class="veloria-product-filter__section">
            <h4 class="veloria-product-filter__section-title"><?php echo esc_html($veloria_color_section_title); ?></h4>
            <?php if (!taxonomy_exists($veloria_color_taxonomy)) : ?>
                <p class="veloria-product-filter__empty-note"><?php esc_html_e('This color taxonomy does not exist. In the widget, open Color taxonomy and choose the attribute your store uses (for example pa_color).', 'veloria'); ?></p>
            <?php elseif (!is_wp_error($veloria_color_terms) && !empty($veloria_color_terms)) : ?>
                <div class="veloria-product-filter__colors">
                    <?php foreach ($veloria_color_terms as $veloria_color_term) : ?>
                        <?php
                        $veloria_color_slug = $veloria_color_term->slug;
                        $veloria_color_hex = sanitize_hex_color((string) get_term_meta($veloria_color_term->term_id, 'color', true));
                        if (!$veloria_color_hex) {
                            $veloria_fallback = str_replace(['pa_', 'color-', 'colour-'], '', $veloria_color_slug);
                            $veloria_color_hex = sanitize_hex_color((string) $veloria_fallback) ?: '#d1d5db';
                        }
                        ?>
                        <label class="veloria-product-filter__color-option" title="<?php echo esc_attr($veloria_color_term->name); ?>">
                            <input type="checkbox" value="<?php echo esc_attr($veloria_color_slug); ?>" data-filter-target="<?php echo esc_attr($veloria_color_param_name); ?>" <?php checked(in_array($veloria_color_slug, $veloria_selected_color_slugs, true)); ?> />
                            <span data-color="<?php echo esc_attr($veloria_color_hex); ?>"></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p class="veloria-product-filter__empty-note"><?php esc_html_e('No color terms returned. In the widget, turn off "Hide colors with no products", or assign products to your color terms.', 'veloria'); ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php
    foreach ($_GET as $veloria_query_key => $veloria_query_value) {
        if (in_array($veloria_query_key, [$veloria_brand_param_name, $veloria_color_param_name, 'rating_filter', 'veloria_min_rating', 'min_price', 'max_price', 'product_cat', 'product_tag', 'paged', 's', 'post_type', 'veloria_pf'], true)) {
            continue;
        }
        if (is_array($veloria_query_value)) {
            foreach ($veloria_query_value as $veloria_item_value) {
                echo '<input type="hidden" name="' . esc_attr($veloria_query_key) . '[]" value="' . esc_attr((string) wp_unslash($veloria_item_value)) . '" />';
            }
            continue;
        }
        echo '<input type="hidden" name="' . esc_attr($veloria_query_key) . '" value="' . esc_attr((string) wp_unslash($veloria_query_value)) . '" />';
    }
    ?>
    <button class="veloria-product-filter__apply" type="submit"><?php echo esc_html($veloria_apply_text); ?></button>
</form>

<?php
    }
}
