<?php


namespace App\Controller;

use App\Model\Entities;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Model\User;

class ProblemsController extends Controller
{
    public function markUpdate(Request $req, Response $res, array $args): Response
    {
        $user = new User();
        $problem_id = $req->getParam('problem_id');
        $mark = $req->getParam('mark');

        if ($mark < 0 || $mark > 5) {
            return $res->withStatus(400);
        }

        $ents = new Entities();

        if ($ents->setMark($problem_id, $user->id, $mark)) {
            return $res->withStatus(200);
        }

        return $res->withStatus(500);
    }

    public function addProblem(Request $req, Response $res, array $args): Response
    {
        $user = new User();

        $problem = $req->getParam('problem');
        $solution = $req->getParam('solution');

        $ents = new Entities();
        if ($ents->addEntity($user->id, $problem, $solution)) {
            return $res->withRedirect($this->pathFor('home.page'));
        }

        return $res->withStatus(500);
    }

    public function deleteProblem(Request $req, Response $res, array $args): bool
    {
        $entity_id = $req->getParam('id');

        $ents = new Entities();

        return $ents->deleteEntity($entity_id);
    }

    public function deleteMark(Request $req, Response $res, array $args): bool
    {
        $entity_id = $req->getParam('id');

        $ents = new Entities();

        return $ents->setMark($entity_id, null, null);
    }

    public function edit(Request $req, Response $res, array $args): Response
    {
        $action = $req->getParam('action');

        switch ($action) {
            case "delete-row":
                if (!$this->deleteProblem($req, $res, $args)) {
                    return $res->withStatus(500);
                }
                break;
            case "delete-mark":
                if (!$this->deleteMark($req, $res, $args)) {
                    return $res->withStatus(500);
                }
                break;
        }

        return $res->withRedirect($this->pathFor('home.page'));
    }
}
