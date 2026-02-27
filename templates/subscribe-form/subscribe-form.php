<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
?><div class="elementrify-subscribe-form"><?php if (!empty($s['title'])) : ?><h4><?php echo esc_html($s['title']); ?></h4><?php endif; ?><form><input type="email" placeholder="<?php echo esc_attr($s['placeholder'] ?? ''); ?>"><button type="submit"><?php echo esc_html($s['button_text'] ?? ''); ?></button></form></div>
