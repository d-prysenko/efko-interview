<?php

namespace App\Controller;

use App\Model\User;
use Psr\Container\ContainerInterface;

class Controller
{
    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function render($res, $template, $params = [])
    {
        $user = new User();
        $params['user'] = $user;
        return $this->container->get('view')->render($res, $template, $params);
    }

    public function pathFor($route_name): string
    {
        return $this->container->get('router')->pathFor($route_name);
    }


}
