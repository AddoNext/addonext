<?php
defined('ABSPATH') || exit;

use Elementor\Icons_Manager;
use Elementor\Utils;
use Veloria\Widgets\HeroBanner\HeroBanner;
use Veloria\Widgets\HeroSlider\HeroSlider;

$veloria_settings = $this->get_settings_for_display();

$veloria_mode = isset($veloria_settings['slider_mode']) ? (string) $veloria_settings['slider_mode'] : 'both';
if (!in_array($veloria_mode, ['background', 'content', 'both'], true)) {
    $veloria_mode = 'both';
}

$veloria_nav = isset($veloria_settings['navigation']) ? (string) $veloria_settings['navigation'] : 'none';
$veloria_autoplay = !empty($veloria_settings['autoplay']) && $veloria_settings['autoplay'] === 'yes';
$veloria_autoplay_delay = isset($veloria_settings['autoplay_delay']) ? max(1, min(30, (int) $veloria_settings['autoplay_delay'])) : 5;
$veloria_loop = !empty($veloria_settings['loop']) && $veloria_settings['loop'] === 'yes';
$veloria_speed = isset($veloria_settings['slider_speed']) ? max(200, min(2000, (int) $veloria_settings['slider_speed'])) : 600;

$veloria_show_arrows = in_array($veloria_nav, ['both', 'arrows'], true);
$veloria_show_dots = in_array($veloria_nav, ['both', 'dots'], true);

$veloria_root_attrs = [
    'class'               => 'veloria-hero-slider veloria-hero-slider--mode-' . $veloria_mode,
    'data-mode'           => $veloria_mode,
    'data-navigation'     => $veloria_nav,
    'data-autoplay'       => $veloria_autoplay ? '1' : '0',
    'data-autoplay-delay' => (string) $veloria_autoplay_delay,
    'data-loop'           => $veloria_loop ? '1' : '0',
    'data-speed'          => (string) $veloria_speed,
];

if ($veloria_mode === 'background') {
    $veloria_typing_ms_root = isset($veloria_settings['title_typing_speed']) ? max(20, min(200, (int) $veloria_settings['title_typing_speed'])) : 55;
    $veloria_typing_loop_root = !empty($veloria_settings['title_typing_loop']) && $veloria_settings['title_typing_loop'] === 'yes';
    $veloria_root_attrs['data-typing-ms'] = (string) $veloria_typing_ms_root;
    $veloria_root_attrs['data-typing-loop'] = $veloria_typing_loop_root ? '1' : '0';
}

