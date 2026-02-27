<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
$items = isset($s['items']) && is_array($s['items']) ? $s['items'] : [];
?><div class="elementrify-timeline"><?php foreach ($items as $item) : ?><div class="elementrify-timeline__item"><span class="elementrify-timeline__date"><?php echo esc_html($item['date'] ?? ''); ?></span><div class="elementrify-timeline__content"><h4><?php echo esc_html($item['title'] ?? ''); ?></h4><p><?php echo esc_html($item['content'] ?? ''); ?></p></div></div><?php endforeach; ?></div>
