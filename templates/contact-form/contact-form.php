<?php
defined('ABSPATH') || exit;
$addonext_settings = $this->get_settings_for_display();
$addonext_fields = isset($addonext_settings['fields']) && is_array($addonext_settings['fields']) ? $addonext_settings['fields'] : [];
$addonext_recipient = isset($addonext_settings['recipient_email']) ? sanitize_email((string) $addonext_settings['recipient_email']) : sanitize_email((string) get_option('admin_email'));
$addonext_enable_recaptcha = !empty($addonext_settings['enable_recaptcha']) && $addonext_settings['enable_recaptcha'] === 'yes';
$addonext_submit_text = isset($addonext_settings['submit_text']) ? $addonext_settings['submit_text'] : __('Send Message', 'addonext');
$addonext_success_message = isset($addonext_settings['success_message']) ? $addonext_settings['success_message'] : __('Thank you for your message. We will get back to you soon.', 'addonext');
$addonext_error_message = isset($addonext_settings['error_message']) ? $addonext_settings['error_message'] : __('Something went wrong. Please try again.', 'addonext');

$addonext_fields_config = [];
foreach ($addonext_fields as $addonext_i => $addonext_f) {
    $addonext_label = isset($addonext_f['label']) ? $addonext_f['label'] : '';
    $addonext_placeholder = isset($addonext_f['placeholder']) ? $addonext_f['placeholder'] : '';
    $addonext_fields_config[] = [
        'name' => 'field_' . $addonext_i,
        'type' => isset($addonext_f['field_type']) ? $addonext_f['field_type'] : 'text',
        'label' => $addonext_label !== '' ? $addonext_label : ($addonext_placeholder !== '' ? $addonext_placeholder : 'field_' . $addonext_i),
    ];
}
$addonext_recaptcha_site_key = $addonext_enable_recaptcha ? \Addonext\Utils\OptionsHelper::get_option('recaptcha_site_key', '') : '';
?>
<form class="addonext-contact-form"
      action=""
      method="post"
      enctype="multipart/form-data"
      data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"
      data-recipient="<?php echo esc_attr($addonext_recipient); ?>"
      data-recaptcha="<?php echo $addonext_enable_recaptcha ? '1' : '0'; ?>"
      data-success-msg="<?php echo esc_attr($addonext_success_message); ?>"
      data-error-msg="<?php echo esc_attr($addonext_error_message); ?>"
      data-fields-config="<?php echo esc_attr(wp_json_encode($addonext_fields_config)); ?>"
      data-sitekey="<?php echo esc_attr($addonext_recaptcha_site_key); ?>">
    <input type="hidden" name="post_id" value="<?php echo esc_attr((string) get_the_ID()); ?>">
    <input type="hidden" name="element_id" value="<?php echo esc_attr($this->get_id()); ?>">
    <?php wp_nonce_field('addonext_contact_form', 'addonext_contact_nonce'); ?>
    <?php foreach ($addonext_fields as $addonext_i => $addonext_field) : ?>
        <?php
        $addonext_name = 'field_' . $addonext_i;
        $addonext_type = isset($addonext_field['field_type']) ? $addonext_field['field_type'] : 'text';
        $addonext_label = isset($addonext_field['label']) ? $addonext_field['label'] : '';
        $addonext_placeholder = isset($addonext_field['placeholder']) ? $addonext_field['placeholder'] : '';
        $addonext_required = !empty($addonext_field['required']) && $addonext_field['required'] === 'yes';
        $addonext_options = isset($addonext_field['options']) ? $addonext_field['options'] : '';
        $addonext_accept = isset($addonext_field['accept']) ? $addonext_field['accept'] : '';
        ?>
        <div class="addonext-contact-form__row">
            <?php if ($addonext_label !== '') : ?>
                <label class="addonext-contact-form__label" for="<?php echo esc_attr($addonext_name); ?>"><?php echo esc_html($addonext_label); ?></label>
            <?php endif; ?>
            <?php if ($addonext_type === 'textarea') : ?>
                <textarea id="<?php echo esc_attr($addonext_name); ?>"
                          class="addonext-contact-form__textarea"
                          name="<?php echo esc_attr($addonext_name); ?>"
                          placeholder="<?php echo esc_attr($addonext_placeholder); ?>"
                          <?php echo $addonext_required ? ' required' : ''; ?>
                          rows="4"></textarea>
            <?php elseif ($addonext_type === 'select') : ?>
                <?php
                $addonext_opts = array_filter(array_map('trim', preg_split('/[\r\n]+/', $addonext_options)));
                ?>
                <select id="<?php echo esc_attr($addonext_name); ?>"
                        class="addonext-contact-form__select"
                        name="<?php echo esc_attr($addonext_name); ?>"
                        <?php echo $addonext_required ? ' required' : ''; ?>>
                    <option value=""><?php echo esc_html__('— Select —', 'addonext'); ?></option>
                    <?php foreach ($addonext_opts as $addonext_opt) : ?>
                        <option value="<?php echo esc_attr($addonext_opt); ?>"><?php echo esc_html($addonext_opt); ?></option>
                    <?php endforeach; ?>
                </select>
            <?php elseif ($addonext_type === 'file') : ?>
                <input type="file"
                       id="<?php echo esc_attr($addonext_name); ?>"
                       class="addonext-contact-form__input addonext-contact-form__input--file"
                       name="<?php echo esc_attr($addonext_name); ?>"
                       <?php echo $addonext_accept ? ' accept="' . esc_attr($addonext_accept) . '"' : ''; ?>
                       <?php echo $addonext_required ? ' required' : ''; ?>>
            <?php else : ?>
                <input type="<?php echo esc_attr($addonext_type); ?>"
                       id="<?php echo esc_attr($addonext_name); ?>"
                       class="addonext-contact-form__input"
                       name="<?php echo esc_attr($addonext_name); ?>"
                       placeholder="<?php echo esc_attr($addonext_placeholder); ?>"
                       <?php echo $addonext_required ? ' required' : ''; ?>>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    <?php if ($addonext_enable_recaptcha && $addonext_recaptcha_site_key !== '') : ?>
        <div class="addonext-contact-form__row addonext-contact-form__recaptcha" data-sitekey="<?php echo esc_attr($addonext_recaptcha_site_key); ?>"></div>
    <?php endif; ?>
    <div class="addonext-contact-form__row addonext-contact-form__row--submit">
        <button type="submit" class="addonext-contact-form__submit"><span class="addonext-contact-form__spinner" aria-hidden="true"></span><span class="addonext-contact-form__submit-text"><?php echo esc_html($addonext_submit_text); ?></span></button>
    </div>
    <div class="addonext-contact-form__message" aria-live="polite"></div>
</form>
