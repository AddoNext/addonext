<?php
namespace Elementrify\Widgets\AnimatedCounter;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class AnimatedCounter extends WidgetBase
{
    public function get_icon(): string { return 'eicon-counter elementrify-icon'; }
    public function get_name(): string { return 'elementrify-animated-counter'; }
    public function get_admin_category(): string { return 'creative'; }
    public function get_title(): string { return __('Animated Counter', 'elementrify'); }
    public function get_description(): string { return __('Count up number animations', 'elementrify'); }
    public function get_brand_color(): string { return '#059669'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('number', ['label' => __('Number', 'elementrify'), 'type' => Controls_Manager::NUMBER, 'default' => 100]);
        $this->add_control('prefix', ['label' => __('Prefix', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => '']);
        $this->add_control('suffix', ['label' => __('Suffix', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => '']);
        $this->add_control('title', ['label' => __('Title', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => '']);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/animated-counter/animated-counter.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-animated-counter"><span class="elementrify-animated-counter__number">{{{ settings.prefix }}}{{{ settings.number }}}{{{ settings.suffix }}}</span><# if (settings.title) { #><span class="elementrify-animated-counter__title">{{{ settings.title }}}</span><# } #></div><?php
    }
}
