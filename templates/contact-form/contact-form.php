<?php
defined('ABSPATH') || exit;
$veloria_settings = $this->get_settings_for_display();
$veloria_fields = isset($veloria_settings['fields']) && is_array($veloria_settings['fields']) ? $veloria_settings['fields'] : [];
$veloria_recipient = isset($veloria_settings['recipient_email']) ? sanitize_email((string) $veloria_settings['recipient_email']) : sanitize_email((string) get_option('admin_email'));
$veloria_enable_recaptcha = !empty($veloria_settings['enable_recaptcha']) && $veloria_settings['enable_recaptcha'] === 'yes';
$veloria_submit_text = isset($veloria_settings['submit_text']) ? $veloria_settings['submit_text'] : __('Send Message', 'veloria');
$veloria_success_message = isset($veloria_settings['success_message']) ? $veloria_settings['success_message'] : __('Thank you for your message. We will get back to you soon.', 'veloria');
$veloria_error_message = isset($veloria_settings['error_message']) ? $veloria_settings['error_message'] : __('Something went wrong. Please try again.', 'veloria');

$veloria_fields_config = [];
foreach ($veloria_fields as $veloria_i => $veloria_f) {
    $veloria_label = isset($veloria_f['label']) ? $veloria_f['label'] : '';
    $veloria_placeholder = isset($veloria_f['placeholder']) ? $veloria_f['placeholder'] : '';
    $veloria_fields_config[] = [
        'name' => 'field_' . $veloria_i,
        'type' => isset($veloria_f['field_type']) ? $veloria_f['field_type'] : 'text',
        'label' => $veloria_label !== '' ? $veloria_label : ($veloria_placeholder !== '' ? $veloria_placeholder : 'field_' . $veloria_i),
    ];
}
$veloria_recaptcha_site_key = $veloria_enable_recaptcha ? \Veloria\Utils\OptionsHelper::get_option('recaptcha_site_key', '') : '';
?>
<form class="veloria-contact-form"
      action=""
      method="post"
      enctype="multipart/form-data"
      data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"
      data-recipient="<?php echo esc_attr($veloria_recipient); ?>"
      data-recaptcha="<?php echo $veloria_enable_recaptcha ? '1' : '0'; ?>"
      data-success-msg="<?php echo esc_attr($veloria_success_message); ?>"
      data-error-msg="<?php echo esc_attr($veloria_error_message); ?>"
      data-fields-config="<?php echo esc_attr(wp_json_encode($veloria_fields_config)); ?>"
      data-sitekey="<?php echo esc_attr($veloria_recaptcha_site_key); ?>">
    <input type="hidden" name="post_id" value="<?php echo esc_attr((string) get_the_ID()); ?>">
    <input type="hidden" name="element_id" value="<?php echo esc_attr($this->get_id()); ?>">
    <?php wp_nonce_field('veloria_contact_form', 'veloria_contact_nonce'); ?>
    <?php foreach ($veloria_fields as $veloria_i => $veloria_field) : ?>
        <?php
        $veloria_name = 'field_' . $veloria_i;
        $veloria_type = isset($veloria_field['field_type']) ? $veloria_field['field_type'] : 'text';
        $veloria_label = isset($veloria_field['label']) ? $veloria_field['label'] : '';
        $veloria_placeholder = isset($veloria_field['placeholder']) ? $veloria_field['placeholder'] : '';
        $veloria_required = !empty($veloria_field['required']) && $veloria_field['required'] === 'yes';
        $veloria_options = isset($veloria_field['options']) ? $veloria_field['options'] : '';
        $veloria_accept = isset($veloria_field['accept']) ? $veloria_field['accept'] : '';
        ?>
        <div class="veloria-contact-form__row">
            <?php if ($veloria_label !== '') : ?>
                <label class="veloria-contact-form__label" for="<?php echo esc_attr($veloria_name); ?>"><?php echo esc_html($veloria_label); ?></label>
            <?php endif; ?>
            <?php if ($veloria_type === 'textarea') : ?>
                <textarea id="<?php echo esc_attr($veloria_name); ?>"
                          class="veloria-contact-form__textarea"
                          name="<?php echo esc_attr($veloria_name); ?>"
                          placeholder="<?php echo esc_attr($veloria_placeholder); ?>"
                          <?php echo $veloria_required ? ' required' : ''; ?>
                          rows="4"></textarea>
            <?php elseif ($veloria_type === 'select') : ?>
                <?php
                $veloria_opts = array_filter(array_map('trim', preg_split('/[\r\n]+/', $veloria_options)));
                ?>
                <select id="<?php echo esc_attr($veloria_name); ?>"
                        class="veloria-contact-form__select"
                        name="<?php echo esc_attr($veloria_name); ?>"
                        <?php echo $veloria_required ? ' required' : ''; ?>>
                    <option value=""><?php echo esc_html__('— Select —', 'veloria'); ?></option>
                    <?php foreach ($veloria_opts as $veloria_opt) : ?>
                        <option value="<?php echo esc_attr($veloria_opt); ?>"><?php echo esc_html($veloria_opt); ?></option>
                    <?php endforeach; ?>
                </select>
            <?php elseif ($veloria_type === 'file') : ?>
                <input type="file"
                       id="<?php echo esc_attr($veloria_name); ?>"
                       class="veloria-contact-form__input veloria-contact-form__input--file"
                       name="<?php echo esc_attr($veloria_name); ?>"
                       <?php echo $veloria_accept ? ' accept="' . esc_attr($veloria_accept) . '"' : ''; ?>
                       <?php echo $veloria_required ? ' required' : ''; ?>>
            <?php else : ?>
                <input type="<?php echo esc_attr($veloria_type); ?>"
                       id="<?php echo esc_attr($veloria_name); ?>"
                       class="veloria-contact-form__input"
                       name="<?php echo esc_attr($veloria_name); ?>"
                       placeholder="<?php echo esc_attr($veloria_placeholder); ?>"
                       <?php echo $veloria_required ? ' required' : ''; ?>>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    <?php if ($veloria_enable_recaptcha && $veloria_recaptcha_site_key !== '') : ?>
        <div class="veloria-contact-form__row veloria-contact-form__recaptcha" data-sitekey="<?php echo esc_attr($veloria_recaptcha_site_key); ?>"></div>
    <?php endif; ?>
    <div class="veloria-contact-form__row veloria-contact-form__row--submit">
        <button type="submit" class="veloria-contact-form__submit"><span class="veloria-contact-form__spinner" aria-hidden="true"></span><span class="veloria-contact-form__submit-text"><?php echo esc_html($veloria_submit_text); ?></span></button>
    </div>
    <div class="veloria-contact-form__message" aria-live="polite"></div>
</form>
