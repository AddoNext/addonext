<?php
namespace Elementrify\Widgets\PriceList;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;

defined('ABSPATH') or exit;

class PriceList extends WidgetBase
{
    public function get_icon(): string { return 'eicon-price-list elementrify-icon'; }
    public function get_name(): string { return 'elementrify-price-list'; }
    public function get_admin_category(): string { return 'marketing'; }
    public function get_title(): string { return __('Price List', 'elementrify'); }
    public function get_description(): string { return __('Menu or price list', 'elementrify'); }
    public function get_brand_color(): string { return '#059669'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $r = new Repeater();
        $r->add_control('title', ['label' => __('Item', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Item', 'elementrify')]);
        $r->add_control('description', ['label' => __('Description', 'elementrify'), 'type' => Controls_Manager::TEXTAREA, 'default' => '']);
        $r->add_control('price', ['label' => __('Price', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => '$0']);
        $this->add_control('items', ['label' => __('Items', 'elementrify'), 'type' => Controls_Manager::REPEATER, 'fields' => $r->get_controls(), 'default' => [['title' => __('Item 1', 'elementrify'), 'price' => '$10']], 'title_field' => '{{{ title }}}']);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/price-list/price-list.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-price-list"><# _.each(settings.items, function(item) { #><div class="elementrify-price-list__row"><span class="elementrify-price-list__title">{{{ item.title }}}</span><span class="elementrify-price-list__price">{{{ item.price }}}</span></div><# if (item.description) { #><p class="elementrify-price-list__desc">{{{ item.description }}}</p><# } #><# }); #></div><?php
    }
}
