<?php
defined('ABSPATH') || exit;

$addonext_s = $this->get_settings_for_display();

$addonext_source = $addonext_s['source'] ?? 'url';
if ($addonext_source === 'file') {
    $addonext_file = $addonext_s['json_file'] ?? [];
    $addonext_url  = is_array($addonext_file) && !empty($addonext_file['url']) ? (string) $addonext_file['url'] : '';
} else {
    $addonext_url = isset($addonext_s['json_url']) ? trim((string) $addonext_s['json_url']) : '';
}

if ($addonext_url === '') {
    $addonext_url = \Addonext\Widgets\LottieAnimation\LottieAnimation::DEFAULT_JSON_URL;
}

$addonext_jsonUrl = esc_url($addonext_url);
if ($addonext_jsonUrl === '') {
    return;
}

$addonext_settingsJson = $this->build_lottie_settings_json($addonext_s);

$addonext_inner = sprintf(
    '<div class="addonext-lottie-animation" data-settings="%s" data-json-url="%s"></div>',
    esc_attr($addonext_settingsJson),
    $addonext_jsonUrl
);

$addonext_linkOn = !empty($addonext_s['link_switcher']) && $addonext_s['link_switcher'] === 'yes';
$addonext_link   = $addonext_s['link'] ?? [];

if ($addonext_linkOn && is_array($addonext_link) && !empty($addonext_link['url'])) {
    $addonext_href = esc_url($addonext_link['url']);
    if ($addonext_href !== '') {
        $addonext_target     = !empty($addonext_link['is_external']) ? ' target="_blank"' : '';
        $addonext_relParts   = [];
        if (!empty($addonext_link['is_external'])) {
            $addonext_relParts[] = 'noopener';
            $addonext_relParts[] = 'noreferrer';
        }
        if (!empty($addonext_link['nofollow'])) {
            $addonext_relParts[] = 'nofollow';
        }
        $addonext_relAttr = $addonext_relParts !== [] ? ' rel="' . esc_attr(implode(' ', $addonext_relParts)) . '"' : '';
        $addonext_inner   = sprintf(
            '<a class="addonext-lottie-animation__link" href="%s"%s%s>%s</a>',
            $addonext_href,
            $addonext_target,
            $addonext_relAttr,
            $addonext_inner
        );
    }
}

printf(
    '<div class="addonext-lottie-animation__wrapper">%s</div>',
    $addonext_inner // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- href/attrs escaped above
);
