<?php
namespace Elementrify\Widgets\ImageCarousel;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;

defined('ABSPATH') or exit;

class ImageCarousel extends WidgetBase
{
    public function get_icon(): string { return 'eicon-slides elementrify-icon'; }
    public function get_name(): string { return 'elementrify-image-carousel'; }
    public function get_admin_category(): string { return 'creative'; }
    public function get_title(): string { return __('Image Carousel', 'elementrify'); }
    public function get_description(): string { return __('Stunning image slider widgets', 'elementrify'); }
    public function get_brand_color(): string { return '#0891b2'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $r = new Repeater();
        $r->add_control('image', ['label' => __('Image', 'elementrify'), 'type' => Controls_Manager::MEDIA]);
        $r->add_control('caption', ['label' => __('Caption', 'elementrify'), 'type' => Controls_Manager::TEXT]);
        $this->add_control('slides', ['label' => __('Slides', 'elementrify'), 'type' => Controls_Manager::REPEATER, 'fields' => $r->get_controls(), 'default' => [], 'title_field' => '{{{ caption }}}']);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/image-carousel/image-carousel.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-image-carousel"><# _.each(settings.slides, function(slide) { #><div class="elementrify-image-carousel__slide"><# if (slide.image && slide.image.url) { #><img src="{{ slide.image.url }}" alt="{{ slide.caption }}"><# } #></div><# }); #></div><?php
    }
}
