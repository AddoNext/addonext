<?php
defined('ABSPATH') || exit;

if (addonext_fs()->can_use_premium_code__premium_only()) {
    if (addonext_fs()->is_plan('pro')) {

$addonext_settings = $this->get_settings_for_display();
$addonext_title = isset($addonext_settings['title']) ? $addonext_settings['title'] : __('Subscribe to Newsletter', 'addonext');
$addonext_subtitle = isset($addonext_settings['subtitle']) ? $addonext_settings['subtitle'] : '';
$addonext_placeholder = isset($addonext_settings['placeholder']) ? $addonext_settings['placeholder'] : __('Enter your email', 'addonext');
$addonext_button_text = isset($addonext_settings['button_text']) ? $addonext_settings['button_text'] : __('Subscribe', 'addonext');
$addonext_list_id = isset($addonext_settings['list_id']) ? $addonext_settings['list_id'] : '';
$addonext_success_message = isset($addonext_settings['success_message']) ? $addonext_settings['success_message'] : __('Thank you for subscribing!', 'addonext');
$addonext_error_message = isset($addonext_settings['error_message']) ? $addonext_settings['error_message'] : __('Something went wrong. Please try again.', 'addonext');
$addonext_icon_html = '';
if (!empty($addonext_settings['icon']['value'])) {
    ob_start();
    \Elementor\Icons_Manager::render_icon($addonext_settings['icon'], ['aria-hidden' => 'true']);
    $addonext_icon_html = ob_get_clean();
}
?>
<div class="addonext-subscribe-form">
    <?php if ($addonext_icon_html) : ?>
        <div class="addonext-subscribe-form__icon"><?php echo $addonext_icon_html; ?></div>
    <?php endif; ?>
    <?php if ($addonext_title !== '') : ?>
        <h3 class="addonext-subscribe-form__title"><?php echo esc_html($addonext_title); ?></h3>
    <?php endif; ?>
    <?php if ($addonext_subtitle !== '') : ?>
        <p class="addonext-subscribe-form__subtitle"><?php echo esc_html($addonext_subtitle); ?></p>
    <?php endif; ?>
    <form class="addonext-subscribe-form__form"
          action=""
          method="post"
          data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"
          data-list-id="<?php echo esc_attr($addonext_list_id); ?>"
          data-success-msg="<?php echo esc_attr($addonext_success_message); ?>"
          data-error-msg="<?php echo esc_attr($addonext_error_message); ?>">
        <?php wp_nonce_field('addonext_subscribe_form', 'addonext_subscribe_nonce'); ?>
        <input type="email"
               name="email"
               class="addonext-subscribe-form__input"
               placeholder="<?php echo esc_attr($addonext_placeholder); ?>"
               required
               autocomplete="email">
        <button type="submit" class="addonext-subscribe-form__submit">
            <span class="addonext-subscribe-form__spinner" aria-hidden="true"></span>
            <span class="addonext-subscribe-form__submit-text"><?php echo esc_html($addonext_button_text); ?></span>
        </button>
    </form>
    <div class="addonext-subscribe-form__message" aria-live="polite"></div>
</div>

<?php
    }
}
