<?php
namespace Elementrify\Widgets\ListWidget;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;

defined('ABSPATH') or exit;

class ListWidget extends WidgetBase
{
    public function get_icon(): string { return 'eicon-editor-list-ul elementrify-icon'; }
    public function get_name(): string { return 'elementrify-list'; }
    public function get_admin_category(): string { return 'content'; }
    public function get_title(): string { return __('List', 'elementrify'); }
    public function get_description(): string { return __('Styled bullet/number lists', 'elementrify'); }
    public function get_brand_color(): string { return '#475569'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $r = new Repeater();
        $r->add_control('text', ['label' => __('Item', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('List item', 'elementrify')]);
        $this->add_control('items', ['label' => __('Items', 'elementrify'), 'type' => Controls_Manager::REPEATER, 'fields' => $r->get_controls(), 'default' => [['text' => __('Item 1', 'elementrify')], ['text' => __('Item 2', 'elementrify')]], 'title_field' => '{{{ text }}}']);
        $this->add_control('list_type', ['label' => __('List type', 'elementrify'), 'type' => Controls_Manager::SELECT, 'default' => 'ul', 'options' => ['ul' => __('Bullets', 'elementrify'), 'ol' => __('Numbers', 'elementrify')]]);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/list/list.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><{{{ settings.list_type }}} class="elementrify-list"><# _.each(settings.items, function(item) { #><li>{{{ item.text }}}</li><# }); #></{{{ settings.list_type }}}><?php
    }
}
