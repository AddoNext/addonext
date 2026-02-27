<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
$items = isset($s['items']) && is_array($s['items']) ? $s['items'] : [];
?><div class="elementrify-faq"><?php foreach ($items as $i => $item) : ?><div class="elementrify-faq__item<?php echo $i === 0 ? ' is-open' : ''; ?>"><div class="elementrify-faq__question"><?php echo esc_html($item['question'] ?? ''); ?></div><div class="elementrify-faq__answer"><?php echo wp_kses_post($item['answer'] ?? ''); ?></div></div><?php endforeach; ?></div>
