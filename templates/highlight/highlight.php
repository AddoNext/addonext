<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$content  = isset($settings['content']) ? (string) $settings['content'] : '';
$highlights = isset($settings['highlights']) && is_array($settings['highlights']) ? $settings['highlights'] : '';
$html = \Elementrify\Widgets\Highlight\Highlight::build_highlighted_content($content, $highlights);
?>
<p class="elementrify-highlight__content"><?php echo $html; ?></p>
