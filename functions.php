<?php

use DI\ContainerBuilder;

defined('ABSPATH') or exit;

/**
 * Get image url
 *
 * @param string $filename
 * @return string
 */
function emty_get_img_url(string $filename = ''): string
{
    return VELORIA_URL . 'src/img/' . $filename;
}

/**
 * Get image path
 *
 * @param string $filename
 * @return string
 */
function emty_get_img_path(string $filename = ''): string
{
    return VELORIA_DIR . 'src/img/' . $filename;
}

/**
 * Get dependency injection container
 *
 * @param mixed $class
 * @return object
 */
function emty_container(mixed $class): object
{
    static $container = false;

    if (!$container) {
        $container = new ContainerBuilder();
        $container->addDefinitions( dirname(__FILE__)  . '/container.php');
        $container = $container->build();
    }

    return $container->get($class);
}

/**
 * Whether this build still ships the pro/ directory.
 *
 * @return bool
 */
function veloria_pro_is_available(): bool
{
    return defined('VELORIA_DIR') && is_dir(VELORIA_DIR . 'pro/inc');
}

/**
 * Safely check for a pro-only class without triggering Composer autoload warnings.
 *
 * @param class-string $class
 * @return bool
 */
function veloria_pro_class_exists(string $class): bool
{
    if (!veloria_pro_is_available()) {
        return false;
    }

    return class_exists($class);
}

/**
 * Whether this build ships the pro account/license REST API.
 *
 * @return bool
 */
function veloria_has_pro_account_api(): bool
{
    return veloria_pro_is_available()
        && is_readable(VELORIA_DIR . 'pro/inc/Pro/Admin/AccountController.php');
}
