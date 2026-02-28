<?php
defined('ABSPATH') || exit;

$settings = $this->get_settings_for_display();
$overlay_class = 'elementrify-modal__overlay';
if (!empty($settings['overlay_blur']) && $settings['overlay_blur'] === 'yes') {
    $overlay_class .= ' is-blur';
}
$this->add_render_attribute('overlay', 'class', $overlay_class);
$this->add_render_attribute('overlay', 'data-countdown', (string) (isset($settings['countdown_seconds']) ? (int) $settings['countdown_seconds'] : 0));

$button_variant = isset($settings['button_variant']) ? $settings['button_variant'] : 'primary';
$button_size   = isset($settings['button_size']) ? $settings['button_size'] : 'md';
$trigger_class = 'elementrify-button elementrify-button--' . esc_attr($button_variant) . ' elementrify-button--' . esc_attr($button_size) . ' elementrify-modal__trigger';
?>
<div class="elementrify-modal">
    <div class="elementrify-button-wrapper">
        <button type="button" class="<?php echo esc_attr($trigger_class); ?>" role="button">
            <span class="elementrify-button__content">
                <span class="elementrify-button__text"><?php echo esc_html($settings['trigger_text'] ?? ''); ?></span>
            </span>
        </button>
    </div>
    <div <?php $this->print_render_attribute_string('overlay'); ?>>
        <div class="elementrify-modal__content" role="dialog" aria-modal="true" aria-labelledby="elementrify-modal-title-<?php echo esc_attr($this->get_id()); ?>">
            <button type="button" class="elementrify-modal__close" aria-label="<?php echo esc_attr__('Close', 'elementrify'); ?>"><span class="elementrify-modal__close-icon" aria-hidden="true">&times;</span></button>
            <?php if (!empty($settings['popup_title'])) : ?>
                <h3 class="elementrify-modal__title" id="elementrify-modal-title-<?php echo esc_attr($this->get_id()); ?>"><?php echo esc_html($settings['popup_title']); ?></h3>
            <?php endif; ?>
            <?php if (!empty($settings['popup_image']['url'])) : ?>
                <div class="elementrify-modal__image">
                    <img src="<?php echo esc_url($settings['popup_image']['url']); ?>" alt="<?php echo esc_attr($settings['popup_image']['alt'] ?? ''); ?>">
                </div>
            <?php endif; ?>
            <?php if (!empty($settings['popup_content'])) : ?>
                <div class="elementrify-modal__body"><?php echo wp_kses_post(nl2br($settings['popup_content'])); ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>
