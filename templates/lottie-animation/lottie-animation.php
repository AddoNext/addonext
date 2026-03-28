<?php
defined('ABSPATH') || exit;

$s = $this->get_settings_for_display();

$source = $s['source'] ?? 'url';
if ($source === 'file') {
    $file = $s['json_file'] ?? [];
    $url  = is_array($file) && !empty($file['url']) ? (string) $file['url'] : '';
} else {
    $url = isset($s['json_url']) ? trim((string) $s['json_url']) : '';
}

if ($url === '') {
    $url = \Elementrify\Widgets\LottieAnimation\LottieAnimation::DEFAULT_JSON_URL;
}

$jsonUrl = esc_url($url);
if ($jsonUrl === '') {
    return;
}

$settingsJson = $this->build_lottie_settings_json($s);

$inner = sprintf(
    '<div class="elementrify-lottie-animation" data-settings="%s" data-json-url="%s"></div>',
    esc_attr($settingsJson),
    $jsonUrl
);

$linkOn = !empty($s['link_switcher']) && $s['link_switcher'] === 'yes';
$link   = $s['link'] ?? [];

if ($linkOn && is_array($link) && !empty($link['url'])) {
    $href = esc_url($link['url']);
    if ($href !== '') {
        $target     = !empty($link['is_external']) ? ' target="_blank"' : '';
        $relParts   = [];
        if (!empty($link['is_external'])) {
            $relParts[] = 'noopener';
            $relParts[] = 'noreferrer';
        }
        if (!empty($link['nofollow'])) {
            $relParts[] = 'nofollow';
        }
        $relAttr = $relParts !== [] ? ' rel="' . esc_attr(implode(' ', $relParts)) . '"' : '';
        $inner   = sprintf(
            '<a class="elementrify-lottie-animation__link" href="%s"%s%s>%s</a>',
            $href,
            $target,
            $relAttr,
            $inner
        );
    }
}

printf(
    '<div class="elementrify-lottie-animation__wrapper">%s</div>',
    $inner // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- href/attrs escaped above
);
