<?php
namespace Addonext\Widgets\ProductFilter;

use Addonext\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class ProductFilter extends WidgetBase
{
    private static bool $min_rating_hooked = false;

    public function get_icon(): string { return 'eicon-filter addonext-icon'; }
    public function get_name(): string { return 'addonext-product-filter'; }
    public function get_admin_category(): string { return 'woocommerce'; }
    public function get_title(): string { return __('Product Filter', 'addonext'); }
    public function get_description(): string { return __('WooCommerce filter panel with price, taxonomies, and rating.', 'addonext'); }
    public function get_brand_color(): string { return '#7f54b3'; }
    
    public function is_preview_only(): bool
    {
        return true;
    }

    public function get_style_depends(): array { return ['addonext-product-filter']; }
    public function get_script_depends(): array { return ['addonext-product-filter']; }

    public function enqueue_assets(): void
    {
        if (addonext_fs()->can_use_premium_code__premium_only()) {
            if (addonext_fs()->is_plan('pro')) {

        wp_register_style(
            'addonext-product-filter',
            ADDONEXT_URL . 'assets/css/product-filter.css',
            [],
            ADDONEXT_VERSION
        );

        wp_register_script(
            'addonext-product-filter',
            ADDONEXT_URL . 'assets/js/product-filter.js',
            ['jquery'],
            ADDONEXT_VERSION,
            true
        );
                }
        }
}

    public function register(): void
    {
        if (self::$min_rating_hooked || !class_exists('WooCommerce')) {
            return;
        }
        self::$min_rating_hooked = true;
        add_action('pre_get_posts', [__CLASS__, 'apply_min_rating_meta_query'], 100);
        add_filter('woocommerce_redirect_single_search_result', [__CLASS__, 'disable_single_result_redirect_for_filter_form'], 10, 1);
    }

    /**
     * Prevents redirecting to a single product when search is submitted from the filter form (stay on archive).
     *
     * @param bool $should_redirect Default WooCommerce behavior.
     * @return bool
     */
    public static function disable_single_result_redirect_for_filter_form(bool $should_redirect): bool
    {
        if (!$should_redirect || empty($_GET['addonext_pf'])) {
            return $should_redirect;
        }
        $marker = sanitize_text_field(wp_unslash((string) $_GET['addonext_pf']));

        return $marker === '1' ? false : $should_redirect;
    }

    /**
     * Restrict main product archives by minimum average rating (supports half-star steps via GET).
     *
     * @param \WP_Query $query Main query.
     */
    public static function apply_min_rating_meta_query(\WP_Query $query): void
    {
        if (is_admin() || !$query->is_main_query()) {
            return;
        }
        if (!function_exists('is_shop') || (!is_shop() && !is_product_taxonomy())) {
            return;
        }
        if (!isset($_GET['addonext_min_rating']) || $_GET['addonext_min_rating'] === '') {
            return;
        }
        $min = (float) wp_unslash($_GET['addonext_min_rating']);
        if ($min <= 0 || $min > 5) {
            return;
        }
        $meta_query = $query->get('meta_query');
        if (!is_array($meta_query)) {
            $meta_query = [];
        }
        $meta_query[] = [
            'key'     => '_wc_average_rating',
            'value'   => (string) $min,
            'compare' => '>=',
            'type'    => 'DECIMAL',
        ];
        $query->set('meta_query', $meta_query);
    }

    /**
     * @return array<string, string>
     */
    private static function get_product_taxonomy_select_options(): array
    {
        $skip = ['product_type', 'product_visibility'];
        $taxonomies = get_object_taxonomies('product', 'objects');
        $options = [];
        foreach ($taxonomies as $name => $obj) {
            if (in_array($name, $skip, true)) {
                continue;
            }
            $options[$name] = sprintf('%s (%s)', $obj->label, $name);
        }
        ksort($options);
        $known_brand_like = ['product_brand', 'pwb-brand', 'berocket_brand', 'yith_product_brand'];
        foreach ($known_brand_like as $slug) {
            if (isset($options[$slug]) || !taxonomy_exists($slug)) {
                continue;
            }
            $tax = get_taxonomy($slug);
            $options[$slug] = $tax
                ? sprintf('%s (%s)', $tax->label, $slug)
                : $slug;
        }
        if (!isset($options['product_brand'])) {
            $options['product_brand'] = __('Brands (product_brand)', 'addonext');
        }
        if (!isset($options['pa_color'])) {
            $options['pa_color'] = __('Color (pa_color)', 'addonext');
        }
        ksort($options);
        if ($options === []) {
            $options['pa_brand'] = __('Brand (pa_brand)', 'addonext');
        }

        return $options;
    }

    /**
     * @param array<string, string> $options
     */
    private static function default_brand_taxonomy_slug(array $options): string
    {
        if (isset($options['pa_brand'])) {
            return 'pa_brand';
        }
        if (isset($options['product_brand'])) {
            return 'product_brand';
        }
        foreach (array_keys($options) as $slug) {
            if (stripos($slug, 'brand') !== false) {
                return $slug;
            }
        }
        foreach (array_keys($options) as $slug) {
            if (stripos($slug, 'manufacturer') !== false || stripos($slug, 'make') !== false) {
                return $slug;
            }
        }
        $keys = array_keys($options);

        return $keys[0] ?? 'pa_brand';
    }

    /**
     * @param array<string, string> $options
     */
    private static function default_color_taxonomy_slug(array $options): string
    {
        if (isset($options['pa_color'])) {
            return 'pa_color';
        }
        foreach (array_keys($options) as $slug) {
            if (stripos($slug, 'color') !== false || stripos($slug, 'colour') !== false) {
                return $slug;
            }
        }
        $keys = array_keys($options);

        return $keys[0] ?? 'pa_color';
    }

    public function register_controls(): void
    {
        if (addonext_fs()->can_use_premium_code__premium_only()) {
            if (addonext_fs()->is_plan('pro')) {

        $taxonomy_options = self::get_product_taxonomy_select_options();

        $this->start_controls_section('section_general', [
            'label' => __('General', 'addonext'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('panel_title', [
            'label' => __('Panel title', 'addonext'),
            'type' => Controls_Manager::TEXT,
            'default' => __('Filter Products', 'addonext'),
            'render_type' => 'template',
        ]);

        $this->add_control('clear_all_text', [
            'label' => __('Clear link text', 'addonext'),
            'type' => Controls_Manager::TEXT,
            'default' => __('Clear All', 'addonext'),
            'render_type' => 'template',
        ]);

        $this->add_control('apply_text', [
            'label' => __('Apply button text', 'addonext'),
            'type' => Controls_Manager::TEXT,
            'default' => __('Apply Filters', 'addonext'),
            'render_type' => 'template',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_product_search', [
            'label' => __('Product search', 'addonext'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('show_product_search', [
            'label' => __('Enable', 'addonext'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'yes',
            'render_type' => 'template',
        ]);

        $this->add_control('search_section_title', [
            'label' => __('Section title', 'addonext'),
            'type' => Controls_Manager::TEXT,
            'default' => '',
            'placeholder' => __('Optional — leave empty to hide the heading', 'addonext'),
            'condition' => ['show_product_search' => 'yes'],
            'render_type' => 'template',
        ]);

        $this->add_control('show_product_search_label', [
            'label' => __('Show label', 'addonext'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => '',
            'condition' => ['show_product_search' => 'yes'],
        ]);

        $this->add_control('product_search_label', [
            'label' => __('Label text', 'addonext'),
            'type' => Controls_Manager::TEXT,
            'default' => __('Search products', 'addonext'),
            'condition' => [
                'show_product_search' => 'yes',
                'show_product_search_label' => 'yes',
            ],
            'render_type' => 'template',
        ]);

        $this->add_control('product_search_placeholder', [
            'label' => __('Placeholder', 'addonext'),
            'type' => Controls_Manager::TEXT,
            'default' => __('Search by name…', 'addonext'),
            'condition' => ['show_product_search' => 'yes'],
            'render_type' => 'template',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_price_filter', [
            'label' => __('Price filter', 'addonext'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('price_section_title', [
            'label' => __('Section title', 'addonext'),
            'type' => Controls_Manager::TEXT,
            'default' => __('Price Range', 'addonext'),
            'render_type' => 'template',
        ]);

        $this->add_control('max_price', [
            'label' => __('Price upper limit', 'addonext'),
            'type' => Controls_Manager::NUMBER,
            'default' => 500,
            'min' => 1,
        ]);

        $this->add_control('min_price', [
            'label' => __('Price lower limit', 'addonext'),
            'type' => Controls_Manager::NUMBER,
            'default' => 0,
            'min' => 0,
        ]);

        $this->add_control('default_min_price', [
            'label' => __('Default minimum price', 'addonext'),
            'type' => Controls_Manager::NUMBER,
            'default' => 0,
            'min' => 0,
        ]);

        $this->add_control('default_max_price', [
            'label' => __('Default maximum price', 'addonext'),
            'type' => Controls_Manager::NUMBER,
            'default' => 500,
            'min' => 1,
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_category_filter', [
            'label' => __('Category filter', 'addonext'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('show_category', [
            'label' => __('Enable', 'addonext'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'yes',
            'render_type' => 'template',
        ]);

        $this->add_control('category_section_title', [
            'label' => __('Section title', 'addonext'),
            'type' => Controls_Manager::TEXT,
            'default' => __('Categories', 'addonext'),
            'condition' => ['show_category' => 'yes'],
            'render_type' => 'template',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_tag_filter', [
            'label' => __('Tag filter', 'addonext'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('show_tag', [
            'label' => __('Enable', 'addonext'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'yes',
            'render_type' => 'template',
        ]);

        $this->add_control('tag_section_title', [
            'label' => __('Section title', 'addonext'),
            'type' => Controls_Manager::TEXT,
            'default' => __('Tags', 'addonext'),
            'condition' => ['show_tag' => 'yes'],
            'render_type' => 'template',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_brand_filter', [
            'label' => __('Brand filter', 'addonext'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('show_brand', [
            'label' => __('Enable', 'addonext'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'yes',
            'render_type' => 'template',
        ]);

        $this->add_control('brand_section_title', [
            'label' => __('Section title', 'addonext'),
            'type' => Controls_Manager::TEXT,
            'default' => __('Brand', 'addonext'),
            'condition' => ['show_brand' => 'yes'],
            'render_type' => 'template',
        ]);

        $this->add_control('brand_taxonomy', [
            'label' => __('Brand taxonomy', 'addonext'),
            'type' => Controls_Manager::SELECT,
            'options' => $taxonomy_options,
            'default' => self::default_brand_taxonomy_slug($taxonomy_options),
            'description' => __('Pick the taxonomy used for brands (e.g. product_brand, pa_brand, pwb-brand).', 'addonext'),
            'condition' => ['show_brand' => 'yes'],
        ]);

        $this->add_control('brand_hide_empty_terms', [
            'label' => __('Hide brands with no products', 'addonext'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'no',
            'description' => __('Turn off to list all brand terms.', 'addonext'),
            'condition' => ['show_brand' => 'yes'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_rating_filter', [
            'label' => __('Rating filter', 'addonext'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('show_rating', [
            'label' => __('Enable', 'addonext'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'yes',
            'render_type' => 'template',
        ]);

        $this->add_control('rating_section_title', [
            'label' => __('Section title', 'addonext'),
            'type' => Controls_Manager::TEXT,
            'default' => __('Rating', 'addonext'),
            'condition' => ['show_rating' => 'yes'],
            'render_type' => 'template',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_color_filter', [
            'label' => __('Color filter', 'addonext'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('show_color', [
            'label' => __('Enable', 'addonext'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => '',
            'render_type' => 'template',
        ]);

        $this->add_control('color_section_title', [
            'label' => __('Section title', 'addonext'),
            'type' => Controls_Manager::TEXT,
            'default' => __('Color', 'addonext'),
            'condition' => ['show_color' => 'yes'],
            'render_type' => 'template',
        ]);

        $this->add_control('color_taxonomy', [
            'label' => __('Color taxonomy', 'addonext'),
            'type' => Controls_Manager::SELECT,
            'options' => $taxonomy_options,
            'default' => self::default_color_taxonomy_slug($taxonomy_options),
            'description' => __('Pick the attribute or taxonomy used for color swatches (usually pa_color).', 'addonext'),
            'condition' => ['show_color' => 'yes'],
        ]);

        $this->add_control('color_hide_empty_terms', [
            'label' => __('Hide colors with no products', 'addonext'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'no',
            'condition' => ['show_color' => 'yes'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style', [
            'label' => __('Card', 'addonext'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('card_bg', [
            'label' => __('Background', 'addonext'),
            'type' => Controls_Manager::COLOR,
            'default' => '#f8fafc',
            'selectors' => [
                '{{WRAPPER}} .addonext-product-filter' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('card_border_color', [
            'label' => __('Border color', 'addonext'),
            'type' => Controls_Manager::COLOR,
            'default' => '#e5e7eb',
            'selectors' => [
                '{{WRAPPER}} .addonext-product-filter' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('card_padding', [
            'label' => __('Box padding', 'addonext'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default' => [
                'top' => 18,
                'right' => 18,
                'bottom' => 18,
                'left' => 18,
                'unit' => 'px',
            ],
            'selectors' => [
                '{{WRAPPER}} .addonext-product-filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'selector' => '{{WRAPPER}} .addonext-product-filter__title, {{WRAPPER}} .addonext-product-filter__section-title',
            ]
        );

        $this->end_controls_section();
                }
        }
}

    public function render(): void
    {
        if (addonext_fs()->can_use_premium_code__premium_only()) {
            if (addonext_fs()->is_plan('pro')) {

        $path = ADDONEXT_DIR . 'pro/templates/product-filter/product-filter.php';
        if (is_readable($path)) include $path;
                }
        }
}
}
