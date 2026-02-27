<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
?><div class="elementrify-content-toggle"><div class="elementrify-content-toggle__labels"><span class="elementrify-content-toggle__label is-active"><?php echo esc_html($s['primary_label'] ?? ''); ?></span><span class="elementrify-content-toggle__label"><?php echo esc_html($s['secondary_label'] ?? ''); ?></span></div><div class="elementrify-content-toggle__panels"><div class="elementrify-content-toggle__panel is-active"><?php echo wp_kses_post($s['primary_content'] ?? ''); ?></div><div class="elementrify-content-toggle__panel"><?php echo wp_kses_post($s['secondary_content'] ?? ''); ?></div></div></div>
