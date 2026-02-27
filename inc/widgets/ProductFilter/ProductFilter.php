<?php
namespace Elementrify\Widgets\ProductFilter;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class ProductFilter extends WidgetBase
{
    public function get_icon(): string { return 'eicon-filter elementrify-icon'; }
    public function get_name(): string { return 'elementrify-product-filter'; }
    public function get_admin_category(): string { return 'woocommerce'; }
    public function get_title(): string { return __('Product Filter', 'elementrify'); }
    public function get_description(): string { return __('Filter products by attributes', 'elementrify'); }
    public function get_brand_color(): string { return '#7f54b3'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('filter_by', ['label' => __('Filter by', 'elementrify'), 'type' => Controls_Manager::SELECT, 'default' => 'category', 'options' => ['category' => __('Category', 'elementrify'), 'tag' => __('Tag', 'elementrify'), 'attribute' => __('Attribute', 'elementrify'), 'price' => __('Price', 'elementrify')]]);
        $this->add_control('show_search', ['label' => __('Show search', 'elementrify'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes']);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/product-filter/product-filter.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-product-filter"><p><?php echo esc_html__('Product filter placeholder. Requires WooCommerce.', 'elementrify'); ?></p></div><?php
    }
}
