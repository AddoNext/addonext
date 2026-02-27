<?php
namespace Elementrify\Widgets\ContactForm;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class ContactForm extends WidgetBase
{
    public function get_icon(): string { return 'eicon-form-horizontal elementrify-icon'; }
    public function get_name(): string { return 'elementrify-contact-form'; }
    public function get_admin_category(): string { return 'marketing'; }
    public function get_title(): string { return __('Contact Form', 'elementrify'); }
    public function get_description(): string { return __('Advanced form builder', 'elementrify'); }
    public function get_brand_color(): string { return '#2563eb'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('submit_text', ['label' => __('Submit text', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Send', 'elementrify')]);
        $this->add_control('success_message', ['label' => __('Success message', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Thank you for your message.', 'elementrify')]);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/contact-form/contact-form.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><form class="elementrify-contact-form"><input type="text" name="name" placeholder="<?php echo esc_attr__('Name', 'elementrify'); ?>"><input type="email" name="email" placeholder="<?php echo esc_attr__('Email', 'elementrify'); ?>"><textarea name="message" placeholder="<?php echo esc_attr__('Message', 'elementrify'); ?>"></textarea><button type="submit">{{{ settings.submit_text }}}</button></form><?php
    }
}
