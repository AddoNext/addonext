<?php
namespace Elementrify\Widgets\BarChart;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;

defined('ABSPATH') or exit;

class BarChart extends WidgetBase
{
    public function get_icon(): string { return 'eicon-bar-chart elementrify-icon'; }
    public function get_name(): string { return 'elementrify-bar-chart'; }
    public function get_admin_category(): string { return 'data'; }
    public function get_title(): string { return __('Bar Chart', 'elementrify'); }
    public function get_description(): string { return __('Interactive bar charts', 'elementrify'); }
    public function get_brand_color(): string { return '#2563eb'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $r = new Repeater();
        $r->add_control('label', ['label' => __('Label', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => '']);
        $r->add_control('value', ['label' => __('Value', 'elementrify'), 'type' => Controls_Manager::NUMBER, 'default' => 0]);
        $this->add_control('title', ['label' => __('Chart title', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => '']);
        $this->add_control('data', ['label' => __('Data', 'elementrify'), 'type' => Controls_Manager::REPEATER, 'fields' => $r->get_controls(), 'default' => [['label' => 'A', 'value' => 30], ['label' => 'B', 'value' => 50]], 'title_field' => '{{{ label }}}']);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/bar-chart/bar-chart.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-bar-chart"><# if (settings.title) { #><h4>{{{ settings.title }}}</h4><# } #><div class="elementrify-bar-chart__bars"><# _.each(settings.data, function(d) { #><div class="elementrify-bar-chart__bar-wrap"><div class="elementrify-bar-chart__bar" style="height: {{ d.value }}%"></div><span class="elementrify-bar-chart__label">{{{ d.label }}}</span></div><# }); #></div></div><?php
    }
}
