<?php
defined('ABSPATH') || exit;

use Elementor\Icons_Manager;
use Elementor\Utils;
use Elementrify\Widgets\HeroBanner\HeroBanner;

$settings = $this->get_settings_for_display();

$tag_prefix = isset($settings['tagline_prefix']) ? (string) $settings['tagline_prefix'] : '';
$tag_accent = isset($settings['tagline_accent']) ? (string) $settings['tagline_accent'] : '';
if (trim($tag_accent) === '') {
    $name = get_bloginfo('name', 'display');
    $tag_accent = is_string($name) && trim($name) !== '' ? $name : '';
}
$title = isset($settings['title']) ? (string) $settings['title'] : '';
[$title_static, $title_typing] = HeroBanner::split_title_last_two_words($title);
$subtitle = isset($settings['subtitle']) ? (string) $settings['subtitle'] : '';
$title_tag = Utils::validate_html_tag($settings['title_tag'] ?? 'h1');
$typing_ms = isset($settings['title_typing_speed']) ? max(20, min(200, (int) $settings['title_typing_speed'])) : 55;
$typing_loop = !empty($settings['title_typing_loop']) && $settings['title_typing_loop'] === 'yes';
$show_line = !empty($settings['show_accent_line']) && $settings['show_accent_line'] === 'yes';
$buttons = isset($settings['buttons']) && is_array($settings['buttons']) ? $settings['buttons'] : [];

