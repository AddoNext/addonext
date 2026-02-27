<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
?><div class="elementrify-twitter-feed"><p><?php echo esc_html__('Twitter feed placeholder.', 'elementrify'); ?> @<?php echo esc_html($s['username'] ?? ''); ?></p></div>
