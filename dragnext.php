<?php
/**
 * Plugin Name: DragNext
 * Plugin URI: https://dragnext.com/
 * Description: A collection of Elementor page builder widgets and extensions for WordPress. Including a 360 image viewer, a 3D model viewer, a PDF viewer, a map widget and a more advanced version of the Elementor's Accordion widget.
 * Version: 1.0.0
 * Author: DragNext Plugins
 * Author URI: https://dragnext.com/
 * Text Domain: dragnext
 * Domain Path: /languages
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires PHP: 7.4
 * Requires at least: 6.0
 * Requires WP: 6.0
 */

use DragNext\Admin\AdminController;

require_once 'vendor/autoload.php';

class DragNext
{
    public function __construct()
    {
        add_action( 'init', [ $this, 'define_constants' ] );
        add_action( 'plugins_loaded', [ $this, 'initialize_classes' ] );
    }

    public function define_constants(): void
    {
        define( 'DRAGNEXT_VERSION', '1.0.0' );
        define( 'DRAGNEXT_DIR', plugin_dir_path( __FILE__ ) );
        define( 'DRAGNEXT_URL', plugin_dir_url( __FILE__ ) );
        define( 'DRAGNEXT_FILE', __FILE__ );
        define( 'DRAGNEXT_BASENAME', plugin_basename( __FILE__ ) );
        define( 'DRAGNEXT', 'dragnext' );
    }

    public static function init()
    {
        static $instance = null;

        if ( null === $instance ) {
            $instance = new DragNext();
        }

        return $instance;
    }

    public function initialize_classes(): void
    {
        dgn_container(AdminController::class);
    }
}

DragNext::init();
