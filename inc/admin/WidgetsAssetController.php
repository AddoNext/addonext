<?php
namespace Elementrify\Admin;

defined('ABSPATH') || exit;

/**
 * WidgetsAssetController class
 *
 * @package Elementrify
 * @subpackage Admin
 * @since 1.0.0
 */
class WidgetsAssetController
{
    /**
     * The list of styles to register
     *
     * @var array
     */
    private array $styles = [
        [
            'handle' => 'elementrify-advanced-heading',
            'file'   => 'assets/css/advanced-heading.css'
        ],
        [
            'handle' => 'elementrify-advanced-tabs',
            'file'   => 'assets/css/advanced-tabs.css'
        ]
    ];

    private array $scripts = [
        [
            'handle' => 'elementrify-advanced-tabs',
            'file'   => 'assets/js/advanced-tabs.js',
            'footer' => true
        ]
    ];

    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('elementor/frontend/after_register_styles', [$this, 'register_styles'], 20);
        add_action('elementor/editor/before_enqueue_scripts', [$this, 'register_styles'], 20);
        add_action('elementor/frontend/after_register_scripts', [$this, 'register_scripts'], 20);
        add_action('elementor/editor/before_enqueue_scripts', [$this, 'register_scripts'], 21);
    }

    /**
     * Register the styles
     */
    public function register_styles(): void
    {
        if (!defined('ELEMENTRIFY_URL') || !defined('ELEMENTRIFY_VERSION')) {
            return;
        }
        foreach ($this->styles as $asset) {
            wp_register_style(
                $asset['handle'],
                ELEMENTRIFY_URL . $asset['file'],
                [],
                ELEMENTRIFY_VERSION
            );
        }
    }

    /**
     * Register the scripts
     */
    public function register_scripts(): void
    {
        if (!defined('ELEMENTRIFY_URL') || !defined('ELEMENTRIFY_VERSION')) {
            return;
        }
        foreach ($this->scripts as $asset) {
            wp_register_script(
                $asset['handle'],
                ELEMENTRIFY_URL . $asset['file'],
                [],
                ELEMENTRIFY_VERSION,
                $asset['footer'] ?? true
            );
        }
    }
}
