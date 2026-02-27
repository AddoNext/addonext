<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
?><div class="elementrify-before-after"><div class="elementrify-before-after__before"><?php if (!empty($s['image_before']['url'])) : ?><img src="<?php echo esc_url($s['image_before']['url']); ?>" alt="Before"><?php endif; ?></div><div class="elementrify-before-after__after"><?php if (!empty($s['image_after']['url'])) : ?><img src="<?php echo esc_url($s['image_after']['url']); ?>" alt="After"><?php endif; ?></div></div>
