<?php
defined('ABSPATH') || exit;

$addonext_settings = $this->get_settings_for_display();
$addonext_gallery = isset($addonext_settings['gallery']) && is_array($addonext_settings['gallery']) ? $addonext_settings['gallery'] : [];
$addonext_view = isset($addonext_settings['view']) && in_array($addonext_settings['view'], ['grid', 'random'], true) ? $addonext_settings['view'] : 'grid';
$addonext_columns = isset($addonext_settings['columns']) ? (int) $addonext_settings['columns'] : 3;
$addonext_columns = max(2, min(6, $addonext_columns));
$addonext_random_columns = isset($addonext_settings['random_columns']) ? (int) $addonext_settings['random_columns'] : 5;
$addonext_random_columns = max(2, min(6, $addonext_random_columns));
$addonext_enable_preview = !empty($addonext_settings['enable_preview']) && $addonext_settings['enable_preview'] === 'yes';
$addonext_overlay_animation_raw = $addonext_settings['overlay_animation'] ?? 'fade-in';
$addonext_allowed_overlay_animations = ['fade-in', 'fade-out', 'slide-top', 'slide-bottom'];
$addonext_overlay_animation = in_array($addonext_overlay_animation_raw, $addonext_allowed_overlay_animations, true) ? $addonext_overlay_animation_raw : 'fade-in';

$addonext_classes = [
    'addonext-image-gallery',
    'addonext-image-gallery--' . $addonext_view,
];
if ($addonext_view === 'grid') {
    $addonext_classes[] = 'addonext-image-gallery--cols-' . $addonext_columns;
} else {
    $addonext_classes[] = 'addonext-image-gallery--random-cols-' . $addonext_random_columns;
}
if ($addonext_enable_preview) {
    $addonext_classes[] = 'addonext-image-gallery--preview-enabled';
    $addonext_classes[] = 'addonext-image-gallery--overlay-' . $addonext_overlay_animation;
}
?>
<div class="<?php echo esc_attr(implode(' ', $addonext_classes)); ?>">
    <?php if ($addonext_view === 'random') : ?>
        <?php
        $addonext_column_count = $addonext_random_columns;
        $addonext_column_items = array_fill(0, $addonext_column_count, []);
        foreach ($addonext_gallery as $addonext_index => $addonext_item) {
            $addonext_column_items[$addonext_index % $addonext_column_count][] = $addonext_item;
        }
        ?>
        <?php foreach ($addonext_column_items as $addonext_column) : ?>
            <div class="addonext-image-gallery__column">
                <?php foreach ($addonext_column as $addonext_item) : ?>
                    <?php
                    $addonext_url = !empty($addonext_item['image']['url']) ? $addonext_item['image']['url'] : \Elementor\Utils::get_placeholder_image_src();
                    $addonext_caption = isset($addonext_item['caption']) ? $addonext_item['caption'] : '';
                    $addonext_title = isset($addonext_item['title']) ? $addonext_item['title'] : '';
                    $addonext_subtitle = isset($addonext_item['subtitle']) ? $addonext_item['subtitle'] : '';
                    ?>
                    <div class="addonext-image-gallery__item">
                        <img class="addonext-image-gallery__image" src="<?php echo esc_url($addonext_url); ?>" alt="<?php echo esc_attr($addonext_caption); ?>">
                        <?php if ($addonext_enable_preview) : ?>
                            <button type="button" class="addonext-image-gallery__preview" data-image-url="<?php echo esc_url($addonext_url); ?>" data-image-alt="<?php echo esc_attr($addonext_caption); ?>" aria-label="<?php echo esc_attr__('Preview image', 'addonext'); ?>">
                                <span class="addonext-image-gallery__overlay-content">
                                    <?php if ($addonext_title !== '') : ?><span class="addonext-image-gallery__overlay-title"><?php echo esc_html($addonext_title); ?></span><?php endif; ?>
                                    <?php if ($addonext_subtitle !== '') : ?><span class="addonext-image-gallery__overlay-subtitle"><?php echo esc_html($addonext_subtitle); ?></span><?php endif; ?>
                                </span>
                                <span class="addonext-image-gallery__preview-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M2.5 12C4.3 8.4 7.8 6 12 6C16.2 6 19.7 8.4 21.5 12C19.7 15.6 16.2 18 12 18C7.8 18 4.3 15.6 2.5 12Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                        <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8"/>
                                    </svg>
                                </span>
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <?php foreach ($addonext_gallery as $addonext_item) : ?>
            <?php
            $addonext_url = !empty($addonext_item['image']['url']) ? $addonext_item['image']['url'] : \Elementor\Utils::get_placeholder_image_src();
            $addonext_caption = isset($addonext_item['caption']) ? $addonext_item['caption'] : '';
            $addonext_title = isset($addonext_item['title']) ? $addonext_item['title'] : '';
            $addonext_subtitle = isset($addonext_item['subtitle']) ? $addonext_item['subtitle'] : '';
            ?>
            <div class="addonext-image-gallery__item">
                <img class="addonext-image-gallery__image" src="<?php echo esc_url($addonext_url); ?>" alt="<?php echo esc_attr($addonext_caption); ?>">
                <?php if ($addonext_enable_preview) : ?>
                    <button type="button" class="addonext-image-gallery__preview" data-image-url="<?php echo esc_url($addonext_url); ?>" data-image-alt="<?php echo esc_attr($addonext_caption); ?>" aria-label="<?php echo esc_attr__('Preview image', 'addonext'); ?>">
                        <span class="addonext-image-gallery__overlay-content">
                            <?php if ($addonext_title !== '') : ?><span class="addonext-image-gallery__overlay-title"><?php echo esc_html($addonext_title); ?></span><?php endif; ?>
                            <?php if ($addonext_subtitle !== '') : ?><span class="addonext-image-gallery__overlay-subtitle"><?php echo esc_html($addonext_subtitle); ?></span><?php endif; ?>
                        </span>
                        <span class="addonext-image-gallery__preview-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 12C4.3 8.4 7.8 6 12 6C16.2 6 19.7 8.4 21.5 12C19.7 15.6 16.2 18 12 18C7.8 18 4.3 15.6 2.5 12Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8"/>
                            </svg>
                        </span>
                    </button>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
