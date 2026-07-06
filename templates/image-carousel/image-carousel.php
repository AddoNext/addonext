<?php
defined('ABSPATH') || exit;

$addonext_settings = $this->get_settings_for_display();
$addonext_slides   = isset($addonext_settings['slides']) && is_array($addonext_settings['slides']) ? $addonext_settings['slides'] : [];
$addonext_nav      = isset($addonext_settings['navigation']) ? $addonext_settings['navigation'] : 'both';
$addonext_show_arrows = ($addonext_nav === 'both' || $addonext_nav === 'arrows') && count($addonext_slides) > 1;
$addonext_show_dots   = ($addonext_nav === 'both' || $addonext_nav === 'dots') && count($addonext_slides) > 1;
$addonext_autoplay = !empty($addonext_settings['autoplay']) && $addonext_settings['autoplay'] === 'yes';
$addonext_autoplay_delay = ($addonext_autoplay && isset($addonext_settings['autoplay_delay']) && (int) $addonext_settings['autoplay_delay'] > 0) ? (int) $addonext_settings['autoplay_delay'] : 5;
$addonext_loop     = empty($addonext_settings['loop']) || $addonext_settings['loop'] === 'yes';
$addonext_pagination_type = isset($addonext_settings['pagination_type']) ? $addonext_settings['pagination_type'] : 'bullets';
$addonext_dots_gap = isset($addonext_settings['dots_gap']['size']) ? (int) $addonext_settings['dots_gap']['size'] : 8;
$addonext_bar_width = ($addonext_pagination_type === 'progressbar' && isset($addonext_settings['bar_width']['size'])) ? (int) $addonext_settings['bar_width']['size'] : 28;

if (empty($addonext_slides)) {
    return;
}

$addonext_image_size_name = isset($addonext_settings['image_size_size']) ? $addonext_settings['image_size_size'] : 'large';
$addonext_image_size_custom = isset($addonext_settings['image_size_custom_dimension']) ? $addonext_settings['image_size_custom_dimension'] : ['width' => '', 'height' => ''];
$addonext_use_custom_size = ($addonext_image_size_name === 'custom' && (!empty($addonext_image_size_custom['width']) || !empty($addonext_image_size_custom['height'])));

$addonext_placeholder_src = \Elementor\Utils::get_placeholder_image_src();
$addonext_autoplay_attr = $addonext_autoplay ? '1' : '0';
$addonext_loop_attr     = $addonext_loop ? '1' : '0';
$addonext_pagination_class = '';
if ($addonext_pagination_type === 'square') {
    $addonext_pagination_class = ' addonext-pagination-square';
} elseif ($addonext_pagination_type === 'progressbar') {
    $addonext_pagination_class = ' addonext-pagination-bar';
}
$addonext_inline_style = '--addonext-dots-gap: ' . $addonext_dots_gap . 'px;';
if ($addonext_pagination_type === 'progressbar') {
    $addonext_inline_style .= ' --addonext-bar-width: ' . $addonext_bar_width . 'px;';
}
$addonext_carousel_height = isset($addonext_settings['carousel_height']['size']) && $addonext_settings['carousel_height']['size'] !== '' ? (float) $addonext_settings['carousel_height']['size'] : 0;
$addonext_carousel_height_unit = isset($addonext_settings['carousel_height']['unit']) ? $addonext_settings['carousel_height']['unit'] : 'px';
if ($addonext_carousel_height > 0) {
    $addonext_inline_style .= ' --addonext-carousel-height: ' . $addonext_carousel_height . $addonext_carousel_height_unit . ';';
}
?>
<div class="addonext-image-carousel swiper swiper-autoheight<?php echo esc_attr($addonext_pagination_class); ?>" data-autoplay="<?php echo esc_attr($addonext_autoplay_attr); ?>" data-autoplay-delay="<?php echo esc_attr($addonext_autoplay_delay); ?>" data-loop="<?php echo esc_attr($addonext_loop_attr); ?>" data-pagination-type="<?php echo esc_attr($addonext_pagination_type); ?>" style="<?php echo esc_attr($addonext_inline_style); ?>">
    <div class="addonext-image-carousel__wrapper swiper-wrapper">
        <?php foreach ($addonext_slides as $addonext_slide) :
            $addonext_img = isset($addonext_slide['image']) && is_array($addonext_slide['image']) ? $addonext_slide['image'] : [];
            $addonext_id  = isset($addonext_img['id']) ? (int) $addonext_img['id'] : 0;
            $addonext_caption = isset($addonext_slide['caption']) ? $addonext_slide['caption'] : '';
            $addonext_url = isset($addonext_img['url']) ? trim((string) $addonext_img['url']) : '';
            if ($addonext_id) {
                if ($addonext_use_custom_size) {
                    $addonext_img_src = \Elementor\Group_Control_Image_Size::get_attachment_image_src($addonext_id, 'image_size', $addonext_settings);
                    if (!empty($addonext_img_src)) {
                        $addonext_url = $addonext_img_src;
                    }
                } else {
                    $addonext_img_src = wp_get_attachment_image_src($addonext_id, $addonext_image_size_name);
                    if (!empty($addonext_img_src[0])) {
                        $addonext_url = $addonext_img_src[0];
                    }
                }
                if ($addonext_url === '' && $addonext_id) {
                    $addonext_full = wp_get_attachment_image_src($addonext_id, 'full');
                    if (!empty($addonext_full[0])) {
                        $addonext_url = $addonext_full[0];
                    }
                }
            }
            if ($addonext_url === '') {
                $addonext_url = $addonext_placeholder_src;
            }
        ?>
            <div class="addonext-image-carousel__slide swiper-slide">
                <img src="<?php echo esc_url($addonext_url); ?>" alt="<?php echo esc_attr($addonext_caption); ?>">
            </div>
        <?php endforeach; ?>
    </div>
    <?php if ($addonext_show_arrows) : ?>
        <div class="addonext-image-carousel__arrow addonext-image-carousel__arrow--prev swiper-button-prev" role="button" tabindex="0" aria-label="<?php esc_attr_e('Previous', 'addonext'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" aria-hidden="true"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
        </div>
        <div class="addonext-image-carousel__arrow addonext-image-carousel__arrow--next swiper-button-next" role="button" tabindex="0" aria-label="<?php esc_attr_e('Next', 'addonext'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" aria-hidden="true"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
        </div>
    <?php endif; ?>
    <?php if ($addonext_show_dots) : ?>
        <div class="swiper-pagination"></div>
    <?php endif; ?>
</div>
