<?php
namespace Elementrify\Widgets\OffCanvasContent;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class OffCanvasContent extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-sidebar elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-off-canvas-content';
    }

    public function get_admin_category(): string
    {
        return 'content';
    }

    public function get_title(): string
    {
        return __('Off-canvas Content', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Slide-out sidebar panels', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#4f46e5';
    }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('trigger_text', ['label' => __('Trigger text', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Open panel', 'elementrify')]);
        $this->add_control('panel_content', ['label' => __('Panel content', 'elementrify'), 'type' => Controls_Manager::WYSIWYG, 'default' => __('Add content for the slide-out panel.', 'elementrify')]);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/off-canvas-content/off-canvas-content.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-off-canvas"><button type="button" class="elementrify-off-canvas__trigger">{{{ settings.trigger_text }}}</button><div class="elementrify-off-canvas__panel">{{{ settings.panel_content }}}</div></div><?php
    }
}
