<?php

use App\Controller\HomeController;
use App\Controller\LoginController;
use App\Controller\ProblemsController;
use App\Controller\ProfileController;

$app->get("/login", LoginController::class . ":login")
    ->add(new \App\Middleware\GuestMiddleware($container))
    ->setName('login.page');

$app->post("/login", LoginController::class . ":authenticate")
    ->add(new \App\Middleware\GuestMiddleware($container))
    ->setName('auth.action');

$app->get('/signup', LoginController::class . ":registration")
    ->add(new \App\Middleware\GuestMiddleware($container))
    ->setName("signup.page");

$app->post('/signup', LoginController::class . ":signup")
    ->add(new \App\Middleware\GuestMiddleware($container))
    ->setName("signup.action");


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

$app->get("/logout", LoginController::class . ":logout")
    ->setName("logout.action");

$app->get("/settings", ProfileController::class . ":settings")
    ->add(new \App\Middleware\AuthMiddleware($container))
    ->setName('settings.page');

$app->get("/adminpanel", ProfileController::class . ":adminPanel")
    ->add(new \App\Middleware\AuthMiddleware($container))
    ->add(new \App\Middleware\AdminMiddleware($container))
    ->setName('adminpanel.page');

$app->post("/user-accept", ProfileController::class . ":userAccept")
    ->add(new \App\Middleware\AuthMiddleware($container))
    ->add(new \App\Middleware\AdminMiddleware($container))
    ->setName('user.accept.action');
