<?php
require "../vendor/autoload.php";

use App\Controller\HomeController;
use App\Controller\LoginController;
//use Slim\App;
use Slim\Views\PhpRenderer;

$config['displayErrorDetails'] = true;
$config['db']['host'] = 'localhost';
$config['db']['user'] = 'root';
$config['db']['password'] = '123';
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

//$app->get('/', function () {
//    echo "You are login in";
//});

//$app->get("/", HomeController::class . ':get');

$app->get("/", HomeController::class);

$app->get("/login", LoginController::class);
$app->post("/login", LoginController::class);



try {
    $app->run();
} catch (Throwable $e) {
    echo "Че-та дропнулось: " . $e->getMessage();
}
