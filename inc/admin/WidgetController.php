<?php
namespace Elementrify\Admin;

use WP_REST_Request;
use WP_REST_Response;
use Elementrify\Utils\DataProcessor;
use Elementrify\Utils\OptionsHelper;
use Elementrify\Common\WidgetManager;

defined('ABSPATH') or exit;

class WidgetController
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_endpoints']);
    }

    public function rest_get_widgets(): WP_REST_Response
    {
        $widgets        = emty_container(WidgetManager::class)->get_widgets();
        $data           = [];
        $active_widgets = OptionsHelper::get_option('active_widgets', []);

        foreach ($widgets as $widget) {
            $widget_instance = emty_container($widget);
            $data[]          = [
                'id'          => $widget_instance->get_name(),
                'title'       => $widget_instance->get_title(),
                'icon'        => $widget_instance->get_icon(),
                'category'    => DataProcessor::get_category_name($widget_instance->get_category()[0]),
                'active'      => in_array($widget_instance->get_name(), $active_widgets),
                'description' => $widget_instance->get_description(),
                'brand_color' => $widget_instance->get_brand_color()
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
        register_rest_route('elementrify/v1', '/widgets', [
            'methods'  => 'GET',
            'callback' => [$this, 'rest_get_widgets']
        ]);

        register_rest_route('elementrify/v1', '/categories', [
            'methods'  => 'GET',
            'callback' => [$this, 'rest_get_categories']
        ]);

        register_rest_route('elementrify/v1', '/update-widgets', [
            'methods'  => 'POST',
            'callback' => [$this, 'rest_update_widgets']
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
                    'message' => __('Invalid widgets', 'elementrify')
                ],
                400
            );
        }

        OptionsHelper::set_option('active_widgets', $widgets);

        return new WP_REST_Response(
            [
                'success' => true,
                'message' => __('Widgets updated successfully', 'elementrify')
            ]
        );
    }
}
