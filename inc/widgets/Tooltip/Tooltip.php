<?php
namespace Elementrify\Widgets\Tooltip;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class Tooltip extends WidgetBase
{
    public function get_icon(): string { return 'eicon-info-circle elementrify-icon'; }
    public function get_name(): string { return 'elementrify-tooltip'; }
    public function get_admin_category(): string { return 'creative'; }
    public function get_title(): string { return __('Tooltip', 'elementrify'); }
    public function get_description(): string { return __('Interactive hover tooltips', 'elementrify'); }
    public function get_brand_color(): string { return '#64748b'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('trigger_text', ['label' => __('Trigger text', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Hover me', 'elementrify')]);
        $this->add_control('tooltip_text', ['label' => __('Tooltip text', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Tooltip content', 'elementrify')]);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/tooltip/tooltip.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><span class="elementrify-tooltip"><span class="elementrify-tooltip__trigger">{{{ settings.trigger_text }}}</span><span class="elementrify-tooltip__content">{{{ settings.tooltip_text }}}</span></span><?php
    }
}
