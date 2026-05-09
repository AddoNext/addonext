<?php
namespace Elementrify\Widgets\ProductTabs;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

defined('ABSPATH') or exit;

/**
 * Shop-style product tabs: repeater-driven queries (standalone from Product Grid).
 */
class ProductTabs extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_assets']);
    }

    public function enqueue_preview_assets(): void
    {
        wp_enqueue_style('elementrify-product-grid');
        wp_enqueue_style('elementrify-product-tabs');
        wp_enqueue_script('elementrify-product-tabs');
    }

    public function get_icon(): string
    {
        return 'eicon-tabs elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-product-tabs';
    }

    public function get_admin_category(): string
    {
        return 'woocommerce';
    }

    public function get_title(): string
    {
        return __('Product Tabs', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Product grids in tabs with filters and footer links.', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#7f54b3';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-product-grid', 'elementrify-product-tabs'];
    }

    public function get_script_depends(): array
    {
        return ['elementor-frontend', 'jquery', 'wc-add-to-cart', 'elementrify-product-tabs'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-product-tabs',
            ELEMENTRIFY_URL . 'assets/css/product-tabs.css',
            ['elementrify-product-grid'],
            ELEMENTRIFY_VERSION
        );

        wp_register_script(
            'elementrify-product-tabs',
            ELEMENTRIFY_URL . 'assets/js/product-tabs.js',
            ['elementor-frontend', 'jquery', 'wc-add-to-cart'],
            ELEMENTRIFY_VERSION,
            true
        );
    }

    /**
     * @return array<string, string>
     */
    private function get_product_category_options(): array
    {
        if (!taxonomy_exists('product_cat')) {
            return [];
        }

        $terms = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
        ]);

        if (is_wp_error($terms) || !is_array($terms)) {
            return [];
        }

        $options = [];
        foreach ($terms as $term) {
            $options[(string) $term->term_id] = $term->name;
        }

        return $options;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private static function default_tabs(): array
    {
        return [
            [
                'tab_title' => __('Products', 'elementrify'),
                'limit' => 4,
                'footer_button_text' => __('View All Products', 'elementrify'),
            ],
            [
                'tab_title' => __('Best Sellers', 'elementrify'),
                'limit' => 4,
                'orderby' => 'popularity',
                'order' => 'DESC',
                'footer_button_text' => __('View All Best Sellers', 'elementrify'),
            ],
            [
                'tab_title' => __('New Arrivals', 'elementrify'),
                'limit' => 4,
                'orderby' => 'date',
                'order' => 'DESC',
                'footer_button_text' => __('View All New Arrivals', 'elementrify'),
            ],
            [
                'tab_title' => __('On Sale', 'elementrify'),
                'limit' => 4,
                'sale_only' => 'yes',
                'footer_button_text' => __('View All Sale Products', 'elementrify'),
            ],
        ];
    }

    public function register_controls(): void
    {
        $category_options = $this->get_product_category_options();

        $this->start_controls_section('section_tabs', [
            'label' => __('Tabs', 'elementrify'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $repeater = new Repeater();

        $repeater->add_control('tab_title', [
            'label' => __('Tab title', 'elementrify'),
            'type' => Controls_Manager::TEXT,
            'default' => __('Tab', 'elementrify'),
        ]);

        $repeater->add_control('limit', [
            'label' => __('Products count', 'elementrify'),
            'type' => Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 50,
            'default' => 4,
        ]);

        $repeater->add_control('categories', [
            'label' => __('Categories', 'elementrify'),
            'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'label_block' => true,
            'options' => $category_options,
        ]);

        $repeater->add_control('include_ids', [
            'label' => __('Include product IDs', 'elementrify'),
            'type' => Controls_Manager::TEXT,
            'placeholder' => '12, 43',
        ]);

        $repeater->add_control('exclude_ids', [
            'label' => __('Exclude product IDs', 'elementrify'),
            'type' => Controls_Manager::TEXT,
            'placeholder' => '8, 16',
        ]);

        $repeater->add_control('sale_only', [
            'label' => __('On sale only', 'elementrify'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => '',
        ]);

        $repeater->add_control('featured_only', [
            'label' => __('Featured only', 'elementrify'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => '',
        ]);

        $repeater->add_control('stock_status', [
            'label' => __('Stock status', 'elementrify'),
            'type' => Controls_Manager::SELECT,
            'default' => 'all',
            'options' => [
                'all' => __('All', 'elementrify'),
                'instock' => __('In stock', 'elementrify'),
                'outofstock' => __('Out of stock', 'elementrify'),
                'onbackorder' => __('On backorder', 'elementrify'),
            ],
        ]);

        $repeater->add_control('orderby', [
            'label' => __('Order by', 'elementrify'),
            'type' => Controls_Manager::SELECT,
            'default' => 'date',
            'options' => [
                'date' => __('Date', 'elementrify'),
                'title' => __('Title', 'elementrify'),
                'price' => __('Price', 'elementrify'),
                'popularity' => __('Popularity', 'elementrify'),
                'rating' => __('Rating', 'elementrify'),
                'rand' => __('Random', 'elementrify'),
                'menu_order' => __('Menu order', 'elementrify'),
            ],
        ]);

        $repeater->add_control('order', [
            'label' => __('Order', 'elementrify'),
            'type' => Controls_Manager::SELECT,
            'default' => 'DESC',
            'options' => [
                'DESC' => __('Descending', 'elementrify'),
                'ASC' => __('Ascending', 'elementrify'),
            ],
        ]);

        $repeater->add_control('footer_button_text', [
            'label' => __('Footer button text', 'elementrify'),
            'type' => Controls_Manager::TEXT,
            'default' => __('View All', 'elementrify'),
            'label_block' => true,
        ]);

        $repeater->add_control('footer_button_link', [
            'label' => __('Footer button link', 'elementrify'),
            'type' => Controls_Manager::URL,
            'placeholder' => home_url('/'),
            'default' => ['url' => '', 'is_external' => '', 'nofollow' => ''],
        ]);

        $this->add_control('tabs', [
            'label' => __('Tabs', 'elementrify'),
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => self::default_tabs(),
            'title_field' => '{{{ tab_title }}}',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_grid', [
            'label' => __('Grid & card', 'elementrify'),
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('columns', [
            'label' => __('Columns (desktop)', 'elementrify'),
            'type' => Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 6,
            'default' => 4,
            'frontend_available' => true,
            'editor_available' => true,
        ]);
        $this->add_control('columns_tablet', [
            'label' => __('Columns (tablet)', 'elementrify'),
            'type' => Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 4,
            'default' => 2,
            'frontend_available' => true,
            'editor_available' => true,
        ]);
        $this->add_control('columns_mobile', [
            'label' => __('Columns (mobile)', 'elementrify'),
            'type' => Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 2,
            'default' => 1,
            'frontend_available' => true,
            'editor_available' => true,
        ]);
        $this->add_control('column_gap', [
            'label' => __('Column gap', 'elementrify'),
            'type' => Controls_Manager::NUMBER,
            'min' => 0,
            'max' => 60,
            'default' => 16,
            'frontend_available' => true,
            'editor_available' => true,
        ]);
        $this->add_control('row_gap', [
            'label' => __('Row gap', 'elementrify'),
            'type' => Controls_Manager::NUMBER,
            'min' => 0,
            'max' => 60,
            'default' => 16,
            'frontend_available' => true,
            'editor_available' => true,
        ]);

        $this->add_control('show_rating', [
            'label' => __('Show rating', 'elementrify'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'yes',
        ]);
        $this->add_control('show_short_desc', [
            'label' => __('Show short description', 'elementrify'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'yes',
        ]);
        $this->add_control('show_old_price', [
            'label' => __('Show old price', 'elementrify'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'yes',
        ]);
        $this->add_control('show_badges', [
            'label' => __('Show badges', 'elementrify'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'yes',
        ]);
        $this->add_control('show_wishlist', [
            'label' => __('Show wishlist button', 'elementrify'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'yes',
        ]);
        $this->add_control('show_add_to_cart', [
            'label' => __('Show add to cart', 'elementrify'),
            'type' => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'yes',
        ]);
        $this->add_control('image_hover_effect', [
            'label' => __('Image hover effect', 'elementrify'),
            'type' => Controls_Manager::SELECT,
            'default' => 'zoom-out',
            'options' => [
                'none' => __('None', 'elementrify'),
                'zoom-in' => __('Zoom in', 'elementrify'),
                'zoom-out' => __('Zoom out', 'elementrify'),
                'jump' => __('Jump', 'elementrify'),
                'tilt' => __('Tilt', 'elementrify'),
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_tabs_style', [
            'label' => __('Tabs', 'elementrify'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('tabs_alignment', [
            'label' => __('Alignment', 'elementrify'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => ['title' => __('Left', 'elementrify'), 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => __('Center', 'elementrify'), 'icon' => 'eicon-text-align-center'],
                'flex-end' => ['title' => __('Right', 'elementrify'), 'icon' => 'eicon-text-align-right'],
            ],
            'default' => 'flex-start',
            'toggle' => true,
            'selectors' => [
                '{{WRAPPER}} .elementrify-product-tabs__nav' => 'justify-content: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'tab_typography',
            'selector' => '{{WRAPPER}} .elementrify-product-tabs__tab',
            'fields_options' => [
                'font_weight' => [
                    'default' => '500',
                ],
            ],
        ]);

        $this->add_control('tab_color', [
            'label' => __('Text color', 'elementrify'),
            'type' => Controls_Manager::COLOR,
            'default' => '#6b7280',
            'selectors' => [
                '{{WRAPPER}} .elementrify-product-tabs__tab' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('tab_color_active', [
            'label' => __('Active text color', 'elementrify'),
            'type' => Controls_Manager::COLOR,
            'default' => '#2563eb',
            'selectors' => [
                '{{WRAPPER}} .elementrify-product-tabs__tab.is-active' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('tab_indicator_color', [
            'label' => __('Active underline color', 'elementrify'),
            'type' => Controls_Manager::COLOR,
            'default' => '#2563eb',
            'selectors' => [
                '{{WRAPPER}} .elementrify-product-tabs__tab.is-active::after' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('tabs_border_color', [
            'label' => __('Bar border color', 'elementrify'),
            'type' => Controls_Manager::COLOR,
            'default' => '#e5e7eb',
            'selectors' => [
                '{{WRAPPER}} .elementrify-product-tabs__nav' => 'border-bottom-color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_footer_btn_style', [
            'label' => __('Footer button', 'elementrify'),
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('footer_btn_align', [
            'label' => __('Alignment', 'elementrify'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'left' => ['title' => __('Left', 'elementrify'), 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => __('Center', 'elementrify'), 'icon' => 'eicon-text-align-center'],
                'right' => ['title' => __('Right', 'elementrify'), 'icon' => 'eicon-text-align-right'],
            ],
            'default' => 'center',
            'selectors' => [
                '{{WRAPPER}} .elementrify-product-tabs__footer' => 'text-align: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'footer_btn_typography',
            'selector' => '{{WRAPPER}} .elementrify-product-tabs__cta',
        ]);

        $this->add_control('footer_btn_text_color', [
            'label' => __('Text color', 'elementrify'),
            'type' => Controls_Manager::COLOR,
            'default' => '#111827',
            'selectors' => [
                '{{WRAPPER}} .elementrify-product-tabs__cta' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name' => 'footer_btn_background',
            'types' => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .elementrify-product-tabs__cta',
            'fields_options' => [
                'background' => ['default' => 'classic'],
                'color' => ['default' => '#f3f4f6'],
            ],
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name' => 'footer_btn_border',
            'selector' => '{{WRAPPER}} .elementrify-product-tabs__cta',
        ]);

        $this->add_control('footer_btn_radius', [
            'label' => __('Border radius', 'elementrify'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default' => [
                'top' => 6,
                'right' => 6,
                'bottom' => 6,
                'left' => 6,
                'unit' => 'px',
                'isLinked' => true,
            ],
            'selectors' => [
                '{{WRAPPER}} .elementrify-product-tabs__cta' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('footer_btn_padding', [
            'label' => __('Padding', 'elementrify'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default' => [
                'top' => 12,
                'right' => 26,
                'bottom' => 12,
                'left' => 26,
                'unit' => 'px',
                'isLinked' => false,
            ],
            'selectors' => [
                '{{WRAPPER}} .elementrify-product-tabs__cta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name' => 'footer_btn_shadow',
            'selector' => '{{WRAPPER}} .elementrify-product-tabs__cta',
        ]);

        $this->end_controls_section();
    }

    /**
     * @param array<string, mixed> $row Repeater row (same shape as grid query settings).
     * @return array<string, mixed>
     */
    public static function build_tab_row_query_args(array $row): array
    {
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

        $limit = isset($row['limit']) ? max(1, (int) $row['limit']) : 20;
        $categories = [];
        if (isset($row['categories']) && is_array($row['categories'])) {
            $categories = array_map('absint', $row['categories']);
            $categories = array_values(array_filter($categories, static function ($termId) {
                return $termId > 0;
            }));
        }
        $include_ids = $parse_ids(isset($row['include_ids']) ? (string) $row['include_ids'] : '');
        $exclude_ids = $parse_ids(isset($row['exclude_ids']) ? (string) $row['exclude_ids'] : '');
        $order_by = isset($row['orderby']) ? (string) $row['orderby'] : 'date';
        $order = isset($row['order']) ? strtoupper((string) $row['order']) : 'DESC';
        $sale_only = isset($row['sale_only']) && $row['sale_only'] === 'yes';
        $featured_only = isset($row['featured_only']) && $row['featured_only'] === 'yes';
        $stock_status = isset($row['stock_status']) ? (string) $row['stock_status'] : 'all';

        $query_args = [
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => $limit,
            'ignore_sticky_posts' => true,
            'no_found_rows' => true,
        ];

        $tax_query = [];
        if ($categories !== []) {
            $tax_query[] = [
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $categories,
                'operator' => 'IN',
            ];
        }
        if ($featured_only) {
            $tax_query[] = [
                'taxonomy' => 'product_visibility',
                'field' => 'name',
                'terms' => ['featured'],
            ];
        }
        if ($tax_query !== []) {
            if (count($tax_query) > 1) {
                $tax_query['relation'] = 'AND';
            }
            $query_args['tax_query'] = $tax_query;
        }

        $meta_query = [];
        if ($stock_status !== 'all') {
            $meta_query[] = [
                'key' => '_stock_status',
                'value' => $stock_status,
            ];
        }
        if ($meta_query !== []) {
            $query_args['meta_query'] = $meta_query;
        }

        if ($sale_only) {
            $sale_ids = function_exists('wc_get_product_ids_on_sale') ? wc_get_product_ids_on_sale() : [];
            $query_args['post__in'] = !empty($sale_ids) ? $sale_ids : [0];
        }
        if ($include_ids !== []) {
            $query_args['post__in'] = isset($query_args['post__in'])
                ? array_values(array_intersect($query_args['post__in'], $include_ids))
                : $include_ids;
        }
        if ($exclude_ids !== []) {
            $query_args['post__not_in'] = $exclude_ids;
        }

        switch ($order_by) {
            case 'price':
                $query_args['orderby'] = 'meta_value_num';
                $query_args['meta_key'] = '_price';
                break;
            case 'popularity':
                $query_args['orderby'] = 'meta_value_num';
                $query_args['meta_key'] = 'total_sales';
                break;
            case 'rating':
                $query_args['orderby'] = 'meta_value_num';
                $query_args['meta_key'] = '_wc_average_rating';
                break;
            case 'title':
            case 'rand':
            case 'menu_order':
            case 'date':
                $query_args['orderby'] = $order_by;
                break;
            default:
                $query_args['orderby'] = 'date';
                break;
        }
        $query_args['order'] = ($order === 'ASC') ? 'ASC' : 'DESC';

        return $query_args;
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/product-tabs/product-tabs.php';
        if (is_readable($path)) {
            include $path;
        }
    }
}
