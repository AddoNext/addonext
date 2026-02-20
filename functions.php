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
    return ELEMENTRIFY_URL . 'src/img/' . $filename;
}

/**
 * Get image path
 *
 * @param string $filename
 * @return string
 */
function emty_get_img_path(string $filename = ''): string
{
    return ELEMENTRIFY_DIR . 'src/img/' . $filename;
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
        $container->addDefinitions(dirname(__FILE__, 3) . '/container.php');
        $container = $container->build();
    }

    return $container->get($class);
}
