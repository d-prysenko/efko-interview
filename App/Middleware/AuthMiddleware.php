<?php


namespace App\Middleware;

use App\Model\User;
use Slim\Http\Response;

class AuthMiddleware extends Middleware
{
    public function __invoke($req, Response $res, $next): Response
    {
        $user = new User();
        if (!$user->isAuth()) {
            return $res->withRedirect($this->container->get('router')->pathFor('login.page'));
        }
        return $next($req, $res);
    }
}
