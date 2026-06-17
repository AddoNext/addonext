<?php
defined('ABSPATH') || exit;

$veloria_settings   = $this->get_settings_for_display();
$veloria_rating     = isset($veloria_settings['rating']) ? (float) $veloria_settings['rating'] : 0;
$veloria_max_stars  = isset($veloria_settings['max_stars']) ? min(10, max(1, (int) $veloria_settings['max_stars'])) : 5;
$veloria_show_title = !empty($veloria_settings['show_title']) && $veloria_settings['show_title'] === 'yes';
$veloria_title      = isset($veloria_settings['title']) ? $veloria_settings['title'] : '';

$veloria_star_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';
?>
<div class="veloria-star-rating">
    <div class="veloria-star-rating__stars">
        <?php for ($veloria_i = 0; $veloria_i < $veloria_max_stars; $veloria_i++) :
            $veloria_remainder    = $veloria_rating - $veloria_i;
            $veloria_fill         = min(1.0, max(0.0, $veloria_remainder));
            $veloria_fill_percent = (int) round($veloria_fill * 100);
        ?>
            <span class="veloria-star-rating__star">
                <span class="veloria-star-rating__star-empty"><?php
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static inline SVG markup.
                echo $veloria_star_svg;
                ?></span>
                <span class="veloria-star-rating__star-fill" style="width: <?php echo esc_attr($veloria_fill_percent); ?>%">
                    <span class="veloria-star-rating__star-filled"><?php
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static inline SVG markup.
                    echo $veloria_star_svg;
                    ?></span>
                </span>
            </span>
        <?php endfor; ?>
    </div>
    <?php if ($veloria_show_title && $veloria_title !== '') : ?>
        <span class="veloria-star-rating__title"><?php echo esc_html($veloria_title); ?></span>
    <?php endif; ?>
</div>
