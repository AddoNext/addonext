<?php
namespace Elementrify\Widgets\ContactForm;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementrify\Utils\OptionsHelper;

defined('ABSPATH') or exit;

class ContactForm extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
    }

    public static function register_ajax_handlers(): void
    {
        add_action('wp_ajax_elementrify_contact_form', [self::class, 'ajax_submit']);
        add_action('wp_ajax_nopriv_elementrify_contact_form', [self::class, 'ajax_submit']);
    }

    public function get_icon(): string
    {
        return 'eicon-form-horizontal elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-contact-form';
    }

    public function get_admin_category(): string
    {
        return 'marketing';
    }

    public function get_title(): string
    {
        return __('Contact Form', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Advanced form builder', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#2563eb';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-contact-form'];
    }

    public function get_script_depends(): array
    {
        $depends = ['elementrify-contact-form'];
        return $depends;
    }

    public function register_controls(): void
    {
        $this->start_controls_section(
            'section_form',
            [
                'label' => __('Form', 'elementrify'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();
        $repeater->add_control(
            'field_type',
            [
                'label'   => __('Type', 'elementrify'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'text',
                'options' => [
                    'text'     => __('Text', 'elementrify'),
                    'email'    => __('Email', 'elementrify'),
                    'textarea' => __('Textarea', 'elementrify'),
                    'password' => __('Password', 'elementrify'),
                    'select'   => __('Dropdown', 'elementrify'),
                    'file'     => __('File', 'elementrify'),
                ],
            ]
        );
        $repeater->add_control(
            'label',
            [
                'label'   => __('Label', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => '',
            ]
        );
        $repeater->add_control(
            'placeholder',
            [
                'label'   => __('Placeholder', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => '',
            ]
        );
        $repeater->add_control(
            'required',
            [
                'label'        => __('Required', 'elementrify'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => '',
            ]
        );
        $repeater->add_control(
            'options',
            [
                'label'     => __('Options (one per line)', 'elementrify'),
                'type'      => Controls_Manager::TEXTAREA,
                'default'   => '',
                'condition' => ['field_type' => 'select'],
            ]
        );
        $repeater->add_control(
            'accept',
            [
                'label'     => __('Accept (e.g. .pdf,.jpg)', 'elementrify'),
                'type'      => Controls_Manager::TEXT,
                'default'   => '',
                'condition' => ['field_type' => 'file'],
            ]
        );

        $default_fields = [
            ['field_type' => 'text', 'placeholder' => __('Your Name', 'elementrify'), 'required' => 'yes', 'label' => ''],
            ['field_type' => 'email', 'placeholder' => __('Email Address', 'elementrify'), 'required' => 'yes', 'label' => ''],
            ['field_type' => 'textarea', 'placeholder' => __('Your Message', 'elementrify'), 'required' => '', 'label' => ''],
        ];

        $this->add_control(
            'fields',
            [
                'label'       => __('Fields', 'elementrify'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => $default_fields,
                'title_field' => '{{{ placeholder || label || field_type }}}',
            ]
        );

        $this->add_control(
            'recipient_email',
            [
                'label'       => __('Recipient email', 'elementrify'),
                'type'        => Controls_Manager::TEXT,
                'default'     => get_option('admin_email'),
                'placeholder' => get_option('admin_email'),
                'description' => __('Where the form submissions will be sent.', 'elementrify'),
            ]
        );

        $this->add_control(
            'enable_recaptcha',
            [
                'label'        => __('Enable reCAPTCHA', 'elementrify'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => '',
                'description'  => __('Requires Site Key and Secret Key in Elementrify → Settings → API Keys.', 'elementrify'),
            ]
        );

        $this->add_control(
            'submit_text',
            [
                'label'   => __('Submit button text', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Send Message', 'elementrify'),
            ]
        );

        $this->add_control(
            'success_message',
            [
                'label'   => __('Success message', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Thank you for your message. We will get back to you soon.', 'elementrify'),
            ]
        );

        $this->add_control(
            'error_message',
            [
                'label'   => __('Error message', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Something went wrong. Please try again.', 'elementrify'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_form',
            [
                'label' => __('Form', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'label_typography',
                'label'          => __('Label', 'elementrify'),
                'selector'       => '{{WRAPPER}} .elementrify-contact-form__label',
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
                'label'          => __('Input / Textarea', 'elementrify'),
                'selector'       => '{{WRAPPER}} .elementrify-contact-form__input, {{WRAPPER}} .elementrify-contact-form__textarea, {{WRAPPER}} .elementrify-contact-form__select',
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
                'label'          => __('Placeholder', 'elementrify'),
                'selector'       => '{{WRAPPER}} .elementrify-contact-form__input::placeholder, {{WRAPPER}} .elementrify-contact-form__textarea::placeholder',
                'fields_options' => [
                    'typography'  => ['default' => 'custom'],
                    'font_size'   => ['default' => ['unit' => 'px', 'size' => 14]],
                ],
            ]
        );

        $this->add_control(
            'input_bg',
            [
                'label'     => __('Input background', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-contact-form__input' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .elementrify-contact-form__textarea' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .elementrify-contact-form__select' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_border_color',
            [
                'label'     => __('Input border color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#d1d5db',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-contact-form__input' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .elementrify-contact-form__textarea' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .elementrify-contact-form__select' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_text_color',
            [
                'label'     => __('Input text color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#111827',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-contact-form__input' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementrify-contact-form__textarea' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementrify-contact-form__select' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_button',
            [
                'label' => __('Button', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} .elementrify-contact-form__submit',
            ]
        );

        $this->add_control(
            'button_bg',
            [
                'label'     => __('Background', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#2563eb',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-contact-form__submit' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label'     => __('Text color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-contact-form__submit' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label'      => __('Border radius', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 24]],
                'default'    => ['size' => 8],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-contact-form__submit' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label'      => __('Padding', 'elementrify'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-contact-form__submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public static function ajax_submit(): void
    {
        $nonce = isset($_REQUEST['elementrify_contact_nonce']) ? sanitize_text_field(wp_unslash($_REQUEST['elementrify_contact_nonce'])) : '';
        if (!wp_verify_nonce($nonce, 'elementrify_contact_form')) {
            wp_send_json_error(['message' => __('Security check failed. Please refresh the page and try again.', 'elementrify')]);
        }

        $form_id = isset($_POST['form_id']) ? sanitize_text_field(wp_unslash($_POST['form_id'])) : '';
        $widget_id = isset($_POST['widget_id']) ? sanitize_text_field(wp_unslash($_POST['widget_id'])) : '';
        $recipient = isset($_POST['recipient']) ? sanitize_email(wp_unslash($_POST['recipient'])) : '';
        $recaptcha_token = isset($_POST['recaptcha_token']) ? sanitize_text_field(wp_unslash($_POST['recaptcha_token'])) : '';
        $use_recaptcha = isset($_POST['use_recaptcha']) && $_POST['use_recaptcha'] === '1';

        if (!$recipient || !is_email($recipient)) {
            wp_send_json_error(['message' => __('Invalid recipient.', 'elementrify')]);
        }

        if ($use_recaptcha) {
            $site_key = OptionsHelper::get_option('recaptcha_site_key', '');
            $secret_key = OptionsHelper::get_option('recaptcha_secret_key', '');
            if (!$secret_key || !$recaptcha_token) {
                wp_send_json_error(['message' => __('reCAPTCHA verification failed.', 'elementrify')]);
            }
            $verify = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
                'body' => [
                    'secret'   => $secret_key,
                    'response' => $recaptcha_token,
                ],
            ]);
            if (is_wp_error($verify)) {
                wp_send_json_error(['message' => __('reCAPTCHA check failed.', 'elementrify')]);
            }
            $body = json_decode(wp_remote_retrieve_body($verify), true);
            if (empty($body['success'])) {
                wp_send_json_error(['message' => __('reCAPTCHA verification failed.', 'elementrify')]);
            }
        }

        $fields_config_raw = isset($_POST['fields_config']) ? wp_unslash($_POST['fields_config']) : '';
        $fields_config = is_string($fields_config_raw) ? json_decode($fields_config_raw, true) : [];
        $fields_config = is_array($fields_config) ? $fields_config : [];
        $data = [];
        foreach ($fields_config as $field) {
            $name = isset($field['name']) ? $field['name'] : '';
            $type = isset($field['type']) ? $field['type'] : 'text';
            if ($name === '') continue;
            $value = '';
            if ($type === 'file' && isset($_FILES[$name]) && !empty($_FILES[$name]['tmp_name'])) {
                $file = $_FILES[$name];
                if (is_uploaded_file($file['tmp_name'])) {
                    $value = $file['name'] . ' (uploaded)';
                }
            } else {
                $raw = isset($_POST[$name]) ? $_POST[$name] : '';
                $value = is_array($raw) ? implode(', ', array_map('sanitize_text_field', $raw)) : sanitize_textarea_field($raw);
            }
            $data[$name] = $value;
        }

        $subject = sprintf(__('[%s] Contact form submission', 'elementrify'), get_bloginfo('name'));
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
            wp_send_json_success(['message' => __('Thank you for your message.', 'elementrify')]);
        }
        wp_send_json_error(['message' => __('Failed to send email.', 'elementrify')]);
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/contact-form/contact-form.php';
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
        var submitText = settings.submit_text || '<?php echo esc_js(__('Send Message', 'elementrify')); ?>';
        var successMsg = settings.success_message || '';
        var errorMsg = settings.error_message || '';
        #>
        <form class="elementrify-contact-form" data-recipient="{{ recipient }}" data-recaptcha="{{ recaptcha ? '1' : '0' }}" data-success-msg="{{ successMsg }}" data-error-msg="{{ errorMsg }}" data-fields-config="">
            <# _.each(fields, function(f, i) {
                var name = 'field_' + i;
                var ph = f.placeholder || '';
                var req = f.required === 'yes';
                var type = f.field_type || 'text';
            #>
            <div class="elementrify-contact-form__row">
                <# if (f.label) { #><label class="elementrify-contact-form__label">{{{ f.label }}}</label><# } #>
                <# if (type === 'textarea') { #>
                <textarea class="elementrify-contact-form__textarea" name="{{ name }}" placeholder="{{ ph }}" <# if (req) { #>required<# } #>></textarea>
                <# } else if (type === 'select') { #>
                <select class="elementrify-contact-form__select" name="{{ name }}" <# if (req) { #>required<# } #>><option value="">—</option></select>
                <# } else if (type === 'file') { #>
                <input type="file" class="elementrify-contact-form__input" name="{{ name }}" <# if (req) { #>required<# } #>>
                <# } else { #>
                <input type="{{ type }}" class="elementrify-contact-form__input" name="{{ name }}" placeholder="{{ ph }}" <# if (req) { #>required<# } #>>
                <# } #>
            </div>
            <# }); #>
            <# if (recaptcha) { #><div class="elementrify-contact-form__recaptcha" data-sitekey=""></div><# } #>
            <div class="elementrify-contact-form__row elementrify-contact-form__row--submit">
                <button type="submit" class="elementrify-contact-form__submit"><span class="elementrify-contact-form__spinner" aria-hidden="true"></span><span class="elementrify-contact-form__submit-text">{{{ submitText }}}</span></button>
            </div>
            <div class="elementrify-contact-form__message" aria-live="polite"></div>
        </form>
        <?php
    }
}
