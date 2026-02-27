<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
?><div class="elementrify-star-rating" title="<?php echo esc_attr($s['rating'] ?? ''); ?>"><span class="elementrify-star-rating__stars"><?php echo esc_html(($s['rating'] ?? '0') . '/' . ($s['max_rating'] ?? '5')); ?></span><?php if (!empty($s['title'])) : ?><span class="elementrify-star-rating__title"><?php echo esc_html($s['title']); ?></span><?php endif; ?></div>
