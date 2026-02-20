<?php

namespace Elementrify\Admin;

defined('ABSPATH') or exit;

/**
 * Class AdminAssets
 * Enqueue admin assets
 * 
 * @package Elementrify
 */
class AdminAssets
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue']);
    }

    public function enqueue(): void
    {
        wp_enqueue_style('elementrify-admin', ELEMENTRIFY_URL . 'dist/css/admin.min.css');
        wp_enqueue_script('elementrify-admin', ELEMENTRIFY_URL . 'dist/js/admin.min.js', [
            'wp-element',
            'wp-i18n',
            'wp-api-fetch',
            'wp-data'
        ], ELEMENTRIFY_VERSION, true);
    }
}
