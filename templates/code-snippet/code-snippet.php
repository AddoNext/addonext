<?php
defined('ABSPATH') || exit;

$veloria_settings = $this->get_settings_for_display();
$veloria_filename = isset($veloria_settings['filename']) ? $veloria_settings['filename'] : 'main.js';
$veloria_language = isset($veloria_settings['language']) ? $veloria_settings['language'] : 'javascript';
$veloria_code = isset($veloria_settings['code']) ? (string) $veloria_settings['code'] : '';
$veloria_code = wp_strip_all_tags($veloria_code);
?>
<div class="veloria-code-snippet veloria-code-snippet--theme-dark">
    <div class="veloria-code-snippet__header">
        <div class="veloria-code-snippet__dots">
            <span class="veloria-code-snippet__dot veloria-code-snippet__dot--red"></span>
            <span class="veloria-code-snippet__dot veloria-code-snippet__dot--yellow"></span>
            <span class="veloria-code-snippet__dot veloria-code-snippet__dot--green"></span>
        </div>
        <div class="veloria-code-snippet__header-right">
            <button type="button" class="veloria-code-snippet__copy" aria-label="<?php echo esc_attr__('Copy code', 'veloria'); ?>">
                <span class="veloria-code-snippet__copy-icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></span>
                <span class="veloria-code-snippet__check-icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span>
            </button>
            <span class="veloria-code-snippet__filename"><?php echo esc_html($veloria_filename); ?></span>
        </div>
    </div>
    <pre class="veloria-code-snippet__pre">
        <code class="veloria-code-snippet__code" data-language="<?php echo esc_attr($veloria_language); ?>"><?php echo esc_html($veloria_code); ?></code>
        <div class="veloria-code-snippet__display" aria-hidden="true"></div>
    </pre>
</div>
