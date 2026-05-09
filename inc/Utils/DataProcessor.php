<?php
namespace Elementrify\Utils;

defined('ABSPATH') or exit;

/**
 * DataProcessor class
 *
 * @package Elementrify\Utils
 * @since 1.0.0
 */
class DataProcessor
{
    /**
     * Get the category name from the category slug
     *
     * @param string $category_slug
     * @return string
     */
    public static function get_category_name(string $category_slug): string
    {
        return str_replace('elementrify-', '', $category_slug);
    }
}
