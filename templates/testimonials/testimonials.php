<?php
defined('ABSPATH') || exit;

$addonext_settings = $this->get_settings_for_display();
$addonext_items    = isset($addonext_settings['items']) && is_array($addonext_settings['items']) ? $addonext_settings['items'] : [];
$addonext_view     = isset($addonext_settings['view']) ? $addonext_settings['view'] : 'grid';

if (empty($addonext_items)) {
    return;
}

$addonext_placeholder_src = \Elementor\Utils::get_placeholder_image_src();
$addonext_star_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';

function addonext_testimonial_card_style($addonext_item) {
    $addonext_accent = isset($addonext_item['card_accent_color']) ? trim((string) $addonext_item['card_accent_color']) : '';
    if ($addonext_accent === '') {
        return '';
    }
    $addonext_parts = ['border-color: ' . esc_attr($addonext_accent) . ';'];
    $addonext_use_gradient = !empty($addonext_item['card_use_gradient']) && $addonext_item['card_use_gradient'] === 'yes';
    if ($addonext_use_gradient) {
        $addonext_hex = ltrim($addonext_accent, '#');
        if (strlen($addonext_hex) === 3) {
            $addonext_hex = $addonext_hex[0] . $addonext_hex[0] . $addonext_hex[1] . $addonext_hex[1] . $addonext_hex[2] . $addonext_hex[2];
        }
        $addonext_r = hexdec(substr($addonext_hex, 0, 2));
        $addonext_g = hexdec(substr($addonext_hex, 2, 2));
        $addonext_b = hexdec(substr($addonext_hex, 4, 2));
        $addonext_light = "rgba($addonext_r,$addonext_g,$addonext_b,0.14)";
        $addonext_fade = "rgba($addonext_r,$addonext_g,$addonext_b,0.04)";
        $addonext_parts[] = 'background: linear-gradient(135deg, ' . $addonext_light . ' 0%, ' . $addonext_fade . ' 45%, #ffffff 100%);';
    }
    return implode(' ', $addonext_parts);
}

$addonext_is_slider = ($addonext_view === 'slider' && count($addonext_items) > 1);
$addonext_columns   = ($addonext_view === 'grid' && isset($addonext_settings['columns'])) ? (int) $addonext_settings['columns'] : 3;
$addonext_column_gap = ($addonext_view === 'grid' && isset($addonext_settings['column_gap']['size'])) ? (int) $addonext_settings['column_gap']['size'] : 24;

$addonext_nav = isset($addonext_settings['navigation']) ? $addonext_settings['navigation'] : 'both';
$addonext_show_arrows = $addonext_is_slider && ($addonext_nav === 'both' || $addonext_nav === 'arrows');
$addonext_show_dots   = $addonext_is_slider && ($addonext_nav === 'both' || $addonext_nav === 'dots');
$addonext_autoplay    = $addonext_is_slider && !empty($addonext_settings['autoplay']) && $addonext_settings['autoplay'] === 'yes';
$addonext_autoplay_delay = ($addonext_autoplay && isset($addonext_settings['autoplay_delay']) && (int) $addonext_settings['autoplay_delay'] > 0) ? (int) $addonext_settings['autoplay_delay'] : 5;
$addonext_loop        = $addonext_is_slider && (empty($addonext_settings['loop']) || $addonext_settings['loop'] === 'yes');
$addonext_pagination_type = isset($addonext_settings['pagination_type']) ? $addonext_settings['pagination_type'] : 'bullets';
$addonext_dots_gap    = isset($addonext_settings['dots_gap']['size']) ? (int) $addonext_settings['dots_gap']['size'] : 8;
$addonext_bar_width   = ($addonext_pagination_type === 'progressbar' && isset($addonext_settings['bar_width']['size'])) ? (int) $addonext_settings['bar_width']['size'] : 28;
$addonext_slides_per_view = $addonext_is_slider && isset($addonext_settings['slides_per_view']) ? max(1, min(5, (int) $addonext_settings['slides_per_view'])) : 3;
$addonext_slide_gap   = $addonext_is_slider && isset($addonext_settings['slide_gap']['size']) ? (int) $addonext_settings['slide_gap']['size'] : 24;

