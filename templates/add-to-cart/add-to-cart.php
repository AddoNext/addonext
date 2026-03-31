<?php
defined('ABSPATH') || exit;

if (!class_exists('WooCommerce')) {
    echo '<div class="elementrify-add-to-cart__placeholder">' . esc_html__('WooCommerce is required for this widget.', 'elementrify') . '</div>';
    return;
}

$settings = $this->get_settings_for_display();

$productId = isset($settings['product_id']) ? absint($settings['product_id']) : 0;
if ($productId <= 0) {
    if (is_singular('product')) {
        $productId = get_queried_object_id();
    } elseif (function_exists('in_the_loop') && in_the_loop() && get_post_type() === 'product') {
        $productId = get_the_ID();
    }
}

if ($productId <= 0) {
    echo '<div class="elementrify-add-to-cart__placeholder">' . esc_html__('Set a product ID, or place this widget on a product page or inside a product loop.', 'elementrify') . '</div>';
    return;
}

$product = wc_get_product($productId);
if (!$product) {
    echo '<div class="elementrify-add-to-cart__placeholder">' . esc_html__('Product not found.', 'elementrify') . '</div>';
    return;
}

$showQuantity = !isset($settings['show_quantity']) || $settings['show_quantity'] === 'yes';
$quantityLabel = isset($settings['quantity_label']) ? (string) $settings['quantity_label'] : __('Quantity', 'elementrify');
$defaultQtySetting = isset($settings['default_qty']) ? max(1, (int) $settings['default_qty']) : 1;

$minQty = (int) apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product);
$maxQty = (int) apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product);
if ($minQty < 1) {
    $minQty = 1;
}
$step = (int) apply_filters('woocommerce_quantity_input_step', 1, $product);
if ($step < 1) {
    $step = 1;
}

$qty = $defaultQtySetting;
if ($qty < $minQty) {
    $qty = $minQty;
}
if ($maxQty > 0 && $qty > $maxQty) {
    $qty = $maxQty;
}

$buttonText = isset($settings['button_text']) ? (string) $settings['button_text'] : __('Add to Cart', 'elementrify');
$openMiniCart = !isset($settings['open_mini_cart']) || $settings['open_mini_cart'] === 'yes';
$cartUrl = '';
if (function_exists('wc_get_page_id')) {
    $cartPageId = (int) wc_get_page_id('cart');
    if ($cartPageId > 0) {
        $cartUrl = (string) get_permalink($cartPageId);
    }
}
if ($cartUrl === '' && function_exists('wc_get_cart_url')) {
    $cartUrl = (string) wc_get_cart_url();
}
$homeBase = untrailingslashit(home_url());
$cartBase = $cartUrl !== '' ? untrailingslashit($cartUrl) : '';
if ($cartBase === '' || $cartBase === $homeBase) {
    $cartUrl = '';
}

$purchasable = $product->is_purchasable();
$inStock = $product->is_in_stock();
$supportsAjax = $product->supports('ajax_add_to_cart');

