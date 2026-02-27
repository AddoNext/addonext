<?php
namespace Elementrify\Widgets\AnimatedGradient;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class AnimatedGradient extends WidgetBase
{
    public function get_icon(): string { return 'eicon-paint-tool elementrify-icon'; }
    public function get_name(): string { return 'elementrify-animated-gradient'; }
    public function get_admin_category(): string { return 'creative'; }
    public function get_title(): string { return __('Animated Gradient', 'elementrify'); }
    public function get_description(): string { return __('Moving gradient backgrounds', 'elementrify'); }
    public function get_brand_color(): string { return '#ec4899'; }

    public function get_style_depends(): array { return ['elementrify-animated-gradient']; }
    public function enqueue_assets(): void { wp_register_style('elementrify-animated-gradient', ELEMENTRIFY_URL . 'assets/css/animated-gradient.css', [], ELEMENTRIFY_VERSION); }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('content', ['label' => __('Content', 'elementrify'), 'type' => Controls_Manager::TEXTAREA, 'default' => '']);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/animated-gradient/animated-gradient.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-animated-gradient">{{{ settings.content }}}</div><?php
    }
}
