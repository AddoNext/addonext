<?php
defined('ABSPATH') || exit;

use Elementor\Utils;

$settings = $this->get_settings_for_display();
$title = isset($settings['title']) ? (string) $settings['title'] : '';
$subtitle = isset($settings['subtitle']) ? (string) $settings['subtitle'] : '';
$title_tag = Utils::validate_html_tag($settings['title_tag'] ?? 'h2');
$buttons = isset($settings['buttons']) && is_array($settings['buttons']) ? $settings['buttons'] : [];

$bg_mode = isset($settings['panel_bg_background']) ? (string) $settings['panel_bg_background'] : '';
$video_link = isset($settings['panel_bg_video_link']) ? trim((string) $settings['panel_bg_video_link']) : '';

$youtube_id = '';
if ($video_link !== '' && preg_match('#(?:youtube\.com/watch\?v=|youtu\.be/)([a-zA-Z0-9_-]{6,})#', $video_link, $m)) {
    $youtube_id = $m[1];
} elseif ($video_link !== '' && preg_match('#youtube\.com/embed/([a-zA-Z0-9_-]{6,})#', $video_link, $m)) {
    $youtube_id = $m[1];
}

$vimeo_id = '';
if ($video_link !== '' && preg_match('#vimeo\.com/(?:video/)?(\d+)#', $video_link, $m)) {
    $vimeo_id = $m[1];
}

$is_file_video = $video_link !== '' && preg_match('#\.(mp4|webm|ogg)(\?|#|$)#i', $video_link);
$video_mime = 'video/mp4';
if ($is_file_video) {
    $path = (string) wp_parse_url($video_link, PHP_URL_PATH);
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    $map = ['mp4' => 'video/mp4', 'webm' => 'video/webm', 'ogg' => 'video/ogg'];
    if (isset($map[$ext])) {
        $video_mime = $map[$ext];
    }
}
?>
<div class="veloria-banner">
    <div class="veloria-banner__bg">
        <?php if ($bg_mode === 'video' && $is_file_video) : ?>
            <video class="veloria-banner__video" autoplay muted playsinline loop preload="metadata">
                <source src="<?php echo esc_url($video_link); ?>" type="<?php echo esc_attr($video_mime); ?>" />
            </video>
        <?php elseif ($bg_mode === 'video' && $youtube_id !== '') : ?>
            <div class="veloria-banner__video veloria-banner__video--youtube">
                <iframe
                    src="<?php echo esc_url('https://www.youtube-nocookie.com/embed/' . $youtube_id . '?autoplay=1&mute=1&controls=0&playsinline=1&rel=0&loop=1&playlist=' . rawurlencode($youtube_id)); ?>"
                    title="<?php echo esc_attr__('Background video', 'veloria'); ?>"
                    loading="lazy"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
            </div>
        <?php elseif ($bg_mode === 'video' && $vimeo_id !== '') : ?>
            <div class="veloria-banner__video veloria-banner__video--vimeo">
                <iframe
                    src="<?php echo esc_url('https://player.vimeo.com/video/' . $vimeo_id . '?autoplay=1&muted=1&background=1&loop=1'); ?>"
                    title="<?php echo esc_attr__('Background video', 'veloria'); ?>"
                    loading="lazy"
                    allow="autoplay; fullscreen; picture-in-picture"
                    allowfullscreen></iframe>
            </div>
        <?php endif; ?>
    </div>
    <div class="veloria-banner__overlay" aria-hidden="true"></div>
    <div class="veloria-banner__inner">
        <?php if ($title !== '') : ?>
            <<?php echo esc_attr($title_tag); ?> class="veloria-banner__title"><?php echo esc_html($title); ?></<?php echo esc_attr($title_tag); ?>>
        <?php endif; ?>
        <?php if ($subtitle !== '') : ?>
            <p class="veloria-banner__subtitle"><?php echo esc_html($subtitle); ?></p>
        <?php endif; ?>
        <?php if ($buttons !== []) : ?>
            <div class="veloria-banner__actions">
                <?php foreach ($buttons as $index => $row) : ?>
                    <?php
                    if (!is_array($row)) {
                        continue;
                    }
                    $text = isset($row['button_text']) ? (string) $row['button_text'] : '';
                    $type = isset($row['button_type']) && $row['button_type'] === 'outlined' ? 'outlined' : 'solid';
                    $link = isset($row['button_link']) && is_array($row['button_link']) ? $row['button_link'] : [];
                    if ($text === '') {
                        continue;
                    }
                    $url = isset($link['url']) ? trim((string) $link['url']) : '';
                    if ($url === '') {
                        $link['url'] = '#';
                    }
                    $key = 'banner_btn_' . (int) $index;
                    $this->add_render_attribute($key, 'class', 'veloria-banner__btn veloria-banner__btn--' . $type);
                    $this->add_link_attributes($key, $link);
                    ?>
                    <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor link attribute string. ?>
                    <a <?php echo $this->get_render_attribute_string($key); ?>><?php echo esc_html($text); ?></a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
