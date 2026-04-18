<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

if (!class_exists('WooCommerce')) {
    echo '<div class="elementrify-product-filter"><p class="elementrify-product-filter__placeholder">' . esc_html__('WooCommerce is required for this widget.', 'elementrify') . '</p></div>';
    return;
}

global $wp;

$settings = $this->get_settings_for_display();
$id_prefix = 'elementrify-pf-' . $this->get_id();

$panel_title = isset($settings['panel_title']) ? (string) $settings['panel_title'] : __('Filter Products', 'elementrify');
$clear_all_text = isset($settings['clear_all_text']) ? (string) $settings['clear_all_text'] : __('Clear All', 'elementrify');
$price_section_title = isset($settings['price_section_title']) ? (string) $settings['price_section_title'] : __('Price Range', 'elementrify');
$search_section_title = isset($settings['search_section_title']) ? trim((string) $settings['search_section_title']) : '';
$product_search_label = isset($settings['product_search_label']) ? (string) $settings['product_search_label'] : __('Search products', 'elementrify');
$product_search_placeholder = isset($settings['product_search_placeholder']) ? (string) $settings['product_search_placeholder'] : __('Search by name…', 'elementrify');
$show_product_search = !isset($settings['show_product_search']) || $settings['show_product_search'] === 'yes';
$show_product_search_label = isset($settings['show_product_search_label']) && $settings['show_product_search_label'] === 'yes';
$category_section_title = isset($settings['category_section_title']) ? (string) $settings['category_section_title'] : __('Categories', 'elementrify');
$tag_section_title = isset($settings['tag_section_title']) ? (string) $settings['tag_section_title'] : __('Tags', 'elementrify');
$brand_section_title = isset($settings['brand_section_title']) ? (string) $settings['brand_section_title'] : __('Brand', 'elementrify');
$rating_section_title = isset($settings['rating_section_title']) ? (string) $settings['rating_section_title'] : __('Rating', 'elementrify');
$color_section_title = isset($settings['color_section_title']) ? (string) $settings['color_section_title'] : __('Color', 'elementrify');
$apply_text = isset($settings['apply_text']) ? (string) $settings['apply_text'] : __('Apply Filters', 'elementrify');

$show_category = !isset($settings['show_category']) || $settings['show_category'] === 'yes';
$show_tag = !isset($settings['show_tag']) || $settings['show_tag'] === 'yes';
$show_brand = !isset($settings['show_brand']) || $settings['show_brand'] === 'yes';
$show_rating = !isset($settings['show_rating']) || $settings['show_rating'] === 'yes';
$show_color = isset($settings['show_color']) && $settings['show_color'] === 'yes';

$brand_taxonomy = isset($settings['brand_taxonomy']) ? sanitize_key((string) $settings['brand_taxonomy']) : '';
if ($brand_taxonomy === '') {
    $brand_taxonomy = 'pa_brand';
}
$color_taxonomy = isset($settings['color_taxonomy']) ? sanitize_key((string) $settings['color_taxonomy']) : '';
if ($color_taxonomy === '') {
    $color_taxonomy = 'pa_color';
}

if ($show_brand && !taxonomy_exists($brand_taxonomy)) {
    foreach (['product_brand', 'pwb-brand', 'pa_brand', 'berocket_brand', 'yith_product_brand'] as $try_brand_tax) {
        if (taxonomy_exists($try_brand_tax)) {
            $brand_taxonomy = $try_brand_tax;
            break;
        }
    }
}


$lower_price_limit = isset($settings['min_price']) ? max(0, (int) $settings['min_price']) : 0;
$upper_price_limit = isset($settings['max_price']) ? max($lower_price_limit + 1, (int) $settings['max_price']) : 500;
$default_min_price = isset($settings['default_min_price']) ? max($lower_price_limit, (int) $settings['default_min_price']) : $lower_price_limit;
$default_max_price = isset($settings['default_max_price']) ? max($default_min_price, (int) $settings['default_max_price']) : $upper_price_limit;
$default_max_price = min($upper_price_limit, $default_max_price);

$current_min_price = isset($_GET['min_price']) ? max($lower_price_limit, (int) wp_unslash($_GET['min_price'])) : $default_min_price;
$current_max_price = isset($_GET['max_price']) ? min($upper_price_limit, (int) wp_unslash($_GET['max_price'])) : $default_max_price;
if ($current_min_price > $current_max_price) {
    $current_min_price = $current_max_price;
}

