<?php
namespace Elementrify\Widgets\TableOfContents;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class TableOfContents extends WidgetBase
{
    public function get_icon(): string { return 'eicon-table-of-contents elementrify-icon'; }
    public function get_name(): string { return 'elementrify-table-of-contents'; }
    public function get_admin_category(): string { return 'content'; }
    public function get_title(): string { return __('Table of Contents', 'elementrify'); }
    public function get_description(): string { return __('Page navigation list', 'elementrify'); }
    public function get_brand_color(): string { return '#0f766e'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('title', ['label' => __('Title', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Contents', 'elementrify')]);
        $this->add_control('headings_selector', ['label' => __('Headings selector', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => 'h2, h3', 'description' => __('CSS selector for headings to include, e.g. h2, h3', 'elementrify')]);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/table-of-contents/table-of-contents.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><nav class="elementrify-toc"><h3 class="elementrify-toc__title">{{{ settings.title }}}</h3><ul class="elementrify-toc__list"><li><?php echo esc_html__('Auto-generated from page headings', 'elementrify'); ?></li></ul></nav><?php
    }
}
