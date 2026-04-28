<?php
namespace Elementrify\Utils;

defined('ABSPATH') || exit;

/**
 * Applies runtime debug behavior from settings.
 */
class DebugModeManager
{
    public function __construct()
    {
        add_action('plugins_loaded', [$this, 'apply_debug_settings'], 1);
    }

    /**
     * Apply debug mode, display and log settings at runtime.
     *
     * @return void
     */
    public function apply_debug_settings(): void
    {
        $is_debug_mode_enabled    = (bool) OptionsHelper::get_option('debug_mode', false);
        $is_debug_display_enabled = (bool) OptionsHelper::get_option('debug_display', false);
        $is_debug_log_enabled     = (bool) OptionsHelper::get_option('debug_log', true);

        if (!defined('WP_DEBUG')) {
            define('WP_DEBUG', $is_debug_mode_enabled);
        }
        if (!defined('WP_DEBUG_DISPLAY')) {
            define('WP_DEBUG_DISPLAY', $is_debug_mode_enabled && $is_debug_display_enabled);
        }
        if (!defined('WP_DEBUG_LOG')) {
            define('WP_DEBUG_LOG', $is_debug_mode_enabled && $is_debug_log_enabled);
        }

        if (!$is_debug_mode_enabled) {
            return;
        }

        error_reporting(E_ALL);
        @ini_set('display_errors', $is_debug_display_enabled ? '1' : '0');
        @ini_set('log_errors', $is_debug_log_enabled ? '1' : '0');

        if ($is_debug_log_enabled) {
            @ini_set('error_log', WP_CONTENT_DIR . '/debug.log');
        }
    }
}
