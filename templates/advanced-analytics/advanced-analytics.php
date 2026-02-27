<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
?><div class="elementrify-advanced-analytics"><h4><?php echo esc_html($s['title'] ?? ''); ?></h4><div class="elementrify-advanced-analytics__placeholder"><?php echo esc_html__('Analytics placeholder. Connect data source.', 'elementrify'); ?></div></div>
