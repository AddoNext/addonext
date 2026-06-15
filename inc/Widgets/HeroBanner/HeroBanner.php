<?php
namespace Veloria\Widgets\HeroBanner;

use Veloria\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
defined('ABSPATH') || exit;

/**
 * Left-aligned hero with tagline, title, subtitle, accent line, and CTA buttons.
 */
class HeroBanner extends WidgetBase
{
    /**
     * Splits a title into static text and the last two words (for typing + gradient).
     *
     * @return array{0: string, 1: string} Static prefix, typing suffix.
     */
    public static function split_title_last_two_words(string $title): array
    {
        $t = trim(preg_replace('/\s+/u', ' ', $title));
        if ($t === '') {
            return ['', ''];
        }
        $words = preg_split('/\s+/u', $t, -1, PREG_SPLIT_NO_EMPTY);
        if (!is_array($words)) {
            return ['', $t];
        }
        $n = count($words);
        if ($n <= 2) {
            return ['', $t];
        }

        return [
            implode(' ', array_slice($words, 0, $n - 2)),
            $words[$n - 2] . ' ' . $words[$n - 1],
        ];
    }

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_assets_in_preview']);
    }

    public function enqueue_assets_in_preview(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        $this->enqueue_assets();
        wp_enqueue_style('veloria-hero-banner');
        wp_enqueue_script('veloria-hero-banner');
                }
        }
}

    public function get_icon(): string
    {
        return 'eicon-slider-push veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-hero-banner';
    }

    public function get_admin_category(): string
    {
        return 'marketing';
    }

    public function get_title(): string
    {
        return __('Hero Banner', 'veloria');
    }

    public function get_description(): string
    {
        return __('Hero section with tagline, headline, background media, and buttons.', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#5E42DA';
    }

    
    public function get_required_plan(): string
    {
        return 'pro';
    }

    public function get_style_depends(): array
    {
        return ['veloria-hero-banner'];
    }

    public function get_script_depends(): array
    {
        return ['veloria-hero-banner'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'veloria-hero-banner',
            VELORIA_URL . 'assets/css/hero-banner.css',
            [],
            VELORIA_VERSION
        );
        wp_register_script(
            'veloria-hero-banner',
            VELORIA_URL . 'assets/js/hero-banner.js',
            ['jquery', 'elementor-frontend'],
            VELORIA_VERSION,
            true
        );
    }

    public function register_controls(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        $this->start_controls_section('section_layout', [
            'label' => __('Layout', 'veloria'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_responsive_control('min_height', [
            'label'      => __('Min height', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'vh'],
            'range'      => [
                'px' => ['min' => 200, 'max' => 900],
                'vh' => ['min' => 30, 'max' => 100],
            ],
            'default'    => ['size' => 100, 'unit' => 'vh'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-hero-banner' => 'min-height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('content_max_width', [
            'label'      => __('Content max width', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range'      => ['px' => ['min' => 280, 'max' => 900]],
            'default'    => ['size' => 520, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-hero-banner__content' => 'max-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('content_align_vertical', [
            'label'     => __('Vertical align', 'veloria'),
            'type'      => Controls_Manager::CHOOSE,
            'options'   => [
                'flex-start' => ['title' => __('Top', 'veloria'), 'icon' => 'eicon-v-align-top'],
                'center'     => ['title' => __('Middle', 'veloria'), 'icon' => 'eicon-v-align-middle'],
                'flex-end'   => ['title' => __('Bottom', 'veloria'), 'icon' => 'eicon-v-align-bottom'],
            ],
            'default'   => 'center',
            'toggle'    => false,
            'selectors' => [
                '{{WRAPPER}} .veloria-hero-banner__inner' => 'justify-content: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_content', [
            'label' => __('Content', 'veloria'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $site_title = get_bloginfo('name', 'display');
        if (!is_string($site_title) || trim($site_title) === '') {
            $site_title = 'Veloria';
        }

        $this->add_control('tagline_prefix', [
            'label'       => __('Tagline (first part)', 'veloria'),
            'type'        => Controls_Manager::TEXT,
            'default'     => __('Built for', 'veloria'),
            'label_block' => true,
            'dynamic'     => ['active' => true],
        ]);

        $this->add_control('tagline_accent', [
            'label'       => __('Tagline (accent)', 'veloria'),
            'type'        => Controls_Manager::TEXT,
            'default'     => $site_title,
            'label_block' => true,
            'description' => __('Uses your WordPress site title by default. Shown with the brand gradient.', 'veloria'),
            'dynamic'     => ['active' => true],
        ]);

        $this->add_control('title', [
            'label'       => __('Title', 'veloria'),
            'type'        => Controls_Manager::TEXT,
            'default'     => __('Create pages that convert faster', 'veloria'),
            'description' => __('The last two words use a typing animation and gradient.', 'veloria'),
            'dynamic'     => ['active' => true],
        ]);

        $this->add_control('title_typing_speed', [
            'label'   => __('Typing speed (ms)', 'veloria'),
            'type'    => Controls_Manager::NUMBER,
            'min'     => 20,
            'max'     => 200,
            'step'    => 5,
            'default' => 55,
        ]);

        $this->add_control('title_typing_loop', [
            'label'        => __('Loop typing animation', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('title_tag', [
            'label'   => __('Title HTML tag', 'veloria'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'h1',
            'options' => [
                'h1' => 'H1',
                'h2' => 'H2',
                'h3' => 'H3',
                'div' => 'div',
            ],
        ]);

        $this->add_control('subtitle', [
            'label'   => __('Subtitle', 'veloria'),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => __('Premium Elementor widgets and patterns to ship polished layouts without slowing down your team.', 'veloria'),
            'rows'    => 4,
            'dynamic' => ['active' => true],
        ]);

        $this->add_control('show_accent_line', [
            'label'        => __('Accent line beside subtitle', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $repeater = new Repeater();
        $repeater->add_control('button_text', [
            'label'   => __('Text', 'veloria'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Browse widgets', 'veloria'),
            'dynamic' => ['active' => true],
        ]);
        $repeater->add_control('button_link', [
            'label'   => __('Link', 'veloria'),
            'type'    => Controls_Manager::URL,
            'default' => [
                'url' => '#', 'is_external' => '', 'nofollow' => '',
            ],
            'dynamic' => ['active' => true],
        ]);
        $repeater->add_control('button_type', [
            'label'   => __('Type', 'veloria'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'solid',
            'options' => [
                'solid'    => __('Solid', 'veloria'),
                'outlined' => __('Outlined', 'veloria'),
            ],
        ]);
        $repeater->add_control('selected_icon', [
            'label'            => __('Icon', 'veloria'),
            'type'             => Controls_Manager::ICONS,
            'fa4compatibility' => 'icon',
            'default'          => [
                'value'   => 'fas fa-arrow-up-right-from-square',
                'library' => 'fa-solid',
            ],
        ]);
        $repeater->add_control('show_icon', [
            'label'        => __('Show icon', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('buttons', [
            'label'       => __('Buttons', 'veloria'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                [
                    'button_text'  => __('Browse widgets', 'veloria'),
                    'button_link'  => ['url' => '#', 'is_external' => '', 'nofollow' => ''],
                    'button_type'  => 'solid',
                    'show_icon'    => 'yes',
                ],
            ],
            'title_field' => '{{{ button_text }}}',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_background', [
            'label'       => __('Background', 'veloria'),
            'tab'         => Controls_Manager::TAB_STYLE,
            'description' => __('Video: YouTube, Vimeo, or direct .mp4 / .webm / .ogg link.', 'veloria'),
        ]);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'           => 'hb_panel_bg',
                'types'          => ['classic', 'gradient', 'video'],
                'selector'       => '{{WRAPPER}} .veloria-hero-banner__bg',
                'fields_options' => [
                    'background' => ['default' => 'classic'],
                    'color'        => ['default' => '#1e293b'],
                    'image'        => [
                        'default' => [
                            'url' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1920&q=80',
                            'id'  => '',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control('overlay_color', [
            'label'     => __('Overlay', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(15, 23, 42, 0.55)',
            'alpha'     => true,
            'selectors' => [
                '{{WRAPPER}} .veloria-hero-banner__overlay' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('inner_padding', [
            'label'      => __('Inner padding', 'veloria'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'default'    => [
                'top' => '48', 'right' => '40', 'bottom' => '48', 'left' => '40', 'unit' => 'px', 'isLinked' => false,
            ],
            'selectors'  => [
                '{{WRAPPER}} .veloria-hero-banner__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_tagline', [
            'label' => __('Tagline', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'tagline_prefix_typography',
                'label'          => __('First part', 'veloria'),
                'selector'       => '{{WRAPPER}} .veloria-hero-banner__tagline-prefix',
                'fields_options' => [
                    'typography'  => ['default' => 'custom'],
                    'font_weight' => ['default' => '600'],
                    'font_size'   => ['default' => ['unit' => 'px', 'size' => 13]],
                    'text_transform' => ['default' => 'uppercase'],
                    'letter_spacing' => ['default' => ['size' => 0.12, 'unit' => 'em']],
                ],
            ]
        );

        $this->add_control('tagline_prefix_color', [
            'label'     => __('First part color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .veloria-hero-banner__tagline-prefix' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'tagline_accent_typography',
                'label'          => __('Accent part', 'veloria'),
                'selector'       => '{{WRAPPER}} .veloria-hero-banner__tagline-accent-text',
                'fields_options' => [
                    'typography'  => ['default' => 'custom'],
                    'font_weight' => ['default' => '700'],
                    'font_size'   => ['default' => ['unit' => 'px', 'size' => 13]],
                    'text_transform' => ['default' => 'uppercase'],
                    'letter_spacing' => ['default' => ['size' => 0.12, 'unit' => 'em']],
                ],
            ]
        );

        $this->add_control('tagline_spacing', [
            'label'      => __('Spacing below tagline', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 48]],
            'default'    => ['size' => 16, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-hero-banner__tagline' => 'margin-bottom: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_title', [
            'label' => __('Title', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'title_typography',
                'selector'       => '{{WRAPPER}} .veloria-hero-banner__title',
                'fields_options' => [
                    'typography'  => ['default' => 'custom'],
                    'font_weight' => ['default' => '700'],
                    'font_size'   => ['default' => ['unit' => 'px', 'size' => 42]],
                    'line_height' => ['default' => ['size' => 1.15, 'unit' => 'em']],
                ],
            ]
        );

        $this->add_control('title_color', [
            'label'     => __('Color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .veloria-hero-banner__title' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('title_spacing', [
            'label'      => __('Spacing below title', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default'    => ['size' => 20, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-hero-banner__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_accent_line', [
            'label'     => __('Accent line', 'veloria'),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => ['show_accent_line' => 'yes'],
        ]);

        $this->add_control('accent_line_width', [
            'label'      => __('Width', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 2, 'max' => 16]],
            'default'    => ['size' => 4, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-hero-banner__accent-line' => 'width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('accent_line_min_height', [
            'label'      => __('Min height', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'range'      => ['px' => ['min' => 24, 'max' => 200]],
            'default'    => ['size' => 72, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-hero-banner__accent-line' => 'min-height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('accent_line_radius', [
            'label'      => __('Radius', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default'    => ['size' => 2, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-hero-banner__accent-line' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'           => 'accent_line_bg',
                'types'          => ['classic', 'gradient'],
                'exclude'        => ['image'],
                'selector'       => '{{WRAPPER}} .veloria-hero-banner__accent-line',
                'fields_options' => [
                    'background' => ['default' => 'gradient'],
                    'color'      => ['default' => '#5E42DA'],
                    'color_b'    => ['default' => '#3b82f6'],
                    'gradient_type' => ['default' => 'linear'],
                    'gradient_angle' => ['default' => ['unit' => 'deg', 'size' => 180]],
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('section_style_subtitle', [
            'label' => __('Subtitle', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('subrow_gap', [
            'label'      => __('Gap beside accent line', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default'    => ['size' => 16, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-hero-banner__subrow' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'subtitle_typography',
                'selector'       => '{{WRAPPER}} .veloria-hero-banner__subtitle',
                'fields_options' => [
                    'typography'  => ['default' => 'custom'],
                    'font_weight' => ['default' => '400'],
                    'font_size'   => ['default' => ['unit' => 'px', 'size' => 16]],
                    'line_height' => ['default' => ['size' => 1.55, 'unit' => 'em']],
                ],
            ]
        );

        $this->add_control('subtitle_color', [
            'label'     => __('Color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(255,255,255,0.92)',
                'selectors' => [
                '{{WRAPPER}} .veloria-hero-banner__subtitle' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('subtitle_spacing', [
            'label'      => __('Spacing below subtitle', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default'    => ['size' => 28, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-hero-banner__subrow-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_buttons_row', [
            'label' => __('Buttons row', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('buttons_gap', [
            'label'      => __('Gap', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default'    => ['size' => 14, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-hero-banner__actions' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('icon_gap', [
            'label'      => __('Icon gap', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default'    => ['size' => 8, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-hero-banner__btn-inner' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_button_solid', [
            'label' => __('Solid button', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'btn_solid_typography',
                'selector'       => '{{WRAPPER}} .veloria-hero-banner__btn--solid',
                'fields_options' => [
                    'typography'  => ['default' => 'custom'],
                    'font_weight' => ['default' => '600'],
                    'font_size'   => ['default' => ['unit' => 'px', 'size' => 14]],
                ],
            ]
        );

        $this->add_control('btn_solid_text', [
            'label'     => __('Text & icon color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .veloria-hero-banner__btn--solid' => 'color: {{VALUE}}; fill: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'           => 'btn_solid_bg',
                'types'          => ['classic', 'gradient'],
                'exclude'        => ['image'],
                'selector'       => '{{WRAPPER}} .veloria-hero-banner__btn--solid',
                'fields_options' => [
                    'background' => ['default' => 'gradient'],
                    'color'      => ['default' => '#5E42DA'],
                    'color_b'    => ['default' => '#A542DA'],
                    'gradient_type' => ['default' => 'linear'],
                    'gradient_angle' => ['default' => ['unit' => 'deg', 'size' => 90]],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'btn_solid_border',
                'selector' => '{{WRAPPER}} .veloria-hero-banner__btn--solid',
            ]
        );

        $this->add_control('btn_solid_radius', [
            'label'      => __('Border radius', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default'    => ['size' => 6, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-hero-banner__btn--solid' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('btn_solid_padding', [
            'label'      => __('Padding', 'veloria'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default'    => [
                'top' => '14', 'right' => '22', 'bottom' => '14', 'left' => '22', 'unit' => 'px', 'isLinked' => false,
            ],
            'selectors'  => [
                '{{WRAPPER}} .veloria-hero-banner__btn--solid' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'btn_solid_shadow',
                'selector' => '{{WRAPPER}} .veloria-hero-banner__btn--solid',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('section_style_button_outlined', [
            'label' => __('Outlined button', 'veloria'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'btn_outlined_typography',
                'selector'       => '{{WRAPPER}} .veloria-hero-banner__btn--outlined',
                'fields_options' => [
                    'typography'  => ['default' => 'custom'],
                    'font_weight' => ['default' => '600'],
                    'font_size'   => ['default' => ['unit' => 'px', 'size' => 14]],
                ],
            ]
        );

        $this->add_control('btn_outlined_text', [
            'label'     => __('Text & icon color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .veloria-hero-banner__btn--outlined' => 'color: {{VALUE}}; fill: {{VALUE}};',
            ],
        ]);

        $this->add_control('btn_outlined_bg', [
            'label'     => __('Background', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(255,255,255,0)',
            'selectors' => [
                '{{WRAPPER}} .veloria-hero-banner__btn--outlined' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'btn_outlined_border',
                'selector' => '{{WRAPPER}} .veloria-hero-banner__btn--outlined',
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
            'label'      => __('Border radius', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default'    => ['size' => 6, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-hero-banner__btn--outlined' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('btn_outlined_padding', [
            'label'      => __('Padding', 'veloria'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default'    => [
                'top' => '12', 'right' => '20', 'bottom' => '12', 'left' => '20', 'unit' => 'px', 'isLinked' => false,
            ],
            'selectors'  => [
                '{{WRAPPER}} .veloria-hero-banner__btn--outlined' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'btn_outlined_shadow',
                'selector' => '{{WRAPPER}} .veloria-hero-banner__btn--outlined',
            ]
        );

        $this->end_controls_section();
                }
        }
}

    public function render(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        $path = VELORIA_DIR . 'templates/hero-banner/hero-banner.php';
        if (is_readable($path)) {
            include $path;
        }
                }
        }
}

    protected function content_template(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        ?>
        <#
        var titleTag = elementor.helpers.validateHTMLTag(settings.title_tag) || 'h1';
        var buttons = settings.buttons || [];
        var showLine = settings.show_accent_line === 'yes';
        var typingMs = settings.title_typing_speed ? parseInt(settings.title_typing_speed, 10) : 55;
        if (isNaN(typingMs) || typingMs < 20) { typingMs = 20; }
        if (typingMs > 200) { typingMs = 200; }
        var typingLoop = settings.title_typing_loop === 'yes' ? '1' : '0';
        function veloriaHeroSplitTitle(t) {
            t = ((t || '') + '').trim().replace(/\s+/g, ' ');
            if (!t) { return { s: '', ty: '' }; }
            var w = t.split(' ');
            if (w.length <= 2) { return { s: '', ty: t }; }
            return { s: w.slice(0, -2).join(' '), ty: w.slice(-2).join(' ') };
        }
        var titleParts = veloriaHeroSplitTitle(settings.title);
        #>
        <div class="veloria-hero-banner" data-typing-ms="{{ typingMs }}" data-typing-loop="{{ typingLoop }}">
            <div class="veloria-hero-banner__bg"></div>
            <div class="veloria-hero-banner__overlay"></div>
            <div class="veloria-hero-banner__inner">
                <div class="veloria-hero-banner__content">
                    <p class="veloria-hero-banner__tagline">
                        <# if (settings.tagline_prefix) { #><span class="veloria-hero-banner__tagline-prefix">{{{ settings.tagline_prefix }}}</span> <# } #>
                        <# if (settings.tagline_accent) { #><span class="veloria-hero-banner__tagline-accent"><span class="veloria-hero-banner__tagline-accent-text">{{{ settings.tagline_accent }}}</span></span><# } #>
                    </p>
                    <# if (settings.title) { #>
                        <{{{ titleTag }}} class="veloria-hero-banner__title" aria-label="{{ settings.title }}">
                            <# if (titleParts.s) { #><span class="veloria-hero-banner__title-static">{{{ titleParts.s }}} </span><# } #>
                            <# if (titleParts.ty) { #>
                                <span class="veloria-hero-banner__title-typing-wrap">
                                    <span class="veloria-hero-banner__title-typing" data-text="{{ titleParts.ty }}"></span><span class="veloria-hero-banner__title-cursor" aria-hidden="true"></span>
                                </span>
                            <# } #>
                        </{{{ titleTag }}}>
                    <# } #>
                    <div class="veloria-hero-banner__subrow-wrap">
                        <div class="veloria-hero-banner__subrow">
                            <# if (showLine) { #><span class="veloria-hero-banner__accent-line" aria-hidden="true"></span><# } #>
                            <# if (settings.subtitle) { #><p class="veloria-hero-banner__subtitle">{{{ settings.subtitle }}}</p><# } #>
                        </div>
                    </div>
                    <# if (buttons.length) { #>
                        <div class="veloria-hero-banner__actions">
                        <# _.each(buttons, function(btn, i) {
                            var href = btn.button_link && btn.button_link.url ? btn.button_link.url : '#';
                            var type = btn.button_type || 'solid';
                            var cls = 'veloria-hero-banner__btn veloria-hero-banner__btn--' + type;
                            var attrs = '';
                            if (btn.button_link && btn.button_link.is_external) { attrs += ' target="_blank"'; }
                            if (btn.button_link && btn.button_link.nofollow) { attrs += ' rel="nofollow"'; }
                            var showIcon = btn.show_icon === 'yes' && btn.selected_icon && btn.selected_icon.value;
                        #>
                            <a href="{{ href }}" class="{{ cls }}"<# print(attrs); #>>
                                <span class="veloria-hero-banner__btn-inner">
                                    <span class="veloria-hero-banner__btn-text">{{{ btn.button_text }}}</span>
                                    <# if (showIcon) { #>
                                        <span class="veloria-hero-banner__btn-icon" aria-hidden="true">{{{ elementor.helpers.renderIcon(view, btn.selected_icon, {}, 'i', 'object').value }}}</span>
                                    <# } #>
                                </span>
                            </a>
                        <# }); #>
                        </div>
                    <# } #>
                </div>
            </div>
        </div>
        <?php
                }
        }
}
}