$veloria_render_bg_inner = static function (array $veloria_row, string $veloria_prefix, string $veloria_inner_class, string $veloria_fallback_photo_url = ''): void {
    $veloria_v = HeroSlider::parseBgVideoState($veloria_row, $veloria_prefix);
    $veloria_img_key = $veloria_prefix . '_image';
    $veloria_img_url = isset($veloria_row[$veloria_img_key]['url']) ? trim((string) $veloria_row[$veloria_img_key]['url']) : '';
    $veloria_extra_attr = '';
    if ($veloria_v['bg_mode'] !== 'video' && $veloria_v['bg_mode'] !== 'gradient' && $veloria_img_url === '' && $veloria_fallback_photo_url !== '') {
        $veloria_extra_attr = ' style="background-image:url(' . esc_url($veloria_fallback_photo_url) . ');background-size:cover;background-position:center;background-repeat:no-repeat;"';
    }
    ?>
    <div class="<?php echo esc_attr($veloria_inner_class); ?>"<?php
    if ($veloria_extra_attr !== '') {
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Inline style; background URL uses esc_url().
        echo $veloria_extra_attr;
    }
    ?>>
        <?php if ($veloria_v['bg_mode'] === 'video' && $veloria_v['is_file_video']) : ?>
            <video class="veloria-hero-slider__video" autoplay muted playsinline loop preload="metadata">
                <source src="<?php echo esc_url($veloria_v['video_link']); ?>" type="<?php echo esc_attr($veloria_v['video_mime']); ?>" />
            </video>
        <?php elseif ($veloria_v['bg_mode'] === 'video' && $veloria_v['youtube_id'] !== '') : ?>
            <div class="veloria-hero-slider__video veloria-hero-slider__video--youtube">
                <iframe
                    src="<?php echo esc_url('https://www.youtube-nocookie.com/embed/' . $veloria_v['youtube_id'] . '?autoplay=1&mute=1&controls=0&playsinline=1&rel=0&loop=1&playlist=' . rawurlencode($veloria_v['youtube_id'])); ?>"
                    title="<?php echo esc_attr__('Background video', 'veloria'); ?>"
                    loading="lazy"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
            </div>
        <?php elseif ($veloria_v['bg_mode'] === 'video' && $veloria_v['vimeo_id'] !== '') : ?>
            <div class="veloria-hero-slider__video veloria-hero-slider__video--vimeo">
                <iframe
                    src="<?php echo esc_url('https://player.vimeo.com/video/' . $veloria_v['vimeo_id'] . '?autoplay=1&muted=1&background=1&loop=1'); ?>"
                    title="<?php echo esc_attr__('Background video', 'veloria'); ?>"
                    loading="lazy"
                    allow="autoplay; fullscreen; picture-in-picture"
                    allowfullscreen></iframe>
            </div>
        <?php endif; ?>
    </div>
    <?php
};

$veloria_widget = $this;
$veloria_render_buttons = static function (array $veloria_buttons, string $veloria_id_prefix) use ($veloria_widget): void {
    if ($veloria_buttons === []) {
        return;
    }
    ?>
    <div class="veloria-hero-slider__actions">
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
            $veloria_key = $veloria_id_prefix . '_' . (int) $veloria_index;
            $veloria_widget->add_render_attribute($veloria_key, 'class', 'veloria-hero-slider__btn veloria-hero-slider__btn--' . $veloria_type);
            $veloria_widget->add_link_attributes($veloria_key, $veloria_link);
            ?>
            <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor link attribute string. ?>
            <a <?php echo $veloria_widget->get_render_attribute_string($veloria_key); ?>>
                <span class="veloria-hero-slider__btn-inner">
                    <span class="veloria-hero-slider__btn-text"><?php echo esc_html($veloria_text); ?></span>
                    <?php if ($veloria_show_icon && !empty($veloria_icon['value'])) : ?>
                        <span class="veloria-hero-slider__btn-icon" aria-hidden="true">
                            <?php Icons_Manager::render_icon($veloria_icon, ['aria-hidden' => 'true']); ?>
                        </span>
                    <?php endif; ?>
                </span>
            </a>
        <?php endforeach; ?>
    </div>
    <?php
};

