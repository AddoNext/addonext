<?php
namespace Elementrify\Widgets\VideoPlayer;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class VideoPlayer extends WidgetBase
{
    public function get_icon(): string { return 'eicon-video-playlist elementrify-icon'; }
    public function get_name(): string { return 'elementrify-video-player'; }
    public function get_admin_category(): string { return 'content'; }
    public function get_title(): string { return __('Video Player', 'elementrify'); }
    public function get_description(): string { return __('YouTube, Vimeo & self-hosted', 'elementrify'); }
    public function get_brand_color(): string { return '#dc2626'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('video_type', ['label' => __('Source', 'elementrify'), 'type' => Controls_Manager::SELECT, 'default' => 'youtube', 'options' => ['youtube' => __('YouTube', 'elementrify'), 'vimeo' => __('Vimeo', 'elementrify'), 'hosted' => __('Self hosted', 'elementrify')]]);
        $this->add_control('youtube_url', ['label' => __('YouTube URL', 'elementrify'), 'type' => Controls_Manager::URL, 'condition' => ['video_type' => 'youtube']]);
        $this->add_control('vimeo_url', ['label' => __('Vimeo URL', 'elementrify'), 'type' => Controls_Manager::URL, 'condition' => ['video_type' => 'vimeo']]);
        $this->add_control('hosted_url', ['label' => __('Video URL', 'elementrify'), 'type' => Controls_Manager::MEDIA, 'media_type' => 'video', 'condition' => ['video_type' => 'hosted']]);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/video-player/video-player.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-video-player"><div class="elementrify-video-player__placeholder"><?php echo esc_html__('Video:', 'elementrify'); ?> {{{ settings.video_type }}}</div></div><?php
    }
}
