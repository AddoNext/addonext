<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
$items = isset($s['items']) && is_array($s['items']) ? $s['items'] : [];
?><div class="elementrify-price-list"><?php foreach ($items as $item) : ?><div class="elementrify-price-list__row"><span class="elementrify-price-list__title"><?php echo esc_html($item['title'] ?? ''); ?></span><span class="elementrify-price-list__price"><?php echo esc_html($item['price'] ?? ''); ?></span></div><?php if (!empty($item['description'])) : ?><p class="elementrify-price-list__desc"><?php echo esc_html($item['description']); ?></p><?php endif; ?><?php endforeach; ?></div>
