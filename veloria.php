<?php
/**
 * Plugin Name: Veloria Widgets for Elementor
 * Plugin URI: https://elementrify.com/
 * Description: Elementor widgets and extensions for WordPress: content blocks, maps, charts, marketing tools, and WooCommerce checkout components.
 * Version: 1.0.0
 * Author: Elementrify
 * Text Domain: veloria
 * Domain Path: /languages
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires PHP: 7.4
 * Requires at least: 6.0
 * Requires WP: 6.0
 */

defined('ABSPATH') || exit;

use Veloria\Common\WidgetManager;
use Veloria\Admin\AdminController;
use Veloria\Admin\WidgetController;
use Veloria\Utils\AssetPerformance;
use Veloria\Common\ExtensionManager;
use Veloria\Admin\SettingsController;
use Veloria\Admin\MigrationController;
use Veloria\Admin\ExtensionsController;
use Veloria\Admin\WidgetsAssetController;

require_once 'vendor/autoload.php';

if ( ! function_exists( 'emt_fs' ) ) {
    // Create a helper function for easy SDK access.
    function emt_fs() {
        global $emt_fs;

        if ( ! isset( $emt_fs ) ) {
            // Activate multisite network integration.
            if ( ! defined( 'WP_FS__PRODUCT_19063_MULTISITE' ) ) {
                define( 'WP_FS__PRODUCT_19063_MULTISITE', true );
            }

            // Include Freemius SDK.
            // SDK is auto-loaded through Composer

            $emt_fs = fs_dynamic_init( array(
                'id'                  => '19063',
                'slug'                => 'veloria',
                'premium_slug'        => 'veloria-pro',
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
                'menu'                => array(
                    'slug'           => 'veloria',
                    'first-path'     => 'admin.php?page=veloria',
                ),
            ) );
        }

        return $emt_fs;
    }

    // Init Freemius.
    emt_fs();
    // Signal that SDK was initiated.
    do_action( 'emt_fs_loaded' );
}

class Veloria
{
    public function __construct()
    {
        add_action('init', [$this, 'define_constants']);
        add_action('plugins_loaded', [$this, 'initialize_classes']);
    }

    public function define_constants(): void
    {
        if (!defined('VELORIA_VERSION')) {
            define('VELORIA_VERSION', '1.0.0');
        }
        if (!defined('VELORIA_DIR')) {
            define('VELORIA_DIR', plugin_dir_path(__FILE__));
        }
        if (!defined('VELORIA_URL')) {
            define('VELORIA_URL', plugin_dir_url(__FILE__));
        }
        if (!defined('VELORIA_FILE')) {
            define('VELORIA_FILE', __FILE__);
        }
        if (!defined('VELORIA_BASENAME')) {
            define('VELORIA_BASENAME', plugin_basename(__FILE__));
        }
        if (!defined('VELORIA')) {
            define('VELORIA', 'veloria');
        }
    }

    public static function init()
    {
        static $instance = null;

        if (null === $instance) {
            $instance = new Veloria();
        }

        return $instance;
    }

    public function initialize_classes(): void
    {
        $this->define_constants();
        emty_container(AdminController::class);
        emty_container(AssetPerformance::class);
        if (class_exists('\Elementor\Plugin')) {
            emty_container(WidgetManager::class);
            emty_container(WidgetController::class);
            emty_container(WidgetsAssetController::class);
            emty_container(ExtensionManager::class);
            emty_container(ExtensionsController::class);
        }

        emty_container(SettingsController::class);
        emty_container(MigrationController::class);

        if (file_exists(VELORIA_DIR . 'pro/register.php')) {
            require_once VELORIA_DIR . 'pro/register.php';
            if (function_exists('veloria_bootstrap_pro')) {
                veloria_bootstrap_pro();
            }
        }

        if (!defined('VELORIA_PRO_ACCOUNT_BOOTSTRAPPED')) {
            emty_container(\Veloria\Admin\FreeAccountController::class);
        }
    }
}

Veloria::init();
