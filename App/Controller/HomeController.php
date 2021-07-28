<?php


namespace App\Controller;

use App\Model\Problems;
use Slim\Http\Response;
use Slim\Http\Request;

class HomeController extends Controller
{
    protected static int $ENTITIES_LIMIT = 14;

    public function home(Request $req, Response $res, array $args): Response
    {
        if (!isset($args['page']) || $args['page'] == 0) {
            $args['page'] = '1';
        }

        $problems = new Problems();

        $pages_count = ceil($problems->rowsCount() / static::$ENTITIES_LIMIT);

        return $this->render($res, "home.twig", [
            'router' => $this->container->get('router'),
            'rows' => $problems->fetch(static::$ENTITIES_LIMIT, static::$ENTITIES_LIMIT * ($args['page'] - 1)),
            'page' => $args['page'],
            'pages_count' => $pages_count
        ]);
    }
}
