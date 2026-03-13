<?php
namespace Elementrify\Widgets\CallToAction;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;

defined('ABSPATH') || exit;

class CallToAction extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-call-to-action elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-call-to-action';
    }

    public function get_admin_category(): string
    {
        return 'marketing';
    }

    public function get_title(): string
    {
        return __('Call to Action', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Conversion focused CTAs', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#2563eb';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-call-to-action'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-call-to-action',
            ELEMENTRIFY_URL . 'assets/css/call-to-action.css',
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
            'title',
            [
                'label'   => __('Title', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Ready to Get Started?', 'elementrify'),
            ]
        );

        $this->add_control(
            'description',
            [
                'label'   => __('Description', 'elementrify'),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => __('Join thousands of satisfied customers today', 'elementrify'),
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label'   => __('Button text', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Start Free Trial', 'elementrify'),
            ]
        );

        $this->add_control(
            'button_url',
            [
                'label'       => __('Button URL', 'elementrify'),
                'type'        => Controls_Manager::URL,
                'placeholder' => __('https://example.com', 'elementrify'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_background',
            [
                'label' => __('Background', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'           => 'background',
                'types'          => ['classic', 'gradient'],
                'selector'       => '{{WRAPPER}} .elementrify-cta',
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
                'label'     => __('Overlay', 'elementrify'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'overlay_color',
            [
                'label'     => __('Overlay color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(0,0,0,0)',
                'alpha'     => true,
                'selectors' => [
                    '{{WRAPPER}} .elementrify-cta::before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'section_border_radius',
            [
                'label'      => __('Border radius', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range'      => ['px' => ['min' => 0, 'max' => 100]],
                'default'    => ['size' => 0],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-cta' => 'border-radius: {{SIZE}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->add_control(
            'padding',
            [
                'label'      => __('Padding', 'elementrify'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default'    => [
                    'top'      => '56px',
                    'right'    => '32px',
                    'bottom'   => '56px',
                    'left'     => '32px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-cta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_title',
            [
                'label' => __('Title', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'title_typography',
                'selector'       => '{{WRAPPER}} .elementrify-cta__title',
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
                'label'     => __('Color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-cta__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_spacing',
            [
                'label'     => __('Spacing below', 'elementrify'),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'     => ['px' => ['min' => 0, 'max' => 60]],
                'default'   => ['size' => 12, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .elementrify-cta__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_description',
            [
                'label' => __('Description', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'description_typography',
                'selector'       => '{{WRAPPER}} .elementrify-cta__description',
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
                'label'     => __('Color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-cta__description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'description_spacing',
            [
                'label'     => __('Spacing below', 'elementrify'),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'     => ['px' => ['min' => 0, 'max' => 80]],
                'default'   => ['size' => 24, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .elementrify-cta__description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_button',
            [
                'label' => __('Button', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'button_typography',
                'selector'       => '{{WRAPPER}} .elementrify-cta__button',
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
                'label'     => __('Background', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-cta__button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label'     => __('Text color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#FF6400',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-cta__button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label'      => __('Border radius', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 24]],
                'default'    => ['size' => 10],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-cta__button' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_padding',
            [
                'label'      => __('Padding', 'elementrify'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default'    => [
                    'top'      => '14px',
                    'right'    => '28px',
                    'bottom'   => '14px',
                    'left'     => '28px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-cta__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'           => 'button_box_shadow',
                'selector'       => '{{WRAPPER}} .elementrify-cta__button',
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
        $path = ELEMENTRIFY_DIR . 'templates/call-to-action/call-to-action.php';
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
        <div class="elementrify-cta">
            <div class="elementrify-cta__inner">
                <# if (settings.title) { #>
                    <h3 class="elementrify-cta__title">{{{ settings.title }}}</h3>
                <# } #>
                <# if (settings.description) { #>
                    <p class="elementrify-cta__description">{{{ settings.description }}}</p>
                <# } #>
                <# if (settings.button_text) { #>
                    <a href="{{ buttonUrl }}"{{ buttonAttrs }} class="elementrify-cta__button">{{{ settings.button_text }}}</a>
                <# } #>
            </div>
        </div>
        <?php
    }
}
