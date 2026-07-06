<?php
namespace Addonext\Widgets\Badge;

use Addonext\Base\WidgetBase;
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
        return 'eicon-tags addonext-icon';
    }

    public function get_name(): string
    {
        return 'addonext-badge';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Badge', 'addonext');
    }

    public function get_description(): string
    {
        return __('Labels and tags', 'addonext');
    }

    public function get_brand_color(): string
    {
        return '#6366f1';
    }

    public function get_style_depends(): array
    {
        return ['addonext-badge'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'addonext-badge',
            ADDONEXT_URL . 'assets/css/badge.css',
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
            'text',
            [
                'label'   => __('Text', 'addonext'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Best Seller', 'addonext'),
                'dynamic' => ['active' => true],
            ]
        );

        $this->add_control(
            'selected_icon',
            [
                'label'   => __('Icon', 'addonext'),
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
                'label'   => __('HTML tag', 'addonext'),
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
                'label'      => __('Space between icon and text', 'addonext'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => ['px' => ['min' => 0, 'max' => 48], 'em' => ['min' => 0, 'max' => 3]],
                'default'    => ['size' => 8, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-badge--has-icon .addonext-badge__text' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Style', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label'     => __('Text & icon color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .addonext-badge' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .addonext-badge__icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography',
                'selector' => '{{WRAPPER}} .addonext-badge',
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
                'selector' => '{{WRAPPER}} .addonext-badge',
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
                'selector' => '{{WRAPPER}} .addonext-badge',
            ]
        );

        $this->add_responsive_control(
            'border_radius',
            [
                'label'      => __('Border radius', 'addonext'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default'    => [
                    'top' => '999', 'right' => '999', 'bottom' => '999', 'left' => '999',
                    'unit' => 'px', 'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'box_shadow',
                'selector' => '{{WRAPPER}} .addonext-badge',
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
                'label'      => __('Padding', 'addonext'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem'],
                'default'    => [
                    'top' => '8', 'right' => '12', 'bottom' => '8', 'left' => '12',
                    'unit' => 'px', 'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .addonext-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'margin',
            [
                'label'      => __('Margin', 'addonext'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-badge' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $template_path = ADDONEXT_DIR . 'templates/badge/badge.php';
        if (is_readable($template_path)) {
            include $template_path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        var tag = elementor.helpers.validateHTMLTag(settings.html_tag) || 'span';
        view.addRenderAttribute('wrap', 'class', 'addonext-badge__wrap');
        view.addRenderAttribute('badge', 'class', 'addonext-badge addonext-badge--has-icon');
        view.addRenderAttribute('text', 'class', 'addonext-badge__text');
        view.addInlineEditingAttributes('text', 'none');
        var iconData = (settings.selected_icon && settings.selected_icon.value) ? settings.selected_icon : { value: 'fas fa-crown', library: 'fa-solid' };
        var iconHtml = elementor.helpers.renderIcon(view, iconData, { 'aria-hidden': 'true', 'width': 16 }, 'i', 'object').value;
        #>
        <div {{{ view.getRenderAttributeString('wrap') }}}>
        <{{{ tag }}} {{{ view.getRenderAttributeString('badge') }}}>
            <span class="addonext-badge__icon" aria-hidden="true">{{{ iconHtml }}}</span>
            <span {{{ view.getRenderAttributeString('text') }}}>{{{ settings.text }}}</span>
        </{{{ tag }}}>
        </div>
        <?php
    }
}
