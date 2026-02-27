<?php
namespace Elementrify\Widgets\ScrollAnimation;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class ScrollAnimation extends WidgetBase
{
    public function get_icon(): string { return 'eicon-scroll elementrify-icon'; }
    public function get_name(): string { return 'elementrify-scroll-animation'; }
    public function get_admin_category(): string { return 'creative'; }
    public function get_title(): string { return __('Scroll Animation', 'elementrify'); }
    public function get_description(): string { return __('Linear scroll animations', 'elementrify'); }
    public function get_brand_color(): string { return '#7c3aed'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('content', ['label' => __('Content', 'elementrify'), 'type' => Controls_Manager::WYSIWYG, 'default' => __('Animated on scroll.', 'elementrify')]);
        $this->add_control('animation', ['label' => __('Animation', 'elementrify'), 'type' => Controls_Manager::SELECT, 'default' => 'fadeInUp', 'options' => ['fadeIn' => __('Fade In', 'elementrify'), 'fadeInUp' => __('Fade In Up', 'elementrify'), 'fadeInDown' => __('Fade In Down', 'elementrify'), 'zoomIn' => __('Zoom In', 'elementrify')]]);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/scroll-animation/scroll-animation.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-scroll-animation elementrify-scroll-animation--{{{ settings.animation }}}">{{{ settings.content }}}</div><?php
    }
}
