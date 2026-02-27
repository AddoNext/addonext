<?php
namespace Elementrify\Widgets\ProductGrid;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class ProductGrid extends WidgetBase
{
    public function get_icon(): string { return 'eicon-products elementrify-icon'; }
    public function get_name(): string { return 'elementrify-product-grid'; }
    public function get_admin_category(): string { return 'woocommerce'; }
    public function get_title(): string { return __('Product Grid', 'elementrify'); }
    public function get_description(): string { return __('Display products in grid', 'elementrify'); }
    public function get_brand_color(): string { return '#7f54b3'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('columns', ['label' => __('Columns', 'elementrify'), 'type' => Controls_Manager::NUMBER, 'min' => 1, 'max' => 6, 'default' => 4]);
        $this->add_control('per_page', ['label' => __('Products per page', 'elementrify'), 'type' => Controls_Manager::NUMBER, 'default' => 8]);
        $this->add_control('orderby', ['label' => __('Order by', 'elementrify'), 'type' => Controls_Manager::SELECT, 'default' => 'menu_order', 'options' => ['menu_order' => __('Menu order', 'elementrify'), 'title' => __('Title', 'elementrify'), 'date' => __('Date', 'elementrify'), 'price' => __('Price', 'elementrify')]]);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/product-grid/product-grid.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-product-grid" data-columns="{{ settings.columns }}"><p><?php echo esc_html__('Product grid placeholder. Requires WooCommerce.', 'elementrify'); ?></p></div><?php
    }
}
