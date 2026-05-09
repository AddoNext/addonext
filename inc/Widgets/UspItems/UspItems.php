<?php
namespace Elementrify\Widgets\UspItems;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;

defined('ABSPATH') || exit;

/**
 * Repeater grid of USP cards with icon ring, title, and subtitle.
 */
class UspItems extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_assets_in_preview']);
    }

    /**
     * Ensures styles load in the Elementor editor canvas.
     */
    public function enqueue_assets_in_preview(): void
    {
        $this->enqueue_assets();
        wp_enqueue_style('elementrify-usp-items');
    }

    public function get_icon(): string
    {
        return 'eicon-bullet-list elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-usp-items';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('USP Items', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Feature cards with icon, title, and short line in a responsive grid.', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#4f46e5';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-usp-items'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-usp-items',
            ELEMENTRIFY_URL . 'assets/css/usp-items.css',
            [],
            ELEMENTRIFY_VERSION
        );
    }

    public function register_controls(): void
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'elementrify'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'item_icon',
            [
                'label'            => __('Icon', 'elementrify'),
                'type'             => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default'          => [
                    'value'   => 'fas fa-rocket',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $repeater->add_control(
            'circle_bg',
            [
                'label'   => __('Icon circle background', 'elementrify'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#e0e7ff',
            ]
        );

        $repeater->add_control(
            'icon_color',
            [
                'label'   => __('Icon color', 'elementrify'),
                'type'    => Controls_Manager::COLOR,
                'default' => '#2563eb',
            ]
        );

        $repeater->add_control(
            'item_title',
            [
                'label'       => __('Title', 'elementrify'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Feature title', 'elementrify'),
                'label_block' => true,
                'dynamic'     => ['active' => true],
            ]
        );

        $repeater->add_control(
            'item_subtitle',
            [
                'label'       => __('Subtitle', 'elementrify'),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Short supporting line', 'elementrify'),
                'label_block' => true,
                'dynamic'     => ['active' => true],
            ]
        );

        $repeater->add_control(
            'item_link',
            [
                'label'   => __('Link', 'elementrify'),
                'type'    => Controls_Manager::URL,
                'dynamic' => ['active' => true],
            ]
        );

        $this->add_control(
            'items',
            [
                'label'       => __('Items', 'elementrify'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'item_icon'     => ['value' => 'fas fa-rocket', 'library' => 'fa-solid'],
                        'circle_bg'     => '#e0e7ff',
                        'icon_color'    => '#2563eb',
                        'item_title'    => __('Fast Performance', 'elementrify'),
                        'item_subtitle' => __('Lightning speed', 'elementrify'),
                    ],
                    [
                        'item_icon'     => ['value' => 'fas fa-lock', 'library' => 'fa-solid'],
                        'circle_bg'     => '#ede9fe',
                        'icon_color'    => '#7c3aed',
                        'item_title'    => __('Secure & Safe', 'elementrify'),
                        'item_subtitle' => __('Protected data', 'elementrify'),
                    ],
                    [
                        'item_icon'     => ['value' => 'fas fa-headset', 'library' => 'fa-solid'],
                        'circle_bg'     => '#d1fae5',
                        'icon_color'    => '#059669',
                        'item_title'    => __('24/7 Support', 'elementrify'),
                        'item_subtitle' => __('Always available', 'elementrify'),
                    ],
                    [
                        'item_icon'     => ['value' => 'fas fa-star', 'library' => 'fa-solid'],
                        'circle_bg'     => '#fef3c7',
                        'icon_color'    => '#d97706',
                        'item_title'    => __('Proven quality', 'elementrify'),
                        'item_subtitle' => __('Trusted by teams', 'elementrify'),
                    ],
                ],
                'title_field' => '{{{ item_title }}}',
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label'   => __('Title HTML tag', 'elementrify'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'h3',
                'options' => [
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'div' => 'div',
                    'p'  => 'p',
                ],
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label'          => __('Columns', 'elementrify'),
                'type'           => Controls_Manager::NUMBER,
                'min'            => 1,
                'max'            => 6,
                'step'           => 1,
                'default'        => 4,
                'tablet_default' => 2,
                'mobile_default' => 1,
                'selectors'      => [
                    '{{WRAPPER}} .elementrify-usp-items__grid' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
                ],
            ]
        );

        $this->add_responsive_control(
            'row_gap',
            [
                'label'      => __('Row gap', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 80]],
                'default'    => ['size' => 12, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-usp-items__grid' => 'row-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'column_gap',
            [
                'label'      => __('Column gap', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 80]],
                'default'    => ['size' => 12, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-usp-items__grid' => 'column-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_card',
            [
                'label' => __('Card', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_bg',
            [
                'label'     => __('Background', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-usp-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'card_shadow',
                'label'    => __('Box shadow', 'elementrify'),
                'selector' => '{{WRAPPER}} .elementrify-usp-item',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'card_border',
                'label'    => __('Border', 'elementrify'),
                'selector' => '{{WRAPPER}} .elementrify-usp-item',
            ]
        );

        $this->add_responsive_control(
            'card_radius',
            [
                'label'      => __('Border radius', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => ['px' => ['min' => 0, 'max' => 40]],
                'default'    => ['size' => 12, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-usp-item' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label'      => __('Padding', 'elementrify'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default'    => [
                    'top' => '16', 'right' => '16', 'bottom' => '16', 'left' => '16',
                    'unit' => 'px', 'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-usp-item__surface' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_min_height',
            [
                'label'      => __('Min height', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 400]],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-usp-item' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_icon_ring',
            [
                'label' => __('Icon circle', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'icon_ring_size',
            [
                'label'      => __('Circle size', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 40, 'max' => 120]],
                'default'    => ['size' => 56, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-usp-item__icon-ring' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_inner_size',
            [
                'label'      => __('Icon size', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 16, 'max' => 56]],
                'default'    => ['size' => 22, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-usp-item__icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_ring_margin_bottom',
            [
                'label'      => __('Space below circle', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 48]],
                'default'    => ['size' => 14, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-usp-item__icon-ring' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_title_style',
            [
                'label' => __('Title', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'label'    => __('Typography', 'elementrify'),
                'selector' => '{{WRAPPER}} .elementrify-usp-item__title',
                'fields_options' => [
                    'typography'  => ['default' => 'yes'],
                    'font_weight' => ['default' => '700'],
                    'font_size'   => ['default' => ['size' => 16, 'unit' => 'px']],
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => __('Color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#111827',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-usp-item__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label'      => __('Space below title', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 32]],
                'default'    => ['size' => 4, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-usp-item__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_subtitle_style',
            [
                'label' => __('Subtitle', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'subtitle_typography',
                'label'    => __('Typography', 'elementrify'),
                'selector' => '{{WRAPPER}} .elementrify-usp-item__subtitle',
                'fields_options' => [
                    'typography'  => ['default' => 'yes'],
                    'font_weight' => ['default' => '400'],
                    'font_size'   => ['default' => ['size' => 14, 'unit' => 'px']],
                ],
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label'     => __('Color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#6b7280',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-usp-item__subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_hover',
            [
                'label' => __('Hover', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_hover_effect',
            [
                'label'   => __('Effect', 'elementrify'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'lift_glow',
                'options' => [
                    'none'      => __('None', 'elementrify'),
                    'lift_glow' => __('Lift and glow shadow', 'elementrify'),
                    'bg'        => __('Background color', 'elementrify'),
                    'zoom'      => __('Zoom in', 'elementrify'),
                    'zoom_out'  => __('Zoom out', 'elementrify'),
                    'jump'      => __('Jump', 'elementrify'),
                ],
            ]
        );

        $this->add_control(
            'hover_bg_color',
            [
                'label'     => __('Hover background', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f9fafb',
                'condition' => ['card_hover_effect' => 'bg'],
                'selectors' => [
                    '{{WRAPPER}} .elementrify-usp-items' => '--usp-hover-card-bg: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hover_shadow_color',
            [
                'label'     => __('Glow shadow', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(79, 70, 229, 0.2)',
                'condition' => ['card_hover_effect' => 'lift_glow'],
                'selectors' => [
                    '{{WRAPPER}} .elementrify-usp-items' => '--usp-hover-glow: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hover_lift_distance',
            [
                'label'      => __('Lift distance (px)', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 24]],
                'default'    => ['size' => 8, 'unit' => 'px'],
                'condition'  => ['card_hover_effect' => 'lift_glow'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-usp-items' => '--usp-lift: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'hover_transition_ms',
            [
                'label'      => __('Transition (ms)', 'elementrify'),
                'type'       => Controls_Manager::NUMBER,
                'min'        => 0,
                'max'        => 800,
                'step'       => 10,
                'default'    => 220,
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-usp-item' => 'transition-duration: {{VALUE}}ms;',
                    '{{WRAPPER}} .elementrify-usp-item__icon' => 'transition-duration: {{VALUE}}ms;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $settings = $this->get_settings_for_display();
        $effect = isset($settings['card_hover_effect']) ? (string) $settings['card_hover_effect'] : 'lift_glow';
        $allowed_effects = ['none', 'lift_glow', 'bg', 'zoom', 'zoom_out', 'jump'];
        if ($effect === '' || !in_array($effect, $allowed_effects, true)) {
            $effect = 'lift_glow';
        }

        $this->add_render_attribute('wrapper', 'class', [
            'elementrify-usp-items',
            'elementrify-usp-items--hover-' . sanitize_html_class($effect),
        ]);

        $path = ELEMENTRIFY_DIR . 'templates/usp-items/usp-items.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        var effect = settings.card_hover_effect || 'lift_glow';
        var tag = settings.title_tag || 'h3';
        #>
        <div class="elementrify-usp-items elementrify-usp-items--hover-{{ effect }}">
            <div class="elementrify-usp-items__grid">
                <# _.each( settings.items, function( item ) {
                    var circleBg = String( item.circle_bg || '#e0e7ff' ).replace( /"/g, '' );
                    var iconColor = String( item.icon_color || '#2563eb' ).replace( /"/g, '' );
                #>
                    <article class="elementrify-usp-item elementor-repeater-item-{{ item._id }}">
                        <div class="elementrify-usp-item__surface">
                            <# print( '<span class="elementrify-usp-item__icon-ring" style="background-color:' + circleBg + ';">' ); #>
                                <# print( '<span class="elementrify-usp-item__icon" style="color:' + iconColor + ';">' ); #>
                                    <# if ( item.item_icon && item.item_icon.value ) { #>
                                        {{{ elementor.helpers.renderIcon( view, item.item_icon, { 'aria-hidden': 'true' }, 'i', 'object' ).value }}}
                                    <# } #>
                                </span>
                            </span>
                            <# print( '<' + tag + ' class="elementrify-usp-item__title">' ); #>{{{ item.item_title }}}<# print( '</' + tag + '>' ); #>
                            <p class="elementrify-usp-item__subtitle">{{{ item.item_subtitle }}}</p>
                        </div>
                    </article>
                <# } ); #>
            </div>
        </div>
        <?php
    }
}
