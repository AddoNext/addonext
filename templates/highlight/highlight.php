<?php
defined('ABSPATH') || exit;

$veloria_settings = $this->get_settings_for_display();
$veloria_content  = isset($veloria_settings['content']) ? (string) $veloria_settings['content'] : '';
$veloria_highlights = isset($veloria_settings['highlights']) && is_array($veloria_settings['highlights']) ? $veloria_settings['highlights'] : '';
$veloria_html = \Veloria\Widgets\Highlight\Highlight::build_highlighted_content($veloria_content, $veloria_highlights);
?>
<p class="veloria-highlight__content"><?php
echo wp_kses(
    $veloria_html,
    [
        'span' => [
            'class' => true,
            'style' => true,
        ],
    ]
);
?></p>
