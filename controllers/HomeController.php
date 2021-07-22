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
        $stmt = $db->prepare('SELECT role FROM users, cookies WHERE cookies.value = ? AND users.id = cookies.user_id');
        $stmt->execute([$_COOKIE['value']]);
        $role = $stmt->fetchColumn();

        return $this->render($res, "index.phtml", [
            'router' => $this->container->get('router'),
            'role' => $role
        ]);
    }
}