<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
$percent = isset($s['percent']['size']) ? (int) $s['percent']['size'] : 0;
?><div class="elementrify-progress-bar"><span class="elementrify-progress-bar__title"><?php echo esc_html($s['title'] ?? ''); ?></span><div class="elementrify-progress-bar__track"><div class="elementrify-progress-bar__fill" style="width: <?php echo esc_attr($percent); ?>%"></div></div><span class="elementrify-progress-bar__percent"><?php echo esc_html($percent); ?>%</span></div>
