<?php
namespace Elementrify\Widgets\Blockquote;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class Blockquote extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-blockquote elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-blockquote';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Blockquote', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Styled quote blocks with author', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#87CEEB';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-blockquote'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-blockquote',
            ELEMENTRIFY_URL . 'assets/css/blockquote.css',
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
            'quote',
            [
                'label'   => __('Quote', 'elementrify'),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => __('The best way to predict the future is to create it. This platform has enabled us to build exactly what we envisioned.', 'elementrify'),
                'dynamic' => ['active' => true],
            ]
        );

        $this->add_control(
            'show_quote_icon',
            [
                'label'        => __('Show quote icon', 'elementrify'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'author_image',
            [
                'label'   => __('Author image', 'elementrify'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [],
            ]
        );

        $this->add_control(
            'author',
            [
                'label'   => __('Author name', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Sarah Johnson', 'elementrify'),
                'dynamic' => ['active' => true],
            ]
        );

        $this->add_control(
            'author_title',
            [
                'label'   => __('Author title / affiliation', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('CEO, TechCorp', 'elementrify'),
                'dynamic' => ['active' => true],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_box',
            [
                'label' => __('Box', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'accent_color',
            [
                'label'     => __('Accent color (icon)', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#59B1F2',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-blockquote' => 'border-left-color: {{VALUE}};',
                    '{{WRAPPER}} .elementrify-blockquote__icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'border_width',
            [
                'label'      => __('Left border width', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 12]],
                'default'    => ['size' => 3],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-blockquote' => 'border-left-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'background',
                'types'    => ['classic', 'gradient'],
                'exclude'  => ['image'],
                'selector' => '{{WRAPPER}} .elementrify-blockquote',
                'fields_options' => [
                    'background' => ['default' => 'gradient'],
                    'color'      => ['default' => '#F0F7FC'],
                    'color_b'    => ['default' => '#ffffff'],
                    'gradient_type' => ['default' => 'linear'],
                    'gradient_angle' => ['default' => ['unit' => 'deg', 'size' => 90]],
                ],
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label'      => __('Border radius', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => ['px' => ['min' => 0, 'max' => 24], 'em' => ['min' => 0, 'max' => 2]],
                'default'    => ['size' => 10, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-blockquote' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'padding',
            [
                'label'      => __('Padding', 'elementrify'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', 'rem'],
                'default'    => ['top' => 32, 'right' => 40, 'bottom' => 32, 'left' => 40, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-blockquote' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'box_shadow',
                'selector' => '{{WRAPPER}} .elementrify-blockquote',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_quote',
            [
                'label' => __('Quote text', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'quote_typography',
                'selector' => '{{WRAPPER}} .elementrify-blockquote__text',
                'fields_options' => [
                    'font_size' => ['default' => ['unit' => 'px', 'size' => 17]],
                    'line_height' => ['default' => ['unit' => 'em', 'size' => 1.6]],
                ],
            ]
        );

        $this->add_control(
            'quote_color',
            [
                'label'     => __('Color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-blockquote__text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_author',
            [
                'label' => __('Author', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'avatar_size',
            [
                'label'      => __('Avatar size', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => ['px' => ['min' => 24, 'max' => 80], 'em' => ['min' => 1, 'max' => 4]],
                'default'    => ['size' => 48, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-blockquote__avatar' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementrify-blockquote__avatar img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'author_typography',
                'label'    => __('Author name', 'elementrify'),
                'selector' => '{{WRAPPER}} .elementrify-blockquote__author-name',
                'fields_options' => [
                    'font_size' => ['default' => ['unit' => 'px', 'size' => 17]],
                    'font_weight' => ['default' => '700'],
                ],
            ]
        );

        $this->add_control(
            'author_name_color',
            [
                'label'     => __('Author name color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-blockquote__author-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'author_title_typography',
                'label'    => __('Author title', 'elementrify'),
                'selector' => '{{WRAPPER}} .elementrify-blockquote__author-title',
                'fields_options' => [
                    'font_size' => ['default' => ['unit' => 'px', 'size' => 14]],
                ],
            ]
        );

        $this->add_control(
            'author_title_color',
            [
                'label'     => __('Author title color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#777777',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-blockquote__author-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $template_path = ELEMENTRIFY_DIR . 'templates/blockquote/blockquote.php';
        if (is_readable($template_path)) {
            include $template_path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        view.addRenderAttribute('wrapper', 'class', 'elementrify-blockquote');
        view.addInlineEditingAttributes('quote', 'none');
        var hasAuthor = settings.author || settings.author_title || (settings.author_image && settings.author_image.url);
        #>
        <blockquote {{{ view.getRenderAttributeString('wrapper') }}}>
            <div class="elementrify-blockquote__body">
                <# if (settings.show_quote_icon === 'yes') { #>
                    <span class="elementrify-blockquote__icon" aria-hidden="true">&ldquo;</span>
                <# } #>
                <p class="elementrify-blockquote__text" {{{ view.getRenderAttributeString('quote') }}}>{{{ settings.quote }}}</p>
            </div>
            <# if (hasAuthor) { #>
                <footer class="elementrify-blockquote__footer">
                    <# if (settings.author_image && settings.author_image.url) { #>
                        <span class="elementrify-blockquote__avatar">
                            <img src="{{ settings.author_image.url }}" alt="{{ settings.author ? settings.author : '' }}">
                        </span>
                    <# } #>
                    <span class="elementrify-blockquote__author-info">
                        <# if (settings.author) { #><cite class="elementrify-blockquote__author-name">{{{ settings.author }}}</cite><# } #>
                        <# if (settings.author_title) { #><span class="elementrify-blockquote__author-title">{{{ settings.author_title }}}</span><# } #>
                    </span>
                </footer>
            <# } #>
        </blockquote>
        <?php
    }
}
