<?php
defined('ABSPATH') || exit;

$veloria_settings = $this->get_settings_for_display();
$veloria_logos = isset($veloria_settings['logos']) && is_array($veloria_settings['logos']) ? $veloria_settings['logos'] : [];
$veloria_view = isset($veloria_settings['view']) ? $veloria_settings['view'] : 'grid';
$veloria_show_title = !empty($veloria_settings['show_title']) && $veloria_settings['show_title'] === 'yes';
$veloria_title = $veloria_show_title && isset($veloria_settings['title']) ? $veloria_settings['title'] : '';

if (empty($veloria_logos)) {
    return;
}

$veloria_placeholder_src = \Elementor\Utils::get_placeholder_image_src();
$veloria_is_slider = ($veloria_view === 'slider' && count($veloria_logos) > 1);
$veloria_columns_mode = ($veloria_view === 'grid' && isset($veloria_settings['columns_mode'])) ? $veloria_settings['columns_mode'] : 'auto';
$veloria_columns = ($veloria_view === 'grid' && $veloria_columns_mode === 'custom' && isset($veloria_settings['columns'])) ? max(1, min(6, (int) $veloria_settings['columns'])) : 4;
$veloria_column_gap = ($veloria_view === 'grid' && isset($veloria_settings['column_gap']['size'])) ? (int) $veloria_settings['column_gap']['size'] : 24;
$veloria_grid_auto_class = ($veloria_view === 'grid' && $veloria_columns_mode === 'auto') ? ' veloria-logo-showcase__grid--auto' : '';
$veloria_nav = isset($veloria_settings['navigation']) ? $veloria_settings['navigation'] : 'none';
$veloria_show_arrows = $veloria_is_slider && ($veloria_nav === 'both' || $veloria_nav === 'arrows');
$veloria_show_dots = $veloria_is_slider && ($veloria_nav === 'both' || $veloria_nav === 'dots');
$veloria_autoplay = $veloria_is_slider && !empty($veloria_settings['autoplay']) && $veloria_settings['autoplay'] === 'yes';
$veloria_autoplay_delay = ($veloria_autoplay && isset($veloria_settings['autoplay_delay']) && (int) $veloria_settings['autoplay_delay'] > 0) ? (int) $veloria_settings['autoplay_delay'] : 3;
$veloria_loop = $veloria_is_slider && (empty($veloria_settings['loop']) || $veloria_settings['loop'] === 'yes');
$veloria_pagination_type = isset($veloria_settings['pagination_type']) ? $veloria_settings['pagination_type'] : 'bullets';
$veloria_dots_gap = isset($veloria_settings['dots_gap']['size']) ? (int) $veloria_settings['dots_gap']['size'] : 8;
$veloria_slides_per_view = $veloria_is_slider && isset($veloria_settings['slides_per_view']) ? max(1, min(6, (int) $veloria_settings['slides_per_view'])) : 4;
$veloria_slide_gap = $veloria_is_slider && isset($veloria_settings['slide_gap']['size']) ? (int) $veloria_settings['slide_gap']['size'] : 24;

$veloria_pagination_class = '';
if ($veloria_pagination_type === 'square') {
    $veloria_pagination_class = ' veloria-pagination-square';
} elseif ($veloria_pagination_type === 'progressbar') {
    $veloria_pagination_class = ' veloria-pagination-bar';
}

$veloria_inline_style = '';
if ($veloria_view === 'grid') {
    $veloria_inline_style = '--veloria-logo-showcase-gap: ' . $veloria_column_gap . 'px;';
    if ($veloria_columns_mode === 'custom') {
        $veloria_inline_style .= ' --veloria-logo-showcase-columns: ' . $veloria_columns . ';';
    }
} else {
    $veloria_inline_style = '--veloria-dots-gap: ' . $veloria_dots_gap . 'px;';
}

