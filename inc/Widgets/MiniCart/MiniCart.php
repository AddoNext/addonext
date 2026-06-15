<?php
namespace Veloria\Widgets\MiniCart;

use Veloria\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class MiniCart extends WidgetBase
{
    private static bool $fragments_hooked = false;

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_assets']);
    }

    public function enqueue_preview_assets(): void
    {
        wp_enqueue_style('veloria-mini-cart');
        wp_enqueue_script('veloria-mini-cart');
    }

    public function get_icon(): string
    {
        return 'eicon-cart veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-mini-cart';
    }

    public function get_admin_category(): string
    {
        return 'woocommerce';
    }

    public function get_title(): string
    {
        return __('Mini Cart', 'veloria');
    }

    public function get_description(): string
    {
        return __('WooCommerce cart link with icon and item count.', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#7f54b3';
    }

    public function get_style_depends(): array
    {
        return ['veloria-mini-cart'];
    }

    public function get_script_depends(): array
    {
        return ['veloria-mini-cart'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'veloria-mini-cart',
            VELORIA_URL . 'assets/css/mini-cart.css',
            [],
            VELORIA_VERSION
        );

        wp_register_script(
            'veloria-mini-cart',
            VELORIA_URL . 'assets/js/mini-cart.js',
            ['jquery'],
            VELORIA_VERSION,
            true
        );
    }

    /**
     * Hook fragment filter early so it runs on WC AJAX requests too.
     * Called from WidgetManager during `init`.
     */
    public static function init_fragment_hooks(): void
    {
        if (self::$fragments_hooked || !class_exists('WooCommerce')) {
            return;
        }
        self::$fragments_hooked = true;
        add_filter('woocommerce_add_to_cart_fragments', [__CLASS__, 'filter_cart_fragments'], 20);
    }

    /**
     * @param array<string, string> $fragments
     * @return array<string, string>
     */
    public static function filter_cart_fragments(array $fragments): array
    {
        if (!function_exists('WC') || !WC()->cart) {
            return $fragments;
        }

        $count = (string) WC()->cart->get_cart_contents_count();
        $empty_class = $count === '0' ? ' veloria-mini-cart__count--empty' : '';
        $fragments['span.veloria-mini-cart__count'] =
            '<span class="veloria-mini-cart__count' . $empty_class . '">' . esc_html($count) . '</span>';
        $fragments['span.veloria-mini-cart__subtotal'] =
            '<span class="veloria-mini-cart__subtotal">' . WC()->cart->get_cart_subtotal() . '</span>';

        return $fragments;
    }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'veloria'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('icon', [
            'label'       => __('Cart icon', 'veloria'),
            'type'        => Controls_Manager::ICONS,
            'default'     => [
                'value'   => 'eicon-cart',
                'library' => 'eicons',
            ],
            'render_type' => 'template',
        ]);

        $this->add_control('show_count', [
            'label'        => __('Show item count', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
            'render_type'  => 'template',
        ]);

        $this->add_control('show_subtotal', [
            'label'        => __('Show subtotal', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'no',
            'render_type'  => 'template',
        ]);

        $this->add_control('show_cart_label', [
            'label'        => __('Show label', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'no',
            'render_type'  => 'template',
        ]);

        $this->add_control('cart_label', [
            'label'       => __('Label', 'veloria'),
            'type'        => Controls_Manager::TEXT,
            'default'     => __('Cart', 'veloria'),
            'condition'   => ['show_cart_label' => 'yes'],
            'render_type' => 'template',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_trigger', [
            'label' => __('Trigger', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('trigger_align', [
            'label'     => __('Alignment', 'veloria'),
            'type'      => Controls_Manager::CHOOSE,
            'options'   => [
                'flex-start' => [
                    'title' => __('Left', 'veloria'),
                    'icon'  => 'eicon-text-align-left',
                ],
                'center'     => [
                    'title' => __('Center', 'veloria'),
                    'icon'  => 'eicon-text-align-center',
                ],
                'flex-end'   => [
                    'title' => __('Right', 'veloria'),
                    'icon'  => 'eicon-text-align-right',
                ],
            ],
            'default'   => 'flex-end',
            'selectors' => [
                '{{WRAPPER}} .veloria-mini-cart__inner' => 'justify-content: {{VALUE}};',
            ],
        ]);

        $this->add_control('trigger_color', [
            'label'     => __('Color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#0f172a',
            'selectors' => [
                '{{WRAPPER}} .veloria-mini-cart__trigger' => 'color: {{VALUE}};',
                '{{WRAPPER}} .veloria-mini-cart__trigger .eicon' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('trigger_bg', [
            'label'     => __('Background', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .veloria-mini-cart__trigger' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'trigger_typography',
                'selector' => '{{WRAPPER}} .veloria-mini-cart__label, {{WRAPPER}} .veloria-mini-cart__subtotal',
            ]
        );

        $this->add_control('trigger_padding', [
            'label'      => __('Padding', 'veloria'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default'    => [
                'top' => '8', 'right' => '12', 'bottom' => '8', 'left' => '12', 'unit' => 'px',
            ],
            'selectors'  => [
                '{{WRAPPER}} .veloria-mini-cart__trigger' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_control('trigger_radius', [
            'label'      => __('Border radius', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 40]],
            'default'    => ['size' => 8, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-mini-cart__trigger' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_badge', [
            'label'     => __('Count badge', 'veloria'),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => ['show_count' => 'yes'],
        ]);

        $this->add_control('badge_bg', [
            'label'     => __('Background', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#7f54b3',
            'selectors' => [
                '{{WRAPPER}} .veloria-mini-cart__count' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('badge_color', [
            'label'     => __('Text color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .veloria-mini-cart__count' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('badge_radius', [
            'label'      => __('Border radius', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 24]],
            'default'    => ['size' => 10, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-mini-cart__count' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = VELORIA_DIR . 'templates/mini-cart/mini-cart.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        var showCount = ( settings.show_count !== 'no' && settings.show_count !== '' );
        var showSubtotal = settings.show_subtotal === 'yes';
        var showLabel = settings.show_cart_label === 'yes';
        var iconHTML = elementor.helpers.renderIcon( view, settings.icon, { 'aria-hidden': true }, 'i', 'object' );
        #>
        <div class="veloria-mini-cart">
            <div class="veloria-mini-cart__inner">
                <a href="#" class="veloria-mini-cart__trigger" onclick="event.preventDefault();">
                    <span class="veloria-mini-cart__icon" aria-hidden="true">
                        <# if ( iconHTML && iconHTML.rendered ) { #>
                            {{{ elementor.helpers.sanitize( iconHTML.value ) }}}
                        <# } else { #>
                            <i class="eicon-cart" aria-hidden="true"></i>
                        <# } #>
                    </span>
                    <# if ( showLabel ) { #>
                        <span class="veloria-mini-cart__label">{{{ settings.cart_label }}}</span>
                    <# } #>
                    <# if ( showSubtotal ) { #>
                        <span class="veloria-mini-cart__subtotal"><?php echo esc_html__('$0.00', 'veloria'); ?></span>
                    <# } #>
                    <# if ( showCount ) { #>
                        <span class="veloria-mini-cart__count">0</span>
                    <# } #>
                </a>
            </div>
        </div>
        <?php
    }
}
