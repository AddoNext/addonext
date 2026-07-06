<?php
defined('ABSPATH') || exit;

use Elementor\Icons_Manager;

if (!class_exists('WooCommerce')) {
    echo '<div class="addonext-mini-cart__placeholder">' . esc_html__('WooCommerce is required for this widget.', 'addonext') . '</div>';
    return;
}

$addonext_settings = $this->get_settings_for_display();
$addonext_icon = isset($addonext_settings['icon']) ? $addonext_settings['icon'] : ['value' => 'eicon-cart', 'library' => 'eicons'];
$addonext_show_count = !isset($addonext_settings['show_count']) || $addonext_settings['show_count'] === 'yes';
$addonext_show_subtotal = isset($addonext_settings['show_subtotal']) && $addonext_settings['show_subtotal'] === 'yes';
$addonext_show_label = isset($addonext_settings['show_cart_label']) && $addonext_settings['show_cart_label'] === 'yes';
$addonext_cart_label = isset($addonext_settings['cart_label']) ? (string) $addonext_settings['cart_label'] : __('Cart', 'addonext');

$addonext_cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
$addonext_cart_url = wc_get_cart_url();
?>
<div class="addonext-mini-cart">
    <div class="addonext-mini-cart__inner">
        <a href="<?php echo esc_url($addonext_cart_url); ?>" class="addonext-mini-cart__trigger">
            <span class="addonext-mini-cart__icon">
                <?php Icons_Manager::render_icon($addonext_icon, ['aria-hidden' => 'true']); ?>
            </span>
            <?php if ($addonext_show_label && $addonext_cart_label !== '') : ?>
                <span class="addonext-mini-cart__label"><?php echo esc_html($addonext_cart_label); ?></span>
            <?php endif; ?>
            <?php if ($addonext_show_subtotal) : ?>
                <span class="addonext-mini-cart__subtotal"><?php echo wp_kses_post(WC()->cart->get_cart_subtotal()); ?></span>
            <?php endif; ?>
            <?php if ($addonext_show_count) : ?>
                <span class="addonext-mini-cart__count<?php echo $addonext_cart_count === 0 ? ' addonext-mini-cart__count--empty' : ''; ?>"><?php echo esc_html((string) $addonext_cart_count); ?></span>
            <?php endif; ?>
        </a>
    </div>
</div>
