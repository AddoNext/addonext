<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
?><div class="elementrify-animated-counter"><span class="elementrify-animated-counter__number"><?php echo esc_html(($s['prefix'] ?? '') . ($s['number'] ?? '0') . ($s['suffix'] ?? '')); ?></span><?php if (!empty($s['title'])) : ?><span class="elementrify-animated-counter__title"><?php echo esc_html($s['title']); ?></span><?php endif; ?></div>
