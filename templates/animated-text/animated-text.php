<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
?><span class="elementrify-animated-text"><span class="elementrify-animated-text__before"><?php echo esc_html($s['before_text'] ?? ''); ?></span><span class="elementrify-animated-text__animated"><?php echo esc_html($s['animated_text'] ?? ''); ?></span><span class="elementrify-animated-text__after"><?php echo esc_html($s['after_text'] ?? ''); ?></span></span>
