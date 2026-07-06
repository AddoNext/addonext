<?php
defined('ABSPATH') || exit;

$addonext_settings = $this->get_settings_for_display();
$addonext_content  = isset($addonext_settings['content']) ? (string) $addonext_settings['content'] : '';
$addonext_highlights = isset($addonext_settings['highlights']) && is_array($addonext_settings['highlights']) ? $addonext_settings['highlights'] : '';
$addonext_html = \Addonext\Widgets\Highlight\Highlight::build_highlighted_content($addonext_content, $addonext_highlights);
?>
<p class="addonext-highlight__content"><?php
echo wp_kses(
    $addonext_html,
    [
        'span' => [
            'class' => true,
            'style' => true,
        ],
    ]
);
?></p>
