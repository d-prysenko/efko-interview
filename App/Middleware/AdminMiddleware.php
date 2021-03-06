<?php


namespace App\Middleware;

use App\Model\User;
use Slim\Http\Response;

class AdminMiddleware extends Middleware
{
    public function __invoke($req, Response $res, $next): Response
    {
        $user = new User();
        if (!$user->isAdmin()) {
            return $res->withStatus(403);
        }

        return $next($req, $res);
    }
}
