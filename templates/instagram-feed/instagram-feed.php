<?php
defined('ABSPATH') || exit;

$veloria_settings = $this->get_settings_for_display();
$veloria_items   = isset($veloria_items) && is_array($veloria_items) ? $veloria_items : [];
$veloria_layout  = isset($veloria_settings['layout']) ? $veloria_settings['layout'] : 'grid';
$veloria_new_tab = !empty($veloria_settings['open_new_tab']) ? '_blank' : '_self';
$veloria_overlay_links = !empty($veloria_settings['overlay_links']);
$veloria_effect_class = isset($veloria_settings['image_effect']) && $veloria_settings['image_effect'] !== 'none' ? ' veloria-instagram-feed__media-wrap--' . $veloria_settings['image_effect'] : '';
$veloria_square_class = !empty($veloria_settings['force_square']) ? ' veloria-instagram-feed__media-wrap--square' : '';

if (empty($veloria_items)) {
    return;
}

$veloria_is_carousel = $veloria_layout === 'carousel';
$veloria_carousel_slides = isset($veloria_settings['carousel_slides']) ? (int) $veloria_settings['carousel_slides'] : 3;
$veloria_carousel_slides_tablet = isset($veloria_settings['carousel_slides_tablet']) ? (int) $veloria_settings['carousel_slides_tablet'] : min(2, $veloria_carousel_slides);
$veloria_carousel_slides_mobile = isset($veloria_settings['carousel_slides_mobile']) ? (int) $veloria_settings['carousel_slides_mobile'] : 1;
$veloria_carousel_space = isset($veloria_settings['carousel_space']) ? (int) $veloria_settings['carousel_space'] : 10;
$veloria_carousel_autoplay = !empty($veloria_settings['carousel_autoplay']);
$veloria_carousel_autoplay_delay = isset($veloria_settings['carousel_autoplay_delay']) ? (int) $veloria_settings['carousel_autoplay_delay'] : 3000;
$veloria_carousel_loop = !empty($veloria_settings['carousel_loop']);
$veloria_carousel_speed = isset($veloria_settings['carousel_speed']) ? (int) $veloria_settings['carousel_speed'] : 500;
$veloria_carousel_nav = !empty($veloria_settings['carousel_nav']);
$veloria_carousel_pagination = !empty($veloria_settings['carousel_pagination']);
?>
<div class="veloria-instagram-feed veloria-instagram-feed--<?php echo esc_attr($veloria_layout); ?>">
    <?php if (!empty($veloria_settings['show_follow']) && !empty($veloria_settings['follow_url']['url'])) : ?>
        <div class="veloria-instagram-feed__follow-wrap">
            <a href="<?php echo esc_url($veloria_settings['follow_url']['url']); ?>" class="veloria-instagram-feed__follow" target="_blank" rel="noopener noreferrer">
                <?php echo esc_html($veloria_settings['follow_text'] ?? __('Follow on Instagram', 'veloria')); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="veloria-instagram-feed__wrap">
        <?php if ($veloria_is_carousel) : ?>
            <div class="veloria-instagram-feed__carousel swiper"
                 data-slides="<?php echo esc_attr($veloria_carousel_slides); ?>"
                 data-slides-tablet="<?php echo esc_attr($veloria_carousel_slides_tablet); ?>"
                 data-slides-mobile="<?php echo esc_attr($veloria_carousel_slides_mobile); ?>"
                 data-space="<?php echo esc_attr($veloria_carousel_space); ?>"
                 data-autoplay="<?php echo $veloria_carousel_autoplay ? '1' : '0'; ?>"
                 data-autoplay-delay="<?php echo esc_attr($veloria_carousel_autoplay_delay); ?>"
                 data-loop="<?php echo $veloria_carousel_loop ? '1' : '0'; ?>"
                 data-speed="<?php echo esc_attr($veloria_carousel_speed); ?>"
                 data-nav="<?php echo $veloria_carousel_nav ? '1' : '0'; ?>"
                 data-pagination="<?php echo $veloria_carousel_pagination ? '1' : '0'; ?>">
                <div class="swiper-wrapper">
                    <?php foreach ($veloria_items as $veloria_item) : ?>
                        <div class="swiper-slide">
                            <?php $this->render_instagram_item($veloria_item, $veloria_settings, $veloria_overlay_links, $veloria_new_tab, $veloria_effect_class . $veloria_square_class); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if ($veloria_carousel_nav) : ?>
                    <div class="swiper-button-prev" aria-label="<?php esc_attr_e('Previous', 'veloria'); ?>"></div>
                    <div class="swiper-button-next" aria-label="<?php esc_attr_e('Next', 'veloria'); ?>"></div>
                <?php endif; ?>
                <?php if ($veloria_carousel_pagination) : ?>
                    <div class="swiper-pagination"></div>
                <?php endif; ?>
            </div>
        <?php else : ?>
            <div class="veloria-instagram-feed__grid">
                <?php foreach ($veloria_items as $veloria_item) : ?>
                    <div class="veloria-instagram-feed__item">
                        <?php $this->render_instagram_item($veloria_item, $veloria_settings, $veloria_overlay_links, $veloria_new_tab, $veloria_effect_class . $veloria_square_class); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
