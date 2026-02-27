<?php
namespace Elementrify\Widgets\MiniCart;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;

defined('ABSPATH') or exit;

class MiniCart extends WidgetBase
{
    public function get_icon(): string { return 'eicon-cart elementrify-icon'; }
    public function get_name(): string { return 'elementrify-mini-cart'; }
    public function get_admin_category(): string { return 'woocommerce'; }
    public function get_title(): string { return __('Mini Cart', 'elementrify'); }
    public function get_description(): string { return __('Floating cart widget', 'elementrify'); }
    public function get_brand_color(): string { return '#7f54b3'; }

    public function register_controls(): void
    {
        $this->start_controls_section('section_content', ['label' => __('Content', 'elementrify'), 'tab' => Controls_Manager::TAB_CONTENT]);
        $this->add_control('icon', ['label' => __('Cart icon', 'elementrify'), 'type' => Controls_Manager::ICONS, 'default' => ['value' => 'eicon-cart', 'library' => 'eicons']]);
        $this->add_control('show_count', ['label' => __('Show item count', 'elementrify'), 'type' => Controls_Manager::SWITCHER, 'default' => 'yes']);
        $this->end_controls_section();
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/mini-cart/mini-cart.php';
        if (is_readable($path)) include $path;
    }

    protected function content_template(): void
    {
        ?><div class="elementrify-mini-cart"><button type="button" class="elementrify-mini-cart__trigger"><?php echo esc_html__('Cart', 'elementrify'); ?></button><div class="elementrify-mini-cart__dropdown"><p><?php echo esc_html__('Mini cart placeholder. Requires WooCommerce.', 'elementrify'); ?></p></div></div><?php
    }
}
