<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$veloria_settings = $this->get_settings_for_display();
$veloria_title = isset($veloria_settings['title']) ? $veloria_settings['title'] : __('Subscribe to Newsletter', 'veloria');
$veloria_subtitle = isset($veloria_settings['subtitle']) ? $veloria_settings['subtitle'] : '';
$veloria_placeholder = isset($veloria_settings['placeholder']) ? $veloria_settings['placeholder'] : __('Enter your email', 'veloria');
$veloria_button_text = isset($veloria_settings['button_text']) ? $veloria_settings['button_text'] : __('Subscribe', 'veloria');
$veloria_list_id = isset($veloria_settings['list_id']) ? $veloria_settings['list_id'] : '';
$veloria_success_message = isset($veloria_settings['success_message']) ? $veloria_settings['success_message'] : __('Thank you for subscribing!', 'veloria');
$veloria_error_message = isset($veloria_settings['error_message']) ? $veloria_settings['error_message'] : __('Something went wrong. Please try again.', 'veloria');
$veloria_icon_html = '';
if (!empty($veloria_settings['icon']['value'])) {
    ob_start();
    \Elementor\Icons_Manager::render_icon($veloria_settings['icon'], ['aria-hidden' => 'true']);
    $veloria_icon_html = ob_get_clean();
}
?>
<div class="veloria-subscribe-form">
    <?php if ($veloria_icon_html) : ?>
        <div class="veloria-subscribe-form__icon"><?php echo $veloria_icon_html; ?></div>
    <?php endif; ?>
    <?php if ($veloria_title !== '') : ?>
        <h3 class="veloria-subscribe-form__title"><?php echo esc_html($veloria_title); ?></h3>
    <?php endif; ?>
    <?php if ($veloria_subtitle !== '') : ?>
        <p class="veloria-subscribe-form__subtitle"><?php echo esc_html($veloria_subtitle); ?></p>
    <?php endif; ?>
    <form class="veloria-subscribe-form__form"
          action=""
          method="post"
          data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"
          data-list-id="<?php echo esc_attr($veloria_list_id); ?>"
          data-success-msg="<?php echo esc_attr($veloria_success_message); ?>"
          data-error-msg="<?php echo esc_attr($veloria_error_message); ?>">
        <?php wp_nonce_field('veloria_subscribe_form', 'veloria_subscribe_nonce'); ?>
        <input type="email"
               name="email"
               class="veloria-subscribe-form__input"
               placeholder="<?php echo esc_attr($veloria_placeholder); ?>"
               required
               autocomplete="email">
        <button type="submit" class="veloria-subscribe-form__submit">
            <span class="veloria-subscribe-form__spinner" aria-hidden="true"></span>
            <span class="veloria-subscribe-form__submit-text"><?php echo esc_html($veloria_button_text); ?></span>
        </button>
    </form>
    <div class="veloria-subscribe-form__message" aria-live="polite"></div>
</div>

<?php
    }
}
