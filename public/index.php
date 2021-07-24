<?php
require "../vendor/autoload.php";

use App\Controller\HomeController;
use App\Controller\ProblemsController;
use App\Controller\LoginController;
use App\Controller\ProfileController;
use Slim\Views\Twig;

$config['displayErrorDetails'] = true;
//$config['db']['host'] = 'localhost';
//$config['db']['user'] = 'root';
//$config['db']['password'] = '';
//$config['db']['dbname'] = 'efco';

$app = new Slim\App(['settings' => $config]);

$container = $app->getContainer();
$container['view'] = function ($container) {
    $view = new Twig('../templates', [
        'cache' => false
    ]);

    // Instantiate and add Slim specific extension
    $router = $container->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));

    return $view;
};


$app->get("/[{page:[0-9]+}]", HomeController::class . ":home")
    ->add(new \App\Middleware\AuthMiddleware($container))
    ->setName('home.page');

$app->post("/rating-update", ProblemsController::class . ":ratingUpdate")
    ->add(new \App\Middleware\AuthMiddleware($container))
    ->setName('ratingUpdate.action');

$app->post("/add-problem", ProblemsController::class . ":addProblem")
    ->add(new \App\Middleware\AuthMiddleware($container))
    ->setName('problemAdd.action');

$app->post("/delete-entry", ProblemsController::class . ":deleteEntry")
    ->add(new \App\Middleware\AuthMiddleware($container))
    ->setName('deleteEntry.action');


$app->get("/login", LoginController::class . ":login")
    ->add(new \App\Middleware\GuestMiddleware($container))
    ->setName('login.page');

$app->post("/login", LoginController::class . ":authenticate")
    ->add(new \App\Middleware\GuestMiddleware($container))
    ->setName('auth.action');

$app->get("/logout", LoginController::class . ":logout")
    ->setName("logout.action");

$app->get("/settings", ProfileController::class . ":settings")
    ->add(new \App\Middleware\AuthMiddleware($container))
    ->setName('settings.page');

$app->get("/adminpanel", ProfileController::class . ":adminpanel")
    ->add(new \App\Middleware\AuthMiddleware($container))
    ->setName('adminpanel.page');

try {
    $app->run();
} catch (Throwable $e) {
    echo "Че-та дропнулось: " . $e->getMessage();
}
