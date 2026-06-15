<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$gallery = isset($settings['gallery']) && is_array($settings['gallery']) ? $settings['gallery'] : [];
$view = isset($settings['view']) && in_array($settings['view'], ['grid', 'random'], true) ? $settings['view'] : 'grid';
$columns = isset($settings['columns']) ? (int) $settings['columns'] : 3;
$columns = max(2, min(6, $columns));
$random_columns = isset($settings['random_columns']) ? (int) $settings['random_columns'] : 5;
$random_columns = max(2, min(6, $random_columns));
$enable_preview = !empty($settings['enable_preview']) && $settings['enable_preview'] === 'yes';
$overlay_animation_raw = $settings['overlay_animation'] ?? 'fade-in';
$allowed_overlay_animations = ['fade-in', 'fade-out', 'slide-top', 'slide-bottom'];
$overlay_animation = in_array($overlay_animation_raw, $allowed_overlay_animations, true) ? $overlay_animation_raw : 'fade-in';

$classes = [
    'veloria-image-gallery',
    'veloria-image-gallery--' . $view,
];
if ($view === 'grid') {
    $classes[] = 'veloria-image-gallery--cols-' . $columns;
} else {
    $classes[] = 'veloria-image-gallery--random-cols-' . $random_columns;
}
if ($enable_preview) {
    $classes[] = 'veloria-image-gallery--preview-enabled';
    $classes[] = 'veloria-image-gallery--overlay-' . $overlay_animation;
}
?>
<div class="<?php echo esc_attr(implode(' ', $classes)); ?>">
    <?php if ($view === 'random') : ?>
        <?php
        $column_count = $random_columns;
        $column_items = array_fill(0, $column_count, []);
        foreach ($gallery as $index => $item) {
            $column_items[$index % $column_count][] = $item;
        }
        ?>
        <?php foreach ($column_items as $column) : ?>
            <div class="veloria-image-gallery__column">
                <?php foreach ($column as $item) : ?>
                    <?php
                    $url = !empty($item['image']['url']) ? $item['image']['url'] : \Elementor\Utils::get_placeholder_image_src();
                    $caption = isset($item['caption']) ? $item['caption'] : '';
                    $title = isset($item['title']) ? $item['title'] : '';
                    $subtitle = isset($item['subtitle']) ? $item['subtitle'] : '';
                    ?>
                    <div class="veloria-image-gallery__item">
                        <img class="veloria-image-gallery__image" src="<?php echo esc_url($url); ?>" alt="<?php echo esc_attr($caption); ?>">
                        <?php if ($enable_preview) : ?>
                            <button type="button" class="veloria-image-gallery__preview" data-image-url="<?php echo esc_url($url); ?>" data-image-alt="<?php echo esc_attr($caption); ?>" aria-label="<?php echo esc_attr__('Preview image', 'veloria'); ?>">
                                <span class="veloria-image-gallery__overlay-content">
                                    <?php if ($title !== '') : ?><span class="veloria-image-gallery__overlay-title"><?php echo esc_html($title); ?></span><?php endif; ?>
                                    <?php if ($subtitle !== '') : ?><span class="veloria-image-gallery__overlay-subtitle"><?php echo esc_html($subtitle); ?></span><?php endif; ?>
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
        <?php foreach ($gallery as $item) : ?>
            <?php
            $url = !empty($item['image']['url']) ? $item['image']['url'] : \Elementor\Utils::get_placeholder_image_src();
            $caption = isset($item['caption']) ? $item['caption'] : '';
            $title = isset($item['title']) ? $item['title'] : '';
            $subtitle = isset($item['subtitle']) ? $item['subtitle'] : '';
            ?>
            <div class="veloria-image-gallery__item">
                <img class="veloria-image-gallery__image" src="<?php echo esc_url($url); ?>" alt="<?php echo esc_attr($caption); ?>">
                <?php if ($enable_preview) : ?>
                    <button type="button" class="veloria-image-gallery__preview" data-image-url="<?php echo esc_url($url); ?>" data-image-alt="<?php echo esc_attr($caption); ?>" aria-label="<?php echo esc_attr__('Preview image', 'veloria'); ?>">
                        <span class="veloria-image-gallery__overlay-content">
                            <?php if ($title !== '') : ?><span class="veloria-image-gallery__overlay-title"><?php echo esc_html($title); ?></span><?php endif; ?>
                            <?php if ($subtitle !== '') : ?><span class="veloria-image-gallery__overlay-subtitle"><?php echo esc_html($subtitle); ?></span><?php endif; ?>
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
