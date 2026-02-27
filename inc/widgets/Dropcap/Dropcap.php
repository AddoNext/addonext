<?php
namespace Elementrify\Widgets\Dropcap;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined('ABSPATH') or exit;

class Dropcap extends WidgetBase
{
    public function get_icon(): string { return 'eicon-paragraph elementrify-icon'; }
    public function get_name(): string { return 'elementrify-dropcap'; }
    public function get_admin_category(): string { return 'content'; }
    public function get_title(): string { return __('Dropcap', 'elementrify'); }
    public function get_description(): string { return __('Styled first letter', 'elementrify'); }
    public function get_brand_color(): string { return '#b45309'; }

    public function get_style_depends(): array { return ['elementrify-dropcap']; }
    public function enqueue_assets(): void { wp_register_style('elementrify-dropcap', ELEMENTRIFY_URL . 'assets/css/dropcap.css', [], ELEMENTRIFY_VERSION); }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('text', ['label' => __('Paragraph', 'elementrify'), 'type' => Controls_Manager::TEXTAREA, 'default' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'elementrify')]);
        $this->end_controls_section();
        $this->start_controls_section('section_style', ['label' => __('Style', 'elementrify'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_control('dropcap_color', ['label' => __('Dropcap color', 'elementrify'), 'type' => Controls_Manager::COLOR, 'default' => '#b45309', 'selectors' => ['{{WRAPPER}} .elementrify-dropcap__letter' => 'color: {{VALUE}};']]);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/dropcap/dropcap.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><p class="elementrify-dropcap"><# var t = settings.text || ''; var first = t.charAt(0); var rest = t.slice(1); #><span class="elementrify-dropcap__letter">{{{ first }}}</span>{{{ rest }}}</p><?php
    }
}
