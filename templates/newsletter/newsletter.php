<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
?><div class="elementrify-newsletter"><h4><?php echo esc_html($s['title'] ?? ''); ?></h4><p><?php echo esc_html($s['description'] ?? ''); ?></p><form><input type="email" placeholder="<?php echo esc_attr__('Your email', 'elementrify'); ?>"><button type="submit"><?php echo esc_html($s['button_text'] ?? ''); ?></button></form></div>
