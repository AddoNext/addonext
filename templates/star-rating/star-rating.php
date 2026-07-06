<?php
defined('ABSPATH') || exit;

$addonext_settings   = $this->get_settings_for_display();
$addonext_rating     = isset($addonext_settings['rating']) ? (float) $addonext_settings['rating'] : 0;
$addonext_max_stars  = isset($addonext_settings['max_stars']) ? min(10, max(1, (int) $addonext_settings['max_stars'])) : 5;
$addonext_show_title = !empty($addonext_settings['show_title']) && $addonext_settings['show_title'] === 'yes';
$addonext_title      = isset($addonext_settings['title']) ? $addonext_settings['title'] : '';

$addonext_star_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';
?>
<div class="addonext-star-rating">
    <div class="addonext-star-rating__stars">
        <?php for ($addonext_i = 0; $addonext_i < $addonext_max_stars; $addonext_i++) :
            $addonext_remainder    = $addonext_rating - $addonext_i;
            $addonext_fill         = min(1.0, max(0.0, $addonext_remainder));
            $addonext_fill_percent = (int) round($addonext_fill * 100);
        ?>
            <span class="addonext-star-rating__star">
                <span class="addonext-star-rating__star-empty"><?php
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static inline SVG markup.
                echo $addonext_star_svg;
                ?></span>
                <span class="addonext-star-rating__star-fill" style="width: <?php echo esc_attr($addonext_fill_percent); ?>%">
                    <span class="addonext-star-rating__star-filled"><?php
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static inline SVG markup.
                    echo $addonext_star_svg;
                    ?></span>
                </span>
            </span>
        <?php endfor; ?>
    </div>
    <?php if ($addonext_show_title && $addonext_title !== '') : ?>
        <span class="addonext-star-rating__title"><?php echo esc_html($addonext_title); ?></span>
    <?php endif; ?>
</div>
