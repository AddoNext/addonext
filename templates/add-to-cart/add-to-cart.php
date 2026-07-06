<?php
defined('ABSPATH') || exit;

if (!class_exists('WooCommerce')) {
    echo '<div class="addonext-add-to-cart__placeholder">' . esc_html__('WooCommerce is required for this widget.', 'addonext') . '</div>';
    return;
}

$addonext_settings = $this->get_settings_for_display();

$addonext_productId = isset($addonext_settings['product_id']) ? absint($addonext_settings['product_id']) : 0;
if ($addonext_productId <= 0) {
    if (is_singular('product')) {
        $addonext_productId = get_queried_object_id();
    } elseif (function_exists('in_the_loop') && in_the_loop() && get_post_type() === 'product') {
        $addonext_productId = get_the_ID();
    }
}

if ($addonext_productId <= 0) {
    echo '<div class="addonext-add-to-cart__placeholder">' . esc_html__('Set a product ID, or place this widget on a product page or inside a product loop.', 'addonext') . '</div>';
    return;
}

$addonext_product = wc_get_product($addonext_productId);
if (!$addonext_product) {
    echo '<div class="addonext-add-to-cart__placeholder">' . esc_html__('Product not found.', 'addonext') . '</div>';
    return;
}

$addonext_showQuantity = !isset($addonext_settings['show_quantity']) || $addonext_settings['show_quantity'] === 'yes';
$addonext_quantityLabel = isset($addonext_settings['quantity_label']) ? (string) $addonext_settings['quantity_label'] : __('Quantity', 'addonext');
$addonext_defaultQtySetting = isset($addonext_settings['default_qty']) ? max(1, (int) $addonext_settings['default_qty']) : 1;

$addonext_minQty = (int) apply_filters('woocommerce_quantity_input_min', $addonext_product->get_min_purchase_quantity(), $addonext_product);
$addonext_maxQty = (int) apply_filters('woocommerce_quantity_input_max', $addonext_product->get_max_purchase_quantity(), $addonext_product);
if ($addonext_minQty < 1) {
    $addonext_minQty = 1;
}
$addonext_step = (int) apply_filters('woocommerce_quantity_input_step', 1, $addonext_product);
if ($addonext_step < 1) {
    $addonext_step = 1;
}

$addonext_qty = $addonext_defaultQtySetting;
if ($addonext_qty < $addonext_minQty) {
    $addonext_qty = $addonext_minQty;
}
if ($addonext_maxQty > 0 && $addonext_qty > $addonext_maxQty) {
    $addonext_qty = $addonext_maxQty;
}

$addonext_buttonText = isset($addonext_settings['button_text']) ? (string) $addonext_settings['button_text'] : __('Add to Cart', 'addonext');
$addonext_openMiniCart = !isset($addonext_settings['open_mini_cart']) || $addonext_settings['open_mini_cart'] === 'yes';
$addonext_cartUrl = '';
if (function_exists('wc_get_page_id')) {
    $addonext_cartPageId = (int) wc_get_page_id('cart');
    if ($addonext_cartPageId > 0) {
        $addonext_cartUrl = (string) get_permalink($addonext_cartPageId);
    }
}
if ($addonext_cartUrl === '' && function_exists('wc_get_cart_url')) {
    $addonext_cartUrl = (string) wc_get_cart_url();
}
$addonext_homeBase = untrailingslashit(home_url());
$addonext_cartBase = $addonext_cartUrl !== '' ? untrailingslashit($addonext_cartUrl) : '';
if ($addonext_cartBase === '' || $addonext_cartBase === $addonext_homeBase) {
    $addonext_cartUrl = '';
}

$addonext_purchasable = $addonext_product->is_purchasable();
$addonext_inStock = $addonext_product->is_in_stock();
$addonext_supportsAjax = $addonext_product->supports('ajax_add_to_cart');

