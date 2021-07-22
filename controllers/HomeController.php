<?php


namespace App\Controller;

use Slim\Http\Response;

class HomeController extends Controller
{
    public function home($req, $res, $args): Response
    {
        if (!$this->isAuth()) {
            return $res->withRedirect($this->pathFor('login.page'));
        }

        $db = $this->container->get('pdo');
        $stmt = $db->prepare('SELECT users.id, role FROM users, cookies WHERE cookies.value = ?');
        $stmt->execute([$_COOKIE['value']]);
        $user = $stmt->fetch();

        $rows = $db->query("SELECT * FROM problems")->fetchAll();

        return $this->render($res, "home.twig", [
            'router' => $this->container->get('router'),
            'user' => $user,
            'rows' => $rows
        ]);
    }

}