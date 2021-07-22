<?php
require "../vendor/autoload.php";

use App\Controller\HomeController;
use App\Controller\LoginController;
use App\Controller\LogoutController;
use Slim\Views\PhpRenderer;

$config['displayErrorDetails'] = true;
$config['db']['host'] = 'localhost';
$config['db']['user'] = 'root';
$config['db']['password'] = '';
$config['db']['dbname'] = 'efco';

$app = new Slim\App(['settings' => $config]);

$container = $app->getContainer();
$container['view'] = new PhpRenderer("../templates/");
$container['pdo'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO('mysql:host='.$db['host'].';dbname='.$db['dbname'], $db['user'], $db['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

$app->get("/", HomeController::class . ":home")->setName('home.page');

$app->get("/login", LoginController::class . ":login")->setName('login.page');
$app->post("/login", LoginController::class . ":authenticate")->setName('auth.action');

$app->get("/logout", LoginController::class . ":logout")->setName("logout.action");

try {
    $app->run();
} catch (Throwable $e) {
    echo "Че-та дропнулось: " . $e->getMessage();
}
