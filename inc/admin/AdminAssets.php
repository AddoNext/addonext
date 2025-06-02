<?php

namespace DragNext\Admin;

defined('ABSPATH') or exit;

/**
 * Class AdminAssets
 * Enqueue admin assets
 * 
 * @package DragNext
 */
class AdminAssets
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue']);
    }

    public function enqueue(): void
    {
        wp_enqueue_style('dragnext-admin', DRAGNEXT_URL . 'dist/css/admin.min.css');
        wp_enqueue_script('dragnext-admin', DRAGNEXT_URL . 'dist/js/admin.min.js', [], DRAGNEXT_VERSION, true);
    }
}
