<?php
namespace Veloria\Admin;

use WP_REST_Response;

defined('ABSPATH') or exit;

/**
 * Free-tier account payload for the admin upgrade screen.
 */
class FreeAccountController
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_endpoints']);
    }

    /**
     * @return void
     */
    public function register_endpoints(): void
    {
        register_rest_route('veloria/v1', '/license', [
            'methods'             => 'GET',
            'callback'            => [$this, 'rest_get_license'],
            'permission_callback' => [$this, 'check_permission'],
        ]);
    }

    /**
     * @return bool
     */
    public function check_permission(): bool
    {
        return current_user_can('manage_options');
    }

    /**
     * @return WP_REST_Response
     */
    public function rest_get_license(): WP_REST_Response
    {
        $upgrade_url = '';
        $account_url = '';

        if (function_exists('emt_fs')) {
            $fs          = emt_fs();
            $upgrade_url = $fs->get_upgrade_url();
            $account_url = $fs->get_account_url();
        }

        return new WP_REST_Response([
            'success' => true,
            'data'    => [
                'is_active'          => false,
                'has_pro_plan'       => false,
                'status_label'       => 'Free',
                'plan_name'          => '',
                'plan_title'         => '',
                'sites_label'        => '',
                'expires_at'         => null,
                'expires_label'      => '',
                'time_left_label'    => '',
                'support_label'      => '',
                'support_sublabel'   => '',
                'license_key_masked' => '',
                'license_id'         => '',
                'activation_date'    => '',
                'sites_used'         => 0,
                'sites_quota'        => null,
                'version'            => defined('VELORIA_VERSION') ? 'v' . VELORIA_VERSION : '',
                'email'              => '',
                'customer_id'        => '',
                'purchase_date'      => '',
                'auto_renewal'       => false,
                'upgrade_url'        => $upgrade_url,
                'account_url'        => $account_url,
                'support_url'        => '',
                'docs_url'           => '',
            ],
        ]);
    }
}
