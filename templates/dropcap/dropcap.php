<?php
defined('ABSPATH') || exit;
$veloria_s = $this->get_settings_for_display();
$veloria_text = $veloria_s['text'] ?? '';
$veloria_fill  = $veloria_s['dropcap_fill'] ?? 'solid';
$veloria_first = mb_substr($veloria_text, 0, 1);
$veloria_rest = mb_substr($veloria_text, 1);
$veloria_wrapper_classes = 'veloria-dropcap';
if ($veloria_fill === 'gradient') {
    $veloria_wrapper_classes .= ' veloria-dropcap--gradient';
}
?><p class="<?php echo esc_attr($veloria_wrapper_classes); ?>"><span class="veloria-dropcap__letter"><?php echo esc_html($veloria_first); ?></span><span class="veloria-dropcap__text"><?php echo esc_html($veloria_rest); ?></span></p>
