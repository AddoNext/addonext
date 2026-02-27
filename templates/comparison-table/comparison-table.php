<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
$rows = isset($s['rows']) && is_array($s['rows']) ? $s['rows'] : [];
?><table class="elementrify-comparison-table"><thead><tr><th></th><th><?php echo esc_html($s['header_plan_a'] ?? ''); ?></th><th><?php echo esc_html($s['header_plan_b'] ?? ''); ?></th></tr></thead><tbody><?php foreach ($rows as $row) : ?><tr><td><?php echo esc_html($row['feature'] ?? ''); ?></td><td><?php echo esc_html($row['plan_a'] ?? ''); ?></td><td><?php echo esc_html($row['plan_b'] ?? ''); ?></td></tr><?php endforeach; ?></tbody></table>
