<?php
namespace Elementrify\Widgets\AnimatedText;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class AnimatedText extends WidgetBase
{
    public function get_icon(): string { return 'eicon-animation-text elementrify-icon'; }
    public function get_name(): string { return 'elementrify-animated-text'; }
    public function get_admin_category(): string { return 'content'; }
    public function get_title(): string { return __('Animated Text', 'elementrify'); }
    public function get_description(): string { return __('Typewriter and reveal text', 'elementrify'); }
    public function get_brand_color(): string { return '#db2777'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('before_text', ['label' => __('Before text', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => '']);
        $this->add_control('animated_text', ['label' => __('Animated text', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Typewriter', 'elementrify')]);
        $this->add_control('after_text', ['label' => __('After text', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => '']);
        $this->add_control('animation_type', ['label' => __('Animation', 'elementrify'), 'type' => Controls_Manager::SELECT, 'default' => 'typewriter', 'options' => ['typewriter' => __('Typewriter', 'elementrify'), 'reveal' => __('Reveal', 'elementrify')]]);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/animated-text/animated-text.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><span class="elementrify-animated-text"><span class="elementrify-animated-text__before">{{{ settings.before_text }}}</span><span class="elementrify-animated-text__animated">{{{ settings.animated_text }}}</span><span class="elementrify-animated-text__after">{{{ settings.after_text }}}</span></span><?php
    }
}
