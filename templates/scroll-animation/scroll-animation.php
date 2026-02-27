<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
?><div class="elementrify-scroll-animation elementrify-scroll-animation--<?php echo esc_attr($s['animation'] ?? 'fadeInUp'); ?>"><?php echo wp_kses_post($s['content'] ?? ''); ?></div>
