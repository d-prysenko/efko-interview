<?php

namespace App\Controller;

use App\Model\User;
use Slim\Http\Response;

class LoginController extends Controller
{

    public function login($req, $res, $args): Response
    {
        return $this->render($res, "login.twig", [
            'error' => '',
            'router' => $this->container->get('router')
        ]);
    }

    public function authenticate($req, $res, $args): Response
    {
        $email = $req->getParam('email');
        $password = $req->getParam('password');

        $user = new User();

        if ($user->verify($email, $password)) {
            $user->setCookies();
            return $res->withRedirect($this->pathFor('home.page'));
        } else {
            return $this->render($res, "login.twig", [
                'error' => 'Неправильный логин или пароль',
                'router' => $this->container->get('router')
            ]);
        }
    }

    public function logout($req, $res, $args): Response
    {
        $user = new User();
        $user->deleteCookies();

        return $res->withRedirect($this->pathFor('login.page'));
    }
}
