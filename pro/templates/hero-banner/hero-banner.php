<?php
defined('ABSPATH') || exit;

use Elementor\Icons_Manager;
use Elementor\Utils;
use Addonext\Widgets\HeroBanner\HeroBanner;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$addonext_settings = $this->get_settings_for_display();

$addonext_tag_prefix = isset($addonext_settings['tagline_prefix']) ? (string) $addonext_settings['tagline_prefix'] : '';
$addonext_tag_accent = isset($addonext_settings['tagline_accent']) ? (string) $addonext_settings['tagline_accent'] : '';
if (trim($addonext_tag_accent) === '') {
    $addonext_name = get_bloginfo('name', 'display');
    $addonext_tag_accent = is_string($addonext_name) && trim($addonext_name) !== '' ? $addonext_name : '';
}
$addonext_title = isset($addonext_settings['title']) ? (string) $addonext_settings['title'] : '';
[$addonext_title_static, $addonext_title_typing] = HeroBanner::split_title_last_two_words($addonext_title);
$addonext_subtitle = isset($addonext_settings['subtitle']) ? (string) $addonext_settings['subtitle'] : '';
$addonext_title_tag = Utils::validate_html_tag($addonext_settings['title_tag'] ?? 'h1');
$addonext_typing_ms = isset($addonext_settings['title_typing_speed']) ? max(20, min(200, (int) $addonext_settings['title_typing_speed'])) : 55;
$addonext_typing_loop = !empty($addonext_settings['title_typing_loop']) && $addonext_settings['title_typing_loop'] === 'yes';
$addonext_show_line = !empty($addonext_settings['show_accent_line']) && $addonext_settings['show_accent_line'] === 'yes';
$addonext_buttons = isset($addonext_settings['buttons']) && is_array($addonext_settings['buttons']) ? $addonext_settings['buttons'] : [];