$this->add_render_attribute('shell', 'class', 'addonext-add-to-cart-shell');
$this->add_render_attribute('shell', 'data-open-mini-cart', $addonext_openMiniCart ? 'yes' : 'no');
if ($addonext_cartUrl !== '') {
    $this->add_render_attribute('shell', 'data-cart-url', $addonext_cartUrl);
}
?>
<div <?php $this->print_render_attribute_string('shell'); ?>>
    <div class="addonext-add-to-cart">
        <?php if (!$addonext_purchasable || !$addonext_inStock) : ?>
            <div class="addonext-add-to-cart__notice"><?php echo $addonext_product->is_in_stock() ? esc_html($addonext_product->add_to_cart_text()) : wp_kses_post(wc_get_stock_html($addonext_product)); ?></div>
        <?php elseif ($addonext_supportsAjax) : ?>
            <?php if ($addonext_showQuantity) : ?>
                <div class="addonext-add-to-cart__quantity">
                    <span class="addonext-add-to-cart__quantity-label"><?php echo esc_html($addonext_quantityLabel); ?></span>
                    <div class="addonext-add-to-cart__quantity-row">
                        <button type="button" class="addonext-add-to-cart__qty-btn addonext-add-to-cart__qty-btn--minus" aria-label="<?php esc_attr_e('Decrease quantity', 'addonext'); ?>">−</button>
                        <input
                            type="number"
                            class="addonext-add-to-cart__qty-input"
                            value="<?php echo esc_attr((string) $addonext_qty); ?>"
                            min="<?php echo esc_attr((string) $addonext_minQty); ?>"
                            <?php echo $addonext_maxQty > 0 ? 'max="' . esc_attr((string) $addonext_maxQty) . '"' : ''; ?>
                            step="<?php echo esc_attr((string) $addonext_step); ?>"
                            inputmode="numeric"
                            aria-label="<?php esc_attr_e('Quantity', 'addonext'); ?>" />
                        <button type="button" class="addonext-add-to-cart__qty-btn addonext-add-to-cart__qty-btn--plus" aria-label="<?php esc_attr_e('Increase quantity', 'addonext'); ?>">+</button>
                    </div>
                </div>
            <?php endif; ?>
            <div class="addonext-add-to-cart__button-wrap">
                <?php
                $this->add_render_attribute('submit', 'class', [
                    'addonext-add-to-cart__submit',
                    'addonext-add-to-cart__cart-btn',
                    'add_to_cart_button',
                    'ajax_add_to_cart',
                ]);
                $this->add_render_attribute('submit', 'href', esc_url($addonext_product->add_to_cart_url()));
                $this->add_render_attribute('submit', 'data-product_id', (string) $addonext_product->get_id());
                $this->add_render_attribute('submit', 'data-product_sku', (string) $addonext_product->get_sku());
                $this->add_render_attribute('submit', 'data-quantity', (string) $addonext_qty);
                $this->add_render_attribute('submit', 'rel', 'nofollow');
                $this->add_render_attribute('submit', 'aria-label', esc_attr($addonext_product->add_to_cart_description()));
                $this->add_render_attribute('submit', 'data-atc-label-default', $addonext_buttonText);
                $this->add_render_attribute('submit', 'data-atc-label-added', __('Added to cart', 'addonext'));
                $this->add_render_attribute('submit', 'data-atc-label-view-cart', __('View cart', 'addonext'));
                $this->add_render_attribute('button_text', 'class', 'addonext-add-to-cart__btn-label');
                $this->add_inline_editing_attributes('button_text', 'basic');
                ?>
                <a <?php $this->print_render_attribute_string('submit'); ?>>
                    <span class="addonext-add-to-cart__btn-icon" aria-hidden="true"><i class="fas fa-cart-plus"></i></span>
                    <span <?php $this->print_render_attribute_string('button_text'); ?>><?php echo esc_html($addonext_buttonText); ?></span>
                </a>
            </div>
        <?php elseif ($addonext_product->is_type('simple')) : ?>
            <?php
            $addonext_formAction = esc_url(apply_filters('woocommerce_add_to_cart_form_action', $addonext_product->get_permalink()));
            ?>
            <form class="addonext-add-to-cart__form cart" action="<?php echo esc_url($addonext_formAction); ?>" method="post" enctype="multipart/form-data">
                <?php if ($addonext_showQuantity) : ?>
                    <div class="addonext-add-to-cart__quantity">
                        <span class="addonext-add-to-cart__quantity-label"><?php echo esc_html($addonext_quantityLabel); ?></span>
                        <div class="addonext-add-to-cart__quantity-row">
                            <button type="button" class="addonext-add-to-cart__qty-btn addonext-add-to-cart__qty-btn--minus" aria-label="<?php esc_attr_e('Decrease quantity', 'addonext'); ?>">−</button>
                            <input
                                type="number"
                                name="quantity"
                                class="addonext-add-to-cart__qty-input"
                                value="<?php echo esc_attr((string) $addonext_qty); ?>"
                                min="<?php echo esc_attr((string) $addonext_minQty); ?>"
                                <?php echo $addonext_maxQty > 0 ? 'max="' . esc_attr((string) $addonext_maxQty) . '"' : ''; ?>
                                step="<?php echo esc_attr((string) $addonext_step); ?>"
                                inputmode="numeric"
                                aria-label="<?php esc_attr_e('Quantity', 'addonext'); ?>" />
                            <button type="button" class="addonext-add-to-cart__qty-btn addonext-add-to-cart__qty-btn--plus" aria-label="<?php esc_attr_e('Increase quantity', 'addonext'); ?>">+</button>
                        </div>
                    </div>
                <?php else : ?>
                    <input type="hidden" name="quantity" value="<?php echo esc_attr((string) $addonext_qty); ?>" />
                <?php endif; ?>
                <input type="hidden" name="add-to-cart" value="<?php echo esc_attr((string) $addonext_product->get_id()); ?>" />
                <button type="submit" class="addonext-add-to-cart__submit addonext-add-to-cart__submit--form">
                    <span class="addonext-add-to-cart__btn-icon" aria-hidden="true"><i class="fas fa-cart-plus"></i></span>
                    <span class="addonext-add-to-cart__btn-label"><?php echo esc_html($addonext_buttonText); ?></span>
                </button>
            </form>
        <?php else : ?>
            <?php
            $addonext_fallbackUrl = $addonext_product->add_to_cart_url();
            $addonext_linkTarget = '';
            $addonext_linkRel = 'nofollow';
            if ($addonext_product->is_type('external')) {
                $addonext_extUrl = (string) get_post_meta($addonext_product->get_id(), '_product_url', true);
                $addonext_fallbackUrl = $addonext_extUrl !== '' ? $addonext_extUrl : $addonext_fallbackUrl;
                $addonext_linkTarget = '_blank';
                $addonext_linkRel = 'nofollow noopener';
            }
            ?>
            <div class="addonext-add-to-cart__button-wrap">
                <a class="addonext-add-to-cart__submit addonext-add-to-cart__submit--link" href="<?php echo esc_url($addonext_fallbackUrl); ?>" <?php echo $addonext_linkTarget !== '' ? 'target="' . esc_attr($addonext_linkTarget) . '"' : ''; ?> rel="<?php echo esc_attr($addonext_linkRel); ?>">
                    <span class="addonext-add-to-cart__btn-icon" aria-hidden="true"><i class="fas fa-cart-plus"></i></span>
                    <span class="addonext-add-to-cart__btn-label"><?php echo esc_html($addonext_product->add_to_cart_text()); ?></span>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
