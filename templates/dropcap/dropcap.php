<?php
defined('ABSPATH') || exit;
$s = $this->get_settings_for_display();
$text = $s['text'] ?? '';
$fill  = $s['dropcap_fill'] ?? 'solid';
$first = mb_substr($text, 0, 1);
$rest = mb_substr($text, 1);
$wrapper_classes = 'elementrify-dropcap';
if ($fill === 'gradient') {
    $wrapper_classes .= ' elementrify-dropcap--gradient';
}
?><p class="<?php echo esc_attr($wrapper_classes); ?>"><span class="elementrify-dropcap__letter"><?php echo esc_html($first); ?></span><span class="elementrify-dropcap__text"><?php echo esc_html($rest); ?></span></p>
