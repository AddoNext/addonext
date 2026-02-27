<?php
namespace Elementrify\Widgets\ImageHotspot;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;

defined('ABSPATH') or exit;

class ImageHotspot extends WidgetBase
{
    public function get_icon(): string { return 'eicon-image-hotspot elementrify-icon'; }
    public function get_name(): string { return 'elementrify-image-hotspot'; }
    public function get_admin_category(): string { return 'creative'; }
    public function get_title(): string { return __('Image Hotspot', 'elementrify'); }
    public function get_description(): string { return __('Interactive image markers', 'elementrify'); }
    public function get_brand_color(): string { return '#ea580c'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('image', ['label' => __('Image', 'elementrify'), 'type' => Controls_Manager::MEDIA]);
        $r = new Repeater();
        $r->add_control('x', ['label' => __('X %', 'elementrify'), 'type' => Controls_Manager::SLIDER, 'size_units' => ['%'], 'range' => ['%' => ['min' => 0, 'max' => 100]], 'default' => ['size' => 50]]);
        $r->add_control('y', ['label' => __('Y %', 'elementrify'), 'type' => Controls_Manager::SLIDER, 'size_units' => ['%'], 'range' => ['%' => ['min' => 0, 'max' => 100]], 'default' => ['size' => 50]]);
        $r->add_control('tooltip', ['label' => __('Tooltip', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Hotspot', 'elementrify')]);
        $this->add_control('hotspots', ['label' => __('Hotspots', 'elementrify'), 'type' => Controls_Manager::REPEATER, 'fields' => $r->get_controls(), 'default' => [], 'title_field' => '{{{ tooltip }}}']);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/image-hotspot/image-hotspot.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-image-hotspot"><# if (settings.image && settings.image.url) { #><img src="{{ settings.image.url }}" alt=""><# } #><# _.each(settings.hotspots, function(h) { #><span class="elementrify-image-hotspot__point" style="left:{{ h.x.size }}%;top:{{ h.y.size }}%" title="{{ h.tooltip }}"></span><# }); #></div><?php
    }
}
