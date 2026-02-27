<?php
namespace Elementrify\Widgets\SubscribeForm;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class SubscribeForm extends WidgetBase
{
    public function get_icon(): string { return 'eicon-mail elementrify-icon'; }
    public function get_name(): string { return 'elementrify-subscribe-form'; }
    public function get_admin_category(): string { return 'marketing'; }
    public function get_title(): string { return __('Subscribe Form', 'elementrify'); }
    public function get_description(): string { return __('Newsletter signup forms', 'elementrify'); }
    public function get_brand_color(): string { return '#dc2626'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('title', ['label' => __('Title', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Subscribe', 'elementrify')]);
        $this->add_control('placeholder', ['label' => __('Placeholder', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Your email', 'elementrify')]);
        $this->add_control('button_text', ['label' => __('Button text', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Subscribe', 'elementrify')]);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/subscribe-form/subscribe-form.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-subscribe-form"><# if (settings.title) { #><h4>{{{ settings.title }}}</h4><# } #><form><input type="email" placeholder="{{ settings.placeholder }}"><button type="submit">{{{ settings.button_text }}}</button></form></div><?php
    }
}
