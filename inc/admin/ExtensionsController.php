<?php
namespace Elementrify\Admin;

use WP_REST_Request;
use WP_REST_Response;
use Elementrify\Utils\OptionsHelper;
use Elementrify\Common\ExtensionManager;

defined('ABSPATH') or exit;

/**
 * Extensions Controller: REST API for extensions data and actions
 *
 * @package Elementrify
 * @since 1.0.0
 */
class ExtensionsController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_endpoints']);
    }

    /**
     * Register REST endpoints for extensions
     *
     * @return void
     */
    public function register_endpoints(): void
    {
        register_rest_route('elementrify/v1', '/extensions', [
            'methods'             => 'GET',
            'callback'            => [$this, 'rest_get_extensions'],
            'permission_callback' => [$this, 'check_permission']
        ]);

        register_rest_route('elementrify/v1', '/extensions', [
            'methods'             => 'POST',
            'callback'            => [$this, 'rest_update_extensions'],
            'permission_callback' => [$this, 'check_permission']
        ]);
    }

    /**
     * Get all available extensions
     *
     * @return WP_REST_Response
     */
    public function rest_get_extensions(): WP_REST_Response
    {
        $extensions = emty_container(ExtensionManager::class)->get_extensions();

        return new WP_REST_Response([
            'success'    => true,
            'extensions' => $extensions
        ]);
    }

    /**
     * Update active extensions list
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function rest_update_extensions(WP_REST_Request $request): WP_REST_Response
    {
        $extensions = $request->get_param('extensions');

        if (empty($extensions) || !is_array($extensions)) {
            return new WP_REST_Response(
                [
                    'success' => false,
                    'message' => __('Invalid extensions', 'elementrify')
                ],
                400
            );
        }

        OptionsHelper::set_option('active_extensions', $extensions);

        return new WP_REST_Response([
            'success' => true,
            'message' => __('Extensions updated successfully', 'elementrify')
        ]);
    }
}
