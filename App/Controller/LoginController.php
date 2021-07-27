<?php

namespace App\Controller;

use App\Model\User;
use Slim\Http\Response;
use Slim\Http\Request;

class LoginController extends Controller
{

    public function login(Request $req, Response $res, array $args): Response
    {
        return $this->render($res, "login.twig", [
            'error' => '',
            'router' => $this->container->get('router')
        ]);
    }

    public function authenticate(Request $req, Response $res, array $args): Response
    {
        $email = $req->getParam('email');
        $password = $req->getParam('password');

        $user = new User();

        if ($user->passwordVerify($email, $password)) {
            $user->setCookies();
            return $res->withRedirect($this->pathFor('home.page'));
        } else {
            return $this->render($res, "login.twig", [
                'error' => 'Неправильный логин или пароль',
                'router' => $this->container->get('router')
            ]);
        }
    }

    public function registration(Request $req, Response $res, array $args): Response
    {
        return $this->render($res, "register.twig");
    }

    public function signup(Request $req, Response $res, array $args): Response
    {
        $name = $req->getParam('name');
        $surname = $req->getParam('surname');
        $email = $req->getParam('email');
        $password = $req->getParam('password');
        $role = $req->getParam('role');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8
            || ($role != 'writer' && $role != 'evaluator') || strlen($name) < 2 || strlen($surname) < 2) {
            return $this->render($res, 'register.twig', ['error' => 'Проверьте корректность введенных данных']);
        }

        try {
            $user = new User();
            $user->register($email, $password, $role, $name, $surname);
            $user->setCookies();
            return $res->withRedirect($this->pathFor('home.page'));
        } catch (\Exception $e) {
            return $this->render($res, 'register.twig', ['error' => $e->getMessage()]);
        }
    }

    public function logout(Request $req, Response $res, array $args): Response
    {
        $user = new User();
        $user->deleteCookies();

        return $res->withRedirect($this->pathFor('login.page'));
    }
}
