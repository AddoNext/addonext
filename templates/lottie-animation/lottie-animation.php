<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
?><div class="elementrify-lottie" data-src="<?php echo esc_url($s['source']['url'] ?? ''); ?>" data-loop="<?php echo esc_attr($s['loop'] ?? 'yes'); ?>"><?php echo esc_html__('Lottie placeholder', 'elementrify'); ?></div>
