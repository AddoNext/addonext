<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
?><div class="elementrify-flip-box"><div class="elementrify-flip-box__inner"><div class="elementrify-flip-box__front"><h3><?php echo esc_html($s['front_title'] ?? ''); ?></h3><p><?php echo esc_html($s['front_description'] ?? ''); ?></p></div><div class="elementrify-flip-box__back"><h3><?php echo esc_html($s['back_title'] ?? ''); ?></h3><p><?php echo esc_html($s['back_description'] ?? ''); ?></p></div></div></div>
