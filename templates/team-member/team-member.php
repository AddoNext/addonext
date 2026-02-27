<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
?><div class="elementrify-team-member"><?php if (!empty($s['image']['url'])) : ?><img src="<?php echo esc_url($s['image']['url']); ?>" alt="<?php echo esc_attr($s['name'] ?? ''); ?>" class="elementrify-team-member__image"><?php endif; ?><h4 class="elementrify-team-member__name"><?php echo esc_html($s['name'] ?? ''); ?></h4><p class="elementrify-team-member__role"><?php echo esc_html($s['role'] ?? ''); ?></p><p class="elementrify-team-member__bio"><?php echo esc_html($s['bio'] ?? ''); ?></p></div>
