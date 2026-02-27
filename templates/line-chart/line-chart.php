<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
?><div class="elementrify-line-chart"><?php if (!empty($s['title'])) : ?><h4><?php echo esc_html($s['title']); ?></h4><?php endif; ?><div class="elementrify-line-chart__placeholder"><?php echo esc_html__('Line chart placeholder', 'elementrify'); ?></div></div>
