<?php
namespace Addonext\Widgets\WooThankYou;

use Addonext\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;

defined('ABSPATH') || exit;

/**
 * WooCommerce order received / thank you summary for Elementor.
 */
class WooThankYou extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_assets']);
    }

    /**
     * Ensure styles load in the editor preview frame.
     */
    public function enqueue_preview_assets(): void
    {
        wp_enqueue_style('addonext-woo-thank-you');
    }

    public function get_icon(): string
    {
        return 'eicon-check-circle-o addonext-icon';
    }

    public function get_name(): string
    {
        return 'addonext-woo-thank-you';
    }

    public function get_admin_category(): string
    {
        return 'woocommerce';
    }

    public function get_title(): string
    {
        return __('WooCommerce Thank You', 'addonext');
    }

    public function get_description(): string
    {
        return __('Thank you message with order summary and action buttons after checkout.', 'addonext');
    }

    public function get_brand_color(): string
    {
        return '#7f54b3';
    }

    public function get_style_depends(): array
    {
        return ['addonext-woo-thank-you'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'addonext-woo-thank-you',
            ADDONEXT_URL . 'assets/css/woo-thank-you.css',
            [],
            ADDONEXT_VERSION
        );
    }

    /**
     * Whether the request is in Elementor edit or preview mode.
     */
    private function is_elementor_design_context(): bool
    {
        if (!class_exists('\Elementor\Plugin')) {
            return false;
        }

        $plugin = \Elementor\Plugin::$instance;

        return ($plugin->editor && $plugin->editor->is_edit_mode())
            || ($plugin->preview && $plugin->preview->is_preview_mode());
    }

    /**
     * Resolve the current thank-you order from the query string.
     *
     * @return \WC_Order|null
     */
    private function resolve_order()
    {
        if (!function_exists('wc_get_order')) {
            return null;
        }

        $order_id = absint(get_query_var('order-received'));
        if ($order_id <= 0) {
            return null;
        }

        $order = wc_get_order($order_id);
        if (!$order instanceof \WC_Order) {
            return null;
        }

        return $order;
    }

    /**
     * @param array<string, mixed> $icon
     */
    public function render_inline_icon(array $icon, string $fallback_class): void
    {
        $value = isset($icon['value']) ? trim((string) $icon['value']) : '';
        $library = isset($icon['library']) ? trim((string) $icon['library']) : '';

        if ($value !== '' && $library !== '' && class_exists(Icons_Manager::class)) {
            ob_start();
            Icons_Manager::render_icon($icon, ['aria-hidden' => 'true']);
            $rendered = trim((string) ob_get_clean());
            if ($rendered !== '') {
                echo $rendered; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor icon markup.

                return;
            }
        }

        $class = $value !== '' ? $value : $fallback_class;
        $class = preg_replace('/[^a-zA-Z0-9_\s-]/', '', $class);
        if ($class === '') {
            $class = $fallback_class;
        }

        echo '<i class="' . esc_attr(trim($class)) . '" aria-hidden="true"></i>';
    }

    /**
     * @param array<string, mixed> $settings
     * @return array{url:string,is_external:bool,nofollow:bool}
     */
    private function resolve_link(array $url_control, string $fallback_url): array
    {
        $url = isset($url_control['url']) ? trim((string) $url_control['url']) : '';
        if ($url === '') {
            $url = $fallback_url;
        }

        return [
            'url'         => $url,
            'is_external' => !empty($url_control['is_external']),
            'nofollow'    => !empty($url_control['nofollow']),
        ];
    }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'addonext'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('show_success_icon', [
            'label'        => __('Show success icon', 'addonext'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('success_icon', [
            'label'       => __('Success icon', 'addonext'),
            'type'        => Controls_Manager::ICONS,
            'default'     => [
                'value'   => 'fas fa-check',
                'library' => 'fa-solid',
            ],
            'condition'   => ['show_success_icon' => 'yes'],
            'render_type' => 'template',
        ]);

        $this->add_control('heading', [
            'label'       => __('Heading', 'addonext'),
            'type'        => Controls_Manager::TEXT,
            'default'     => __('Thank you for your order!', 'addonext'),
            'label_block' => true,
        ]);

        $this->add_control('description', [
            'label'   => __('Description', 'addonext'),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => __('Your order has been received and is now being processed. You will receive an email confirmation shortly.', 'addonext'),
            'rows'    => 3,
        ]);

        $this->add_control('show_details', [
            'label'        => __('Show order details', 'addonext'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('label_order_number', [
            'label'     => __('Order number label', 'addonext'),
            'type'      => Controls_Manager::TEXT,
            'default'   => __('Order Number:', 'addonext'),
            'condition' => ['show_details' => 'yes'],
        ]);

        $this->add_control('label_date', [
            'label'     => __('Date label', 'addonext'),
            'type'      => Controls_Manager::TEXT,
            'default'   => __('Date:', 'addonext'),
            'condition' => ['show_details' => 'yes'],
        ]);

        $this->add_control('label_total', [
            'label'     => __('Total label', 'addonext'),
            'type'      => Controls_Manager::TEXT,
            'default'   => __('Total:', 'addonext'),
            'condition' => ['show_details' => 'yes'],
        ]);

        $this->add_control('label_payment', [
            'label'     => __('Payment label', 'addonext'),
            'type'      => Controls_Manager::TEXT,
            'default'   => __('Payment:', 'addonext'),
            'condition' => ['show_details' => 'yes'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_actions', [
            'label' => __('Buttons', 'addonext'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('show_track', [
            'label'        => __('Track order', 'addonext'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('track_label', [
            'label'     => __('Track label', 'addonext'),
            'type'      => Controls_Manager::TEXT,
            'default'   => __('Track Your Order', 'addonext'),
            'condition' => ['show_track' => 'yes'],
        ]);

        $this->add_control('track_icon', [
            'label'     => __('Track icon', 'addonext'),
            'type'      => Controls_Manager::ICONS,
            'default'   => [
                'value'   => 'fas fa-truck',
                'library' => 'fa-solid',
            ],
            'condition' => ['show_track' => 'yes'],
        ]);

        $this->add_control('track_url', [
            'label'       => __('Track URL', 'addonext'),
            'type'        => Controls_Manager::URL,
            'description' => __('Leave empty to use the order view URL when an order is available.', 'addonext'),
            'condition'   => ['show_track' => 'yes'],
        ]);

        $this->add_control('show_continue', [
            'label'        => __('Continue shopping', 'addonext'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('continue_label', [
            'label'     => __('Continue label', 'addonext'),
            'type'      => Controls_Manager::TEXT,
            'default'   => __('Continue Shopping', 'addonext'),
            'condition' => ['show_continue' => 'yes'],
        ]);

        $this->add_control('continue_icon', [
            'label'     => __('Continue icon', 'addonext'),
            'type'      => Controls_Manager::ICONS,
            'default'   => [
                'value'   => 'fas fa-shopping-bag',
                'library' => 'fa-solid',
            ],
            'condition' => ['show_continue' => 'yes'],
        ]);

        $this->add_control('continue_url', [
            'label'       => __('Continue URL', 'addonext'),
            'type'        => Controls_Manager::URL,
            'description' => __('Leave empty to use the shop page URL.', 'addonext'),
            'condition'   => ['show_continue' => 'yes'],
        ]);

        $this->add_control('show_receipt', [
            'label'        => __('Receipt button', 'addonext'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('receipt_label', [
            'label'     => __('Receipt label', 'addonext'),
            'type'      => Controls_Manager::TEXT,
            'default'   => __('Download Receipt', 'addonext'),
            'condition' => ['show_receipt' => 'yes'],
        ]);

        $this->add_control('receipt_icon', [
            'label'     => __('Receipt icon', 'addonext'),
            'type'      => Controls_Manager::ICONS,
            'default'   => [
                'value'   => 'fas fa-download',
                'library' => 'fa-solid',
            ],
            'condition' => ['show_receipt' => 'yes'],
        ]);

        $this->add_control('receipt_url', [
            'label'       => __('Receipt URL', 'addonext'),
            'type'        => Controls_Manager::URL,
            'description' => __('Leave empty to use the order view URL. Point to a PDF or invoice plugin endpoint if needed.', 'addonext'),
            'condition'   => ['show_receipt' => 'yes'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_card', [
            'label' => __('Card', 'addonext'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('card_bg', [
            'label'     => __('Background', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#e8f8ef',
            'selectors' => [
                '{{WRAPPER}} .addonext-wty__surface' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('card_radius', [
            'label'      => __('Radius', 'addonext'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 48]],
            'default'    => ['size' => 16, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .addonext-wty__surface' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('card_padding', [
            'label'      => __('Padding', 'addonext'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default'    => [
                'top' => '32', 'right' => '28', 'bottom' => '32', 'left' => '28', 'unit' => 'px',
            ],
            'selectors'  => [
                '{{WRAPPER}} .addonext-wty__surface' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_icon', [
            'label'     => __('Success icon', 'addonext'),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => ['show_success_icon' => 'yes'],
        ]);

        $this->add_control('success_icon_bg', [
            'label'     => __('Circle background', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#c8eed9',
            'selectors' => [
                '{{WRAPPER}} .addonext-wty__success-icon' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('success_icon_color', [
            'label'     => __('Icon color', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1a7f4c',
            'selectors' => [
                '{{WRAPPER}} .addonext-wty__success-icon' => 'color: {{VALUE}};',
                '{{WRAPPER}} .addonext-wty__success-icon svg' => 'fill: {{VALUE}};',
            ],
        ]);

        $this->add_control('success_icon_size', [
            'label'      => __('Size', 'addonext'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 32, 'max' => 96]],
            'default'    => ['size' => 56, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .addonext-wty__success-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: calc({{SIZE}}{{UNIT}} * 0.45);',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_heading', [
            'label' => __('Heading', 'addonext'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('heading_color', [
            'label'     => __('Color', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#0f172a',
            'selectors' => [
                '{{WRAPPER}} .addonext-wty__heading' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'heading_typography',
                'selector' => '{{WRAPPER}} .addonext-wty__heading',
            ]
        );

        $this->add_control('description_color', [
            'label'     => __('Description color', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#64748b',
            'selectors' => [
                '{{WRAPPER}} .addonext-wty__description' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'description_typography',
                'selector' => '{{WRAPPER}} .addonext-wty__description',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('section_style_details', [
            'label'     => __('Order box', 'addonext'),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => ['show_details' => 'yes'],
        ]);

        $this->add_control('details_bg', [
            'label'     => __('Background', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .addonext-wty__details' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('details_border', [
            'label'     => __('Border color', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#bfe5d0',
            'selectors' => [
                '{{WRAPPER}} .addonext-wty__details' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('details_radius', [
            'label'      => __('Radius', 'addonext'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 24]],
            'default'    => ['size' => 12, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .addonext-wty__details' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('detail_label_color', [
            'label'     => __('Label color', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#64748b',
            'selectors' => [
                '{{WRAPPER}} .addonext-wty__detail-label' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('detail_value_color', [
            'label'     => __('Value color', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#0f172a',
            'selectors' => [
                '{{WRAPPER}} .addonext-wty__detail-value' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_buttons', [
            'label' => __('Buttons', 'addonext'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('btn_track_bg', [
            'label'     => __('Track background', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#2563eb',
            'selectors' => [
                '{{WRAPPER}} .addonext-wty-btn--track' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('btn_track_color', [
            'label'     => __('Track text', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .addonext-wty-btn--track' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('btn_continue_bg', [
            'label'     => __('Continue background', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#f1f5f9',
            'selectors' => [
                '{{WRAPPER}} .addonext-wty-btn--continue' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('btn_continue_color', [
            'label'     => __('Continue text', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#0f172a',
            'selectors' => [
                '{{WRAPPER}} .addonext-wty-btn--continue' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('btn_receipt_bg', [
            'label'     => __('Receipt background', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#16a34a',
            'selectors' => [
                '{{WRAPPER}} .addonext-wty-btn--receipt' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('btn_receipt_color', [
            'label'     => __('Receipt text', 'addonext'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .addonext-wty-btn--receipt' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('btn_radius', [
            'label'      => __('Radius', 'addonext'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 24]],
            'default'    => ['size' => 10, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .addonext-wty-btn' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('actions_gap', [
            'label'      => __('Gap', 'addonext'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 32]],
            'default'    => ['size' => 12, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .addonext-wty__actions' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();
    }

    public function render(): void
    {
        if (class_exists('\Elementor\Plugin')) {
            $plugin = \Elementor\Plugin::$instance;
            if (isset($plugin->frontend) && is_object($plugin->frontend) && method_exists($plugin->frontend, 'enqueue_font')) {
                $plugin->frontend->enqueue_font('fa-solid');
            }
        }

        foreach (['elementor-icons-fa-solid', 'elementor-icons-fa-regular', 'elementor-icons-fa-brands'] as $faHandle) {
            if (wp_style_is($faHandle, 'registered')) {
                wp_enqueue_style($faHandle);
            }
        }

        if (!class_exists('WooCommerce')) {
            echo '<div class="addonext-wty addonext-wty--inactive">';
            echo esc_html__('WooCommerce is required for this widget.', 'addonext');
            echo '</div>';

            return;
        }

        $addonext_settings = $this->get_settings_for_display();
        $order = $this->resolve_order();
        $use_preview = $this->is_elementor_design_context();

        if (!$order && !$use_preview) {
            echo '<div class="addonext-wty addonext-wty--empty">';
            echo esc_html__('Order details appear here after a successful checkout.', 'addonext');
            echo '</div>';

            return;
        }

        $fallback_shop = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/');
        if (!is_string($fallback_shop) || $fallback_shop === '') {
            $fallback_shop = home_url('/');
        }

        $view_order_fallback = $fallback_shop;
        if ($order instanceof \WC_Order && function_exists('wc_get_endpoint_url') && function_exists('wc_get_page_permalink')) {
            $myaccount = wc_get_page_permalink('myaccount');
            if (is_string($myaccount) && $myaccount !== '') {
                $view_order_fallback = wc_get_endpoint_url('view-order', (string) $order->get_id(), $myaccount);
            }
        }

        $track_link = $this->resolve_link(
            isset($addonext_settings['track_url']) && is_array($addonext_settings['track_url']) ? $addonext_settings['track_url'] : [],
            $view_order_fallback
        );

        $continue_link = $this->resolve_link(
            isset($addonext_settings['continue_url']) && is_array($addonext_settings['continue_url']) ? $addonext_settings['continue_url'] : [],
            $fallback_shop
        );

        $receipt_link = $this->resolve_link(
            isset($addonext_settings['receipt_url']) && is_array($addonext_settings['receipt_url']) ? $addonext_settings['receipt_url'] : [],
            $view_order_fallback
        );

        if ($use_preview && !$order) {
            $addonext_order_number = '#12848';
            $addonext_order_date = date_i18n(get_option('date_format'), strtotime('2023-12-16'));
            $addonext_order_total = function_exists('wc_price') ? wc_price(313.49) : '$313.49';
            $addonext_payment_label = __('Credit Card', 'addonext');
        } elseif ($order instanceof \WC_Order) {
            $addonext_order_number = '#' . $order->get_order_number();
            $created = $order->get_date_created();
            $addonext_order_date = $created ? wc_format_datetime($created) : '';
            $addonext_order_total = wp_kses_post($order->get_formatted_order_total());
            $addonext_payment_label = $order->get_payment_method_title();
            if ($addonext_payment_label === '') {
                $addonext_payment_label = __('N/A', 'addonext');
            }
        } else {
            $addonext_order_number = '';
            $addonext_order_date = '';
            $addonext_order_total = '';
            $addonext_payment_label = '';
        }

        $this->add_link_attributes('addonext_wty_track', [
            'url'         => $track_link['url'],
            'is_external' => $track_link['is_external'] ? 'on' : '',
            'nofollow'    => $track_link['nofollow'] ? 'on' : '',
        ]);
        $this->add_link_attributes('addonext_wty_continue', [
            'url'         => $continue_link['url'],
            'is_external' => $continue_link['is_external'] ? 'on' : '',
            'nofollow'    => $continue_link['nofollow'] ? 'on' : '',
        ]);
        $this->add_link_attributes('addonext_wty_receipt', [
            'url'         => $receipt_link['url'],
            'is_external' => $receipt_link['is_external'] ? 'on' : '',
            'nofollow'    => $receipt_link['nofollow'] ? 'on' : '',
        ]);

        $path = ADDONEXT_DIR . 'templates/woo-thank-you/woo-thank-you.php';
        if (!is_readable($path)) {
            return;
        }

        include $path;
    }
}
