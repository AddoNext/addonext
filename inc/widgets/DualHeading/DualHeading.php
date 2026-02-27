<?php
namespace Elementrify\Widgets\DualHeading;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class DualHeading extends WidgetBase
{
    public function get_icon(): string { return 'eicon-heading elementrify-icon'; }
    public function get_name(): string { return 'elementrify-dual-heading'; }
    public function get_admin_category(): string { return 'creative'; }
    public function get_title(): string { return __('Dual Heading', 'elementrify'); }
    public function get_description(): string { return __('Split heading styles', 'elementrify'); }
    public function get_brand_color(): string { return '#1d4ed8'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('first_line', ['label' => __('First line', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('First', 'elementrify')]);
        $this->add_control('second_line', ['label' => __('Second line', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Second', 'elementrify')]);
        $this->add_control('tag', ['label' => __('HTML tag', 'elementrify'), 'type' => Controls_Manager::SELECT, 'default' => 'h2', 'options' => ['h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3', 'h4' => 'H4']]);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/dual-heading/dual-heading.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><{{{ settings.tag }}} class="elementrify-dual-heading"><span class="elementrify-dual-heading__first">{{{ settings.first_line }}}</span> <span class="elementrify-dual-heading__second">{{{ settings.second_line }}}</span></{{{ settings.tag }}}><?php
    }
}
