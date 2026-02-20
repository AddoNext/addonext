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

use Elementrify\Admin\AdminController;

require_once 'vendor/autoload.php';

class Elementrify
{
    public function __construct()
    {
        add_action( 'init', [ $this, 'define_constants' ] );
        add_action( 'plugins_loaded', [ $this, 'initialize_classes' ] );
    }

    public function define_constants(): void
    {
        define( 'ELEMENTRIFY_VERSION', '1.0.0' );
        define( 'ELEMENTRIFY_DIR', plugin_dir_path( __FILE__ ) );
        define( 'ELEMENTRIFY_URL', plugin_dir_url( __FILE__ ) );
        define( 'ELEMENTRIFY_FILE', __FILE__ );
        define( 'ELEMENTRIFY_BASENAME', plugin_basename( __FILE__ ) );
        define( 'ELEMENTRIFY', 'elementrify' );
    }

    public static function init()
    {
        static $instance = null;

        if ( null === $instance ) {
            $instance = new Elementrify();
        }

        return $instance;
    }

    public function initialize_classes(): void
    {
        emty_container(AdminController::class);
    }
}

Elementrify::init();
