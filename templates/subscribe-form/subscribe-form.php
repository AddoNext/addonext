<?php
defined('ABSPATH') || exit;

if (emt_fs()->can_use_premium_code__premium_only()) {
    if (emt_fs()->is_plan('pro')) {

$settings = $this->get_settings_for_display();
$title = isset($settings['title']) ? $settings['title'] : __('Subscribe to Newsletter', 'veloria');
$subtitle = isset($settings['subtitle']) ? $settings['subtitle'] : '';
$placeholder = isset($settings['placeholder']) ? $settings['placeholder'] : __('Enter your email', 'veloria');
$button_text = isset($settings['button_text']) ? $settings['button_text'] : __('Subscribe', 'veloria');
$list_id = isset($settings['list_id']) ? $settings['list_id'] : '';
$success_message = isset($settings['success_message']) ? $settings['success_message'] : __('Thank you for subscribing!', 'veloria');
$error_message = isset($settings['error_message']) ? $settings['error_message'] : __('Something went wrong. Please try again.', 'veloria');
$icon_html = '';
if (!empty($settings['icon']['value'])) {
    ob_start();
    \Elementor\Icons_Manager::render_icon($settings['icon'], ['aria-hidden' => 'true']);
    $icon_html = ob_get_clean();
}
?>
<div class="veloria-subscribe-form">
    <?php if ($icon_html) : ?>
        <div class="veloria-subscribe-form__icon"><?php echo $icon_html; ?></div>
    <?php endif; ?>
    <?php if ($title !== '') : ?>
        <h3 class="veloria-subscribe-form__title"><?php echo esc_html($title); ?></h3>
    <?php endif; ?>
    <?php if ($subtitle !== '') : ?>
        <p class="veloria-subscribe-form__subtitle"><?php echo esc_html($subtitle); ?></p>
    <?php endif; ?>
    <form class="veloria-subscribe-form__form"
          action=""
          method="post"
          data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"
          data-list-id="<?php echo esc_attr($list_id); ?>"
          data-success-msg="<?php echo esc_attr($success_message); ?>"
          data-error-msg="<?php echo esc_attr($error_message); ?>">
        <?php wp_nonce_field('veloria_subscribe_form', 'veloria_subscribe_nonce'); ?>
        <input type="email"
               name="email"
               class="veloria-subscribe-form__input"
               placeholder="<?php echo esc_attr($placeholder); ?>"
               required
               autocomplete="email">
        <button type="submit" class="veloria-subscribe-form__submit">
            <span class="veloria-subscribe-form__spinner" aria-hidden="true"></span>
            <span class="veloria-subscribe-form__submit-text"><?php echo esc_html($button_text); ?></span>
        </button>
    </form>
    <div class="veloria-subscribe-form__message" aria-live="polite"></div>
</div>

<?php
    }
}
