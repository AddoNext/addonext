<?php
/**
 * Plugin Name: Elementrify
 * Plugin URI: https://elementrify.com/
 * Description: A collection of Elementor page builder widgets and extensions for WordPress. Including a 360 image viewer, a 3D model viewer, a PDF viewer, a map widget and a more advanced version of the Elementor's Accordion widget.
 * Version: 1.0.0
 * Author: AddoNext
 * Author URI: https://elementrify.com/
 * Text Domain: elementrify
 * Domain Path: /languages
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires PHP: 7.4
 * Requires at least: 6.0
 * Requires WP: 6.0
 */

use Elementrify\Common\WidgetManager;
use Elementrify\Common\ExtensionManager;
use Elementrify\Common\GlobalCustomAssets;
use Elementrify\Admin\AdminController;
use Elementrify\Admin\WidgetController;
use Elementrify\Admin\LicenseController;
use Elementrify\Admin\SettingsController;
use Elementrify\Admin\ExtensionsController;
use Elementrify\Admin\WidgetsAssetController;

require_once 'vendor/autoload.php';

if (!function_exists('emt_fs')) {
    // Create a helper function for easy SDK access.
    function emt_fs()
    {
        global $emt_fs;

        if (!isset($emt_fs)) {
            // Activate multisite network integration.
            if (!defined('WP_FS__PRODUCT_19063_MULTISITE')) {
                define('WP_FS__PRODUCT_19063_MULTISITE', true);
            }

            // Include Freemius SDK.
            // SDK is auto-loaded through Composer

            $emt_fs = fs_dynamic_init([
                'id'                  => '19063',
                'slug'                => 'elementrify',
                'premium_slug'        => 'dragnext-premium',
                'type'                => 'plugin',
                'public_key'          => 'pk_213c59173633a027d6e134676b4ba',
                'is_premium'          => true,
                'premium_suffix'      => 'Pro',
                // If your plugin is a serviceware, set this option to false.
                'has_premium_version' => true,
                'has_addons'          => false,
                'has_paid_plans'      => true,
                'is_org_compliant'    => true,
                // Automatically removed in the free version. If you're not using the
                // auto-generated free version, delete this line before uploading to wp.org.
                'wp_org_gatekeeper'   => 'OA7#BoRiBNqdf52FvzEf!!074aRLPs8fspif$7K1#4u4Csys1fQlCecVcUTOs2mcpeVHi#C2j9d09fOTvbC0HloPT7fFee5WdS3G',
                'trial'               => [
                    'days'               => 3,
                    'is_require_payment' => true
                ],
                'menu'                => [
                    'slug'       => 'elementrify',
                    'first-path' => 'admin.php?page=elementrify&tab=setup-wizard'
                ]
            ]);
        }

        return $emt_fs;
    }

    // Init Freemius.
    emt_fs();
    // Signal that SDK was initiated.
    do_action('emt_fs_loaded');
}

class Elementrify
{
    public function __construct()
    {
        add_action('init', [$this, 'define_constants']);
        add_action('plugins_loaded', [$this, 'initialize_classes']);
    }

    public function define_constants(): void
    {
        define('ELEMENTRIFY_VERSION', '1.0.0');
        define('ELEMENTRIFY_DIR', plugin_dir_path(__FILE__));
        define('ELEMENTRIFY_URL', plugin_dir_url(__FILE__));
        define('ELEMENTRIFY_FILE', __FILE__);
        define('ELEMENTRIFY_BASENAME', plugin_basename(__FILE__));
        define('ELEMENTRIFY', 'elementrify');
    }

    public static function init()
    {
        static $instance = null;

        if (null === $instance) {
            $instance = new Elementrify();
        }

        return $instance;
    }

    public function initialize_classes(): void
    {
        emty_container(AdminController::class);
        emty_container(GlobalCustomAssets::class);

        if (class_exists('\Elementor\Plugin')) {
            emty_container(WidgetManager::class);
            emty_container(WidgetController::class);
            emty_container(WidgetsAssetController::class);
            emty_container(ExtensionManager::class);
            emty_container(ExtensionsController::class);
        }

        emty_container(SettingsController::class);
        emty_container(LicenseController::class);
    }
}

Elementrify::init();
