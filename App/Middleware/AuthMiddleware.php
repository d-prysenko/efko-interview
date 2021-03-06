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

        if (!$user->verified) {
            return $this->container->get('view')->render($res, 'intermediate_home.twig', ['user' => $user]);
        }

        return $next($req, $res);
    }
}
