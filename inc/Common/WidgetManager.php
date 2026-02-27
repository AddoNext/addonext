<?php
namespace Elementrify\Common;

use Elementor\Widgets_Manager;
use Elementrify\Utils\OptionsHelper;
use Elementrify\Widgets\Button\Button;
use Elementrify\Widgets\Accordion\Accordion;

defined('ABSPATH') or exit;

/**
 * WidgetManager class
 *
 * @package Elementrify
 * @since 1.0.0
 */
class WidgetManager
{
    /**
     * The list of widgets to register
     *
     * @var array
     */
    protected static $widgets = [
        Accordion::class,
        Button::class
    ];

    /**
     * The list of categories to register
     *
     * @var array
     */
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

    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('elementor/elements/categories_registered', [$this, 'register_category']);
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
        add_action('elementor/editor/before_enqueue_scripts', [$this, 'enqueue_editor_badge_assets']);
    }

    /**
     * Enqueue the editor badge assets
     *
     * @return void
     */
    public function enqueue_editor_badge_assets(): void
    {
        wp_enqueue_style(
            'elementrify-editor-badge',
            ELEMENTRIFY_URL . 'assets/css/editor-badge.css',
            [],
            ELEMENTRIFY_VERSION
        );
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
                'title' => 'Elementrify',
                'icon'  => 'fa-layer-group'
            ]
        );
    }

    /**
     * Get the active widgets
     *
     * @return array
     */
    public static function get_active_widgets(): array
    {
        $widgets = OptionsHelper::get_option('active_widgets', []);

        return array_filter(self::$widgets, function ($widget) use ($widgets) {
            return in_array(emty_container($widget)->get_name(), $widgets);
        });
    }

    /**
     * Get the widgets
     *
     * @return array
     */
    public function get_widgets(): array
    {
        return self::$widgets;
    }

    /**
     * Get the categories
     *
     * @return array
     */
    public function get_categories(): array
    {
        return self::$categories;
    }

    /**
     * Register the widgets
     *
     * @param \Elementor\Widgets_Manager $widgets_manager
     */
    public function register_widgets(Widgets_Manager $widgets_manager): void
    {
        $widgets = self::get_active_widgets();
        foreach ($widgets as $widget) {
            if (class_exists('\Elementor\Plugin') && method_exists(\Elementor\Plugin::instance()->widgets_manager, 'register_widget_type')) {
                $widgets_manager->register(new $widget());
            }
        }
    }
}
