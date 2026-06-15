<?php
namespace Veloria\Widgets\WooDashboard;

use Veloria\Base\WidgetBase;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Icons_Manager;

defined('ABSPATH') or exit;

/**
 * WooDashboard widget.
 *
 * @package Veloria
 * @since 1.0.0
 */
class WooDashboard extends WidgetBase
{
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_preview_assets']);
    }

    public function enqueue_preview_assets(): void
    {
        wp_enqueue_style('veloria-woo-dashboard');
        wp_enqueue_script('veloria-woo-dashboard');
    }

    public function get_icon(): string
    {
        return 'eicon-user-circle-o veloria-icon';
    }

    public function get_name(): string
    {
        return 'veloria-woo-dashboard';
    }

    public function get_admin_category(): string
    {
        return 'woocommerce';
    }

    public function get_title(): string
    {
        return __('Woo Dashboard', 'veloria');
    }

    public function get_description(): string
    {
        return __('Customer dashboard summary with orders and shortcuts.', 'veloria');
    }

    public function get_brand_color(): string
    {
        return '#7f54b3';
    }

    
    public function get_required_plan(): string
    {
        return 'pro';
    }

    public function get_style_depends(): array
    {
        return ['veloria-woo-dashboard'];
    }

    public function get_script_depends(): array
    {
        return ['elementor-frontend', 'jquery', 'veloria-woo-dashboard'];
    }

    public function enqueue_assets(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        wp_register_style(
            'veloria-woo-dashboard',
            VELORIA_URL . 'assets/css/woo-dashboard.css',
            [],
            VELORIA_VERSION
        );

        wp_register_script(
            'veloria-woo-dashboard',
            VELORIA_URL . 'assets/js/woo-dashboard.js',
            ['elementor-frontend', 'jquery', 'wc-country-select', 'wc-address-i18n'],
            VELORIA_VERSION,
            true
        );

        wp_localize_script(
            'veloria-woo-dashboard',
            'veloriaWooDashboard',
            [
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce'   => wp_create_nonce('veloria_woo_dashboard_account'),
                'nonceAddress' => wp_create_nonce('veloria_woo_dashboard_address'),
                'i18n'    => [
                    'editAddress'  => __('Edit %s', 'veloria'),
                    'addAddress'   => __('Add %s', 'veloria'),
                    'addressSaved' => __('Address saved.', 'veloria'),
                    'addressEmpty' => __('You have not set up this type of address yet.', 'veloria'),
                ],
            ]
        );
                }
        }
}

    /**
     * Registers AJAX handlers.
     */
    public static function register_ajax_handlers(): void
    {
        add_action('wp_ajax_veloria_woo_dashboard_save_account', [__CLASS__, 'ajax_save_account']);
        add_action('wp_ajax_veloria_woo_dashboard_save_address', [__CLASS__, 'ajax_save_address']);
        add_action('wp_ajax_veloria_woo_dashboard_get_address_form', [__CLASS__, 'ajax_get_address_form']);
    }

    /**
     * AJAX: return fresh address form HTML after save.
     */
    public static function ajax_get_address_form(): void
    {
        if (!isset($_POST['address_nonce'])
            || !wp_verify_nonce(sanitize_text_field(wp_unslash((string) $_POST['address_nonce'])), 'veloria_woo_dashboard_address')) {
            wp_send_json_error(
                ['messages' => [__('Invalid session. Please refresh the page.', 'veloria')]],
                403
            );
        }

        if (!is_user_logged_in() || !class_exists('WooCommerce')) {
            wp_send_json_error(
                ['messages' => [__('You cannot perform this action.', 'veloria')]],
                403
            );
        }

        $typeRaw = isset($_POST['address_type']) ? sanitize_title(wp_unslash((string) $_POST['address_type'])) : '';
        $address_type = function_exists('wc_edit_address_i18n')
            ? wc_edit_address_i18n($typeRaw, true)
            : sanitize_key($typeRaw);

        if (!in_array($address_type, ['billing', 'shipping'], true)) {
            wp_send_json_error(['messages' => [__('Invalid address type.', 'veloria')]], 400);
        }

        if ($address_type === 'shipping' && (wc_ship_to_billing_address_only() || !wc_shipping_enabled())) {
            wp_send_json_error(['messages' => [__('Shipping address is not available.', 'veloria')]], 400);
        }

        $vars = self::get_address_edit_template_vars($address_type);
        ob_start();
        wc_get_template('myaccount/form-edit-address.php', $vars);
        $html = ob_get_clean();
        $html = preg_replace('/<form\b/', '<form class="veloria-wd-addresses__wc-form"', $html, 1);

        wp_send_json_success(['html' => $html]);
    }

    /**
     * AJAX: save account details without redirect (aligned with WooCommerce rules).
     */
    public static function ajax_save_account(): void
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash((string) $_POST['nonce'])), 'veloria_woo_dashboard_account')) {
            wp_send_json_error(
                ['messages' => [__('Invalid session. Please refresh the page.', 'veloria')]],
                403
            );
        }

        if (!is_user_logged_in() || !class_exists('WooCommerce')) {
            wp_send_json_error(
                ['messages' => [__('You cannot perform this action.', 'veloria')]],
                403
            );
        }

        $result = self::process_account_save_request();
        if (!$result['success']) {
            wp_send_json_error(['messages' => $result['messages']], 400);
        }

        wp_send_json_success(
            [
                'message'     => $result['message'],
                'displayName' => $result['display_name'],
            ]
        );
    }

    /**
     * AJAX: save billing or shipping address (no redirect).
     */
    public static function ajax_save_address(): void
    {
        $nonceOk = isset($_POST['address_nonce'])
            && wp_verify_nonce(sanitize_text_field(wp_unslash((string) $_POST['address_nonce'])), 'veloria_woo_dashboard_address');

        if (!$nonceOk) {
            wp_send_json_error(
                ['messages' => [__('Invalid session. Please refresh the page.', 'veloria')]],
                403
            );
        }

        if (!is_user_logged_in() || !class_exists('WooCommerce')) {
            wp_send_json_error(
                ['messages' => [__('You cannot perform this action.', 'veloria')]],
                403
            );
        }

        $typeRaw = isset($_POST['address_type']) ? sanitize_title(wp_unslash((string) $_POST['address_type'])) : '';
        $result = self::process_address_save_request($typeRaw);
        if (!$result['success']) {
            wp_send_json_error(['messages' => $result['messages']], 400);
        }

        wp_send_json_success(
            [
                'message'      => $result['message'],
                'formatted'    => $result['formatted'],
                'hasAddress'   => $result['has_address'],
                'addressType'  => $result['address_type'],
            ]
        );
    }

    /**
     * Template variables for WooCommerce address form (mirrors WC_Shortcode_My_Account::edit_address).
     *
     * @return array{load_address:string,address:array<string,mixed>}
     */
    public static function get_address_edit_template_vars(string $load_address): array
    {
        $load_address = sanitize_key($load_address);
        if (!in_array($load_address, ['billing', 'shipping'], true)) {
            $load_address = 'billing';
        }

        $current_user = wp_get_current_user();
        $country = get_user_meta(get_current_user_id(), $load_address . '_country', true);
        if (!$country) {
            $country = WC()->countries->get_base_country();
        }

        if ($load_address === 'billing') {
            $allowed = WC()->countries->get_allowed_countries();
            if (!array_key_exists($country, $allowed)) {
                $keys = array_keys($allowed);
                $country = $keys !== [] ? $keys[0] : WC()->countries->get_base_country();
            }
        }

        if ($load_address === 'shipping') {
            $allowed = WC()->countries->get_shipping_countries();
            if (!array_key_exists($country, $allowed)) {
                $keys = array_keys($allowed);
                $country = $keys !== [] ? $keys[0] : WC()->countries->get_base_country();
            }
        }

        $address = WC()->countries->get_address_fields($country, $load_address . '_');

        foreach ($address as $key => $field) {
            $value = get_user_meta(get_current_user_id(), $key, true);
            if (!$value) {
                switch ($key) {
                    case 'billing_email':
                    case 'shipping_email':
                        $value = $current_user->user_email;
                        break;
                }
            }
            $address[$key]['value'] = apply_filters('woocommerce_my_account_edit_address_field_value', $value, $key, $load_address);
        }

        return [
            'load_address' => $load_address,
            'address'      => apply_filters('woocommerce_address_to_edit', $address, $load_address),
        ];
    }

    /**
     * Validates and saves address; returns structured result for AJAX.
     *
     * @return array{success:bool,messages:string[],message:string,formatted:string,has_address:bool,address_type:string}
     */
    private static function process_address_save_request(string $address_type_raw): array
    {
        $fail = static function (array $messages): array {
            return [
                'success'      => false,
                'messages'     => $messages,
                'message'      => '',
                'formatted'    => '',
                'has_address'  => false,
                'address_type' => '',
            ];
        };

        $user_id = get_current_user_id();
        if ($user_id <= 0) {
            return $fail([__('You must be logged in.', 'veloria')]);
        }

        $address_type = function_exists('wc_edit_address_i18n')
            ? wc_edit_address_i18n(sanitize_title($address_type_raw), true)
            : sanitize_key($address_type_raw);

        if (!in_array($address_type, ['billing', 'shipping'], true)) {
            return $fail([__('Invalid address type.', 'veloria')]);
        }

        if ($address_type === 'shipping' && (wc_ship_to_billing_address_only() || !wc_shipping_enabled())) {
            return $fail([__('Shipping address is not available.', 'veloria')]);
        }

        if (!isset($_POST[$address_type . '_country'])) {
            return $fail([__('Please select a country.', 'veloria')]);
        }

        $customer = new \WC_Customer($user_id);
        $country = wc_clean(wp_unslash($_POST[$address_type . '_country']));
        $address = WC()->countries->get_address_fields($country, $address_type . '_');

        $messages = [];

        foreach ($address as $key => $field) {
            if (!isset($field['type'])) {
                $field['type'] = 'text';
            }

            if ('checkbox' === $field['type']) {
                $value = (int) isset($_POST[$key]);
            } else {
                $value = isset($_POST[$key]) ? wc_clean(wp_unslash($_POST[$key])) : '';
            }

            $value = apply_filters('woocommerce_process_myaccount_field_' . $key, $value);

            $field_invalid = false;

            if (!empty($field['required']) && empty($value)) {
                $messages[] = sprintf(
                    /* translators: %s: Field name. */
                    __('%s is a required field.', 'veloria'),
                    wp_strip_all_tags((string) $field['label'])
                );
                $field_invalid = true;
            }

            if (!$field_invalid && $value !== '' && $value !== 0 && !empty($field['validate']) && is_array($field['validate'])) {
                foreach ($field['validate'] as $rule) {
                    switch ($rule) {
                        case 'postcode':
                            $postcode_country = wc_clean(wp_unslash($_POST[$address_type . '_country']));
                            $value = wc_format_postcode($value, $postcode_country);
                            if ($value !== '' && !\WC_Validation::is_postcode($value, $postcode_country)) {
                                $postcode_notice = ('IE' === $postcode_country)
                                    ? __('Please enter a valid Eircode.', 'veloria')
                                    : __('Please enter a valid postcode / ZIP.', 'veloria');
                                $messages[] = $postcode_notice;
                                $field_invalid = true;
                            }
                            break;
                        case 'phone':
                            if ($value !== '' && !\WC_Validation::is_phone($value)) {
                                $messages[] = sprintf(
                                    /* translators: %s: Field name. */
                                    __('%s is not a valid phone number.', 'veloria'),
                                    wp_strip_all_tags((string) $field['label'])
                                );
                                $field_invalid = true;
                            }
                            break;
                        case 'email':
                            $value = strtolower((string) $value);
                            if (!is_email($value)) {
                                $messages[] = sprintf(
                                    /* translators: %s: Field name. */
                                    __('%s is not a valid email address.', 'veloria'),
                                    wp_strip_all_tags((string) $field['label'])
                                );
                                $field_invalid = true;
                            }
                            break;
                    }
                }
            }

            if ($field_invalid) {
                continue;
            }

            try {
                if (is_callable([$customer, 'set_' . $key])) {
                    $customer->{'set_' . $key}($value);
                } else {
                    $customer->update_meta_data($key, $value);
                }
            } catch (\WC_Data_Exception $e) {
                if ('customer_invalid_billing_email' !== $e->getErrorCode()) {
                    $messages[] = $e->getMessage();
                }
            }
        }

        /**
         * Hook: woocommerce_after_save_address_validation.
         */
        do_action('woocommerce_after_save_address_validation', $user_id, $address_type, $address, $customer);

        if ($messages !== []) {
            return $fail($messages);
        }

        $customer->save();

        /**
         * Hook: woocommerce_customer_save_address.
         */
        do_action('woocommerce_customer_save_address', $user_id, $address_type, $address, $customer);

        $formatted = wc_get_account_formatted_address($address_type);
        $has_address = $formatted !== '';

        return [
            'success'      => true,
            'messages'     => [],
            'message'      => __('Address changed successfully.', 'veloria'),
            'formatted'    => $formatted,
            'has_address'  => $has_address,
            'address_type' => $address_type,
        ];
    }

    /**
     * Validates and saves account fields; returns structured result for AJAX.
     *
     * @return array{success:bool, messages:string[], message:string, display_name:string}
     */
    private static function process_account_save_request(): array
    {
        $fail = static function (array $messages): array {
            return [
                'success'      => false,
                'messages'     => $messages,
                'message'      => '',
                'display_name' => '',
            ];
        };

        $userId = get_current_user_id();
        if ($userId <= 0) {
            return $fail([__('You must be logged in.', 'veloria')]);
        }

        $accountFirstName = !empty($_POST['account_first_name']) ? wc_clean(wp_unslash((string) $_POST['account_first_name'])) : '';
        $accountLastName = !empty($_POST['account_last_name']) ? wc_clean(wp_unslash((string) $_POST['account_last_name'])) : '';
        $accountDisplayName = !empty($_POST['account_display_name']) ? wc_clean(wp_unslash((string) $_POST['account_display_name'])) : '';
        $accountEmailRaw = !empty($_POST['account_email']) ? wc_clean(wp_unslash((string) $_POST['account_email'])) : '';
        $passCur = !empty($_POST['password_current']) ? (string) wp_unslash($_POST['password_current']) : '';
        $pass1 = !empty($_POST['password_1']) ? (string) wp_unslash($_POST['password_1']) : '';
        $pass2 = !empty($_POST['password_2']) ? (string) wp_unslash($_POST['password_2']) : '';
        $savePass = true;

        $currentUser = get_user_by('id', $userId);
        if (!$currentUser instanceof \WP_User) {
            return $fail([__('Could not load your profile.', 'veloria')]);
        }

        $currentFirstName = $currentUser->first_name;
        $currentLastName = $currentUser->last_name;
        $currentEmail = $currentUser->user_email;

        $userObj = new \stdClass();
        $userObj->ID = $userId;
        $userObj->first_name = $accountFirstName;
        $userObj->last_name = $accountLastName;
        $userObj->display_name = $accountDisplayName;

        $messages = [];

        if (is_email($accountDisplayName)) {
            $messages[] = __('Display name cannot be changed to email address due to privacy concern.', 'veloria');
        }

        $requiredFields = apply_filters(
            'woocommerce_save_account_details_required_fields',
            [
                'account_first_name'   => __('First name', 'veloria'),
                'account_last_name'    => __('Last name', 'veloria'),
                'account_display_name' => __('Display name', 'veloria'),
                'account_email'        => __('Email address', 'veloria'),
            ]
        );

        foreach ($requiredFields as $fieldKey => $fieldName) {
            if (empty($_POST[$fieldKey])) {
                $messages[] = sprintf(
                    /* translators: %s: Field name. */
                    __('%s is a required field.', 'veloria'),
                    $fieldName
                );
            }
        }

        $accountEmail = '';
        if ($accountEmailRaw !== '') {
            $accountEmail = sanitize_email($accountEmailRaw);
            if (!is_email($accountEmail)) {
                $messages[] = __('Please provide a valid email address.', 'veloria');
            } elseif (email_exists($accountEmail) && $accountEmail !== $currentUser->user_email) {
                $messages[] = __('This email address is already registered.', 'veloria');
            } elseif (is_email($accountEmail)) {
                $userObj->user_email = $accountEmail;
            }
        }

        if ($passCur !== '' && $pass1 === '' && $pass2 === '') {
            $messages[] = __('Please fill out all password fields.', 'veloria');
            $savePass = false;
        } elseif ($pass1 !== '' && $passCur === '') {
            $messages[] = __('Please enter your current password.', 'veloria');
            $savePass = false;
        } elseif ($pass1 !== '' && $pass2 === '') {
            $messages[] = __('Please re-enter your password.', 'veloria');
            $savePass = false;
        } elseif (($pass1 !== '' || $pass2 !== '') && $pass1 !== $pass2) {
            $messages[] = __('New passwords do not match.', 'veloria');
            $savePass = false;
        } elseif ($pass1 !== '' && !wp_check_password($passCur, $currentUser->user_pass, $currentUser->ID)) {
            $messages[] = __('Your current password is incorrect.', 'veloria');
            $savePass = false;
        }

        if ($pass1 !== '' && $savePass) {
            $userObj->user_pass = $pass1;
        }

        $wcErrors = new \WP_Error();
        do_action_ref_array('woocommerce_save_account_details_errors', [&$wcErrors, &$userObj]);
        if ($wcErrors->has_errors()) {
            foreach ($wcErrors->get_error_messages() as $err) {
                $messages[] = $err;
            }
        }

        if ($messages !== []) {
            return $fail($messages);
        }

        $update = [
            'ID'            => $userId,
            'first_name'    => $accountFirstName,
            'last_name'     => $accountLastName,
            'display_name'  => $accountDisplayName,
            'user_email'    => $accountEmail,
        ];
        if ($pass1 !== '' && $savePass) {
            $update['user_pass'] = $pass1;
        }

        $updated = wp_update_user($update);
        if (is_wp_error($updated)) {
            return $fail([$updated->get_error_message()]);
        }

        try {
            $customer = new \WC_Customer($userId);
            if (isset($userObj->user_email) && is_email((string) $userObj->user_email) && $currentEmail !== $userObj->user_email) {
                $customer->set_billing_email((string) $userObj->user_email);
            }
            if ($currentFirstName !== $accountFirstName) {
                $customer->set_billing_first_name($accountFirstName);
            }
            if ($currentLastName !== $accountLastName) {
                $customer->set_billing_last_name($accountLastName);
            }
            $customer->save();
        } catch (\WC_Data_Exception $e) {
            return $fail([$e->getMessage()]);
        } catch (\Exception $e) {
            return $fail(
                [
                    sprintf(
                        /* translators: %s: Error message. */
                        __('An error occurred while saving account details: %s', 'veloria'),
                        $e->getMessage()
                    ),
                ]
            );
        }

        do_action('woocommerce_save_account_details', $userId);

        $fresh = get_userdata($userId);
        $displayName = $fresh && $fresh->display_name !== '' ? $fresh->display_name : $accountDisplayName;

        return [
            'success'      => true,
            'messages'     => [],
            'message'      => __('Account details changed successfully.', 'veloria'),
            'display_name' => $displayName,
        ];
    }

    public function register_controls(): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        $downloadsDefault = function_exists('wc_get_account_endpoint_url')
            ? wc_get_account_endpoint_url('downloads')
            : '';

        $this->start_controls_section('section_visibility', [
            'label' => __('Sections', 'veloria'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('show_sidebar', [
            'label'        => __('Sidebar', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('show_stats', [
            'label'        => __('Summary cards (top)', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('show_recent_orders', [
            'label'        => __('Recent orders card', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('show_quick_actions', [
            'label'        => __('Quick actions', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('show_panel_orders', [
            'label'       => __('Orders panel & menu', 'veloria'),
            'description' => __('Hides the orders screen and its sidebar link.', 'veloria'),
            'type'        => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'     => 'yes',
        ]);

        $this->add_control('show_panel_wishlist', [
            'label'        => __('Wishlist panel & menu', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('show_panel_account', [
            'label'        => __('Account details panel & menu', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('show_panel_addresses', [
            'label'        => __('Addresses panel & menu', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('show_logout_nav', [
            'label'        => __('Logout in sidebar', 'veloria'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_stats_content', [
            'label'     => __('Summary cards', 'veloria'),
            'tab'       => Controls_Manager::TAB_CONTENT,
            'condition' => ['show_stats' => 'yes'],
        ]);

        $this->add_control('stat_label_orders', [
            'label'   => __('Orders card label', 'veloria'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Total Orders', 'veloria'),
        ]);

        $this->add_control('stat_icon_orders', [
            'label'            => __('Orders card icon', 'veloria'),
            'type'             => Controls_Manager::ICONS,
            'fa4compatibility' => 'stat_icon_orders_legacy',
            'default'          => ['value' => 'fas fa-shopping-bag', 'library' => 'fa-solid'],
        ]);

        $this->add_control('stat_label_spent', [
            'label'   => __('Spent card label', 'veloria'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Total Spent', 'veloria'),
        ]);

        $this->add_control('stat_icon_spent', [
            'label'            => __('Spent card icon', 'veloria'),
            'type'             => Controls_Manager::ICONS,
            'fa4compatibility' => 'stat_icon_spent_legacy',
            'default'          => ['value' => 'fas fa-dollar-sign', 'library' => 'fa-solid'],
        ]);

        $this->add_control('stat_label_wishlist', [
            'label'   => __('Wishlist card label', 'veloria'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Wishlist Items', 'veloria'),
        ]);

        $this->add_control('stat_icon_wishlist', [
            'label'            => __('Wishlist card icon', 'veloria'),
            'type'             => Controls_Manager::ICONS,
            'fa4compatibility' => 'stat_icon_wishlist_legacy',
            'default'          => ['value' => 'fas fa-heart', 'library' => 'fa-solid'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_quick_actions_content', [
            'label'     => __('Quick actions', 'veloria'),
            'tab'       => Controls_Manager::TAB_CONTENT,
            'condition' => ['show_quick_actions' => 'yes'],
        ]);

        $this->add_control('quick_actions_title', [
            'label'   => __('Title', 'veloria'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Quick Actions', 'veloria'),
        ]);

        $qaRepeater = new Repeater();
        $qaRepeater->add_control('item_label', [
            'label'   => __('Label', 'veloria'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Action', 'veloria'),
        ]);
        $qaRepeater->add_control('item_icon', [
            'label'            => __('Icon', 'veloria'),
            'type'             => Controls_Manager::ICONS,
            'fa4compatibility' => 'item_icon_legacy',
            'default'          => ['value' => 'fas fa-circle', 'library' => 'fa-solid'],
        ]);
        $qaRepeater->add_control('item_icon_color', [
            'label'   => __('Icon color', 'veloria'),
            'type'    => Controls_Manager::COLOR,
            'default' => '#2563eb',
        ]);
        $qaRepeater->add_control('item_action', [
            'label'   => __('Action', 'veloria'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'panel',
            'options' => [
                'panel' => __('Open dashboard panel', 'veloria'),
                'url'   => __('Custom link', 'veloria'),
            ],
        ]);
        $qaRepeater->add_control('item_panel', [
            'label'     => __('Panel', 'veloria'),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'orders',
            'options'   => [
                'dashboard'    => __('Dashboard', 'veloria'),
                'orders'       => __('Orders', 'veloria'),
                'wishlist'     => __('Wishlist', 'veloria'),
                'edit-account' => __('Account details', 'veloria'),
                'edit-address' => __('Addresses', 'veloria'),
            ],
            'condition' => ['item_action' => 'panel'],
        ]);
        $qaRepeater->add_control('item_link', [
            'label'       => __('Link', 'veloria'),
            'type'        => Controls_Manager::URL,
            'dynamic'     => ['active' => true],
            'placeholder' => __('https://', 'veloria'),
            'condition'   => ['item_action' => 'url'],
        ]);

        $this->add_control('quick_actions_items', [
            'label'       => __('Actions', 'veloria'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $qaRepeater->get_controls(),
            'title_field' => '{{{ item_label }}}',
            'default'     => [
                [
                    'item_label'      => __('Reorder', 'veloria'),
                    'item_icon'       => ['value' => 'fas fa-sync-alt', 'library' => 'fa-solid'],
                    'item_icon_color' => '#2563eb',
                    'item_action'     => 'panel',
                    'item_panel'      => 'orders',
                ],
                [
                    'item_label'      => __('Track Order', 'veloria'),
                    'item_icon'       => ['value' => 'fas fa-truck', 'library' => 'fa-solid'],
                    'item_icon_color' => '#16a34a',
                    'item_action'     => 'panel',
                    'item_panel'      => 'orders',
                ],
                [
                    'item_label'      => __('Support', 'veloria'),
                    'item_icon'       => ['value' => 'fas fa-headset', 'library' => 'fa-solid'],
                    'item_icon_color' => '#7c3aed',
                    'item_action'     => 'panel',
                    'item_panel'      => 'edit-account',
                ],
                [
                    'item_label'      => __('Downloads', 'veloria'),
                    'item_icon'       => ['value' => 'fas fa-file-download', 'library' => 'fa-solid'],
                    'item_icon_color' => '#ea580c',
                    'item_action'     => 'url',
                    'item_link'       => [
                        'url'         => $downloadsDefault,
                        'is_external' => '',
                        'nofollow'    => '',
                    ],
                ],
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_orders', [
            'label' => __('Orders', 'veloria'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('orders_limit', [
            'label'   => __('Recent orders limit', 'veloria'),
            'type'    => Controls_Manager::NUMBER,
            'min'     => 1,
            'max'     => 20,
            'default' => 2,
        ]);

        $this->add_control('orders_list_limit', [
            'label'   => __('Orders screen limit', 'veloria'),
            'type'    => Controls_Manager::NUMBER,
            'min'     => 1,
            'max'     => 50,
            'default' => 25,
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_guest', [
            'label' => __('Guest', 'veloria'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('guest_message', [
            'label'       => __('Guest message', 'veloria'),
            'type'        => Controls_Manager::TEXTAREA,
            'default'     => __('Please log in to view your dashboard.', 'veloria'),
            'rows'        => 3,
            'placeholder' => __('Message shown to logged-out visitors.', 'veloria'),
        ]);

        $this->add_control('guest_button_label', [
            'label'   => __('Guest button label', 'veloria'),
            'type'    => Controls_Manager::TEXT,
            'default' => __('Log in', 'veloria'),
        ]);

        $this->end_controls_section();

        $this->register_style_controls();
                }
        }
}

    /**
     * Style tab: summary cards, quick actions, layout helpers.
     */
    private function register_style_controls(): void
    {
        $this->start_controls_section('section_stats_style', [
            'label'     => __('Summary cards', 'veloria'),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => ['show_stats' => 'yes'],
        ]);

        $this->add_responsive_control('stats_row_gap', [
            'label'      => __('Column gap', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 48]],
            'default'    => ['size' => 14, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-woo-dashboard__stats' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('stats_row_margin_bottom', [
            'label'      => __('Bottom spacing', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 80]],
            'default'    => ['size' => 18, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-woo-dashboard__stats' => 'margin-bottom: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('stats_columns', [
            'label'          => __('Columns', 'veloria'),
            'type'           => Controls_Manager::SELECT,
            'default'        => '3',
            'tablet_default' => '3',
            'mobile_default' => '1',
            'options'        => [
                '1' => '1',
                '2' => '2',
                '3' => '3',
            ],
            'selectors'      => [
                '{{WRAPPER}} .veloria-woo-dashboard__stats' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
            ],
        ]);

        $this->start_controls_tabs('tabs_stats_cards');

        foreach (
            [
                'orders'   => ['label' => __('Orders card', 'veloria'), 'sel' => '{{WRAPPER}} .veloria-woo-dashboard__stat--orders'],
                'spent'    => ['label' => __('Spent card', 'veloria'), 'sel' => '{{WRAPPER}} .veloria-woo-dashboard__stat--spent'],
                'wishlist' => ['label' => __('Wishlist card', 'veloria'), 'sel' => '{{WRAPPER}} .veloria-woo-dashboard__stat--wishlist'],
            ] as $key => $meta
        ) {
            $this->start_controls_tab('tab_stat_' . $key, ['label' => $meta['label']]);
            $sel = $meta['sel'];

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name'     => 'stat_bg_' . $key,
                    'types'    => ['classic', 'gradient'],
                    'selector' => $sel,
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name'     => 'stat_border_' . $key,
                    'selector' => $sel,
                ]
            );

            $this->add_responsive_control('stat_radius_' . $key, [
                'label'      => __('Border radius', 'veloria'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    $sel => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]);

            $this->add_responsive_control('stat_padding_' . $key, [
                'label'      => __('Padding', 'veloria'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    $sel => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]);

            $this->add_responsive_control('stat_min_height_' . $key, [
                'label'      => __('Min height', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 300]],
                'selectors'  => [
                    $sel => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]);

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name'     => 'stat_shadow_' . $key,
                    'selector' => $sel,
                ]
            );

            $this->add_control('stat_text_color_' . $key, [
                'label'     => __('Text color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    $sel => 'color: {{VALUE}};',
                ],
            ]);

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'     => 'stat_label_typo_' . $key,
                    'label'    => __('Label typography', 'veloria'),
                    'selector' => $sel . ' .veloria-woo-dashboard__stat-label',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'     => 'stat_value_typo_' . $key,
                    'label'    => __('Value typography', 'veloria'),
                    'selector' => $sel . ' .veloria-woo-dashboard__stat-value',
                ]
            );

            $this->add_responsive_control('stat_icon_size_' . $key, [
                'label'      => __('Icon size', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 8, 'max' => 64]],
                'selectors'  => [
                    $sel . ' .veloria-woo-dashboard__stat-icon'     => 'font-size: {{SIZE}}{{UNIT}};',
                    $sel . ' .veloria-woo-dashboard__stat-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]);

            $this->add_control('stat_icon_color_' . $key, [
                'label'     => __('Icon color', 'veloria'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    $sel . ' .veloria-woo-dashboard__stat-icon'     => 'color: {{VALUE}}; opacity: 1;',
                    $sel . ' .veloria-woo-dashboard__stat-icon i'   => 'color: {{VALUE}};',
                    $sel . ' .veloria-woo-dashboard__stat-icon svg' => 'fill: {{VALUE}};',
                ],
            ]);

            $this->add_responsive_control('stat_icon_position_' . $key, [
                'label'      => __('Icon offset', 'veloria'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 0, 'max' => 48]],
                'default'    => ['size' => 14, 'unit' => 'px'],
                'selectors'  => [
                    $sel . ' .veloria-woo-dashboard__stat-icon' => 'top: {{SIZE}}{{UNIT}}; right: {{SIZE}}{{UNIT}};',
                ],
            ]);

            $this->end_controls_tab();
        }

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section('section_quick_actions_style', [
            'label'     => __('Quick actions', 'veloria'),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => ['show_quick_actions' => 'yes'],
        ]);

        $this->add_control('qa_title_heading', [
            'label' => __('Title', 'veloria'),
            'type'  => Controls_Manager::HEADING,
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'qa_title_typo',
                'selector' => '{{WRAPPER}} .veloria-woo-dashboard__card--quick-actions .veloria-woo-dashboard__card-title',
            ]
        );

        $this->add_control('qa_title_color', [
            'label'     => __('Title color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .veloria-woo-dashboard__card--quick-actions .veloria-woo-dashboard__card-title' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('qa_grid_heading', [
            'label'     => __('Grid', 'veloria'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_responsive_control('qa_columns', [
            'label'          => __('Columns', 'veloria'),
            'type'           => Controls_Manager::SELECT,
            'default'        => '2',
            'tablet_default' => '2',
            'mobile_default' => '2',
            'options'        => [
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
            ],
            'selectors'      => [
                '{{WRAPPER}} .veloria-woo-dashboard__card--quick-actions .veloria-woo-dashboard__actions' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
            ],
        ]);

        $this->add_responsive_control('qa_grid_gap', [
            'label'      => __('Gap', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 40]],
            'default'    => ['size' => 12, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-woo-dashboard__card--quick-actions .veloria-woo-dashboard__actions' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('qa_item_heading', [
            'label'     => __('Action button', 'veloria'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('qa_item_bg_color', [
            'label'     => __('Background color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .veloria-woo-dashboard__card--quick-actions .veloria-woo-dashboard__action' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'qa_item_border',
                'selector' => '{{WRAPPER}} .veloria-woo-dashboard__card--quick-actions .veloria-woo-dashboard__action',
            ]
        );

        $this->add_responsive_control('qa_item_radius', [
            'label'      => __('Border radius', 'veloria'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-woo-dashboard__card--quick-actions .veloria-woo-dashboard__action' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('qa_item_padding', [
            'label'      => __('Padding', 'veloria'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-woo-dashboard__card--quick-actions .veloria-woo-dashboard__action' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('qa_icon_size', [
            'label'      => __('Icon size', 'veloria'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 16, 'max' => 48]],
            'default'    => ['size' => 28, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .veloria-woo-dashboard__card--quick-actions .veloria-woo-dashboard__action-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .veloria-woo-dashboard__card--quick-actions .veloria-woo-dashboard__action-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'qa_label_typo',
                'label'    => __('Label typography', 'veloria'),
                'selector' => '{{WRAPPER}} .veloria-woo-dashboard__card--quick-actions .veloria-woo-dashboard__action-label',
            ]
        );

        $this->add_control('qa_label_color', [
            'label'     => __('Label color', 'veloria'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .veloria-woo-dashboard__card--quick-actions .veloria-woo-dashboard__action-label' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
    }

    /**
     * @param array<string, mixed> $icon
     *
     * @return array<string, mixed>
     */
    private static function normalize_elementor_icon(array $icon): array
    {
        $value = isset($icon['value']) ? trim((string) $icon['value']) : '';
        $library = isset($icon['library']) ? trim((string) $icon['library']) : '';

        if ($value === '') {
            return $icon;
        }

        if ($library === '') {
            if (strpos($value, 'fas ') === 0 || strpos($value, 'fa-solid') === 0) {
                $icon['library'] = 'fa-solid';
            } elseif (strpos($value, 'far ') === 0 || strpos($value, 'fa-regular') === 0) {
                $icon['library'] = 'fa-regular';
            } elseif (strpos($value, 'fab ') === 0 || strpos($value, 'fa-brands') === 0) {
                $icon['library'] = 'fa-brands';
            } elseif (strpos($value, 'fa ') === 0) {
                $icon['library'] = 'fa-solid';
            } else {
                $icon['library'] = 'fa-solid';
            }
        }

        return $icon;
    }

    /**
     * @param array<string, mixed> $icon
     */
    public function render_dashboard_icon(array $icon, string $fallbackClass = 'fas fa-circle'): void
    {
        if (emt_fs()->can_use_premium_code__premium_only()) {
            if (emt_fs()->is_plan('pro')) {

        $icon = self::normalize_elementor_icon($icon);
        $value = isset($icon['value']) ? trim((string) $icon['value']) : '';
        $library = isset($icon['library']) ? trim((string) $icon['library']) : '';

        if ($value !== '' && $library !== '' && class_exists(Icons_Manager::class)) {
            ob_start();
            Icons_Manager::render_icon($icon, ['aria-hidden' => 'true']);
            $rendered = trim((string) ob_get_clean());
            if ($rendered !== '') {
                echo $rendered; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor icon markup.

                return;
            }
        }

        $class = $value !== '' ? $value : $fallbackClass;
        $class = preg_replace('/[^a-zA-Z0-9_\s-]/', '', $class);
        if ($class === '') {
            $class = $fallbackClass;
        }

        echo '<i class="' . esc_attr(trim($class)) . '" aria-hidden="true"></i>';
                }
        }
}

    /**
     * Human-readable label for the user's primary role.
     */
    public function get_user_role_label(\WP_User $user): string
    {
        $roles = $user->roles;
        if (empty($roles)) {
            return __('Member', 'veloria');
        }

        $roleKey = $roles[0];
        $wp_roles = wp_roles();
        if (!isset($wp_roles->role_names[$roleKey])) {
            return $roleKey;
        }

        return translate_user_role($wp_roles->role_names[$roleKey]);
    }

    public function render(): void
    {
        if (class_exists('\Elementor\Plugin')) {
            $plugin = \Elementor\Plugin::$instance;
            if (isset($plugin->frontend) && is_object($plugin->frontend) && method_exists($plugin->frontend, 'enqueue_font')) {
                $plugin->frontend->enqueue_font('fa-solid');
                $plugin->frontend->enqueue_font('fa-regular');
                $plugin->frontend->enqueue_font('fa-brands');
            }
        }

        foreach (['elementor-icons-fa-solid', 'elementor-icons-fa-regular', 'elementor-icons-fa-brands'] as $faHandle) {
            if (wp_style_is($faHandle, 'registered')) {
                wp_enqueue_style($faHandle);
            }
        }

        if (class_exists('WooCommerce')) {
            wp_enqueue_script('wc-country-select');
            wp_enqueue_script('wc-address-i18n');
        }
        $path = VELORIA_DIR . 'templates/woo-dashboard/woo-dashboard.php';
        if (is_readable($path)) {
            include $path;
        }
    }
}

