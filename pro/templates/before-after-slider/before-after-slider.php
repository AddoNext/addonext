<?php
defined('ABSPATH') || exit;

use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$veloria_s        = $this->get_settings_for_display();
$veloria_split    = isset($veloria_s['initial_position']['size']) ? (float) $veloria_s['initial_position']['size'] : 50.0;
$veloria_split    = max(0.0, min(100.0, $veloria_split));
$veloria_vertical = isset($veloria_s['slide_direction']) && $veloria_s['slide_direction'] === 'vertical';

$veloria_before = $veloria_s['image_before'] ?? [];
$veloria_after  = $veloria_s['image_after'] ?? [];

$veloria_imgBefore = '';
if (!empty($veloria_before['id'])) {
    $veloria_src = Group_Control_Image_Size::get_attachment_image_src((int) $veloria_before['id'], 'image_size', $veloria_s);
    if ($veloria_src) {
        $veloria_imgBefore = '<img class="veloria-before-after__img" src="' . esc_url($veloria_src) . '" alt="' . esc_attr($veloria_before['alt'] ?? '') . '" draggable="false" loading="lazy">';
    }
}
if ($veloria_imgBefore === '' && !empty($veloria_before['url'])) {
    $veloria_imgBefore = '<img class="veloria-before-after__img" src="' . esc_url($veloria_before['url']) . '" alt="' . esc_attr($veloria_before['alt'] ?? '') . '" draggable="false" loading="lazy">';
}

$veloria_imgAfter = '';
if (!empty($veloria_after['id'])) {
    $veloria_src = Group_Control_Image_Size::get_attachment_image_src((int) $veloria_after['id'], 'image_size', $veloria_s);
    if ($veloria_src) {
        $veloria_imgAfter = '<img class="veloria-before-after__img" src="' . esc_url($veloria_src) . '" alt="' . esc_attr($veloria_after['alt'] ?? '') . '" draggable="false" loading="lazy">';
    }
}
if ($veloria_imgAfter === '' && !empty($veloria_after['url'])) {
    $veloria_imgAfter = '<img class="veloria-before-after__img" src="' . esc_url($veloria_after['url']) . '" alt="' . esc_attr($veloria_after['alt'] ?? '') . '" draggable="false" loading="lazy">';
}

$veloria_iconHtml = '';
if (!empty($veloria_s['handle_icon'])) {
    ob_start();
    Icons_Manager::render_icon($veloria_s['handle_icon'], ['aria-hidden' => 'true']);
    $veloria_iconHtml = trim((string) ob_get_clean());
}
if ($veloria_iconHtml === '') {
    $veloria_iconHtml = $veloria_vertical
        ? '<i class="fas fa-arrows-alt-v" aria-hidden="true"></i>'
        : '<i class="fas fa-arrows-alt-h" aria-hidden="true"></i>';
}

$veloria_showLabels = !isset($veloria_s['show_labels']) || $veloria_s['show_labels'] === 'yes';
$veloria_lb         = isset($veloria_s['label_before']) ? (string) $veloria_s['label_before'] : __('Before', 'veloria');
$veloria_la         = isset($veloria_s['label_after']) ? (string) $veloria_s['label_after'] : __('After', 'veloria');

$veloria_rootClass    = 'veloria-before-after' . ($veloria_vertical ? ' veloria-before-after--vertical' : '');
$veloria_dataOrient   = $veloria_vertical ? 'vertical' : 'horizontal';
$veloria_clipAfter    = $veloria_vertical
    ? 'inset(' . $veloria_split . '% 0 0 0)'
    : 'inset(0 0 0 ' . $veloria_split . '%)';
$veloria_dividerStyle = $veloria_vertical
    ? 'top:' . $veloria_split . '%;'
    : 'left:' . $veloria_split . '%;';
?>
<div
    class="<?php echo esc_attr($veloria_rootClass); ?>"
    style="--ba-split: <?php echo esc_attr((string) $veloria_split); ?>%; --ba-split-num: <?php echo esc_attr((string) $veloria_split); ?>;"
    data-initial-split="<?php echo esc_attr((string) $veloria_split); ?>"
    data-ba-orientation="<?php echo esc_attr($veloria_dataOrient); ?>"
>
    <div class="veloria-before-after__frame">
        <div class="veloria-before-after__before">
            <?php echo $veloria_imgBefore ? wp_kses_post($veloria_imgBefore) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>
        <div class="veloria-before-after__after" style="clip-path: <?php echo esc_attr($veloria_clipAfter); ?>;">
            <?php echo $veloria_imgAfter ? wp_kses_post($veloria_imgAfter) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>
        <?php if ($veloria_showLabels && $veloria_lb !== '') : ?>
            <span class="veloria-before-after__label veloria-before-after__label--before"><?php echo esc_html($veloria_lb); ?></span>
        <?php endif; ?>
        <?php if ($veloria_showLabels && $veloria_la !== '') : ?>
            <span class="veloria-before-after__label veloria-before-after__label--after"><?php echo esc_html($veloria_la); ?></span>
        <?php endif; ?>
        <div class="veloria-before-after__divider" style="<?php echo esc_attr($veloria_dividerStyle); ?>">
            <span class="veloria-before-after__line" aria-hidden="true"></span>
            <button
                type="button"
                class="veloria-before-after__handle"
                aria-label="<?php echo esc_attr__('Drag to compare images', 'veloria'); ?>"
                aria-orientation="<?php echo esc_attr($veloria_dataOrient); ?>"
                aria-valuemin="0"
                aria-valuemax="100"
                aria-valuenow="<?php echo esc_attr((string) (int) round($veloria_split)); ?>"
                role="slider"
            >
                <span class="veloria-before-after__handle-icon"><?php echo $veloria_iconHtml; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
            </button>
        </div>
    </div>
</div>

<?php
    }
}
