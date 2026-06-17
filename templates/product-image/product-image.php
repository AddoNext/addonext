<?php
/**
 * Product Image widget template.
 *
 * @var array<int, array{thumb:string,main:string,full:string,alt:string}> $veloria_gallery_images
 * @var \WC_Product $veloria_product
 * @var bool $veloria_show_thumbnails
 * @var bool $veloria_show_thumb_row
 * @var string $veloria_zoom_mode lightbox|pan
 * @var string $veloria_images_json
 * @var float $veloria_pan_scale
 */

defined('ABSPATH') || exit;

$veloria_uid = 'veloria-pi-' . $this->get_id();
$veloria_first = $veloria_gallery_images[0];
$veloria_main_alt = $veloria_first['alt'] !== '' ? $veloria_first['alt'] : $veloria_product->get_name();
?>
<div
    id="<?php echo esc_attr($veloria_uid); ?>"
    class="veloria-product-image elementor-clickable"
    data-zoom="<?php echo esc_attr($veloria_zoom_mode); ?>"
    data-pan-scale="<?php echo esc_attr((string) $veloria_pan_scale); ?>"
    data-images="<?php echo esc_attr($veloria_images_json); ?>"
>
    <?php if ($veloria_zoom_mode === 'lightbox') : ?>
        <button
            type="button"
            class="veloria-pi__main veloria-pi__main--clickable elementor-clickable"
            aria-label="<?php echo esc_attr__('Open image in lightbox', 'veloria'); ?>"
        >
            <span class="veloria-pi__visual">
                <span class="veloria-pi__stage">
                    <img
                        class="veloria-pi__main-img"
                        src="<?php echo esc_url($veloria_first['main']); ?>"
                        alt="<?php echo esc_attr($veloria_main_alt); ?>"
                        width="600"
                        height="600"
                        decoding="async"
                        fetchpriority="high"
                    />
                </span>
            </span>
        </button>
    <?php else : ?>
        <div class="veloria-pi__main veloria-pi__main--pan-zoom">
            <div class="veloria-pi__visual">
                <div
                    class="veloria-pi__stage elementor-clickable"
                    role="button"
                    tabindex="0"
                    aria-label="<?php echo esc_attr__('Click to zoom. When zoomed, drag to move.', 'veloria'); ?>"
                >
                    <img
                        class="veloria-pi__main-img"
                        src="<?php echo esc_url($veloria_first['main']); ?>"
                        alt="<?php echo esc_attr($veloria_main_alt); ?>"
                        width="600"
                        height="600"
                        decoding="async"
                        fetchpriority="high"
                    />
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($veloria_show_thumb_row) : ?>
        <div class="veloria-pi__thumbs" aria-label="<?php echo esc_attr__('Product gallery', 'veloria'); ?>">
            <?php foreach ($veloria_gallery_images as $veloria_index => $veloria_row) : ?>
                <?php
                $veloria_thumb_alt = $veloria_row['alt'] !== '' ? $veloria_row['alt'] : sprintf(
                    /* translators: %d: image position */
                    __('Product image %d', 'veloria'),
                    $veloria_index + 1
                );
                ?>
                <button
                    type="button"
                    class="veloria-pi__thumb elementor-clickable<?php echo $veloria_index === 0 ? ' is-active' : ''; ?>"
                    aria-pressed="<?php echo $veloria_index === 0 ? 'true' : 'false'; ?>"
                    aria-label="<?php echo esc_attr($veloria_thumb_alt); ?>"
                    data-index="<?php echo esc_attr((string) $veloria_index); ?>"
                >
                    <img
                        src="<?php echo esc_url($veloria_row['thumb']); ?>"
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
