<?php
namespace Elementrify\Widgets\Banner;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;

defined('ABSPATH') || exit;

/**
 * Hero banner with full background options and repeater buttons.
 */
class Banner extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-banner elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-banner';
    }

    public function get_admin_category(): string
    {
        return 'marketing';
    }

    public function get_title(): string
    {
        return __('Banner', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Centered hero with background and call-to-action buttons.', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#6366f1';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-banner'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-banner',
            ELEMENTRIFY_URL . 'assets/css/banner.css',
            [],
            ELEMENTRIFY_VERSION
        );
    }

    public function register_controls(): void
    {
        $this->start_controls_section('section_layout', [
            'label' => __('Layout', 'elementrify'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_responsive_control('min_height', [
            'label'      => __('Min height', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'vh'],
            'range'      => [
                'px' => ['min' => 120, 'max' => 900],
                'vh' => ['min' => 20, 'max' => 100],
            ],
            'default'    => [
                'size' => 320,
                'unit' => 'px',
            ],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-banner' => 'min-height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('content_align', [
            'label'     => __('Vertical align', 'elementrify'),
            'type'      => Controls_Manager::CHOOSE,
            'options'   => [
                'flex-start' => [
                    'title' => __('Top', 'elementrify'),
                    'icon'  => 'eicon-v-align-top',
                ],
                'center'     => [
                    'title' => __('Middle', 'elementrify'),
                    'icon'  => 'eicon-v-align-middle',
                ],
                'flex-end'   => [
                    'title' => __('Bottom', 'elementrify'),
                    'icon'  => 'eicon-v-align-bottom',
                ],
            ],
            'default'   => 'center',
            'toggle'    => false,
            'selectors' => [
                '{{WRAPPER}} .elementrify-banner__inner' => 'justify-content: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_content', [
            'label' => __('Content', 'elementrify'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('title', [
            'label'   => __('Title', 'elementrify'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Build Amazing Websites', 'elementrify'),
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('title_tag', [
            'label'   => __('Title HTML tag', 'elementrify'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'h2',
            'options' => [
                'h1' => 'H1',
                'h2' => 'H2',
                'h3' => 'H3',
                'h4' => 'H4',
                'div' => 'div',
            ],
        ]);

        $this->add_control('subtitle', [
            'label'   => __('Subtitle', 'elementrify'),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => __('Create stunning pages with our premium Elementor widgets', 'elementrify'),
            'rows'    => 3,
            'dynamic' => ['active' => true],
        ]);

        $repeater = new Repeater();
        $repeater->add_control('button_text', [
            'label'   => __('Text', 'elementrify'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Button', 'elementrify'),
            'dynamic' => ['active' => true],
        ]);
        $repeater->add_control('button_link', [
            'label'       => __('Link', 'elementrify'),
            'type'        => Controls_Manager::URL,
            'placeholder' => __('https://example.com', 'elementrify'),
            'default'     => [
                'url'         => '#',
                'is_external' => '',
                'nofollow'    => '',
            ],
            'dynamic'     => ['active' => true],
        ]);
        $repeater->add_control('button_type', [
            'label'   => __('Button type', 'elementrify'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'solid',
            'options' => [
                'solid'    => __('Solid', 'elementrify'),
                'outlined' => __('Outlined', 'elementrify'),
            ],
        ]);

        $this->add_control('buttons', [
            'label'       => __('Buttons', 'elementrify'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                [
                    'button_text'  => __('Get Started', 'elementrify'),
                    'button_link'  => [
                        'url'         => '#',
                        'is_external' => '',
                        'nofollow'    => '',
                    ],
                    'button_type'  => 'solid',
                ],
                [
                    'button_text'  => __('Learn More', 'elementrify'),
                    'button_link'  => [
                        'url'         => '#',
                        'is_external' => '',
                        'nofollow'    => '',
                    ],
                    'button_type'  => 'outlined',
                ],
            ],
            'title_field' => '{{{ button_text }}}',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_background', [
            'label'       => __('Background', 'elementrify'),
            'tab'         => Controls_Manager::TAB_STYLE,
            'description' => __('Video: YouTube, Vimeo, or a direct file link (.mp4, .webm, .ogg). Other types use the Classic and Gradient tabs.', 'elementrify'),
        ]);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'           => 'panel_bg',
                'types'          => ['classic', 'gradient', 'video'],
                'selector'       => '{{WRAPPER}} .elementrify-banner__bg',
                'fields_options' => [
                    'background' => ['default' => 'gradient'],
                    'color'      => ['default' => '#2563eb'],
                    'color_b'    => ['default' => '#9333ea'],
                ],
            ]
        );

        $this->add_control('overlay_heading', [
            'label'     => __('Overlay', 'elementrify'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('overlay_color', [
            'label'     => __('Color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(0,0,0,0.15)',
            'alpha'     => true,
            'selectors' => [
                '{{WRAPPER}} .elementrify-banner__overlay' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('banner_padding', [
            'label'      => __('Padding', 'elementrify'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'default'    => [
                'top' => '48', 'right' => '24', 'bottom' => '48', 'left' => '24', 'unit' => 'px', 'isLinked' => false,
            ],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-banner__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_control('banner_radius', [
            'label'      => __('Border radius', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range'      => ['px' => ['min' => 0, 'max' => 48]],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-banner' => 'border-radius: {{SIZE}}{{UNIT}}; overflow: hidden;',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_title', [
            'label' => __('Title', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'title_typography',
                'selector'       => '{{WRAPPER}} .elementrify-banner__title',
                'fields_options' => [
                    'typography'  => ['default' => 'custom'],
                    'font_weight' => ['default' => '700'],
                    'font_size'   => ['default' => ['unit' => 'px', 'size' => 40]],
                ],
            ]
        );

        $this->add_control('title_color', [
            'label'     => __('Color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .elementrify-banner__title' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('title_spacing', [
            'label'      => __('Spacing below', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 48]],
            'default'    => ['size' => 12, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-banner__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_subtitle', [
            'label' => __('Subtitle', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'subtitle_typography',
                'selector'       => '{{WRAPPER}} .elementrify-banner__subtitle',
                'fields_options' => [
                    'typography'  => ['default' => 'custom'],
                    'font_weight' => ['default' => '400'],
                    'font_size'   => ['default' => ['unit' => 'px', 'size' => 18]],
                ],
            ]
        );

        $this->add_control('subtitle_color', [
            'label'     => __('Color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .elementrify-banner__subtitle' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('subtitle_spacing', [
            'label'      => __('Spacing below', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 64]],
            'default'    => ['size' => 28, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-banner__subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_buttons_row', [
            'label' => __('Buttons row', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('buttons_gap', [
            'label'      => __('Gap', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 48]],
            'default'    => ['size' => 16, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-banner__actions' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('buttons_justify', [
            'label'     => __('Horizontal align', 'elementrify'),
            'type'      => Controls_Manager::CHOOSE,
            'options'   => [
                'flex-start' => [
                    'title' => __('Left', 'elementrify'),
                    'icon'  => 'eicon-h-align-left',
                ],
                'center'     => [
                    'title' => __('Center', 'elementrify'),
                    'icon'  => 'eicon-h-align-center',
                ],
                'flex-end'   => [
                    'title' => __('Right', 'elementrify'),
                    'icon'  => 'eicon-h-align-right',
                ],
            ],
            'default'   => 'center',
            'toggle'    => false,
            'selectors' => [
                '{{WRAPPER}} .elementrify-banner__actions' => 'justify-content: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_button_solid', [
            'label' => __('Solid button', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'btn_solid_typography',
                'label'          => __('Typography', 'elementrify'),
                'selector'       => '{{WRAPPER}} .elementrify-banner__btn--solid',
                'fields_options' => [
                    'typography'  => ['default' => 'custom'],
                    'font_weight' => ['default' => '600'],
                    'font_size'   => ['default' => ['unit' => 'px', 'size' => 14]],
                ],
            ]
        );

        $this->add_control('btn_solid_text', [
            'label'     => __('Text color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1e3a8a',
            'selectors' => [
                '{{WRAPPER}} .elementrify-banner__btn--solid' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('btn_solid_bg', [
            'label'     => __('Background', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .elementrify-banner__btn--solid' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'btn_solid_border',
                'selector' => '{{WRAPPER}} .elementrify-banner__btn--solid',
            ]
        );

        $this->add_control('btn_solid_radius', [
            'label'      => __('Border radius', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 48]],
            'default'    => ['size' => 8, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-banner__btn--solid' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('btn_solid_padding', [
            'label'      => __('Padding', 'elementrify'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default'    => [
                'top' => '14', 'right' => '28', 'bottom' => '14', 'left' => '28', 'unit' => 'px', 'isLinked' => false,
            ],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-banner__btn--solid' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'btn_solid_shadow',
                'selector' => '{{WRAPPER}} .elementrify-banner__btn--solid',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('section_style_button_outlined', [
            'label' => __('Outlined button', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'btn_outlined_typography',
                'label'          => __('Typography', 'elementrify'),
                'selector'       => '{{WRAPPER}} .elementrify-banner__btn--outlined',
                'fields_options' => [
                    'typography'  => ['default' => 'custom'],
                    'font_weight' => ['default' => '600'],
                    'font_size'   => ['default' => ['unit' => 'px', 'size' => 14]],
                ],
            ]
        );

        $this->add_control('btn_outlined_text', [
            'label'     => __('Text color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .elementrify-banner__btn--outlined' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('btn_outlined_bg', [
            'label'     => __('Background', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(255,255,255,0)',
            'selectors' => [
                '{{WRAPPER}} .elementrify-banner__btn--outlined' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'btn_outlined_border',
                'selector' => '{{WRAPPER}} .elementrify-banner__btn--outlined',
                'fields_options' => [
                    'border' => ['default' => 'solid'],
                    'width'  => [
                        'default' => [
                            'top' => '2', 'right' => '2', 'bottom' => '2', 'left' => '2', 'unit' => 'px', 'isLinked' => true,
                        ],
                    ],
                    'color'  => ['default' => '#ffffff'],
                ],
            ]
        );

        $this->add_control('btn_outlined_radius', [
            'label'      => __('Border radius', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 48]],
            'default'    => ['size' => 8, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-banner__btn--outlined' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('btn_outlined_padding', [
            'label'      => __('Padding', 'elementrify'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default'    => [
                'top' => '12', 'right' => '26', 'bottom' => '12', 'left' => '26', 'unit' => 'px', 'isLinked' => false,
            ],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-banner__btn--outlined' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'btn_outlined_shadow',
                'selector' => '{{WRAPPER}} .elementrify-banner__btn--outlined',
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/banner/banner.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        var titleTag = elementor.helpers.validateHTMLTag(settings.title_tag) || 'h2';
        var buttons = settings.buttons || [];
        #>
        <div class="elementrify-banner">
            <div class="elementrify-banner__bg"></div>
            <div class="elementrify-banner__overlay"></div>
            <div class="elementrify-banner__inner">
                <# if (settings.title) { #>
                    <{{{ titleTag }}} class="elementrify-banner__title">{{{ settings.title }}}</{{{ titleTag }}}>
                <# } #>
                <# if (settings.subtitle) { #>
                    <p class="elementrify-banner__subtitle">{{{ settings.subtitle }}}</p>
                <# } #>
                <# if (buttons.length) { #>
                    <div class="elementrify-banner__actions">
                    <# _.each(buttons, function(btn) {
                        var href = btn.button_link && btn.button_link.url ? btn.button_link.url : '#';
                        var type = btn.button_type || 'solid';
                        var cls = 'elementrify-banner__btn elementrify-banner__btn--' + type;
                        var attrs = '';
                        if (btn.button_link && btn.button_link.is_external) { attrs += ' target="_blank"'; }
                        if (btn.button_link && btn.button_link.nofollow) { attrs += ' rel="nofollow"'; }
                    #>
                        <a href="{{ href }}" class="{{ cls }}"<# print(attrs); #>>{{{ btn.button_text }}}</a>
                    <# }); #>
                    </div>
                <# } #>
            </div>
        </div>
        <?php
    }
}
