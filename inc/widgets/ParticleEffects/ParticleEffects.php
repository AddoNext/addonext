<?php
namespace Elementrify\Widgets\ParticleEffects;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class ParticleEffects extends WidgetBase
{
    public function get_icon(): string { return 'eicon-animation elementrify-icon'; }
    public function get_name(): string { return 'elementrify-particle-effects'; }
    public function get_admin_category(): string { return 'creative'; }
    public function get_title(): string { return __('Particle Effects', 'elementrify'); }
    public function get_description(): string { return __('Animated particle backgrounds', 'elementrify'); }
    public function get_brand_color(): string { return '#8b5cf6'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('density', ['label' => __('Density', 'elementrify'), 'type' => Controls_Manager::SLIDER, 'range' => ['px' => ['min' => 10, 'max' => 200]], 'default' => ['size' => 80]]);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/particle-effects/particle-effects.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-particle-effects"><canvas class="elementrify-particle-effects__canvas"></canvas></div><?php
    }
}