$addonext_bg_mode = isset($addonext_settings['hb_panel_bg_background']) ? (string) $addonext_settings['hb_panel_bg_background'] : '';
$addonext_video_link = isset($addonext_settings['hb_panel_bg_video_link']) ? trim((string) $addonext_settings['hb_panel_bg_video_link']) : '';

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
    $addonext_path = (string) parse_url($addonext_video_link, PHP_URL_PATH);
    $addonext_ext = strtolower(pathinfo($addonext_path, PATHINFO_EXTENSION));
    $addonext_map = ['mp4' => 'video/mp4', 'webm' => 'video/webm', 'ogg' => 'video/ogg'];
    if (isset($addonext_map[$addonext_ext])) {
        $addonext_video_mime = $addonext_map[$addonext_ext];
    }
}
?>
<div class="addonext-hero-banner" data-typing-ms="<?php echo esc_attr((string) $addonext_typing_ms); ?>" data-typing-loop="<?php echo $addonext_typing_loop ? '1' : '0'; ?>">
    <div class="addonext-hero-banner__bg">
        <?php if ($addonext_bg_mode === 'video' && $addonext_is_file_video) : ?>
            <video class="addonext-hero-banner__video" autoplay muted playsinline loop preload="metadata">
                <source src="<?php echo esc_url($addonext_video_link); ?>" type="<?php echo esc_attr($addonext_video_mime); ?>" />
            </video>
        <?php elseif ($addonext_bg_mode === 'video' && $addonext_youtube_id !== '') : ?>
            <div class="addonext-hero-banner__video addonext-hero-banner__video--youtube">
                <iframe
                    src="<?php echo esc_url('https://www.youtube-nocookie.com/embed/' . $addonext_youtube_id . '?autoplay=1&mute=1&controls=0&playsinline=1&rel=0&loop=1&playlist=' . rawurlencode($addonext_youtube_id)); ?>"
                    title="<?php echo esc_attr__('Background video', 'addonext'); ?>"
                    loading="lazy"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
            </div>
        <?php elseif ($addonext_bg_mode === 'video' && $addonext_vimeo_id !== '') : ?>
            <div class="addonext-hero-banner__video addonext-hero-banner__video--vimeo">
                <iframe
                    src="<?php echo esc_url('https://player.vimeo.com/video/' . $addonext_vimeo_id . '?autoplay=1&muted=1&background=1&loop=1'); ?>"
                    title="<?php echo esc_attr__('Background video', 'addonext'); ?>"
                    loading="lazy"
                    allow="autoplay; fullscreen; picture-in-picture"
                    allowfullscreen></iframe>
            </div>
        <?php endif; ?>
    </div>
    <div class="addonext-hero-banner__overlay" aria-hidden="true"></div>
    <div class="addonext-hero-banner__inner">
        <div class="addonext-hero-banner__content">
            <?php if ($addonext_tag_prefix !== '' || $addonext_tag_accent !== '') : ?>
                <p class="addonext-hero-banner__tagline">
                    <?php if ($addonext_tag_prefix !== '') : ?>
                        <span class="addonext-hero-banner__tagline-prefix"><?php echo esc_html($addonext_tag_prefix); ?></span>
                    <?php endif; ?>
                    <?php if ($addonext_tag_prefix !== '' && $addonext_tag_accent !== '') : ?>
                        <?php echo ' '; ?>
                    <?php endif; ?>
                    <?php if ($addonext_tag_accent !== '') : ?>
                        <span class="addonext-hero-banner__tagline-accent"><span class="addonext-hero-banner__tagline-accent-text"><?php echo esc_html($addonext_tag_accent); ?></span></span>
                    <?php endif; ?>
                </p>
            <?php endif; ?>
            <?php if ($addonext_title !== '') : ?>
                <<?php echo esc_attr($addonext_title_tag); ?> class="addonext-hero-banner__title" aria-label="<?php echo esc_attr($addonext_title); ?>">
                    <?php if ($addonext_title_static !== '') : ?>
                        <span class="addonext-hero-banner__title-static"><?php echo esc_html($addonext_title_static); ?> </span>
                    <?php endif; ?>
                    <?php if ($addonext_title_typing !== '') : ?>
                        <span class="addonext-hero-banner__title-typing-wrap">
                            <span class="addonext-hero-banner__title-typing" data-text="<?php echo esc_attr($addonext_title_typing); ?>"></span><span class="addonext-hero-banner__title-cursor" aria-hidden="true"></span>
                        </span>
                    <?php endif; ?>
                </<?php echo esc_attr($addonext_title_tag); ?>>
            <?php endif; ?>
            <?php if ($addonext_show_line || $addonext_subtitle !== '') : ?>
                <div class="addonext-hero-banner__subrow-wrap">
                    <div class="addonext-hero-banner__subrow">
                        <?php if ($addonext_show_line) : ?>
                            <span class="addonext-hero-banner__accent-line" aria-hidden="true"></span>
                        <?php endif; ?>
                        <?php if ($addonext_subtitle !== '') : ?>
                            <p class="addonext-hero-banner__subtitle"><?php echo esc_html($addonext_subtitle); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($addonext_buttons !== []) : ?>
                <div class="addonext-hero-banner__actions">
                    <?php foreach ($addonext_buttons as $addonext_index => $addonext_row) : ?>
                        <?php
                        if (!is_array($addonext_row)) {
                            continue;
                        }
                        $addonext_text = isset($addonext_row['button_text']) ? (string) $addonext_row['button_text'] : '';
                        $addonext_type = isset($addonext_row['button_type']) && $addonext_row['button_type'] === 'outlined' ? 'outlined' : 'solid';
                        $addonext_link = isset($addonext_row['button_link']) && is_array($addonext_row['button_link']) ? $addonext_row['button_link'] : [];
                        $addonext_icon = isset($addonext_row['selected_icon']) ? $addonext_row['selected_icon'] : [];
                        $addonext_show_icon = !empty($addonext_row['show_icon']) && $addonext_row['show_icon'] === 'yes';
                        if ($addonext_text === '') {
                            continue;
                        }
                        $addonext_url = isset($addonext_link['url']) ? trim((string) $addonext_link['url']) : '';
                        if ($addonext_url === '') {
                            $addonext_link['url'] = '#';
                        }
                        $addonext_key = 'hero_banner_btn_' . (int) $addonext_index;
                        $this->add_render_attribute($addonext_key, 'class', 'addonext-hero-banner__btn addonext-hero-banner__btn--' . $addonext_type);
                        $this->add_link_attributes($addonext_key, $addonext_link);
                        ?>
                        <a <?php echo $this->get_render_attribute_string($addonext_key); ?>>
                            <span class="addonext-hero-banner__btn-inner">
                                <span class="addonext-hero-banner__btn-text"><?php echo esc_html($addonext_text); ?></span>
                                <?php if ($addonext_show_icon && !empty($addonext_icon['value'])) : ?>
                                    <span class="addonext-hero-banner__btn-icon" aria-hidden="true">
                                        <?php Icons_Manager::render_icon($addonext_icon, ['aria-hidden' => 'true']); ?>
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
