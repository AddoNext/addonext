<?php
defined('ABSPATH') || exit;

use Elementor\Icons_Manager;

if (!class_exists('WooCommerce')) {
    echo '<div class="elementrify-mini-cart__placeholder">' . esc_html__('WooCommerce is required for this widget.', 'elementrify') . '</div>';
    return;
}

$settings = $this->get_settings_for_display();
$icon = isset($settings['icon']) ? $settings['icon'] : ['value' => 'eicon-cart', 'library' => 'eicons'];
$show_count = !isset($settings['show_count']) || $settings['show_count'] === 'yes';
$show_subtotal = isset($settings['show_subtotal']) && $settings['show_subtotal'] === 'yes';
$show_label = isset($settings['show_cart_label']) && $settings['show_cart_label'] === 'yes';
$cart_label = isset($settings['cart_label']) ? (string) $settings['cart_label'] : __('Cart', 'elementrify');

$cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
$cart_url = wc_get_cart_url();
?>
<div class="elementrify-mini-cart">
    <div class="elementrify-mini-cart__inner">
        <a href="<?php echo esc_url($cart_url); ?>" class="elementrify-mini-cart__trigger">
            <span class="elementrify-mini-cart__icon">
                <?php Icons_Manager::render_icon($icon, ['aria-hidden' => 'true']); ?>
            </span>
            <?php if ($show_label && $cart_label !== '') : ?>
                <span class="elementrify-mini-cart__label"><?php echo esc_html($cart_label); ?></span>
            <?php endif; ?>
            <?php if ($show_subtotal) : ?>
                <span class="elementrify-mini-cart__subtotal"><?php echo wp_kses_post(WC()->cart->get_cart_subtotal()); ?></span>
            <?php endif; ?>
            <?php if ($show_count) : ?>
                <span class="elementrify-mini-cart__count<?php echo $cart_count === 0 ? ' elementrify-mini-cart__count--empty' : ''; ?>"><?php echo esc_html((string) $cart_count); ?></span>
            <?php endif; ?>
        </a>
    </div>
</div>
