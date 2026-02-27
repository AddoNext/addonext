<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
?><nav class="elementrify-toc"><h3 class="elementrify-toc__title"><?php echo esc_html($s['title'] ?? __('Contents', 'elementrify')); ?></h3><ul class="elementrify-toc__list"><li><?php echo esc_html__('Auto-generated from page headings', 'elementrify'); ?></li></ul></nav>
