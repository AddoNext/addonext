<?php
namespace DragNext\Admin\Widgets;

use DragNext\Utils\OptionsHelper;
use WP_REST_Request;
use WP_REST_Response;

defined( 'ABSPATH' ) or exit;

class WidgetManager
{
    public function __construct()
    {
        add_action( 'rest_api_init', [ $this, 'register_endpoints' ] );
        add_action( 'elementor/elements/categories_registered', [ $this, 'register_category' ] );
    }



    public function rest_get_widgets(): array
    {
        return [
            'success' => true
         ];
    }

    /**
     * Register the 'dragnext' Elementor widget category
     *
     * @param \Elementor\Elements_Manager $elements_manager
     */
    public function register_category( $elements_manager )
    {
        $elements_manager->add_category(
            'dragnext',
            [
                'title' => __( 'DragNext', 'dragnext' ),
                'icon'  => 'fa fa-plug'
             ]
        );
    }

    public function register_endpoints(): void
    {
        register_rest_route( 'dragnext/v1', '/widgets', [
            'methods'  => 'GET',
            'callback' => [ $this, 'rest_get_widgets' ]
         ] );

        register_rest_route( 'dragnext/v1', '/update-widgets', [
            'methods'  => 'POST',
            'callback' => [ $this, 'rest_update_widgets' ]
         ] );
    }

    public static function set_active_widgets( array $widgets ): void
    {
        $all_widgets = self::get_widgets();
        foreach($widgets as $widget_id){

        }

        OptionsHelper::set_option( 'dragnext_active_widgets', $widgets );
    }

    public function rest_update_widgets( WP_REST_Request $request ): WP_REST_Response
    {
        $params = $request->get_params();

        self::set_active_widgets( $params[ 'widgets' ] );

        return new WP_REST_Response(
            [
                'success' => true,
                'message' => __( 'Widgets updated successfully', 'dragnext' )
             ]
        );
    }
}
