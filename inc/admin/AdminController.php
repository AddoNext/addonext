<?php
namespace Elementrify\Admin;

defined( 'ABSPATH' ) || die( 'No direct access allowed' );

use Elementrify\Common\WidgetController;

class AdminController
{
    public function __construct()
    {
        add_action( 'admin_menu', [ $this, 'register_admin_menu' ] );
        add_action( 'admin_init', [ $this, 'init' ] );
    }

    public function init(): void
    {
        emty_container(AdminAssets::class);
        emty_container(WidgetController::class);
    }

    public function register_admin_menu(): void
    {
        add_menu_page(
            'Elementrify',
            'Elementrify',
            'manage_options',
            'elementrify',
            [ $this, 'render_admin_page' ],
            'dashicons-welcome-widgets-menus',
            100
        );
    }

    public function render_admin_page(): void
    {
        echo '<div id="elementrify-admin"></div>';
    }
}
