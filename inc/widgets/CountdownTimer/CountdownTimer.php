<?php
namespace Elementrify\Widgets\CountdownTimer;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class CountdownTimer extends WidgetBase
{
    public function get_icon(): string { return 'eicon-countdown elementrify-icon'; }
    public function get_name(): string { return 'elementrify-countdown-timer'; }
    public function get_admin_category(): string { return 'creative'; }
    public function get_title(): string { return __('Countdown Timer', 'elementrify'); }
    public function get_description(): string { return __('Event countdown displays', 'elementrify'); }
    public function get_brand_color(): string { return '#dc2626'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('due_date', ['label' => __('Due date', 'elementrify'), 'type' => Controls_Manager::DATE_TIME, 'default' => date('Y-m-d H:i', strtotime('+1 month'))]);
        $this->add_control('labels', ['label' => __('Show labels', 'elementrify'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes']);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/countdown-timer/countdown-timer.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-countdown" data-due="{{ settings.due_date }}"><span class="elementrify-countdown__segment"><span class="elementrify-countdown__value">0</span><# if (settings.labels === 'yes') { #><span class="elementrify-countdown__label"><?php echo esc_html__('Days', 'elementrify'); ?></span><# } #></span></div><?php
    }
}
