<?php
namespace Veloria\Widgets\ProductCarousel;

use Veloria\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class ProductCarousel extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_assets']);
    }

    public function enqueue_preview_assets(): void
    {
        wp_enqueue_style('veloria-product-carousel');
        wp_enqueue_script('veloria-product-carousel');
    }

    public function get_icon(): string
    {
        return 'eicon-slides veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-product-carousel';
    }

    public function get_admin_category(): string
    {
        return 'woocommerce';
    }

    public function get_title(): string
    {
        return __('Product Carousel', 'veloria');
    }

    public function get_description(): string
    {
        return __('Product slider widget', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#7f54b3';
    }

    
    public function is_preview_only(): bool
    {
        return true;
    }

    public function get_style_depends(): array
    {
        return ['veloria-product-carousel'];
    }

    public function get_script_depends(): array
    {
        return ['swiper', 'elementor-frontend', 'jquery', 'wc-add-to-cart', 'veloria-product-carousel'];
    }

    public function enqueue_assets(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        wp_register_style(
            'veloria-product-carousel',
            VELORIA_URL . 'assets/css/product-carousel.css',
            [],
            VELORIA_VERSION
        );

        wp_register_script(
            'veloria-product-carousel',
            VELORIA_URL . 'assets/js/product-carousel.js',
            ['swiper', 'elementor-frontend', 'jquery', 'wc-add-to-cart'],
            VELORIA_VERSION,
            true
        );
                }
        }
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
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        $this->start_controls_section('section_query', [
            'label' => __('Query', 'veloria'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('limit', [
            'label'   => __('Products count', 'veloria'),
            'type'    => Controls_Manager::NUMBER,
            'min'     => 1,
            'max'     => 50,
            'default' => 20,
        ]);

        $this->add_control('categories', [
            'label'       => __('Categories', 'veloria'),
            'type'        => Controls_Manager::SELECT2,
            'multiple'    => true,
            'label_block' => true,
            'options'     => $this->get_product_category_options(),
        ]);

        $this->add_control('include_ids', [
            'label'       => __('Include product IDs', 'veloria'),
            'type'        => Controls_Manager::TEXT,
            'placeholder' => '12, 43, 50',
            'description' => __('Comma-separated IDs. Keeps only these products.', 'veloria'),
        ]);

        $this->add_control('exclude_ids', [
            'label'       => __('Exclude product IDs', 'veloria'),
            'type'        => Controls_Manager::TEXT,
            'placeholder' => '8, 16',
            'description' => __('Comma-separated IDs to exclude.', 'veloria'),
        ]);

        $this->add_control('sale_only', [
            'label'        => __('On sale only', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => '',
        ]);

        $this->add_control('featured_only', [
            'label'        => __('Featured only', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => '',
        ]);

        $this->add_control('stock_status', [
            'label'   => __('Stock status', 'veloria'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'all',
            'options' => [
                'all'         => __('All', 'veloria'),
                'instock'     => __('In stock', 'veloria'),
                'outofstock'  => __('Out of stock', 'veloria'),
                'onbackorder' => __('On backorder', 'veloria'),
            ],
        ]);

        $this->add_control('orderby', [
            'label'   => __('Order by', 'veloria'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'date',
            'options' => [
                'date'       => __('Date', 'veloria'),
                'title'      => __('Title', 'veloria'),
                'price'      => __('Price', 'veloria'),
                'popularity' => __('Popularity', 'veloria'),
                'rating'     => __('Rating', 'veloria'),
                'rand'       => __('Random', 'veloria'),
                'menu_order' => __('Menu order', 'veloria'),
            ],
        ]);

        $this->add_control('order', [
            'label'   => __('Order', 'veloria'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'DESC',
            'options' => [
                'DESC' => __('Descending', 'veloria'),
                'ASC'  => __('Ascending', 'veloria'),
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_layout', [
            'label' => __('Carousel', 'veloria'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('per_view', [
            'label'   => __('Slides (Desktop)', 'veloria'),
            'type'    => Controls_Manager::NUMBER,
            'min'     => 1,
            'max'     => 6,
            'default' => 4,
        ]);
        $this->add_control('per_view_tablet', [
            'label'   => __('Slides (Tablet)', 'veloria'),
            'type'    => Controls_Manager::NUMBER,
            'min'     => 1,
            'max'     => 4,
            'default' => 2,
        ]);
        $this->add_control('per_view_mobile', [
            'label'   => __('Slides (Mobile)', 'veloria'),
            'type'    => Controls_Manager::NUMBER,
            'min'     => 1,
            'max'     => 2,
            'default' => 1,
        ]);
        $this->add_control('space_between', [
            'label'   => __('Space between', 'veloria'),
            'type'    => Controls_Manager::NUMBER,
            'min'     => 0,
            'max'     => 60,
            'default' => 16,
        ]);
        $this->add_control('show_nav', [
            'label'        => __('Show arrows', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);
        $this->add_control('show_dots', [
            'label'        => __('Show dots', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => '',
        ]);
        $this->add_control('loop', [
            'label'        => __('Loop', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => '',
        ]);

        $this->add_control('show_rating', [
            'label'        => __('Show rating', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);
        $this->add_control('show_short_desc', [
            'label'        => __('Show short description', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);
        $this->add_control('show_old_price', [
            'label'        => __('Show old price', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);
        $this->add_control('show_badges', [
            'label'        => __('Show badges', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);
        $this->add_control('show_wishlist', [
            'label'        => __('Show wishlist button', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);
        $this->add_control('show_add_to_cart', [
            'label'        => __('Show add to cart button', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);
        $this->add_control('image_hover_effect', [
            'label'   => __('Image hover effect', 'veloria'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'zoom-out',
            'options' => [
                'none'      => __('None', 'veloria'),
                'zoom-in'   => __('Zoom In', 'veloria'),
                'zoom-out'  => __('Zoom Out', 'veloria'),
                'jump'      => __('Jump', 'veloria'),
                'tilt'      => __('Tilt', 'veloria'),
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_card', [
            'label' => __('Card', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('card_padding', [
            'label'      => __('Card padding', 'veloria'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default'    => [
                'top' => '12',
                'right' => '12',
                'bottom' => '12',
                'left' => '12',
                'unit' => 'px',
                'isLinked' => true,
            ],
            'selectors'  => [
                '{{WRAPPER}} .veloria-product-carousel__card-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);
        $this->add_control('card_radius', [
            'label'      => __('Card radius', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 30]],
            'default'    => ['size' => 12, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-product-carousel__card' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);
        $this->add_control('card_bg', [
            'label'     => __('Card background', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .veloria-product-carousel__card' => 'background-color: {{VALUE}};',
            ],
        ]);
        $this->add_control('title_color', [
            'label'     => __('Title color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#111827',
            'selectors' => [
                '{{WRAPPER}} .veloria-product-carousel__title' => 'color: {{VALUE}};',
            ],
        ]);
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .veloria-product-carousel__title',
            ]
        );
        $this->add_control('price_color', [
            'label'     => __('Price color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#111827',
            'selectors' => [
                '{{WRAPPER}} .veloria-product-carousel__price-current' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
                }
        }
}

    public function render(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        $path = VELORIA_DIR . 'pro/templates/product-carousel/product-carousel.php';
        if (is_readable($path)) include $path;
                }
        }
}

}
