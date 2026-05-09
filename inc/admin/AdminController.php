<?php
namespace Elementrify\Admin;

defined( 'ABSPATH' ) || die( 'No direct access allowed' );


class AdminController
{
    public function __construct()
    {
        add_action( 'admin_menu', [ $this, 'register_admin_menu' ] );
        add_action( 'admin_init', [ $this, 'init' ] );
        add_action( 'admin_notices', [ $this, 'render_elementor_dependency_notice' ] );
    }

    public function init(): void
    {
        emty_container(AdminAssets::class);
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
        $elementor_active = AdminAssets::is_elementor_plugin_active();
        echo '<div id="elementrify-admin" data-elementor-active="' . ($elementor_active ? '1' : '0') . '"></div>';
    }

    /**
     * Prints an error-style admin notice on all admin screens when Elementor is inactive.
     *
     * @return void
     */
    public function render_elementor_dependency_notice(): void
    {
        if (!current_user_can('activate_plugins')) {
            return;
        }

        if (AdminAssets::is_elementor_plugin_active()) {
            return;
        }

        $plugin_file = AdminAssets::ELEMENTOR_PLUGIN_FILE;

        if (AdminAssets::is_elementor_installed()) {
            $action_url = wp_nonce_url(
                admin_url('plugins.php?action=activate&plugin=' . rawurlencode($plugin_file)),
                'activate-plugin_' . $plugin_file
            );
            $message    = __('Elementrify is not working because you need to activate the Elementor plugin.', 'elementrify');
            $button     = __('Activate Elementor Now', 'elementrify');
        } else {
            $action_url = admin_url('plugin-install.php?s=elementor&tab=search&type=term');
            $message    = __('Elementrify requires the Elementor plugin. Install Elementor from WordPress.org to continue.', 'elementrify');
            $button     = __('Install Elementor', 'elementrify');
        }

        printf(
            '<div class="notice notice-error"><p>%s</p><p><a class="button button-primary" href="%s">%s</a></p></div>',
            esc_html($message),
            esc_url($action_url),
            esc_html($button)
        );
    }
}
