<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
$cards = isset($s['cards']) && is_array($s['cards']) ? $s['cards'] : [];
?><div class="elementrify-bento-grid"><?php foreach ($cards as $card) : ?><div class="elementrify-bento-grid__card elementrify-bento-grid__card--<?php echo esc_attr($card['size'] ?? 'normal'); ?>"><h4><?php echo esc_html($card['title'] ?? ''); ?></h4><p><?php echo esc_html($card['content'] ?? ''); ?></p></div><?php endforeach; ?></div>
