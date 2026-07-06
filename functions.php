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
    return ADDONEXT_URL . 'src/img/' . $filename;
}

/**
 * Get image path
 *
 * @param string $filename
 * @return string
 */
function emty_get_img_path(string $filename = ''): string
{
    return ADDONEXT_DIR . 'src/img/' . $filename;
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
function addonext_pro_is_available(): bool
{
    return defined('ADDONEXT_DIR') && is_dir(ADDONEXT_DIR . 'pro/inc');
}

/**
 * Safely check for a pro-only class without triggering Composer autoload warnings.
 *
 * @param class-string $class
 * @return bool
 */
function addonext_pro_class_exists(string $class): bool
{
    if (!addonext_pro_is_available()) {
        return false;
    }

    return class_exists($class);
}

/**
 * Whether this build ships the pro account/license REST API.
 *
 * @return bool
 */
function addonext_has_pro_account_api(): bool
{
    return addonext_pro_is_available()
        && is_readable(ADDONEXT_DIR . 'pro/inc/Pro/Admin/AccountController.php');
}
