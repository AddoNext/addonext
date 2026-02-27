<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
$text = $s['text'] ?? '';
$first = mb_substr($text, 0, 1);
$rest = mb_substr($text, 1);
?><p class="elementrify-dropcap"><span class="elementrify-dropcap__letter"><?php echo esc_html($first); ?></span><?php echo esc_html($rest); ?></p>
