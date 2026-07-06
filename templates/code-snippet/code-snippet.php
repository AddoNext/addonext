<?php
defined('ABSPATH') || exit;

$addonext_settings = $this->get_settings_for_display();
$addonext_filename = isset($addonext_settings['filename']) ? $addonext_settings['filename'] : 'main.js';
$addonext_language = isset($addonext_settings['language']) ? $addonext_settings['language'] : 'javascript';
$addonext_code = isset($addonext_settings['code']) ? (string) $addonext_settings['code'] : '';
$addonext_code = wp_strip_all_tags($addonext_code);
?>
<div class="addonext-code-snippet addonext-code-snippet--theme-dark">
    <div class="addonext-code-snippet__header">
        <div class="addonext-code-snippet__dots">
            <span class="addonext-code-snippet__dot addonext-code-snippet__dot--red"></span>
            <span class="addonext-code-snippet__dot addonext-code-snippet__dot--yellow"></span>
            <span class="addonext-code-snippet__dot addonext-code-snippet__dot--green"></span>
        </div>
        <div class="addonext-code-snippet__header-right">
            <button type="button" class="addonext-code-snippet__copy" aria-label="<?php echo esc_attr__('Copy code', 'addonext'); ?>">
                <span class="addonext-code-snippet__copy-icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></span>
                <span class="addonext-code-snippet__check-icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span>
            </button>
            <span class="addonext-code-snippet__filename"><?php echo esc_html($addonext_filename); ?></span>
        </div>
    </div>
    <pre class="addonext-code-snippet__pre">
        <code class="addonext-code-snippet__code" data-language="<?php echo esc_attr($addonext_language); ?>"><?php echo esc_html($addonext_code); ?></code>
        <div class="addonext-code-snippet__display" aria-hidden="true"></div>
    </pre>
</div>
