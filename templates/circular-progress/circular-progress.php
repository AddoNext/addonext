<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
$percent = isset($s['percent']['size']) ? (int) $s['percent']['size'] : 0;
?><div class="elementrify-circular-progress"><svg viewBox="0 0 36 36"><circle class="elementrify-circular-progress__bg" cx="18" cy="18" r="16"/><circle class="elementrify-circular-progress__fill" cx="18" cy="18" r="16" stroke-dasharray="<?php echo esc_attr($percent); ?>, 100"/></svg><span class="elementrify-circular-progress__value"><?php echo esc_html($percent); ?>%</span><?php if (!empty($s['title'])) : ?><span class="elementrify-circular-progress__title"><?php echo esc_html($s['title']); ?></span><?php endif; ?></div>
