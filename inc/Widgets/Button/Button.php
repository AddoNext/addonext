<?php
namespace Addonext\Widgets\Button;

use Addonext\Base\WidgetBase;
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
            'xs' => __('Extra Small', 'addonext'),
            'sm' => __('Small', 'addonext'),
            'md' => __('Medium', 'addonext'),
            'lg' => __('Large', 'addonext'),
            'xl' => __('Extra Large', 'addonext'),
        ];
    }

    public static function get_variants(): array
    {
        return [
            'primary'   => __('Primary', 'addonext'),
            'secondary' => __('Secondary', 'addonext'),
        ];
    }

    public function get_icon(): string
    {
        return 'eicon-button addonext-icon';
    }

    public function get_name(): string
    {
        return 'addonext-button';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Button', 'addonext');
    }

    public function get_description(): string
    {
        return __('Fully customizable button with variants, link, icon and styles', 'addonext');
    }

    public function get_brand_color(): string
    {
        return '#3b82f6';
    }

    public function get_style_depends(): array
    {
        return ['addonext-button'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'addonext-button',
            ADDONEXT_URL . 'assets/css/button.css',
            [],
            ADDONEXT_VERSION
        );
    }

    public function register_controls(): void
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'addonext'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'variant',
            [
                'label'   => __('Variant', 'addonext'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'primary',
                'options' => self::get_variants(),
            ]
        );

        $this->add_control(
            'text',
            [
                'label'       => __('Text', 'addonext'),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => ['active' => true],
                'default'     => __('Click Here', 'addonext'),
                'placeholder' => __('Click Here', 'addonext'),
            ]
        );

        $this->add_control(
            'link',
            [
                'label'   => __('Link', 'addonext'),
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
                'label'       => __('Size', 'addonext'),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'md',
                'options'     => self::get_button_sizes(),
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'selected_icon',
            [
                'label'            => __('Icon', 'addonext'),
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
                'label'   => __('Icon Position', 'addonext'),
                'type'    => Controls_Manager::CHOOSE,
                'default' => is_rtl() ? 'row-reverse' : 'row',
                'options' => [
                    'row'          => [
                        'title' => __('Start', 'addonext'),
                        'icon'  => "eicon-h-align-{$start}",
                    ],
                    'row-reverse'  => [
                        'title' => __('End', 'addonext'),
                        'icon'  => "eicon-h-align-{$end}",
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .addonext-button__content' => 'flex-direction: {{VALUE}};',
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
                'label'      => __('Icon Spacing', 'addonext'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range'      => [
                    'px'  => ['max' => 50],
                    'em'  => ['max' => 5],
                    'rem' => ['max' => 5],
                ],
                'selectors' => [
                    '{{WRAPPER}} .addonext-button__content' => 'gap: {{SIZE}}{{UNIT}};',
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
                'label'       => __('Button ID', 'addonext'),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => ['active' => true],
                'default'     => '',
                'title'       => __('Add your custom id WITHOUT the Pound key. e.g: my-id', 'addonext'),
                'separator'   => 'before',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_common',
            [
                'label' => __('Alignment & typography', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label'   => __('Alignment', 'addonext'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'left'    => [
                        'title' => __('Left', 'addonext'),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center'  => [
                        'title' => __('Center', 'addonext'),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'right'   => [
                        'title' => __('Right', 'addonext'),
                        'icon'  => 'eicon-h-align-right',
                    ],
                    'justify' => [
                        'title' => __('Stretch', 'addonext'),
                        'icon'  => 'eicon-h-align-stretch',
                    ],
                ],
                'default'      => 'left',
                'prefix_class' => 'addonext-button-wrapper%s-align-',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography',
                'selector' => '{{WRAPPER}} .addonext-button',
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
                'selector' => '{{WRAPPER}} .addonext-button',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_primary',
            [
                'label'     => __('Primary style', 'addonext'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => ['variant' => 'primary'],
            ]
        );

        $this->start_controls_tabs('tabs_primary_style');

        $this->start_controls_tab('tab_primary_normal', ['label' => __('Normal', 'addonext')]);

        $this->add_control(
            'button_text_color',
            [
                'label'     => __('Text & icon color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .addonext-button' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'background',
                'types'    => ['classic', 'gradient'],
                'exclude'  => ['image'],
                'selector' => '{{WRAPPER}} .addonext-button',
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
                'selector' => '{{WRAPPER}} .addonext-button',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('tab_primary_hover', ['label' => __('Hover', 'addonext')]);

        $this->add_control(
            'hover_text_color',
            [
                'label'     => __('Text & icon color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .addonext-button:hover, {{WRAPPER}} .addonext-button:focus' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'background_hover',
                'types'    => ['classic', 'gradient'],
                'exclude'  => ['image'],
                'selector' => '{{WRAPPER}} .addonext-button:hover, {{WRAPPER}} .addonext-button:focus',
                'fields_options' => ['background' => ['default' => 'classic']],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'button_box_shadow_hover',
                'selector' => '{{WRAPPER}} .addonext-button:hover, {{WRAPPER}} .addonext-button:focus',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_secondary',
            [
                'label'     => __('Secondary style', 'addonext'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => ['variant' => 'secondary'],
            ]
        );

        $this->start_controls_tabs('tabs_secondary_style');

        $this->start_controls_tab('tab_secondary_normal', ['label' => __('Normal', 'addonext')]);

        $this->add_control(
            'secondary_text_color',
            [
                'label'     => __('Text & icon color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#5E42DA',
                'selectors' => [
                    '{{WRAPPER}} .addonext-button' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'secondary_bg_color',
            [
                'label'     => __('Background', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'transparent',
                'selectors' => [
                    '{{WRAPPER}} .addonext-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'secondary_border',
                'selector' => '{{WRAPPER}} .addonext-button',
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
                'selector' => '{{WRAPPER}} .addonext-button',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('tab_secondary_hover', ['label' => __('Hover', 'addonext')]);

        $this->add_control(
            'secondary_hover_text_color',
            [
                'label'     => __('Text & icon color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .addonext-button:hover, {{WRAPPER}} .addonext-button:focus' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'secondary_hover_bg_color',
            [
                'label'     => __('Background', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .addonext-button:hover, {{WRAPPER}} .addonext-button:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'secondary_hover_border_color',
            [
                'label'     => __('Border color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .addonext-button:hover, {{WRAPPER}} .addonext-button:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'secondary_box_shadow_hover',
                'selector' => '{{WRAPPER}} .addonext-button:hover, {{WRAPPER}} .addonext-button:focus',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_shape',
            [
                'label' => __('Shape & motion', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'border_radius',
            [
                'label'      => __('Border radius', 'addonext'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'default'    => [
                    'top' => '4', 'right' => '4', 'bottom' => '4', 'left' => '4',
                    'unit' => 'px', 'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .addonext-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'padding',
            [
                'label'      => __('Padding', 'addonext'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'default'    => [
                    'top' => '12', 'right' => '24', 'bottom' => '12', 'left' => '24',
                    'unit' => 'px', 'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .addonext-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'hover_transition_duration',
            [
                'label'      => __('Transition duration', 'addonext'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['s', 'ms'],
                'default'    => ['unit' => 's', 'size' => 0.3],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-button' => 'transition-duration: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'hover_animation',
            [
                'label' => __('Hover animation', 'addonext'),
                'type'  => Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $settings = $this->get_settings_for_display();
        $template_path = ADDONEXT_DIR . 'templates/button/button.php';
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

        view.addRenderAttribute('wrapper', 'class', 'addonext-button-wrapper');
        view.addRenderAttribute('button', 'class', 'addonext-button');
        view.addRenderAttribute('button', 'class', 'addonext-button--' + (settings.size || 'md'));
        view.addRenderAttribute('button', 'class', 'addonext-button--' + settings.variant);

        if (settings.hover_animation) {
            view.addRenderAttribute('button', 'class', 'elementor-animation-' + settings.hover_animation);
        }
        if (settings.link && settings.link.url) {
            view.addRenderAttribute('button', 'href', elementor.helpers.sanitizeUrl(settings.link.url));
            view.addRenderAttribute('button', 'class', 'addonext-button-link');
        } else {
            view.addRenderAttribute('button', 'role', 'button');
        }
        if (settings.button_css_id) {
            view.addRenderAttribute('button', 'id', settings.button_css_id);
        }
        view.addRenderAttribute('content', 'class', 'addonext-button__content');
        view.addRenderAttribute('text', 'class', 'addonext-button__text');
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
                        <span class="addonext-button__icon">{{{ iconHtml.value }}}</span>
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
