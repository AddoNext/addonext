<?php
namespace DragNext\Admin;

defined( 'ABSPATH' ) || die( 'No direct access allowed' );

class AdminController
{
    public function __construct()
    {
        add_action( 'admin_menu', [ $this, 'register_admin_menu' ] );
    }

    public function init(): void
    {
        new AdminAssets();
    }

    public function register_admin_menu(): void
    {
        add_menu_page(
            'DragNext',
            'DragNext',
            'manage_options',
            'dragnext',
            [ $this, 'render_admin_page' ],
            'dashicons-welcome-widgets-menus',
            100
        );
    }

    public function render_admin_page(): void
    {
        echo '<div id="dragnext-landing"></div>';
    }
}
