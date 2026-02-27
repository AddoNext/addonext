<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
$image = $s['image'] ?? [];
?><div class="elementrify-parallax" data-speed="<?php echo esc_attr($s['speed']['size'] ?? 0.5); ?>"><?php if (!empty($image['url'])) : ?><div class="elementrify-parallax__bg" style="background-image:url(<?php echo esc_url($image['url']); ?>)"></div><?php endif; ?><div class="elementrify-parallax__content"><?php echo wp_kses_post($s['content'] ?? ''); ?></div></div>
