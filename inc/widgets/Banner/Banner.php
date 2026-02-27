<?php
namespace Elementrify\Widgets\Banner;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class Banner extends WidgetBase
{
    public function get_icon(): string { return 'eicon-banner elementrify-icon'; }
    public function get_name(): string { return 'elementrify-banner'; }
    public function get_admin_category(): string { return 'marketing'; }
    public function get_title(): string { return __('Banner', 'elementrify'); }
    public function get_description(): string { return __('Promotional banner widget', 'elementrify'); }
    public function get_brand_color(): string { return '#dc2626'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('image', ['label' => __('Background image', 'elementrify'), 'type' => Controls_Manager::MEDIA]);
        $this->add_control('title', ['label' => __('Title', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Banner title', 'elementrify')]);
        $this->add_control('subtitle', ['label' => __('Subtitle', 'elementrify'), 'type' => Controls_Manager::TEXTAREA, 'default' => '']);
        $this->add_control('button_text', ['label' => __('Button text', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => '']);
        $this->add_control('button_url', ['label' => __('Button URL', 'elementrify'), 'type' => Controls_Manager::URL]);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/banner/banner.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-banner" <# if (settings.image && settings.image.url) { #>style="background-image:url({{ settings.image.url }})"<# } #>><div class="elementrify-banner__content"><h2 class="elementrify-banner__title">{{{ settings.title }}}</h2><p class="elementrify-banner__subtitle">{{{ settings.subtitle }}}</p><# if (settings.button_text && settings.button_url && settings.button_url.url) { #><a href="{{ settings.button_url.url }}" class="elementrify-banner__button">{{{ settings.button_text }}}</a><# } #></div></div><?php
    }
}
