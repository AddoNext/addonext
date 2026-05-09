<?php
namespace Elementrify\Widgets\ProductGrid;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class ProductGrid extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_assets']);
    }

    public function enqueue_preview_assets(): void
    {
        wp_enqueue_style('elementrify-product-grid');
        wp_enqueue_script('elementrify-product-grid');
    }

    public function get_icon(): string
    {
        return 'eicon-products elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-product-grid';
    }

    public function get_admin_category(): string
    {
        return 'woocommerce';
    }

    public function get_title(): string
    {
        return __('Product Grid', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Display products in grid', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#7f54b3';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-product-grid'];
    }

    public function get_script_depends(): array
    {
        return ['elementor-frontend', 'jquery', 'wc-add-to-cart', 'elementrify-product-grid'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-product-grid',
            ELEMENTRIFY_URL . 'assets/css/product-grid.css',
            [],
            ELEMENTRIFY_VERSION
        );

        wp_register_script(
            'elementrify-product-grid',
            ELEMENTRIFY_URL . 'assets/js/product-grid.js',
            ['elementor-frontend', 'jquery', 'wc-add-to-cart'],
            ELEMENTRIFY_VERSION,
            true
        );
    }

    private function get_product_category_options(): array
    {
        if (!class_exists('WooCommerce')) {
            return [];
        }

        $terms = get_terms([
            'taxonomy'   => 'product_cat',
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

    public function register_controls(): void
    {
        $this->start_controls_section('section_query', [
            'label' => __('Query', 'elementrify'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('limit', [
            'label'   => __('Products count', 'elementrify'),
            'type'    => Controls_Manager::NUMBER,
            'min'     => 1,
            'max'     => 50,
            'default' => 20,
        ]);

        $this->add_control('categories', [
            'label'       => __('Categories', 'elementrify'),
            'type'        => Controls_Manager::SELECT2,
            'multiple'    => true,
            'label_block' => true,
            'options'     => $this->get_product_category_options(),
        ]);

        $this->add_control('include_ids', [
            'label'       => __('Include product IDs', 'elementrify'),
            'type'        => Controls_Manager::TEXT,
            'placeholder' => '12, 43, 50',
            'description' => __('Comma-separated IDs. Keeps only these products.', 'elementrify'),
        ]);

        $this->add_control('exclude_ids', [
            'label'       => __('Exclude product IDs', 'elementrify'),
            'type'        => Controls_Manager::TEXT,
            'placeholder' => '8, 16',
            'description' => __('Comma-separated IDs to exclude.', 'elementrify'),
        ]);

        $this->add_control('sale_only', [
            'label'        => __('On sale only', 'elementrify'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => '',
        ]);

        $this->add_control('featured_only', [
            'label'        => __('Featured only', 'elementrify'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => '',
        ]);

        $this->add_control('stock_status', [
            'label'   => __('Stock status', 'elementrify'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'all',
            'options' => [
                'all'         => __('All', 'elementrify'),
                'instock'     => __('In stock', 'elementrify'),
                'outofstock'  => __('Out of stock', 'elementrify'),
                'onbackorder' => __('On backorder', 'elementrify'),
            ],
        ]);

        $this->add_control('orderby', [
            'label'   => __('Order by', 'elementrify'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'date',
            'options' => [
                'date'       => __('Date', 'elementrify'),
                'title'      => __('Title', 'elementrify'),
                'price'      => __('Price', 'elementrify'),
                'popularity' => __('Popularity', 'elementrify'),
                'rating'     => __('Rating', 'elementrify'),
                'rand'       => __('Random', 'elementrify'),
                'menu_order' => __('Menu order', 'elementrify'),
            ],
        ]);

        $this->add_control('order', [
            'label'   => __('Order', 'elementrify'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'DESC',
            'options' => [
                'DESC' => __('Descending', 'elementrify'),
                'ASC'  => __('Ascending', 'elementrify'),
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_layout', [
            'label' => __('Grid', 'elementrify'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('columns', [
            'label'   => __('Columns (Desktop)', 'elementrify'),
            'type'    => Controls_Manager::NUMBER,
            'min'     => 1,
            'max'     => 6,
            'default' => 4,
        ]);
        $this->add_control('columns_tablet', [
            'label'   => __('Columns (Tablet)', 'elementrify'),
            'type'    => Controls_Manager::NUMBER,
            'min'     => 1,
            'max'     => 4,
            'default' => 2,
        ]);
        $this->add_control('columns_mobile', [
            'label'   => __('Columns (Mobile)', 'elementrify'),
            'type'    => Controls_Manager::NUMBER,
            'min'     => 1,
            'max'     => 2,
            'default' => 1,
        ]);
        $this->add_control('column_gap', [
            'label'   => __('Column gap', 'elementrify'),
            'type'    => Controls_Manager::NUMBER,
            'min'     => 0,
            'max'     => 60,
            'default' => 16,
        ]);
        $this->add_control('row_gap', [
            'label'   => __('Row gap', 'elementrify'),
            'type'    => Controls_Manager::NUMBER,
            'min'     => 0,
            'max'     => 60,
            'default' => 16,
        ]);

        $this->add_control('show_rating', [
            'label'        => __('Show rating', 'elementrify'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);
        $this->add_control('show_short_desc', [
            'label'        => __('Show short description', 'elementrify'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);
        $this->add_control('show_old_price', [
            'label'        => __('Show old price', 'elementrify'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);
        $this->add_control('show_badges', [
            'label'        => __('Show badges', 'elementrify'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);
        $this->add_control('show_wishlist', [
            'label'        => __('Show wishlist button', 'elementrify'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);
        $this->add_control('show_add_to_cart', [
            'label'        => __('Show add to cart button', 'elementrify'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);
        $this->add_control('image_hover_effect', [
            'label'   => __('Image hover effect', 'elementrify'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'zoom-out',
            'options' => [
                'none'      => __('None', 'elementrify'),
                'zoom-in'   => __('Zoom In', 'elementrify'),
                'zoom-out'  => __('Zoom Out', 'elementrify'),
                'jump'      => __('Jump', 'elementrify'),
                'tilt'      => __('Tilt', 'elementrify'),
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_card', [
            'label' => __('Card', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('card_padding', [
            'label'      => __('Card padding', 'elementrify'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default'    => [
                'top'      => '12',
                'right'    => '12',
                'bottom'   => '12',
                'left'     => '12',
                'unit'     => 'px',
                'isLinked' => true,
            ],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-product-grid__card-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        $this->add_control('card_radius', [
            'label'      => __('Card radius', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 30]],
            'default'    => ['size' => 12, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-product-grid__card' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->add_control('card_bg', [
            'label'     => __('Card background', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .elementrify-product-grid__card' => 'background-color: {{VALUE}};',
            ],
        ]);
        $this->add_control('title_color', [
            'label'     => __('Title color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#111827',
            'selectors' => [
                '{{WRAPPER}} .elementrify-product-grid__title' => 'color: {{VALUE}};',
            ],
        ]);
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .elementrify-product-grid__title',
            ]
        );
        $this->add_control('price_color', [
            'label'     => __('Price color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#111827',
            'selectors' => [
                '{{WRAPPER}} .elementrify-product-grid__price-current' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/product-grid/product-grid.php';
        if (is_readable($path)) include $path;
    }
}
