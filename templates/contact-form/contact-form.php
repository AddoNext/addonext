<?php
defined('ABSPATH') || exit;
$settings = $this->get_settings_for_display();
$fields = isset($settings['fields']) && is_array($settings['fields']) ? $settings['fields'] : [];
$recipient = isset($settings['recipient_email']) ? $settings['recipient_email'] : get_option('admin_email');
$enable_recaptcha = !empty($settings['enable_recaptcha']) && $settings['enable_recaptcha'] === 'yes';
$submit_text = isset($settings['submit_text']) ? $settings['submit_text'] : __('Send Message', 'elementrify');
$success_message = isset($settings['success_message']) ? $settings['success_message'] : __('Thank you for your message. We will get back to you soon.', 'elementrify');
$error_message = isset($settings['error_message']) ? $settings['error_message'] : __('Something went wrong. Please try again.', 'elementrify');

$fields_config = [];
foreach ($fields as $i => $f) {
    $label = isset($f['label']) ? $f['label'] : '';
    $placeholder = isset($f['placeholder']) ? $f['placeholder'] : '';
    $fields_config[] = [
        'name' => 'field_' . $i,
        'type' => isset($f['field_type']) ? $f['field_type'] : 'text',
        'label' => $label !== '' ? $label : ($placeholder !== '' ? $placeholder : 'field_' . $i),
    ];
}
$recaptcha_site_key = $enable_recaptcha ? \Elementrify\Utils\OptionsHelper::get_option('recaptcha_site_key', '') : '';
?>
<form class="elementrify-contact-form"
      action=""
      method="post"
      enctype="multipart/form-data"
      data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"
      data-recipient="<?php echo esc_attr($recipient); ?>"
      data-recaptcha="<?php echo $enable_recaptcha ? '1' : '0'; ?>"
      data-success-msg="<?php echo esc_attr($success_message); ?>"
      data-error-msg="<?php echo esc_attr($error_message); ?>"
      data-fields-config="<?php echo esc_attr(wp_json_encode($fields_config)); ?>"
      data-sitekey="<?php echo esc_attr($recaptcha_site_key); ?>">
    <?php wp_nonce_field('elementrify_contact_form', 'elementrify_contact_nonce'); ?>
    <?php foreach ($fields as $i => $field) : ?>
        <?php
        $name = 'field_' . $i;
        $type = isset($field['field_type']) ? $field['field_type'] : 'text';
        $label = isset($field['label']) ? $field['label'] : '';
        $placeholder = isset($field['placeholder']) ? $field['placeholder'] : '';
        $required = !empty($field['required']) && $field['required'] === 'yes';
        $options = isset($field['options']) ? $field['options'] : '';
        $accept = isset($field['accept']) ? $field['accept'] : '';
        ?>
        <div class="elementrify-contact-form__row">
            <?php if ($label !== '') : ?>
                <label class="elementrify-contact-form__label" for="<?php echo esc_attr($name); ?>"><?php echo esc_html($label); ?></label>
            <?php endif; ?>
            <?php if ($type === 'textarea') : ?>
                <textarea id="<?php echo esc_attr($name); ?>"
                          class="elementrify-contact-form__textarea"
                          name="<?php echo esc_attr($name); ?>"
                          placeholder="<?php echo esc_attr($placeholder); ?>"
                          <?php echo $required ? ' required' : ''; ?>
                          rows="4"></textarea>
            <?php elseif ($type === 'select') : ?>
                <?php
                $opts = array_filter(array_map('trim', preg_split('/[\r\n]+/', $options)));
                ?>
                <select id="<?php echo esc_attr($name); ?>"
                        class="elementrify-contact-form__select"
                        name="<?php echo esc_attr($name); ?>"
                        <?php echo $required ? ' required' : ''; ?>>
                    <option value=""><?php echo esc_html__('— Select —', 'elementrify'); ?></option>
                    <?php foreach ($opts as $opt) : ?>
                        <option value="<?php echo esc_attr($opt); ?>"><?php echo esc_html($opt); ?></option>
                    <?php endforeach; ?>
                </select>
            <?php elseif ($type === 'file') : ?>
                <input type="file"
                       id="<?php echo esc_attr($name); ?>"
                       class="elementrify-contact-form__input elementrify-contact-form__input--file"
                       name="<?php echo esc_attr($name); ?>"
                       <?php echo $accept ? ' accept="' . esc_attr($accept) . '"' : ''; ?>
                       <?php echo $required ? ' required' : ''; ?>>
            <?php else : ?>
                <input type="<?php echo esc_attr($type); ?>"
                       id="<?php echo esc_attr($name); ?>"
                       class="elementrify-contact-form__input"
                       name="<?php echo esc_attr($name); ?>"
                       placeholder="<?php echo esc_attr($placeholder); ?>"
                       <?php echo $required ? ' required' : ''; ?>>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    <?php if ($enable_recaptcha && $recaptcha_site_key !== '') : ?>
        <div class="elementrify-contact-form__row elementrify-contact-form__recaptcha" data-sitekey="<?php echo esc_attr($recaptcha_site_key); ?>"></div>
    <?php endif; ?>
    <div class="elementrify-contact-form__row elementrify-contact-form__row--submit">
        <button type="submit" class="elementrify-contact-form__submit"><span class="elementrify-contact-form__spinner" aria-hidden="true"></span><span class="elementrify-contact-form__submit-text"><?php echo esc_html($submit_text); ?></span></button>
    </div>
    <div class="elementrify-contact-form__message" aria-live="polite"></div>
</form>
