<?php
defined('ABSPATH') || exit;

$addonext_settings = $this->get_settings_for_display();
$addonext_logos = isset($addonext_settings['logos']) && is_array($addonext_settings['logos']) ? $addonext_settings['logos'] : [];
$addonext_view = isset($addonext_settings['view']) ? $addonext_settings['view'] : 'grid';
$addonext_show_title = !empty($addonext_settings['show_title']) && $addonext_settings['show_title'] === 'yes';
$addonext_title = $addonext_show_title && isset($addonext_settings['title']) ? $addonext_settings['title'] : '';

if (empty($addonext_logos)) {
    return;
}

$addonext_placeholder_src = \Elementor\Utils::get_placeholder_image_src();
$addonext_is_slider = ($addonext_view === 'slider' && count($addonext_logos) > 1);
$addonext_columns_mode = ($addonext_view === 'grid' && isset($addonext_settings['columns_mode'])) ? $addonext_settings['columns_mode'] : 'auto';
$addonext_columns = ($addonext_view === 'grid' && $addonext_columns_mode === 'custom' && isset($addonext_settings['columns'])) ? max(1, min(6, (int) $addonext_settings['columns'])) : 4;
$addonext_column_gap = ($addonext_view === 'grid' && isset($addonext_settings['column_gap']['size'])) ? (int) $addonext_settings['column_gap']['size'] : 24;
$addonext_grid_auto_class = ($addonext_view === 'grid' && $addonext_columns_mode === 'auto') ? ' addonext-logo-showcase__grid--auto' : '';
$addonext_nav = isset($addonext_settings['navigation']) ? $addonext_settings['navigation'] : 'none';
$addonext_show_arrows = $addonext_is_slider && ($addonext_nav === 'both' || $addonext_nav === 'arrows');
$addonext_show_dots = $addonext_is_slider && ($addonext_nav === 'both' || $addonext_nav === 'dots');
$addonext_autoplay = $addonext_is_slider && !empty($addonext_settings['autoplay']) && $addonext_settings['autoplay'] === 'yes';
$addonext_autoplay_delay = ($addonext_autoplay && isset($addonext_settings['autoplay_delay']) && (int) $addonext_settings['autoplay_delay'] > 0) ? (int) $addonext_settings['autoplay_delay'] : 3;
$addonext_loop = $addonext_is_slider && (empty($addonext_settings['loop']) || $addonext_settings['loop'] === 'yes');
$addonext_pagination_type = isset($addonext_settings['pagination_type']) ? $addonext_settings['pagination_type'] : 'bullets';
$addonext_dots_gap = isset($addonext_settings['dots_gap']['size']) ? (int) $addonext_settings['dots_gap']['size'] : 8;
$addonext_slides_per_view = $addonext_is_slider && isset($addonext_settings['slides_per_view']) ? max(1, min(6, (int) $addonext_settings['slides_per_view'])) : 4;
$addonext_slide_gap = $addonext_is_slider && isset($addonext_settings['slide_gap']['size']) ? (int) $addonext_settings['slide_gap']['size'] : 24;

$addonext_pagination_class = '';
if ($addonext_pagination_type === 'square') {
    $addonext_pagination_class = ' addonext-pagination-square';
} elseif ($addonext_pagination_type === 'progressbar') {
    $addonext_pagination_class = ' addonext-pagination-bar';
}

$addonext_inline_style = '';
if ($addonext_view === 'grid') {
    $addonext_inline_style = '--addonext-logo-showcase-gap: ' . $addonext_column_gap . 'px;';
    if ($addonext_columns_mode === 'custom') {
        $addonext_inline_style .= ' --addonext-logo-showcase-columns: ' . $addonext_columns . ';';
    }
} else {
    $addonext_inline_style = '--addonext-dots-gap: ' . $addonext_dots_gap . 'px;';
}

