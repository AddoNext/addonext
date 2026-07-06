<?php
defined('ABSPATH') || exit;

use Elementor\Icons_Manager;
use Elementor\Utils;
use Addonext\Widgets\HeroBanner\HeroBanner;
use Addonext\Widgets\HeroSlider\HeroSlider;

$addonext_settings = $this->get_settings_for_display();

$addonext_mode = isset($addonext_settings['slider_mode']) ? (string) $addonext_settings['slider_mode'] : 'both';
if (!in_array($addonext_mode, ['background', 'content', 'both'], true)) {
    $addonext_mode = 'both';
}

$addonext_nav = isset($addonext_settings['navigation']) ? (string) $addonext_settings['navigation'] : 'none';
$addonext_autoplay = !empty($addonext_settings['autoplay']) && $addonext_settings['autoplay'] === 'yes';
$addonext_autoplay_delay = isset($addonext_settings['autoplay_delay']) ? max(1, min(30, (int) $addonext_settings['autoplay_delay'])) : 5;
$addonext_loop = !empty($addonext_settings['loop']) && $addonext_settings['loop'] === 'yes';
$addonext_speed = isset($addonext_settings['slider_speed']) ? max(200, min(2000, (int) $addonext_settings['slider_speed'])) : 600;

$addonext_show_arrows = in_array($addonext_nav, ['both', 'arrows'], true);
$addonext_show_dots = in_array($addonext_nav, ['both', 'dots'], true);

$addonext_root_attrs = [
    'class'               => 'addonext-hero-slider addonext-hero-slider--mode-' . $addonext_mode,
    'data-mode'           => $addonext_mode,
    'data-navigation'     => $addonext_nav,
    'data-autoplay'       => $addonext_autoplay ? '1' : '0',
    'data-autoplay-delay' => (string) $addonext_autoplay_delay,
    'data-loop'           => $addonext_loop ? '1' : '0',
    'data-speed'          => (string) $addonext_speed,
];

if ($addonext_mode === 'background') {
    $addonext_typing_ms_root = isset($addonext_settings['title_typing_speed']) ? max(20, min(200, (int) $addonext_settings['title_typing_speed'])) : 55;
    $addonext_typing_loop_root = !empty($addonext_settings['title_typing_loop']) && $addonext_settings['title_typing_loop'] === 'yes';
    $addonext_root_attrs['data-typing-ms'] = (string) $addonext_typing_ms_root;
    $addonext_root_attrs['data-typing-loop'] = $addonext_typing_loop_root ? '1' : '0';
}

