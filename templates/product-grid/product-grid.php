<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
?><div class="elementrify-product-grid" data-columns="<?php echo esc_attr($s['columns'] ?? 4); ?>"><p><?php echo esc_html__('Product grid placeholder. Requires WooCommerce.', 'elementrify'); ?></p></div>