function addonext_logo_showcase_img_url($addonext_item, $addonext_placeholder_src) {
    $addonext_url = isset($addonext_item['logo']['url']) ? trim((string) $addonext_item['logo']['url']) : '';
    if ($addonext_url === '' && !empty($addonext_item['logo']['id'])) {
        $addonext_src = wp_get_attachment_image_src((int) $addonext_item['logo']['id'], 'medium');
        if (!empty($addonext_src[0])) {
            $addonext_url = $addonext_src[0];
        }
    }
    return $addonext_url !== '' ? $addonext_url : $addonext_placeholder_src;
}
?>
<div class="addonext-logo-showcase">
    <?php if ($addonext_show_title && $addonext_title !== '') : ?>
        <h3 class="addonext-logo-showcase__title"><?php echo esc_html($addonext_title); ?></h3>
    <?php endif; ?>
    <?php if ($addonext_view === 'grid') : ?>
        <div class="addonext-logo-showcase__grid<?php echo esc_attr($addonext_grid_auto_class); ?>" style="<?php echo esc_attr($addonext_inline_style); ?>">
            <?php foreach ($addonext_logos as $addonext_item) :
                $addonext_img_url = addonext_logo_showcase_img_url($addonext_item, $addonext_placeholder_src);
                $addonext_link_url = !empty($addonext_item['link']['url']) ? esc_url($addonext_item['link']['url']) : '';
                $addonext_alt = isset($addonext_item['alt']) ? $addonext_item['alt'] : '';
            ?>
                <div class="addonext-logo-showcase__card">
                    <?php if ($addonext_link_url !== '') : ?><a href="<?php echo esc_url($addonext_link_url); ?>" class="addonext-logo-showcase__link"><?php endif; ?>
                        <img class="addonext-logo-showcase__img" src="<?php echo esc_url($addonext_img_url); ?>" alt="<?php echo esc_attr($addonext_alt); ?>">
                    <?php if ($addonext_link_url !== '') : ?></a><?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="addonext-logo-showcase--slider swiper<?php echo esc_attr($addonext_pagination_class); ?>"
             data-autoplay="<?php echo $addonext_autoplay ? '1' : '0'; ?>"
             data-autoplay-delay="<?php echo esc_attr($addonext_autoplay_delay); ?>"
             data-loop="<?php echo $addonext_loop ? '1' : '0'; ?>"
             data-pagination-type="<?php echo esc_attr($addonext_pagination_type); ?>"
             data-slides-per-view="<?php echo esc_attr($addonext_slides_per_view); ?>"
             data-space-between="<?php echo esc_attr($addonext_slide_gap); ?>"
             style="<?php echo esc_attr($addonext_inline_style); ?>">
            <div class="swiper-wrapper">
                <?php foreach ($addonext_logos as $addonext_item) :
                    $addonext_img_url = addonext_logo_showcase_img_url($addonext_item, $addonext_placeholder_src);
                    $addonext_link_url = !empty($addonext_item['link']['url']) ? esc_url($addonext_item['link']['url']) : '';
                    $addonext_alt = isset($addonext_item['alt']) ? $addonext_item['alt'] : '';
                ?>
                    <div class="swiper-slide">
                        <div class="addonext-logo-showcase__card">
                            <?php if ($addonext_link_url !== '') : ?><a href="<?php echo esc_url($addonext_link_url); ?>" class="addonext-logo-showcase__link"><?php endif; ?>
                                <img class="addonext-logo-showcase__img" src="<?php echo esc_url($addonext_img_url); ?>" alt="<?php echo esc_attr($addonext_alt); ?>">
                            <?php if ($addonext_link_url !== '') : ?></a><?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if ($addonext_show_arrows) : ?>
                <div class="addonext-logo-showcase__arrow addonext-logo-showcase__arrow--prev swiper-button-prev" role="button" tabindex="0" aria-label="<?php esc_attr_e('Previous', 'addonext'); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" aria-hidden="true"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
                </div>
                <div class="addonext-logo-showcase__arrow addonext-logo-showcase__arrow--next swiper-button-next" role="button" tabindex="0" aria-label="<?php esc_attr_e('Next', 'addonext'); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" aria-hidden="true"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
                </div>
            <?php endif; ?>
            <?php if ($addonext_show_dots) : ?>
                <div class="swiper-pagination"></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
