<?php
defined('ABSPATH') || exit;

$veloria_settings = $this->get_settings_for_display();
$veloria_gallery = isset($veloria_settings['gallery']) && is_array($veloria_settings['gallery']) ? $veloria_settings['gallery'] : [];
$veloria_view = isset($veloria_settings['view']) && in_array($veloria_settings['view'], ['grid', 'random'], true) ? $veloria_settings['view'] : 'grid';
$veloria_columns = isset($veloria_settings['columns']) ? (int) $veloria_settings['columns'] : 3;
$veloria_columns = max(2, min(6, $veloria_columns));
$veloria_random_columns = isset($veloria_settings['random_columns']) ? (int) $veloria_settings['random_columns'] : 5;
$veloria_random_columns = max(2, min(6, $veloria_random_columns));
$veloria_enable_preview = !empty($veloria_settings['enable_preview']) && $veloria_settings['enable_preview'] === 'yes';
$veloria_overlay_animation_raw = $veloria_settings['overlay_animation'] ?? 'fade-in';
$veloria_allowed_overlay_animations = ['fade-in', 'fade-out', 'slide-top', 'slide-bottom'];
$veloria_overlay_animation = in_array($veloria_overlay_animation_raw, $veloria_allowed_overlay_animations, true) ? $veloria_overlay_animation_raw : 'fade-in';

$veloria_classes = [
    'veloria-image-gallery',
    'veloria-image-gallery--' . $veloria_view,
];
if ($veloria_view === 'grid') {
    $veloria_classes[] = 'veloria-image-gallery--cols-' . $veloria_columns;
} else {
    $veloria_classes[] = 'veloria-image-gallery--random-cols-' . $veloria_random_columns;
}
if ($veloria_enable_preview) {
    $veloria_classes[] = 'veloria-image-gallery--preview-enabled';
    $veloria_classes[] = 'veloria-image-gallery--overlay-' . $veloria_overlay_animation;
}
?>
<div class="<?php echo esc_attr(implode(' ', $veloria_classes)); ?>">
    <?php if ($veloria_view === 'random') : ?>
        <?php
        $veloria_column_count = $veloria_random_columns;
        $veloria_column_items = array_fill(0, $veloria_column_count, []);
        foreach ($veloria_gallery as $veloria_index => $veloria_item) {
            $veloria_column_items[$veloria_index % $veloria_column_count][] = $veloria_item;
        }
        ?>
        <?php foreach ($veloria_column_items as $veloria_column) : ?>
            <div class="veloria-image-gallery__column">
                <?php foreach ($veloria_column as $veloria_item) : ?>
                    <?php
                    $veloria_url = !empty($veloria_item['image']['url']) ? $veloria_item['image']['url'] : \Elementor\Utils::get_placeholder_image_src();
                    $veloria_caption = isset($veloria_item['caption']) ? $veloria_item['caption'] : '';
                    $veloria_title = isset($veloria_item['title']) ? $veloria_item['title'] : '';
                    $veloria_subtitle = isset($veloria_item['subtitle']) ? $veloria_item['subtitle'] : '';
                    ?>
                    <div class="veloria-image-gallery__item">
                        <img class="veloria-image-gallery__image" src="<?php echo esc_url($veloria_url); ?>" alt="<?php echo esc_attr($veloria_caption); ?>">
                        <?php if ($veloria_enable_preview) : ?>
                            <button type="button" class="veloria-image-gallery__preview" data-image-url="<?php echo esc_url($veloria_url); ?>" data-image-alt="<?php echo esc_attr($veloria_caption); ?>" aria-label="<?php echo esc_attr__('Preview image', 'veloria'); ?>">
                                <span class="veloria-image-gallery__overlay-content">
                                    <?php if ($veloria_title !== '') : ?><span class="veloria-image-gallery__overlay-title"><?php echo esc_html($veloria_title); ?></span><?php endif; ?>
                                    <?php if ($veloria_subtitle !== '') : ?><span class="veloria-image-gallery__overlay-subtitle"><?php echo esc_html($veloria_subtitle); ?></span><?php endif; ?>
                                </span>
                                <span class="veloria-image-gallery__preview-icon" aria-hidden="true">
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
        <?php foreach ($veloria_gallery as $veloria_item) : ?>
            <?php
            $veloria_url = !empty($veloria_item['image']['url']) ? $veloria_item['image']['url'] : \Elementor\Utils::get_placeholder_image_src();
            $veloria_caption = isset($veloria_item['caption']) ? $veloria_item['caption'] : '';
            $veloria_title = isset($veloria_item['title']) ? $veloria_item['title'] : '';
            $veloria_subtitle = isset($veloria_item['subtitle']) ? $veloria_item['subtitle'] : '';
            ?>
            <div class="veloria-image-gallery__item">
                <img class="veloria-image-gallery__image" src="<?php echo esc_url($veloria_url); ?>" alt="<?php echo esc_attr($veloria_caption); ?>">
                <?php if ($veloria_enable_preview) : ?>
                    <button type="button" class="veloria-image-gallery__preview" data-image-url="<?php echo esc_url($veloria_url); ?>" data-image-alt="<?php echo esc_attr($veloria_caption); ?>" aria-label="<?php echo esc_attr__('Preview image', 'veloria'); ?>">
                        <span class="veloria-image-gallery__overlay-content">
                            <?php if ($veloria_title !== '') : ?><span class="veloria-image-gallery__overlay-title"><?php echo esc_html($veloria_title); ?></span><?php endif; ?>
                            <?php if ($veloria_subtitle !== '') : ?><span class="veloria-image-gallery__overlay-subtitle"><?php echo esc_html($veloria_subtitle); ?></span><?php endif; ?>
                        </span>
                        <span class="veloria-image-gallery__preview-icon" aria-hidden="true">
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