$addonext_render_bg_inner = static function (array $addonext_row, string $addonext_prefix, string $addonext_inner_class, string $addonext_fallback_photo_url = ''): void {
    $addonext_v = HeroSlider::parseBgVideoState($addonext_row, $addonext_prefix);
    $addonext_img_key = $addonext_prefix . '_image';
    $addonext_img_url = isset($addonext_row[$addonext_img_key]['url']) ? trim((string) $addonext_row[$addonext_img_key]['url']) : '';
    $addonext_extra_attr = '';
    if ($addonext_v['bg_mode'] !== 'video' && $addonext_v['bg_mode'] !== 'gradient' && $addonext_img_url === '' && $addonext_fallback_photo_url !== '') {
        $addonext_extra_attr = ' style="background-image:url(' . esc_url($addonext_fallback_photo_url) . ');background-size:cover;background-position:center;background-repeat:no-repeat;"';
    }
    ?>
    <div class="<?php echo esc_attr($addonext_inner_class); ?>"<?php
    if ($addonext_extra_attr !== '') {
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Inline style; background URL uses esc_url().
        echo $addonext_extra_attr;
    }
    ?>>
        <?php if ($addonext_v['bg_mode'] === 'video' && $addonext_v['is_file_video']) : ?>
            <video class="addonext-hero-slider__video" autoplay muted playsinline loop preload="metadata">
                <source src="<?php echo esc_url($addonext_v['video_link']); ?>" type="<?php echo esc_attr($addonext_v['video_mime']); ?>" />
            </video>
        <?php elseif ($addonext_v['bg_mode'] === 'video' && $addonext_v['youtube_id'] !== '') : ?>
            <div class="addonext-hero-slider__video addonext-hero-slider__video--youtube">
                <iframe
                    src="<?php echo esc_url('https://www.youtube-nocookie.com/embed/' . $addonext_v['youtube_id'] . '?autoplay=1&mute=1&controls=0&playsinline=1&rel=0&loop=1&playlist=' . rawurlencode($addonext_v['youtube_id'])); ?>"
                    title="<?php echo esc_attr__('Background video', 'addonext'); ?>"
                    loading="lazy"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
            </div>
        <?php elseif ($addonext_v['bg_mode'] === 'video' && $addonext_v['vimeo_id'] !== '') : ?>
            <div class="addonext-hero-slider__video addonext-hero-slider__video--vimeo">
                <iframe
                    src="<?php echo esc_url('https://player.vimeo.com/video/' . $addonext_v['vimeo_id'] . '?autoplay=1&muted=1&background=1&loop=1'); ?>"
                    title="<?php echo esc_attr__('Background video', 'addonext'); ?>"
                    loading="lazy"
                    allow="autoplay; fullscreen; picture-in-picture"
                    allowfullscreen></iframe>
            </div>
        <?php endif; ?>
    </div>
    <?php
};

$addonext_widget = $this;
$addonext_render_buttons = static function (array $addonext_buttons, string $addonext_id_prefix) use ($addonext_widget): void {
    if ($addonext_buttons === []) {
        return;
    }
    ?>
    <div class="addonext-hero-slider__actions">
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
            $addonext_key = $addonext_id_prefix . '_' . (int) $addonext_index;
            $addonext_widget->add_render_attribute($addonext_key, 'class', 'addonext-hero-slider__btn addonext-hero-slider__btn--' . $addonext_type);
            $addonext_widget->add_link_attributes($addonext_key, $addonext_link);
            ?>
            <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor link attribute string. ?>
            <a <?php echo $addonext_widget->get_render_attribute_string($addonext_key); ?>>
                <span class="addonext-hero-slider__btn-inner">
                    <span class="addonext-hero-slider__btn-text"><?php echo esc_html($addonext_text); ?></span>
                    <?php if ($addonext_show_icon && !empty($addonext_icon['value'])) : ?>
                        <span class="addonext-hero-slider__btn-icon" aria-hidden="true">
                            <?php Icons_Manager::render_icon($addonext_icon, ['aria-hidden' => 'true']); ?>
                        </span>
                    <?php endif; ?>
                </span>
            </a>
        <?php endforeach; ?>
    </div>
    <?php
};

