<?php
namespace Veloria\Utils;

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
    }
}
