<?php
defined('ABSPATH') || exit;

if (!class_exists('WooCommerce')) {
    echo '<div class="veloria-add-to-cart__placeholder">' . esc_html__('WooCommerce is required for this widget.', 'veloria') . '</div>';
    return;
}

$veloria_settings = $this->get_settings_for_display();

$veloria_productId = isset($veloria_settings['product_id']) ? absint($veloria_settings['product_id']) : 0;
if ($veloria_productId <= 0) {
    if (is_singular('product')) {
        $veloria_productId = get_queried_object_id();
    } elseif (function_exists('in_the_loop') && in_the_loop() && get_post_type() === 'product') {
        $veloria_productId = get_the_ID();
    }
}

if ($veloria_productId <= 0) {
    echo '<div class="veloria-add-to-cart__placeholder">' . esc_html__('Set a product ID, or place this widget on a product page or inside a product loop.', 'veloria') . '</div>';
    return;
}

$veloria_product = wc_get_product($veloria_productId);
if (!$veloria_product) {
    echo '<div class="veloria-add-to-cart__placeholder">' . esc_html__('Product not found.', 'veloria') . '</div>';
    return;
}

$veloria_showQuantity = !isset($veloria_settings['show_quantity']) || $veloria_settings['show_quantity'] === 'yes';
$veloria_quantityLabel = isset($veloria_settings['quantity_label']) ? (string) $veloria_settings['quantity_label'] : __('Quantity', 'veloria');
$veloria_defaultQtySetting = isset($veloria_settings['default_qty']) ? max(1, (int) $veloria_settings['default_qty']) : 1;

$veloria_minQty = (int) apply_filters('woocommerce_quantity_input_min', $veloria_product->get_min_purchase_quantity(), $veloria_product);
$veloria_maxQty = (int) apply_filters('woocommerce_quantity_input_max', $veloria_product->get_max_purchase_quantity(), $veloria_product);
if ($veloria_minQty < 1) {
    $veloria_minQty = 1;
}
$veloria_step = (int) apply_filters('woocommerce_quantity_input_step', 1, $veloria_product);
if ($veloria_step < 1) {
    $veloria_step = 1;
}

$veloria_qty = $veloria_defaultQtySetting;
if ($veloria_qty < $veloria_minQty) {
    $veloria_qty = $veloria_minQty;
}
if ($veloria_maxQty > 0 && $veloria_qty > $veloria_maxQty) {
    $veloria_qty = $veloria_maxQty;
}

$veloria_buttonText = isset($veloria_settings['button_text']) ? (string) $veloria_settings['button_text'] : __('Add to Cart', 'veloria');
$veloria_openMiniCart = !isset($veloria_settings['open_mini_cart']) || $veloria_settings['open_mini_cart'] === 'yes';
$veloria_cartUrl = '';
if (function_exists('wc_get_page_id')) {
    $veloria_cartPageId = (int) wc_get_page_id('cart');
    if ($veloria_cartPageId > 0) {
        $veloria_cartUrl = (string) get_permalink($veloria_cartPageId);
    }
}
if ($veloria_cartUrl === '' && function_exists('wc_get_cart_url')) {
    $veloria_cartUrl = (string) wc_get_cart_url();
}
$veloria_homeBase = untrailingslashit(home_url());
$veloria_cartBase = $veloria_cartUrl !== '' ? untrailingslashit($veloria_cartUrl) : '';
if ($veloria_cartBase === '' || $veloria_cartBase === $veloria_homeBase) {
    $veloria_cartUrl = '';
}

$veloria_purchasable = $veloria_product->is_purchasable();
$veloria_inStock = $veloria_product->is_in_stock();
$veloria_supportsAjax = $veloria_product->supports('ajax_add_to_cart');

