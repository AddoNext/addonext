<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
$gallery = isset($s['gallery']) && is_array($s['gallery']) ? $s['gallery'] : [];
$columns = isset($s['columns']) ? (int) $s['columns'] : 3;
?><div class="elementrify-image-gallery" style="grid-template-columns: repeat(<?php echo esc_attr($columns); ?>, 1fr)"><?php foreach ($gallery as $item) : ?><div class="elementrify-image-gallery__item"><?php if (!empty($item['image']['url'])) : ?><img src="<?php echo esc_url($item['image']['url']); ?>" alt="<?php echo esc_attr($item['caption'] ?? ''); ?>"><?php endif; ?></div><?php endforeach; ?></div>
