<?php
namespace Elementrify\Widgets\Toggle;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;

defined('ABSPATH') or exit;

class Toggle extends WidgetBase
{
    public function get_icon(): string { return 'eicon-toggle elementrify-icon'; }
    public function get_name(): string { return 'elementrify-toggle'; }
    public function get_admin_category(): string { return 'content'; }
    public function get_title(): string { return __('Toggle', 'elementrify'); }
    public function get_description(): string { return __('Expandable toggle items', 'elementrify'); }
    public function get_brand_color(): string { return '#0d9488'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $r = new Repeater();
        $r->add_control('title', ['label' => __('Title', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Toggle title', 'elementrify')]);
        $r->add_control('content', ['label' => __('Content', 'elementrify'), 'type' => Controls_Manager::WYSIWYG, 'default' => __('Content.', 'elementrify')]);
        $this->add_control('items', ['label' => __('Items', 'elementrify'), 'type' => Controls_Manager::REPEATER, 'fields' => $r->get_controls(), 'default' => [['title' => __('Item 1', 'elementrify'), 'content' => __('Content 1.', 'elementrify')]], 'title_field' => '{{{ title }}}']);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/toggle/toggle.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-toggle"><# _.each(settings.items, function(item, i) { var open = i === 0 ? ' is-open' : ''; #><div class="elementrify-toggle__item{{ open }}"><div class="elementrify-toggle__header">{{{ item.title }}}</div><div class="elementrify-toggle__content">{{{ item.content }}}</div></div><# }); #></div><?php
    }
}
