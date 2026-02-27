<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
$items = isset($s['items']) && is_array($s['items']) ? $s['items'] : [];
?><div class="elementrify-toggle"><?php foreach ($items as $i => $item) : ?><div class="elementrify-toggle__item<?php echo $i === 0 ? ' is-open' : ''; ?>"><div class="elementrify-toggle__header"><?php echo esc_html($item['title'] ?? ''); ?></div><div class="elementrify-toggle__content"><?php echo wp_kses_post($item['content'] ?? ''); ?></div></div><?php endforeach; ?></div>
