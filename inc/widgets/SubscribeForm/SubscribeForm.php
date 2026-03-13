<?php
namespace Elementrify\Widgets\SubscribeForm;

use Elementrify\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementrify\Utils\OptionsHelper;

defined('ABSPATH') or exit;

class SubscribeForm extends WidgetBase
{
    public function get_icon(): string
    {
        return 'eicon-mail elementrify-icon';
    }

    public function get_name(): string
    {
        return 'elementrify-subscribe-form';
    }

    public function get_admin_category(): string
    {
        return 'marketing';
    }

    public function get_title(): string
    {
        return __('Subscribe Form', 'elementrify');
    }

    public function get_description(): string
    {
        return __('Newsletter signup with Mailchimp', 'elementrify');
    }

    public function get_brand_color(): string
    {
        return '#E43775';
    }

    public function get_style_depends(): array
    {
        return ['elementrify-subscribe-form'];
    }

    public function get_script_depends(): array
    {
        return ['elementrify-subscribe-form'];
    }

    public function register_controls(): void
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'elementrify'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'icon',
            [
                'label'   => __('Icon', 'elementrify'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'eicon-envelope',
                    'library' => 'eicons',
                ],
            ]
        );

        $this->add_control(
            'title',
            [
                'label'   => __('Title', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Subscribe to Newsletter', 'elementrify'),
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label'   => __('Subtitle', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Get the latest updates and offers', 'elementrify'),
            ]
        );

        $this->add_control(
            'placeholder',
            [
                'label'   => __('Email placeholder', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Enter your email', 'elementrify'),
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label'   => __('Button text', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Subscribe', 'elementrify'),
            ]
        );

        $this->add_control(
            'list_id',
            [
                'label'       => __('Mailchimp Audience ID', 'elementrify'),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'placeholder' => __('e.g. abc123def4', 'elementrify'),
                'description' => __('Find this in Mailchimp: Audience → Settings → Audience name and defaults.', 'elementrify'),
            ]
        );

        $this->add_control(
            'success_message',
            [
                'label'   => __('Success message', 'elementrify'),
                'type'    => Controls_Manager::TEXT,
                'default' => __('Thank you for subscribing!', 'elementrify'),
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
            'section_style_box',
            [
                'label' => __('Box', 'elementrify'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'            => 'box_background',
                'types'           => ['classic', 'gradient'],
                'exclude'         => ['image'],
                'selector'        => '{{WRAPPER}} .elementrify-subscribe-form',
                'fields_options' => [
                    'background'     => ['default' => 'gradient'],
                    'color'          => ['default' => '#FF4081'],
                    'color_b'        => ['default' => '#E01A4F'],
                    'gradient_type'  => ['default' => 'linear'],
                    'gradient_angle' => ['default' => ['unit' => 'deg', 'size' => 90]],
                ],
            ]
        );

        $this->add_responsive_control(
            'box_padding',
            [
                'label'      => __('Padding', 'elementrify'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-subscribe-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'box_radius',
            [
                'label'      => __('Box border radius', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 50]],
                'default'    => ['size' => 12],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-subscribe-form' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'     => __('Icon color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-subscribe-form__icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementrify-subscribe-form__icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label'      => __('Icon size', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 12, 'max' => 64]],
                'default'    => ['size' => 32],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-subscribe-form__icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}}; max-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementrify-subscribe-form__icon i' => 'font-size: {{SIZE}}{{UNIT}} !important; width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;',
                    '{{WRAPPER}} .elementrify-subscribe-form__icon svg' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_control(
            'heading_title_style',
            [
                'label'     => __('Title', 'elementrify'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => __('Title color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-subscribe-form__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'title_typography',
                'selector'       => '{{WRAPPER}} .elementrify-subscribe-form__title',
                'fields_options' => [
                    'typography' => ['default' => 'custom'],
                    'font_size'  => ['default' => ['unit' => 'px', 'size' => 26]],
                    'font_weight' => ['default' => '700'],
                ],
            ]
        );

        $this->add_control(
            'heading_subtitle_style',
            [
                'label'     => __('Subtitle', 'elementrify'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label'     => __('Subtitle color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'rgba(255,255,255,0.9)',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-subscribe-form__subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'subtitle_typography',
                'selector'       => '{{WRAPPER}} .elementrify-subscribe-form__subtitle',
                'fields_options' => [
                    'typography' => ['default' => 'custom'],
                    'font_size'  => ['default' => ['unit' => 'px', 'size' => 16]],
                ],
            ]
        );

        $this->add_control(
            'heading_form_style',
            [
                'label'     => __('Form', 'elementrify'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'input_bg',
            [
                'label'     => __('Input background', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-subscribe-form__input' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg',
            [
                'label'     => __('Button background', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f0f0f0',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-subscribe-form__submit' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'form_padding',
            [
                'label'      => __('Input & button padding', 'elementrify'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default'    => [
                    'top'    => '16',
                    'right'  => '24',
                    'bottom' => '16',
                    'left'   => '24',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-subscribe-form__input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .elementrify-subscribe-form__submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .elementrify-subscribe-form__input' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label'     => __('Button text color', 'elementrify'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#E43775',
                'selectors' => [
                    '{{WRAPPER}} .elementrify-subscribe-form__submit' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'input_typography',
                'label'          => __('Input typography', 'elementrify'),
                'selector'       => '{{WRAPPER}} .elementrify-subscribe-form__input',
                'fields_options' => [
                    'typography'  => ['default' => 'custom'],
                    'font_size'   => ['default' => ['unit' => 'px', 'size' => 16]],
                    'font_weight' => ['default' => '400'],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'           => 'button_typography',
                'label'          => __('Button typography', 'elementrify'),
                'selector'       => '{{WRAPPER}} .elementrify-subscribe-form__submit',
                'fields_options' => [
                    'typography'  => ['default' => 'custom'],
                    'font_size'   => ['default' => ['unit' => 'px', 'size' => 16]],
                    'font_weight' => ['default' => '700'],
                ],
            ]
        );

        $this->add_control(
            'form_border_radius',
            [
                'label'      => __('Input & button radius', 'elementrify'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 50]],
                'default'    => ['size' => 8],
                'selectors'  => [
                    '{{WRAPPER}} .elementrify-subscribe-form__input' => 'border-radius: {{SIZE}}{{UNIT}} 0 0 {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementrify-subscribe-form__submit' => 'border-radius: 0 {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    public static function register_ajax_handlers(): void
    {
        add_action('wp_ajax_elementrify_subscribe_form', [self::class, 'ajax_submit']);
        add_action('wp_ajax_nopriv_elementrify_subscribe_form', [self::class, 'ajax_submit']);
    }

    public static function ajax_submit(): void
    {
        $nonce = isset($_REQUEST['elementrify_subscribe_nonce']) ? sanitize_text_field(wp_unslash($_REQUEST['elementrify_subscribe_nonce'])) : '';
        if (!wp_verify_nonce($nonce, 'elementrify_subscribe_form')) {
            wp_send_json_error(['message' => __('Security check failed. Please refresh and try again.', 'elementrify')]);
        }

        $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
        $list_id = isset($_POST['list_id']) ? sanitize_text_field(wp_unslash($_POST['list_id'])) : '';

        if (!$email || !is_email($email)) {
            wp_send_json_error(['message' => __('Please enter a valid email address.', 'elementrify')]);
        }

        if (!$list_id) {
            wp_send_json_error(['message' => __('Newsletter is not configured.', 'elementrify')]);
        }

        $api_key = OptionsHelper::get_option('mailchimp_api_key', '');
        if (!$api_key) {
            wp_send_json_error(['message' => __('Mailchimp is not configured.', 'elementrify')]);
        }

        $dc = '';
        if (strpos($api_key, '-') !== false) {
            $dc = substr($api_key, strpos($api_key, '-') + 1);
        }
        if (!$dc) {
            wp_send_json_error(['message' => __('Invalid Mailchimp API key.', 'elementrify')]);
        }

        $member_id = md5(strtolower($email));
        $url = sprintf('https://%s.api.mailchimp.com/3.0/lists/%s/members/%s', $dc, $list_id, $member_id);

        $response = wp_remote_request($url, [
            'method'  => 'PUT',
            'timeout' => 15,
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Basic ' . base64_encode('anystring:' . $api_key),
            ],
            'body' => wp_json_encode([
                'email_address' => $email,
                'status'        => 'subscribed',
            ]),
        ]);

        if (is_wp_error($response)) {
            wp_send_json_error(['message' => __('Could not connect to Mailchimp.', 'elementrify')]);
        }

        $code = wp_remote_retrieve_response_code($response);
        $body = json_decode(wp_remote_retrieve_body($response), true);

        if ($code >= 200 && $code < 300) {
            wp_send_json_success(['message' => __('Thank you for subscribing!', 'elementrify')]);
        }

        $message = __('Subscription failed. Please try again.', 'elementrify');
        if (is_array($body) && !empty($body['detail'])) {
            $message = sanitize_text_field($body['detail']);
        }
        if ($code === 400 && is_array($body) && !empty($body['title'])) {
            if (strpos(strtolower($body['title']), 'already') !== false) {
                $message = __('This email is already subscribed.', 'elementrify');
            }
        }
        wp_send_json_error(['message' => $message]);
    }

    public function render(): void
    {
        $path = ELEMENTRIFY_DIR . 'templates/subscribe-form/subscribe-form.php';
        if (is_readable($path)) {
            include $path;
        }
    }

    protected function content_template(): void
    {
        ?>
        <#
        var title = settings.title || '<?php echo esc_js(__('Subscribe to Newsletter', 'elementrify')); ?>';
        var subtitle = settings.subtitle || '';
        var placeholder = settings.placeholder || '<?php echo esc_js(__('Enter your email', 'elementrify')); ?>';
        var buttonText = settings.button_text || '<?php echo esc_js(__('Subscribe', 'elementrify')); ?>';
        var iconHtml = '';
        if (settings.icon && settings.icon.value) {
            iconHtml = elementor.helpers.renderIcon(view, settings.icon, { 'aria-hidden': 'true' }, 'i', 'object').value || '';
        }
        #>
        <div class="elementrify-subscribe-form">
            <# if (iconHtml) { #><div class="elementrify-subscribe-form__icon">{{{ iconHtml }}}</div><# } #>
            <# if (title) { #><h3 class="elementrify-subscribe-form__title">{{{ title }}}</h3><# } #>
            <# if (subtitle) { #><p class="elementrify-subscribe-form__subtitle">{{{ subtitle }}}</p><# } #>
            <form class="elementrify-subscribe-form__form" data-ajax-url="" data-list-id="" data-success-msg="" data-error-msg="">
                <input type="email" name="email" class="elementrify-subscribe-form__input" placeholder="{{ placeholder }}" required>
                <button type="submit" class="elementrify-subscribe-form__submit"><span class="elementrify-subscribe-form__spinner" aria-hidden="true"></span><span class="elementrify-subscribe-form__submit-text">{{{ buttonText }}}</span></button>
            </form>
            <div class="elementrify-subscribe-form__message" aria-live="polite"></div>
        </div>
        <?php
    }
}
