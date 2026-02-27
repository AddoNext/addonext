<?php
namespace Elementrify\Widgets\InstagramFeed;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class InstagramFeed extends WidgetBase
{
    public function get_icon(): string { return 'eicon-instagram-gallery elementrify-icon'; }
    public function get_name(): string { return 'elementrify-instagram-feed'; }
    public function get_admin_category(): string { return 'marketing'; }
    public function get_title(): string { return __('Instagram Feed', 'elementrify'); }
    public function get_description(): string { return __('Display Instagram posts', 'elementrify'); }
    public function get_brand_color(): string { return '#e4405f'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('username', ['label' => __('Username / Token', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => '']);
        $this->add_control('count', ['label' => __('Number of posts', 'elementrify'), 'type' => Controls_Manager::NUMBER, 'default' => 6]);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/instagram-feed/instagram-feed.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-instagram-feed"><p><?php echo esc_html__('Instagram feed placeholder. Connect API to show posts.', 'elementrify'); ?> ({{{ settings.username }}})</p></div><?php
    }
}
