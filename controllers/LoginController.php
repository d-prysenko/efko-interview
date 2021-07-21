<?php

namespace App\Controller;

class LoginController extends Controller
{
    public function get()
    {
        return $this->container->get('view')->render($this->res, "login.phtml", ['error' => '']);
    }

    public function post()
    {
        // test
        $email = $this->req->getParam('email');
        $password = $this->req->getParam('password');

        if ($email == 'admin' && $password == '123') {
            return $this->res->withRedirect('/');
        } else {
            return $this->container->get('view')
                ->render($this->res, "login.phtml", ['error' => 'Неправильный логин или пароль']);
        }
    }
}
