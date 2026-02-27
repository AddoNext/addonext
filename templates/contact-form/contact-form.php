<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
?><form class="elementrify-contact-form"><input type="text" name="name" placeholder="<?php echo esc_attr__('Name', 'elementrify'); ?>"><input type="email" name="email" placeholder="<?php echo esc_attr__('Email', 'elementrify'); ?>"><textarea name="message" placeholder="<?php echo esc_attr__('Message', 'elementrify'); ?>"></textarea><button type="submit"><?php echo esc_html($s['submit_text'] ?? ''); ?></button></form>
