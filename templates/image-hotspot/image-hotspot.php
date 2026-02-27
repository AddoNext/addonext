<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
$image = $s['image'] ?? [];
$hotspots = isset($s['hotspots']) && is_array($s['hotspots']) ? $s['hotspots'] : [];
?><div class="elementrify-image-hotspot"><?php if (!empty($image['url'])) : ?><img src="<?php echo esc_url($image['url']); ?>" alt=""><?php endif; ?><?php foreach ($hotspots as $h) : ?><span class="elementrify-image-hotspot__point" style="left:<?php echo esc_attr($h['x']['size'] ?? 50); ?>%;top:<?php echo esc_attr($h['y']['size'] ?? 50); ?>%" title="<?php echo esc_attr($h['tooltip'] ?? ''); ?>"></span><?php endforeach; ?></div>
