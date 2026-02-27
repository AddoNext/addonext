<?php
namespace Elementrify\Widgets\PricingTable;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;

defined('ABSPATH') or exit;

class PricingTable extends WidgetBase
{
    public function get_icon(): string { return 'eicon-price-table elementrify-icon'; }
    public function get_name(): string { return 'elementrify-pricing-table'; }
    public function get_admin_category(): string { return 'marketing'; }
    public function get_title(): string { return __('Pricing Table', 'elementrify'); }
    public function get_description(): string { return __('Beautiful pricing layouts', 'elementrify'); }
    public function get_brand_color(): string { return '#059669'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('title', ['label' => __('Plan name', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Basic', 'elementrify')]);
        $this->add_control('price', ['label' => __('Price', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => '$9']);
        $this->add_control('period', ['label' => __('Period', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => '/mo']);
        $r = new Repeater();
        $r->add_control('feature', ['label' => __('Feature', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Feature', 'elementrify')]);
        $this->add_control('features', ['label' => __('Features', 'elementrify'), 'type' => Controls_Manager::REPEATER, 'fields' => $r->get_controls(), 'default' => [['feature' => __('Feature 1', 'elementrify')]], 'title_field' => '{{{ feature }}}']);
        $this->add_control('button_text', ['label' => __('Button text', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Get Started', 'elementrify')]);
        $this->add_control('button_url', ['label' => __('Button URL', 'elementrify'), 'type' => Controls_Manager::URL]);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/pricing-table/pricing-table.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-pricing-table"><h3 class="elementrify-pricing-table__title">{{{ settings.title }}}</h3><div class="elementrify-pricing-table__price">{{{ settings.price }}}{{{ settings.period }}}</div><ul class="elementrify-pricing-table__features"><# _.each(settings.features, function(f) { #><li>{{{ f.feature }}}</li><# }); #></ul><a href="{{ settings.button_url.url }}" class="elementrify-pricing-table__button">{{{ settings.button_text }}}</a></div><?php
    }
}
