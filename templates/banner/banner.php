<?php
defined('ABSPATH') || exit;

use Elementor\Utils;

$veloria_settings = $this->get_settings_for_display();
$veloria_title = isset($veloria_settings['title']) ? (string) $veloria_settings['title'] : '';
$veloria_subtitle = isset($veloria_settings['subtitle']) ? (string) $veloria_settings['subtitle'] : '';
$veloria_title_tag = Utils::validate_html_tag($veloria_settings['title_tag'] ?? 'h2');
$veloria_buttons = isset($veloria_settings['buttons']) && is_array($veloria_settings['buttons']) ? $veloria_settings['buttons'] : [];

$veloria_bg_mode = isset($veloria_settings['panel_bg_background']) ? (string) $veloria_settings['panel_bg_background'] : '';
$veloria_video_link = isset($veloria_settings['panel_bg_video_link']) ? trim((string) $veloria_settings['panel_bg_video_link']) : '';

$veloria_youtube_id = '';
if ($veloria_video_link !== '' && preg_match('#(?:youtube\.com/watch\?v=|youtu\.be/)([a-zA-Z0-9_-]{6,})#', $veloria_video_link, $veloria_m)) {
    $veloria_youtube_id = $veloria_m[1];
} elseif ($veloria_video_link !== '' && preg_match('#youtube\.com/embed/([a-zA-Z0-9_-]{6,})#', $veloria_video_link, $veloria_m)) {
    $veloria_youtube_id = $veloria_m[1];
}

$veloria_vimeo_id = '';
if ($veloria_video_link !== '' && preg_match('#vimeo\.com/(?:video/)?(\d+)#', $veloria_video_link, $veloria_m)) {
    $veloria_vimeo_id = $veloria_m[1];
}

$veloria_is_file_video = $veloria_video_link !== '' && preg_match('#\.(mp4|webm|ogg)(\?|#|$)#i', $veloria_video_link);
$veloria_video_mime = 'video/mp4';
if ($veloria_is_file_video) {
    $veloria_path = (string) wp_parse_url($veloria_video_link, PHP_URL_PATH);
    $veloria_ext = strtolower(pathinfo($veloria_path, PATHINFO_EXTENSION));
    $veloria_map = ['mp4' => 'video/mp4', 'webm' => 'video/webm', 'ogg' => 'video/ogg'];
    if (isset($veloria_map[$veloria_ext])) {
        $veloria_video_mime = $veloria_map[$veloria_ext];
    }
}
?>
<div class="veloria-banner">
    <div class="veloria-banner__bg">
        <?php if ($veloria_bg_mode === 'video' && $veloria_is_file_video) : ?>
            <video class="veloria-banner__video" autoplay muted playsinline loop preload="metadata">
                <source src="<?php echo esc_url($veloria_video_link); ?>" type="<?php echo esc_attr($veloria_video_mime); ?>" />
            </video>
        <?php elseif ($veloria_bg_mode === 'video' && $veloria_youtube_id !== '') : ?>
            <div class="veloria-banner__video veloria-banner__video--youtube">
                <iframe
                    src="<?php echo esc_url('https://www.youtube-nocookie.com/embed/' . $veloria_youtube_id . '?autoplay=1&mute=1&controls=0&playsinline=1&rel=0&loop=1&playlist=' . rawurlencode($veloria_youtube_id)); ?>"
                    title="<?php echo esc_attr__('Background video', 'veloria'); ?>"
                    loading="lazy"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
            </div>
        <?php elseif ($veloria_bg_mode === 'video' && $veloria_vimeo_id !== '') : ?>
            <div class="veloria-banner__video veloria-banner__video--vimeo">
                <iframe
                    src="<?php echo esc_url('https://player.vimeo.com/video/' . $veloria_vimeo_id . '?autoplay=1&muted=1&background=1&loop=1'); ?>"
                    title="<?php echo esc_attr__('Background video', 'veloria'); ?>"
                    loading="lazy"
                    allow="autoplay; fullscreen; picture-in-picture"
                    allowfullscreen></iframe>
            </div>
        <?php endif; ?>
    </div>
    <div class="veloria-banner__overlay" aria-hidden="true"></div>
    <div class="veloria-banner__inner">
        <?php if ($veloria_title !== '') : ?>
            <<?php echo esc_attr($veloria_title_tag); ?> class="veloria-banner__title"><?php echo esc_html($veloria_title); ?></<?php echo esc_attr($veloria_title_tag); ?>>
        <?php endif; ?>
        <?php if ($veloria_subtitle !== '') : ?>
            <p class="veloria-banner__subtitle"><?php echo esc_html($veloria_subtitle); ?></p>
        <?php endif; ?>
        <?php if ($veloria_buttons !== []) : ?>
            <div class="veloria-banner__actions">
                <?php foreach ($veloria_buttons as $veloria_index => $veloria_row) : ?>
                    <?php
                    if (!is_array($veloria_row)) {
                        continue;
                    }
                    $veloria_text = isset($veloria_row['button_text']) ? (string) $veloria_row['button_text'] : '';
                    $veloria_type = isset($veloria_row['button_type']) && $veloria_row['button_type'] === 'outlined' ? 'outlined' : 'solid';
                    $veloria_link = isset($veloria_row['button_link']) && is_array($veloria_row['button_link']) ? $veloria_row['button_link'] : [];
                    if ($veloria_text === '') {
                        continue;
                    }
                    $veloria_url = isset($veloria_link['url']) ? trim((string) $veloria_link['url']) : '';
                    if ($veloria_url === '') {
                        $veloria_link['url'] = '#';
                    }
                    $veloria_key = 'banner_btn_' . (int) $veloria_index;
                    $this->add_render_attribute($veloria_key, 'class', 'veloria-banner__btn veloria-banner__btn--' . $veloria_type);
                    $this->add_link_attributes($veloria_key, $veloria_link);
                    ?>
                    <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor link attribute string. ?>
                    <a <?php echo $this->get_render_attribute_string($veloria_key); ?>><?php echo esc_html($veloria_text); ?></a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
