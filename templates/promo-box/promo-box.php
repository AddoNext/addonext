<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
?><div class="elementrify-promo-box"><h4><?php echo esc_html($s['title'] ?? ''); ?></h4><p><?php echo esc_html($s['content'] ?? ''); ?></p><?php if (!empty($s['button_text']) && !empty($s['button_url']['url'])) : ?><a href="<?php echo esc_url($s['button_url']['url']); ?>"><?php echo esc_html($s['button_text']); ?></a><?php endif; ?></div>
