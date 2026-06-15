<?php
namespace Veloria\Widgets\Divider;

use Veloria\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;

defined('ABSPATH') or exit;

class Divider extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-divider veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-divider';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Divider', 'veloria');
    }

    public function get_description(): string
    {
        return __('Section dividers & separators', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#6b7280';
    }

    public function get_style_depends(): array
    {
        return ['veloria-divider'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'veloria-divider',
            VELORIA_URL . 'assets/css/divider.css',
            [],
            VELORIA_VERSION
        );
    }

    public function register_controls(): void
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'veloria'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'variant',
            [
                'label'   => __('Variant', 'veloria'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'line',
                'options' => [
                    'line'      => __('Simple line', 'veloria'),
                    'line_icon' => __('Line with icon', 'veloria'),
                ],
            ]
        );

        $this->add_control(
            'style',
            [
                'label'   => __('Line style', 'veloria'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'solid',
                'options' => [
                    'solid'  => __('Solid', 'veloria'),
                    'double' => __('Double', 'veloria'),
                    'dotted' => __('Dotted', 'veloria'),
                    'dashed' => __('Dashed', 'veloria'),
                ],
            ]
        );

        $this->add_control(
            'selected_icon',
            [
                'label'     => __('Icon', 'veloria'),
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
                'label' => __('Line', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'color',
            [
                'label'     => __('Color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#e5e7eb',
                'selectors' => [
                    '{{WRAPPER}} .veloria-divider__line' => 'border-top-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'thickness',
            [
                'label'      => __('Thickness', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => ['px' => ['max' => 20], 'em' => ['max' => 2]],
                'default'    => ['size' => 1, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-divider__line' => 'border-top-width: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label'   => __('Alignment', 'veloria'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'left'   => ['title' => __('Left', 'veloria'), 'icon' => 'eicon-h-align-left'],
                    'center' => ['title' => __('Center', 'veloria'), 'icon' => 'eicon-h-align-center'],
                    'right'  => ['title' => __('Right', 'veloria'), 'icon' => 'eicon-h-align-right'],
                ],
                'default'      => 'center',
                'prefix_class' => 'veloria-divider-wrapper--align-',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_icon',
            [
                'label'     => __('Icon', 'veloria'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => ['variant' => 'line_icon'],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label'      => __('Icon size', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => ['px' => ['min' => 8, 'max' => 64], 'em' => ['min' => 0.5, 'max' => 4]],
                'default'    => ['size' => 16, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-divider__icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .veloria-divider__icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .veloria-divider__icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'     => __('Icon color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .veloria-divider__icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'icon_background',
                'types'    => ['classic', 'gradient'],
                'exclude'  => ['image'],
                'selector' => '{{WRAPPER}} .veloria-divider__icon',
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
                'label'      => __('Padding', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => ['px' => ['min' => 0, 'max' => 40], 'em' => ['min' => 0, 'max' => 2.5]],
                'default'    => ['size' => 12, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-divider__icon' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_border_radius',
            [
                'label'      => __('Border radius', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range'      => ['px' => ['max' => 200], '%' => ['max' => 50], 'em' => ['max' => 10]],
                'default'    => ['size' => 50, 'unit' => '%'],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-divider__icon' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $template_path = VELORIA_DIR . 'templates/divider/divider.php';
        if (is_readable($template_path)) {
            include $template_path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        var variant = settings.variant || 'line';
        var wrapperClass = 'veloria-divider-wrapper';
        if (variant === 'line_icon') wrapperClass += ' veloria-divider-wrapper--line-icon';
        view.addRenderAttribute('wrapper', 'class', wrapperClass);
        var lineStyle = settings.style || 'solid';
        var lineColor = settings.color || '#e5e7eb';
        var thick = settings.thickness && settings.thickness.size != null ? settings.thickness.size : 1;
        var thickUnit = settings.thickness && settings.thickness.unit ? settings.thickness.unit : 'px';
        var lineStyleAttr = 'border:none;border-top-width:' + thick + thickUnit + ';border-top-style:' + lineStyle + ';border-top-color:' + lineColor + ';height:0;min-height:0;';
        view.addRenderAttribute('line_left', 'class', 'veloria-divider__line veloria-divider__line--left veloria-divider__line--' + lineStyle);
        view.addRenderAttribute('line_left', 'style', lineStyleAttr);
        view.addRenderAttribute('line_right', 'class', 'veloria-divider__line veloria-divider__line--right veloria-divider__line--' + lineStyle);
        view.addRenderAttribute('line_right', 'style', lineStyleAttr);
        view.addRenderAttribute('line_standalone', 'class', 'veloria-divider__line veloria-divider__line--standalone');
        view.addRenderAttribute('line_standalone', 'style', lineStyleAttr);
        #>
        <div {{{ view.getRenderAttributeString('wrapper') }}}>
            <# if (variant === 'line_icon') { #>
                <span {{{ view.getRenderAttributeString('line_left') }}}></span>
                <span class="veloria-divider__icon" aria-hidden="true">
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
