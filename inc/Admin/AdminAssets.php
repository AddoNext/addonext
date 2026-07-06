<?php

namespace Addonext\Admin;

defined('ABSPATH') or exit;

/**
 * Class AdminAssets
 * Enqueue admin assets
 * 
 * @package AddoNext
 */
class AdminAssets
{
    /** @var string Elementor plugin basename relative to wp-plugins. */
    public const ELEMENTOR_PLUGIN_FILE = 'elementor/elementor.php';

    /**
     * Registers admin asset hooks.
     */
    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue']);
    }

    /**
     * Whether the Elementor main plugin file exists under wp-content/plugins.
     *
     * @return bool
     */
    public static function is_elementor_installed(): bool
    {
        return file_exists(WP_PLUGIN_DIR . '/' . self::ELEMENTOR_PLUGIN_FILE);
    }

    /**
     * Whether the Elementor plugin is installed and active (WordPress plugin list).
     *
     * @return bool
     */
    public static function is_elementor_plugin_active(): bool
    {
        if (!function_exists('is_plugin_active')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        return is_plugin_active(self::ELEMENTOR_PLUGIN_FILE);
    }

    /**
     * Enqueues admin assets and passes runtime environment flags to React.
     *
     * @param string $hook_suffix Current admin screen hook.
     */
    public function enqueue(string $hook_suffix): void
    {
        if ($hook_suffix !== 'toplevel_page_addonext') {
            return;
        }

        wp_enqueue_style('addonext-admin', ADDONEXT_URL . 'dist/css/admin.min.css', [], ADDONEXT_VERSION);
        wp_enqueue_script('addonext-admin', ADDONEXT_URL . 'dist/js/admin.min.js', [
            'wp-element',
            'wp-i18n',
            'wp-api-fetch',
            'wp-data'
        ], ADDONEXT_VERSION, true);
        wp_localize_script(
            'addonext-admin',
            'addonextAdminData',
            [
                'isElementorActive' => self::is_elementor_plugin_active(),
                'hasProAccountApi'  => function_exists('addonext_has_pro_account_api') && addonext_has_pro_account_api(),
            ]
        );
    }
}