$bg_mode = isset($settings['hb_panel_bg_background']) ? (string) $settings['hb_panel_bg_background'] : '';
$video_link = isset($settings['hb_panel_bg_video_link']) ? trim((string) $settings['hb_panel_bg_video_link']) : '';

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
    $path = (string) parse_url($video_link, PHP_URL_PATH);
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    $map = ['mp4' => 'video/mp4', 'webm' => 'video/webm', 'ogg' => 'video/ogg'];
    if (isset($map[$ext])) {
        $video_mime = $map[$ext];
    }
}
?>
<div class="elementrify-hero-banner" data-typing-ms="<?php echo esc_attr((string) $typing_ms); ?>" data-typing-loop="<?php echo $typing_loop ? '1' : '0'; ?>">
    <div class="elementrify-hero-banner__bg">
        <?php if ($bg_mode === 'video' && $is_file_video) : ?>
            <video class="elementrify-hero-banner__video" autoplay muted playsinline loop preload="metadata">
                <source src="<?php echo esc_url($video_link); ?>" type="<?php echo esc_attr($video_mime); ?>" />
            </video>
        <?php elseif ($bg_mode === 'video' && $youtube_id !== '') : ?>
            <div class="elementrify-hero-banner__video elementrify-hero-banner__video--youtube">
                <iframe
                    src="<?php echo esc_url('https://www.youtube-nocookie.com/embed/' . $youtube_id . '?autoplay=1&mute=1&controls=0&playsinline=1&rel=0&loop=1&playlist=' . rawurlencode($youtube_id)); ?>"
                    title="<?php echo esc_attr__('Background video', 'elementrify'); ?>"
                    loading="lazy"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
            </div>
        <?php elseif ($bg_mode === 'video' && $vimeo_id !== '') : ?>
            <div class="elementrify-hero-banner__video elementrify-hero-banner__video--vimeo">
                <iframe
                    src="<?php echo esc_url('https://player.vimeo.com/video/' . $vimeo_id . '?autoplay=1&muted=1&background=1&loop=1'); ?>"
                    title="<?php echo esc_attr__('Background video', 'elementrify'); ?>"
                    loading="lazy"
                    allow="autoplay; fullscreen; picture-in-picture"
                    allowfullscreen></iframe>
            </div>
        <?php endif; ?>
    </div>
    <div class="elementrify-hero-banner__overlay" aria-hidden="true"></div>
    <div class="elementrify-hero-banner__inner">
        <div class="elementrify-hero-banner__content">
            <?php if ($tag_prefix !== '' || $tag_accent !== '') : ?>
                <p class="elementrify-hero-banner__tagline">
                    <?php if ($tag_prefix !== '') : ?>
                        <span class="elementrify-hero-banner__tagline-prefix"><?php echo esc_html($tag_prefix); ?></span>
                    <?php endif; ?>
                    <?php if ($tag_prefix !== '' && $tag_accent !== '') : ?>
                        <?php echo ' '; ?>
                    <?php endif; ?>
                    <?php if ($tag_accent !== '') : ?>
                        <span class="elementrify-hero-banner__tagline-accent"><span class="elementrify-hero-banner__tagline-accent-text"><?php echo esc_html($tag_accent); ?></span></span>
                    <?php endif; ?>
                </p>
            <?php endif; ?>
            <?php if ($title !== '') : ?>
                <<?php echo esc_attr($title_tag); ?> class="elementrify-hero-banner__title" aria-label="<?php echo esc_attr($title); ?>">
                    <?php if ($title_static !== '') : ?>
                        <span class="elementrify-hero-banner__title-static"><?php echo esc_html($title_static); ?> </span>
                    <?php endif; ?>
                    <?php if ($title_typing !== '') : ?>
                        <span class="elementrify-hero-banner__title-typing-wrap">
                            <span class="elementrify-hero-banner__title-typing" data-text="<?php echo esc_attr($title_typing); ?>"></span><span class="elementrify-hero-banner__title-cursor" aria-hidden="true"></span>
                        </span>
                    <?php endif; ?>
                </<?php echo esc_attr($title_tag); ?>>
            <?php endif; ?>
            <?php if ($show_line || $subtitle !== '') : ?>
                <div class="elementrify-hero-banner__subrow-wrap">
                    <div class="elementrify-hero-banner__subrow">
                        <?php if ($show_line) : ?>
                            <span class="elementrify-hero-banner__accent-line" aria-hidden="true"></span>
                        <?php endif; ?>
                        <?php if ($subtitle !== '') : ?>
                            <p class="elementrify-hero-banner__subtitle"><?php echo esc_html($subtitle); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($buttons !== []) : ?>
                <div class="elementrify-hero-banner__actions">
                    <?php foreach ($buttons as $index => $row) : ?>
                        <?php
                        if (!is_array($row)) {
                            continue;
                        }
                        $text = isset($row['button_text']) ? (string) $row['button_text'] : '';
                        $type = isset($row['button_type']) && $row['button_type'] === 'outlined' ? 'outlined' : 'solid';
                        $link = isset($row['button_link']) && is_array($row['button_link']) ? $row['button_link'] : [];
                        $icon = isset($row['selected_icon']) ? $row['selected_icon'] : [];
                        $show_icon = !empty($row['show_icon']) && $row['show_icon'] === 'yes';
                        if ($text === '') {
                            continue;
                        }
                        $url = isset($link['url']) ? trim((string) $link['url']) : '';
                        if ($url === '') {
                            $link['url'] = '#';
                        }
                        $key = 'hero_banner_btn_' . (int) $index;
                        $this->add_render_attribute($key, 'class', 'elementrify-hero-banner__btn elementrify-hero-banner__btn--' . $type);
                        $this->add_link_attributes($key, $link);
                        ?>
                        <a <?php echo $this->get_render_attribute_string($key); ?>>
                            <span class="elementrify-hero-banner__btn-inner">
                                <span class="elementrify-hero-banner__btn-text"><?php echo esc_html($text); ?></span>
                                <?php if ($show_icon && !empty($icon['value'])) : ?>
                                    <span class="elementrify-hero-banner__btn-icon" aria-hidden="true">
                                        <?php Icons_Manager::render_icon($icon, ['aria-hidden' => 'true']); ?>
                                    </span>
                                <?php endif; ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
