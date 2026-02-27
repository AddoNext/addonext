<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
$style = !empty($s['image']['url']) ? ' style="background-image:url(' . esc_url($s['image']['url']) . ')"' : '';
?><div class="elementrify-banner"<?php echo $style; ?>><div class="elementrify-banner__content"><h2 class="elementrify-banner__title"><?php echo esc_html($s['title'] ?? ''); ?></h2><p class="elementrify-banner__subtitle"><?php echo esc_html($s['subtitle'] ?? ''); ?></p><?php if (!empty($s['button_text']) && !empty($s['button_url']['url'])) : ?><a href="<?php echo esc_url($s['button_url']['url']); ?>" class="elementrify-banner__button"><?php echo esc_html($s['button_text']); ?></a><?php endif; ?></div></div>
