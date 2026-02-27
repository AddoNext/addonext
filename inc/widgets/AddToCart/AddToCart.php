<?php
namespace Elementrify\Widgets\AddToCart;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class AddToCart extends WidgetBase
{
    public function get_icon(): string { return 'eicon-cart-medium elementrify-icon'; }
    public function get_name(): string { return 'elementrify-add-to-cart'; }
    public function get_admin_category(): string { return 'woocommerce'; }
    public function get_title(): string { return __('Add to Cart', 'elementrify'); }
    public function get_description(): string { return __('Inline add to cart button', 'elementrify'); }
    public function get_brand_color(): string { return '#7f54b3'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('product_id', ['label' => __('Product ID', 'elementrify'), 'type' => Controls_Manager::NUMBER, 'description' => __('Leave empty to use current product in loop.', 'elementrify')]);
        $this->add_control('button_text', ['label' => __('Button text', 'elementrify'), 'type' => Controls_Manager::TEXT, 'default' => __('Add to cart', 'elementrify')]);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/add-to-cart/add-to-cart.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-add-to-cart"><button type="button" class="elementrify-add-to-cart__btn">{{{ settings.button_text }}}</button></div><?php
    }
}
