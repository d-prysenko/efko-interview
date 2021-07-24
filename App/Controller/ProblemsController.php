<?php


namespace App\Controller;

use App\Model\Entities;
use Slim\Http\Request;
use Slim\Http\Response;
use \App\Model\User;

class ProblemsController extends Controller
{
    public function ratingUpdate(Request $req, Response $res, array $args): Response
    {
        $user = new User();
        $problem_id = $req->getParam('problem_id');
        $mark = $req->getParam('mark');

        if (!$user->isAdmin() && !$user->isEvaluator()) {
            return $res->withStatus(403);
        }

        if ($mark < 1 || $mark > 5) {
            return $res->withStatus(403);
        }

        $ents = new Entities();

        if ($ents->setMark($problem_id, $user->id, $mark)) {
            return $res->withStatus(200);
        }

        return $res->withStatus(404);
    }

    public function addProblem(Request $req, Response $res, array $args): Response
    {
        $user = new User();

        if ($user->isAdmin() || $user->isWriter()) {
            $problem = $req->getParam('problem');
            $solution = $req->getParam('solution');

            $ents = new Entities();
            $ents->addEntity($user->id, $problem, $solution);

            return $res->withRedirect($this->pathFor('home.page'));
        }
        return $res->withStatus(403);
    }

    public function deleteEntry(Request $req, Response $res, array $args): Response
    {

        $user = new User();

        if ($user->isAdmin()) {
            $entity_id = $req->getParam('problem_id');

            $ents = new Entities();
            $ents->deleteEntity($entity_id);

            return $res->withStatus(200);
        }

        return $res->withStatus(403);
    }
}