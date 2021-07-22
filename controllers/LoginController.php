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

    public function signup($req, $res, $args): Response
    {
        if ($this->isAuth()) {
            return $res->withRedirect($this->pathFor('home.page'));
        }
    }

    public function login($req, $res, $args): Response
    {
        if ($this->isAuth()) {
            return $res->withRedirect($this->pathFor('home.page'));
        }

        return $this->render($res, "login.phtml", [
            'error' => '',
            'router' => $this->container->get('router')
        ]);
    }

    public function authenticate($req, $res, $args): Response
    {
        $email = $req->getParam('email');
        $password = $req->getParam('password');

        $user_id = $this->validate($email, $password);

        if ($user_id) {
            $value = uniqid('', true);

            $db = $this->container->get('pdo');
            $stmt = $db->prepare("INSERT INTO cookies VALUES (NULL, ?, ?)");
            $stmt->execute([$user_id, $value]);

            setcookie("id", $db->lastInsertId(), time()+60*60*24*365);
            setcookie("value", $value, time()+60*60*24*365);

            return $res->withRedirect($this->pathFor('home.page'));
        } else {
            return $this->render($res, "login.phtml", [
                'error' => 'Неправильный логин или пароль',
                'router' => $this->container->get('router')
            ]);
        }
    }

    public function logout($req, $res, $args): Response
    {
        if (isset($_COOKIE['id'], $_COOKIE['value'])) {
            $db = $this->container->get('pdo');
            try {
                $stmt = $db->prepare("DELETE FROM cookies WHERE `id` = ? AND `value` = ?");
                $stmt->execute([$_COOKIE['id'], $_COOKIE['value']]);
                setcookie('id', '', 0);
                setcookie('value', '', 0);
            } catch (\PDOException $e) {
                echo "Неудалось выполнить запрос к базе данных: " . $e->getMessage();
                exit(-1);
            }
        }
        return $res->withRedirect($this->pathFor('login.page'));
    }
}
