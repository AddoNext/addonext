<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
?><div class="elementrify-cta"><h3 class="elementrify-cta__title"><?php echo esc_html($s['title'] ?? ''); ?></h3><p class="elementrify-cta__desc"><?php echo esc_html($s['description'] ?? ''); ?></p><a href="<?php echo esc_url($s['button_url']['url'] ?? '#'); ?>" class="elementrify-cta__button"><?php echo esc_html($s['button_text'] ?? ''); ?></a></div>
