<?php
namespace Elementrify\Widgets\SmartPostList;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class SmartPostList extends WidgetBase
{
    public function get_icon(): string { return 'eicon-post-list elementrify-icon'; }
    public function get_name(): string { return 'elementrify-smart-post-list'; }
    public function get_admin_category(): string { return 'content'; }
    public function get_title(): string { return __('Smart Post List', 'elementrify'); }
    public function get_description(): string { return __('Display posts in lists', 'elementrify'); }
    public function get_brand_color(): string { return '#059669'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('posts_count', ['label' => __('Number of posts', 'elementrify'), 'type' => Controls_Manager::NUMBER, 'default' => 5]);
        $this->add_control('post_type', ['label' => __('Post type', 'elementrify'), 'type' => Controls_Manager::SELECT, 'default' => 'post', 'options' => ['post' => __('Posts', 'elementrify'), 'page' => __('Pages', 'elementrify')]]);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/smart-post-list/smart-post-list.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><ul class="elementrify-smart-post-list"><li><?php echo esc_html__('Post list placeholder (posts: ', 'elementrify'); ?>{{{ settings.posts_count }}})</li></ul><?php
    }
}
