<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
$features = isset($s['features']) && is_array($s['features']) ? $s['features'] : [];
$btn_url = $s['button_url']['url'] ?? '#';
?><div class="elementrify-pricing-table"><h3 class="elementrify-pricing-table__title"><?php echo esc_html($s['title'] ?? ''); ?></h3><div class="elementrify-pricing-table__price"><?php echo esc_html(($s['price'] ?? '') . ($s['period'] ?? '')); ?></div><ul class="elementrify-pricing-table__features"><?php foreach ($features as $f) : ?><li><?php echo esc_html($f['feature'] ?? ''); ?></li><?php endforeach; ?></ul><a href="<?php echo esc_url($btn_url); ?>" class="elementrify-pricing-table__button"><?php echo esc_html($s['button_text'] ?? ''); ?></a></div>
