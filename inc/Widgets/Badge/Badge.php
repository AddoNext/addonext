<?php
namespace Veloria\Widgets\Badge;

use Veloria\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class Badge extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-tags veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-badge';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Badge', 'veloria');
    }

    public function get_description(): string
    {
        return __('Labels and tags', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#6366f1';
    }

    public function get_style_depends(): array
    {
        return ['veloria-badge'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'veloria-badge',
            VELORIA_URL . 'assets/css/badge.css',
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
            'text',
            [
                'label'   => __('Text', 'veloria'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Best Seller', 'veloria'),
                'dynamic' => ['active' => true],
            ]
        );

        $this->add_control(
            'selected_icon',
            [
                'label'   => __('Icon', 'veloria'),
                'type'    => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'label_block' => false,
                'default' => [
                    'value'   => 'fas fa-crown',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
            'html_tag',
            [
                'label'   => __('HTML tag', 'veloria'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'span',
                'options' => [
                    'span' => 'span',
                    'div'  => 'div',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_spacing',
            [
                'label'      => __('Space between icon and text', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => ['px' => ['min' => 0, 'max' => 48], 'em' => ['min' => 0, 'max' => 3]],
                'default'    => ['size' => 8, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-badge--has-icon .veloria-badge__text' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Style', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label'     => __('Text & icon color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .veloria-badge' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .veloria-badge__icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography',
                'selector' => '{{WRAPPER}} .veloria-badge',
                'fields_options' => [
                    'typography'  => ['default' => 'yes'],
                    'font_size'   => ['default' => ['size' => 14, 'unit' => 'px']],
                    'font_weight' => ['default' => '600'],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'background',
                'types'    => ['classic', 'gradient'],
                'exclude'  => ['image'],
                'selector' => '{{WRAPPER}} .veloria-badge',
                'fields_options' => [
                    'background' => ['default' => 'gradient'],
                    'color'      => ['default' => '#F37548'],
                    'color_b'    => ['default' => '#F14E6E'],
                    'gradient_type' => ['default' => 'linear'],
                    'gradient_angle' => ['default' => ['unit' => 'deg', 'size' => 90]],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'border',
                'selector' => '{{WRAPPER}} .veloria-badge',
            ]
        );

        $this->add_responsive_control(
            'border_radius',
            [
                'label'      => __('Border radius', 'veloria'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default'    => [
                    'top' => '999', 'right' => '999', 'bottom' => '999', 'left' => '999',
                    'unit' => 'px', 'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'box_shadow',
                'selector' => '{{WRAPPER}} .veloria-badge',
                'fields_options' => [
                    'box_shadow_type' => ['default' => 'yes'],
                    'box_shadow' => [
                        'default' => [
                            'horizontal' => 0,
                            'vertical'   => 4,
                            'blur'       => 6,
                            'spread'     => 0,
                            'color'      => 'rgba(0,0,0,0.1)',
                        ],
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'padding',
            [
                'label'      => __('Padding', 'veloria'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem'],
                'default'    => [
                    'top' => '8', 'right' => '12', 'bottom' => '8', 'left' => '12',
                    'unit' => 'px', 'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .veloria-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'margin',
            [
                'label'      => __('Margin', 'veloria'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-badge' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $template_path = VELORIA_DIR . 'templates/badge/badge.php';
        if (is_readable($template_path)) {
            include $template_path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        var tag = elementor.helpers.validateHTMLTag(settings.html_tag) || 'span';
        view.addRenderAttribute('wrap', 'class', 'veloria-badge__wrap');
        view.addRenderAttribute('badge', 'class', 'veloria-badge veloria-badge--has-icon');
        view.addRenderAttribute('text', 'class', 'veloria-badge__text');
        view.addInlineEditingAttributes('text', 'none');
        var iconData = (settings.selected_icon && settings.selected_icon.value) ? settings.selected_icon : { value: 'fas fa-crown', library: 'fa-solid' };
        var iconHtml = elementor.helpers.renderIcon(view, iconData, { 'aria-hidden': 'true', 'width': 16 }, 'i', 'object').value;
        #>
        <div {{{ view.getRenderAttributeString('wrap') }}}>
        <{{{ tag }}} {{{ view.getRenderAttributeString('badge') }}}>
            <span class="veloria-badge__icon" aria-hidden="true">{{{ iconHtml }}}</span>
            <span {{{ view.getRenderAttributeString('text') }}}>{{{ settings.text }}}</span>
        </{{{ tag }}}>
        </div>
        <?php
    }
}
