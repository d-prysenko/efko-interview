<?php


namespace App\Controller;

use Slim\Http\Response;

class LogoutController extends Controller
{
    public function get(): Response
    {
        if (isset($_COOKIE['id'], $_COOKIE['value'])) {
            $db = $this->container->get('pdo');
            try {
                $stmt = $db->prepare("DELETE FROM cookies WHERE id = ? AND value = ?");
                $stmt->execute([$_COOKIE['id'], $_COOKIE['email']]);
                setcookie('id', '', 0);
                setcookie('value', '', 0);
                return $this->redirect('/login');
            } catch (\PDOException $e) {
                echo "Неудалось выполнить запрос к базе данных: " . $e->getMessage();
                exit(-1);
            }
        }
        return $this->redirect('/login');
    }
}
