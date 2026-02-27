<?php
namespace Elementrify\Widgets\HoverBox;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class HoverBox extends WidgetBase
{
    public function get_icon(): string { return 'eicon-hypster elementrify-icon'; }
    public function get_name(): string { return 'elementrify-hover-box'; }
    public function get_admin_category(): string { return 'creative'; }
    public function get_title(): string { return __('Hover Box', 'elementrify'); }
    public function get_description(): string { return __('Content reveal on hover', 'elementrify'); }
    public function get_brand_color(): string { return '#059669'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('front_content', ['label' => __('Front', 'elementrify'), 'type' => Controls_Manager::TEXTAREA, 'default' => __('Front content', 'elementrify')]);
        $this->add_control('back_content', ['label' => __('Back (on hover)', 'elementrify'), 'type' => Controls_Manager::TEXTAREA, 'default' => __('Back content', 'elementrify')]);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/hover-box/hover-box.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-hover-box"><div class="elementrify-hover-box__front">{{{ settings.front_content }}}</div><div class="elementrify-hover-box__back">{{{ settings.back_content }}}</div></div><?php
    }
}
