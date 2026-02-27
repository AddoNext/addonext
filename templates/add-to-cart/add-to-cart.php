<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
?><div class="elementrify-add-to-cart"><button type="button" class="elementrify-add-to-cart__btn"><?php echo esc_html($s['button_text'] ?? __('Add to cart', 'elementrify')); ?></button></div>
