<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
$tag = !empty($s['tag']) ? $s['tag'] : 'h2';
?><<?php echo esc_attr($tag); ?> class="elementrify-dual-heading"><span class="elementrify-dual-heading__first"><?php echo esc_html($s['first_line'] ?? ''); ?></span> <span class="elementrify-dual-heading__second"><?php echo esc_html($s['second_line'] ?? ''); ?></span></<?php echo esc_attr($tag); ?>>