$addonext_pagination_class = '';
if ($addonext_pagination_type === 'square') {
    $addonext_pagination_class = ' addonext-pagination-square';
} elseif ($addonext_pagination_type === 'progressbar') {
    $addonext_pagination_class = ' addonext-pagination-bar';
}

$addonext_inline_style = '';
if ($addonext_view === 'grid') {
    $addonext_inline_style = '--addonext-testimonials-columns: ' . $addonext_columns . '; --addonext-testimonials-gap: ' . $addonext_column_gap . 'px;';
} else {
    $addonext_inline_style = '--addonext-dots-gap: ' . $addonext_dots_gap . 'px;';
    if ($addonext_pagination_type === 'progressbar') {
        $addonext_inline_style .= ' --addonext-bar-width: ' . $addonext_bar_width . 'px;';
    }
}

if ($addonext_view === 'grid') :
?>
<div class="addonext-testimonials addonext-testimonials--grid" style="<?php echo esc_attr($addonext_inline_style); ?>">
    <?php foreach ($addonext_items as $addonext_item) :
        $addonext_avatar_url = isset($addonext_item['image']['url']) ? trim((string) $addonext_item['image']['url']) : '';
        if ($addonext_avatar_url === '' && !empty($addonext_item['image']['id'])) {
            $addonext_src = wp_get_attachment_image_src((int) $addonext_item['image']['id'], 'thumbnail');
            if (!empty($addonext_src[0])) {
                $addonext_avatar_url = $addonext_src[0];
            }
        }
        if ($addonext_avatar_url === '') {
            $addonext_avatar_url = $addonext_placeholder_src;
        }
        $addonext_rating = isset($addonext_item['rating']) ? min(5.0, max(0.0, (float) $addonext_item['rating'])) : 5.0;
        $addonext_card_style = addonext_testimonial_card_style($addonext_item);
    ?>
        <div class="addonext-testimonial-card"<?php echo $addonext_card_style !== '' ? ' style="' . esc_attr($addonext_card_style) . '"' : ''; ?>>
            <div class="addonext-testimonial__stars">
                <?php for ($addonext_i = 0; $addonext_i < 5; $addonext_i++) :
                    $addonext_remainder = $addonext_rating - $addonext_i;
                    $addonext_fill = min(1.0, max(0.0, $addonext_remainder));
                    $addonext_fill_pct = (int) round($addonext_fill * 100);
                ?>
                    <span class="addonext-testimonial__star">
                        <span class="addonext-testimonial__star-empty"><?php
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static inline SVG markup.
                        echo $addonext_star_svg;
                        ?></span>
                        <span class="addonext-testimonial__star-fill" style="width:<?php echo esc_attr($addonext_fill_pct); ?>%">
                            <span class="addonext-testimonial__star-filled"><?php
                            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static inline SVG markup.
                            echo $addonext_star_svg;
                            ?></span>
                        </span>
                    </span>
                <?php endfor; ?>
            </div>
            <blockquote class="addonext-testimonial-card__quote">"<?php echo wp_kses_post($addonext_item['content'] ?? ''); ?>"</blockquote>
            <div class="addonext-testimonial-card__footer">
                <img class="addonext-testimonial-card__avatar" src="<?php echo esc_url($addonext_avatar_url); ?>" alt="<?php echo esc_attr($addonext_item['name'] ?? ''); ?>">
                <div class="addonext-testimonial-card__meta">
                    <span class="addonext-testimonial-card__name"><?php echo esc_html($addonext_item['name'] ?? ''); ?></span>
                    <?php if (!empty($addonext_item['role'])) : ?>
                        <span class="addonext-testimonial-card__role"><?php echo esc_html($addonext_item['role']); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php
