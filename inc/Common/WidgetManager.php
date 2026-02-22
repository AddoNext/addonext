<?php
namespace Elementrify\Common;

use Elementor\Widgets_Manager;
use Elementrify\Utils\OptionsHelper;
use Elementrify\Widgets\Button\Button;
use Elementrify\Widgets\Accordion\Accordion;

defined('ABSPATH') or exit;

class WidgetManager
{
    protected static $widgets = [
        Accordion::class,
        Button::class
    ];

    protected static $categories = [
        'content'   => [
            'title' => 'Content Widgets',
            'icon'  => 'fa-layer-group'
        ],
        'creative'  => [
            'title' => 'Creative Widgets',
            'icon'  => 'fa-palette'
        ],
        'marketing' => [
            'title' => 'Marketing Widgets',
            'icon'  => 'fa-bullhorn'
        ],
        'data'      => [
            'title' => 'Data & Chart Widgets',
            'icon'  => 'fa-chart-pie'
        ]
    ];

    public function __construct()
    {
    }

    public static function get_active_widgets(): array
    {
        $widgets = OptionsHelper::get_option('elementrify_active_widgets', []);

        return array_filter(self::$widgets, function ($widget) use ($widgets) {
            return in_array($widget, $widgets);
        });
    }

    public function get_widgets(): array
    {
        return self::$widgets;
    }

    public function get_categories(): array
    {
        return self::$categories;
    }

    public function register(Widgets_Manager $widgets_manager): void
    {
        foreach (self::$widgets as $widget) {
            if (class_exists('\Elementor\Plugin') && method_exists(\Elementor\Plugin::instance()->widgets_manager, 'register_widget_type')) {
                $widgets_manager->register(new $widget());
            }
        }
    }
}
