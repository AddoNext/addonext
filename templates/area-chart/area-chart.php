<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
?><div class="elementrify-area-chart"><?php if (!empty($s['title'])) : ?><h4><?php echo esc_html($s['title']); ?></h4><?php endif; ?><div class="elementrify-area-chart__placeholder"><?php echo esc_html__('Area chart placeholder', 'elementrify'); ?></div></div>
