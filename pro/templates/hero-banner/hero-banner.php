<?php
defined('ABSPATH') || exit;

use Elementor\Icons_Manager;
use Elementor\Utils;
use Veloria\Widgets\HeroBanner\HeroBanner;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$veloria_settings = $this->get_settings_for_display();

$veloria_tag_prefix = isset($veloria_settings['tagline_prefix']) ? (string) $veloria_settings['tagline_prefix'] : '';
$veloria_tag_accent = isset($veloria_settings['tagline_accent']) ? (string) $veloria_settings['tagline_accent'] : '';
if (trim($veloria_tag_accent) === '') {
    $veloria_name = get_bloginfo('name', 'display');
    $veloria_tag_accent = is_string($veloria_name) && trim($veloria_name) !== '' ? $veloria_name : '';
}
$veloria_title = isset($veloria_settings['title']) ? (string) $veloria_settings['title'] : '';
[$veloria_title_static, $veloria_title_typing] = HeroBanner::split_title_last_two_words($veloria_title);
$veloria_subtitle = isset($veloria_settings['subtitle']) ? (string) $veloria_settings['subtitle'] : '';
$veloria_title_tag = Utils::validate_html_tag($veloria_settings['title_tag'] ?? 'h1');
$veloria_typing_ms = isset($veloria_settings['title_typing_speed']) ? max(20, min(200, (int) $veloria_settings['title_typing_speed'])) : 55;
$veloria_typing_loop = !empty($veloria_settings['title_typing_loop']) && $veloria_settings['title_typing_loop'] === 'yes';
$veloria_show_line = !empty($veloria_settings['show_accent_line']) && $veloria_settings['show_accent_line'] === 'yes';
$veloria_buttons = isset($veloria_settings['buttons']) && is_array($veloria_settings['buttons']) ? $veloria_settings['buttons'] : [];

