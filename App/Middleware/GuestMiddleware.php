<?php


namespace App\Middleware;

use App\Model\User;
use Slim\Http\Response;

class GuestMiddleware extends Middleware
{
    public function __invoke($req, Response $res, $next): Response
    {
        $user = new User();
        if ($user->isAuth()) {
            return $res->withRedirect($this->container->get('router')->pathFor('home.page'));
        }
        return $next($req, $res);
    }
}