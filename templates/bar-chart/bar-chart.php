<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
$data = isset($s['data']) && is_array($s['data']) ? $s['data'] : [];
?><div class="elementrify-bar-chart"><?php if (!empty($s['title'])) : ?><h4><?php echo esc_html($s['title']); ?></h4><?php endif; ?><div class="elementrify-bar-chart__bars"><?php foreach ($data as $d) : ?><div class="elementrify-bar-chart__bar-wrap"><div class="elementrify-bar-chart__bar" style="height: <?php echo esc_attr($d['value'] ?? 0); ?>%"></div><span class="elementrify-bar-chart__label"><?php echo esc_html($d['label'] ?? ''); ?></span></div><?php endforeach; ?></div></div>
