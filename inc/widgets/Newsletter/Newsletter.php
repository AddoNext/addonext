<?php
namespace Elementrify\Widgets\Newsletter;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class Newsletter extends WidgetBase
{
    public function get_icon(): string { return 'eicon-email-field elementrify-icon'; }
    public function get_name(): string { return 'elementrify-newsletter'; }
    public function get_admin_category(): string { return 'marketing'; }
    public function get_title(): string { return __('Newsletter', 'elementrify'); }
    public function get_description(): string { return __('Email signup block', 'elementrify'); }
    public function get_brand_color(): string { return '#2563eb'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('title', ['label' => __('Title', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Subscribe to our newsletter', 'elementrify')]);
        $this->add_control('description', ['label' => __('Description', 'elementrify'), 'type' => Controls_Manager::TEXTAREA, 'default' => '']);
        $this->add_control('button_text', ['label' => __('Button text', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Subscribe', 'elementrify')]);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/newsletter/newsletter.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-newsletter"><h4>{{{ settings.title }}}</h4><p>{{{ settings.description }}}</p><form><input type="email" placeholder="<?php echo esc_attr__('Your email', 'elementrify'); ?>"><button type="submit">{{{ settings.button_text }}}</button></form></div><?php
    }
}
