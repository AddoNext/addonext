<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
?><span class="elementrify-highlight"><?php echo esc_html($s['text'] ?? ''); ?></span>