$veloria_render_slide_text_block = static function (array $veloria_slide, int $veloria_slide_index) use ($veloria_render_buttons): void {
    $veloria_tag_prefix = isset($veloria_slide['tagline_prefix']) ? (string) $veloria_slide['tagline_prefix'] : '';
    $veloria_tag_accent = isset($veloria_slide['tagline_accent']) ? (string) $veloria_slide['tagline_accent'] : '';
    if (trim($veloria_tag_accent) === '') {
        $veloria_name = get_bloginfo('name', 'display');
        $veloria_tag_accent = is_string($veloria_name) && trim($veloria_name) !== '' ? $veloria_name : '';
    }
    $veloria_title = isset($veloria_slide['title']) ? (string) $veloria_slide['title'] : '';
    [$veloria_title_static, $veloria_title_typing] = HeroBanner::split_title_last_two_words($veloria_title);
    $veloria_subtitle = isset($veloria_slide['subtitle']) ? (string) $veloria_slide['subtitle'] : '';
    $veloria_title_tag = Utils::validate_html_tag($veloria_slide['title_tag'] ?? 'h1');
    $veloria_typing_ms = isset($veloria_slide['title_typing_speed']) ? max(20, min(200, (int) $veloria_slide['title_typing_speed'])) : 55;
    $veloria_typing_loop = !empty($veloria_slide['title_typing_loop']) && $veloria_slide['title_typing_loop'] === 'yes';
    $veloria_show_line = !empty($veloria_slide['show_accent_line']) && $veloria_slide['show_accent_line'] === 'yes';
    $veloria_buttons = isset($veloria_slide['buttons']) && is_array($veloria_slide['buttons']) ? $veloria_slide['buttons'] : [];
    ?>
    <div class="veloria-hero-slider__content">
        <?php if ($veloria_tag_prefix !== '' || $veloria_tag_accent !== '') : ?>
            <p class="veloria-hero-slider__tagline">
                <?php if ($veloria_tag_prefix !== '') : ?>
                    <span class="veloria-hero-slider__tagline-prefix"><?php echo esc_html($veloria_tag_prefix); ?></span>
                <?php endif; ?>
                <?php if ($veloria_tag_prefix !== '' && $veloria_tag_accent !== '') : ?>
                    <?php echo ' '; ?>
                <?php endif; ?>
                <?php if ($veloria_tag_accent !== '') : ?>
                    <span class="veloria-hero-slider__tagline-accent"><span class="veloria-hero-slider__tagline-accent-text"><?php echo esc_html($veloria_tag_accent); ?></span></span>
                <?php endif; ?>
            </p>
        <?php endif; ?>
        <?php if ($veloria_title !== '') : ?>
            <<?php echo esc_attr($veloria_title_tag); ?> class="veloria-hero-slider__title" aria-label="<?php echo esc_attr($veloria_title); ?>">
                <?php if ($veloria_title_static !== '') : ?>
                    <span class="veloria-hero-slider__title-static"><?php echo esc_html($veloria_title_static); ?> </span>
                <?php endif; ?>
                <?php if ($veloria_title_typing !== '') : ?>
                    <span class="veloria-hero-slider__title-typing-wrap">
                        <span class="veloria-hero-slider__title-typing" data-text="<?php echo esc_attr($veloria_title_typing); ?>"></span><span class="veloria-hero-slider__title-cursor" aria-hidden="true"></span>
                    </span>
                <?php endif; ?>
            </<?php echo esc_attr($veloria_title_tag); ?>>
        <?php endif; ?>
        <?php if ($veloria_show_line || $veloria_subtitle !== '') : ?>
            <div class="veloria-hero-slider__subrow-wrap">
                <div class="veloria-hero-slider__subrow">
                    <?php if ($veloria_show_line) : ?>
                        <span class="veloria-hero-slider__accent-line" aria-hidden="true"></span>
                    <?php endif; ?>
                    <?php if ($veloria_subtitle !== '') : ?>
                        <p class="veloria-hero-slider__subtitle"><?php echo esc_html($veloria_subtitle); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        <?php $veloria_render_buttons($veloria_buttons, 'hs_btn_' . $veloria_slide_index); ?>
    </div>
    <?php
};

