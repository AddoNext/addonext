<?php
/**
 * Product Image widget template.
 *
 * @var array<int, array{thumb:string,main:string,full:string,alt:string}> $gallery_images
 * @var bool $show_thumbnails
 * @var bool $show_thumb_row
 * @var string $zoom_mode lightbox|pan
 * @var string $images_json
 * @var float $pan_scale
 */

defined('ABSPATH') || exit;

$uid = 'elementrify-pi-' . $this->get_id();
$first = $gallery_images[0];
$main_alt = $first['alt'] !== '' ? $first['alt'] : $product->get_name();
?>
<div
    id="<?php echo esc_attr($uid); ?>"
    class="elementrify-product-image elementor-clickable"
    data-zoom="<?php echo esc_attr($zoom_mode); ?>"
    data-pan-scale="<?php echo esc_attr((string) $pan_scale); ?>"
    data-images="<?php echo esc_attr($images_json); ?>"
>
    <?php if ($zoom_mode === 'lightbox') : ?>
        <button
            type="button"
            class="elementrify-pi__main elementrify-pi__main--clickable elementor-clickable"
            aria-label="<?php echo esc_attr__('Open image in lightbox', 'elementrify'); ?>"
        >
            <span class="elementrify-pi__visual">
                <span class="elementrify-pi__stage">
                    <img
                        class="elementrify-pi__main-img"
                        src="<?php echo esc_url($first['main']); ?>"
                        alt="<?php echo esc_attr($main_alt); ?>"
                        width="600"
                        height="600"
                        decoding="async"
                        fetchpriority="high"
                    />
                </span>
            </span>
        </button>
    <?php else : ?>
        <div class="elementrify-pi__main elementrify-pi__main--pan-zoom">
            <div class="elementrify-pi__visual">
                <div
                    class="elementrify-pi__stage elementor-clickable"
                    role="button"
                    tabindex="0"
                    aria-label="<?php echo esc_attr__('Click to zoom. When zoomed, drag to move.', 'elementrify'); ?>"
                >
                    <img
                        class="elementrify-pi__main-img"
                        src="<?php echo esc_url($first['main']); ?>"
                        alt="<?php echo esc_attr($main_alt); ?>"
                        width="600"
                        height="600"
                        decoding="async"
                        fetchpriority="high"
                    />
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($show_thumb_row) : ?>
        <div class="elementrify-pi__thumbs" aria-label="<?php echo esc_attr__('Product gallery', 'elementrify'); ?>">
            <?php foreach ($gallery_images as $index => $row) : ?>
                <?php
                $thumb_alt = $row['alt'] !== '' ? $row['alt'] : sprintf(
                    /* translators: %d: image position */
                    __('Product image %d', 'elementrify'),
                    $index + 1
                );
                ?>
                <button
                    type="button"
                    class="elementrify-pi__thumb elementor-clickable<?php echo $index === 0 ? ' is-active' : ''; ?>"
                    aria-pressed="<?php echo $index === 0 ? 'true' : 'false'; ?>"
                    aria-label="<?php echo esc_attr($thumb_alt); ?>"
                    data-index="<?php echo esc_attr((string) $index); ?>"
                >
                    <img
                        src="<?php echo esc_url($row['thumb']); ?>"
                        alt=""
                        width="100"
                        height="100"
                        loading="lazy"
                        decoding="async"
                    />
                </button>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
