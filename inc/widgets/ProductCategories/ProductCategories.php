<?php
namespace Elementrify\Widgets\ProductCategories;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class ProductCategories extends WidgetBase
{
    public function get_icon(): string { return 'eicon-nav-menu elementrify-icon'; }
    public function get_name(): string { return 'elementrify-product-categories'; }
    public function get_admin_category(): string { return 'woocommerce'; }
    public function get_title(): string { return __('Product Categories', 'elementrify'); }
    public function get_description(): string { return __('Category showcase', 'elementrify'); }
    public function get_brand_color(): string { return '#7f54b3'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('columns', ['label' => __('Columns', 'elementrify'), 'type' => Controls_Manager::NUMBER, 'min' => 1, 'max' => 6, 'default' => 4]);
        $this->add_control('show_count', ['label' => __('Show count', 'elementrify'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes']);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/product-categories/product-categories.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-product-categories"><p><?php echo esc_html__('Product categories placeholder. Requires WooCommerce.', 'elementrify'); ?></p></div><?php
    }
}
