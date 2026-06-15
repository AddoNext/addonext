<?php
namespace Veloria\Utils;

defined('ABSPATH') or exit;

class OptionsHelper
{
    const option_prefix = 'veloria_';

    public static function get_option(string $key, $default = null)
    {
        return get_option(self::option_prefix . $key, $default);
    }

    public static function set_option(string $key, $value)
    {
        return update_option(self::option_prefix . $key, $value);
    }
}
