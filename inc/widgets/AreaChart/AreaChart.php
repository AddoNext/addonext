<?php
namespace Elementrify\Widgets\AreaChart;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;

defined('ABSPATH') or exit;

class AreaChart extends WidgetBase
{
    public function get_icon(): string { return 'eicon-chart-area elementrify-icon'; }
    public function get_name(): string { return 'elementrify-area-chart'; }
    public function get_admin_category(): string { return 'data'; }
    public function get_title(): string { return __('Area Chart', 'elementrify'); }
    public function get_description(): string { return __('Show area charts', 'elementrify'); }
    public function get_brand_color(): string { return '#0d9488'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $r = new Repeater();
        $r->add_control('label', ['label' => __('Label', 'elementrify'), 'type' => Controls_Manager::TEXT]);
        $r->add_control('value', ['label' => __('Value', 'elementrify'), 'type' => Controls_Manager::NUMBER, 'default' => 0]);
        $this->add_control('title', ['label' => __('Chart title', 'elementrify'), 'type' => Controls_Manager::TEXT]);
        $this->add_control('data', ['label' => __('Data', 'elementrify'), 'type' => Controls_Manager::REPEATER, 'fields' => $r->get_controls(), 'default' => [], 'title_field' => '{{{ label }}}']);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/area-chart/area-chart.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-area-chart"><# if (settings.title) { #><h4>{{{ settings.title }}}</h4><# } #><div class="elementrify-area-chart__placeholder"><?php echo esc_html__('Area chart placeholder', 'elementrify'); ?></div></div><?php
    }
}