else :
?>
<div class="addonext-testimonials addonext-testimonials--slider swiper<?php echo esc_attr($addonext_pagination_class); ?>" data-autoplay="<?php echo $addonext_autoplay ? '1' : '0'; ?>" data-autoplay-delay="<?php echo esc_attr($addonext_autoplay_delay); ?>" data-loop="<?php echo $addonext_loop ? '1' : '0'; ?>" data-pagination-type="<?php echo esc_attr($addonext_pagination_type); ?>" data-slides-per-view="<?php echo esc_attr($addonext_slides_per_view); ?>" data-space-between="<?php echo esc_attr($addonext_slide_gap); ?>" style="<?php echo esc_attr($addonext_inline_style); ?>">
    <div class="swiper-wrapper">
        <?php foreach ($addonext_items as $addonext_item) :
            $addonext_avatar_url = isset($addonext_item['image']['url']) ? trim((string) $addonext_item['image']['url']) : '';
            if ($addonext_avatar_url === '' && !empty($addonext_item['image']['id'])) {
                $addonext_src = wp_get_attachment_image_src((int) $addonext_item['image']['id'], 'thumbnail');
                if (!empty($addonext_src[0])) {
                    $addonext_avatar_url = $addonext_src[0];
                }
            }
            if ($addonext_avatar_url === '') {
                $addonext_avatar_url = $addonext_placeholder_src;
            }
            $addonext_rating = isset($addonext_item['rating']) ? min(5.0, max(0.0, (float) $addonext_item['rating'])) : 5.0;
            $addonext_card_style = addonext_testimonial_card_style($addonext_item);
        ?>
            <div class="swiper-slide">
                <div class="addonext-testimonial-card"<?php echo $addonext_card_style !== '' ? ' style="' . esc_attr($addonext_card_style) . '"' : ''; ?>>
                    <div class="addonext-testimonial__stars">
                        <?php for ($addonext_i = 0; $addonext_i < 5; $addonext_i++) :
                            $addonext_remainder = $addonext_rating - $addonext_i;
                            $addonext_fill = min(1.0, max(0.0, $addonext_remainder));
                            $addonext_fill_pct = (int) round($addonext_fill * 100);
                        ?>
                            <span class="addonext-testimonial__star">
                                <span class="addonext-testimonial__star-empty"><?php
                                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static inline SVG markup.
                                echo $addonext_star_svg;
                                ?></span>
                                <span class="addonext-testimonial__star-fill" style="width:<?php echo esc_attr($addonext_fill_pct); ?>%">
                                    <span class="addonext-testimonial__star-filled"><?php
                                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static inline SVG markup.
                                    echo $addonext_star_svg;
                                    ?></span>
                                </span>
                            </span>
                        <?php endfor; ?>
                    </div>
                    <blockquote class="addonext-testimonial-card__quote">"<?php echo wp_kses_post($addonext_item['content'] ?? ''); ?>"</blockquote>
                    <div class="addonext-testimonial-card__footer">
                        <img class="addonext-testimonial-card__avatar" src="<?php echo esc_url($addonext_avatar_url); ?>" alt="<?php echo esc_attr($addonext_item['name'] ?? ''); ?>">
                        <div class="addonext-testimonial-card__meta">
                            <span class="addonext-testimonial-card__name"><?php echo esc_html($addonext_item['name'] ?? ''); ?></span>
                            <?php if (!empty($addonext_item['role'])) : ?>
                                <span class="addonext-testimonial-card__role"><?php echo esc_html($addonext_item['role']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php if ($addonext_show_arrows) : ?>
        <div class="addonext-testimonials__arrow addonext-testimonials__arrow--prev swiper-button-prev" role="button" tabindex="0" aria-label="<?php esc_attr_e('Previous', 'addonext'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" aria-hidden="true"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
        </div>
        <div class="addonext-testimonials__arrow addonext-testimonials__arrow--next swiper-button-next" role="button" tabindex="0" aria-label="<?php esc_attr_e('Next', 'addonext'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" aria-hidden="true"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
        </div>
    <?php endif; ?>
    <?php if ($addonext_show_dots) : ?>
        <div class="swiper-pagination"></div>
    <?php endif; ?>
</div>
<?php
endif;