$addonext_render_slide_text_block = static function (array $addonext_slide, int $addonext_slide_index) use ($addonext_render_buttons): void {
    $addonext_tag_prefix = isset($addonext_slide['tagline_prefix']) ? (string) $addonext_slide['tagline_prefix'] : '';
    $addonext_tag_accent = isset($addonext_slide['tagline_accent']) ? (string) $addonext_slide['tagline_accent'] : '';
    if (trim($addonext_tag_accent) === '') {
        $addonext_name = get_bloginfo('name', 'display');
        $addonext_tag_accent = is_string($addonext_name) && trim($addonext_name) !== '' ? $addonext_name : '';
    }
    $addonext_title = isset($addonext_slide['title']) ? (string) $addonext_slide['title'] : '';
    [$addonext_title_static, $addonext_title_typing] = HeroBanner::split_title_last_two_words($addonext_title);
    $addonext_subtitle = isset($addonext_slide['subtitle']) ? (string) $addonext_slide['subtitle'] : '';
    $addonext_title_tag = Utils::validate_html_tag($addonext_slide['title_tag'] ?? 'h1');
    $addonext_typing_ms = isset($addonext_slide['title_typing_speed']) ? max(20, min(200, (int) $addonext_slide['title_typing_speed'])) : 55;
    $addonext_typing_loop = !empty($addonext_slide['title_typing_loop']) && $addonext_slide['title_typing_loop'] === 'yes';
    $addonext_show_line = !empty($addonext_slide['show_accent_line']) && $addonext_slide['show_accent_line'] === 'yes';
    $addonext_buttons = isset($addonext_slide['buttons']) && is_array($addonext_slide['buttons']) ? $addonext_slide['buttons'] : [];
    ?>
    <div class="addonext-hero-slider__content">
        <?php if ($addonext_tag_prefix !== '' || $addonext_tag_accent !== '') : ?>
            <p class="addonext-hero-slider__tagline">
                <?php if ($addonext_tag_prefix !== '') : ?>
                    <span class="addonext-hero-slider__tagline-prefix"><?php echo esc_html($addonext_tag_prefix); ?></span>
                <?php endif; ?>
                <?php if ($addonext_tag_prefix !== '' && $addonext_tag_accent !== '') : ?>
                    <?php echo ' '; ?>
                <?php endif; ?>
                <?php if ($addonext_tag_accent !== '') : ?>
                    <span class="addonext-hero-slider__tagline-accent"><span class="addonext-hero-slider__tagline-accent-text"><?php echo esc_html($addonext_tag_accent); ?></span></span>
                <?php endif; ?>
            </p>
        <?php endif; ?>
        <?php if ($addonext_title !== '') : ?>
            <<?php echo esc_attr($addonext_title_tag); ?> class="addonext-hero-slider__title" aria-label="<?php echo esc_attr($addonext_title); ?>">
                <?php if ($addonext_title_static !== '') : ?>
                    <span class="addonext-hero-slider__title-static"><?php echo esc_html($addonext_title_static); ?> </span>
                <?php endif; ?>
                <?php if ($addonext_title_typing !== '') : ?>
                    <span class="addonext-hero-slider__title-typing-wrap">
                        <span class="addonext-hero-slider__title-typing" data-text="<?php echo esc_attr($addonext_title_typing); ?>"></span><span class="addonext-hero-slider__title-cursor" aria-hidden="true"></span>
                    </span>
                <?php endif; ?>
            </<?php echo esc_attr($addonext_title_tag); ?>>
        <?php endif; ?>
        <?php if ($addonext_show_line || $addonext_subtitle !== '') : ?>
            <div class="addonext-hero-slider__subrow-wrap">
                <div class="addonext-hero-slider__subrow">
                    <?php if ($addonext_show_line) : ?>
                        <span class="addonext-hero-slider__accent-line" aria-hidden="true"></span>
                    <?php endif; ?>
                    <?php if ($addonext_subtitle !== '') : ?>
                        <p class="addonext-hero-slider__subtitle"><?php echo esc_html($addonext_subtitle); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        <?php $addonext_render_buttons($addonext_buttons, 'hs_btn_' . $addonext_slide_index); ?>
    </div>
    <?php
};

