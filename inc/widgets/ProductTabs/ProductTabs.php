<?php
namespace Elementrify\Widgets\ProductTabs;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class ProductTabs extends WidgetBase
{
    public function get_icon(): string { return 'eicon-tabs elementrify-icon'; }
    public function get_name(): string { return 'elementrify-product-tabs'; }
    public function get_admin_category(): string { return 'woocommerce'; }
    public function get_title(): string { return __('Product Tabs', 'elementrify'); }
    public function get_description(): string { return __('Product detail tabs', 'elementrify'); }
    public function get_brand_color(): string { return '#7f54b3'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('tabs_style', ['label' => __('Tabs style', 'elementrify'), 'type' => Controls_Manager::SELECT, 'default' => 'horizontal', 'options' => ['horizontal' => __('Horizontal', 'elementrify'), 'vertical' => __('Vertical', 'elementrify')]]);
        $this->add_control('show_description', ['label' => __('Description tab', 'elementrify'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes']);
        $this->add_control('show_reviews', ['label' => __('Reviews tab', 'elementrify'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes']);
        $this->add_control('show_additional', ['label' => __('Additional info tab', 'elementrify'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes']);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/product-tabs/product-tabs.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-product-tabs"><p><?php echo esc_html__('Product tabs placeholder. Requires WooCommerce.', 'elementrify'); ?></p></div><?php
    }
}
