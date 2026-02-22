<?php
namespace Elementrify\Admin;

use WP_REST_Request;
use WP_REST_Response;
use Elementrify\Utils\OptionsHelper;
use Elementrify\Common\WidgetManager;

defined('ABSPATH') or exit;

class WidgetController
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_endpoints']);
        add_action('elementor/elements/categories_registered', [$this, 'register_category']);
    }

    public function rest_get_widgets(): WP_REST_Response
    {
        $widgets        = emty_container(WidgetManager::class)->get_widgets();
        $data           = [];
        $active_widgets = OptionsHelper::get_option('active_widgets', [array_keys($widgets)]);

        foreach ($widgets as $widget) {
            $widget_instance = emty_container($widget);
            $data[]          = [
                'id'             => $widget_instance->get_name(),
                'title'          => $widget_instance->get_title(),
                'icon'           => $widget_instance->get_icon(),
                'category'       => $widget_instance->get_category(),
                'admin_category' => method_exists($widget_instance, 'get_admin_category')
                    ? $widget_instance->get_admin_category()
                    : 'content',
                'active'         => in_array($widget_instance->get_name(), $active_widgets),
                'description'    => $widget_instance->get_description(),
                'brand_color'    => $widget_instance->get_brand_color()
            ];
        }

        $active = OptionsHelper::get_option('active_widgets', []);

        return new WP_REST_Response([
            'success'       => true,
            'widgets'       => $data,
            'activeWidgets' => is_array($active) ? $active : []
        ]);
    }

    /**
     * Register the 'elementrify' Elementor widget category
     *
     * @param \Elementor\Elements_Manager $elements_manager
     */
    public function register_category($elements_manager)
    {
        $elements_manager->add_category(
            'elementrify',
            [
                'title' => __('Elementrify', 'elementrify'),
                'icon'  => 'fa fa-plug'
            ]
        );
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

    public static function set_active_widgets(array $widgets): void
    {
        // $all_widgets = self::get_widgets();
        foreach ($widgets as $widget_id) {
        }

        OptionsHelper::set_option('elementrify_active_widgets', $widgets);
    }

    public function rest_update_widgets(WP_REST_Request $request): WP_REST_Response
    {
        $params = $request->get_params();

        self::set_active_widgets($params['widgets']);

        return new WP_REST_Response(
            [
                'success' => true,
                'message' => __('Widgets updated successfully', 'elementrify')
            ]
        );
    }
}
