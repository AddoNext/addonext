<?php
namespace Elementrify\Widgets\NotificationBar;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class NotificationBar extends WidgetBase
{
    public function get_icon(): string { return 'eicon-alert elementrify-icon'; }
    public function get_name(): string { return 'elementrify-notification-bar'; }
    public function get_admin_category(): string { return 'marketing'; }
    public function get_title(): string { return __('Notification Bar', 'elementrify'); }
    public function get_description(): string { return __('Top announcement bars', 'elementrify'); }
    public function get_brand_color(): string { return '#f59e0b'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('message', ['label' => __('Message', 'elementrify'), 'type' => Controls_Manager::TEXTAREA, 'default' => __('Announcement message', 'elementrify')]);
        $this->add_control('link_url', ['label' => __('Link URL', 'elementrify'), 'type' => Controls_Manager::URL]);
        $this->add_control('link_text', ['label' => __('Link text', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => '']);
        $this->add_control('dismissible', ['label' => __('Dismissible', 'elementrify'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes']);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/notification-bar/notification-bar.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-notification-bar"><span class="elementrify-notification-bar__message">{{{ settings.message }}}</span><# if (settings.link_url && settings.link_url.url) { #><a href="{{ settings.link_url.url }}">{{{ settings.link_text }}}</a><# } #><# if (settings.dismissible === 'yes') { #><button type="button" class="elementrify-notification-bar__close" aria-label="Close">&times;</button><# } #></div><?php
    }
}
