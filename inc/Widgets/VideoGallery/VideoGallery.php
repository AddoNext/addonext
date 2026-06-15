<?php
namespace Veloria\Widgets\VideoGallery;

use Veloria\Base\WidgetBase;
use Veloria\Widgets\VideoPlayer\VideoPlayer;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

defined('ABSPATH') || exit;

/**
 * Responsive grid of video tiles; playback opens a lightbox player (same sources as Video Player).
 */
class VideoGallery extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_assets']);
    }

    /**
     * Loads assets in the Elementor editor canvas.
     */
    public function enqueue_preview_assets(): void
    {
        $this->enqueue_assets();
        wp_enqueue_style('veloria-video-player');
        wp_enqueue_style('veloria-video-gallery');
        wp_enqueue_script('veloria-video-gallery');
    }

    public function get_icon(): string
    {
        return 'eicon-gallery-grid veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-video-gallery';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Video Gallery', 'veloria');
    }

    public function get_description(): string
    {
        return __('Grid of YouTube, Vimeo, Dailymotion, or self-hosted clips with lightbox playback.', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#dc2626';
    }

    public function get_style_depends(): array
    {
        return ['veloria-video-player', 'veloria-video-gallery'];
    }

    public function get_script_depends(): array
    {
        return ['veloria-video-gallery'];
    }

    public function enqueue_assets(): void
    {
        wp_register_style(
            'veloria-video-gallery',
            VELORIA_URL . 'assets/css/video-gallery.css',
            ['veloria-video-player'],
            VELORIA_VERSION
        );
        wp_register_script(
            'veloria-video-gallery',
            VELORIA_URL . 'assets/js/video-gallery.js',
            ['jquery', 'elementor-frontend'],
            VELORIA_VERSION,
            true
        );
    }

    public function register_controls(): void
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Videos', 'veloria'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'video_type',
            [
                'label'   => __('Source', 'veloria'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'youtube',
                'options' => [
                    'youtube'     => __('YouTube', 'veloria'),
                    'vimeo'       => __('Vimeo', 'veloria'),
                    'dailymotion' => __('Dailymotion', 'veloria'),
                    'hosted'      => __('Self hosted', 'veloria'),
                ],
            ]
        );

        $repeater->add_control(
            'youtube_url',
            [
                'label'     => __('YouTube URL', 'veloria'),
                'type'      => Controls_Manager::URL,
                'default'   => ['url' => 'https://www.youtube.com/watch?v=jNQXAC9IVRw'],
                'condition' => ['video_type' => 'youtube'],
            ]
        );

        $repeater->add_control(
            'vimeo_url',
            [
                'label'     => __('Vimeo URL', 'veloria'),
                'type'      => Controls_Manager::URL,
                'default'   => ['url' => 'https://vimeo.com/148751763'],
                'condition' => ['video_type' => 'vimeo'],
            ]
        );

        $repeater->add_control(
            'dailymotion_url',
            [
                'label'     => __('Dailymotion URL', 'veloria'),
                'type'      => Controls_Manager::URL,
                'default'   => ['url' => 'https://www.dailymotion.com/video/x7tfyzb'],
                'condition' => ['video_type' => 'dailymotion'],
            ]
        );

        $repeater->add_control(
            'hosted_url',
            [
                'label'      => __('Video file', 'veloria'),
                'type'       => Controls_Manager::MEDIA,
                'media_type' => 'video',
                'condition'  => ['video_type' => 'hosted'],
            ]
        );

        $repeater->add_control(
            'thumbnail',
            [
                'label'   => __('Custom thumbnail', 'veloria'),
                'type'    => Controls_Manager::MEDIA,
                'default' => ['url' => ''],
            ]
        );

        $repeater->add_control(
            'show_title',
            [
                'label'        => __('Show title', 'veloria'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label'     => __('Title', 'veloria'),
                'type'      => Controls_Manager::TEXT,
                'default'   => __('Video title', 'veloria'),
                'condition' => ['show_title' => 'yes'],
            ]
        );

        $repeater->add_control(
            'show_subtitle',
            [
                'label'        => __('Show subtitle', 'veloria'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $repeater->add_control(
            'subtitle',
            [
                'label'     => __('Subtitle', 'veloria'),
                'type'      => Controls_Manager::TEXT,
                'default'   => __('Short description', 'veloria'),
                'condition' => ['show_subtitle' => 'yes'],
            ]
        );

        $demo_ids = ['jNQXAC9IVRw', '9bZkp7q19f0', 'L_jWHffIx5E', 'y6120QOlsfU', 'kJQP7kiw5Fk', 'OPf0YbXqDm0'];
        $default_items = [];
        foreach ($demo_ids as $id) {
            $default_items[] = [
                'video_type'  => 'youtube',
                'youtube_url' => ['url' => 'https://www.youtube.com/watch?v=' . $id],
                'show_title'  => 'yes',
                'title'       => __('Sample video', 'veloria'),
                'show_subtitle' => 'yes',
                'subtitle'    => __('Tap play to watch', 'veloria'),
            ];
        }

        $this->add_control(
            'items',
            [
                'label'       => __('Items', 'veloria'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => $default_items,
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label'          => __('Columns', 'veloria'),
                'type'           => Controls_Manager::NUMBER,
                'min'            => 1,
                'max'            => 8,
                'step'           => 1,
                'default'        => 3,
                'tablet_default' => 2,
                'mobile_default' => 1,
                'selectors'      => [
                    '{{WRAPPER}} .veloria-video-gallery__grid' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
                ],
            ]
        );

        $this->add_responsive_control(
            'row_gap',
            [
                'label'      => __('Row gap', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 48]],
                'default'    => ['size' => 12, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-video-gallery__grid' => 'row-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'column_gap',
            [
                'label'      => __('Column gap', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 48]],
                'default'    => ['size' => 12, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-video-gallery__grid' => 'column-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_cell',
            [
                'label' => __('Tile', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'aspect_ratio',
            [
                'label'   => __('Aspect ratio', 'veloria'),
                'type'    => Controls_Manager::SELECT,
                'default' => '16/9',
                'options' => [
                    '16/9' => '16:9',
                    '4/3'  => '4:3',
                    '21/9' => '21:9',
                ],
                'selectors' => [
                    '{{WRAPPER}} .veloria-video-gallery__cell .veloria-video-player__inner' => 'aspect-ratio: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label'      => __('Border radius', 'veloria'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default'    => ['top' => 12, 'right' => 12, 'bottom' => 12, 'left' => 12, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-video-gallery__cell .veloria-video-player__inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'tile_border',
                'label'    => __('Border', 'veloria'),
                'selector' => '{{WRAPPER}} .veloria-video-gallery__cell .veloria-video-player__inner',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'tile_shadow',
                'label'    => __('Box shadow', 'veloria'),
                'selector' => '{{WRAPPER}} .veloria-video-gallery__cell .veloria-video-player__inner',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_overlay',
            [
                'label' => __('Overlay', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'overlay_gradient_start',
            [
                'label'     => __('Gradient start', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#312e81',
                'selectors' => [
                    '{{WRAPPER}} .veloria-video-gallery__cell .veloria-video-player__overlay' => '--veloria-vp-overlay-start: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'overlay_gradient_end',
            [
                'label'     => __('Gradient end', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#4c1d95',
                'selectors' => [
                    '{{WRAPPER}} .veloria-video-gallery__cell .veloria-video-player__overlay' => '--veloria-vp-overlay-end: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_play',
            [
                'label' => __('Play button', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'play_button_bg',
            [
                'label'     => __('Background', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .veloria-video-gallery__play' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'play_button_icon_color',
            [
                'label'     => __('Icon color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#2563eb',
                'selectors' => [
                    '{{WRAPPER}} .veloria-video-gallery__play-icon' => 'border-left-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'play_button_shadow',
                'selector' => '{{WRAPPER}} .veloria-video-gallery__play',
            ]
        );

        $this->add_responsive_control(
            'gallery_play_button_size',
            [
                'label'      => __('Button size', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 32, 'max' => 88]],
                'default'    => ['size' => 44, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}}' => '--veloria-vg-play: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_title',
            [
                'label' => __('Title', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .veloria-video-gallery__cell .veloria-video-player__title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => __('Color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .veloria-video-gallery__cell .veloria-video-player__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_subtitle',
            [
                'label' => __('Subtitle', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'subtitle_typography',
                'selector' => '{{WRAPPER}} .veloria-video-gallery__cell .veloria-video-player__subtitle',
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label'     => __('Color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .veloria-video-gallery__cell .veloria-video-player__subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_lightbox',
            [
                'label' => __('Lightbox', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'lightbox_backdrop',
            [
                'label'     => __('Backdrop', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(15, 23, 42, 0.82)',
                'selectors' => [
                    '{{WRAPPER}} .veloria-video-gallery__lightbox-backdrop' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'lightbox_max_width',
            [
                'label'      => __('Player max width', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range'      => [
                    'px' => ['min' => 280, 'max' => 1400],
                    '%'  => ['min' => 50, 'max' => 100],
                    'vw' => ['min' => 50, 'max' => 100],
                ],
                'default'    => ['size' => 960, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-video-gallery__lightbox-dialog' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'lightbox_radius',
            [
                'label'      => __('Player corner radius', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 32]],
                'default'    => ['size' => 12, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-video-gallery__lightbox-stage' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = VELORIA_DIR . 'templates/video-gallery/video-gallery.php';
        if (is_readable($path)) {
            include $path;
        }
    }
}