$this->add_render_attribute('shell', 'class', 'elementrify-add-to-cart-shell');
$this->add_render_attribute('shell', 'data-open-mini-cart', $openMiniCart ? 'yes' : 'no');
if ($cartUrl !== '') {
    $this->add_render_attribute('shell', 'data-cart-url', $cartUrl);
}
?>
<div <?php $this->print_render_attribute_string('shell'); ?>>
    <div class="elementrify-add-to-cart">
        <?php if (!$purchasable || !$inStock) : ?>
            <div class="elementrify-add-to-cart__notice"><?php echo $product->is_in_stock() ? esc_html($product->add_to_cart_text()) : wp_kses_post(wc_get_stock_html($product)); ?></div>
        <?php elseif ($supportsAjax) : ?>
            <?php if ($showQuantity) : ?>
                <div class="elementrify-add-to-cart__quantity">
                    <span class="elementrify-add-to-cart__quantity-label"><?php echo esc_html($quantityLabel); ?></span>
                    <div class="elementrify-add-to-cart__quantity-row">
                        <button type="button" class="elementrify-add-to-cart__qty-btn elementrify-add-to-cart__qty-btn--minus" aria-label="<?php esc_attr_e('Decrease quantity', 'elementrify'); ?>">−</button>
                        <input
                            type="number"
                            class="elementrify-add-to-cart__qty-input"
                            value="<?php echo esc_attr((string) $qty); ?>"
                            min="<?php echo esc_attr((string) $minQty); ?>"
                            <?php echo $maxQty > 0 ? 'max="' . esc_attr((string) $maxQty) . '"' : ''; ?>
                            step="<?php echo esc_attr((string) $step); ?>"
                            inputmode="numeric"
                            aria-label="<?php esc_attr_e('Quantity', 'elementrify'); ?>" />
                        <button type="button" class="elementrify-add-to-cart__qty-btn elementrify-add-to-cart__qty-btn--plus" aria-label="<?php esc_attr_e('Increase quantity', 'elementrify'); ?>">+</button>
                    </div>
                </div>
            <?php endif; ?>
            <div class="elementrify-add-to-cart__button-wrap">
                <?php
                $this->add_render_attribute('submit', 'class', [
                    'elementrify-add-to-cart__submit',
                    'elementrify-add-to-cart__cart-btn',
                    'add_to_cart_button',
                    'ajax_add_to_cart',
                ]);
                $this->add_render_attribute('submit', 'href', esc_url($product->add_to_cart_url()));
                $this->add_render_attribute('submit', 'data-product_id', (string) $product->get_id());
                $this->add_render_attribute('submit', 'data-product_sku', (string) $product->get_sku());
                $this->add_render_attribute('submit', 'data-quantity', (string) $qty);
                $this->add_render_attribute('submit', 'rel', 'nofollow');
                $this->add_render_attribute('submit', 'aria-label', esc_attr($product->add_to_cart_description()));
                $this->add_render_attribute('submit', 'data-atc-label-default', $buttonText);
                $this->add_render_attribute('submit', 'data-atc-label-added', __('Added to cart', 'elementrify'));
                $this->add_render_attribute('submit', 'data-atc-label-view-cart', __('View cart', 'elementrify'));
                $this->add_render_attribute('button_text', 'class', 'elementrify-add-to-cart__btn-label');
                $this->add_inline_editing_attributes('button_text', 'basic');
                ?>
                <a <?php $this->print_render_attribute_string('submit'); ?>>
                    <span class="elementrify-add-to-cart__btn-icon" aria-hidden="true"><i class="fas fa-cart-plus"></i></span>
                    <span <?php $this->print_render_attribute_string('button_text'); ?>><?php echo esc_html($buttonText); ?></span>
                </a>
            </div>
        <?php elseif ($product->is_type('simple')) : ?>
            <?php
            $formAction = esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink()));
            ?>
            <form class="elementrify-add-to-cart__form cart" action="<?php echo esc_url($formAction); ?>" method="post" enctype="multipart/form-data">
                <?php if ($showQuantity) : ?>
                    <div class="elementrify-add-to-cart__quantity">
                        <span class="elementrify-add-to-cart__quantity-label"><?php echo esc_html($quantityLabel); ?></span>
                        <div class="elementrify-add-to-cart__quantity-row">
                            <button type="button" class="elementrify-add-to-cart__qty-btn elementrify-add-to-cart__qty-btn--minus" aria-label="<?php esc_attr_e('Decrease quantity', 'elementrify'); ?>">−</button>
                            <input
                                type="number"
                                name="quantity"
                                class="elementrify-add-to-cart__qty-input"
                                value="<?php echo esc_attr((string) $qty); ?>"
                                min="<?php echo esc_attr((string) $minQty); ?>"
                                <?php echo $maxQty > 0 ? 'max="' . esc_attr((string) $maxQty) . '"' : ''; ?>
                                step="<?php echo esc_attr((string) $step); ?>"
                                inputmode="numeric"
                                aria-label="<?php esc_attr_e('Quantity', 'elementrify'); ?>" />
                            <button type="button" class="elementrify-add-to-cart__qty-btn elementrify-add-to-cart__qty-btn--plus" aria-label="<?php esc_attr_e('Increase quantity', 'elementrify'); ?>">+</button>
                        </div>
                    </div>
                <?php else : ?>
                    <input type="hidden" name="quantity" value="<?php echo esc_attr((string) $qty); ?>" />
                <?php endif; ?>
                <input type="hidden" name="add-to-cart" value="<?php echo esc_attr((string) $product->get_id()); ?>" />
                <button type="submit" class="elementrify-add-to-cart__submit elementrify-add-to-cart__submit--form">
                    <span class="elementrify-add-to-cart__btn-icon" aria-hidden="true"><i class="fas fa-cart-plus"></i></span>
                    <span class="elementrify-add-to-cart__btn-label"><?php echo esc_html($buttonText); ?></span>
                </button>
            </form>
        <?php else : ?>
            <?php
            $fallbackUrl = $product->add_to_cart_url();
            $linkTarget = '';
            $linkRel = 'nofollow';
            if ($product->is_type('external')) {
                $extUrl = (string) get_post_meta($product->get_id(), '_product_url', true);
                $fallbackUrl = $extUrl !== '' ? $extUrl : $fallbackUrl;
                $linkTarget = '_blank';
                $linkRel = 'nofollow noopener';
            }
            ?>
            <div class="elementrify-add-to-cart__button-wrap">
                <a class="elementrify-add-to-cart__submit elementrify-add-to-cart__submit--link" href="<?php echo esc_url($fallbackUrl); ?>" <?php echo $linkTarget !== '' ? 'target="' . esc_attr($linkTarget) . '"' : ''; ?> rel="<?php echo esc_attr($linkRel); ?>">
                    <span class="elementrify-add-to-cart__btn-icon" aria-hidden="true"><i class="fas fa-cart-plus"></i></span>
                    <span class="elementrify-add-to-cart__btn-label"><?php echo esc_html($product->add_to_cart_text()); ?></span>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
