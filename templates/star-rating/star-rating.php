<?php
defined('ABSPATH') || exit;

$settings   = $this->get_settings_for_display();
$rating     = isset($settings['rating']) ? (float) $settings['rating'] : 0;
$max_stars  = isset($settings['max_stars']) ? min(10, max(1, (int) $settings['max_stars'])) : 5;
$show_title = !empty($settings['show_title']) && $settings['show_title'] === 'yes';
$title      = isset($settings['title']) ? $settings['title'] : '';

$star_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';
?>
<div class="veloria-star-rating">
    <div class="veloria-star-rating__stars">
        <?php for ($i = 0; $i < $max_stars; $i++) :
            $remainder    = $rating - $i;
            $fill         = min(1.0, max(0.0, $remainder));
            $fill_percent = (int) round($fill * 100);
        ?>
            <span class="veloria-star-rating__star">
                <span class="veloria-star-rating__star-empty"><?php
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static inline SVG markup.
                echo $star_svg;
                ?></span>
                <span class="veloria-star-rating__star-fill" style="width: <?php echo esc_attr($fill_percent); ?>%">
                    <span class="veloria-star-rating__star-filled"><?php
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static inline SVG markup.
                    echo $star_svg;
                    ?></span>
                </span>
            </span>
        <?php endfor; ?>
    </div>
    <?php if ($show_title && $title !== '') : ?>
        <span class="veloria-star-rating__title"><?php echo esc_html($title); ?></span>
    <?php endif; ?>
</div>
