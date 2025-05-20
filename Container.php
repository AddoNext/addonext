<?php

defined('ABSPATH') or exit;

use DI\Container;

/**
 * Get dependency injection container
 *
 * @param mixed $class
 * @return object
 */
function dgn_container(mixed $class): object
{
    static $container = false;

    if (!$container) {
        $container = new Container;
    }

    return $container->get($class);
}
