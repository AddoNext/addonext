<?php
namespace Elementrify\Widgets\CircularProgress;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class CircularProgress extends WidgetBase
{
    public function get_icon(): string { return 'eicon-circle-o elementrify-icon'; }
    public function get_name(): string { return 'elementrify-circular-progress'; }
    public function get_admin_category(): string { return 'creative'; }
    public function get_title(): string { return __('Circular Progress', 'elementrify'); }
    public function get_description(): string { return __('Circular progress indicators', 'elementrify'); }
    public function get_brand_color(): string { return '#2563eb'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('percent', ['label' => __('Percentage', 'elementrify'), 'type' => Controls_Manager::SLIDER, 'size_units' => ['%'], 'range' => ['%' => ['min' => 0, 'max' => 100]], 'default' => ['size' => 75]]);
        $this->add_control('title', ['label' => __('Title', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => '']);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/circular-progress/circular-progress.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-circular-progress"><svg viewBox="0 0 36 36"><circle class="elementrify-circular-progress__bg" cx="18" cy="18" r="16"/><circle class="elementrify-circular-progress__fill" cx="18" cy="18" r="16" stroke-dasharray="{{ settings.percent.size }}, 100"/></svg><span class="elementrify-circular-progress__value">{{{ settings.percent.size }}}%</span><# if (settings.title) { #><span class="elementrify-circular-progress__title">{{{ settings.title }}}</span><# } #></div><?php
    }
}