?>
<div<?php
foreach ($addonext_root_attrs as $addonext_attr => $addonext_val) {
    printf(' %s="%s"', esc_attr($addonext_attr), esc_attr($addonext_val));
}
echo '>';
?>
<?php if ($addonext_mode === 'background') : ?>
    <?php
    $addonext_bg_slides = isset($addonext_settings['bg_slides']) && is_array($addonext_settings['bg_slides']) ? $addonext_settings['bg_slides'] : [];
    if ($addonext_bg_slides === []) {
        $addonext_bg_slides = [[]];
    }
    ?>
    <div class="addonext-hero-slider__bg-swiper swiper">
        <div class="swiper-wrapper">
            <?php foreach ($addonext_bg_slides as $addonext_bi => $addonext_bg_row) : ?>
                <?php
                if (!is_array($addonext_bg_row)) {
                    continue;
                }
                $addonext_bg_rep_id = isset($addonext_bg_row['_id']) && (string) $addonext_bg_row['_id'] !== '' ? (string) $addonext_bg_row['_id'] : 'bg' . (int) $addonext_bi;
                ?>
                <div class="swiper-slide elementor-repeater-item-<?php echo esc_attr($addonext_bg_rep_id); ?>">
                    <?php $addonext_render_bg_inner($addonext_bg_row, 'bg_only_panel', 'addonext-hero-slider__bg-slide-inner', HeroSlider::demoBgImageUrl((int) $addonext_bi)); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="addonext-hero-slider__overlay" aria-hidden="true"></div>
    <div class="addonext-hero-slider__inner">
        <?php
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
        $addonext_show_line = !empty($addonext_settings['show_accent_line']) && $addonext_settings['show_accent_line'] === 'yes';
        $addonext_buttons = isset($addonext_settings['buttons']) && is_array($addonext_settings['buttons']) ? $addonext_settings['buttons'] : [];
        ?>
        <div class="addonext-hero-slider__content">
            <?php if ($addonext_tag_prefix !== '' || $addonext_tag_accent !== '') : ?>
                <p class="addonext-hero-slider__tagline">
                    <?php if ($addonext_tag_prefix !== '') : ?>
                        <span class="addonext-hero-slider__tagline-prefix"><?php echo esc_html($addonext_tag_prefix); ?></span>
                    <?php endif; ?>
                    <?php if ($addonext_tag_prefix !== '' && $addonext_tag_accent !== '') : ?>
                        <?php echo ' '; ?>
                    <?php endif; ?>
                    <?php if ($addonext_tag_accent !== '') : ?>
                        <span class="addonext-hero-slider__tagline-accent"><span class="addonext-hero-slider__tagline-accent-text"><?php echo esc_html($addonext_tag_accent); ?></span></span>
                    <?php endif; ?>
                </p>
            <?php endif; ?>
            <?php if ($addonext_title !== '') : ?>
                <<?php echo esc_attr($addonext_title_tag); ?> class="addonext-hero-slider__title" aria-label="<?php echo esc_attr($addonext_title); ?>">
                    <?php if ($addonext_title_static !== '') : ?>
                        <span class="addonext-hero-slider__title-static"><?php echo esc_html($addonext_title_static); ?> </span>
                    <?php endif; ?>
                    <?php if ($addonext_title_typing !== '') : ?>
                        <span class="addonext-hero-slider__title-typing-wrap">
                            <span class="addonext-hero-slider__title-typing" data-text="<?php echo esc_attr($addonext_title_typing); ?>"></span><span class="addonext-hero-slider__title-cursor" aria-hidden="true"></span>
                        </span>
                    <?php endif; ?>
                </<?php echo esc_attr($addonext_title_tag); ?>>
            <?php endif; ?>
            <?php if ($addonext_show_line || $addonext_subtitle !== '') : ?>
                <div class="addonext-hero-slider__subrow-wrap">
                    <div class="addonext-hero-slider__subrow">
                        <?php if ($addonext_show_line) : ?>
                            <span class="addonext-hero-slider__accent-line" aria-hidden="true"></span>
                        <?php endif; ?>
                        <?php if ($addonext_subtitle !== '') : ?>
                            <p class="addonext-hero-slider__subtitle"><?php echo esc_html($addonext_subtitle); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php $addonext_render_buttons($addonext_buttons, 'hs_static_btn'); ?>
        </div>
    </div>

