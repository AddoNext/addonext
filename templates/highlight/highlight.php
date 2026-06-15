<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$content  = isset($settings['content']) ? (string) $settings['content'] : '';
$highlights = isset($settings['highlights']) && is_array($settings['highlights']) ? $settings['highlights'] : '';
$html = \Veloria\Widgets\Highlight\Highlight::build_highlighted_content($content, $highlights);
?>
<p class="veloria-highlight__content"><?php
echo wp_kses(
    $html,
    [
        'span' => [
            'class' => true,
            'style' => true,
        ],
    ]
);
?></p>
