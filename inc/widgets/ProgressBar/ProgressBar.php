<?php
namespace Elementrify\Widgets\ProgressBar;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class ProgressBar extends WidgetBase
{
    public function get_icon(): string { return 'eicon-bar-chart elementrify-icon'; }
    public function get_name(): string { return 'elementrify-progress-bar'; }
    public function get_admin_category(): string { return 'creative'; }
    public function get_title(): string { return __('Progress Bar', 'elementrify'); }
    public function get_description(): string { return __('Animated progress indicators', 'elementrify'); }
    public function get_brand_color(): string { return '#2563eb'; }

    public function get_style_depends(): array { return ['elementrify-progress-bar']; }
    public function enqueue_assets(): void { wp_register_style('elementrify-progress-bar', ELEMENTRIFY_URL . 'assets/css/progress-bar.css', [], ELEMENTRIFY_VERSION); }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('title', ['label' => __('Title', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Skill', 'elementrify')]);
        $this->add_control('percent', ['label' => __('Percentage', 'elementrify'), 'type' => Controls_Manager::SLIDER, 'size_units' => ['%'], 'range' => ['%' => ['min' => 0, 'max' => 100]], 'default' => ['unit' => '%', 'size' => 70]]);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/progress-bar/progress-bar.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-progress-bar"><span class="elementrify-progress-bar__title">{{{ settings.title }}}</span><div class="elementrify-progress-bar__track"><div class="elementrify-progress-bar__fill" style="width: {{ settings.percent.size }}%"></div></div><span class="elementrify-progress-bar__percent">{{{ settings.percent.size }}}%</span></div><?php
    }
}
