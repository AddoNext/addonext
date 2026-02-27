<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
$slides = isset($s['slides']) && is_array($s['slides']) ? $s['slides'] : [];
?><div class="elementrify-image-carousel"><?php foreach ($slides as $slide) : ?><div class="elementrify-image-carousel__slide"><?php if (!empty($slide['image']['url'])) : ?><img src="<?php echo esc_url($slide['image']['url']); ?>" alt="<?php echo esc_attr($slide['caption'] ?? ''); ?>"><?php endif; ?></div><?php endforeach; ?></div>
