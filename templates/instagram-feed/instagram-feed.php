<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$items   = isset($items) && is_array($items) ? $items : [];
$layout  = isset($settings['layout']) ? $settings['layout'] : 'grid';
$new_tab = !empty($settings['open_new_tab']) ? '_blank' : '_self';
$overlay_links = !empty($settings['overlay_links']);
$effect_class = isset($settings['image_effect']) && $settings['image_effect'] !== 'none' ? ' elementrify-instagram-feed__media-wrap--' . $settings['image_effect'] : '';
$square_class = !empty($settings['force_square']) ? ' elementrify-instagram-feed__media-wrap--square' : '';

if (empty($items)) {
    return;
}

$is_carousel = $layout === 'carousel';
$carousel_slides = isset($settings['carousel_slides']) ? (int) $settings['carousel_slides'] : 3;
$carousel_slides_tablet = isset($settings['carousel_slides_tablet']) ? (int) $settings['carousel_slides_tablet'] : min(2, $carousel_slides);
$carousel_slides_mobile = isset($settings['carousel_slides_mobile']) ? (int) $settings['carousel_slides_mobile'] : 1;
$carousel_space = isset($settings['carousel_space']) ? (int) $settings['carousel_space'] : 10;
$carousel_autoplay = !empty($settings['carousel_autoplay']);
$carousel_autoplay_delay = isset($settings['carousel_autoplay_delay']) ? (int) $settings['carousel_autoplay_delay'] : 3000;
$carousel_loop = !empty($settings['carousel_loop']);
$carousel_speed = isset($settings['carousel_speed']) ? (int) $settings['carousel_speed'] : 500;
$carousel_nav = !empty($settings['carousel_nav']);
$carousel_pagination = !empty($settings['carousel_pagination']);
?>
<div class="elementrify-instagram-feed elementrify-instagram-feed--<?php echo esc_attr($layout); ?>">
    <?php if (!empty($settings['show_follow']) && !empty($settings['follow_url']['url'])) : ?>
        <div class="elementrify-instagram-feed__follow-wrap">
            <a href="<?php echo esc_url($settings['follow_url']['url']); ?>" class="elementrify-instagram-feed__follow" target="_blank" rel="noopener noreferrer">
                <?php echo esc_html($settings['follow_text'] ?? __('Follow on Instagram', 'elementrify')); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="elementrify-instagram-feed__wrap">
        <?php if ($is_carousel) : ?>
            <div class="elementrify-instagram-feed__carousel swiper"
                 data-slides="<?php echo esc_attr($carousel_slides); ?>"
                 data-slides-tablet="<?php echo esc_attr($carousel_slides_tablet); ?>"
                 data-slides-mobile="<?php echo esc_attr($carousel_slides_mobile); ?>"
                 data-space="<?php echo esc_attr($carousel_space); ?>"
                 data-autoplay="<?php echo $carousel_autoplay ? '1' : '0'; ?>"
                 data-autoplay-delay="<?php echo esc_attr($carousel_autoplay_delay); ?>"
                 data-loop="<?php echo $carousel_loop ? '1' : '0'; ?>"
                 data-speed="<?php echo esc_attr($carousel_speed); ?>"
                 data-nav="<?php echo $carousel_nav ? '1' : '0'; ?>"
                 data-pagination="<?php echo $carousel_pagination ? '1' : '0'; ?>">
                <div class="swiper-wrapper">
                    <?php foreach ($items as $item) : ?>
                        <div class="swiper-slide">
                            <?php $this->render_instagram_item($item, $settings, $overlay_links, $new_tab, $effect_class . $square_class); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if ($carousel_nav) : ?>
                    <div class="swiper-button-prev" aria-label="<?php esc_attr_e('Previous', 'elementrify'); ?>"></div>
                    <div class="swiper-button-next" aria-label="<?php esc_attr_e('Next', 'elementrify'); ?>"></div>
                <?php endif; ?>
                <?php if ($carousel_pagination) : ?>
                    <div class="swiper-pagination"></div>
                <?php endif; ?>
            </div>
        <?php else : ?>
            <div class="elementrify-instagram-feed__grid">
                <?php foreach ($items as $item) : ?>
                    <div class="elementrify-instagram-feed__item">
                        <?php $this->render_instagram_item($item, $settings, $overlay_links, $new_tab, $effect_class . $square_class); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
