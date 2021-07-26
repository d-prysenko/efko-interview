<?php


namespace App\Controller;

use App\Model\Entities;
use App\Model\User;
use Slim\Http\Response;
use Slim\Http\Request;

class HomeController extends Controller
{
    protected static int $ENTITIES_LIMIT = 14;

    public function home(Request $req, Response $res, array $args): Response
    {
        if (!isset($args['page'])) {
            $args['page'] = '0';
        }

        $user = new User();
        $problems = new Entities();

        return $this->render($res, "home.twig", [
            'router' => $this->container->get('router'),
            'user' => $user,
            'rows' => $problems->fetch(static::$ENTITIES_LIMIT, static::$ENTITIES_LIMIT * $args['page'])
        ]);
    }
}
