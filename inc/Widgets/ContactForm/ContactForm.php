<?php
namespace Veloria\Widgets\ContactForm;

use Veloria\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Veloria\Utils\OptionsHelper;

defined('ABSPATH') or exit;

class ContactForm extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
    }

    public static function register_ajax_handlers(): void
    {
        add_action('wp_ajax_veloria_contact_form', [self::class, 'ajax_submit']);
        add_action('wp_ajax_nopriv_veloria_contact_form', [self::class, 'ajax_submit']);
    }

    public function get_icon(): string
    {
        return 'eicon-form-horizontal veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-contact-form';
    }

    public function get_admin_category(): string
    {
        return 'marketing';
    }

    public function get_title(): string
    {
        return __('Contact Form', 'veloria');
    }

    public function get_description(): string
    {
        return __('Advanced form builder', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#2563eb';
    }

    public function get_style_depends(): array
    {
        return ['veloria-contact-form'];
    }

    public function get_script_depends(): array
    {
        $depends = ['veloria-contact-form'];
        return $depends;
    }

    public function register_controls(): void
    {
        $this->start_controls_section(
            'section_form',
            [
                'label' => __('Form', 'veloria'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();
        $repeater->add_control(
            'field_type',
            [
                'label'   => __('Type', 'veloria'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'text',
                'options' => [
                    'text'     => __('Text', 'veloria'),
                    'email'    => __('Email', 'veloria'),
                    'textarea' => __('Textarea', 'veloria'),
                    'password' => __('Password', 'veloria'),
                    'select'   => __('Dropdown', 'veloria'),
                    'file'     => __('File', 'veloria'),
                ],
            ]
        );
        $repeater->add_control(
            'label',
            [
                'label'   => __('Label', 'veloria'),
                'type'    => Controls_Manager::TEXT,
                'default' => '',
            ]
        );
        $repeater->add_control(
            'placeholder',
            [
                'label'   => __('Placeholder', 'veloria'),
                'type'    => Controls_Manager::TEXT,
                'default' => '',
            ]
        );
        $repeater->add_control(
            'required',
            [
                'label'        => __('Required', 'veloria'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => '',
            ]
        );
        $repeater->add_control(
            'options',
            [
                'label'     => __('Options (one per line)', 'veloria'),
                'type'      => Controls_Manager::TEXTAREA,
                'default'   => '',
                'condition' => ['field_type' => 'select'],
            ]
        );
        $repeater->add_control(
            'accept',
            [
                'label'     => __('Accept (e.g. .pdf,.jpg)', 'veloria'),
                'type'      => Controls_Manager::TEXT,
                'default'   => '',
                'condition' => ['field_type' => 'file'],
            ]
        );

        $default_fields = [
            ['field_type' => 'text', 'placeholder' => __('Your Name', 'veloria'), 'required' => 'yes', 'label' => ''],
            ['field_type' => 'email', 'placeholder' => __('Email Address', 'veloria'), 'required' => 'yes', 'label' => ''],
            ['field_type' => 'textarea', 'placeholder' => __('Your Message', 'veloria'), 'required' => '', 'label' => ''],
        ];

        $this->add_control(
            'fields',
            [
                'label'       => __('Fields', 'veloria'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => $default_fields,
                'title_field' => '{{{ placeholder || label || field_type }}}',
            ]
        );

        $this->add_control(
            'recipient_email',
            [
                'label'       => __('Recipient email', 'veloria'),
                'type'        => Controls_Manager::TEXT,
                'default'     => get_option('admin_email'),
                'placeholder' => get_option('admin_email'),
                'description' => __('Where the form submissions will be sent.', 'veloria'),
            ]
        );

        $this->add_control(
            'enable_recaptcha',
            [
                'label'        => __('Enable reCAPTCHA', 'veloria'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => '',
                'description'  => __('Requires Site Key and Secret Key in Veloria → Settings → API Keys.', 'veloria'),
            ]
        );

        $this->add_control(
            'submit_text',
            [
                'label'   => __('Submit button text', 'veloria'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Send Message', 'veloria'),
            ]
        );

        $this->add_control(
            'success_message',
            [
                'label'   => __('Success message', 'veloria'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Thank you for your message. We will get back to you soon.', 'veloria'),
            ]
        );

        $this->add_control(
            'error_message',
            [
                'label'   => __('Error message', 'veloria'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Something went wrong. Please try again.', 'veloria'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_form',
            [
                'label' => __('Form', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'label_typography',
                'label'          => __('Label', 'veloria'),
                'selector'       => '{{WRAPPER}} .veloria-contact-form__label',
                'fields_options' => [
                    'typography'  => ['default' => 'custom'],
                    'font_size'   => ['default' => ['unit' => 'px', 'size' => 14]],
                    'font_weight' => ['default' => '500'],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'input_typography',
                'label'          => __('Input / Textarea', 'veloria'),
                'selector'       => '{{WRAPPER}} .veloria-contact-form__input, {{WRAPPER}} .veloria-contact-form__textarea, {{WRAPPER}} .veloria-contact-form__select',
                'fields_options' => [
                    'typography'  => ['default' => 'custom'],
                    'font_size'   => ['default' => ['unit' => 'px', 'size' => 14]],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'placeholder_typography',
                'label'          => __('Placeholder', 'veloria'),
                'selector'       => '{{WRAPPER}} .veloria-contact-form__input::placeholder, {{WRAPPER}} .veloria-contact-form__textarea::placeholder',
                'fields_options' => [
                    'typography'  => ['default' => 'custom'],
                    'font_size'   => ['default' => ['unit' => 'px', 'size' => 14]],
                ],
            ]
        );

        $this->add_control(
            'input_bg',
            [
                'label'     => __('Input background', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .veloria-contact-form__input' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .veloria-contact-form__textarea' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .veloria-contact-form__select' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_border_color',
            [
                'label'     => __('Input border color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#d1d5db',
                'selectors' => [
                    '{{WRAPPER}} .veloria-contact-form__input' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .veloria-contact-form__textarea' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .veloria-contact-form__select' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_text_color',
            [
                'label'     => __('Input text color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#111827',
                'selectors' => [
                    '{{WRAPPER}} .veloria-contact-form__input' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .veloria-contact-form__textarea' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .veloria-contact-form__select' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_button',
            [
                'label' => __('Button', 'veloria'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} .veloria-contact-form__submit',
            ]
        );

        $this->add_control(
            'button_bg',
            [
                'label'     => __('Background', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#2563eb',
                'selectors' => [
                    '{{WRAPPER}} .veloria-contact-form__submit' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label'     => __('Text color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .veloria-contact-form__submit' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label'      => __('Border radius', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 24]],
                'default'    => ['size' => 8],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-contact-form__submit' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label'      => __('Padding', 'veloria'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .veloria-contact-form__submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }


    /**
     * Resolve saved Elementor widget settings for a contact form instance.
     *
     * @param int    $post_id
     * @param string $element_id
     * @return array<string, mixed>|null
     */
    public static function resolve_widget_settings(int $post_id, string $element_id): ?array
    {
        if ($post_id <= 0 || $element_id === '' || !class_exists('\Elementor\Plugin')) {
            return null;
        }

        $document = \Elementor\Plugin::$instance->documents->get($post_id, false);
        if (!$document) {
            return null;
        }

        $elements = $document->get_elements_data();
        if (!is_array($elements)) {
            return null;
        }

        $found = self::find_element_settings($elements, $element_id);

        return is_array($found) ? $found : null;
    }

    /**
     * @param array<int, mixed> $elements
     * @param string            $element_id
     * @return array<string, mixed>|null
     */
    private static function find_element_settings(array $elements, string $element_id): ?array
    {
        foreach ($elements as $element) {
            if (!is_array($element)) {
                continue;
            }

            if (($element['id'] ?? '') === $element_id && ($element['widgetType'] ?? '') === 'veloria-contact-form') {
                return isset($element['settings']) && is_array($element['settings']) ? $element['settings'] : [];
            }

            if (!empty($element['elements']) && is_array($element['elements'])) {
                $nested = self::find_element_settings($element['elements'], $element_id);
                if ($nested !== null) {
                    return $nested;
                }
            }
        }

        return null;
    }

    /**
     * @param array<string, mixed> $settings
     * @return array<int, array<string, string>>
     */
    public static function build_fields_config_from_settings(array $settings): array
    {
        $fields = isset($settings['fields']) && is_array($settings['fields']) ? $settings['fields'] : [];
        $config = [];

        foreach ($fields as $i => $field) {
            if (!is_array($field)) {
                continue;
            }
            $label = isset($field['label']) ? sanitize_text_field((string) $field['label']) : '';
            $placeholder = isset($field['placeholder']) ? sanitize_text_field((string) $field['placeholder']) : '';
            $config[] = [
                'name'  => 'field_' . (int) $i,
                'type'  => isset($field['field_type']) ? sanitize_key((string) $field['field_type']) : 'text',
                'label' => $label !== '' ? $label : ($placeholder !== '' ? $placeholder : 'field_' . (int) $i),
            ];
        }

        return $config;
    }


    public static function ajax_submit(): void
    {
        $nonce = isset($_REQUEST['veloria_contact_nonce']) ? sanitize_text_field(wp_unslash($_REQUEST['veloria_contact_nonce'])) : '';
        if (!wp_verify_nonce($nonce, 'veloria_contact_form')) {
            wp_send_json_error(['message' => __('Security check failed. Please refresh the page and try again.', 'veloria')]);
        }

        $post_id = isset($_POST['post_id']) ? absint(wp_unslash($_POST['post_id'])) : 0;
        $element_id = isset($_POST['element_id']) ? sanitize_text_field(wp_unslash($_POST['element_id'])) : '';
        $settings = self::resolve_widget_settings($post_id, $element_id);

        if ($settings === null) {
            wp_send_json_error(['message' => __('Invalid form configuration.', 'veloria')]);
        }

        $recipient = isset($settings['recipient_email']) ? sanitize_email((string) $settings['recipient_email']) : '';
        if ($recipient === '' || !is_email($recipient)) {
            $recipient = sanitize_email((string) get_option('admin_email'));
        }

        $recaptcha_token = isset($_POST['recaptcha_token']) ? sanitize_text_field(wp_unslash($_POST['recaptcha_token'])) : '';
        $use_recaptcha = !empty($settings['enable_recaptcha']) && $settings['enable_recaptcha'] === 'yes';

        if ($use_recaptcha) {
            $site_key = OptionsHelper::get_option('recaptcha_site_key', '');
            $secret_key = OptionsHelper::get_option('recaptcha_secret_key', '');
            if (!$secret_key || !$recaptcha_token) {
                wp_send_json_error(['message' => __('reCAPTCHA verification failed.', 'veloria')]);
            }
            $verify = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
                'body' => [
                    'secret'   => $secret_key,
                    'response' => $recaptcha_token,
                ],
            ]);
            if (is_wp_error($verify)) {
                wp_send_json_error(['message' => __('reCAPTCHA check failed.', 'veloria')]);
            }
            $body = json_decode(wp_remote_retrieve_body($verify), true);
            if (empty($body['success'])) {
                wp_send_json_error(['message' => __('reCAPTCHA verification failed.', 'veloria')]);
            }
        }

        $fields_config = self::build_fields_config_from_settings($settings);
        $allowed_names = array_fill_keys(array_column($fields_config, 'name'), true);
        $data = [];

        foreach ($fields_config as $field) {
            $name = $field['name'];
            $type = $field['type'];
            $value = '';

            if ($type === 'file' && isset($_FILES[$name]) && is_array($_FILES[$name]) && !empty($_FILES[$name]['tmp_name'])) {
                // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Validated with is_uploaded_file().
                $file = $_FILES[$name];
                if (is_uploaded_file($file['tmp_name'])) {
                    $value = sanitize_file_name((string) $file['name']) . ' (uploaded)';
                }
            } else {
                // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitized below.
                $raw = isset($_POST[$name]) ? wp_unslash($_POST[$name]) : '';
                $value = is_array($raw)
                    ? implode(', ', array_map('sanitize_text_field', $raw))
                    : sanitize_textarea_field((string) $raw);
            }

            $data[$name] = $value;
        }

        foreach (array_keys($_POST) as $posted_key) {
            if (strpos((string) $posted_key, 'field_') === 0 && !isset($allowed_names[$posted_key])) {
                wp_send_json_error(['message' => __('Invalid form field.', 'veloria')]);
            }
        }

        $subject = sprintf(
            /* translators: %s: site title */
            __('[%s] Contact form submission', 'veloria'),
            get_bloginfo('name')
        );
        $body_email = '';
        foreach ($fields_config as $field) {
            $name = isset($field['name']) ? $field['name'] : '';
            $label = isset($field['label']) ? $field['label'] : $name;
            $val = isset($data[$name]) ? $data[$name] : '';
            $body_email .= $label . ': ' . $val . "\n";
        }
        $headers = ['Content-Type: text/plain; charset=UTF-8'];
        $sent = wp_mail($recipient, $subject, $body_email, $headers);

        if ($sent) {
            wp_send_json_success(['message' => __('Thank you for your message.', 'veloria')]);
        }
        wp_send_json_error(['message' => __('Failed to send email.', 'veloria')]);
    }

    public function render(): void
    {
        $path = VELORIA_DIR . 'templates/contact-form/contact-form.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        var fields = settings.fields || [];
        var recipient = settings.recipient_email || '';
        var recaptcha = settings.enable_recaptcha === 'yes';
        var submitText = settings.submit_text || '<?php echo esc_js(__('Send Message', 'veloria')); ?>';
        var successMsg = settings.success_message || '';
        var errorMsg = settings.error_message || '';
        #>
        <form class="veloria-contact-form" data-recipient="{{ recipient }}" data-recaptcha="{{ recaptcha ? '1' : '0' }}" data-success-msg="{{ successMsg }}" data-error-msg="{{ errorMsg }}" data-fields-config="">
            <# _.each(fields, function(f, i) {
                var name = 'field_' + i;
                var ph = f.placeholder || '';
                var req = f.required === 'yes';
                var type = f.field_type || 'text';
            #>
            <div class="veloria-contact-form__row">
                <# if (f.label) { #><label class="veloria-contact-form__label">{{{ f.label }}}</label><# } #>
                <# if (type === 'textarea') { #>
                <textarea class="veloria-contact-form__textarea" name="{{ name }}" placeholder="{{ ph }}" <# if (req) { #>required<# } #>></textarea>
                <# } else if (type === 'select') { #>
                <select class="veloria-contact-form__select" name="{{ name }}" <# if (req) { #>required<# } #>><option value="">—</option></select>
                <# } else if (type === 'file') { #>
                <input type="file" class="veloria-contact-form__input" name="{{ name }}" <# if (req) { #>required<# } #>>
                <# } else { #>
                <input type="{{ type }}" class="veloria-contact-form__input" name="{{ name }}" placeholder="{{ ph }}" <# if (req) { #>required<# } #>>
                <# } #>
            </div>
            <# }); #>
            <# if (recaptcha) { #><div class="veloria-contact-form__recaptcha" data-sitekey=""></div><# } #>
            <div class="veloria-contact-form__row veloria-contact-form__row--submit">
                <button type="submit" class="veloria-contact-form__submit"><span class="veloria-contact-form__spinner" aria-hidden="true"></span><span class="veloria-contact-form__submit-text">{{{ submitText }}}</span></button>
            </div>
            <div class="veloria-contact-form__message" aria-live="polite"></div>
        </form>
        <?php
    }
}
