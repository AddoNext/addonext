<?php
namespace Elementrify\Widgets\LogoShowcase;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;

defined('ABSPATH') or exit;

class LogoShowcase extends WidgetBase
{
    public function get_icon(): string { return 'eicon-logo elementrify-icon'; }
    public function get_name(): string { return 'elementrify-logo-showcase'; }
    public function get_admin_category(): string { return 'marketing'; }
    public function get_title(): string { return __('Logo Showcase', 'elementrify'); }
    public function get_description(): string { return __('Client logos carousel', 'elementrify'); }
    public function get_brand_color(): string { return '#64748b'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $r = new Repeater();
        $r->add_control('logo', ['label' => __('Logo', 'elementrify'), 'type' => Controls_Manager::MEDIA]);
        $r->add_control('link', ['label' => __('Link', 'elementrify'), 'type' => Controls_Manager::URL]);
        $r->add_control('alt', ['label' => __('Alt text', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => '']);
        $this->add_control('logos', ['label' => __('Logos', 'elementrify'), 'type' => Controls_Manager::REPEATER, 'fields' => $r->get_controls(), 'default' => [], 'title_field' => '{{{ alt }}}']);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/logo-showcase/logo-showcase.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-logo-showcase"><# _.each(settings.logos, function(item) { #><div class="elementrify-logo-showcase__item"><# if (item.link && item.link.url) { #><a href="{{ item.link.url }}"><# } #><# if (item.logo && item.logo.url) { #><img src="{{ item.logo.url }}" alt="{{ item.alt }}"><# } #><# if (item.link && item.link.url) { #></a><# } #></div><# }); #></div><?php
    }
}