$brand_param_name = 'filter_' . wc_attribute_taxonomy_slug($brand_taxonomy);
$color_param_name = 'filter_' . wc_attribute_taxonomy_slug($color_taxonomy);
$selected_brand_slugs = isset($_GET[$brand_param_name]) ? array_filter(array_map('sanitize_title', explode(',', (string) wp_unslash($_GET[$brand_param_name])))) : [];
$selected_color_slugs = isset($_GET[$color_param_name]) ? array_filter(array_map('sanitize_title', explode(',', (string) wp_unslash($_GET[$color_param_name])))) : [];
$selected_ratings = isset($_GET['rating_filter']) ? array_map('intval', array_filter(explode(',', (string) wp_unslash($_GET['rating_filter'])))) : [];

$parse_slug_list = static function ($key): array {
    if (!isset($_GET[$key])) {
        return [];
    }
    $raw = wp_unslash($_GET[$key]);
    if (is_array($raw)) {
        return array_values(array_filter(array_map('sanitize_title', $raw)));
    }
    return array_filter(array_map('sanitize_title', explode(',', (string) $raw)));
};

$selected_cat_slugs = $parse_slug_list('product_cat');
$selected_tag_slugs = $parse_slug_list('product_tag');

$brand_hide_empty = isset($settings['brand_hide_empty_terms']) && $settings['brand_hide_empty_terms'] === 'yes';
$brand_terms = [];
if ($show_brand && taxonomy_exists($brand_taxonomy)) {
    $brand_terms = get_terms([
        'taxonomy' => $brand_taxonomy,
        'hide_empty' => $brand_hide_empty,
        'orderby' => 'name',
        'order' => 'ASC',
    ]);
    if (is_wp_error($brand_terms)) {
        $brand_terms = [];
    }
}
$color_hide_empty = isset($settings['color_hide_empty_terms']) && $settings['color_hide_empty_terms'] === 'yes';
$color_terms = [];
if ($show_color && taxonomy_exists($color_taxonomy)) {
    $color_terms = get_terms([
        'taxonomy' => $color_taxonomy,
        'hide_empty' => $color_hide_empty,
        'orderby' => 'name',
        'order' => 'ASC',
    ]);
    if (is_wp_error($color_terms)) {
        $color_terms = [];
    }
}
$category_terms = $show_category ? get_terms(['taxonomy' => 'product_cat', 'hide_empty' => true]) : [];
$tag_terms = $show_tag ? get_terms(['taxonomy' => 'product_tag', 'hide_empty' => true]) : [];

$current_url = home_url(add_query_arg([], $wp->request ?? ''));
$query_args = $_GET;
unset(
    $query_args[$brand_param_name],
    $query_args[$color_param_name],
    $query_args['rating_filter'],
    $query_args['elementrify_min_rating'],
    $query_args['min_price'],
    $query_args['max_price'],
    $query_args['product_cat'],
    $query_args['product_tag'],
    $query_args['paged'],
    $query_args['s'],
    $query_args['post_type'],
    $query_args['elementrify_pf']
);
$clear_url = add_query_arg($query_args, $current_url);

$min_price_id = $id_prefix . '-min-price';
$max_price_id = $id_prefix . '-max-price';
$range_min_id = $id_prefix . '-range-min';
$range_max_id = $id_prefix . '-range-max';
$rating_hidden_id = $id_prefix . '-rating-hidden';
$product_search_id = $id_prefix . '-product-search';

$current_search = isset($_GET['s']) ? sanitize_text_field(wp_unslash((string) $_GET['s'])) : '';

$initial_rating_ui = 0.0;
if (isset($_GET['elementrify_min_rating']) && is_numeric($_GET['elementrify_min_rating'])) {
    $initial_rating_ui = min(5, max(0, round((float) wp_unslash($_GET['elementrify_min_rating']) * 2) / 2));
} elseif (!empty($selected_ratings)) {
    $initial_rating_ui = min(5, max(0, (float) max($selected_ratings)));
}

