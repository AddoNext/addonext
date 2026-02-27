<?php
namespace Elementrify\Widgets\Parallax;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class Parallax extends WidgetBase
{
    public function get_icon(): string { return 'eicon-parallax elementrify-icon'; }
    public function get_name(): string { return 'elementrify-parallax'; }
    public function get_admin_category(): string { return 'creative'; }
    public function get_title(): string { return __('Parallax', 'elementrify'); }
    public function get_description(): string { return __('Parallax scroll effect', 'elementrify'); }
    public function get_brand_color(): string { return '#0d9488'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('image', ['label' => __('Background image', 'elementrify'), 'type' => Controls_Manager::MEDIA]);
        $this->add_control('content', ['label' => __('Content', 'elementrify'), 'type' => Controls_Manager::WYSIWYG, 'default' => __('Parallax content', 'elementrify')]);
        $this->add_control('speed', ['label' => __('Parallax speed', 'elementrify'), 'type' => Controls_Manager::SLIDER, 'range' => ['px' => ['min' => 0, 'max' => 2, 'step' => 0.1]], 'default' => ['size' => 0.5]]);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/parallax/parallax.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-parallax" data-speed="{{ settings.speed.size }}"><# if (settings.image && settings.image.url) { #><div class="elementrify-parallax__bg" style="background-image:url({{ settings.image.url }})"></div><# } #><div class="elementrify-parallax__content">{{{ settings.content }}}</div></div><?php
    }
}
