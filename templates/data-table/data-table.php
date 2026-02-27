<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
$header = isset($s['header_row']) ? array_map('trim', explode(',', $s['header_row'])) : [];
$rows = isset($s['rows']) && is_array($s['rows']) ? $s['rows'] : [];
?><div class="elementrify-data-table"><table><thead><tr><?php foreach ($header as $h) : ?><th><?php echo esc_html($h); ?></th><?php endforeach; ?></tr></thead><tbody><?php foreach ($rows as $row) :
    $cells = array_map('trim', explode(',', $row['cells'] ?? '')); ?><tr><?php foreach ($cells as $c) : ?><td><?php echo esc_html($c); ?></td><?php endforeach; ?></tr><?php endforeach; ?></tbody></table></div>
