<?php
namespace Elementrify\Widgets\CallToAction;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class CallToAction extends WidgetBase
{
    public function get_icon(): string { return 'eicon-call-to-action elementrify-icon'; }
    public function get_name(): string { return 'elementrify-call-to-action'; }
    public function get_admin_category(): string { return 'marketing'; }
    public function get_title(): string { return __('Call to Action', 'elementrify'); }
    public function get_description(): string { return __('Conversion focused CTAs', 'elementrify'); }
    public function get_brand_color(): string { return '#2563eb'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('title', ['label' => __('Title', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Ready to get started?', 'elementrify')]);
        $this->add_control('description', ['label' => __('Description', 'elementrify'), 'type' => Controls_Manager::TEXTAREA, 'default' => '']);
        $this->add_control('button_text', ['label' => __('Button text', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Get Started', 'elementrify')]);
        $this->add_control('button_url', ['label' => __('Button URL', 'elementrify'), 'type' => Controls_Manager::URL]);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/call-to-action/call-to-action.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-cta"><h3 class="elementrify-cta__title">{{{ settings.title }}}</h3><p class="elementrify-cta__desc">{{{ settings.description }}}</p><a href="{{ settings.button_url.url }}" class="elementrify-cta__button">{{{ settings.button_text }}}</a></div><?php
    }
}
