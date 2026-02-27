<?php
namespace Elementrify\Widgets\PromoBox;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class PromoBox extends WidgetBase
{
    public function get_icon(): string { return 'eicon-info-box elementrify-icon'; }
    public function get_name(): string { return 'elementrify-promo-box'; }
    public function get_admin_category(): string { return 'marketing'; }
    public function get_title(): string { return __('Promo Box', 'elementrify'); }
    public function get_description(): string { return __('Highlight offers', 'elementrify'); }
    public function get_brand_color(): string { return '#ea580c'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('title', ['label' => __('Title', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Special offer', 'elementrify')]);
        $this->add_control('content', ['label' => __('Content', 'elementrify'), 'type' => Controls_Manager::TEXTAREA, 'default' => __('Promo content here.', 'elementrify')]);
        $this->add_control('button_text', ['label' => __('Button text', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => '']);
        $this->add_control('button_url', ['label' => __('Button URL', 'elementrify'), 'type' => Controls_Manager::URL]);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/promo-box/promo-box.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-promo-box"><h4>{{{ settings.title }}}</h4><p>{{{ settings.content }}}</p><# if (settings.button_text && settings.button_url && settings.button_url.url) { #><a href="{{ settings.button_url.url }}">{{{ settings.button_text }}}</a><# } #></div><?php
    }
}