$veloria_bg_mode = isset($veloria_settings['hb_panel_bg_background']) ? (string) $veloria_settings['hb_panel_bg_background'] : '';
$veloria_video_link = isset($veloria_settings['hb_panel_bg_video_link']) ? trim((string) $veloria_settings['hb_panel_bg_video_link']) : '';

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
    $veloria_path = (string) parse_url($veloria_video_link, PHP_URL_PATH);
    $veloria_ext = strtolower(pathinfo($veloria_path, PATHINFO_EXTENSION));
    $veloria_map = ['mp4' => 'video/mp4', 'webm' => 'video/webm', 'ogg' => 'video/ogg'];
    if (isset($veloria_map[$veloria_ext])) {
        $veloria_video_mime = $veloria_map[$veloria_ext];
    }
}
?>
<div class="veloria-hero-banner" data-typing-ms="<?php echo esc_attr((string) $veloria_typing_ms); ?>" data-typing-loop="<?php echo $veloria_typing_loop ? '1' : '0'; ?>">
    <div class="veloria-hero-banner__bg">
        <?php if ($veloria_bg_mode === 'video' && $veloria_is_file_video) : ?>
            <video class="veloria-hero-banner__video" autoplay muted playsinline loop preload="metadata">
                <source src="<?php echo esc_url($veloria_video_link); ?>" type="<?php echo esc_attr($veloria_video_mime); ?>" />
            </video>
        <?php elseif ($veloria_bg_mode === 'video' && $veloria_youtube_id !== '') : ?>
            <div class="veloria-hero-banner__video veloria-hero-banner__video--youtube">
                <iframe
                    src="<?php echo esc_url('https://www.youtube-nocookie.com/embed/' . $veloria_youtube_id . '?autoplay=1&mute=1&controls=0&playsinline=1&rel=0&loop=1&playlist=' . rawurlencode($veloria_youtube_id)); ?>"
                    title="<?php echo esc_attr__('Background video', 'veloria'); ?>"
                    loading="lazy"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
            </div>
        <?php elseif ($veloria_bg_mode === 'video' && $veloria_vimeo_id !== '') : ?>
            <div class="veloria-hero-banner__video veloria-hero-banner__video--vimeo">
                <iframe
                    src="<?php echo esc_url('https://player.vimeo.com/video/' . $veloria_vimeo_id . '?autoplay=1&muted=1&background=1&loop=1'); ?>"
                    title="<?php echo esc_attr__('Background video', 'veloria'); ?>"
                    loading="lazy"
                    allow="autoplay; fullscreen; picture-in-picture"
                    allowfullscreen></iframe>
            </div>
        <?php endif; ?>
    </div>
    <div class="veloria-hero-banner__overlay" aria-hidden="true"></div>
    <div class="veloria-hero-banner__inner">
        <div class="veloria-hero-banner__content">
            <?php if ($veloria_tag_prefix !== '' || $veloria_tag_accent !== '') : ?>
                <p class="veloria-hero-banner__tagline">
                    <?php if ($veloria_tag_prefix !== '') : ?>
                        <span class="veloria-hero-banner__tagline-prefix"><?php echo esc_html($veloria_tag_prefix); ?></span>
                    <?php endif; ?>
                    <?php if ($veloria_tag_prefix !== '' && $veloria_tag_accent !== '') : ?>
                        <?php echo ' '; ?>
                    <?php endif; ?>
                    <?php if ($veloria_tag_accent !== '') : ?>
                        <span class="veloria-hero-banner__tagline-accent"><span class="veloria-hero-banner__tagline-accent-text"><?php echo esc_html($veloria_tag_accent); ?></span></span>
                    <?php endif; ?>
                </p>
            <?php endif; ?>
            <?php if ($veloria_title !== '') : ?>
                <<?php echo esc_attr($veloria_title_tag); ?> class="veloria-hero-banner__title" aria-label="<?php echo esc_attr($veloria_title); ?>">
                    <?php if ($veloria_title_static !== '') : ?>
                        <span class="veloria-hero-banner__title-static"><?php echo esc_html($veloria_title_static); ?> </span>
                    <?php endif; ?>
                    <?php if ($veloria_title_typing !== '') : ?>
                        <span class="veloria-hero-banner__title-typing-wrap">
                            <span class="veloria-hero-banner__title-typing" data-text="<?php echo esc_attr($veloria_title_typing); ?>"></span><span class="veloria-hero-banner__title-cursor" aria-hidden="true"></span>
                        </span>
                    <?php endif; ?>
                </<?php echo esc_attr($veloria_title_tag); ?>>
            <?php endif; ?>
            <?php if ($veloria_show_line || $veloria_subtitle !== '') : ?>
                <div class="veloria-hero-banner__subrow-wrap">
                    <div class="veloria-hero-banner__subrow">
                        <?php if ($veloria_show_line) : ?>
                            <span class="veloria-hero-banner__accent-line" aria-hidden="true"></span>
                        <?php endif; ?>
                        <?php if ($veloria_subtitle !== '') : ?>
                            <p class="veloria-hero-banner__subtitle"><?php echo esc_html($veloria_subtitle); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($veloria_buttons !== []) : ?>
                <div class="veloria-hero-banner__actions">
                    <?php foreach ($veloria_buttons as $veloria_index => $veloria_row) : ?>
                        <?php
                        if (!is_array($veloria_row)) {
                            continue;
                        }
                        $veloria_text = isset($veloria_row['button_text']) ? (string) $veloria_row['button_text'] : '';
                        $veloria_type = isset($veloria_row['button_type']) && $veloria_row['button_type'] === 'outlined' ? 'outlined' : 'solid';
                        $veloria_link = isset($veloria_row['button_link']) && is_array($veloria_row['button_link']) ? $veloria_row['button_link'] : [];
                        $veloria_icon = isset($veloria_row['selected_icon']) ? $veloria_row['selected_icon'] : [];
                        $veloria_show_icon = !empty($veloria_row['show_icon']) && $veloria_row['show_icon'] === 'yes';
                        if ($veloria_text === '') {
                            continue;
                        }
                        $veloria_url = isset($veloria_link['url']) ? trim((string) $veloria_link['url']) : '';
                        if ($veloria_url === '') {
                            $veloria_link['url'] = '#';
                        }
                        $veloria_key = 'hero_banner_btn_' . (int) $veloria_index;
                        $this->add_render_attribute($veloria_key, 'class', 'veloria-hero-banner__btn veloria-hero-banner__btn--' . $veloria_type);
                        $this->add_link_attributes($veloria_key, $veloria_link);
                        ?>
                        <a <?php echo $this->get_render_attribute_string($veloria_key); ?>>
                            <span class="veloria-hero-banner__btn-inner">
                                <span class="veloria-hero-banner__btn-text"><?php echo esc_html($veloria_text); ?></span>
                                <?php if ($veloria_show_icon && !empty($veloria_icon['value'])) : ?>
                                    <span class="veloria-hero-banner__btn-icon" aria-hidden="true">
                                        <?php Icons_Manager::render_icon($veloria_icon, ['aria-hidden' => 'true']); ?>
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

<?php
    }
}
