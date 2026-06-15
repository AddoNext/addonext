<?php
defined('ABSPATH') || exit;

use Elementor\Icons_Manager;
use Elementor\Utils;
use Veloria\Widgets\HeroBanner\HeroBanner;
use Veloria\Widgets\HeroSlider\HeroSlider;

$settings = $this->get_settings_for_display();

$mode = isset($settings['slider_mode']) ? (string) $settings['slider_mode'] : 'both';
if (!in_array($mode, ['background', 'content', 'both'], true)) {
    $mode = 'both';
}

$nav = isset($settings['navigation']) ? (string) $settings['navigation'] : 'none';
$autoplay = !empty($settings['autoplay']) && $settings['autoplay'] === 'yes';
$autoplay_delay = isset($settings['autoplay_delay']) ? max(1, min(30, (int) $settings['autoplay_delay'])) : 5;
$loop = !empty($settings['loop']) && $settings['loop'] === 'yes';
$speed = isset($settings['slider_speed']) ? max(200, min(2000, (int) $settings['slider_speed'])) : 600;

$show_arrows = in_array($nav, ['both', 'arrows'], true);
$show_dots = in_array($nav, ['both', 'dots'], true);

$root_attrs = [
    'class'               => 'veloria-hero-slider veloria-hero-slider--mode-' . $mode,
    'data-mode'           => $mode,
    'data-navigation'     => $nav,
    'data-autoplay'       => $autoplay ? '1' : '0',
    'data-autoplay-delay' => (string) $autoplay_delay,
    'data-loop'           => $loop ? '1' : '0',
    'data-speed'          => (string) $speed,
];

if ($mode === 'background') {
    $typing_ms_root = isset($settings['title_typing_speed']) ? max(20, min(200, (int) $settings['title_typing_speed'])) : 55;
    $typing_loop_root = !empty($settings['title_typing_loop']) && $settings['title_typing_loop'] === 'yes';
    $root_attrs['data-typing-ms'] = (string) $typing_ms_root;
    $root_attrs['data-typing-loop'] = $typing_loop_root ? '1' : '0';
}

$render_bg_inner = static function (array $row, string $prefix, string $inner_class, string $fallback_photo_url = ''): void {
    $v = HeroSlider::parseBgVideoState($row, $prefix);
    $img_key = $prefix . '_image';
    $img_url = isset($row[$img_key]['url']) ? trim((string) $row[$img_key]['url']) : '';
    $extra_attr = '';
    if ($v['bg_mode'] !== 'video' && $v['bg_mode'] !== 'gradient' && $img_url === '' && $fallback_photo_url !== '') {
        $extra_attr = ' style="background-image:url(' . esc_url($fallback_photo_url) . ');background-size:cover;background-position:center;background-repeat:no-repeat;"';
    }
    ?>
    <div class="<?php echo esc_attr($inner_class); ?>"<?php
    if ($extra_attr !== '') {
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Inline style; background URL uses esc_url().
        echo $extra_attr;
    }
    ?>>
        <?php if ($v['bg_mode'] === 'video' && $v['is_file_video']) : ?>
            <video class="veloria-hero-slider__video" autoplay muted playsinline loop preload="metadata">
                <source src="<?php echo esc_url($v['video_link']); ?>" type="<?php echo esc_attr($v['video_mime']); ?>" />
            </video>
        <?php elseif ($v['bg_mode'] === 'video' && $v['youtube_id'] !== '') : ?>
            <div class="veloria-hero-slider__video veloria-hero-slider__video--youtube">
                <iframe
                    src="<?php echo esc_url('https://www.youtube-nocookie.com/embed/' . $v['youtube_id'] . '?autoplay=1&mute=1&controls=0&playsinline=1&rel=0&loop=1&playlist=' . rawurlencode($v['youtube_id'])); ?>"
                    title="<?php echo esc_attr__('Background video', 'veloria'); ?>"
                    loading="lazy"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
            </div>
        <?php elseif ($v['bg_mode'] === 'video' && $v['vimeo_id'] !== '') : ?>
            <div class="veloria-hero-slider__video veloria-hero-slider__video--vimeo">
                <iframe
                    src="<?php echo esc_url('https://player.vimeo.com/video/' . $v['vimeo_id'] . '?autoplay=1&muted=1&background=1&loop=1'); ?>"
                    title="<?php echo esc_attr__('Background video', 'veloria'); ?>"
                    loading="lazy"
                    allow="autoplay; fullscreen; picture-in-picture"
                    allowfullscreen></iframe>
            </div>
        <?php endif; ?>
    </div>
    <?php
};

