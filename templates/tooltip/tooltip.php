<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
?><span class="elementrify-tooltip"><span class="elementrify-tooltip__trigger"><?php echo esc_html($s['trigger_text'] ?? ''); ?></span><span class="elementrify-tooltip__content"><?php echo esc_html($s['tooltip_text'] ?? ''); ?></span></span>
