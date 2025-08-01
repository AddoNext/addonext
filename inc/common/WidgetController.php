<?php
namespace DragNext\Admin\Widgets;

use Elementor\Widgets_Manager;
use DragNext\Utils\OptionsHelper;
use DragNext\Widgets\Button\Button;
use DragNext\Widgets\Accordion\Accordion;

defined( 'ABSPATH' ) or exit;

class WidgetController
{
    protected static $widgets = [
        'Accordion' => Accordion::class,
        'Button'    => Button::class
     ];

    public function __construct()
    {
    }

    public static function get_active_widgets(): array
    {
        $widgets = OptionsHelper::get_option( 'dragnext_active_widgets', [  ] );

        return array_filter( self::$widgets, function ( $widget ) use ( $widgets ) {
            return in_array( $widget, $widgets );
        } );
    }

    public function get_widget_ids(): array
    {
        return array_keys( self::$widgets );
    }

    public function get_widgets(): array
    {
        return self::$widgets;
    }

    public function register( Widgets_Manager $widgets_manager ): void
    {
        $widgets = [

         ];

        foreach ( $widgets as $widget_name => $widget ) {
            if ( class_exists( '\Elementor\Plugin' ) && method_exists( \Elementor\Plugin::instance()->widgets_manager, 'register_widget_type' ) ) {
                $widgets_manager->register( new $widget() );
            }

        }

    }

}
