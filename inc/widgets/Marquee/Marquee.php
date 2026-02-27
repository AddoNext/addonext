<?php
namespace Elementrify\Widgets\Marquee;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class Marquee extends WidgetBase
{
    public function get_icon(): string { return 'eicon-animation-text elementrify-icon'; }
    public function get_name(): string { return 'elementrify-marquee'; }
    public function get_admin_category(): string { return 'creative'; }
    public function get_title(): string { return __('Marquee', 'elementrify'); }
    public function get_description(): string { return __('Scrolling text or images', 'elementrify'); }
    public function get_brand_color(): string { return '#0d9488'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('content', ['label' => __('Content', 'elementrify'), 'type' => Controls_Manager::TEXTAREA, 'default' => __('Scrolling text...', 'elementrify')]);
        $this->add_control('speed', ['label' => __('Speed', 'elementrify'), 'type' => Controls_Manager::SLIDER, 'range' => ['px' => ['min' => 1, 'max' => 20]], 'default' => ['size' => 5]]);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/marquee/marquee.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-marquee"><span class="elementrify-marquee__content">{{{ settings.content }}}</span></div><?php
    }
}
