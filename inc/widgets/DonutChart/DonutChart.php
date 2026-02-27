<?php
namespace Elementrify\Widgets\DonutChart;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;

defined('ABSPATH') or exit;

class DonutChart extends WidgetBase
{
    public function get_icon(): string { return 'eicon-circle-o elementrify-icon'; }
    public function get_name(): string { return 'elementrify-donut-chart'; }
    public function get_admin_category(): string { return 'data'; }
    public function get_title(): string { return __('Donut Chart', 'elementrify'); }
    public function get_description(): string { return __('Donut chart visualization', 'elementrify'); }
    public function get_brand_color(): string { return '#8b5cf6'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $r = new Repeater();
        $r->add_control('label', ['label' => __('Label', 'elementrify'), 'type' => Controls_Manager::TEXT]);
        $r->add_control('value', ['label' => __('Value', 'elementrify'), 'type' => Controls_Manager::NUMBER, 'default' => 0]);
        $this->add_control('data', ['label' => __('Data', 'elementrify'), 'type' => Controls_Manager::REPEATER, 'fields' => $r->get_controls(), 'default' => [], 'title_field' => '{{{ label }}}']);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/donut-chart/donut-chart.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-donut-chart"><div class="elementrify-donut-chart__placeholder"><?php echo esc_html__('Donut chart placeholder', 'elementrify'); ?></div></div><?php
    }
}
