<?php
namespace Elementrify\Widgets\LottieAnimation;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class LottieAnimation extends WidgetBase
{
    public function get_icon(): string { return 'eicon-lottie elementrify-icon'; }
    public function get_name(): string { return 'elementrify-lottie-animation'; }
    public function get_admin_category(): string { return 'creative'; }
    public function get_title(): string { return __('Lottie Animation', 'elementrify'); }
    public function get_description(): string { return __('JSON-based animations', 'elementrify'); }
    public function get_brand_color(): string { return '#06b6d4'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('source', ['label' => __('JSON URL', 'elementrify'), 'type' => Controls_Manager::URL, 'placeholder' => 'https://...']);
        $this->add_control('loop', ['label' => __('Loop', 'elementrify'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes']);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/lottie-animation/lottie-animation.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-lottie" data-src="{{ settings.source.url }}" data-loop="{{ settings.loop }}"><?php echo esc_html__('Lottie placeholder', 'elementrify'); ?></div><?php
    }
}
