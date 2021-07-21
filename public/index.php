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

$app = new Core\App(['settings' => $config]);

$container = $app->getContainer();
$container['view'] = new PhpRenderer("../templates/");
$container['pdo'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO('mysql:host='.$db['host'].';dbname='.$db['dbname'], $db['user'], $db['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

$app->get("/", HomeController::class);

$app->get("/login", LoginController::class);
$app->post("/login", LoginController::class);

$app->get("/logout", LogoutController::class);

try {
    $app->run();
} catch (Throwable $e) {
    echo "��-�� ����������: " . $e->getMessage();
}
