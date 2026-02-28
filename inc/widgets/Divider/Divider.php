<?php
namespace Elementrify\Widgets\Divider;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;

defined('ABSPATH') or exit;

class Divider extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-divider elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-divider';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Divider', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Section dividers & separators', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#6b7280';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-divider'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-divider',
            ELEMENTRIFY_URL . 'assets/css/divider.css',
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
                'default' => 'line',
                'options' => [
                    'line'      => __('Simple line', 'elementrify'),
                    'line_icon' => __('Line with icon', 'elementrify'),
                ],
            ]
        );

        $this->add_control(
            'style',
            [
                'label'   => __('Line style', 'elementrify'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'solid',
                'options' => [
                    'solid'  => __('Solid', 'elementrify'),
                    'double' => __('Double', 'elementrify'),
                    'dotted' => __('Dotted', 'elementrify'),
                    'dashed' => __('Dashed', 'elementrify'),
                ],
            ]
        );

        $this->add_control(
            'selected_icon',
            [
                'label'     => __('Icon', 'elementrify'),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'   => 'eicon-star',
                    'library' => 'eicons',
                ],
                'condition' => ['variant' => 'line_icon'],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_line',
            [
                'label' => __('Line', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'color',
            [
                'label'     => __('Color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#e5e7eb',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-divider__line' => 'border-top-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'thickness',
            [
                'label'      => __('Thickness', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => ['px' => ['max' => 20], 'em' => ['max' => 2]],
                'default'    => ['size' => 1, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-divider__line' => 'border-top-width: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label'   => __('Alignment', 'elementrify'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'left'   => ['title' => __('Left', 'elementrify'), 'icon' => 'eicon-h-align-left'],
                    'center' => ['title' => __('Center', 'elementrify'), 'icon' => 'eicon-h-align-center'],
                    'right'  => ['title' => __('Right', 'elementrify'), 'icon' => 'eicon-h-align-right'],
                ],
                'default'      => 'center',
                'prefix_class' => 'elementrify-divider-wrapper--align-',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_icon',
            [
                'label'     => __('Icon', 'elementrify'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => ['variant' => 'line_icon'],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label'      => __('Icon size', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => ['px' => ['min' => 8, 'max' => 64], 'em' => ['min' => 0.5, 'max' => 4]],
                'default'    => ['size' => 16, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-divider__icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementrify-divider__icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementrify-divider__icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'     => __('Icon color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-divider__icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'icon_background',
                'types'    => ['classic', 'gradient'],
                'exclude'  => ['image'],
                'selector' => '{{WRAPPER}} .elementrify-divider__icon',
                'fields_options' => [
                    'background' => ['default' => 'gradient'],
                    'color'      => ['default' => '#5E42DA'],
                    'color_b'    => ['default' => '#A542DA'],
                    'gradient_type' => ['default' => 'linear'],
                    'gradient_angle' => ['default' => ['unit' => 'deg', 'size' => 90]],
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_padding',
            [
                'label'      => __('Padding', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => ['px' => ['min' => 0, 'max' => 40], 'em' => ['min' => 0, 'max' => 2.5]],
                'default'    => ['size' => 12, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-divider__icon' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_border_radius',
            [
                'label'      => __('Border radius', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range'      => ['px' => ['max' => 200], '%' => ['max' => 50], 'em' => ['max' => 10]],
                'default'    => ['size' => 50, 'unit' => '%'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-divider__icon' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $template_path = ELEMENTRIFY_DIR . 'templates/divider/divider.php';
        if (is_readable($template_path)) {
            include $template_path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        var variant = settings.variant || 'line';
        var wrapperClass = 'elementrify-divider-wrapper';
        if (variant === 'line_icon') wrapperClass += ' elementrify-divider-wrapper--line-icon';
        view.addRenderAttribute('wrapper', 'class', wrapperClass);
        var lineStyle = settings.style || 'solid';
        var lineColor = settings.color || '#e5e7eb';
        var thick = settings.thickness && settings.thickness.size != null ? settings.thickness.size : 1;
        var thickUnit = settings.thickness && settings.thickness.unit ? settings.thickness.unit : 'px';
        var lineStyleAttr = 'border:none;border-top-width:' + thick + thickUnit + ';border-top-style:' + lineStyle + ';border-top-color:' + lineColor + ';height:0;min-height:0;';
        view.addRenderAttribute('line_left', 'class', 'elementrify-divider__line elementrify-divider__line--left elementrify-divider__line--' + lineStyle);
        view.addRenderAttribute('line_left', 'style', lineStyleAttr);
        view.addRenderAttribute('line_right', 'class', 'elementrify-divider__line elementrify-divider__line--right elementrify-divider__line--' + lineStyle);
        view.addRenderAttribute('line_right', 'style', lineStyleAttr);
        view.addRenderAttribute('line_standalone', 'class', 'elementrify-divider__line elementrify-divider__line--standalone');
        view.addRenderAttribute('line_standalone', 'style', lineStyleAttr);
        #>
        <div {{{ view.getRenderAttributeString('wrapper') }}}>
            <# if (variant === 'line_icon') { #>
                <span {{{ view.getRenderAttributeString('line_left') }}}></span>
                <span class="elementrify-divider__icon" aria-hidden="true">
                    <# var iconData = (settings.selected_icon && settings.selected_icon.value) ? settings.selected_icon : { value: 'eicon-star', library: 'eicons' }; #>
                    {{{ elementor.helpers.renderIcon(view, iconData, {}, 'i', 'object').value }}}
                </span>
                <span {{{ view.getRenderAttributeString('line_right') }}}></span>
            <# } else { #>
                <span {{{ view.getRenderAttributeString('line_standalone') }}}></span>
            <# } #>
        </div>
        <?php
    }
}
