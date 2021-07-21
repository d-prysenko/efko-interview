<?php

namespace App\Controller;

use Slim\Http\Response;

class LoginController extends Controller
{
    private function validate($email, $password): int
    {
        if ($email == '' || strlen($password) < 6) {
            return false;
        }

        $db = $this->container->get('pdo');

        $stmt = $db->prepare("SELECT * FROM users WHERE `email`= ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password . $user['salt'], $user['hash'])) {
            return $user['id'];
        }

        return 0;
    }

    public function get(): Response
    {
        if ($this->isAuth()) {
            return $this->redirect('/');
        }
        return $this->render("login.phtml", ['error' => '']);
    }

    public function post(): Response
    {
        $email = $this->req->getParam('email');
        $password = $this->req->getParam('password');

        $user_id = $this->validate($email, $password);

        if ($user_id) {
            $value = uniqid('', true);

            $db = $this->container->get('pdo');
            $stmt = $db->prepare("INSERT INTO cookies VALUES (NULL, ?, ?)");
            $stmt->execute([$user_id, $value]);

            setcookie("id", $db->lastInsertId(), time()+60*60*24*365);
            setcookie("value", $value, time()+60*60*24*365);
            return $this->redirect('/');
        } else {
            return $this->render("login.phtml", ['error' => 'Неправильный логин или пароль']);
        }
    }
}
