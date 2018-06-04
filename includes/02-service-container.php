<?php
declare(strict_types=1);

use DI\Container;
use DI\ContainerBuilder;

/**
 * Gets the Container
 *
 * @return Container
 * @throws Exception
 */
function getContainer(): Container
{
    //Bootstrap static
    static $container = null;

    //Lazy load?
    if ($container === null) {
        $builder = new ContainerBuilder();
        $builder->addDefinitions(getenv('PATH_APPLICATION_CODE') . '/config/php-di-definitions.php');
        $container = $builder->build();
    }

    //Return instance
    return $container;
}
