<?php
defined('ABSPATH') || exit;

$addonext_settings = $this->get_settings_for_display();
$addonext_items   = isset($addonext_items) && is_array($addonext_items) ? $addonext_items : [];
$addonext_layout  = isset($addonext_settings['layout']) ? $addonext_settings['layout'] : 'grid';
$addonext_new_tab = !empty($addonext_settings['open_new_tab']) ? '_blank' : '_self';
$addonext_overlay_links = !empty($addonext_settings['overlay_links']);
$addonext_effect_class = isset($addonext_settings['image_effect']) && $addonext_settings['image_effect'] !== 'none' ? ' addonext-instagram-feed__media-wrap--' . $addonext_settings['image_effect'] : '';
$addonext_square_class = !empty($addonext_settings['force_square']) ? ' addonext-instagram-feed__media-wrap--square' : '';

if (empty($addonext_items)) {
    return;
}

$addonext_is_carousel = $addonext_layout === 'carousel';
$addonext_carousel_slides = isset($addonext_settings['carousel_slides']) ? (int) $addonext_settings['carousel_slides'] : 3;
$addonext_carousel_slides_tablet = isset($addonext_settings['carousel_slides_tablet']) ? (int) $addonext_settings['carousel_slides_tablet'] : min(2, $addonext_carousel_slides);
$addonext_carousel_slides_mobile = isset($addonext_settings['carousel_slides_mobile']) ? (int) $addonext_settings['carousel_slides_mobile'] : 1;
$addonext_carousel_space = isset($addonext_settings['carousel_space']) ? (int) $addonext_settings['carousel_space'] : 10;
$addonext_carousel_autoplay = !empty($addonext_settings['carousel_autoplay']);
$addonext_carousel_autoplay_delay = isset($addonext_settings['carousel_autoplay_delay']) ? (int) $addonext_settings['carousel_autoplay_delay'] : 3000;
$addonext_carousel_loop = !empty($addonext_settings['carousel_loop']);
$addonext_carousel_speed = isset($addonext_settings['carousel_speed']) ? (int) $addonext_settings['carousel_speed'] : 500;
$addonext_carousel_nav = !empty($addonext_settings['carousel_nav']);
$addonext_carousel_pagination = !empty($addonext_settings['carousel_pagination']);
?>
<div class="addonext-instagram-feed addonext-instagram-feed--<?php echo esc_attr($addonext_layout); ?>">
    <?php if (!empty($addonext_settings['show_follow']) && !empty($addonext_settings['follow_url']['url'])) : ?>
        <div class="addonext-instagram-feed__follow-wrap">
            <a href="<?php echo esc_url($addonext_settings['follow_url']['url']); ?>" class="addonext-instagram-feed__follow" target="_blank" rel="noopener noreferrer">
                <?php echo esc_html($addonext_settings['follow_text'] ?? __('Follow on Instagram', 'addonext')); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="addonext-instagram-feed__wrap">
        <?php if ($addonext_is_carousel) : ?>
            <div class="addonext-instagram-feed__carousel swiper"
                 data-slides="<?php echo esc_attr($addonext_carousel_slides); ?>"
                 data-slides-tablet="<?php echo esc_attr($addonext_carousel_slides_tablet); ?>"
                 data-slides-mobile="<?php echo esc_attr($addonext_carousel_slides_mobile); ?>"
                 data-space="<?php echo esc_attr($addonext_carousel_space); ?>"
                 data-autoplay="<?php echo $addonext_carousel_autoplay ? '1' : '0'; ?>"
                 data-autoplay-delay="<?php echo esc_attr($addonext_carousel_autoplay_delay); ?>"
                 data-loop="<?php echo $addonext_carousel_loop ? '1' : '0'; ?>"
                 data-speed="<?php echo esc_attr($addonext_carousel_speed); ?>"
                 data-nav="<?php echo $addonext_carousel_nav ? '1' : '0'; ?>"
                 data-pagination="<?php echo $addonext_carousel_pagination ? '1' : '0'; ?>">
                <div class="swiper-wrapper">
                    <?php foreach ($addonext_items as $addonext_item) : ?>
                        <div class="swiper-slide">
                            <?php $this->render_instagram_item($addonext_item, $addonext_settings, $addonext_overlay_links, $addonext_new_tab, $addonext_effect_class . $addonext_square_class); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if ($addonext_carousel_nav) : ?>
                    <div class="swiper-button-prev" aria-label="<?php esc_attr_e('Previous', 'addonext'); ?>"></div>
                    <div class="swiper-button-next" aria-label="<?php esc_attr_e('Next', 'addonext'); ?>"></div>
                <?php endif; ?>
                <?php if ($addonext_carousel_pagination) : ?>
                    <div class="swiper-pagination"></div>
                <?php endif; ?>
            </div>
        <?php else : ?>
            <div class="addonext-instagram-feed__grid">
                <?php foreach ($addonext_items as $addonext_item) : ?>
                    <div class="addonext-instagram-feed__item">
                        <?php $this->render_instagram_item($addonext_item, $addonext_settings, $addonext_overlay_links, $addonext_new_tab, $addonext_effect_class . $addonext_square_class); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
