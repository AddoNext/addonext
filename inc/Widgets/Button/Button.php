<?php
namespace Elementrify\Widgets\Button;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

defined('ABSPATH') or exit;

class Button extends WidgetBase
{
    public static function get_button_sizes(): array
    {
        return [
            'xs' => __('Extra Small', 'elementrify'),
            'sm' => __('Small', 'elementrify'),
            'md' => __('Medium', 'elementrify'),
            'lg' => __('Large', 'elementrify'),
            'xl' => __('Extra Large', 'elementrify'),
        ];
    }

    public static function get_variants(): array
    {
        return [
            'primary'   => __('Primary', 'elementrify'),
            'secondary' => __('Secondary', 'elementrify'),
        ];
    }

    public function get_icon(): string
    {
        return 'eicon-button elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-button';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Button', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Fully customizable button with variants, link, icon and styles', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#3b82f6';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-button'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-button',
            ELEMENTRIFY_URL . 'assets/css/button.css',
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

        $this->add_control(
            'variant',
            [
                'label'   => __('Variant', 'elementrify'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'primary',
                'options' => self::get_variants(),
            ]
        );

        $this->add_control(
            'text',
            [
                'label'       => __('Text', 'elementrify'),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => ['active' => true],
                'default'     => __('Click Here', 'elementrify'),
                'placeholder' => __('Click Here', 'elementrify'),
            ]
        );

        $this->add_control(
            'link',
            [
                'label'   => __('Link', 'elementrify'),
                'type'    => Controls_Manager::URL,
                'dynamic' => ['active' => true],
                'default' => [
                    'url'         => '#',
                    'is_external' => false,
                    'nofollow'    => false,
                ],
            ]
        );

        $this->add_control(
            'size',
            [
                'label'       => __('Size', 'elementrify'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'md',
                'options'     => self::get_button_sizes(),
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'selected_icon',
            [
                'label'            => __('Icon', 'elementrify'),
                'type'             => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'label_block'      => false,
                'default'          => [
                    'value'   => '',
                    'library' => '',
                ],
            ]
        );

        $start = is_rtl() ? 'right' : 'left';
        $end   = is_rtl() ? 'left' : 'right';

        $this->add_control(
            'icon_align',
            [
                'label'   => __('Icon Position', 'elementrify'),
                'type'    => Controls_Manager::CHOOSE,
                'default' => is_rtl() ? 'row-reverse' : 'row',
                'options' => [
                    'row'          => [
                        'title' => __('Start', 'elementrify'),
                        'icon'  => "eicon-h-align-{$start}",
                    ],
                    'row-reverse'  => [
                        'title' => __('End', 'elementrify'),
                        'icon'  => "eicon-h-align-{$end}",
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementrify-button__content' => 'flex-direction: {{VALUE}};',
                ],
                'condition' => [
                    'text!' => '',
                    'selected_icon[value]!' => '',
                ],
            ]
        );

        $this->add_control(
            'icon_indent',
            [
                'label'      => __('Icon Spacing', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range'      => [
                    'px'  => ['max' => 50],
                    'em'  => ['max' => 5],
                    'rem' => ['max' => 5],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementrify-button__content' => 'gap: {{SIZE}}{{UNIT}};',
                ],
                'default'   => ['size' => 4, 'unit' => 'px'],
                'condition' => [
                    'text!' => '',
                    'selected_icon[value]!' => '',
                ],
            ]
        );

        $this->add_control(
            'button_css_id',
            [
                'label'       => __('Button ID', 'elementrify'),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => ['active' => true],
                'default'     => '',
                'title'       => __('Add your custom id WITHOUT the Pound key. e.g: my-id', 'elementrify'),
                'separator'   => 'before',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_common',
            [
                'label' => __('Alignment & typography', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label'   => __('Alignment', 'elementrify'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'left'    => [
                        'title' => __('Left', 'elementrify'),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center'  => [
                        'title' => __('Center', 'elementrify'),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'right'   => [
                        'title' => __('Right', 'elementrify'),
                        'icon'  => 'eicon-h-align-right',
                    ],
                    'justify' => [
                        'title' => __('Stretch', 'elementrify'),
                        'icon'  => 'eicon-h-align-stretch',
                    ],
                ],
                'default'      => 'left',
                'prefix_class' => 'elementrify-button-wrapper%s-align-',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography',
                'selector' => '{{WRAPPER}} .elementrify-button',
                'fields_options' => [
                    'font_size' => [
                        'default' => ['unit' => 'px', 'size' => 14],
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'text_shadow',
                'selector' => '{{WRAPPER}} .elementrify-button',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_primary',
            [
                'label'     => __('Primary style', 'elementrify'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => ['variant' => 'primary'],
            ]
        );

        $this->start_controls_tabs('tabs_primary_style');

        $this->start_controls_tab('tab_primary_normal', ['label' => __('Normal', 'elementrify')]);

        $this->add_control(
            'button_text_color',
            [
                'label'     => __('Text & icon color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-button' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'background',
                'types'    => ['classic', 'gradient'],
                'exclude'  => ['image'],
                'selector' => '{{WRAPPER}} .elementrify-button',
                'fields_options' => [
                    'background' => ['default' => 'gradient'],
                    'color'     => ['default' => '#5E42DA'],
                    'color_b'   => ['default' => '#A542DA'],
                    'gradient_type' => ['default' => 'linear'],
                    'gradient_angle' => ['default' => ['unit' => 'deg', 'size' => 90]],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .elementrify-button',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('tab_primary_hover', ['label' => __('Hover', 'elementrify')]);

        $this->add_control(
            'hover_text_color',
            [
                'label'     => __('Text & icon color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementrify-button:hover, {{WRAPPER}} .elementrify-button:focus' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'background_hover',
                'types'    => ['classic', 'gradient'],
                'exclude'  => ['image'],
                'selector' => '{{WRAPPER}} .elementrify-button:hover, {{WRAPPER}} .elementrify-button:focus',
                'fields_options' => ['background' => ['default' => 'classic']],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'button_box_shadow_hover',
                'selector' => '{{WRAPPER}} .elementrify-button:hover, {{WRAPPER}} .elementrify-button:focus',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_secondary',
            [
                'label'     => __('Secondary style', 'elementrify'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => ['variant' => 'secondary'],
            ]
        );

        $this->start_controls_tabs('tabs_secondary_style');

        $this->start_controls_tab('tab_secondary_normal', ['label' => __('Normal', 'elementrify')]);

        $this->add_control(
            'secondary_text_color',
            [
                'label'     => __('Text & icon color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#5E42DA',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-button' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'secondary_bg_color',
            [
                'label'     => __('Background', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'transparent',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'secondary_border',
                'selector' => '{{WRAPPER}} .elementrify-button',
                'fields_options' => [
                    'border' => ['default' => 'solid'],
                    'width'  => [
                        'default' => [
                            'top' => '2', 'right' => '2', 'bottom' => '2', 'left' => '2',
                            'unit' => 'px', 'isLinked' => true,
                        ],
                    ],
                    'color'  => ['default' => '#5E42DA'],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'secondary_box_shadow',
                'selector' => '{{WRAPPER}} .elementrify-button',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('tab_secondary_hover', ['label' => __('Hover', 'elementrify')]);

        $this->add_control(
            'secondary_hover_text_color',
            [
                'label'     => __('Text & icon color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementrify-button:hover, {{WRAPPER}} .elementrify-button:focus' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'secondary_hover_bg_color',
            [
                'label'     => __('Background', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementrify-button:hover, {{WRAPPER}} .elementrify-button:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'secondary_hover_border_color',
            [
                'label'     => __('Border color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementrify-button:hover, {{WRAPPER}} .elementrify-button:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'secondary_box_shadow_hover',
                'selector' => '{{WRAPPER}} .elementrify-button:hover, {{WRAPPER}} .elementrify-button:focus',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_shape',
            [
                'label' => __('Shape & motion', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'border_radius',
            [
                'label'      => __('Border radius', 'elementrify'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'default'    => [
                    'top' => '4', 'right' => '4', 'bottom' => '4', 'left' => '4',
                    'unit' => 'px', 'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementrify-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'padding',
            [
                'label'      => __('Padding', 'elementrify'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'default'    => [
                    'top' => '12', 'right' => '24', 'bottom' => '12', 'left' => '24',
                    'unit' => 'px', 'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementrify-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'hover_transition_duration',
            [
                'label'      => __('Transition duration', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['s', 'ms'],
                'default'    => ['unit' => 's', 'size' => 0.3],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-button' => 'transition-duration: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'hover_animation',
            [
                'label' => __('Hover animation', 'elementrify'),
                'type'  => Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $settings = $this->get_settings_for_display();
        $template_path = ELEMENTRIFY_DIR . 'templates/button/button.php';
        if (is_readable($template_path)) {
            include $template_path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        const hasText = '' !== settings.text;
        const hasIcon = settings.selected_icon && settings.selected_icon.value;
        if (! hasText && ! hasIcon) return;

        view.addRenderAttribute('wrapper', 'class', 'elementrify-button-wrapper');
        view.addRenderAttribute('button', 'class', 'elementrify-button');
        view.addRenderAttribute('button', 'class', 'elementrify-button--' + (settings.size || 'md'));
        view.addRenderAttribute('button', 'class', 'elementrify-button--' + settings.variant);

        if (settings.hover_animation) {
            view.addRenderAttribute('button', 'class', 'elementor-animation-' + settings.hover_animation);
        }
        if (settings.link && settings.link.url) {
            view.addRenderAttribute('button', 'href', elementor.helpers.sanitizeUrl(settings.link.url));
            view.addRenderAttribute('button', 'class', 'elementrify-button-link');
        } else {
            view.addRenderAttribute('button', 'role', 'button');
        }
        if (settings.button_css_id) {
            view.addRenderAttribute('button', 'id', settings.button_css_id);
        }
        view.addRenderAttribute('content', 'class', 'elementrify-button__content');
        view.addRenderAttribute('text', 'class', 'elementrify-button__text');
        view.addInlineEditingAttributes('text', 'none');

        let iconHtml = '';
        if (hasIcon) {
            iconHtml = elementor.helpers.renderIcon(view, settings.selected_icon, { 'aria-hidden': true }, 'i', 'object');
        }
        #>
        <div {{{ view.getRenderAttributeString('wrapper') }}}>
            <a {{{ view.getRenderAttributeString('button') }}}>
                <span {{{ view.getRenderAttributeString('content') }}}>
                    <# if (hasIcon && iconHtml && iconHtml.rendered) { #>
                        <span class="elementrify-button__icon">{{{ iconHtml.value }}}</span>
                    <# } #>
                    <# if (hasText) { #>
                        <span {{{ view.getRenderAttributeString('text') }}}>{{{ settings.text }}}</span>
                    <# } #>
                </span>
            </a>
        </div>
        <?php
    }
}
