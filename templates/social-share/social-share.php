<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
$networks = isset($s['networks']) && is_array($s['networks']) ? $s['networks'] : [];
?><div class="elementrify-social-share"><?php foreach ($networks as $n) : ?><a href="#" class="elementrify-social-share__link elementrify-social-share__link--<?php echo esc_attr($n['network'] ?? ''); ?>" aria-label="Share on <?php echo esc_attr($n['network'] ?? ''); ?>"><?php echo esc_html($n['network'] ?? ''); ?></a><?php endforeach; ?></div>