$widget = $this;
$render_buttons = static function (array $buttons, string $id_prefix) use ($widget): void {
    if ($buttons === []) {
        return;
    }
    ?>
    <div class="veloria-hero-slider__actions">
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
            $key = $id_prefix . '_' . (int) $index;
            $widget->add_render_attribute($key, 'class', 'veloria-hero-slider__btn veloria-hero-slider__btn--' . $type);
            $widget->add_link_attributes($key, $link);
            ?>
            <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor link attribute string. ?>
            <a <?php echo $widget->get_render_attribute_string($key); ?>>
                <span class="veloria-hero-slider__btn-inner">
                    <span class="veloria-hero-slider__btn-text"><?php echo esc_html($text); ?></span>
                    <?php if ($show_icon && !empty($icon['value'])) : ?>
                        <span class="veloria-hero-slider__btn-icon" aria-hidden="true">
                            <?php Icons_Manager::render_icon($icon, ['aria-hidden' => 'true']); ?>
                        </span>
                    <?php endif; ?>
                </span>
            </a>
        <?php endforeach; ?>
    </div>
    <?php
};

$render_slide_text_block = static function (array $slide, int $slide_index) use ($render_buttons): void {
    $tag_prefix = isset($slide['tagline_prefix']) ? (string) $slide['tagline_prefix'] : '';
    $tag_accent = isset($slide['tagline_accent']) ? (string) $slide['tagline_accent'] : '';
    if (trim($tag_accent) === '') {
        $name = get_bloginfo('name', 'display');
        $tag_accent = is_string($name) && trim($name) !== '' ? $name : '';
    }
    $title = isset($slide['title']) ? (string) $slide['title'] : '';
    [$title_static, $title_typing] = HeroBanner::split_title_last_two_words($title);
    $subtitle = isset($slide['subtitle']) ? (string) $slide['subtitle'] : '';
    $title_tag = Utils::validate_html_tag($slide['title_tag'] ?? 'h1');
    $typing_ms = isset($slide['title_typing_speed']) ? max(20, min(200, (int) $slide['title_typing_speed'])) : 55;
    $typing_loop = !empty($slide['title_typing_loop']) && $slide['title_typing_loop'] === 'yes';
    $show_line = !empty($slide['show_accent_line']) && $slide['show_accent_line'] === 'yes';
    $buttons = isset($slide['buttons']) && is_array($slide['buttons']) ? $slide['buttons'] : [];
    ?>
    <div class="veloria-hero-slider__content">
        <?php if ($tag_prefix !== '' || $tag_accent !== '') : ?>
            <p class="veloria-hero-slider__tagline">
                <?php if ($tag_prefix !== '') : ?>
                    <span class="veloria-hero-slider__tagline-prefix"><?php echo esc_html($tag_prefix); ?></span>
                <?php endif; ?>
                <?php if ($tag_prefix !== '' && $tag_accent !== '') : ?>
                    <?php echo ' '; ?>
                <?php endif; ?>
                <?php if ($tag_accent !== '') : ?>
                    <span class="veloria-hero-slider__tagline-accent"><span class="veloria-hero-slider__tagline-accent-text"><?php echo esc_html($tag_accent); ?></span></span>
                <?php endif; ?>
            </p>
        <?php endif; ?>
        <?php if ($title !== '') : ?>
            <<?php echo esc_attr($title_tag); ?> class="veloria-hero-slider__title" aria-label="<?php echo esc_attr($title); ?>">
                <?php if ($title_static !== '') : ?>
                    <span class="veloria-hero-slider__title-static"><?php echo esc_html($title_static); ?> </span>
                <?php endif; ?>
                <?php if ($title_typing !== '') : ?>
                    <span class="veloria-hero-slider__title-typing-wrap">
                        <span class="veloria-hero-slider__title-typing" data-text="<?php echo esc_attr($title_typing); ?>"></span><span class="veloria-hero-slider__title-cursor" aria-hidden="true"></span>
                    </span>
                <?php endif; ?>
            </<?php echo esc_attr($title_tag); ?>>
        <?php endif; ?>
        <?php if ($show_line || $subtitle !== '') : ?>
            <div class="veloria-hero-slider__subrow-wrap">
                <div class="veloria-hero-slider__subrow">
                    <?php if ($show_line) : ?>
                        <span class="veloria-hero-slider__accent-line" aria-hidden="true"></span>
                    <?php endif; ?>
                    <?php if ($subtitle !== '') : ?>
                        <p class="veloria-hero-slider__subtitle"><?php echo esc_html($subtitle); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        <?php $render_buttons($buttons, 'hs_btn_' . $slide_index); ?>
    </div>
    <?php
};