$rating_hidden_value = '';
if ($initial_rating_ui > 0) {
    $is_whole = abs(fmod($initial_rating_ui, 1.0)) < 0.001;
    $rating_hidden_value = $is_whole
        ? (string) (int) round($initial_rating_ui)
        : number_format($initial_rating_ui, 1, '.', '');
}
?>
<form class="elementrify-product-filter" method="get" action="<?php echo esc_url($current_url); ?>">
    <input type="hidden" name="elementrify_pf" value="1" />
    <div class="elementrify-product-filter__head">
        <h3 class="elementrify-product-filter__title"><?php echo esc_html($panel_title); ?></h3>
        <a class="elementrify-product-filter__clear" href="<?php echo esc_url($clear_url); ?>"><?php echo esc_html($clear_all_text); ?></a>
    </div>

    <?php if ($show_product_search) : ?>
        <div class="elementrify-product-filter__section elementrify-product-filter__section--search">
            <?php if ($search_section_title !== '') : ?>
                <h4 class="elementrify-product-filter__section-title"><?php echo esc_html($search_section_title); ?></h4>
            <?php endif; ?>
            <div class="elementrify-product-filter__search-fields">
                <?php if ($show_product_search_label) : ?>
                    <label class="elementrify-product-filter__search-label" for="<?php echo esc_attr($product_search_id); ?>"><?php echo esc_html($product_search_label); ?></label>
                <?php endif; ?>
                <input
                    id="<?php echo esc_attr($product_search_id); ?>"
                    class="elementrify-product-filter__search-input"
                    type="search"
                    name="s"
                    value="<?php echo esc_attr($current_search); ?>"
                    placeholder="<?php echo esc_attr($product_search_placeholder); ?>"
                    autocomplete="off"
                    <?php if (!$show_product_search_label) : ?>
                        aria-label="<?php
                        echo esc_attr(
                            $product_search_placeholder !== ''
                                ? $product_search_placeholder
                                : ($product_search_label !== '' ? $product_search_label : __('Search', 'elementrify'))
                        );
                        ?>"
                    <?php endif; ?>
                />
            </div>
            <input type="hidden" name="post_type" value="product" />
        </div>
    <?php endif; ?>

    <div class="elementrify-product-filter__section">
        <h4 class="elementrify-product-filter__section-title"><?php echo esc_html($price_section_title); ?></h4>
        <div class="elementrify-product-filter__dual-range">
            <div class="elementrify-product-filter__range-rail" aria-hidden="true"></div>
            <div class="elementrify-product-filter__range-fill" aria-hidden="true"></div>
            <input
                id="<?php echo esc_attr($range_min_id); ?>"
                class="elementrify-product-filter__range elementrify-product-filter__range--min"
                type="range"
                min="<?php echo esc_attr((string) $lower_price_limit); ?>"
                max="<?php echo esc_attr((string) $upper_price_limit); ?>"
                value="<?php echo esc_attr((string) $current_min_price); ?>"
                aria-label="<?php esc_attr_e('Minimum price', 'elementrify'); ?>"
                data-min-input="<?php echo esc_attr($min_price_id); ?>"
                data-max-input="<?php echo esc_attr($max_price_id); ?>"
                data-role="min"
            />
            <input
                id="<?php echo esc_attr($range_max_id); ?>"
                class="elementrify-product-filter__range elementrify-product-filter__range--max"
                type="range"
                min="<?php echo esc_attr((string) $lower_price_limit); ?>"
                max="<?php echo esc_attr((string) $upper_price_limit); ?>"
                value="<?php echo esc_attr((string) $current_max_price); ?>"
                aria-label="<?php esc_attr_e('Maximum price', 'elementrify'); ?>"
                data-min-input="<?php echo esc_attr($min_price_id); ?>"
                data-max-input="<?php echo esc_attr($max_price_id); ?>"
                data-role="max"
            />
        </div>
        <div class="elementrify-product-filter__price-inputs">
            <input id="<?php echo esc_attr($min_price_id); ?>" type="number" name="min_price" min="<?php echo esc_attr((string) $lower_price_limit); ?>" max="<?php echo esc_attr((string) $upper_price_limit); ?>" value="<?php echo esc_attr((string) $current_min_price); ?>" />
            <span class="elementrify-product-filter__dash">-</span>
            <input id="<?php echo esc_attr($max_price_id); ?>" type="number" name="max_price" min="<?php echo esc_attr((string) $lower_price_limit); ?>" max="<?php echo esc_attr((string) $upper_price_limit); ?>" value="<?php echo esc_attr((string) $current_max_price); ?>" />
        </div>
    </div>

    <?php if ($show_category) : ?>
        <div class="elementrify-product-filter__section">
            <h4 class="elementrify-product-filter__section-title"><?php echo esc_html($category_section_title); ?></h4>
            <?php if (!is_wp_error($category_terms) && !empty($category_terms)) : ?>
                <?php foreach ($category_terms as $cat_term) : ?>
                    <label class="elementrify-product-filter__option">
                        <input type="checkbox" name="product_cat[]" value="<?php echo esc_attr($cat_term->slug); ?>" <?php checked(in_array($cat_term->slug, $selected_cat_slugs, true)); ?> />
                        <span><?php echo esc_html($cat_term->name); ?> (<?php echo esc_html((string) $cat_term->count); ?>)</span>
                    </label>
                <?php endforeach; ?>
            <?php else : ?>
                <p class="elementrify-product-filter__empty-note"><?php esc_html_e('No categories found.', 'elementrify'); ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($show_tag) : ?>
        <div class="elementrify-product-filter__section">
            <h4 class="elementrify-product-filter__section-title"><?php echo esc_html($tag_section_title); ?></h4>
            <?php if (!is_wp_error($tag_terms) && !empty($tag_terms)) : ?>
                <?php foreach ($tag_terms as $tag_term) : ?>
                    <label class="elementrify-product-filter__option">
                        <input type="checkbox" name="product_tag[]" value="<?php echo esc_attr($tag_term->slug); ?>" <?php checked(in_array($tag_term->slug, $selected_tag_slugs, true)); ?> />
                        <span><?php echo esc_html($tag_term->name); ?> (<?php echo esc_html((string) $tag_term->count); ?>)</span>
                    </label>
                <?php endforeach; ?>
            <?php else : ?>
                <p class="elementrify-product-filter__empty-note"><?php esc_html_e('No tags found.', 'elementrify'); ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($show_brand) : ?>
        <div class="elementrify-product-filter__section">
            <h4 class="elementrify-product-filter__section-title"><?php echo esc_html($brand_section_title); ?></h4>
            <?php if (!taxonomy_exists($brand_taxonomy)) : ?>
                <p class="elementrify-product-filter__empty-note"><?php esc_html_e('This brand taxonomy does not exist. In the widget, open Brand taxonomy and choose the one your store uses (for example pa_brand or pwb-brand).', 'elementrify'); ?></p>
            <?php elseif (!empty($brand_terms)) : ?>
                <?php foreach ($brand_terms as $brand_term) : ?>
                    <label class="elementrify-product-filter__option">
                        <input type="checkbox" value="<?php echo esc_attr($brand_term->slug); ?>" data-filter-target="<?php echo esc_attr($brand_param_name); ?>" <?php checked(in_array($brand_term->slug, $selected_brand_slugs, true)); ?> />
                        <span><?php echo esc_html($brand_term->name); ?> (<?php echo esc_html((string) $brand_term->count); ?>)</span>
                    </label>
                <?php endforeach; ?>
            <?php else : ?>
                <p class="elementrify-product-filter__empty-note"><?php esc_html_e('No brand terms returned. In the widget, turn off "Hide brands with no products", or assign products to your brand terms.', 'elementrify'); ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($show_rating) : ?>
        <div class="elementrify-product-filter__section">
            <h4 class="elementrify-product-filter__section-title"><?php echo esc_html($rating_section_title); ?></h4>
            <div
                class="elementrify-product-filter__rating-stars-wrap"
                data-initial-rating="<?php echo esc_attr((string) $initial_rating_ui); ?>"
                data-text-none="<?php echo esc_attr(__('No minimum rating', 'elementrify')); ?>"
                data-text-hover="<?php echo esc_attr(__('%s stars', 'elementrify')); ?>"
                data-text-selected="<?php echo esc_attr(__('%s stars & up', 'elementrify')); ?>"
            >
                <div class="elementrify-product-filter__rating-stars" role="group" aria-label="<?php echo esc_attr(__('Minimum rating', 'elementrify')); ?>">
                    <?php
                    for ($star = 1; $star <= 5; $star += 1) {
                        $half_val = $star - 0.5;
                        $full_val = (float) $star;
                        ?>
                        <span class="elementrify-product-filter__star-pair" data-star="<?php echo esc_attr((string) $star); ?>">
                            <span class="elementrify-product-filter__star-skin" aria-hidden="true">
                                <span class="elementrify-product-filter__star-bg">★</span>
                                <span class="elementrify-product-filter__star-fill">★</span>
                            </span>
                            <button
                                type="button"
                                class="elementrify-product-filter__star-half elementrify-product-filter__star-half--left"
                                data-value="<?php echo esc_attr((string) $half_val); ?>"
                                aria-label="<?php echo esc_attr(sprintf(/* translators: %s: rating like 3.5 */ __('%s stars out of 5', 'elementrify'), (string) $half_val)); ?>"
                            ></button>
                            <button
                                type="button"
                                class="elementrify-product-filter__star-half elementrify-product-filter__star-half--right"
                                data-value="<?php echo esc_attr((string) $full_val); ?>"
                                aria-label="<?php echo esc_attr(sprintf(/* translators: %s: rating like 4 */ __('%s stars out of 5', 'elementrify'), (string) $full_val)); ?>"
                            ></button>
                        </span>
                        <?php
                    }
                    ?>
                </div>
                <span class="elementrify-product-filter__rating-caption" id="<?php echo esc_attr($id_prefix); ?>-rating-caption"></span>
                <input
                    type="hidden"
                    name="elementrify_min_rating"
                    id="<?php echo esc_attr($rating_hidden_id); ?>"
                    class="elementrify-product-filter__rating-hidden"
                    value="<?php echo esc_attr($rating_hidden_value); ?>"
                    <?php disabled($rating_hidden_value === ''); ?>
                />
            </div>
        </div>
    <?php endif; ?>

    <?php if ($show_color) : ?>
        <div class="elementrify-product-filter__section">
            <h4 class="elementrify-product-filter__section-title"><?php echo esc_html($color_section_title); ?></h4>
            <?php if (!taxonomy_exists($color_taxonomy)) : ?>
                <p class="elementrify-product-filter__empty-note"><?php esc_html_e('This color taxonomy does not exist. In the widget, open Color taxonomy and choose the attribute your store uses (for example pa_color).', 'elementrify'); ?></p>
            <?php elseif (!is_wp_error($color_terms) && !empty($color_terms)) : ?>
                <div class="elementrify-product-filter__colors">
                    <?php foreach ($color_terms as $color_term) : ?>
                        <?php
                        $color_slug = $color_term->slug;
                        $color_hex = sanitize_hex_color((string) get_term_meta($color_term->term_id, 'color', true));
                        if (!$color_hex) {
                            $fallback = str_replace(['pa_', 'color-', 'colour-'], '', $color_slug);
                            $color_hex = sanitize_hex_color((string) $fallback) ?: '#d1d5db';
                        }
                        ?>
                        <label class="elementrify-product-filter__color-option" title="<?php echo esc_attr($color_term->name); ?>">
                            <input type="checkbox" value="<?php echo esc_attr($color_slug); ?>" data-filter-target="<?php echo esc_attr($color_param_name); ?>" <?php checked(in_array($color_slug, $selected_color_slugs, true)); ?> />
                            <span data-color="<?php echo esc_attr($color_hex); ?>"></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p class="elementrify-product-filter__empty-note"><?php esc_html_e('No color terms returned. In the widget, turn off "Hide colors with no products", or assign products to your color terms.', 'elementrify'); ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php
    foreach ($_GET as $query_key => $query_value) {
        if (in_array($query_key, [$brand_param_name, $color_param_name, 'rating_filter', 'elementrify_min_rating', 'min_price', 'max_price', 'product_cat', 'product_tag', 'paged', 's', 'post_type', 'elementrify_pf'], true)) {
            continue;
        }
        if (is_array($query_value)) {
            foreach ($query_value as $item_value) {
                echo '<input type="hidden" name="' . esc_attr($query_key) . '[]" value="' . esc_attr((string) wp_unslash($item_value)) . '" />';
            }
            continue;
        }
        echo '<input type="hidden" name="' . esc_attr($query_key) . '" value="' . esc_attr((string) wp_unslash($query_value)) . '" />';
    }
    ?>
    <button class="elementrify-product-filter__apply" type="submit"><?php echo esc_html($apply_text); ?></button>
</form>

<?php
    }
}
