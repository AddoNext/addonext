<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
?><div class="elementrify-tilt-effect"><?php echo wp_kses_post($s['content'] ?? ''); ?></div>
