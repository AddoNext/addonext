<?php
defined('ABSPATH') || exit;

use Elementor\Icons_Manager;

if (!class_exists('WooCommerce')) {
    echo '<div class="veloria-mini-cart__placeholder">' . esc_html__('WooCommerce is required for this widget.', 'veloria') . '</div>';
    return;
}

$veloria_settings = $this->get_settings_for_display();
$veloria_icon = isset($veloria_settings['icon']) ? $veloria_settings['icon'] : ['value' => 'eicon-cart', 'library' => 'eicons'];
$veloria_show_count = !isset($veloria_settings['show_count']) || $veloria_settings['show_count'] === 'yes';
$veloria_show_subtotal = isset($veloria_settings['show_subtotal']) && $veloria_settings['show_subtotal'] === 'yes';
$veloria_show_label = isset($veloria_settings['show_cart_label']) && $veloria_settings['show_cart_label'] === 'yes';
$veloria_cart_label = isset($veloria_settings['cart_label']) ? (string) $veloria_settings['cart_label'] : __('Cart', 'veloria');

$veloria_cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
$veloria_cart_url = wc_get_cart_url();
?>
<div class="veloria-mini-cart">
    <div class="veloria-mini-cart__inner">
        <a href="<?php echo esc_url($veloria_cart_url); ?>" class="veloria-mini-cart__trigger">
            <span class="veloria-mini-cart__icon">
                <?php Icons_Manager::render_icon($veloria_icon, ['aria-hidden' => 'true']); ?>
            </span>
            <?php if ($veloria_show_label && $veloria_cart_label !== '') : ?>
                <span class="veloria-mini-cart__label"><?php echo esc_html($veloria_cart_label); ?></span>
            <?php endif; ?>
            <?php if ($veloria_show_subtotal) : ?>
                <span class="veloria-mini-cart__subtotal"><?php echo wp_kses_post(WC()->cart->get_cart_subtotal()); ?></span>
            <?php endif; ?>
            <?php if ($veloria_show_count) : ?>
                <span class="veloria-mini-cart__count<?php echo $veloria_cart_count === 0 ? ' veloria-mini-cart__count--empty' : ''; ?>"><?php echo esc_html((string) $veloria_cart_count); ?></span>
            <?php endif; ?>
        </a>
    </div>
</div>
