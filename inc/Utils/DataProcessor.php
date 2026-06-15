<?php
namespace Veloria\Utils;

defined('ABSPATH') or exit;

/**
 * DataProcessor class
 *
 * @package Veloria\Utils
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
        return str_replace('veloria-', '', $category_slug);
    }
}
