<?php
namespace Elementrify\Widgets\AuthorBox;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class AuthorBox extends WidgetBase
{
    public function get_icon(): string { return 'eicon-person elementrify-icon'; }
    public function get_name(): string { return 'elementrify-author-box'; }
    public function get_admin_category(): string { return 'marketing'; }
    public function get_title(): string { return __('Author Box', 'elementrify'); }
    public function get_description(): string { return __('Author bio block', 'elementrify'); }
    public function get_brand_color(): string { return '#64748b'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('avatar', ['label' => __('Avatar', 'elementrify'), 'type' => Controls_Manager::MEDIA]);
        $this->add_control('name', ['label' => __('Name', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Author name', 'elementrify')]);
        $this->add_control('bio', ['label' => __('Bio', 'elementrify'), 'type' => Controls_Manager::TEXTAREA, 'default' => '']);
        $this->add_control('link', ['label' => __('Profile URL', 'elementrify'), 'type' => Controls_Manager::URL]);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/author-box/author-box.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-author-box"><# if (settings.avatar && settings.avatar.url) { #><img src="{{ settings.avatar.url }}" alt="{{ settings.name }}" class="elementrify-author-box__avatar"><# } #><div class="elementrify-author-box__content"><h4 class="elementrify-author-box__name">{{{ settings.name }}}</h4><p class="elementrify-author-box__bio">{{{ settings.bio }}}</p></div></div><?php
    }
}
