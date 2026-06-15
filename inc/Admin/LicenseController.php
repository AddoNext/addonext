<?php
namespace Veloria\Admin;

use WP_REST_Request;
use WP_REST_Response;
use Veloria\Utils\LicenseCapabilities;

defined('ABSPATH') or exit;

/**
 * License controller: REST API for license data and actions using Freemius SDK.
 */
class LicenseController
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_endpoints']);
    }

    /**
     * Register REST endpoints for license.
     *
     * @return void
     */
    public function register_endpoints(): void
    {
        register_rest_route('veloria/v1', '/license', [
            'methods'             => 'GET',
            'callback'            => [$this, 'rest_get_license'],
            'permission_callback' => [$this, 'check_permission']
        ]);

        register_rest_route('veloria/v1', '/license/activate', [
            'methods'             => 'POST',
            'callback'            => [$this, 'rest_activate_license'],
            'permission_callback' => [$this, 'check_permission'],
            'args'                => [
                'license_key' => [
                    'required'          => true,
                    'type'              => 'string',
                    'sanitize_callback' => 'sanitize_text_field'
                ]
            ]
        ]);

        register_rest_route('veloria/v1', '/license/deactivate', [
            'methods'             => 'POST',
            'callback'            => [$this, 'rest_deactivate_license'],
            'permission_callback' => [$this, 'check_permission']
        ]);

        register_rest_route('veloria/v1', '/license/check', [
            'methods'             => 'POST',
            'callback'            => [$this, 'rest_check_license'],
            'permission_callback' => [$this, 'check_permission']
        ]);
    }

    /**
     * Check if the current user can manage options.
     *
     * @return bool
     */
    public function check_permission(): bool
    {
        return current_user_can('manage_options');
    }

    /**
     * GET /license: return license payload for the UI.
     *
     * @return WP_REST_Response
     */
    public function rest_get_license(): WP_REST_Response
    {
        $data = $this->build_license_payload();

        return new WP_REST_Response(['success' => true, 'data' => $data]);
    }

    /**
     * Build license payload from Freemius SDK.
     *
     * @return array<string, mixed>
     */
    private function build_license_payload(): array
    {
        if (!function_exists('emt_fs')) {
            return $this->default_payload(false, 'Not activated');
        }

        $fs = emt_fs();
        if (!$fs->is_registered()) {
            return $this->default_payload(false, 'Not activated');
        }

        $user    = $fs->get_user();
        $site    = $fs->get_site();
        $plan    = $fs->get_plan();
        $license = $fs->get_active_parent_license(null, false);

        $version     = $fs->get_plugin_version();
        $upgrade_url = $fs->get_upgrade_url();
        $account_url = $fs->get_account_url();
        $support_url = $fs->get_support_forum_url();

        $is_active = $fs->has_active_valid_license();
        if ($license && $license->is_expired()) {
            $status_label = 'Expired';
        } elseif ($is_active) {
            $status_label = 'Active';
        } else {
            $status_label = 'Not activated';
        }

        $internal_license  = $this->get_fs_internal_license();
        $effective_license = $license ?: $internal_license;

        $plan_name   = $plan ? $plan->title : '';
        $plan_title  = $plan ? $fs->get_plan_title() : '';
        $sites_used  = $site && isset($site->id) ? 1 : 0;
        $sites_quota = null;
        if ($effective_license) {
            $sites_used  = $effective_license->activated + $effective_license->activated_local;
            $sites_quota = $effective_license->is_unlimited() ? null : (int) $effective_license->quota;
        }
        $sites_label = $sites_quota === null ? 'Unlimited Sites' : $sites_used . ' of ' . $sites_quota;

        $expires_at      = null;
        $expires_label   = '';
        $time_left_label = '';
        if ($effective_license && !$effective_license->is_lifetime() && !empty($effective_license->expiration)) {
            $expires_at    = $effective_license->expiration;
            $expires_label = $this->format_date($effective_license->expiration);
            $now           = time();
            $end           = strtotime($effective_license->expiration);
            if ($end !== false) {
                if ($end > $now) {
                    $diff            = $end - $now;
                    $months          = (int) floor($diff / (30 * 24 * 3600));
                    $time_left_label = $months >= 12
                        ? (int) floor($months / 12) . ' year(s) left'
                        : $months . ' months left';
                } else {
                    $time_left_label = 'Expired';
                }
            }
        } elseif ($effective_license && $effective_license->is_lifetime()) {
            $expires_label   = 'Lifetime';
            $time_left_label = 'Never expires';
        }

        $support_label    = 'Priority';
        $support_sublabel = '24/7 Support';

        $license_key_masked = '';
        if ($effective_license && !empty($effective_license->secret_key)) {
            $license_key_masked = $this->mask_license_key($effective_license->secret_key);
        }
        $license_id = '';
        if ($effective_license && isset($effective_license->id) && $effective_license->id) {
            $license_id = '#' . $effective_license->id;
        }

        $activation_date = $site && !empty($site->created) ? $this->format_date($site->created) : '';
        $version_label   = $version ? 'v' . $version : '';

        $license_owner = $this->get_license_owner_user($effective_license);
        $fallback_user = $this->get_fs_internal_user() ?: $user;
        $account_user  = $license_owner ?: $fallback_user;
        $email         = $account_user && !empty($account_user->email) ? $account_user->email : '';
        $customer_id   = '';
        if ($account_user && isset($account_user->customer_id) && (string) $account_user->customer_id !== '') {
            $customer_id = '#' . $account_user->customer_id;
        }

        $purchase_date = '';
        if ($effective_license && !empty($effective_license->created)) {
            $purchase_date = $this->format_date($effective_license->created);
        }

        $auto_renewal = true;

        return [
            'is_active'          => $is_active,
            'has_pro_plan'       => LicenseCapabilities::site_has_active_pro_plan(),
            'status_label'       => $status_label,
            'plan_name'          => $plan_name,
            'plan_title'         => $plan_title,
            'sites_label'        => $sites_label,
            'expires_at'         => $expires_at,
            'expires_label'      => $expires_label,
            'time_left_label'    => $time_left_label,
            'support_label'      => $support_label,
            'support_sublabel'   => $support_sublabel,
            'license_key_masked' => $license_key_masked,
            'license_id'         => $license_id,
            'activation_date'    => $activation_date,
            'sites_used'         => $sites_used,
            'sites_quota'        => $sites_quota,
            'version'            => $version_label,
            'email'              => $email,
            'customer_id'        => $customer_id,
            'purchase_date'      => $purchase_date,
            'auto_renewal'       => $auto_renewal,
            'upgrade_url'        => $upgrade_url ?: '',
            'account_url'        => $account_url ?: '',
            'support_url'        => $support_url ?: '',
            'docs_url'           => ''
        ];
    }

    /**
     * Default payload when not connected or no license.
     *
     * @param bool   $is_active
     * @param string $status_label
     * @return array<string, mixed>
     */
    private function default_payload(bool $is_active, string $status_label): array
    {
        $upgrade_url = '';
        $account_url = '';
        $support_url = '';
        if (function_exists('emt_fs')) {
            $fs          = emt_fs();
            $upgrade_url = $fs->get_upgrade_url();
            $account_url = $fs->get_account_url();
            $support_url = $fs->get_support_forum_url();
        }
        $version = function_exists('emt_fs') ? 'v' . emt_fs()->get_plugin_version() : '';

        return [
            'is_active'          => $is_active,
            'has_pro_plan'       => LicenseCapabilities::site_has_active_pro_plan(),
            'status_label'       => $status_label,
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
            'version'            => $version,
            'email'              => '',
            'customer_id'        => '',
            'purchase_date'      => '',
            'auto_renewal'       => false,
            'upgrade_url'        => $upgrade_url ?: '',
            'account_url'        => $account_url ?: '',
            'support_url'        => $support_url ?: '',
            'docs_url'           => ''
        ];
    }

    /**
     * Format date for display.
     *
     * @param string $date
     * @return string
     */
    private function format_date(string $date): string
    {
        $ts = strtotime($date);

        return $ts ? gmdate('M j, Y', $ts) : '';
    }

    /**
     * Get the SDK internal _license object (has secret_key, id, created when set on activation).
     *
     * @return \FS_Plugin_License|null
     */
    private function get_fs_internal_license()
    {
        if (!function_exists('emt_fs')) {
            return;
        }
        $fs = emt_fs();
        try {
            $prop = new \ReflectionProperty($fs, '_license');
            $prop->setAccessible(true);
            $license = $prop->getValue($fs);

            return is_object($license) ? $license : null;
        } catch (\Throwable $e) {
            return;
        }
    }

    /**
     * Get the SDK internal _user object (Freemius account email and customer_id, not WP admin).
     *
     * @return \FS_User|null
     */
    private function get_fs_internal_user()
    {
        if (!function_exists('emt_fs')) {
            return;
        }
        $fs = emt_fs();
        try {
            $prop = new \ReflectionProperty($fs, '_user');
            $prop->setAccessible(true);
            $user = $prop->getValue($fs);

            return is_object($user) ? $user : null;
        } catch (\Throwable $e) {
            return;
        }
    }

    /**
     * Get the license owner (purchaser) from SDK cache by license user_id. Returns the Freemius
     * account that owns the license (email and customer_id), not the connected WP user.
     *
     * @param \FS_Plugin_License|null $license
     * @return \FS_User|null
     */
    private function get_license_owner_user($license)
    {
        if (!function_exists('emt_fs') || !$license || empty($license->user_id)) {
            return;
        }
        $fs = emt_fs();
        try {
            $method = new \ReflectionMethod($fs, '_get_user_by_id');
            $method->setAccessible(true);
            $user = $method->invoke(null, $license->user_id);

            return is_object($user) ? $user : null;
        } catch (\Throwable $e) {
            return;
        }
    }

    /**
     * Mask license key for display (e.g. ANXT-****-****-****-A1B2).
     *
     * @param string $key
     * @return string
     */
    private function mask_license_key(string $key): string
    {
        $len = strlen($key);
        if ($len <= 8) {
            return str_repeat('*', min(4, $len));
        }

        return substr($key, 0, 4) . '-****-****-****-' . substr($key, -4);
    }

    /**
     * POST /license/activate: activate with license_key in body.
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function rest_activate_license(WP_REST_Request $request): WP_REST_Response
    {
        $license_key = trim((string) $request->get_param('license_key'));
        if ($license_key === '') {
            return new WP_REST_Response([
                'success' => false,
                'message' => 'License key is required.'
            ], 400);
        }

        if (!function_exists('emt_fs')) {
            return new WP_REST_Response([
                'success' => false,
                'message' => 'License service is not available.'
            ], 503);
        }

        $fs = emt_fs();
        try {
            $ref = new \ReflectionMethod($fs, 'activate_license');
            $ref->setAccessible(true);
            $result = $ref->invoke($fs, $license_key, [], null, null, null, null, null, null, null);
        } catch (\Throwable $e) {
            return new WP_REST_Response([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }

        if (!is_array($result)) {
            return new WP_REST_Response([
                'success' => false,
                'message' => 'Activation failed.'
            ], 200);
        }

        if (!empty($result['success'])) {
            $data = $this->build_license_payload();

            return new WP_REST_Response([
                'success' => true,
                'message' => 'License activated successfully.',
                'data'    => $data
            ]);
        }

        $message = isset($result['error']) ? $result['error'] : 'Activation failed.';

        return new WP_REST_Response([
            'success' => false,
            'message' => $message
        ], 200);
    }

    /**
     * POST /license/deactivate: deactivate current license.
     *
     * @return WP_REST_Response
     */
    public function rest_deactivate_license(): WP_REST_Response
    {
        if (!function_exists('emt_fs')) {
            return new WP_REST_Response([
                'success' => false,
                'message' => 'License service is not available.'
            ], 503);
        }

        $fs = emt_fs();
        if (!$fs->is_registered()) {
            return new WP_REST_Response([
                'success' => true,
                'message' => 'No active license.'
            ]);
        }

        $site = $fs->get_site();
        if (!$site || !isset($site->license_id) || !$site->license_id) {
            return new WP_REST_Response([
                'success' => true,
                'message' => 'No active license.'
            ]);
        }

        try {
            $ref = new \ReflectionMethod($fs, '_deactivate_license');
            $ref->setAccessible(true);
            $ref->invoke($fs, false);
        } catch (\Throwable $e) {
            return new WP_REST_Response([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }

        $data = $this->build_license_payload();

        return new WP_REST_Response([
            'success' => true,
            'message' => 'License deactivated.',
            'data'    => $data
        ]);
    }

    /**
     * POST /license/check: sync and return same payload as GET.
     *
     * @return WP_REST_Response
     */
    public function rest_check_license(): WP_REST_Response
    {
        if (function_exists('emt_fs')) {
            $fs = emt_fs();
            if (method_exists($fs, '_sync_plugin_license')) {
                $ref = new \ReflectionMethod($fs, '_sync_plugin_license');
                $ref->setAccessible(true);
                $ref->invoke($fs, true, false);
            }
        }
        $data = $this->build_license_payload();

        return new WP_REST_Response(['success' => true, 'data' => $data]);
    }
}
