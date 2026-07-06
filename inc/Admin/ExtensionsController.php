<?php
namespace Addonext\Admin;

use WP_REST_Request;
use WP_REST_Response;
use Addonext\Utils\OptionsHelper;
use Addonext\Common\ExtensionManager;

defined('ABSPATH') or exit;

/**
 * Extensions Controller: REST API for extensions data and actions
 *
 * @package AddoNext
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
        register_rest_route('addonext/v1', '/extensions', [
            'methods'             => 'GET',
            'callback'            => [$this, 'rest_get_extensions'],
            'permission_callback' => [$this, 'check_permission']
        ]);

        register_rest_route('addonext/v1', '/extensions', [
            'methods'             => 'POST',
            'callback'            => [$this, 'rest_update_extensions'],
            'permission_callback' => [$this, 'check_permission']
        ]);

        register_rest_route(
            'addonext/v1',
            '/extensions/(?P<id>[a-z0-9\\-]+)/settings',
            [
                'methods'             => ['GET', 'POST'],
                'callback'            => [$this, 'rest_extension_settings_dispatch'],
                'permission_callback' => [$this, 'check_permission'],
            ]
        );
    }

    /**
     * Route GET vs POST for extension settings
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function rest_extension_settings_dispatch(WP_REST_Request $request): WP_REST_Response
    {
        if (strtoupper($request->get_method()) === 'GET') {
            return $this->rest_get_extension_settings($request);
        }

        return $this->rest_update_extension_settings($request);
    }

    /**
     * Whether the current user may manage extensions
     *
     * @return bool
     */
    public function check_permission(): bool
    {
        return current_user_can('manage_options');
    }

    /**
     * Get all available extensions
     *
     * @return WP_REST_Response
     */
    public function rest_get_extensions(): WP_REST_Response
    {
        $extensions = emty_container(ExtensionManager::class)->get_extensions_catalog();

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

        if (!is_array($extensions)) {
            return new WP_REST_Response(
                [
                    'success' => false,
                    'message' => __('Invalid extensions', 'addonext')
                ],
                400
            );
        }

        $manager = emty_container(ExtensionManager::class);
        $clean   = $manager->sanitize_active_extension_ids($extensions);

        OptionsHelper::set_option('active_extensions', $clean);

        return new WP_REST_Response([
            'success'    => true,
            'message'    => __('Extensions updated successfully', 'addonext'),
            'extensions' => $manager->get_extensions_catalog()
        ]);
    }

    /**
     * Get one extension's settings (merged with defaults)
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function rest_get_extension_settings(WP_REST_Request $request): WP_REST_Response
    {
        $id      = (string) $request->get_param('id');
        $manager = emty_container(ExtensionManager::class);
        $class   = $manager->get_extension_class_for_id($id);

        if ($class === null) {
            return new WP_REST_Response(['success' => false, 'message' => __('Unknown extension', 'addonext')], 404);
        }

        $instance = emty_container($class);

        if (!$instance->has_settings()) {
            return new WP_REST_Response(['success' => false, 'message' => __('This extension has no settings', 'addonext')], 400);
        }

        return new WP_REST_Response([
            'success'  => true,
            'id'       => $id,
            'fields'   => $instance->get_fields(),
            'settings' => $manager->get_extension_settings_payload($id),
        ]);
    }

    /**
     * Save settings for one extension
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function rest_update_extension_settings(WP_REST_Request $request): WP_REST_Response
    {
        $id      = (string) $request->get_param('id');
        $manager = emty_container(ExtensionManager::class);
        $class   = $manager->get_extension_class_for_id($id);

        if ($class === null) {
            return new WP_REST_Response(['success' => false, 'message' => __('Unknown extension', 'addonext')], 404);
        }

        $instance = emty_container($class);

        if (!$instance->has_settings()) {
            return new WP_REST_Response(['success' => false, 'message' => __('This extension has no settings', 'addonext')], 400);
        }

        $raw = $request->get_param('settings');

        if (!is_array($raw)) {
            return new WP_REST_Response(
                ['success' => false, 'message' => __('Invalid settings', 'addonext')],
                400
            );
        }

        $clean = $instance->sanitize_settings($raw);
        $manager->save_extension_settings($id, $clean);

        return new WP_REST_Response([
            'success'  => true,
            'message'  => __('Settings saved', 'addonext'),
            'id'       => $id,
            'settings' => $manager->get_extension_settings_payload($id),
        ]);
    }
}
