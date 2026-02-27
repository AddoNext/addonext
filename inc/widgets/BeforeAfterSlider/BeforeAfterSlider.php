<?php
namespace Elementrify\Widgets\BeforeAfterSlider;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class BeforeAfterSlider extends WidgetBase
{
    public function get_icon(): string { return 'eicon-image-before-after elementrify-icon'; }
    public function get_name(): string { return 'elementrify-before-after-slider'; }
    public function get_admin_category(): string { return 'creative'; }
    public function get_title(): string { return __('Before After Slider', 'elementrify'); }
    public function get_description(): string { return __('Image comparison slider', 'elementrify'); }
    public function get_brand_color(): string { return '#0d9488'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('image_before', ['label' => __('Before image', 'elementrify'), 'type' => Controls_Manager::MEDIA]);
        $this->add_control('image_after', ['label' => __('After image', 'elementrify'), 'type' => Controls_Manager::MEDIA]);
        $this->add_control('offset', ['label' => __('Initial offset %', 'elementrify'), 'type' => Controls_Manager::SLIDER, 'size_units' => ['%'], 'range' => ['%' => ['min' => 0, 'max' => 100]], 'default' => ['size' => 50]]);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/before-after-slider/before-after-slider.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-before-after"><div class="elementrify-before-after__before"><# if (settings.image_before && settings.image_before.url) { #><img src="{{ settings.image_before.url }}" alt="Before"><# } #></div><div class="elementrify-before-after__after"><# if (settings.image_after && settings.image_after.url) { #><img src="{{ settings.image_after.url }}" alt="After"><# } #></div></div><?php
    }
}
