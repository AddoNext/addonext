<?php
defined('ABSPATH') || exit;

$addonext_settings = $this->get_settings_for_display();
$addonext_overlay_class = 'addonext-modal__overlay';
if (!empty($addonext_settings['overlay_blur']) && $addonext_settings['overlay_blur'] === 'yes') {
    $addonext_overlay_class .= ' is-blur';
}
$this->add_render_attribute('overlay', 'class', $addonext_overlay_class);
$this->add_render_attribute('overlay', 'data-countdown', (string) (isset($addonext_settings['countdown_seconds']) ? (int) $addonext_settings['countdown_seconds'] : 0));

$addonext_button_variant = isset($addonext_settings['button_variant']) ? $addonext_settings['button_variant'] : 'primary';
$addonext_button_size   = isset($addonext_settings['button_size']) ? $addonext_settings['button_size'] : 'md';
$addonext_trigger_class = 'addonext-button addonext-button--' . esc_attr($addonext_button_variant) . ' addonext-button--' . esc_attr($addonext_button_size) . ' addonext-modal__trigger';
?>
<div class="addonext-modal">
    <div class="addonext-button-wrapper">
        <button type="button" class="<?php echo esc_attr($addonext_trigger_class); ?>" role="button">
            <span class="addonext-button__content">
                <span class="addonext-button__text"><?php echo esc_html($addonext_settings['trigger_text'] ?? ''); ?></span>
            </span>
        </button>
    </div>
    <div <?php $this->print_render_attribute_string('overlay'); ?>>
        <div class="addonext-modal__content" role="dialog" aria-modal="true" aria-labelledby="addonext-modal-title-<?php echo esc_attr($this->get_id()); ?>">
            <button type="button" class="addonext-modal__close" aria-label="<?php echo esc_attr__('Close', 'addonext'); ?>"><span class="addonext-modal__close-icon" aria-hidden="true">&times;</span></button>
            <?php if (!empty($addonext_settings['popup_title'])) : ?>
                <h3 class="addonext-modal__title" id="addonext-modal-title-<?php echo esc_attr($this->get_id()); ?>"><?php echo esc_html($addonext_settings['popup_title']); ?></h3>
            <?php endif; ?>
            <?php if (!empty($addonext_settings['popup_image']['url'])) : ?>
                <div class="addonext-modal__image">
                    <img src="<?php echo esc_url($addonext_settings['popup_image']['url']); ?>" alt="<?php echo esc_attr($addonext_settings['popup_image']['alt'] ?? ''); ?>">
                </div>
            <?php endif; ?>
            <?php if (!empty($addonext_settings['popup_content'])) : ?>
                <div class="addonext-modal__body"><?php echo wp_kses_post(nl2br($addonext_settings['popup_content'])); ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>
