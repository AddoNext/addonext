<?php
namespace Addonext\Widgets\CallToAction;

use Addonext\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;

defined('ABSPATH') || exit;

class CallToAction extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-call-to-action addonext-icon';
    }

    public function get_name(): string
    {
        return 'addonext-call-to-action';
    }

    public function get_admin_category(): string
    {
        return 'marketing';
    }

    public function get_title(): string
    {
        return __('Call to Action', 'addonext');
    }

    public function get_description(): string
    {
        return __('Conversion focused CTAs', 'addonext');
    }

    public function get_brand_color(): string
    {
        return '#2563eb';
    }

    public function get_style_depends(): array
    {
        return ['addonext-call-to-action'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'addonext-call-to-action',
            ADDONEXT_URL . 'assets/css/call-to-action.css',
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
            'title',
            [
                'label'   => __('Title', 'addonext'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Ready to Get Started?', 'addonext'),
            ]
        );

        $this->add_control(
            'description',
            [
                'label'   => __('Description', 'addonext'),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => __('Join thousands of satisfied customers today', 'addonext'),
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label'   => __('Button text', 'addonext'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Start Free Trial', 'addonext'),
            ]
        );

        $this->add_control(
            'button_url',
            [
                'label'       => __('Button URL', 'addonext'),
                'type'        => Controls_Manager::URL,
                'placeholder' => __('https://example.com', 'addonext'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_background',
            [
                'label' => __('Background', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'           => 'background',
                'types'          => ['classic', 'gradient'],
                'selector'       => '{{WRAPPER}} .addonext-cta',
                'fields_options' => [
                    'background' => ['default' => 'gradient'],
                    'color'      => ['default' => '#FF8300'],
                    'color_b'    => ['default' => '#FF3C00'],
                ],
            ]
        );

        $this->add_control(
            'overlay_heading',
            [
                'label'     => __('Overlay', 'addonext'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'overlay_color',
            [
                'label'     => __('Overlay color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(0,0,0,0)',
                'alpha'     => true,
                'selectors' => [
                    '{{WRAPPER}} .addonext-cta::before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'section_border_radius',
            [
                'label'      => __('Border radius', 'addonext'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range'      => ['px' => ['min' => 0, 'max' => 100]],
                'default'    => ['size' => 0],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-cta' => 'border-radius: {{SIZE}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->add_control(
            'padding',
            [
                'label'      => __('Padding', 'addonext'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default'    => [
                    'top'      => '56px',
                    'right'    => '32px',
                    'bottom'   => '56px',
                    'left'     => '32px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-cta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_title',
            [
                'label' => __('Title', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'title_typography',
                'selector'       => '{{WRAPPER}} .addonext-cta__title',
                'fields_options' => [
                    'typography'  => ['default' => 'custom'],
                    'font_weight' => ['default' => '700'],
                    'font_size'   => ['default' => ['unit' => 'px', 'size' => 32]],
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => __('Color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .addonext-cta__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_spacing',
            [
                'label'     => __('Spacing below', 'addonext'),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'     => ['px' => ['min' => 0, 'max' => 60]],
                'default'   => ['size' => 12, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .addonext-cta__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_description',
            [
                'label' => __('Description', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'description_typography',
                'selector'       => '{{WRAPPER}} .addonext-cta__description',
                'fields_options' => [
                    'typography'  => ['default' => 'custom'],
                    'font_weight' => ['default' => '400'],
                    'font_size'   => ['default' => ['unit' => 'px', 'size' => 16]],
                ],
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label'     => __('Color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .addonext-cta__description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'description_spacing',
            [
                'label'     => __('Spacing below', 'addonext'),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'     => ['px' => ['min' => 0, 'max' => 80]],
                'default'   => ['size' => 24, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .addonext-cta__description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_button',
            [
                'label' => __('Button', 'addonext'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'button_typography',
                'selector'       => '{{WRAPPER}} .addonext-cta__button',
                'fields_options' => [
                    'typography'  => ['default' => 'custom'],
                    'font_weight' => ['default' => '700'],
                    'font_size'   => ['default' => ['unit' => 'px', 'size' => 16]],
                ],
            ]
        );

        $this->add_control(
            'button_bg',
            [
                'label'     => __('Background', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .addonext-cta__button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label'     => __('Text color', 'addonext'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#FF6400',
                'selectors' => [
                    '{{WRAPPER}} .addonext-cta__button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label'      => __('Border radius', 'addonext'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 24]],
                'default'    => ['size' => 10],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-cta__button' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_padding',
            [
                'label'      => __('Padding', 'addonext'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default'    => [
                    'top'      => '14px',
                    'right'    => '28px',
                    'bottom'   => '14px',
                    'left'     => '28px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .addonext-cta__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'           => 'button_box_shadow',
                'selector'       => '{{WRAPPER}} .addonext-cta__button',
                'fields_options' => [
                    'box_shadow_type' => ['default' => 'yes'],
                    'box_shadow'     => [
                        'default' => [
                            'horizontal' => 0,
                            'vertical'   => 4,
                            'blur'       => 12,
                            'spread'     => 0,
                            'color'      => 'rgba(0,0,0,0.08)',
                        ],
                    ],
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ADDONEXT_DIR . 'templates/call-to-action/call-to-action.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        var buttonUrl = settings.button_url && settings.button_url.url ? settings.button_url.url : '#';
        var buttonAttrs = '';
        if (settings.button_url && settings.button_url.is_external) {
            buttonAttrs += ' target="_blank"';
        }
        if (settings.button_url && settings.button_url.nofollow) {
            buttonAttrs += ' rel="nofollow"';
        }
        #>
        <div class="addonext-cta">
            <div class="addonext-cta__inner">
                <# if (settings.title) { #>
                    <h3 class="addonext-cta__title">{{{ settings.title }}}</h3>
                <# } #>
                <# if (settings.description) { #>
                    <p class="addonext-cta__description">{{{ settings.description }}}</p>
                <# } #>
                <# if (settings.button_text) { #>
                    <a href="{{ buttonUrl }}"{{ buttonAttrs }} class="addonext-cta__button">{{{ settings.button_text }}}</a>
                <# } #>
            </div>
        </div>
        <?php
    }
}
