<?php

use App\Controller\HomeController;
use App\Controller\LoginController;
use App\Controller\ProblemsController;
use App\Controller\AdminController;
use App\Controller\SettingsController;

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;
use App\Middleware\AdminMiddleware;
use App\Middleware\EvaluatorMiddleware;
use App\Middleware\WriterMiddleware;

$app->get("/login", LoginController::class . ":login")
    ->add(new GuestMiddleware($container))
    ->setName('login.page');

$app->post("/login", LoginController::class . ":auth")
    ->add(new GuestMiddleware($container))
    ->setName('auth.action');

$app->get("/logout", LoginController::class . ":logout")
    ->setName("logout.action");

$app->get('/signup', LoginController::class . ":registration")
    ->add(new GuestMiddleware($container))
    ->setName("signup.page");

$app->post('/signup', LoginController::class . ":signup")
    ->add(new GuestMiddleware($container))
    ->setName("signup.action");


$app->get("/[{page:[0-9]+}]", HomeController::class . ":home")
    ->add(new AuthMiddleware($container))
    ->setName('home.page');


$app->post("/mark-update", ProblemsController::class . ":markUpdate")
    ->add(new AuthMiddleware($container))
    ->add(new EvaluatorMiddleware($container))
    ->setName('markUpdate.action');

$app->post("/add-problem", ProblemsController::class . ":addProblem")
    ->add(new AuthMiddleware($container))
    ->add(new WriterMiddleware($container))
    ->setName('problemAdd.action');

$app->post("/edit", ProblemsController::class . ":edit")
    ->add(new AuthMiddleware($container))
    ->add(new AdminMiddleware($container))
    ->setName('admin.edit.action');


$app->get("/settings", SettingsController::class . ":settings")
    ->add(new AuthMiddleware($container))
    ->setName('settings.page');


$app->get("/admin-panel", AdminController::class . ":adminPanel")
    ->add(new AuthMiddleware($container))
    ->add(new AdminMiddleware($container))
    ->setName('adminPanel.page');

$app->post("/user-accept", AdminController::class . ":userAccept")
    ->add(new AuthMiddleware($container))
    ->add(new AdminMiddleware($container))
    ->setName('userAccept.action');
