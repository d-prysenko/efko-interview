<?php

namespace App\Controller;

use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class Controller
{
    protected Request $req;
    protected Response $res;
    protected array $args;
    protected ContainerInterface $container;
    protected array $params;

    public function __construct(Request $req, Response $res, array $args, ContainerInterface $container)
    {
        $this->req = $req;
        $this->res = $res;
        $this->args = $args;
        $this->container = $container;
    }

    public function __get($prop)
    {
        if ($this->container->{$prop}) {
            return $this->container->{$prop};
        }
        return null;
    }

    public function render($url, $params = [])
    {
        return $this->container->get('view')->render($this->res, $url, $params);
    }

    public function redirect($url): Response
    {
        return $this->res->withRedirect($url);
    }

    public function isAuth(): bool
    {
        if (isset($_COOKIE['id'], $_COOKIE['value'])) {
            $id = $_COOKIE['id'];
            $value = $_COOKIE['value'];
            $db = $this->container->get('pdo');
            try {
                $stmt = $db->prepare("SELECT * FROM cookies WHERE `id` = ? AND `value` = ?");
                $stmt->execute([$id, $value]);
                if ($stmt->rowCount()) {
                    return true;
                }
            } catch (\PDOException $e) {
                echo "Неудалось выполнить запрос к базе данных: " . $e->getMessage();
                exit(-1);
            }
        }
        return false;
    }

    public function get(): Response
    {
        if ($this->isAuth() == false) {
            return $this->redirect('/login');
        }
        return $this->res->write("Unknown page");
    }

    public function post(): Response
    {
        if ($this->isAuth() == false) {
            return $this->redirect('/login');
        }
        return $this->res->write("Unknown page");
    }

}