?>
<div<?php
foreach ($veloria_root_attrs as $veloria_attr => $veloria_val) {
    printf(' %s="%s"', esc_attr($veloria_attr), esc_attr($veloria_val));
}
echo '>';
?>
<?php if ($veloria_mode === 'background') : ?>
    <?php
    $veloria_bg_slides = isset($veloria_settings['bg_slides']) && is_array($veloria_settings['bg_slides']) ? $veloria_settings['bg_slides'] : [];
    if ($veloria_bg_slides === []) {
        $veloria_bg_slides = [[]];
    }
    ?>
    <div class="veloria-hero-slider__bg-swiper swiper">
        <div class="swiper-wrapper">
            <?php foreach ($veloria_bg_slides as $veloria_bi => $veloria_bg_row) : ?>
                <?php
                if (!is_array($veloria_bg_row)) {
                    continue;
                }
                $veloria_bg_rep_id = isset($veloria_bg_row['_id']) && (string) $veloria_bg_row['_id'] !== '' ? (string) $veloria_bg_row['_id'] : 'bg' . (int) $veloria_bi;
                ?>
                <div class="swiper-slide elementor-repeater-item-<?php echo esc_attr($veloria_bg_rep_id); ?>">
                    <?php $veloria_render_bg_inner($veloria_bg_row, 'bg_only_panel', 'veloria-hero-slider__bg-slide-inner', HeroSlider::demoBgImageUrl((int) $veloria_bi)); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="veloria-hero-slider__overlay" aria-hidden="true"></div>
    <div class="veloria-hero-slider__inner">
        <?php
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
        $veloria_show_line = !empty($veloria_settings['show_accent_line']) && $veloria_settings['show_accent_line'] === 'yes';
        $veloria_buttons = isset($veloria_settings['buttons']) && is_array($veloria_settings['buttons']) ? $veloria_settings['buttons'] : [];
        ?>
        <div class="veloria-hero-slider__content">
            <?php if ($veloria_tag_prefix !== '' || $veloria_tag_accent !== '') : ?>
                <p class="veloria-hero-slider__tagline">
                    <?php if ($veloria_tag_prefix !== '') : ?>
                        <span class="veloria-hero-slider__tagline-prefix"><?php echo esc_html($veloria_tag_prefix); ?></span>
                    <?php endif; ?>
                    <?php if ($veloria_tag_prefix !== '' && $veloria_tag_accent !== '') : ?>
                        <?php echo ' '; ?>
                    <?php endif; ?>
                    <?php if ($veloria_tag_accent !== '') : ?>
                        <span class="veloria-hero-slider__tagline-accent"><span class="veloria-hero-slider__tagline-accent-text"><?php echo esc_html($veloria_tag_accent); ?></span></span>
                    <?php endif; ?>
                </p>
            <?php endif; ?>
            <?php if ($veloria_title !== '') : ?>
                <<?php echo esc_attr($veloria_title_tag); ?> class="veloria-hero-slider__title" aria-label="<?php echo esc_attr($veloria_title); ?>">
                    <?php if ($veloria_title_static !== '') : ?>
                        <span class="veloria-hero-slider__title-static"><?php echo esc_html($veloria_title_static); ?> </span>
                    <?php endif; ?>
                    <?php if ($veloria_title_typing !== '') : ?>
                        <span class="veloria-hero-slider__title-typing-wrap">
                            <span class="veloria-hero-slider__title-typing" data-text="<?php echo esc_attr($veloria_title_typing); ?>"></span><span class="veloria-hero-slider__title-cursor" aria-hidden="true"></span>
                        </span>
                    <?php endif; ?>
                </<?php echo esc_attr($veloria_title_tag); ?>>
            <?php endif; ?>
            <?php if ($veloria_show_line || $veloria_subtitle !== '') : ?>
                <div class="veloria-hero-slider__subrow-wrap">
                    <div class="veloria-hero-slider__subrow">
                        <?php if ($veloria_show_line) : ?>
                            <span class="veloria-hero-slider__accent-line" aria-hidden="true"></span>
                        <?php endif; ?>
                        <?php if ($veloria_subtitle !== '') : ?>
                            <p class="veloria-hero-slider__subtitle"><?php echo esc_html($veloria_subtitle); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php $veloria_render_buttons($veloria_buttons, 'hs_static_btn'); ?>
        </div>
    </div>

