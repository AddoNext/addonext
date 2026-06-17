<?php
namespace Veloria\Widgets\WooThankYou;

use Veloria\Base\WidgetBase;
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
        wp_enqueue_style('veloria-woo-thank-you');
    }

    public function get_icon(): string
    {
        return 'eicon-check-circle-o veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-woo-thank-you';
    }

    public function get_admin_category(): string
    {
        return 'woocommerce';
    }

    public function get_title(): string
    {
        return __('WooCommerce Thank You', 'veloria');
    }

    public function get_description(): string
    {
        return __('Thank you message with order summary and action buttons after checkout.', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#7f54b3';
    }

    public function get_style_depends(): array
    {
        return ['veloria-woo-thank-you'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'veloria-woo-thank-you',
            VELORIA_URL . 'assets/css/woo-thank-you.css',
            [],
            VELORIA_VERSION
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
            'label' => __('Content', 'veloria'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('show_success_icon', [
            'label'        => __('Show success icon', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('success_icon', [
            'label'       => __('Success icon', 'veloria'),
            'type'        => Controls_Manager::ICONS,
            'default'     => [
                'value'   => 'fas fa-check',
                'library' => 'fa-solid',
            ],
            'condition'   => ['show_success_icon' => 'yes'],
            'render_type' => 'template',
        ]);

        $this->add_control('heading', [
            'label'       => __('Heading', 'veloria'),
            'type'        => Controls_Manager::TEXT,
            'default'     => __('Thank you for your order!', 'veloria'),
            'label_block' => true,
        ]);

        $this->add_control('description', [
            'label'   => __('Description', 'veloria'),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => __('Your order has been received and is now being processed. You will receive an email confirmation shortly.', 'veloria'),
            'rows'    => 3,
        ]);

        $this->add_control('show_details', [
            'label'        => __('Show order details', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('label_order_number', [
            'label'     => __('Order number label', 'veloria'),
            'type'      => Controls_Manager::TEXT,
            'default'   => __('Order Number:', 'veloria'),
            'condition' => ['show_details' => 'yes'],
        ]);

        $this->add_control('label_date', [
            'label'     => __('Date label', 'veloria'),
            'type'      => Controls_Manager::TEXT,
            'default'   => __('Date:', 'veloria'),
            'condition' => ['show_details' => 'yes'],
        ]);

        $this->add_control('label_total', [
            'label'     => __('Total label', 'veloria'),
            'type'      => Controls_Manager::TEXT,
            'default'   => __('Total:', 'veloria'),
            'condition' => ['show_details' => 'yes'],
        ]);

        $this->add_control('label_payment', [
            'label'     => __('Payment label', 'veloria'),
            'type'      => Controls_Manager::TEXT,
            'default'   => __('Payment:', 'veloria'),
            'condition' => ['show_details' => 'yes'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_actions', [
            'label' => __('Buttons', 'veloria'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('show_track', [
            'label'        => __('Track order', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('track_label', [
            'label'     => __('Track label', 'veloria'),
            'type'      => Controls_Manager::TEXT,
            'default'   => __('Track Your Order', 'veloria'),
            'condition' => ['show_track' => 'yes'],
        ]);

        $this->add_control('track_icon', [
            'label'     => __('Track icon', 'veloria'),
            'type'      => Controls_Manager::ICONS,
            'default'   => [
                'value'   => 'fas fa-truck',
                'library' => 'fa-solid',
            ],
            'condition' => ['show_track' => 'yes'],
        ]);

        $this->add_control('track_url', [
            'label'       => __('Track URL', 'veloria'),
            'type'        => Controls_Manager::URL,
            'description' => __('Leave empty to use the order view URL when an order is available.', 'veloria'),
            'condition'   => ['show_track' => 'yes'],
        ]);

        $this->add_control('show_continue', [
            'label'        => __('Continue shopping', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('continue_label', [
            'label'     => __('Continue label', 'veloria'),
            'type'      => Controls_Manager::TEXT,
            'default'   => __('Continue Shopping', 'veloria'),
            'condition' => ['show_continue' => 'yes'],
        ]);

        $this->add_control('continue_icon', [
            'label'     => __('Continue icon', 'veloria'),
            'type'      => Controls_Manager::ICONS,
            'default'   => [
                'value'   => 'fas fa-shopping-bag',
                'library' => 'fa-solid',
            ],
            'condition' => ['show_continue' => 'yes'],
        ]);

        $this->add_control('continue_url', [
            'label'       => __('Continue URL', 'veloria'),
            'type'        => Controls_Manager::URL,
            'description' => __('Leave empty to use the shop page URL.', 'veloria'),
            'condition'   => ['show_continue' => 'yes'],
        ]);

        $this->add_control('show_receipt', [
            'label'        => __('Receipt button', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('receipt_label', [
            'label'     => __('Receipt label', 'veloria'),
            'type'      => Controls_Manager::TEXT,
            'default'   => __('Download Receipt', 'veloria'),
            'condition' => ['show_receipt' => 'yes'],
        ]);

        $this->add_control('receipt_icon', [
            'label'     => __('Receipt icon', 'veloria'),
            'type'      => Controls_Manager::ICONS,
            'default'   => [
                'value'   => 'fas fa-download',
                'library' => 'fa-solid',
            ],
            'condition' => ['show_receipt' => 'yes'],
        ]);

        $this->add_control('receipt_url', [
            'label'       => __('Receipt URL', 'veloria'),
            'type'        => Controls_Manager::URL,
            'description' => __('Leave empty to use the order view URL. Point to a PDF or invoice plugin endpoint if needed.', 'veloria'),
            'condition'   => ['show_receipt' => 'yes'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_card', [
            'label' => __('Card', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('card_bg', [
            'label'     => __('Background', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#e8f8ef',
            'selectors' => [
                '{{WRAPPER}} .veloria-wty__surface' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('card_radius', [
            'label'      => __('Radius', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 48]],
            'default'    => ['size' => 16, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-wty__surface' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('card_padding', [
            'label'      => __('Padding', 'veloria'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default'    => [
                'top' => '32', 'right' => '28', 'bottom' => '32', 'left' => '28', 'unit' => 'px',
            ],
            'selectors'  => [
                '{{WRAPPER}} .veloria-wty__surface' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_icon', [
            'label'     => __('Success icon', 'veloria'),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => ['show_success_icon' => 'yes'],
        ]);

        $this->add_control('success_icon_bg', [
            'label'     => __('Circle background', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#c8eed9',
            'selectors' => [
                '{{WRAPPER}} .veloria-wty__success-icon' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('success_icon_color', [
            'label'     => __('Icon color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1a7f4c',
            'selectors' => [
                '{{WRAPPER}} .veloria-wty__success-icon' => 'color: {{VALUE}};',
                '{{WRAPPER}} .veloria-wty__success-icon svg' => 'fill: {{VALUE}};',
            ],
        ]);

        $this->add_control('success_icon_size', [
            'label'      => __('Size', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 32, 'max' => 96]],
            'default'    => ['size' => 56, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-wty__success-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: calc({{SIZE}}{{UNIT}} * 0.45);',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_heading', [
            'label' => __('Heading', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('heading_color', [
            'label'     => __('Color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#0f172a',
            'selectors' => [
                '{{WRAPPER}} .veloria-wty__heading' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'heading_typography',
                'selector' => '{{WRAPPER}} .veloria-wty__heading',
            ]
        );

        $this->add_control('description_color', [
            'label'     => __('Description color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#64748b',
            'selectors' => [
                '{{WRAPPER}} .veloria-wty__description' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'description_typography',
                'selector' => '{{WRAPPER}} .veloria-wty__description',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('section_style_details', [
            'label'     => __('Order box', 'veloria'),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => ['show_details' => 'yes'],
        ]);

        $this->add_control('details_bg', [
            'label'     => __('Background', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .veloria-wty__details' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('details_border', [
            'label'     => __('Border color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#bfe5d0',
            'selectors' => [
                '{{WRAPPER}} .veloria-wty__details' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('details_radius', [
            'label'      => __('Radius', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 24]],
            'default'    => ['size' => 12, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-wty__details' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('detail_label_color', [
            'label'     => __('Label color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#64748b',
            'selectors' => [
                '{{WRAPPER}} .veloria-wty__detail-label' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('detail_value_color', [
            'label'     => __('Value color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#0f172a',
            'selectors' => [
                '{{WRAPPER}} .veloria-wty__detail-value' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_buttons', [
            'label' => __('Buttons', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('btn_track_bg', [
            'label'     => __('Track background', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#2563eb',
            'selectors' => [
                '{{WRAPPER}} .veloria-wty-btn--track' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('btn_track_color', [
            'label'     => __('Track text', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .veloria-wty-btn--track' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('btn_continue_bg', [
            'label'     => __('Continue background', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#f1f5f9',
            'selectors' => [
                '{{WRAPPER}} .veloria-wty-btn--continue' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('btn_continue_color', [
            'label'     => __('Continue text', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#0f172a',
            'selectors' => [
                '{{WRAPPER}} .veloria-wty-btn--continue' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('btn_receipt_bg', [
            'label'     => __('Receipt background', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#16a34a',
            'selectors' => [
                '{{WRAPPER}} .veloria-wty-btn--receipt' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('btn_receipt_color', [
            'label'     => __('Receipt text', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .veloria-wty-btn--receipt' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('btn_radius', [
            'label'      => __('Radius', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 24]],
            'default'    => ['size' => 10, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-wty-btn' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('actions_gap', [
            'label'      => __('Gap', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 32]],
            'default'    => ['size' => 12, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-wty__actions' => 'gap: {{SIZE}}{{UNIT}};',
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
            echo '<div class="veloria-wty veloria-wty--inactive">';
            echo esc_html__('WooCommerce is required for this widget.', 'veloria');
            echo '</div>';

            return;
        }

        $veloria_settings = $this->get_settings_for_display();
        $order = $this->resolve_order();
        $use_preview = $this->is_elementor_design_context();

        if (!$order && !$use_preview) {
            echo '<div class="veloria-wty veloria-wty--empty">';
            echo esc_html__('Order details appear here after a successful checkout.', 'veloria');
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
            isset($veloria_settings['track_url']) && is_array($veloria_settings['track_url']) ? $veloria_settings['track_url'] : [],
            $view_order_fallback
        );

        $continue_link = $this->resolve_link(
            isset($veloria_settings['continue_url']) && is_array($veloria_settings['continue_url']) ? $veloria_settings['continue_url'] : [],
            $fallback_shop
        );

        $receipt_link = $this->resolve_link(
            isset($veloria_settings['receipt_url']) && is_array($veloria_settings['receipt_url']) ? $veloria_settings['receipt_url'] : [],
            $view_order_fallback
        );

        if ($use_preview && !$order) {
            $veloria_order_number = '#12848';
            $veloria_order_date = date_i18n(get_option('date_format'), strtotime('2023-12-16'));
            $veloria_order_total = function_exists('wc_price') ? wc_price(313.49) : '$313.49';
            $veloria_payment_label = __('Credit Card', 'veloria');
        } elseif ($order instanceof \WC_Order) {
            $veloria_order_number = '#' . $order->get_order_number();
            $created = $order->get_date_created();
            $veloria_order_date = $created ? wc_format_datetime($created) : '';
            $veloria_order_total = wp_kses_post($order->get_formatted_order_total());
            $veloria_payment_label = $order->get_payment_method_title();
            if ($veloria_payment_label === '') {
                $veloria_payment_label = __('N/A', 'veloria');
            }
        } else {
            $veloria_order_number = '';
            $veloria_order_date = '';
            $veloria_order_total = '';
            $veloria_payment_label = '';
        }

        $this->add_link_attributes('veloria_wty_track', [
            'url'         => $track_link['url'],
            'is_external' => $track_link['is_external'] ? 'on' : '',
            'nofollow'    => $track_link['nofollow'] ? 'on' : '',
        ]);
        $this->add_link_attributes('veloria_wty_continue', [
            'url'         => $continue_link['url'],
            'is_external' => $continue_link['is_external'] ? 'on' : '',
            'nofollow'    => $continue_link['nofollow'] ? 'on' : '',
        ]);
        $this->add_link_attributes('veloria_wty_receipt', [
            'url'         => $receipt_link['url'],
            'is_external' => $receipt_link['is_external'] ? 'on' : '',
            'nofollow'    => $receipt_link['nofollow'] ? 'on' : '',
        ]);

        $path = VELORIA_DIR . 'templates/woo-thank-you/woo-thank-you.php';
        if (!is_readable($path)) {
            return;
        }

        include $path;
    }
}