<?php elseif ($addonext_mode === 'content') : ?>
    <div class="addonext-hero-slider__sticky-bg">
        <?php $addonext_render_bg_inner($addonext_settings, 'sticky_panel_bg', 'addonext-hero-slider__sticky-bg-inner', HeroSlider::demoBgImageUrl(0)); ?>
    </div>
    <div class="addonext-hero-slider__overlay" aria-hidden="true"></div>
    <div class="addonext-hero-slider__inner">
        <div class="addonext-hero-slider__content-swiper swiper">
            <div class="swiper-wrapper">
                <?php
                $addonext_content_slides = isset($addonext_settings['content_slides']) && is_array($addonext_settings['content_slides']) ? $addonext_settings['content_slides'] : [];
                if ($addonext_content_slides === []) {
                    $addonext_content_slides = [[]];
                }
                foreach ($addonext_content_slides as $addonext_si => $addonext_slide) :
                    if (!is_array($addonext_slide)) {
                        continue;
                    }
                    $addonext_typing_ms = isset($addonext_slide['title_typing_speed']) ? max(20, min(200, (int) $addonext_slide['title_typing_speed'])) : 55;
                    $addonext_typing_loop = !empty($addonext_slide['title_typing_loop']) && $addonext_slide['title_typing_loop'] === 'yes';
                    ?>
                    <div class="swiper-slide" data-typing-ms="<?php echo esc_attr((string) $addonext_typing_ms); ?>" data-typing-loop="<?php echo $addonext_typing_loop ? '1' : '0'; ?>">
                        <?php $addonext_render_slide_text_block($addonext_slide, (int) $addonext_si); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

<?php else : ?>
    <div class="addonext-hero-slider__main-swiper swiper">
        <div class="swiper-wrapper">
            <?php
            $addonext_hero_slides = isset($addonext_settings['hero_slides']) && is_array($addonext_settings['hero_slides']) ? $addonext_settings['hero_slides'] : [];
            if ($addonext_hero_slides === []) {
                $addonext_hero_slides = [[]];
            }
            foreach ($addonext_hero_slides as $addonext_si => $addonext_slide) :
                if (!is_array($addonext_slide)) {
                    continue;
                }
                $addonext_typing_ms = isset($addonext_slide['title_typing_speed']) ? max(20, min(200, (int) $addonext_slide['title_typing_speed'])) : 55;
                $addonext_typing_loop = !empty($addonext_slide['title_typing_loop']) && $addonext_slide['title_typing_loop'] === 'yes';
                $addonext_hero_rep_id = isset($addonext_slide['_id']) && (string) $addonext_slide['_id'] !== '' ? (string) $addonext_slide['_id'] : 'hs' . (int) $addonext_si;
                ?>
                <div class="swiper-slide elementor-repeater-item-<?php echo esc_attr($addonext_hero_rep_id); ?>" data-typing-ms="<?php echo esc_attr((string) $addonext_typing_ms); ?>" data-typing-loop="<?php echo $addonext_typing_loop ? '1' : '0'; ?>">
                    <div class="addonext-hero-slider__combo-slide-bg">
                        <?php $addonext_render_bg_inner($addonext_slide, 'combo_bg_panel', 'addonext-hero-slider__combo-slide-bg-inner', HeroSlider::demoBgImageUrl((int) $addonext_si)); ?>
                    </div>
                    <div class="addonext-hero-slider__slide-overlay" aria-hidden="true"></div>
                    <div class="addonext-hero-slider__inner addonext-hero-slider__inner--in-slide">
                        <?php $addonext_render_slide_text_block($addonext_slide, (int) $addonext_si); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<?php if ($addonext_show_arrows) : ?>
    <div class="addonext-hero-slider__arrow addonext-hero-slider__arrow--prev swiper-button-prev" role="button" tabindex="0" aria-label="<?php esc_attr_e('Previous slide', 'addonext'); ?>"></div>
    <div class="addonext-hero-slider__arrow addonext-hero-slider__arrow--next swiper-button-next" role="button" tabindex="0" aria-label="<?php esc_attr_e('Next slide', 'addonext'); ?>"></div>
<?php endif; ?>
<?php if ($addonext_show_dots) : ?>
    <div class="swiper-pagination addonext-hero-slider__pagination-wrap"></div>
<?php endif; ?>
</div>
