<?php
namespace Veloria\Admin;

use WP_REST_Request;
use WP_REST_Response;
use Veloria\Utils\OptionsHelper;
use Veloria\Utils\LicenseCapabilities;
use Veloria\Common\WidgetManager;

defined('ABSPATH') or exit;

class WidgetController
{
    /**
     * Drops pro-only widget ids when the site does not have an active pro license.
     *
     * @param array<int, mixed> $widget_ids
     * @return array<int, string>
     */
    public static function sanitize_active_widget_ids(array $widget_ids): array
    {
        $pro_access = LicenseCapabilities::site_has_active_pro_plan();
        if ($pro_access) {
            return array_values(
                array_unique(
                    array_map(
                        static function ($id): string {
                            return sanitize_text_field((string) $id);
                        },
                        $widget_ids
                    )
                )
            );
        }

        $manager  = emty_container(WidgetManager::class);
        $allowed  = [];
        foreach ($manager->get_widgets() as $widget_class) {
            $instance = emty_container($widget_class);
            if ($instance->get_required_plan() !== 'pro') {
                $allowed[] = $instance->get_name();
            }
        }

        $allowed_set = array_fill_keys($allowed, true);
        $out         = [];

        foreach ($widget_ids as $id) {
            $clean = sanitize_text_field((string) $id);
            if ($clean !== '' && isset($allowed_set[$clean])) {
                $out[] = $clean;
            }
        }

        return array_values(array_unique($out));
    }

    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_endpoints']);
    }

    public function rest_get_widgets(): WP_REST_Response
    {
        $widgets        = emty_container(WidgetManager::class)->get_widgets();
        $data           = [];
        $active_widgets = OptionsHelper::get_option('active_widgets', []);
        $active_widgets = is_array($active_widgets) ? $active_widgets : [];
        $sanitized      = self::sanitize_active_widget_ids($active_widgets);

        if ($sanitized !== $active_widgets) {
            OptionsHelper::set_option('active_widgets', $sanitized);
            $active_widgets = $sanitized;
        }

        $pro_access = LicenseCapabilities::site_has_active_pro_plan();

        foreach ($widgets as $widget) {
            $widget_instance = emty_container($widget);
            $required_plan   = $widget_instance->get_required_plan();
            $is_locked       = ($required_plan === 'pro') && !$pro_access;
            $name            = $widget_instance->get_name();
            $stored_active   = in_array($name, $active_widgets, true);
            $data[]          = [
                'id'             => $name,
                'title'          => $widget_instance->get_title(),
                'icon'           => $widget_instance->get_icon(),
                'category'       => $widget_instance->get_admin_category(),
                'active'         => $stored_active && !$is_locked,
                'description'    => $widget_instance->get_description(),
                'brand_color'    => $widget_instance->get_brand_color(),
                'required_plan'  => $required_plan,
                'is_locked'      => $is_locked,
            ];
        }

        $active = OptionsHelper::get_option('active_widgets', []);

        return new WP_REST_Response([
            'success'       => true,
            'widgets'       => $data,
            'activeWidgets' => is_array($active) ? $active : []
        ]);
    }

    public function register_endpoints(): void
    {
        register_rest_route('veloria/v1', '/widgets', [
            'methods'  => 'GET',
            'callback' => [$this, 'rest_get_widgets'],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route('veloria/v1', '/categories', [
            'methods'  => 'GET',
            'callback' => [$this, 'rest_get_categories'],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route('veloria/v1', '/update-widgets', [
            'methods'  => 'POST',
            'callback' => [$this, 'rest_update_widgets'],
            'permission_callback' => '__return_true'
        ]);
    }

    public function rest_get_categories(): WP_REST_Response
    {
        $categories = emty_container(WidgetManager::class)->get_categories();

        return new WP_REST_Response([
            'success'    => true,
            'categories' => $categories
        ]);
    }

    public function rest_update_widgets(WP_REST_Request $request): WP_REST_Response
    {
        $widgets = $request->get_param('widgets');

        if (empty($widgets) || !is_array($widgets)) {
            return new WP_REST_Response(
                [
                    'success' => false,
                    'message' => __('Invalid widgets', 'veloria')
                ],
                400
            );
        }

        $sanitized = self::sanitize_active_widget_ids($widgets);
        OptionsHelper::set_option('active_widgets', $sanitized);

        return new WP_REST_Response(
            [
                'success' => true,
                'message' => __('Widgets updated successfully', 'veloria')
            ]
        );
    }
}
