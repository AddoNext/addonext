<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
?><div class="elementrify-countdown" data-due="<?php echo esc_attr($s['due_date'] ?? ''); ?>"><span class="elementrify-countdown__segment"><span class="elementrify-countdown__value">0</span><?php if (!empty($s['labels'])) : ?><span class="elementrify-countdown__label"><?php echo esc_html__('Days', 'elementrify'); ?></span><?php endif; ?></span></div>
