<?php
defined('ABSPATH') || exit;

use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$s        = $this->get_settings_for_display();
$split    = isset($s['initial_position']['size']) ? (float) $s['initial_position']['size'] : 50.0;
$split    = max(0.0, min(100.0, $split));
$vertical = isset($s['slide_direction']) && $s['slide_direction'] === 'vertical';

$before = $s['image_before'] ?? [];
$after  = $s['image_after'] ?? [];

$imgBefore = '';
if (!empty($before['id'])) {
    $src = Group_Control_Image_Size::get_attachment_image_src((int) $before['id'], 'image_size', $s);
    if ($src) {
        $imgBefore = '<img class="veloria-before-after__img" src="' . esc_url($src) . '" alt="' . esc_attr($before['alt'] ?? '') . '" draggable="false" loading="lazy">';
    }
}
if ($imgBefore === '' && !empty($before['url'])) {
    $imgBefore = '<img class="veloria-before-after__img" src="' . esc_url($before['url']) . '" alt="' . esc_attr($before['alt'] ?? '') . '" draggable="false" loading="lazy">';
}

$imgAfter = '';
if (!empty($after['id'])) {
    $src = Group_Control_Image_Size::get_attachment_image_src((int) $after['id'], 'image_size', $s);
    if ($src) {
        $imgAfter = '<img class="veloria-before-after__img" src="' . esc_url($src) . '" alt="' . esc_attr($after['alt'] ?? '') . '" draggable="false" loading="lazy">';
    }
}
if ($imgAfter === '' && !empty($after['url'])) {
    $imgAfter = '<img class="veloria-before-after__img" src="' . esc_url($after['url']) . '" alt="' . esc_attr($after['alt'] ?? '') . '" draggable="false" loading="lazy">';
}

$iconHtml = '';
if (!empty($s['handle_icon'])) {
    ob_start();
    Icons_Manager::render_icon($s['handle_icon'], ['aria-hidden' => 'true']);
    $iconHtml = trim((string) ob_get_clean());
}
if ($iconHtml === '') {
    $iconHtml = $vertical
        ? '<i class="fas fa-arrows-alt-v" aria-hidden="true"></i>'
        : '<i class="fas fa-arrows-alt-h" aria-hidden="true"></i>';
}

$showLabels = !isset($s['show_labels']) || $s['show_labels'] === 'yes';
$lb         = isset($s['label_before']) ? (string) $s['label_before'] : __('Before', 'veloria');
$la         = isset($s['label_after']) ? (string) $s['label_after'] : __('After', 'veloria');

$rootClass    = 'veloria-before-after' . ($vertical ? ' veloria-before-after--vertical' : '');
$dataOrient   = $vertical ? 'vertical' : 'horizontal';
$clipAfter    = $vertical
    ? 'inset(' . $split . '% 0 0 0)'
    : 'inset(0 0 0 ' . $split . '%)';
$dividerStyle = $vertical
    ? 'top:' . $split . '%;'
    : 'left:' . $split . '%;';
?>
<div
    class="<?php echo esc_attr($rootClass); ?>"
    style="--ba-split: <?php echo esc_attr((string) $split); ?>%; --ba-split-num: <?php echo esc_attr((string) $split); ?>;"
    data-initial-split="<?php echo esc_attr((string) $split); ?>"
    data-ba-orientation="<?php echo esc_attr($dataOrient); ?>"
>
    <div class="veloria-before-after__frame">
        <div class="veloria-before-after__before">
            <?php echo $imgBefore ? wp_kses_post($imgBefore) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>
        <div class="veloria-before-after__after" style="clip-path: <?php echo esc_attr($clipAfter); ?>;">
            <?php echo $imgAfter ? wp_kses_post($imgAfter) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>
        <?php if ($showLabels && $lb !== '') : ?>
            <span class="veloria-before-after__label veloria-before-after__label--before"><?php echo esc_html($lb); ?></span>
        <?php endif; ?>
        <?php if ($showLabels && $la !== '') : ?>
            <span class="veloria-before-after__label veloria-before-after__label--after"><?php echo esc_html($la); ?></span>
        <?php endif; ?>
        <div class="veloria-before-after__divider" style="<?php echo esc_attr($dividerStyle); ?>">
            <span class="veloria-before-after__line" aria-hidden="true"></span>
            <button
                type="button"
                class="veloria-before-after__handle"
                aria-label="<?php echo esc_attr__('Drag to compare images', 'veloria'); ?>"
                aria-orientation="<?php echo esc_attr($dataOrient); ?>"
                aria-valuemin="0"
                aria-valuemax="100"
                aria-valuenow="<?php echo esc_attr((string) (int) round($split)); ?>"
                role="slider"
            >
                <span class="veloria-before-after__handle-icon"><?php echo $iconHtml; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
            </button>
        </div>
    </div>
</div>

<?php
    }
}
