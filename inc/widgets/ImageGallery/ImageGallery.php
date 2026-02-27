<?php
namespace Elementrify\Widgets\ImageGallery;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;

defined('ABSPATH') or exit;

class ImageGallery extends WidgetBase
{
    public function get_icon(): string { return 'eicon-gallery-grid elementrify-icon'; }
    public function get_name(): string { return 'elementrify-image-gallery'; }
    public function get_admin_category(): string { return 'creative'; }
    public function get_title(): string { return __('Image Gallery', 'elementrify'); }
    public function get_description(): string { return __('Responsive image galleries', 'elementrify'); }
    public function get_brand_color(): string { return '#0d9488'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $r = new Repeater();
        $r->add_control('image', ['label' => __('Image', 'elementrify'), 'type' => Controls_Manager::MEDIA]);
        $r->add_control('caption', ['label' => __('Caption', 'elementrify'), 'type' => Controls_Manager::TEXT]);
        $this->add_control('gallery', ['label' => __('Images', 'elementrify'), 'type' => Controls_Manager::REPEATER, 'fields' => $r->get_controls(), 'default' => [], 'title_field' => '{{{ caption }}}']);
        $this->add_control('columns', ['label' => __('Columns', 'elementrify'), 'type' => Controls_Manager::NUMBER, 'min' => 1, 'max' => 6, 'default' => 3]);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/image-gallery/image-gallery.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-image-gallery" style="grid-template-columns: repeat({{ settings.columns }}, 1fr)"><# _.each(settings.gallery, function(item) { #><div class="elementrify-image-gallery__item"><# if (item.image && item.image.url) { #><img src="{{ item.image.url }}" alt="{{ item.caption }}"><# } #></div><# }); #></div><?php
    }
}
