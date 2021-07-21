<?php

namespace Core;

use Slim\Interfaces\RouteInterface;

class App extends \Slim\App
{
    public function get($pattern, $callable): RouteInterface
    {
        return parent::get($pattern, function ($req, $res, $args) use ($callable) {
            $controller = new $callable($req, $res, $args, $this);
            return $controller->get();
        });
    }

    public function post($pattern, $callable): RouteInterface
    {
        return parent::post($pattern, function ($req, $res, $args) use ($callable) {
            $controller = new $callable($req, $res, $args, $this);
            return $controller->post();
        });
    }
}
