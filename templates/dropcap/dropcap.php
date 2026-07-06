<?php
defined('ABSPATH') || exit;
$addonext_s = $this->get_settings_for_display();
$addonext_text = $addonext_s['text'] ?? '';
$addonext_fill  = $addonext_s['dropcap_fill'] ?? 'solid';
$addonext_first = mb_substr($addonext_text, 0, 1);
$addonext_rest = mb_substr($addonext_text, 1);
$addonext_wrapper_classes = 'addonext-dropcap';
if ($addonext_fill === 'gradient') {
    $addonext_wrapper_classes .= ' addonext-dropcap--gradient';
}
?><p class="<?php echo esc_attr($addonext_wrapper_classes); ?>"><span class="addonext-dropcap__letter"><?php echo esc_html($addonext_first); ?></span><span class="addonext-dropcap__text"><?php echo esc_html($addonext_rest); ?></span></p>
