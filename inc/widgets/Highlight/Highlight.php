<?php
namespace Elementrify\Widgets\Highlight;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class Highlight extends WidgetBase
{
    public function get_icon(): string { return 'eicon-editor-underline elementrify-icon'; }
    public function get_name(): string { return 'elementrify-highlight'; }
    public function get_admin_category(): string { return 'content'; }
    public function get_title(): string { return __('Highlight', 'elementrify'); }
    public function get_description(): string { return __('Highlighted text span', 'elementrify'); }
    public function get_brand_color(): string { return '#eab308'; }

    public function get_style_depends(): array { return ['elementrify-highlight']; }
    public function enqueue_assets(): void { wp_register_style('elementrify-highlight', ELEMENTRIFY_URL . 'assets/css/highlight.css', [], ELEMENTRIFY_VERSION); }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('text', ['label' => __('Text', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Highlighted text', 'elementrify')]);
        $this->end_controls_section();
        $this->start_controls_section('section_style', ['label' => __('Style', 'elementrify'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_control('bg_color', ['label' => __('Background', 'elementrify'), 'type' => Controls_Manager::COLOR, 'default' => '#fef08a', 'selectors' => ['{{WRAPPER}} .elementrify-highlight' => 'background-color: {{VALUE}};']]);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/highlight/highlight.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><span class="elementrify-highlight">{{{ settings.text }}}</span><?php
    }
}
