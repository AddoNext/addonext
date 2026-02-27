<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
?><div class="elementrify-google-map"><div class="elementrify-google-map__placeholder"><?php echo esc_html__('Map placeholder:', 'elementrify'); ?> <?php echo esc_html($s['address'] ?? ''); ?></div></div>
