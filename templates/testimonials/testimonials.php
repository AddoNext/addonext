<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
$items = isset($s['items']) && is_array($s['items']) ? $s['items'] : [];
?><div class="elementrify-testimonials"><?php foreach ($items as $item) : ?><div class="elementrify-testimonial"><blockquote><?php echo esc_html($item['content'] ?? ''); ?></blockquote><cite><?php echo esc_html($item['name'] ?? ''); ?><?php if (!empty($item['role'])) : ?> — <?php echo esc_html($item['role']); ?><?php endif; ?></cite></div><?php endforeach; ?></div>