?>
<div<?php
foreach ($root_attrs as $attr => $val) {
    printf(' %s="%s"', esc_attr($attr), esc_attr($val));
}
echo '>';
?>
<?php if ($mode === 'background') : ?>
    <?php
    $bg_slides = isset($settings['bg_slides']) && is_array($settings['bg_slides']) ? $settings['bg_slides'] : [];
    if ($bg_slides === []) {
        $bg_slides = [[]];
    }
    ?>
    <div class="veloria-hero-slider__bg-swiper swiper">
        <div class="swiper-wrapper">
            <?php foreach ($bg_slides as $bi => $bg_row) : ?>
                <?php
                if (!is_array($bg_row)) {
                    continue;
                }
                $bg_rep_id = isset($bg_row['_id']) && (string) $bg_row['_id'] !== '' ? (string) $bg_row['_id'] : 'bg' . (int) $bi;
                ?>
                <div class="swiper-slide elementor-repeater-item-<?php echo esc_attr($bg_rep_id); ?>">
                    <?php $render_bg_inner($bg_row, 'bg_only_panel', 'veloria-hero-slider__bg-slide-inner', HeroSlider::demoBgImageUrl((int) $bi)); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="veloria-hero-slider__overlay" aria-hidden="true"></div>
    <div class="veloria-hero-slider__inner">
        <?php
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
        $show_line = !empty($settings['show_accent_line']) && $settings['show_accent_line'] === 'yes';
        $buttons = isset($settings['buttons']) && is_array($settings['buttons']) ? $settings['buttons'] : [];
        ?>
        <div class="veloria-hero-slider__content">
            <?php if ($tag_prefix !== '' || $tag_accent !== '') : ?>
                <p class="veloria-hero-slider__tagline">
                    <?php if ($tag_prefix !== '') : ?>
                        <span class="veloria-hero-slider__tagline-prefix"><?php echo esc_html($tag_prefix); ?></span>
                    <?php endif; ?>
                    <?php if ($tag_prefix !== '' && $tag_accent !== '') : ?>
                        <?php echo ' '; ?>
                    <?php endif; ?>
                    <?php if ($tag_accent !== '') : ?>
                        <span class="veloria-hero-slider__tagline-accent"><span class="veloria-hero-slider__tagline-accent-text"><?php echo esc_html($tag_accent); ?></span></span>
                    <?php endif; ?>
                </p>
            <?php endif; ?>
            <?php if ($title !== '') : ?>
                <<?php echo esc_attr($title_tag); ?> class="veloria-hero-slider__title" aria-label="<?php echo esc_attr($title); ?>">
                    <?php if ($title_static !== '') : ?>
                        <span class="veloria-hero-slider__title-static"><?php echo esc_html($title_static); ?> </span>
                    <?php endif; ?>
                    <?php if ($title_typing !== '') : ?>
                        <span class="veloria-hero-slider__title-typing-wrap">
                            <span class="veloria-hero-slider__title-typing" data-text="<?php echo esc_attr($title_typing); ?>"></span><span class="veloria-hero-slider__title-cursor" aria-hidden="true"></span>
                        </span>
                    <?php endif; ?>
                </<?php echo esc_attr($title_tag); ?>>
            <?php endif; ?>
            <?php if ($show_line || $subtitle !== '') : ?>
                <div class="veloria-hero-slider__subrow-wrap">
                    <div class="veloria-hero-slider__subrow">
                        <?php if ($show_line) : ?>
                            <span class="veloria-hero-slider__accent-line" aria-hidden="true"></span>
                        <?php endif; ?>
                        <?php if ($subtitle !== '') : ?>
                            <p class="veloria-hero-slider__subtitle"><?php echo esc_html($subtitle); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php $render_buttons($buttons, 'hs_static_btn'); ?>
        </div>
    </div>

