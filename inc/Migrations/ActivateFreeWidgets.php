<?php
namespace Veloria\Migrations;

use Veloria\Base\MigrationBase;
use Veloria\Utils\OptionsHelper;
use Veloria\Common\WidgetManager;

defined('ABSPATH') or exit;

/**
 * Migration to activate free widgets
 *
 * @package Veloria\Migrations
 */
class ActivateFreeWidgets extends MigrationBase
{
    /**
     * Run the migration
     *
     * @return void
     */
    public function up(): void
    {
        $free_widgets  = [];
        $saved_widgets = OptionsHelper::get_option('active_widgets', []);

        $manager = emty_container(WidgetManager::class);
        foreach ($manager->get_widgets() as $widget_class) {
            $instance = emty_container($widget_class);
            if ($instance->get_required_plan() !== 'pro') {
                $free_widgets[] = $instance->get_name();
            }
        }

        OptionsHelper::set_option('active_widgets', array_unique(array_merge($saved_widgets, $free_widgets)));
    }

    /**
     * Rollback the migration
     *
     * @return void
     */
    public function down(): void
    {
        $saved_widgets = OptionsHelper::get_option('active_widgets', []);
        $free_widgets  = [];
        $manager       = emty_container('WidgetManager');
        foreach ($manager->get_widgets() as $widget) {
            if ($widget->get_required_plan() !== 'pro') {
                $free_widgets[] = $widget->get_name();
            }
        }

        OptionsHelper::set_option('active_widgets', array_diff($saved_widgets, $free_widgets));
    }

    /**
     * Validate the migration
     *
     * @return bool
     */
    public function validate(): bool
    {
        return class_exists('Elementor\Plugin');
    }
}
