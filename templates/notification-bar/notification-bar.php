<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
?><div class="elementrify-notification-bar"><span class="elementrify-notification-bar__message"><?php echo esc_html($s['message'] ?? ''); ?></span><?php if (!empty($s['link_url']['url'])) : ?><a href="<?php echo esc_url($s['link_url']['url']); ?>"><?php echo esc_html($s['link_text'] ?? ''); ?></a><?php endif; ?><?php if (!empty($s['dismissible'])) : ?><button type="button" class="elementrify-notification-bar__close" aria-label="Close">&times;</button><?php endif; ?></div>
