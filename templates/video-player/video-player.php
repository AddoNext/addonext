<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
?><div class="elementrify-video-player"><div class="elementrify-video-player__placeholder"><?php echo esc_html__('Video:', 'elementrify'); ?> <?php echo esc_html($s['video_type'] ?? 'youtube'); ?></div></div>
