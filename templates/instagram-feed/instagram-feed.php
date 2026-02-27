<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
?><div class="elementrify-instagram-feed"><p><?php echo esc_html__('Instagram feed placeholder. Connect API to show posts.', 'elementrify'); ?> (<?php echo esc_html($s['username'] ?? ''); ?>)</p></div>
