<?php
defined('ABSPATH') || exit;

use Elementor\Utils;

$addonext_settings = $this->get_settings_for_display();
$addonext_title = isset($addonext_settings['title']) ? (string) $addonext_settings['title'] : '';
$addonext_subtitle = isset($addonext_settings['subtitle']) ? (string) $addonext_settings['subtitle'] : '';
$addonext_title_tag = Utils::validate_html_tag($addonext_settings['title_tag'] ?? 'h2');
$addonext_buttons = isset($addonext_settings['buttons']) && is_array($addonext_settings['buttons']) ? $addonext_settings['buttons'] : [];

$addonext_bg_mode = isset($addonext_settings['panel_bg_background']) ? (string) $addonext_settings['panel_bg_background'] : '';
$addonext_video_link = isset($addonext_settings['panel_bg_video_link']) ? trim((string) $addonext_settings['panel_bg_video_link']) : '';

$addonext_youtube_id = '';
if ($addonext_video_link !== '' && preg_match('#(?:youtube\.com/watch\?v=|youtu\.be/)([a-zA-Z0-9_-]{6,})#', $addonext_video_link, $addonext_m)) {
    $addonext_youtube_id = $addonext_m[1];
} elseif ($addonext_video_link !== '' && preg_match('#youtube\.com/embed/([a-zA-Z0-9_-]{6,})#', $addonext_video_link, $addonext_m)) {
    $addonext_youtube_id = $addonext_m[1];
}

$addonext_vimeo_id = '';
if ($addonext_video_link !== '' && preg_match('#vimeo\.com/(?:video/)?(\d+)#', $addonext_video_link, $addonext_m)) {
    $addonext_vimeo_id = $addonext_m[1];
}

$addonext_is_file_video = $addonext_video_link !== '' && preg_match('#\.(mp4|webm|ogg)(\?|#|$)#i', $addonext_video_link);
$addonext_video_mime = 'video/mp4';
if ($addonext_is_file_video) {
    $addonext_path = (string) wp_parse_url($addonext_video_link, PHP_URL_PATH);
    $addonext_ext = strtolower(pathinfo($addonext_path, PATHINFO_EXTENSION));
    $addonext_map = ['mp4' => 'video/mp4', 'webm' => 'video/webm', 'ogg' => 'video/ogg'];
    if (isset($addonext_map[$addonext_ext])) {
        $addonext_video_mime = $addonext_map[$addonext_ext];
    }
}
?>
<div class="addonext-banner">
    <div class="addonext-banner__bg">
        <?php if ($addonext_bg_mode === 'video' && $addonext_is_file_video) : ?>
            <video class="addonext-banner__video" autoplay muted playsinline loop preload="metadata">
                <source src="<?php echo esc_url($addonext_video_link); ?>" type="<?php echo esc_attr($addonext_video_mime); ?>" />
            </video>
        <?php elseif ($addonext_bg_mode === 'video' && $addonext_youtube_id !== '') : ?>
            <div class="addonext-banner__video addonext-banner__video--youtube">
                <iframe
                    src="<?php echo esc_url('https://www.youtube-nocookie.com/embed/' . $addonext_youtube_id . '?autoplay=1&mute=1&controls=0&playsinline=1&rel=0&loop=1&playlist=' . rawurlencode($addonext_youtube_id)); ?>"
                    title="<?php echo esc_attr__('Background video', 'addonext'); ?>"
                    loading="lazy"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
            </div>
        <?php elseif ($addonext_bg_mode === 'video' && $addonext_vimeo_id !== '') : ?>
            <div class="addonext-banner__video addonext-banner__video--vimeo">
                <iframe
                    src="<?php echo esc_url('https://player.vimeo.com/video/' . $addonext_vimeo_id . '?autoplay=1&muted=1&background=1&loop=1'); ?>"
                    title="<?php echo esc_attr__('Background video', 'addonext'); ?>"
                    loading="lazy"
                    allow="autoplay; fullscreen; picture-in-picture"
                    allowfullscreen></iframe>
            </div>
        <?php endif; ?>
    </div>
    <div class="addonext-banner__overlay" aria-hidden="true"></div>
    <div class="addonext-banner__inner">
        <?php if ($addonext_title !== '') : ?>
            <<?php echo esc_attr($addonext_title_tag); ?> class="addonext-banner__title"><?php echo esc_html($addonext_title); ?></<?php echo esc_attr($addonext_title_tag); ?>>
        <?php endif; ?>
        <?php if ($addonext_subtitle !== '') : ?>
            <p class="addonext-banner__subtitle"><?php echo esc_html($addonext_subtitle); ?></p>
        <?php endif; ?>
        <?php if ($addonext_buttons !== []) : ?>
            <div class="addonext-banner__actions">
                <?php foreach ($addonext_buttons as $addonext_index => $addonext_row) : ?>
                    <?php
                    if (!is_array($addonext_row)) {
                        continue;
                    }
                    $addonext_text = isset($addonext_row['button_text']) ? (string) $addonext_row['button_text'] : '';
                    $addonext_type = isset($addonext_row['button_type']) && $addonext_row['button_type'] === 'outlined' ? 'outlined' : 'solid';
                    $addonext_link = isset($addonext_row['button_link']) && is_array($addonext_row['button_link']) ? $addonext_row['button_link'] : [];
                    if ($addonext_text === '') {
                        continue;
                    }
                    $addonext_url = isset($addonext_link['url']) ? trim((string) $addonext_link['url']) : '';
                    if ($addonext_url === '') {
                        $addonext_link['url'] = '#';
                    }
                    $addonext_key = 'banner_btn_' . (int) $addonext_index;
                    $this->add_render_attribute($addonext_key, 'class', 'addonext-banner__btn addonext-banner__btn--' . $addonext_type);
                    $this->add_link_attributes($addonext_key, $addonext_link);
                    ?>
                    <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor link attribute string. ?>
                    <a <?php echo $this->get_render_attribute_string($addonext_key); ?>><?php echo esc_html($addonext_text); ?></a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
