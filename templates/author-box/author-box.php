<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
?><div class="elementrify-author-box"><?php if (!empty($s['avatar']['url'])) : ?><img src="<?php echo esc_url($s['avatar']['url']); ?>" alt="<?php echo esc_attr($s['name'] ?? ''); ?>" class="elementrify-author-box__avatar"><?php endif; ?><div class="elementrify-author-box__content"><h4 class="elementrify-author-box__name"><?php echo esc_html($s['name'] ?? ''); ?></h4><p class="elementrify-author-box__bio"><?php echo esc_html($s['bio'] ?? ''); ?></p></div></div>
