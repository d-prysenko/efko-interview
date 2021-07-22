<?php

namespace App\Controller;

use Psr\Container\ContainerInterface;

class Controller
{
    protected ContainerInterface $container;
    protected array $params;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function render($res, $template, $params = [])
    {
        return $this->container->get('view')->render($res, $template, $params);
    }

    public function pathFor($route_name): string
    {
        return $this->container->get('router')->pathFor($route_name);
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
}
