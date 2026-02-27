<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
?><div class="elementrify-off-canvas"><button type="button" class="elementrify-off-canvas__trigger"><?php echo esc_html($s['trigger_text'] ?? __('Open panel', 'elementrify')); ?></button><div class="elementrify-off-canvas__panel"><?php echo wp_kses_post($s['panel_content'] ?? ''); ?></div></div>
