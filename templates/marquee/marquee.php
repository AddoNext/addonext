<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
?><div class="elementrify-marquee"><span class="elementrify-marquee__content"><?php echo esc_html($s['content'] ?? ''); ?></span></div>
