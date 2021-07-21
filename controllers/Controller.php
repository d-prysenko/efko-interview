<?php

namespace App\Controller;

use \Psr\Container\ContainerInterface;
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

    public function param($name)
    {
        if (isset($this->params[$name])) {
            return $this->params[$name];
        } else {
            return '';
        }
    }
}