function veloria_logo_showcase_img_url($veloria_item, $veloria_placeholder_src) {
    $veloria_url = isset($veloria_item['logo']['url']) ? trim((string) $veloria_item['logo']['url']) : '';
    if ($veloria_url === '' && !empty($veloria_item['logo']['id'])) {
        $veloria_src = wp_get_attachment_image_src((int) $veloria_item['logo']['id'], 'medium');
        if (!empty($veloria_src[0])) {
            $veloria_url = $veloria_src[0];
        }
    }
    return $veloria_url !== '' ? $veloria_url : $veloria_placeholder_src;
}
?>
<div class="veloria-logo-showcase">
    <?php if ($veloria_show_title && $veloria_title !== '') : ?>
        <h3 class="veloria-logo-showcase__title"><?php echo esc_html($veloria_title); ?></h3>
    <?php endif; ?>
    <?php if ($veloria_view === 'grid') : ?>
        <div class="veloria-logo-showcase__grid<?php echo esc_attr($veloria_grid_auto_class); ?>" style="<?php echo esc_attr($veloria_inline_style); ?>">
            <?php foreach ($veloria_logos as $veloria_item) :
                $veloria_img_url = veloria_logo_showcase_img_url($veloria_item, $veloria_placeholder_src);
                $veloria_link_url = !empty($veloria_item['link']['url']) ? esc_url($veloria_item['link']['url']) : '';
                $veloria_alt = isset($veloria_item['alt']) ? $veloria_item['alt'] : '';
            ?>
                <div class="veloria-logo-showcase__card">
                    <?php if ($veloria_link_url !== '') : ?><a href="<?php echo esc_url($veloria_link_url); ?>" class="veloria-logo-showcase__link"><?php endif; ?>
                        <img class="veloria-logo-showcase__img" src="<?php echo esc_url($veloria_img_url); ?>" alt="<?php echo esc_attr($veloria_alt); ?>">
                    <?php if ($veloria_link_url !== '') : ?></a><?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="veloria-logo-showcase--slider swiper<?php echo esc_attr($veloria_pagination_class); ?>"
             data-autoplay="<?php echo $veloria_autoplay ? '1' : '0'; ?>"
             data-autoplay-delay="<?php echo esc_attr($veloria_autoplay_delay); ?>"
             data-loop="<?php echo $veloria_loop ? '1' : '0'; ?>"
             data-pagination-type="<?php echo esc_attr($veloria_pagination_type); ?>"
             data-slides-per-view="<?php echo esc_attr($veloria_slides_per_view); ?>"
             data-space-between="<?php echo esc_attr($veloria_slide_gap); ?>"
             style="<?php echo esc_attr($veloria_inline_style); ?>">
            <div class="swiper-wrapper">
                <?php foreach ($veloria_logos as $veloria_item) :
                    $veloria_img_url = veloria_logo_showcase_img_url($veloria_item, $veloria_placeholder_src);
                    $veloria_link_url = !empty($veloria_item['link']['url']) ? esc_url($veloria_item['link']['url']) : '';
                    $veloria_alt = isset($veloria_item['alt']) ? $veloria_item['alt'] : '';
                ?>
                    <div class="swiper-slide">
                        <div class="veloria-logo-showcase__card">
                            <?php if ($veloria_link_url !== '') : ?><a href="<?php echo esc_url($veloria_link_url); ?>" class="veloria-logo-showcase__link"><?php endif; ?>
                                <img class="veloria-logo-showcase__img" src="<?php echo esc_url($veloria_img_url); ?>" alt="<?php echo esc_attr($veloria_alt); ?>">
                            <?php if ($veloria_link_url !== '') : ?></a><?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if ($veloria_show_arrows) : ?>
                <div class="veloria-logo-showcase__arrow veloria-logo-showcase__arrow--prev swiper-button-prev" role="button" tabindex="0" aria-label="<?php esc_attr_e('Previous', 'veloria'); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" aria-hidden="true"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
                </div>
                <div class="veloria-logo-showcase__arrow veloria-logo-showcase__arrow--next swiper-button-next" role="button" tabindex="0" aria-label="<?php esc_attr_e('Next', 'veloria'); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" aria-hidden="true"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
                </div>
            <?php endif; ?>
            <?php if ($veloria_show_dots) : ?>
                <div class="swiper-pagination"></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
