<?php
namespace Elementrify\Common;

use Elementrify\Utils\OptionsHelper;
use Elementrify\Extensions\SmoothScroll\SmoothScroll;

defined('ABSPATH') or exit;

/**
 * Elementrify Extension Manager class
 *
 * @package Elementrify
 * @since 1.0.0
 */
class ExtensionManager
{
    protected static $extensions = [
        SmoothScroll::class
    ];

    public function __construct()
    {
    }

    /**
     * Get all available extensions
     *
     * @return array
     */
    public function get_extensions(): array
    {
        return self::$extensions;
    }

    /**
     * Get all active extensions
     *
     * @return array
     */
    public function get_active_extensions(): array
    {
        $extensions = OptionsHelper::get_option('active_extensions', []);

        return array_filter(self::$extensions, function (string $extension) use ($extensions) {
            return in_array(emty_container($extension)->get_id(), $extensions);
        });
    }
}