$this->add_render_attribute('shell', 'class', 'veloria-add-to-cart-shell');
$this->add_render_attribute('shell', 'data-open-mini-cart', $veloria_openMiniCart ? 'yes' : 'no');
if ($veloria_cartUrl !== '') {
    $this->add_render_attribute('shell', 'data-cart-url', $veloria_cartUrl);
}
?>
<div <?php $this->print_render_attribute_string('shell'); ?>>
    <div class="veloria-add-to-cart">
        <?php if (!$veloria_purchasable || !$veloria_inStock) : ?>
            <div class="veloria-add-to-cart__notice"><?php echo $veloria_product->is_in_stock() ? esc_html($veloria_product->add_to_cart_text()) : wp_kses_post(wc_get_stock_html($veloria_product)); ?></div>
        <?php elseif ($veloria_supportsAjax) : ?>
            <?php if ($veloria_showQuantity) : ?>
                <div class="veloria-add-to-cart__quantity">
                    <span class="veloria-add-to-cart__quantity-label"><?php echo esc_html($veloria_quantityLabel); ?></span>
                    <div class="veloria-add-to-cart__quantity-row">
                        <button type="button" class="veloria-add-to-cart__qty-btn veloria-add-to-cart__qty-btn--minus" aria-label="<?php esc_attr_e('Decrease quantity', 'veloria'); ?>">−</button>
                        <input
                            type="number"
                            class="veloria-add-to-cart__qty-input"
                            value="<?php echo esc_attr((string) $veloria_qty); ?>"
                            min="<?php echo esc_attr((string) $veloria_minQty); ?>"
                            <?php echo $veloria_maxQty > 0 ? 'max="' . esc_attr((string) $veloria_maxQty) . '"' : ''; ?>
                            step="<?php echo esc_attr((string) $veloria_step); ?>"
                            inputmode="numeric"
                            aria-label="<?php esc_attr_e('Quantity', 'veloria'); ?>" />
                        <button type="button" class="veloria-add-to-cart__qty-btn veloria-add-to-cart__qty-btn--plus" aria-label="<?php esc_attr_e('Increase quantity', 'veloria'); ?>">+</button>
                    </div>
                </div>
            <?php endif; ?>
            <div class="veloria-add-to-cart__button-wrap">
                <?php
                $this->add_render_attribute('submit', 'class', [
                    'veloria-add-to-cart__submit',
                    'veloria-add-to-cart__cart-btn',
                    'add_to_cart_button',
                    'ajax_add_to_cart',
                ]);
                $this->add_render_attribute('submit', 'href', esc_url($veloria_product->add_to_cart_url()));
                $this->add_render_attribute('submit', 'data-product_id', (string) $veloria_product->get_id());
                $this->add_render_attribute('submit', 'data-product_sku', (string) $veloria_product->get_sku());
                $this->add_render_attribute('submit', 'data-quantity', (string) $veloria_qty);
                $this->add_render_attribute('submit', 'rel', 'nofollow');
                $this->add_render_attribute('submit', 'aria-label', esc_attr($veloria_product->add_to_cart_description()));
                $this->add_render_attribute('submit', 'data-atc-label-default', $veloria_buttonText);
                $this->add_render_attribute('submit', 'data-atc-label-added', __('Added to cart', 'veloria'));
                $this->add_render_attribute('submit', 'data-atc-label-view-cart', __('View cart', 'veloria'));
                $this->add_render_attribute('button_text', 'class', 'veloria-add-to-cart__btn-label');
                $this->add_inline_editing_attributes('button_text', 'basic');
                ?>
                <a <?php $this->print_render_attribute_string('submit'); ?>>
                    <span class="veloria-add-to-cart__btn-icon" aria-hidden="true"><i class="fas fa-cart-plus"></i></span>
                    <span <?php $this->print_render_attribute_string('button_text'); ?>><?php echo esc_html($veloria_buttonText); ?></span>
                </a>
            </div>
        <?php elseif ($veloria_product->is_type('simple')) : ?>
            <?php
            $veloria_formAction = esc_url(apply_filters('woocommerce_add_to_cart_form_action', $veloria_product->get_permalink()));
            ?>
            <form class="veloria-add-to-cart__form cart" action="<?php echo esc_url($veloria_formAction); ?>" method="post" enctype="multipart/form-data">
                <?php if ($veloria_showQuantity) : ?>
                    <div class="veloria-add-to-cart__quantity">
                        <span class="veloria-add-to-cart__quantity-label"><?php echo esc_html($veloria_quantityLabel); ?></span>
                        <div class="veloria-add-to-cart__quantity-row">
                            <button type="button" class="veloria-add-to-cart__qty-btn veloria-add-to-cart__qty-btn--minus" aria-label="<?php esc_attr_e('Decrease quantity', 'veloria'); ?>">−</button>
                            <input
                                type="number"
                                name="quantity"
                                class="veloria-add-to-cart__qty-input"
                                value="<?php echo esc_attr((string) $veloria_qty); ?>"
                                min="<?php echo esc_attr((string) $veloria_minQty); ?>"
                                <?php echo $veloria_maxQty > 0 ? 'max="' . esc_attr((string) $veloria_maxQty) . '"' : ''; ?>
                                step="<?php echo esc_attr((string) $veloria_step); ?>"
                                inputmode="numeric"
                                aria-label="<?php esc_attr_e('Quantity', 'veloria'); ?>" />
                            <button type="button" class="veloria-add-to-cart__qty-btn veloria-add-to-cart__qty-btn--plus" aria-label="<?php esc_attr_e('Increase quantity', 'veloria'); ?>">+</button>
                        </div>
                    </div>
                <?php else : ?>
                    <input type="hidden" name="quantity" value="<?php echo esc_attr((string) $veloria_qty); ?>" />
                <?php endif; ?>
                <input type="hidden" name="add-to-cart" value="<?php echo esc_attr((string) $veloria_product->get_id()); ?>" />
                <button type="submit" class="veloria-add-to-cart__submit veloria-add-to-cart__submit--form">
                    <span class="veloria-add-to-cart__btn-icon" aria-hidden="true"><i class="fas fa-cart-plus"></i></span>
                    <span class="veloria-add-to-cart__btn-label"><?php echo esc_html($veloria_buttonText); ?></span>
                </button>
            </form>
        <?php else : ?>
            <?php
            $veloria_fallbackUrl = $veloria_product->add_to_cart_url();
            $veloria_linkTarget = '';
            $veloria_linkRel = 'nofollow';
            if ($veloria_product->is_type('external')) {
                $veloria_extUrl = (string) get_post_meta($veloria_product->get_id(), '_product_url', true);
                $veloria_fallbackUrl = $veloria_extUrl !== '' ? $veloria_extUrl : $veloria_fallbackUrl;
                $veloria_linkTarget = '_blank';
                $veloria_linkRel = 'nofollow noopener';
            }
            ?>
            <div class="veloria-add-to-cart__button-wrap">
                <a class="veloria-add-to-cart__submit veloria-add-to-cart__submit--link" href="<?php echo esc_url($veloria_fallbackUrl); ?>" <?php echo $veloria_linkTarget !== '' ? 'target="' . esc_attr($veloria_linkTarget) . '"' : ''; ?> rel="<?php echo esc_attr($veloria_linkRel); ?>">
                    <span class="veloria-add-to-cart__btn-icon" aria-hidden="true"><i class="fas fa-cart-plus"></i></span>
                    <span class="veloria-add-to-cart__btn-label"><?php echo esc_html($veloria_product->add_to_cart_text()); ?></span>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
