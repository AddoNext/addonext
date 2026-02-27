<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
$logos = isset($s['logos']) && is_array($s['logos']) ? $s['logos'] : [];
?><div class="elementrify-logo-showcase"><?php foreach ($logos as $item) : ?><div class="elementrify-logo-showcase__item"><?php if (!empty($item['link']['url'])) : ?><a href="<?php echo esc_url($item['link']['url']); ?>"><?php endif; ?><?php if (!empty($item['logo']['url'])) : ?><img src="<?php echo esc_url($item['logo']['url']); ?>" alt="<?php echo esc_attr($item['alt'] ?? ''); ?>"><?php endif; ?><?php if (!empty($item['link']['url'])) : ?></a><?php endif; ?></div><?php endforeach; ?></div>