<?php elseif ($mode === 'content') : ?>
    <div class="veloria-hero-slider__sticky-bg">
        <?php $render_bg_inner($settings, 'sticky_panel_bg', 'veloria-hero-slider__sticky-bg-inner', HeroSlider::demoBgImageUrl(0)); ?>
    </div>
    <div class="veloria-hero-slider__overlay" aria-hidden="true"></div>
    <div class="veloria-hero-slider__inner">
        <div class="veloria-hero-slider__content-swiper swiper">
            <div class="swiper-wrapper">
                <?php
                $content_slides = isset($settings['content_slides']) && is_array($settings['content_slides']) ? $settings['content_slides'] : [];
                if ($content_slides === []) {
                    $content_slides = [[]];
                }
                foreach ($content_slides as $si => $slide) :
                    if (!is_array($slide)) {
                        continue;
                    }
                    $typing_ms = isset($slide['title_typing_speed']) ? max(20, min(200, (int) $slide['title_typing_speed'])) : 55;
                    $typing_loop = !empty($slide['title_typing_loop']) && $slide['title_typing_loop'] === 'yes';
                    ?>
                    <div class="swiper-slide" data-typing-ms="<?php echo esc_attr((string) $typing_ms); ?>" data-typing-loop="<?php echo $typing_loop ? '1' : '0'; ?>">
                        <?php $render_slide_text_block($slide, (int) $si); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

<?php else : ?>
    <div class="veloria-hero-slider__main-swiper swiper">
        <div class="swiper-wrapper">
            <?php
            $hero_slides = isset($settings['hero_slides']) && is_array($settings['hero_slides']) ? $settings['hero_slides'] : [];
            if ($hero_slides === []) {
                $hero_slides = [[]];
            }
            foreach ($hero_slides as $si => $slide) :
                if (!is_array($slide)) {
                    continue;
                }
                $typing_ms = isset($slide['title_typing_speed']) ? max(20, min(200, (int) $slide['title_typing_speed'])) : 55;
                $typing_loop = !empty($slide['title_typing_loop']) && $slide['title_typing_loop'] === 'yes';
                $hero_rep_id = isset($slide['_id']) && (string) $slide['_id'] !== '' ? (string) $slide['_id'] : 'hs' . (int) $si;
                ?>
                <div class="swiper-slide elementor-repeater-item-<?php echo esc_attr($hero_rep_id); ?>" data-typing-ms="<?php echo esc_attr((string) $typing_ms); ?>" data-typing-loop="<?php echo $typing_loop ? '1' : '0'; ?>">
                    <div class="veloria-hero-slider__combo-slide-bg">
                        <?php $render_bg_inner($slide, 'combo_bg_panel', 'veloria-hero-slider__combo-slide-bg-inner', HeroSlider::demoBgImageUrl((int) $si)); ?>
                    </div>
                    <div class="veloria-hero-slider__slide-overlay" aria-hidden="true"></div>
                    <div class="veloria-hero-slider__inner veloria-hero-slider__inner--in-slide">
                        <?php $render_slide_text_block($slide, (int) $si); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<?php if ($show_arrows) : ?>
    <div class="veloria-hero-slider__arrow veloria-hero-slider__arrow--prev swiper-button-prev" role="button" tabindex="0" aria-label="<?php esc_attr_e('Previous slide', 'veloria'); ?>"></div>
    <div class="veloria-hero-slider__arrow veloria-hero-slider__arrow--next swiper-button-next" role="button" tabindex="0" aria-label="<?php esc_attr_e('Next slide', 'veloria'); ?>"></div>
<?php endif; ?>
<?php if ($show_dots) : ?>
    <div class="swiper-pagination veloria-hero-slider__pagination-wrap"></div>
<?php endif; ?>
</div>
