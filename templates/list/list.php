<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
$items = isset($s['items']) && is_array($s['items']) ? $s['items'] : [];
$tag = isset($s['list_type']) && $s['list_type'] === 'ol' ? 'ol' : 'ul';
?><<?php echo $tag; ?> class="elementrify-list"><?php foreach ($items as $item) : ?><li><?php echo esc_html($item['text'] ?? ''); ?></li><?php endforeach; ?></<?php echo $tag; ?>>
