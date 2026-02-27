<?php
namespace Elementrify\Widgets\FlipBox;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class FlipBox extends WidgetBase
{
    public function get_icon(): string { return 'eicon-flip-box elementrify-icon'; }
    public function get_name(): string { return 'elementrify-flip-box'; }
    public function get_admin_category(): string { return 'creative'; }
    public function get_title(): string { return __('3D Flip Box', 'elementrify'); }
    public function get_description(): string { return __('Interactive flip animations', 'elementrify'); }
    public function get_brand_color(): string { return '#7c3aed'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('front_title', ['label' => __('Front title', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Front', 'elementrify')]);
        $this->add_control('front_description', ['label' => __('Front description', 'elementrify'), 'type' => Controls_Manager::TEXTAREA, 'default' => '']);
        $this->add_control('back_title', ['label' => __('Back title', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Back', 'elementrify')]);
        $this->add_control('back_description', ['label' => __('Back description', 'elementrify'), 'type' => Controls_Manager::TEXTAREA, 'default' => '']);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/flip-box/flip-box.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-flip-box"><div class="elementrify-flip-box__inner"><div class="elementrify-flip-box__front"><h3>{{{ settings.front_title }}}</h3><p>{{{ settings.front_description }}}</p></div><div class="elementrify-flip-box__back"><h3>{{{ settings.back_title }}}</h3><p>{{{ settings.back_description }}}</p></div></div></div><?php
    }
}