<?php elseif ($veloria_mode === 'content') : ?>
    <div class="veloria-hero-slider__sticky-bg">
        <?php $veloria_render_bg_inner($veloria_settings, 'sticky_panel_bg', 'veloria-hero-slider__sticky-bg-inner', HeroSlider::demoBgImageUrl(0)); ?>
    </div>
    <div class="veloria-hero-slider__overlay" aria-hidden="true"></div>
    <div class="veloria-hero-slider__inner">
        <div class="veloria-hero-slider__content-swiper swiper">
            <div class="swiper-wrapper">
                <?php
                $veloria_content_slides = isset($veloria_settings['content_slides']) && is_array($veloria_settings['content_slides']) ? $veloria_settings['content_slides'] : [];
                if ($veloria_content_slides === []) {
                    $veloria_content_slides = [[]];
                }
                foreach ($veloria_content_slides as $veloria_si => $veloria_slide) :
                    if (!is_array($veloria_slide)) {
                        continue;
                    }
                    $veloria_typing_ms = isset($veloria_slide['title_typing_speed']) ? max(20, min(200, (int) $veloria_slide['title_typing_speed'])) : 55;
                    $veloria_typing_loop = !empty($veloria_slide['title_typing_loop']) && $veloria_slide['title_typing_loop'] === 'yes';
                    ?>
                    <div class="swiper-slide" data-typing-ms="<?php echo esc_attr((string) $veloria_typing_ms); ?>" data-typing-loop="<?php echo $veloria_typing_loop ? '1' : '0'; ?>">
                        <?php $veloria_render_slide_text_block($veloria_slide, (int) $veloria_si); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

<?php else : ?>
    <div class="veloria-hero-slider__main-swiper swiper">
        <div class="swiper-wrapper">
            <?php
            $veloria_hero_slides = isset($veloria_settings['hero_slides']) && is_array($veloria_settings['hero_slides']) ? $veloria_settings['hero_slides'] : [];
            if ($veloria_hero_slides === []) {
                $veloria_hero_slides = [[]];
            }
            foreach ($veloria_hero_slides as $veloria_si => $veloria_slide) :
                if (!is_array($veloria_slide)) {
                    continue;
                }
                $veloria_typing_ms = isset($veloria_slide['title_typing_speed']) ? max(20, min(200, (int) $veloria_slide['title_typing_speed'])) : 55;
                $veloria_typing_loop = !empty($veloria_slide['title_typing_loop']) && $veloria_slide['title_typing_loop'] === 'yes';
                $veloria_hero_rep_id = isset($veloria_slide['_id']) && (string) $veloria_slide['_id'] !== '' ? (string) $veloria_slide['_id'] : 'hs' . (int) $veloria_si;
                ?>
                <div class="swiper-slide elementor-repeater-item-<?php echo esc_attr($veloria_hero_rep_id); ?>" data-typing-ms="<?php echo esc_attr((string) $veloria_typing_ms); ?>" data-typing-loop="<?php echo $veloria_typing_loop ? '1' : '0'; ?>">
                    <div class="veloria-hero-slider__combo-slide-bg">
                        <?php $veloria_render_bg_inner($veloria_slide, 'combo_bg_panel', 'veloria-hero-slider__combo-slide-bg-inner', HeroSlider::demoBgImageUrl((int) $veloria_si)); ?>
                    </div>
                    <div class="veloria-hero-slider__slide-overlay" aria-hidden="true"></div>
                    <div class="veloria-hero-slider__inner veloria-hero-slider__inner--in-slide">
                        <?php $veloria_render_slide_text_block($veloria_slide, (int) $veloria_si); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<?php if ($veloria_show_arrows) : ?>
    <div class="veloria-hero-slider__arrow veloria-hero-slider__arrow--prev swiper-button-prev" role="button" tabindex="0" aria-label="<?php esc_attr_e('Previous slide', 'veloria'); ?>"></div>
    <div class="veloria-hero-slider__arrow veloria-hero-slider__arrow--next swiper-button-next" role="button" tabindex="0" aria-label="<?php esc_attr_e('Next slide', 'veloria'); ?>"></div>
<?php endif; ?>
<?php if ($veloria_show_dots) : ?>
    <div class="swiper-pagination veloria-hero-slider__pagination-wrap"></div>
<?php endif; ?>
</div>
