<?php


namespace App\Controller;

use Slim\Http\Request;
use Slim\Http\Response;

class ProblemsController extends Controller
{
    public function ratingUpdate(Request $req, Response $res, array $args): Response
    {
        if (!$this->isAuth()) {
            return $res->withRedirect($this->pathFor('login.page'));
        }

        // TODO: get user method
        $db = $this->container->get('pdo');
        $stmt = $db->prepare('SELECT users.id, role FROM users, cookies WHERE cookies.value = ?');
        $stmt->execute([$_COOKIE['value']]);
        $user = $stmt->fetch();

        if ($user['role'] == 'admin' || $user['role'] == 'evaluator') {
            $problem_id = $req->getParam('problem_id');
            $mark = $req->getParam('mark');

            if ($mark < 1 || $mark > 5) {
                return $res->withStatus(403);
            }

            $prep = $db->prepare('UPDATE problems SET mark = ?, evaluator_id = ?, date=NOW() WHERE id = ?');

            if ($prep->execute([$mark, $user['id'], $problem_id])) {
                return $res->withStatus(200);
            } else {
                return $res->withStatus(404);
            }

        }

        return $res->withStatus(403);
    }

    public function addProblem(Request $req, Response $res, array $args): Response
    {
        if (!$this->isAuth()) {
            return $res->withRedirect($this->pathFor('login.page'));
        }

        // TODO: get user method
        $db = $this->container->get('pdo');
        $stmt = $db->prepare('SELECT users.id, role FROM users, cookies WHERE cookies.value = ?');
        $stmt->execute([$_COOKIE['value']]);
        $user = $stmt->fetch();

        if ($user['role'] == 'admin' || $user['role'] == 'writer') {
            $problem = $req->getParam('problem');
            $solution = $req->getParam('solution');

            $prep = $db->prepare('INSERT INTO problems VALUES (NULL, ?, NULL, NOW(), ?, ?, NULL)');
            $prep->execute([$user['id'], $problem, $solution]);
            return $res->withRedirect($this->pathFor('home.page'));

        }
    }

    public function deleteEntry(Request $req, Response $res, array $args): Response
    {
        if (!$this->isAuth()) {
            return $res->withRedirect($this->pathFor('login.page'));
        }

        // TODO: get user method
        $db = $this->container->get('pdo');
        $stmt = $db->prepare('SELECT users.id, role FROM users, cookies WHERE cookies.value = ?');
        $stmt->execute([$_COOKIE['value']]);
        $user = $stmt->fetch();

        if ($user['role'] == 'admin') {
            $problem_id = $req->getParam('problem_id');

            $prep = $db->prepare('DELETE FROM problems WHERE id = ?');
            $prep->execute([$problem_id]);
            return $res->withStatus(200);
        }

        return $res->withStatus(403);
    }
}