<?php
defined('ABSPATH') || exit;

$veloria_s = $this->get_settings_for_display();

$veloria_source = $veloria_s['source'] ?? 'url';
if ($veloria_source === 'file') {
    $veloria_file = $veloria_s['json_file'] ?? [];
    $veloria_url  = is_array($veloria_file) && !empty($veloria_file['url']) ? (string) $veloria_file['url'] : '';
} else {
    $veloria_url = isset($veloria_s['json_url']) ? trim((string) $veloria_s['json_url']) : '';
}

if ($veloria_url === '') {
    $veloria_url = \Veloria\Widgets\LottieAnimation\LottieAnimation::DEFAULT_JSON_URL;
}

$veloria_jsonUrl = esc_url($veloria_url);
if ($veloria_jsonUrl === '') {
    return;
}

$veloria_settingsJson = $this->build_lottie_settings_json($veloria_s);

$veloria_inner = sprintf(
    '<div class="veloria-lottie-animation" data-settings="%s" data-json-url="%s"></div>',
    esc_attr($veloria_settingsJson),
    $veloria_jsonUrl
);

$veloria_linkOn = !empty($veloria_s['link_switcher']) && $veloria_s['link_switcher'] === 'yes';
$veloria_link   = $veloria_s['link'] ?? [];

if ($veloria_linkOn && is_array($veloria_link) && !empty($veloria_link['url'])) {
    $veloria_href = esc_url($veloria_link['url']);
    if ($veloria_href !== '') {
        $veloria_target     = !empty($veloria_link['is_external']) ? ' target="_blank"' : '';
        $veloria_relParts   = [];
        if (!empty($veloria_link['is_external'])) {
            $veloria_relParts[] = 'noopener';
            $veloria_relParts[] = 'noreferrer';
        }
        if (!empty($veloria_link['nofollow'])) {
            $veloria_relParts[] = 'nofollow';
        }
        $veloria_relAttr = $veloria_relParts !== [] ? ' rel="' . esc_attr(implode(' ', $veloria_relParts)) . '"' : '';
        $veloria_inner   = sprintf(
            '<a class="veloria-lottie-animation__link" href="%s"%s%s>%s</a>',
            $veloria_href,
            $veloria_target,
            $veloria_relAttr,
            $veloria_inner
        );
    }
}

printf(
    '<div class="veloria-lottie-animation__wrapper">%s</div>',
    $veloria_inner // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- href/attrs escaped above
);
