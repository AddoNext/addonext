<?php
namespace Addonext\Pro\Migrations;

use Addonext\Base\MigrationBase;
use Addonext\Utils\OptionsHelper;
use Addonext\Common\WidgetManager;
use Addonext\Pro\Utils\PreviewAccess;

defined('ABSPATH') or exit;

/**
 * Migration to activate pro widgets
 *
 * @package AddoNext\Migrations
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
            if ($instance->is_preview_only()) {
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
            if ($instance->is_preview_only()) {
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
        return !PreviewAccess::is_preview_mode() && class_exists('Elementor\Plugin');
    }
}
