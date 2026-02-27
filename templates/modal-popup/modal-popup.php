<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
?><div class="elementrify-modal"><button type="button" class="elementrify-modal__trigger"><?php echo esc_html($s['trigger_text'] ?? ''); ?></button><div class="elementrify-modal__overlay"><div class="elementrify-modal__content"><?php echo wp_kses_post($s['modal_content'] ?? ''); ?></div></div></div>
