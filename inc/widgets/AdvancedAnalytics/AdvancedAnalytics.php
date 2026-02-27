<?php
namespace Elementrify\Widgets\AdvancedAnalytics;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class AdvancedAnalytics extends WidgetBase
{
    public function get_icon(): string { return 'eicon-analytics elementrify-icon'; }
    public function get_name(): string { return 'elementrify-advanced-analytics'; }
    public function get_admin_category(): string { return 'data'; }
    public function get_title(): string { return __('Advanced Analytics', 'elementrify'); }
    public function get_description(): string { return __('Real-time data analytics', 'elementrify'); }
    public function get_brand_color(): string { return '#6366f1'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('title', ['label' => __('Title', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Analytics', 'elementrify')]);
        $this->add_control('data_source', ['label' => __('Data source', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => '']);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/advanced-analytics/advanced-analytics.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-advanced-analytics"><h4>{{{ settings.title }}}</h4><div class="elementrify-advanced-analytics__placeholder"><?php echo esc_html__('Analytics placeholder. Connect data source.', 'elementrify'); ?></div></div><?php
    }
}
