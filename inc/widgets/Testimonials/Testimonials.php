<?php
namespace Elementrify\Widgets\Testimonials;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;

defined('ABSPATH') or exit;

class Testimonials extends WidgetBase
{
    public function get_icon(): string { return 'eicon-testimonial elementrify-icon'; }
    public function get_name(): string { return 'elementrify-testimonials'; }
    public function get_admin_category(): string { return 'marketing'; }
    public function get_title(): string { return __('Testimonials', 'elementrify'); }
    public function get_description(): string { return __('Customer reviews displays', 'elementrify'); }
    public function get_brand_color(): string { return '#7c3aed'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $r = new Repeater();
        $r->add_control('content', ['label' => __('Quote', 'elementrify'), 'type' => Controls_Manager::TEXTAREA, 'default' => __('Great product!', 'elementrify')]);
        $r->add_control('name', ['label' => __('Name', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('John', 'elementrify')]);
        $r->add_control('role', ['label' => __('Role / Title', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => '']);
        $r->add_control('image', ['label' => __('Avatar', 'elementrify'), 'type' => Controls_Manager::MEDIA]);
        $this->add_control('items', ['label' => __('Testimonials', 'elementrify'), 'type' => Controls_Manager::REPEATER, 'fields' => $r->get_controls(), 'default' => [['content' => __('Testimonial text.', 'elementrify'), 'name' => __('Jane', 'elementrify')]], 'title_field' => '{{{ name }}}']);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/testimonials/testimonials.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-testimonials"><# _.each(settings.items, function(item) { #><div class="elementrify-testimonial"><blockquote>{{{ item.content }}}</blockquote><cite>{{{ item.name }}}<# if (item.role) { #> — {{{ item.role }}}<# } #></cite></div><# }); #></div><?php
    }
}
