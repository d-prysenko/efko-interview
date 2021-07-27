<?php
require "../vendor/autoload.php";

use Slim\Views\Twig;

$config['displayErrorDetails'] = true;

$app = new Slim\App(['settings' => $config]);

$container = $app->getContainer();
$container['view'] = function ($container) {
    $view = new Twig('../templates', [
        'cache' => false
    ]);

    $router = $container->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));

    return $view;
};

require __DIR__ . "/../App/routes.php";

try {
    $app->run();
} catch (Throwable $e) {
    echo "Че-та дропнулось: " . $e->getMessage();
}
