<?php
namespace Addonext\Widgets\AddToCart;

use Addonext\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class AddToCart extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_assets']);
    }

    public function enqueue_preview_assets(): void
    {
        wp_enqueue_style('addonext-add-to-cart');
        wp_enqueue_script('addonext-add-to-cart');
    }

    public function get_icon(): string
    {
        return 'eicon-cart-medium addonext-icon';
    }

    public function get_name(): string
    {
        return 'addonext-add-to-cart';
    }

    public function get_admin_category(): string
    {
        return 'woocommerce';
    }

    public function get_title(): string
    {
        return __('Add to Cart', 'addonext');
    }

    public function get_description(): string
    {
        return __('Quantity picker with add to cart', 'addonext');
    }

    public function get_brand_color(): string
    {
        return '#7f54b3';
    }

    public function get_style_depends(): array
    {
        return ['addonext-add-to-cart'];
    }

    public function get_script_depends(): array
    {
        return ['elementor-frontend', 'jquery', 'addonext-add-to-cart'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'addonext-add-to-cart',
            ADDONEXT_URL . 'assets/css/add-to-cart.css',
            [],
            ADDONEXT_VERSION
        );

        wp_register_script(
            'addonext-add-to-cart',
            ADDONEXT_URL . 'assets/js/add-to-cart.js',
            ['elementor-frontend', 'jquery'],
            ADDONEXT_VERSION,
            true
        );
    }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', [
            'label' => __('Product', 'addonext'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('product_id', [
            'label'       => __('Product ID', 'addonext'),
            'type'        => Controls_Manager::NUMBER,
            'min'         => 0,
            'description' => __('Leave empty on single product pages or inside a product loop to use the current product.', 'addonext'),
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_quantity', [
            'label' => __('Quantity', 'addonext'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('show_quantity', [
            'label'        => __('Show quantity', 'addonext'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('quantity_label', [
            'label'     => __('Label', 'addonext'),
            'type'      => Controls_Manager::TEXT,
            'default'   => __('Quantity', 'addonext'),
            'condition' => ['show_quantity' => 'yes'],
        ]);

        $this->add_control('default_qty', [
            'label'     => __('Default quantity', 'addonext'),
            'type'      => Controls_Manager::NUMBER,
            'min'       => 1,
            'default'   => 1,
            'condition' => ['show_quantity' => 'yes'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_button', [
            'label' => __('Button', 'addonext'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('button_text', [
            'label'   => __('Text', 'addonext'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Add to Cart', 'addonext'),
        ]);

        $this->add_control('open_mini_cart', [
            'label'        => __('Open cart drawer after add', 'addonext'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('show_view_cart_link', [
            'label'        => __('Show view cart control', 'addonext'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_quantity', [
            'label' => __('Quantity', 'addonext'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('quantity_label_color', [
            'label'     => __('Label color', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#0f172a',
            'selectors' => [
                '{{WRAPPER}} .addonext-add-to-cart__quantity-label' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'quantity_label_typography',
                'selector' => '{{WRAPPER}} .addonext-add-to-cart__quantity-label',
            ]
        );

        $this->add_control('quantity_controls_bg', [
            'label'     => __('Control background', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .addonext-add-to-cart__quantity-row' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .addonext-add-to-cart__qty-btn' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .addonext-add-to-cart__qty-input' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('quantity_controls_border', [
            'label'     => __('Control border', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#e2e8f0',
            'selectors'  => [
                '{{WRAPPER}} .addonext-add-to-cart__quantity-row' => 'border-color: {{VALUE}};',
                '{{WRAPPER}} .addonext-add-to-cart__qty-btn--minus' => 'border-right-color: {{VALUE}};',
                '{{WRAPPER}} .addonext-add-to-cart__qty-btn--plus' => 'border-left-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('quantity_btn_symbol_color', [
            'label'     => __('Plus / minus color', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#94a3b8',
            'selectors' => [
                '{{WRAPPER}} .addonext-add-to-cart__qty-btn' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('quantity_radius', [
            'label'      => __('Corner radius', 'addonext'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 16]],
            'default'    => ['size' => 8, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .addonext-add-to-cart__quantity-row' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('quantity_input_width', [
            'label'      => __('Quantity control width', 'addonext'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range'      => [
                'px' => [
                    'min' => 120,
                    'max' => 720,
                ],
                '%' => [
                    'min' => 10,
                    'max' => 100,
                ],
            ],
            'default'    => [
                'size' => 100,
                'unit' => '%',
            ],
            'selectors'  => [
                '{{WRAPPER}} .addonext-add-to-cart__quantity-row' => 'width: {{SIZE}}{{UNIT}}; max-width: 100%; box-sizing: border-box;',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_button', [
            'label' => __('Add to cart button', 'addonext'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('button_bg', [
            'label'     => __('Background', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#2563eb',
            'selectors' => [
                '{{WRAPPER}} .addonext-add-to-cart__submit:not(.is-added-success)' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('button_bg_hover', [
            'label'     => __('Background hover', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1d4ed8',
            'selectors' => [
                '{{WRAPPER}} .addonext-add-to-cart__submit:hover:not(.is-added-success):not(.loading)' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('button_text_color', [
            'label'     => __('Text color', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .addonext-add-to-cart__submit' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} .addonext-add-to-cart__btn-label',
                'fields_options' => [
                    'font_size' => [
                        'default' => [
                            'unit' => 'px',
                            'size' => 14,
                        ],
                    ],
                ],
            ]
        );

        $this->add_control('button_radius', [
            'label'      => __('Border radius', 'addonext'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 20]],
            'default'    => ['size' => 10, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .addonext-add-to-cart__submit' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('success_bg', [
            'label'     => __('Success background', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#16a34a',
            'selectors' => [
                '{{WRAPPER}} .addonext-add-to-cart__submit.is-added-success' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ADDONEXT_DIR . 'templates/add-to-cart/add-to-cart.php';
        if (is_readable($path)) {
            include $path;
        }
    }
}
