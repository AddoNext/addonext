<?php
namespace Elementrify\Widgets\TwitterFeed;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class TwitterFeed extends WidgetBase
{
    public function get_icon(): string { return 'eicon-twitter-feed elementrify-icon'; }
    public function get_name(): string { return 'elementrify-twitter-feed'; }
    public function get_admin_category(): string { return 'marketing'; }
    public function get_title(): string { return __('Twitter Feed', 'elementrify'); }
    public function get_description(): string { return __('Embed Twitter timeline', 'elementrify'); }
    public function get_brand_color(): string { return '#1da1f2'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('username', ['label' => __('Twitter username', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => '']);
        $this->add_control('tweet_limit', ['label' => __('Number of tweets', 'elementrify'), 'type' => Controls_Manager::NUMBER, 'default' => 5]);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/twitter-feed/twitter-feed.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-twitter-feed"><p><?php echo esc_html__('Twitter feed placeholder.', 'elementrify'); ?> @{{{ settings.username }}}</p></div><?php
    }
}
