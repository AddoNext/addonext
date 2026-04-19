<?php
namespace Elementrify\Widgets\WooCheckout;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

/**
 * WooCommerce checkout for Elementor with Elementrify-owned markup (WooCommerce checkout API).
 */
class WooCheckout extends WidgetBase
{
    private static bool $renderHooksActive = false;

    /** @var array<string, mixed> */
    private static array $renderSettings = [];

    private static bool $checkoutWidgetRenderedThisRequest = false;

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_assets']);
        add_filter('woocommerce_gateway_description', [__CLASS__, 'filter_gateway_description_for_elementrify'], 9999, 2);
        add_filter('woocommerce_update_order_review_fragments', [__CLASS__, 'filter_checkout_fragments'], 20, 1);
        add_action('wp_footer', [__CLASS__, 'footer_patch_checkout_script_params'], 5);
    }

    public function enqueue_preview_assets(): void
    {
        wp_enqueue_style('elementrify-woo-checkout');
    }

    public function get_icon(): string
    {
        return 'eicon-cart elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-woo-checkout';
    }

    public function get_admin_category(): string
    {
        return 'woocommerce';
    }

    public function get_title(): string
    {
        return __('WooCommerce Checkout', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Checkout layout built for Elementor with WooCommerce billing, payments, and order review.', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#7f54b3';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-woo-checkout'];
    }

    public function get_script_depends(): array
    {
        return ['elementor-frontend', 'jquery', 'wc-checkout', 'elementrify-woo-checkout'];
    }

    public function enqueue_assets(): void
    {
        $checkoutCssPath = ELEMENTRIFY_DIR . 'assets/css/woo-checkout.css';
        $checkoutCssVer = is_readable($checkoutCssPath)
            ? (string) filemtime($checkoutCssPath)
            : ELEMENTRIFY_VERSION;

        wp_register_style(
            'elementrify-woo-checkout',
            ELEMENTRIFY_URL . 'assets/css/woo-checkout.css',
            [],
            $checkoutCssVer
        );

        wp_register_script(
            'elementrify-woo-checkout',
            ELEMENTRIFY_URL . 'assets/js/woo-checkout.js',
            ['jquery', 'elementor-frontend', 'wc-checkout'],
            ELEMENTRIFY_VERSION,
            true
        );
    }

    /**
     * @param array<string, mixed> $settings
     */
    public static function begin_render(array $settings): void
    {
        if (self::$renderHooksActive) {
            return;
        }
        self::$renderHooksActive = true;
        self::$renderSettings = $settings;

        add_filter('woocommerce_is_checkout', [__CLASS__, 'filter_force_is_checkout'], 999);

        $showCoupon = isset($settings['show_coupon_sidebar']) && $settings['show_coupon_sidebar'] === 'yes';
        if ($showCoupon && function_exists('woocommerce_checkout_coupon_form')) {
            remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
        }

    }

    public static function end_render(): void
    {
        if (!self::$renderHooksActive) {
            return;
        }

        remove_filter('woocommerce_is_checkout', [__CLASS__, 'filter_force_is_checkout'], 999);

        if (function_exists('woocommerce_checkout_coupon_form')) {
            add_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
        }

        self::$renderHooksActive = false;
        self::$renderSettings = [];
    }

    /**
     * @param mixed $is
     * @return bool|mixed
     */
    public static function filter_force_is_checkout($is)
    {
        return self::$renderHooksActive ? true : $is;
    }

    /**
     * @param array<string, string> $fragments
     * @return array<string, string>
     */
    public static function filter_checkout_fragments($fragments)
    {
        if (!is_array($fragments) || !self::is_elementrify_checkout_post_data()) {
            return $fragments;
        }

        if (isset($fragments['.woocommerce-checkout-review-order-table'])) {
            $fragments['.elementrify-co-order-review'] = CheckoutOrderUi::render();
            unset($fragments['.woocommerce-checkout-review-order-table']);
        }

        if (isset($fragments['.woocommerce-checkout-payment'])) {
            $split = CheckoutRenderer::split_payment_markup($fragments['.woocommerce-checkout-payment']);
            unset($fragments['.woocommerce-checkout-payment']);

            $fragments['.elementrify-wc-checkout__payment-methods-slot'] =
                '<div id="payment" class="woocommerce-checkout-payment elementrify-wc-checkout__payment-methods-slot elementrify-wc-checkout__payment-outer elementrify-co-payment-root">' . CheckoutPaymentUi::render_gateway_list() . '</div>';
            $fragments['.elementrify-wc-checkout__place-order-slot'] =
                '<div class="elementrify-wc-checkout__place-order-slot">' . $split['place_order'] . '</div>';
        }

        return $fragments;
    }

    public static function footer_patch_checkout_script_params(): void
    {
        if (!self::$checkoutWidgetRenderedThisRequest) {
            return;
        }
        wp_add_inline_script(
            'wc-checkout',
            'if(typeof wc_checkout_params!=="undefined"){wc_checkout_params.is_checkout="1";}',
            'before'
        );
    }

    private static function is_elementrify_checkout_post_data(): bool
    {
        $raw = isset($_POST['post_data']) ? wp_unslash($_POST['post_data']) : '';
        if (!is_string($raw) || $raw === '') {
            return false;
        }
        parse_str($raw, $parsed);

        return !empty($parsed['elementrify_checkout']);
    }

    /**
     * @param string $description
     * @param string $gateway_id
     */
    public static function filter_gateway_description_for_elementrify($description, $gateway_id): string
    {
        unset($gateway_id);

        if (!is_string($description)) {
            $description = '';
        }

        if (!self::is_elementrify_gateway_description_context()) {
            return $description;
        }

        if (self::payment_descriptions_visible_in_elementrify_context()) {
            return $description;
        }

        return '';
    }

    private static function is_elementrify_gateway_description_context(): bool
    {
        if (self::$renderHooksActive) {
            return true;
        }

        if (!defined('DOING_AJAX') || !DOING_AJAX) {
            return false;
        }

        return self::is_elementrify_checkout_post_data();
    }

    private static function payment_descriptions_visible_in_elementrify_context(): bool
    {
        if (self::$renderHooksActive) {
            return isset(self::$renderSettings['show_payment_descriptions'])
                && self::$renderSettings['show_payment_descriptions'] === 'yes';
        }

        $raw = isset($_POST['post_data']) ? wp_unslash($_POST['post_data']) : '';
        if (!is_string($raw) || $raw === '') {
            return false;
        }

        parse_str($raw, $parsed);

        return !empty($parsed['elementrify_show_payment_descriptions'])
            && (string) $parsed['elementrify_show_payment_descriptions'] === '1';
    }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'elementrify'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('show_coupon_sidebar', [
            'label'        => __('Coupon in sidebar', 'elementrify'),
            'description'  => __('Places the coupon field at the top of the order summary column. When off, WooCommerce shows the default coupon link above the form.', 'elementrify'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('coupon_section_title', [
            'label'     => __('Coupon section title', 'elementrify'),
            'type'      => Controls_Manager::TEXT,
            'default'   => __('Coupon', 'elementrify'),
            'condition' => ['show_coupon_sidebar' => 'yes'],
        ]);

        $this->add_control('billing_section_title', [
            'label'   => __('Billing section title', 'elementrify'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Billing details', 'elementrify'),
        ]);

        $this->add_control('order_summary_title', [
            'label'   => __('Order summary title', 'elementrify'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Order summary', 'elementrify'),
        ]);

        $this->add_control('payment_section_title', [
            'label'   => __('Payment section title', 'elementrify'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Payment method', 'elementrify'),
        ]);

        $this->add_control('show_payment_descriptions', [
            'label'        => __('Payment method descriptions', 'elementrify'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => __('Show', 'elementrify'),
            'label_off'    => __('Hide', 'elementrify'),
            'return_value' => 'yes',
            'default'      => '',
            'description'  => __('Extra text some gateways output under the method name. Hidden by default.', 'elementrify'),
        ]);

        $this->end_controls_section();

        $this->register_style_controls();
    }

    private function register_style_controls(): void
    {
        $w = '{{WRAPPER}} .elementrify-wc-checkout';

        $this->start_controls_section('style_layout', [
            'label' => __('Layout', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('layout_gap', [
            'label'      => __('Column gap', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 80]],
            'default'    => ['size' => 24, 'unit' => 'px'],
            'selectors'  => [
                $w . ' .elementrify-wc-checkout__layout' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('layout_sidebar_min', [
            'label'      => __('Sidebar minimum width', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range'      => ['px' => ['min' => 240, 'max' => 480]],
            'default'    => ['size' => 320, 'unit' => 'px'],
            'selectors'  => [
                $w . ' .elementrify-wc-checkout__col--sidebar' => 'flex-basis: {{SIZE}}{{UNIT}}; min-width: min(100%, {{SIZE}}{{UNIT}});',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('style_sections', [
            'label' => __('Checkout sections', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'section_bg',
                'types'    => ['classic', 'gradient'],
                'selector' => $w . ' .elementrify-wc-checkout__card--billing, ' . $w . ' .elementrify-wc-checkout__card--additional, ' . $w . ' .elementrify-wc-checkout__card--summary',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'section_border',
                'selector' => $w . ' .elementrify-wc-checkout__card--billing, ' . $w . ' .elementrify-wc-checkout__card--additional, ' . $w . ' .elementrify-wc-checkout__card--summary',
            ]
        );

        $this->add_responsive_control('section_radius', [
            'label'      => __('Border radius', 'elementrify'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => [
                $w . ' .elementrify-wc-checkout__card--billing, ' . $w . ' .elementrify-wc-checkout__card--additional, ' . $w . ' .elementrify-wc-checkout__card--summary' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('section_padding', [
            'label'      => __('Padding', 'elementrify'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors'  => [
                $w . ' .elementrify-wc-checkout__card--billing, ' . $w . ' .elementrify-wc-checkout__card--additional, ' . $w . ' .elementrify-wc-checkout__card--summary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'section_shadow',
                'selector' => $w . ' .elementrify-wc-checkout__card--billing, ' . $w . ' .elementrify-wc-checkout__card--additional, ' . $w . ' .elementrify-wc-checkout__card--summary',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('style_headings', [
            'label' => __('Section titles', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'heading_typo',
                'selector' => $w . ' .elementrify-wc-checkout__section-title, ' . $w . ' .woocommerce-billing-fields > h3, ' . $w . ' .woocommerce-shipping-fields > h3, ' . $w . ' .elementrify-wc-checkout__coupon-title',
            ]
        );

        $this->add_control('heading_color', [
            'label'     => __('Color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                $w . ' .elementrify-wc-checkout__section-title, ' . $w . ' .woocommerce-billing-fields > h3, ' . $w . ' .woocommerce-shipping-fields > h3, ' . $w . ' .elementrify-wc-checkout__coupon-title' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('heading_spacing', [
            'label'      => __('Bottom spacing', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 48]],
            'selectors'  => [
                $w . ' .elementrify-wc-checkout__section-title, ' . $w . ' .woocommerce-billing-fields > h3, ' . $w . ' .woocommerce-shipping-fields > h3, ' . $w . ' .elementrify-wc-checkout__coupon-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('style_inputs', [
            'label' => __('Inputs & selects', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'input_typo',
                'selector' => $w . ' input.elementrify-co-input, ' . $w . ' input.input-text, ' . $w . ' select, ' . $w . ' textarea',
            ]
        );

        $this->add_control('input_color', [
            'label'     => __('Text color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                $w . ' input.elementrify-co-input, ' . $w . ' input.input-text, ' . $w . ' select, ' . $w . ' textarea' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('input_bg', [
            'label'     => __('Background', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                $w . ' input.elementrify-co-input, ' . $w . ' input.input-text, ' . $w . ' select, ' . $w . ' textarea' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'input_border',
                'selector' => $w . ' input.elementrify-co-input, ' . $w . ' input.input-text, ' . $w . ' select, ' . $w . ' textarea',
            ]
        );

        $this->add_responsive_control('input_radius', [
            'label'      => __('Border radius', 'elementrify'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => [
                $w . ' input.elementrify-co-input, ' . $w . ' input.input-text, ' . $w . ' select, ' . $w . ' textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('input_padding_dim', [
            'label'      => __('Padding', 'elementrify'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors'  => [
                $w . ' input.elementrify-co-input, ' . $w . ' input.input-text, ' . $w . ' select, ' . $w . ' textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_control('label_heading', [
            'label'     => __('Labels', 'elementrify'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'label_typo',
                'selector' => $w . ' label, ' . $w . ' .woocommerce form .form-row label',
            ]
        );

        $this->add_control('label_color', [
            'label'     => __('Label color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                $w . ' label, ' . $w . ' .woocommerce form .form-row label' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('style_primary_btn', [
            'label' => __('Place order button', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'place_order_typo',
                'selector' => $w . ' #place_order',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'place_order_bg',
                'types'    => ['classic', 'gradient'],
                'selector' => $w . ' #place_order',
            ]
        );

        $this->add_control('place_order_color', [
            'label'     => __('Text color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                $w . ' #place_order' => 'color: {{VALUE}};',
            ]
        ]);

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'place_order_border',
                'selector' => $w . ' #place_order',
            ]
        );

        $this->add_responsive_control('place_order_radius', [
            'label'      => __('Border radius', 'elementrify'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => [
                $w . ' #place_order' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ]
        ]);

        $this->add_responsive_control('place_order_padding', [
            'label'      => __('Padding', 'elementrify'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors'  => [
                $w . ' #place_order' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('style_secondary_btn', [
            'label' => __('Secondary buttons', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'secondary_btn_typo',
                'selector' => $w . ' .woocommerce-form-coupon button.button, ' . $w . ' .checkout_coupon button.button, ' . $w . ' .elementrify-co-tpl-coupon__apply, ' . $w . ' button[name="woocommerce_checkout_update_totals"]',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'secondary_btn_bg',
                'types'    => ['classic', 'gradient'],
                'selector' => $w . ' .woocommerce-form-coupon button.button, ' . $w . ' .checkout_coupon .button, ' . $w . ' .elementrify-co-tpl-coupon__apply',
            ]
        );

        $this->add_control('secondary_btn_color', [
            'label'     => __('Text color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                $w . ' .woocommerce-form-coupon button.button, ' . $w . ' .checkout_coupon .button, ' . $w . ' .elementrify-co-tpl-coupon__apply' => 'color: {{VALUE}};',
            ]
        ]);

        $this->add_responsive_control('secondary_btn_radius', [
            'label'      => __('Border radius', 'elementrify'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => [
                $w . ' .woocommerce-form-coupon button.button, ' . $w . ' .checkout_coupon .button, ' . $w . ' .elementrify-co-tpl-coupon__apply' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ]
        ]);

        $this->end_controls_section();

        $this->start_controls_section('style_payment', [
            'label' => __('Payment methods', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'payment_item_bg',
                'types'    => ['classic', 'gradient'],
                'selector' => $w . ' .wc_payment_methods > li',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'payment_item_border',
                'selector' => $w . ' .wc_payment_methods > li',
            ]
        );

        $this->add_responsive_control('payment_item_radius', [
            'label'      => __('Item radius', 'elementrify'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => [
                $w . ' .wc_payment_methods > li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ]
        ]);

        $this->add_responsive_control('payment_item_padding', [
            'label'      => __('Item padding', 'elementrify'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors'  => [
                $w . ' .wc_payment_methods > li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ]
        ]);

        $this->add_responsive_control('payment_item_gap', [
            'label'      => __('Space between methods', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 24]],
            'selectors'  => [
                $w . ' .wc_payment_methods' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('style_order_table', [
            'label' => __('Order summary table', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'order_table_typo',
                'selector' => $w . ' .elementrify-co-order-review',
            ]
        );

        $this->add_control('order_table_color', [
            'label'     => __('Text color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                $w . ' .elementrify-co-order-review' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('order_table_border_color', [
            'label'     => __('Divider color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                $w . ' .elementrify-co-line' => 'border-bottom-color: {{VALUE}};',
                $w . ' .elementrify-co-totals__table tr' => 'border-top-color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('style_coupon', [
            'label'     => __('Coupon box', 'elementrify'),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => ['show_coupon_sidebar' => 'yes'],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'coupon_toggle_typo',
                'label'    => __('Toggle text', 'elementrify'),
                'selector' => $w . ' .elementrify-co-coupon-prompt, ' . $w . ' .elementrify-co-coupon-prompt .elementrify-co-coupon-link',
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $settings = $this->get_settings_for_display();

        if (!class_exists('WooCommerce')) {
            echo '<p class="elementrify-wc-checkout__notice">' . esc_html__('WooCommerce is required.', 'elementrify') . '</p>';

            return;
        }

        self::begin_render($settings);

        try {
            if (function_exists('WC')) {
                WC()->frontend_includes();
            }
            if (class_exists('\WC_Frontend_Scripts')) {
                \WC_Frontend_Scripts::load_scripts();
            }

            wp_enqueue_script('woocommerce');
            wp_enqueue_script('wc-checkout');
            wp_enqueue_script('wc-country-select');
            wp_enqueue_script('wc-address-i18n');
            wp_enqueue_script('selectWoo');
            if (wp_style_is('select2', 'registered')) {
                wp_enqueue_style('select2');
            }

            if (!function_exists('WC') || !WC()->cart) {
                echo '<p class="elementrify-wc-checkout__notice">' . esc_html__('Cart is not available.', 'elementrify') . '</p>';

                return;
            }

            wc_maybe_define_constant('WOOCOMMERCE_CHECKOUT', true);

            do_action('woocommerce_before_checkout_form_cart_notices');

            WC()->cart->calculate_totals();

            $checkout = WC()->checkout();

            if (WC()->cart->is_empty() && !is_customize_preview() && apply_filters('woocommerce_checkout_redirect_empty_cart', true)) {
                echo '<div class="elementrify-wc-checkout">';
                wc_print_notice(__('Your cart is currently empty.', 'elementrify'), 'notice');
                echo '<p><a class="button wc-forward" href="' . esc_url(wc_get_page_permalink('shop')) . '">' . esc_html__('Return to shop', 'elementrify') . '</a></p>';
                echo '</div>';

                return;
            }

            if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
                echo '<div class="elementrify-wc-checkout">';
                echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'elementrify')));
                echo '</div>';

                return;
            }

            if (empty($_POST) && wc_notice_count('error') > 0) {
                echo '<div class="elementrify-wc-checkout">';
                wc_get_template('checkout/cart-errors.php', ['checkout' => $checkout]);
                wc_clear_notices();
                echo '</div>';

                return;
            }

            if (wc_notice_count('error') === 0 && !empty($_POST['woocommerce_checkout_update_totals'])) {
                wc_add_notice(__('The order totals have been updated. Please confirm your order by pressing the "Place order" button at the bottom of the page.', 'elementrify'));
            }

            $showCoupon = isset($settings['show_coupon_sidebar']) && $settings['show_coupon_sidebar'] === 'yes';
            $classes = ['elementrify-wc-checkout'];
            if (!$showCoupon) {
                $classes[] = 'elementrify-wc-checkout--no-coupon';
            }

            self::$checkoutWidgetRenderedThisRequest = true;

            wp_enqueue_style('elementrify-woo-checkout');

            do_action('woocommerce_before_checkout_form', $checkout);

            echo '<div class="' . esc_attr(implode(' ', $classes)) . '">';
            CheckoutRenderer::render_form($checkout, $settings);
            echo '</div>';

            do_action('woocommerce_after_checkout_form', $checkout);
        } finally {
            self::end_render();
        }
    }
}
