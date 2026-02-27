<?php
namespace Elementrify\Widgets\ContentToggle;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class ContentToggle extends WidgetBase
{
    public function get_icon(): string { return 'eicon-toggle elementrify-icon'; }
    public function get_name(): string { return 'elementrify-content-toggle'; }
    public function get_admin_category(): string { return 'content'; }
    public function get_title(): string { return __('Content Toggle', 'elementrify'); }
    public function get_description(): string { return __('Switch between content blocks', 'elementrify'); }
    public function get_brand_color(): string { return '#0d9488'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('primary_label', ['label' => __('Primary label', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Option A', 'elementrify')]);
        $this->add_control('primary_content', ['label' => __('Primary content', 'elementrify'), 'type' => Controls_Manager::WYSIWYG, 'default' => __('Content for option A.', 'elementrify')]);
        $this->add_control('secondary_label', ['label' => __('Secondary label', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Option B', 'elementrify')]);
        $this->add_control('secondary_content', ['label' => __('Secondary content', 'elementrify'), 'type' => Controls_Manager::WYSIWYG, 'default' => __('Content for option B.', 'elementrify')]);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/content-toggle/content-toggle.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-content-toggle"><div class="elementrify-content-toggle__labels"><span class="elementrify-content-toggle__label is-active">{{{ settings.primary_label }}}</span><span class="elementrify-content-toggle__label">{{{ settings.secondary_label }}}</span></div><div class="elementrify-content-toggle__panels"><div class="elementrify-content-toggle__panel is-active">{{{ settings.primary_content }}}</div><div class="elementrify-content-toggle__panel">{{{ settings.secondary_content }}}</div></div></div><?php
    }
}
