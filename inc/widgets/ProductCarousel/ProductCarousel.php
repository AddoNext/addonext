<?php
namespace Elementrify\Widgets\ProductCarousel;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class ProductCarousel extends WidgetBase
{
    public function get_icon(): string { return 'eicon-slides elementrify-icon'; }
    public function get_name(): string { return 'elementrify-product-carousel'; }
    public function get_admin_category(): string { return 'woocommerce'; }
    public function get_title(): string { return __('Product Carousel', 'elementrify'); }
    public function get_description(): string { return __('Product slider widget', 'elementrify'); }
    public function get_brand_color(): string { return '#7f54b3'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('per_view', ['label' => __('Products per view', 'elementrify'), 'type' => Controls_Manager::NUMBER, 'min' => 1, 'max' => 6, 'default' => 4]);
        $this->add_control('limit', ['label' => __('Limit', 'elementrify'), 'type' => Controls_Manager::NUMBER, 'default' => 12]);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/product-carousel/product-carousel.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-product-carousel"><p><?php echo esc_html__('Product carousel placeholder. Requires WooCommerce.', 'elementrify'); ?></p></div><?php
    }
}
