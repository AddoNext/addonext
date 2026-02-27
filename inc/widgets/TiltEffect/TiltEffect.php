<?php
namespace Elementrify\Widgets\TiltEffect;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class TiltEffect extends WidgetBase
{
    public function get_icon(): string { return 'eicon-tilt elementrify-icon'; }
    public function get_name(): string { return 'elementrify-tilt-effect'; }
    public function get_admin_category(): string { return 'creative'; }
    public function get_title(): string { return __('Tilt Effect', 'elementrify'); }
    public function get_description(): string { return __('3D tilt on hover', 'elementrify'); }
    public function get_brand_color(): string { return '#8b5cf6'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('content', ['label' => __('Content', 'elementrify'), 'type' => Controls_Manager::WYSIWYG, 'default' => __('Tilt content', 'elementrify')]);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/tilt-effect/tilt-effect.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-tilt-effect">{{{ settings.content }}}</div><?php
    }
}
