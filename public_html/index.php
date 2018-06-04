<?php
declare(strict_types=1);

use JeremySells\AddressBook\Libraries\Output\OutputInterface;

require_once realpath(__DIR__ . '/../init.php');

call_user_func(function() {
    $root = getenv('PATH_APPLICATION_CODE');
    $routesConfig = require_once $root . '/config/routes.php';
    $container = getContainer();

    //Map Routes
    $router = $container->get(AltoRouter::class);
    foreach ($routesConfig as $routeConfig) {
        list ($name, $method, $route, $controller, $function) = $routeConfig;
        $target = function () use ($container, $controller, $function) {
            $service = $container->get($controller);
            return call_user_func_array([$service, $function], func_get_args());
        };
        $router->map($method, $route, $target, $name);
    }

    //Run
    $match = $router->match();
    if ($match === false || $match === null) {
        $url404 = $router->generate('404');
        $match = $router->match($url404, 'GET');
    }

    /** @var OutputInterface $output */
    $output = call_user_func_array($match['target'], array_values($match['params']));
    $output->output();
});
