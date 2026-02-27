<?php
namespace Elementrify\Widgets\ComparisonTable;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;

defined('ABSPATH') or exit;

class ComparisonTable extends WidgetBase
{
    public function get_icon(): string { return 'eicon-table elementrify-icon'; }
    public function get_name(): string { return 'elementrify-comparison-table'; }
    public function get_admin_category(): string { return 'marketing'; }
    public function get_title(): string { return __('Comparison Table', 'elementrify'); }
    public function get_description(): string { return __('Feature comparison', 'elementrify'); }
    public function get_brand_color(): string { return '#4f46e5'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $r = new Repeater();
        $r->add_control('feature', ['label' => __('Feature', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => '']);
        $r->add_control('plan_a', ['label' => __('Plan A', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => '']);
        $r->add_control('plan_b', ['label' => __('Plan B', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => '']);
        $this->add_control('header_plan_a', ['label' => __('Plan A title', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Plan A', 'elementrify')]);
        $this->add_control('header_plan_b', ['label' => __('Plan B title', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Plan B', 'elementrify')]);
        $this->add_control('rows', ['label' => __('Rows', 'elementrify'), 'type' => Controls_Manager::REPEATER, 'fields' => $r->get_controls(), 'default' => [], 'title_field' => '{{{ feature }}}']);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/comparison-table/comparison-table.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><table class="elementrify-comparison-table"><thead><tr><th></th><th>{{{ settings.header_plan_a }}}</th><th>{{{ settings.header_plan_b }}}</th></tr></thead><tbody><# _.each(settings.rows, function(row) { #><tr><td>{{{ row.feature }}}</td><td>{{{ row.plan_a }}}</td><td>{{{ row.plan_b }}}</td></tr><# }); #></tbody></table><?php
    }
}
