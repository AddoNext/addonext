<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
?><div class="elementrify-hover-box"><div class="elementrify-hover-box__front"><?php echo esc_html($s['front_content'] ?? ''); ?></div><div class="elementrify-hover-box__back"><?php echo esc_html($s['back_content'] ?? ''); ?></div></div>
