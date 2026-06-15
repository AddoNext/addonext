<?php
namespace Veloria\Widgets\VideoPlayer;

use Veloria\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

defined('ABSPATH') || exit;

class VideoPlayer extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-video-playlist veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-video-player';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Video Player', 'veloria');
    }

    public function get_description(): string
    {
        return __('YouTube, Vimeo, Dailymotion & self-hosted with overlay', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#dc2626';
    }

    public function get_style_depends(): array
    {
        return ['veloria-video-player'];
    }

    public function get_script_depends(): array
    {
        return ['veloria-video-player'];
    }

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_scripts']);
    }

    public function enqueue_preview_scripts(): void
    {
        wp_enqueue_style('veloria-video-player');
        wp_enqueue_script('veloria-video-player');
    }

    public function register_controls(): void
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Video', 'veloria'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
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

        $this->add_control(
            'youtube_url',
            [
                'label'     => __('YouTube URL', 'veloria'),
                'type'      => Controls_Manager::URL,
                'default'   => ['url' => 'https://www.youtube.com/watch?v=jNQXAC9IVRw'],
                'condition' => ['video_type' => 'youtube'],
            ]
        );

        $this->add_control(
            'vimeo_url',
            [
                'label'     => __('Vimeo URL', 'veloria'),
                'type'      => Controls_Manager::URL,
                'default'   => ['url' => 'https://vimeo.com/148751763'],
                'condition' => ['video_type' => 'vimeo'],
            ]
        );

        $this->add_control(
            'dailymotion_url',
            [
                'label'     => __('Dailymotion URL', 'veloria'),
                'type'      => Controls_Manager::URL,
                'default'   => ['url' => 'https://www.dailymotion.com/video/x7tfyzb'],
                'condition' => ['video_type' => 'dailymotion'],
            ]
        );

        $this->add_control(
            'hosted_url',
            [
                'label'     => __('Video file', 'veloria'),
                'type'      => Controls_Manager::MEDIA,
                'media_type'=> 'video',
                'condition' => ['video_type' => 'hosted'],
            ]
        );

        $this->add_control(
            'thumbnail',
            [
                'label'   => __('Custom thumbnail', 'veloria'),
                'type'    => Controls_Manager::MEDIA,
                'default' => ['url' => ''],
            ]
        );

        $this->add_control(
            'show_title',
            [
                'label'        => __('Show title', 'veloria'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'     => 'yes',
            ]
        );

        $this->add_control(
            'title',
            [
                'label'     => __('Title', 'veloria'),
                'type'      => Controls_Manager::TEXT,
                'default'   => __('Watch Our Introduction Video', 'veloria'),
                'condition' => ['show_title' => 'yes'],
            ]
        );

        $this->add_control(
            'show_subtitle',
            [
                'label'        => __('Show subtitle', 'veloria'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'     => 'yes',
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label'     => __('Subtitle', 'veloria'),
                'type'      => Controls_Manager::TEXT,
                'default'   => __('Learn how to use our widgets', 'veloria'),
                'condition' => ['show_subtitle' => 'yes'],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_container',
            [
                'label' => __('Container', 'veloria'),
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
                    '{{WRAPPER}} .veloria-video-player__inner' => 'aspect-ratio: {{VALUE}};',
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
                    '{{WRAPPER}} .veloria-video-player__inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .veloria-video-player__inner iframe' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .veloria-video-player__inner video' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'border',
                'selector' => '{{WRAPPER}} .veloria-video-player__inner',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'box_shadow',
                'selector' => '{{WRAPPER}} .veloria-video-player__inner',
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
                    '{{WRAPPER}} .veloria-video-player__overlay' => '--veloria-vp-overlay-start: {{VALUE}};',
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
                    '{{WRAPPER}} .veloria-video-player__overlay' => '--veloria-vp-overlay-end: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_play_button',
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
                    '{{WRAPPER}} .veloria-video-player__play' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .veloria-video-player__play-icon' => 'border-left-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'play_button_shadow',
                'selector' => '{{WRAPPER}} .veloria-video-player__play',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_title',
            [
                'label'     => __('Title', 'veloria'),
                'tab'      => Controls_Manager::TAB_STYLE,
                'condition' => ['show_title' => 'yes'],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .veloria-video-player__title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => __('Color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .veloria-video-player__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_subtitle',
            [
                'label'     => __('Subtitle', 'veloria'),
                'tab'      => Controls_Manager::TAB_STYLE,
                'condition' => ['show_subtitle' => 'yes'],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'subtitle_typography',
                'selector' => '{{WRAPPER}} .veloria-video-player__subtitle',
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label'     => __('Color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .veloria-video-player__subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public static function get_embed_url(string $type, $url): string
    {
        if (empty($url) || !is_string($url)) {
            return '';
        }
        $url = trim($url);
        if ($type === 'youtube') {
            if (preg_match('#(?:youtube\.com/watch\?v=|youtu\.be/)([a-zA-Z0-9_-]+)#', $url, $m)) {
                return 'https://www.youtube.com/embed/' . $m[1] . '?autoplay=1';
            }
        }
        if ($type === 'vimeo') {
            if (preg_match('#vimeo\.com/(?:video/)?(\d+)#', $url, $m)) {
                return 'https://player.vimeo.com/video/' . $m[1] . '?autoplay=1';
            }
        }
        if ($type === 'dailymotion') {
            if (preg_match('#dailymotion\.com/video/([a-zA-Z0-9]+)#', $url, $m)) {
                return 'https://www.dailymotion.com/embed/video/' . $m[1] . '?autoplay=1';
            }
        }
        return '';
    }

    public function render(): void
    {
        $path = VELORIA_DIR . 'templates/video-player/video-player.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        var embedUrl = '';
        var videoType = settings.video_type;
        if (videoType === 'youtube' && settings.youtube_url && settings.youtube_url.url) {
            var y = settings.youtube_url.url;
            var m = y.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/);
            if (m) embedUrl = 'https://www.youtube.com/embed/' + m[1] + '?autoplay=1';
        } else if (videoType === 'vimeo' && settings.vimeo_url && settings.vimeo_url.url) {
            var v = settings.vimeo_url.url;
            var vm = v.match(/vimeo\.com\/(?:video\/)?(\d+)/);
            if (vm) embedUrl = 'https://player.vimeo.com/video/' + vm[1] + '?autoplay=1';
        } else if (videoType === 'dailymotion' && settings.dailymotion_url && settings.dailymotion_url.url) {
            var d = settings.dailymotion_url.url;
            var dm = d.match(/dailymotion\.com\/video\/([a-zA-Z0-9]+)/);
            if (dm) embedUrl = 'https://www.dailymotion.com/embed/video/' + dm[1] + '?autoplay=1';
        }
        var hostedUrl = (videoType === 'hosted' && settings.hosted_url && settings.hosted_url.url) ? settings.hosted_url.url : '';
        var thumbUrl = (settings.thumbnail && settings.thumbnail.url) ? settings.thumbnail.url : '';
        var hasThumb = thumbUrl !== '';
        view.addRenderAttribute('inner', 'class', 'veloria-video-player__inner');
        if (hasThumb) view.addRenderAttribute('inner', 'class', 'veloria-video-player--has-thumbnail');
        view.addRenderAttribute('inner', 'data-embed', embedUrl);
        view.addRenderAttribute('inner', 'data-hosted', hostedUrl);
        view.addRenderAttribute('inner', 'data-type', videoType);
        #>
        <div class="veloria-video-player">
            <div {{{ view.getRenderAttributeString('inner') }}}>
                <div class="veloria-video-player__overlay">
                    <# if (hasThumb) { #>
                    <img class="veloria-video-player__thumbnail" src="{{ thumbUrl }}" alt="" loading="lazy" decoding="async">
                    <span class="veloria-video-player__overlay-shade" aria-hidden="true"></span>
                    <# } #>
                    <button type="button" class="veloria-video-player__play" aria-label="<?php echo esc_attr__('Play video', 'veloria'); ?>">
                        <span class="veloria-video-player__play-icon"></span>
                    </button>
                    <# if (settings.show_title === 'yes' && settings.title) { #>
                    <div class="veloria-video-player__caption">
                        <div class="veloria-video-player__title">{{{ settings.title }}}</div>
                        <# if (settings.show_subtitle === 'yes' && settings.subtitle) { #>
                        <div class="veloria-video-player__subtitle">{{{ settings.subtitle }}}</div>
                        <# } #>
                    </div>
                    <# } else if (settings.show_subtitle === 'yes' && settings.subtitle) { #>
                    <div class="veloria-video-player__caption">
                        <div class="veloria-video-player__subtitle">{{{ settings.subtitle }}}</div>
                    </div>
                    <# } #>
                </div>
                <div class="veloria-video-player__embed"></div>
            </div>
        </div>
        <?php
    }
}
