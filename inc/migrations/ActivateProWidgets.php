<?php
namespace Elementrify\Migrations;

use Elementrify\Base\MigrationBase;
use Elementrify\Utils\OptionsHelper;
use Elementrify\Common\WidgetManager;
use Elementrify\Utils\LicenseCapabilities;

defined('ABSPATH') or exit;

/**
 * Migration to activate pro widgets
 *
 * @package Elementrify\Migrations
 */
class ActivateProWidgets extends MigrationBase
{
    /**
     * Run the migration
     *
     * @return void
     */
    public function up(): void
    {
        $pro_widgets   = [];
        $saved_widgets = OptionsHelper::get_option('active_widgets', []);

        $manager = emty_container(WidgetManager::class);
        foreach ($manager->get_widgets() as $widget_class) {
            $instance = emty_container($widget_class);
            if ($instance->get_required_plan() === 'pro') {
                $pro_widgets[] = $instance->get_name();
            }
        }

        OptionsHelper::set_option('active_widgets', array_unique(array_merge($saved_widgets, $pro_widgets)));
    }

    /**
     * Rollback the migration
     *
     * @return void
     */
    public function down(): void
    {
        $saved_widgets = OptionsHelper::get_option('active_widgets', []);
        $pro_widgets   = [];
        $manager       = emty_container(WidgetManager::class);
        foreach ($manager->get_widgets() as $widget_class) {
            $instance = emty_container($widget_class);
            if ($instance->get_required_plan() === 'pro') {
                $pro_widgets[] = $instance->get_name();
            }
        }

        OptionsHelper::set_option('active_widgets', array_diff($saved_widgets, $pro_widgets));
    }

    /**
     * Validate the migration
     *
     * @return bool
     */
    public function validate(): bool
    {
        return LicenseCapabilities::site_has_active_pro_plan();
    }
}
