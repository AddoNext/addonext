<?php
namespace Elementrify\Widgets\HeroSlider;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
defined('ABSPATH') || exit;

/**
 * Hero section with Swiper: background slides, content slides, or both per slide.
 */
class HeroSlider extends WidgetBase
{
    private const DEMO_BG_IMAGES = [
        'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1920&q=80',
        'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=1920&q=80',
        'https://images.unsplash.com/photo-1557804506-669a67965ba0?auto=format&fit=crop&w=1920&q=80',
    ];

    /**
     * @return array{url: string, id: string, size: string}
     */
    private static function demoBgImageField(int $index): array
    {
        $list = self::DEMO_BG_IMAGES;
        $url = $list[$index % count($list)];

        return [
            'url'  => $url,
            'id'   => '',
            'size' => '',
        ];
    }

    /**
     * Demo image URL for template fallbacks when a slide has no `_id` yet.
     */
    public static function demoBgImageUrl(int $index): string
    {
        $row = self::demoBgImageField($index);

        return $row['url'];
    }

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_scripts']);
    }

    public function enqueue_preview_scripts(): void
    {
        wp_enqueue_style('swiper');
        wp_enqueue_style('elementrify-hero-slider');
        wp_enqueue_script('swiper');
        wp_enqueue_script('elementrify-hero-slider');
    }

    public function get_icon(): string
    {
        return 'eicon-slider-push elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-hero-slider';
    }

    public function get_admin_category(): string
    {
        return 'marketing';
    }

    public function get_title(): string
    {
        return __('Hero Slider', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Hero with sliding backgrounds, sliding copy and CTAs, or synchronized slides.', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#5E42DA';
    }

    public function get_style_depends(): array
    {
        return ['swiper', 'elementrify-hero-slider'];
    }

    public function get_script_depends(): array
    {
        return ['swiper', 'elementrify-hero-slider'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'elementrify-hero-slider',
            ELEMENTRIFY_URL . 'assets/css/hero-slider.css',
            ['swiper'],
            ELEMENTRIFY_VERSION
        );
        wp_register_script(
            'elementrify-hero-slider',
            ELEMENTRIFY_URL . 'assets/js/hero-slider.js',
            ['jquery', 'elementor-frontend', 'swiper'],
            ELEMENTRIFY_VERSION,
            true
        );
    }

    /**
     * @return array<string, mixed>
     */
    public static function parseBgVideoState(array $source, string $prefix): array
    {
        $bg_mode = isset($source[$prefix . '_background']) ? (string) $source[$prefix . '_background'] : '';
        $video_link = isset($source[$prefix . '_video_link']) ? trim((string) $source[$prefix . '_video_link']) : '';

        $youtube_id = '';
        if ($video_link !== '' && preg_match('#(?:youtube\.com/watch\?v=|youtu\.be/)([a-zA-Z0-9_-]{6,})#', $video_link, $m)) {
            $youtube_id = $m[1];
        } elseif ($video_link !== '' && preg_match('#youtube\.com/embed/([a-zA-Z0-9_-]{6,})#', $video_link, $m)) {
            $youtube_id = $m[1];
        }

        $vimeo_id = '';
        if ($video_link !== '' && preg_match('#vimeo\.com/(?:video/)?(\d+)#', $video_link, $m)) {
            $vimeo_id = $m[1];
        }

        $is_file_video = $video_link !== '' && preg_match('#\.(mp4|webm|ogg)(\?|#|$)#i', $video_link);
        $video_mime = 'video/mp4';
        if ($is_file_video) {
            $path = (string) wp_parse_url($video_link, PHP_URL_PATH);
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            $map = ['mp4' => 'video/mp4', 'webm' => 'video/webm', 'ogg' => 'video/ogg'];
            if (isset($map[$ext])) {
                $video_mime = $map[$ext];
            }
        }

        return [
            'bg_mode'       => $bg_mode,
            'video_link'    => $video_link,
            'youtube_id'    => $youtube_id,
            'vimeo_id'      => $vimeo_id,
            'is_file_video' => (bool) $is_file_video,
            'video_mime'    => $video_mime,
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function getButtonsFieldDefinitions(): array
    {
        $r = new Repeater();
        $r->add_control('button_text', [
            'label'   => __('Text', 'elementrify'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Browse widgets', 'elementrify'),
            'dynamic' => ['active' => true],
        ]);
        $r->add_control('button_link', [
            'label'   => __('Link', 'elementrify'),
            'type'    => Controls_Manager::URL,
            'default' => ['url' => '#', 'is_external' => '', 'nofollow' => ''],
            'dynamic' => ['active' => true],
        ]);
        $r->add_control('button_type', [
            'label'   => __('Type', 'elementrify'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'solid',
            'options' => [
                'solid'    => __('Solid', 'elementrify'),
                'outlined' => __('Outlined', 'elementrify'),
            ],
        ]);
        $r->add_control('selected_icon', [
            'label'            => __('Icon', 'elementrify'),
            'type'             => Controls_Manager::ICONS,
            'fa4compatibility' => 'icon',
            'default'          => [
                'value'   => 'fas fa-arrow-up-right-from-square',
                'library' => 'fa-solid',
            ],
        ]);
        $r->add_control('show_icon', [
            'label'        => __('Show icon', 'elementrify'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        return $r->get_controls();
    }

    /**
     * @param array<string, mixed> $defaults Optional defaults merged into repeater field defaults for first row.
     */
    private function addSlideTextAndButtons(Repeater $slide_rep, array $defaults = []): void
    {
        $site_title = get_bloginfo('name', 'display');
        if (!is_string($site_title) || trim($site_title) === '') {
            $site_title = 'Elementrify';
        }

        $slide_rep->add_control('tagline_prefix', [
            'label'       => __('Tagline (first part)', 'elementrify'),
            'type'        => Controls_Manager::TEXT,
            'default'     => $defaults['tagline_prefix'] ?? __('Built for', 'elementrify'),
            'label_block' => true,
            'dynamic'     => ['active' => true],
        ]);
        $slide_rep->add_control('tagline_accent', [
            'label'       => __('Tagline (accent)', 'elementrify'),
            'type'        => Controls_Manager::TEXT,
            'default'     => $defaults['tagline_accent'] ?? $site_title,
            'label_block' => true,
            'dynamic'     => ['active' => true],
        ]);
        $slide_rep->add_control('title', [
            'label'       => __('Title', 'elementrify'),
            'type'        => Controls_Manager::TEXT,
            'default'     => $defaults['title'] ?? __('Create pages that convert faster', 'elementrify'),
            'description' => __('The last two words use a typing animation and gradient.', 'elementrify'),
            'dynamic'     => ['active' => true],
        ]);
        $slide_rep->add_control('title_typing_speed', [
            'label'   => __('Typing speed (ms)', 'elementrify'),
            'type'    => Controls_Manager::NUMBER,
            'min'     => 20,
            'max'     => 200,
            'step'    => 5,
            'default' => 55,
        ]);
        $slide_rep->add_control('title_typing_loop', [
            'label'        => __('Loop typing animation', 'elementrify'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);
        $slide_rep->add_control('title_tag', [
            'label'   => __('Title HTML tag', 'elementrify'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'h1',
            'options' => [
                'h1'  => 'H1',
                'h2'  => 'H2',
                'h3'  => 'H3',
                'div' => 'div',
            ],
        ]);
        $slide_rep->add_control('subtitle', [
            'label'   => __('Subtitle', 'elementrify'),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => $defaults['subtitle'] ?? __('Premium Elementor widgets and patterns to ship polished layouts without slowing down your team.', 'elementrify'),
            'rows'    => 4,
            'dynamic' => ['active' => true],
        ]);
        $slide_rep->add_control('show_accent_line', [
            'label'        => __('Accent line beside subtitle', 'elementrify'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);
        $slide_rep->add_control('buttons', [
            'label'       => __('Buttons', 'elementrify'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $this->getButtonsFieldDefinitions(),
            'default'     => [
                [
                    'button_text' => __('Browse widgets', 'elementrify'),
                    'button_link' => ['url' => '#', 'is_external' => '', 'nofollow' => ''],
                    'button_type' => 'solid',
                    'show_icon'   => 'yes',
                ],
            ],
            'title_field' => '{{{ button_text }}}',
        ]);
    }

    private function addComboBackgroundToRepeater(Repeater $r): void
    {
        $r->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'           => 'combo_bg_panel',
                'types'          => ['classic', 'gradient', 'video'],
                'selector'       => '{{WRAPPER}} {{CURRENT_ITEM}} .elementrify-hero-slider__combo-slide-bg',
                'fields_options' => [
                    'background' => ['default' => 'classic'],
                    'color'      => ['default' => '#1e293b'],
                    'image'      => [
                        'default' => self::demoBgImageField(0),
                    ],
                ],
            ]
        );
    }

    public function register_controls(): void
    {
        $this->start_controls_section('section_slider', [
            'label' => __('Slider', 'elementrify'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('slider_mode', [
            'label'   => __('Slide mode', 'elementrify'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'both',
            'options' => [
                'background' => __('Background only', 'elementrify'),
                'content'    => __('Content only', 'elementrify'),
                'both'       => __('Background and content', 'elementrify'),
            ],
        ]);

        $this->add_control('navigation', [
            'label'   => __('Navigation', 'elementrify'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'none',
            'options' => [
                'both'   => __('Arrows and dots', 'elementrify'),
                'arrows' => __('Arrows', 'elementrify'),
                'dots'   => __('Dots', 'elementrify'),
                'none'   => __('None', 'elementrify'),
            ],
        ]);

        $this->add_control('autoplay', [
            'label'        => __('Autoplay', 'elementrify'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('autoplay_delay', [
            'label'     => __('Autoplay interval (seconds)', 'elementrify'),
            'type'      => Controls_Manager::NUMBER,
            'min'       => 1,
            'max'       => 30,
            'step'      => 1,
            'default'   => 5,
            'condition' => ['autoplay' => 'yes'],
        ]);

        $this->add_control('loop', [
            'label'        => __('Loop', 'elementrify'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('slider_speed', [
            'label'   => __('Transition duration (ms)', 'elementrify'),
            'type'    => Controls_Manager::NUMBER,
            'min'     => 200,
            'max'     => 2000,
            'step'    => 50,
            'default' => 600,
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_layout', [
            'label' => __('Layout', 'elementrify'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_responsive_control('min_height', [
            'label'      => __('Min height', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'vh'],
            'range'      => [
                'px' => ['min' => 200, 'max' => 900],
                'vh' => ['min' => 30, 'max' => 100],
            ],
            'default'    => ['size' => 100, 'unit' => 'vh'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-hero-slider' => 'min-height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('content_max_width', [
            'label'      => __('Content max width', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range'      => ['px' => ['min' => 280, 'max' => 900]],
            'default'    => ['size' => 520, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-hero-slider__content' => 'max-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('content_align_vertical', [
            'label'     => __('Vertical align', 'elementrify'),
            'type'      => Controls_Manager::CHOOSE,
            'options'   => [
                'flex-start' => ['title' => __('Top', 'elementrify'), 'icon' => 'eicon-v-align-top'],
                'center'     => ['title' => __('Middle', 'elementrify'), 'icon' => 'eicon-v-align-middle'],
                'flex-end'   => ['title' => __('Bottom', 'elementrify'), 'icon' => 'eicon-v-align-bottom'],
            ],
            'default'   => 'center',
            'toggle'    => false,
            'selectors' => [
                '{{WRAPPER}} .elementrify-hero-slider__inner' => 'justify-content: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $site_title = get_bloginfo('name', 'display');
        if (!is_string($site_title) || trim($site_title) === '') {
            $site_title = 'Elementrify';
        }

        $this->start_controls_section('section_static_content', [
            'label'     => __('Content', 'elementrify'),
            'tab'       => Controls_Manager::TAB_CONTENT,
            'condition' => ['slider_mode' => 'background'],
        ]);

        $this->add_control('tagline_prefix', [
            'label'       => __('Tagline (first part)', 'elementrify'),
            'type'        => Controls_Manager::TEXT,
            'default'     => __('Built for', 'elementrify'),
            'label_block' => true,
            'dynamic'     => ['active' => true],
        ]);
        $this->add_control('tagline_accent', [
            'label'       => __('Tagline (accent)', 'elementrify'),
            'type'        => Controls_Manager::TEXT,
            'default'     => $site_title,
            'label_block' => true,
            'dynamic'     => ['active' => true],
        ]);
        $this->add_control('title', [
            'label'       => __('Title', 'elementrify'),
            'type'        => Controls_Manager::TEXT,
            'default'     => __('Create pages that convert faster', 'elementrify'),
            'description' => __('The last two words use a typing animation and gradient.', 'elementrify'),
            'dynamic'     => ['active' => true],
        ]);
        $this->add_control('title_typing_speed', [
            'label'   => __('Typing speed (ms)', 'elementrify'),
            'type'    => Controls_Manager::NUMBER,
            'min'     => 20,
            'max'     => 200,
            'step'    => 5,
            'default' => 55,
        ]);
        $this->add_control('title_typing_loop', [
            'label'        => __('Loop typing animation', 'elementrify'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);
        $this->add_control('title_tag', [
            'label'   => __('Title HTML tag', 'elementrify'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'h1',
            'options' => [
                'h1'  => 'H1',
                'h2'  => 'H2',
                'h3'  => 'H3',
                'div' => 'div',
            ],
        ]);
        $this->add_control('subtitle', [
            'label'   => __('Subtitle', 'elementrify'),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => __('Premium Elementor widgets and patterns to ship polished layouts without slowing down your team.', 'elementrify'),
            'rows'    => 4,
            'dynamic' => ['active' => true],
        ]);
        $this->add_control('show_accent_line', [
            'label'        => __('Accent line beside subtitle', 'elementrify'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);
        $this->add_control('buttons', [
            'label'       => __('Buttons', 'elementrify'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $this->getButtonsFieldDefinitions(),
            'default'     => [
                [
                    'button_text' => __('Browse widgets', 'elementrify'),
                    'button_link' => ['url' => '#', 'is_external' => '', 'nofollow' => ''],
                    'button_type' => 'solid',
                    'show_icon'   => 'yes',
                ],
            ],
            'title_field' => '{{{ button_text }}}',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_bg_slides', [
            'label'     => __('Background slides', 'elementrify'),
            'tab'       => Controls_Manager::TAB_CONTENT,
            'condition' => ['slider_mode' => 'background'],
        ]);

        $bg_rep = new Repeater();
        $bg_rep->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'           => 'bg_only_panel',
                'types'          => ['classic', 'gradient', 'video'],
                'selector'       => '{{WRAPPER}} {{CURRENT_ITEM}} .elementrify-hero-slider__bg-slide-inner',
                'fields_options' => [
                    'background' => ['default' => 'classic'],
                    'color'      => ['default' => '#1e293b'],
                    'image'      => [
                        'default' => self::demoBgImageField(0),
                    ],
                ],
            ]
        );

        $this->add_control('bg_slides', [
            'label'       => __('Slides', 'elementrify'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $bg_rep->get_controls(),
            'default'     => [
                [
                    'bg_only_panel_background' => 'classic',
                    'bg_only_panel_image'      => self::demoBgImageField(0),
                ],
                [
                    'bg_only_panel_background' => 'classic',
                    'bg_only_panel_image'      => self::demoBgImageField(1),
                ],
                [
                    'bg_only_panel_background' => 'classic',
                    'bg_only_panel_image'      => self::demoBgImageField(2),
                ],
            ],
            'title_field' => __('Slide', 'elementrify') . ' {{{ _id }}}',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_sticky_bg', [
            'label'       => __('Background', 'elementrify'),
            'tab'         => Controls_Manager::TAB_CONTENT,
            'description' => __('Video: YouTube, Vimeo, or direct .mp4 / .webm / .ogg link.', 'elementrify'),
            'condition'   => ['slider_mode' => 'content'],
        ]);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'           => 'sticky_panel_bg',
                'types'          => ['classic', 'gradient', 'video'],
                'selector'       => '{{WRAPPER}} .elementrify-hero-slider__sticky-bg',
                'fields_options' => [
                    'background' => ['default' => 'classic'],
                    'color'      => ['default' => '#1e293b'],
                    'image'      => [
                        'default' => self::demoBgImageField(0),
                    ],
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('section_content_slides', [
            'label'     => __('Content slides', 'elementrify'),
            'tab'       => Controls_Manager::TAB_CONTENT,
            'condition' => ['slider_mode' => 'content'],
        ]);

        $content_rep = new Repeater();
        $this->addSlideTextAndButtons($content_rep, [
            'title'    => __('First headline for your hero', 'elementrify'),
            'subtitle' => __('Swap slides to tell a short story while the background stays fixed.', 'elementrify'),
        ]);
        $this->add_control('content_slides', [
            'label'       => __('Slides', 'elementrify'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $content_rep->get_controls(),
            'default'     => [
                [
                    'title'    => __('First headline for your hero', 'elementrify'),
                    'subtitle' => __('Swap slides to tell a short story while the background stays fixed.', 'elementrify'),
                ],
                [
                    'title'    => __('Second slide, same backdrop', 'elementrify'),
                    'subtitle' => __('Use the repeater to add tagline, title, subtitle, and buttons per slide.', 'elementrify'),
                ],
            ],
            'title_field' => '{{{ title }}}',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_hero_slides', [
            'label'     => __('Slides', 'elementrify'),
            'tab'       => Controls_Manager::TAB_CONTENT,
            'condition' => ['slider_mode' => 'both'],
        ]);

        $combo_rep = new Repeater();
        $this->addComboBackgroundToRepeater($combo_rep);
        $this->addSlideTextAndButtons($combo_rep);
        $this->add_control('hero_slides', [
            'label'       => __('Slides', 'elementrify'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $combo_rep->get_controls(),
            'default'     => [
                [
                    'title'                     => __('Slide one headline', 'elementrify'),
                    'combo_bg_panel_background' => 'classic',
                    'combo_bg_panel_image'      => self::demoBgImageField(0),
                ],
                [
                    'title'                     => __('Slide two headline', 'elementrify'),
                    'combo_bg_panel_background' => 'classic',
                    'combo_bg_panel_image'      => self::demoBgImageField(1),
                ],
            ],
            'title_field' => '{{{ title }}}',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_background', [
            'label' => __('Background overlay', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('overlay_color', [
            'label'     => __('Overlay', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(15, 23, 42, 0.55)',
            'alpha'     => true,
            'selectors' => [
                '{{WRAPPER}} .elementrify-hero-slider__overlay' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .elementrify-hero-slider__slide-overlay' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('inner_padding', [
            'label'      => __('Inner padding', 'elementrify'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'default'    => [
                'top' => '48', 'right' => '40', 'bottom' => '48', 'left' => '40', 'unit' => 'px', 'isLinked' => false,
            ],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-hero-slider__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_tagline', [
            'label' => __('Tagline', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'tagline_prefix_typography',
                'label'          => __('First part', 'elementrify'),
                'selector'       => '{{WRAPPER}} .elementrify-hero-slider__tagline-prefix',
                'fields_options' => [
                    'typography'     => ['default' => 'custom'],
                    'font_weight'    => ['default' => '600'],
                    'font_size'      => ['default' => ['unit' => 'px', 'size' => 13]],
                    'text_transform' => ['default' => 'uppercase'],
                    'letter_spacing' => ['default' => ['size' => 0.12, 'unit' => 'em']],
                ],
            ]
        );

        $this->add_control('tagline_prefix_color', [
            'label'     => __('First part color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .elementrify-hero-slider__tagline-prefix' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'tagline_accent_typography',
                'label'          => __('Accent part', 'elementrify'),
                'selector'       => '{{WRAPPER}} .elementrify-hero-slider__tagline-accent-text',
                'fields_options' => [
                    'typography'     => ['default' => 'custom'],
                    'font_weight'    => ['default' => '700'],
                    'font_size'      => ['default' => ['unit' => 'px', 'size' => 13]],
                    'text_transform' => ['default' => 'uppercase'],
                    'letter_spacing' => ['default' => ['size' => 0.12, 'unit' => 'em']],
                ],
            ]
        );

        $this->add_control('tagline_spacing', [
            'label'      => __('Spacing below tagline', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 48]],
            'default'    => ['size' => 16, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-hero-slider__tagline' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
                'selector'       => '{{WRAPPER}} .elementrify-hero-slider__title',
                'fields_options' => [
                    'typography'  => ['default' => 'custom'],
                    'font_weight' => ['default' => '700'],
                    'font_size'   => ['default' => ['unit' => 'px', 'size' => 42]],
                    'line_height' => ['default' => ['size' => 1.15, 'unit' => 'em']],
                ],
            ]
        );

        $this->add_control('title_color', [
            'label'     => __('Color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .elementrify-hero-slider__title' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('title_spacing', [
            'label'      => __('Spacing below title', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default'    => ['size' => 20, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-hero-slider__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_style_accent_line', [
            'label' => __('Accent line', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('accent_line_width', [
            'label'      => __('Width', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 2, 'max' => 16]],
            'default'    => ['size' => 4, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-hero-slider__accent-line' => 'width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('accent_line_min_height', [
            'label'      => __('Min height', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'range'      => ['px' => ['min' => 24, 'max' => 200]],
            'default'    => ['size' => 72, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-hero-slider__accent-line' => 'min-height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('accent_line_radius', [
            'label'      => __('Radius', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default'    => ['size' => 2, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-hero-slider__accent-line' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'           => 'accent_line_bg',
                'types'          => ['classic', 'gradient'],
                'exclude'        => ['image'],
                'selector'       => '{{WRAPPER}} .elementrify-hero-slider__accent-line',
                'fields_options' => [
                    'background'     => ['default' => 'gradient'],
                    'color'          => ['default' => '#5E42DA'],
                    'color_b'        => ['default' => '#3b82f6'],
                    'gradient_type'  => ['default' => 'linear'],
                    'gradient_angle' => ['default' => ['unit' => 'deg', 'size' => 180]],
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('section_style_subtitle', [
            'label' => __('Subtitle', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('subrow_gap', [
            'label'      => __('Gap beside accent line', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default'    => ['size' => 16, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-hero-slider__subrow' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'subtitle_typography',
                'selector'       => '{{WRAPPER}} .elementrify-hero-slider__subtitle',
                'fields_options' => [
                    'typography'  => ['default' => 'custom'],
                    'font_weight' => ['default' => '400'],
                    'font_size'   => ['default' => ['unit' => 'px', 'size' => 16]],
                    'line_height' => ['default' => ['size' => 1.55, 'unit' => 'em']],
                ],
            ]
        );

        $this->add_control('subtitle_color', [
            'label'     => __('Color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(255,255,255,0.92)',
            'selectors' => [
                '{{WRAPPER}} .elementrify-hero-slider__subtitle' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('subtitle_spacing', [
            'label'      => __('Spacing below subtitle', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default'    => ['size' => 28, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-hero-slider__subrow-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
            'default'    => ['size' => 14, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-hero-slider__actions' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('icon_gap', [
            'label'      => __('Icon gap', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default'    => ['size' => 8, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-hero-slider__btn-inner' => 'gap: {{SIZE}}{{UNIT}};',
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
                'selector'       => '{{WRAPPER}} .elementrify-hero-slider__btn--solid',
                'fields_options' => [
                    'typography'  => ['default' => 'custom'],
                    'font_weight' => ['default' => '600'],
                    'font_size'   => ['default' => ['unit' => 'px', 'size' => 14]],
                ],
            ]
        );

        $this->add_control('btn_solid_text', [
            'label'     => __('Text & icon color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .elementrify-hero-slider__btn--solid' => 'color: {{VALUE}}; fill: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'           => 'btn_solid_bg',
                'types'          => ['classic', 'gradient'],
                'exclude'        => ['image'],
                'selector'       => '{{WRAPPER}} .elementrify-hero-slider__btn--solid',
                'fields_options' => [
                    'background'     => ['default' => 'gradient'],
                    'color'          => ['default' => '#5E42DA'],
                    'color_b'        => ['default' => '#A542DA'],
                    'gradient_type'  => ['default' => 'linear'],
                    'gradient_angle' => ['default' => ['unit' => 'deg', 'size' => 90]],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'btn_solid_border',
                'selector' => '{{WRAPPER}} .elementrify-hero-slider__btn--solid',
            ]
        );

        $this->add_control('btn_solid_radius', [
            'label'      => __('Border radius', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default'    => ['size' => 6, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-hero-slider__btn--solid' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('btn_solid_padding', [
            'label'      => __('Padding', 'elementrify'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default'    => [
                'top' => '14', 'right' => '22', 'bottom' => '14', 'left' => '22', 'unit' => 'px', 'isLinked' => false,
            ],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-hero-slider__btn--solid' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'btn_solid_shadow',
                'selector' => '{{WRAPPER}} .elementrify-hero-slider__btn--solid',
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
                'selector'       => '{{WRAPPER}} .elementrify-hero-slider__btn--outlined',
                'fields_options' => [
                    'typography'  => ['default' => 'custom'],
                    'font_weight' => ['default' => '600'],
                    'font_size'   => ['default' => ['unit' => 'px', 'size' => 14]],
                ],
            ]
        );

        $this->add_control('btn_outlined_text', [
            'label'     => __('Text & icon color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .elementrify-hero-slider__btn--outlined' => 'color: {{VALUE}}; fill: {{VALUE}};',
            ],
        ]);

        $this->add_control('btn_outlined_bg', [
            'label'     => __('Background', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(255,255,255,0)',
            'selectors' => [
                '{{WRAPPER}} .elementrify-hero-slider__btn--outlined' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'           => 'btn_outlined_border',
                'selector'       => '{{WRAPPER}} .elementrify-hero-slider__btn--outlined',
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
            'default'    => ['size' => 6, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-hero-slider__btn--outlined' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('btn_outlined_padding', [
            'label'      => __('Padding', 'elementrify'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default'    => [
                'top' => '12', 'right' => '20', 'bottom' => '12', 'left' => '20', 'unit' => 'px', 'isLinked' => false,
            ],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-hero-slider__btn--outlined' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'btn_outlined_shadow',
                'selector' => '{{WRAPPER}} .elementrify-hero-slider__btn--outlined',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('section_style_nav', [
            'label' => __('Slider navigation', 'elementrify'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $nav_arrow_visibility = ['navigation' => ['both', 'arrows']];
        $nav_dots_visibility  = ['navigation' => ['both', 'dots']];

        $this->add_control('nav_heading_arrows', [
            'label'     => __('Arrows', 'elementrify'),
            'type'      => Controls_Manager::HEADING,
            'condition' => $nav_arrow_visibility,
        ]);

        $this->add_responsive_control('nav_arrow_size', [
            'label'      => __('Arrow size', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 18, 'max' => 80]],
            'default'    => ['size' => 24, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-hero-slider' => '--swiper-navigation-size: {{SIZE}}{{UNIT}};',
            ],
            'condition'  => $nav_arrow_visibility,
        ]);

        $this->add_control('nav_arrow_color', [
            'label'     => __('Arrow color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .elementrify-hero-slider' => '--swiper-navigation-color: {{VALUE}};',
            ],
            'condition' => $nav_arrow_visibility,
        ]);

        $this->add_control('nav_heading_dots', [
            'label'     => __('Dots', 'elementrify'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => $nav_dots_visibility,
        ]);

        $this->add_responsive_control('nav_dot_size', [
            'label'      => __('Dot size', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 4, 'max' => 28]],
            'default'    => ['size' => 8, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-hero-slider' => '--swiper-pagination-bullet-size: {{SIZE}}{{UNIT}};',
            ],
            'condition'  => $nav_dots_visibility,
        ]);

        $this->add_control('nav_dot_color_active', [
            'label'     => __('Active dot color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .elementrify-hero-slider' => '--swiper-pagination-color: {{VALUE}};',
            ],
            'condition' => $nav_dots_visibility,
        ]);

        $this->add_control('nav_dot_color_inactive', [
            'label'     => __('Inactive dot color', 'elementrify'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(255, 255, 255, 0.45)',
            'alpha'     => true,
            'selectors' => [
                '{{WRAPPER}} .elementrify-hero-slider' => '--swiper-pagination-bullet-inactive-color: {{VALUE}};',
            ],
            'condition' => $nav_dots_visibility,
        ]);

        $this->add_responsive_control('nav_dot_gap', [
            'label'      => __('Dot spacing', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 24]],
            'default'    => ['size' => 4, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-hero-slider' => '--swiper-pagination-bullet-horizontal-gap: {{SIZE}}{{UNIT}};',
            ],
            'condition'  => $nav_dots_visibility,
        ]);

        $this->add_control('nav_dots_offset', [
            'label'      => __('Dots offset from bottom', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 80]],
            'default'    => ['size' => 20, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-hero-slider__pagination-wrap' => 'bottom: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('nav_arrow_offset', [
            'label'      => __('Arrows inset from sides', 'elementrify'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 4, 'max' => 48]],
            'selectors'  => [
                '{{WRAPPER}} .elementrify-hero-slider__arrow--prev' => 'left: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .elementrify-hero-slider__arrow--next' => 'right: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/hero-slider/hero-slider.php';
        if (is_readable($path)) {
            include $path;
        }
    }
}
