<?php


namespace App\Controller;

use App\Model\Entities;
use App\Model\User;
use App\Model\Users;
use Slim\Http\Request;
use Slim\Http\Response;

class AdminController extends Controller
{
    public function adminPanel(Request $req, Response $res, array $args): Response
    {
        $problems = new Entities();
        $users = new Users();

        return $this->render($res, "adminpanel.twig", [
            'problems_count' => $problems->rowsCount(),
            'rated_problems_count' => $problems->ratedCount(),
            'active_writers' => $problems->getActiveWriters(),
            'active_raters' => $problems->getActiveEvaluators(),
            'new_users' => $users->getNewUsersList()
        ]);
    }

    public function userAccept(Request $req, Response $res, array $args): Response
    {
        $users = new Users();
        $user_id = $req->getParam('id');

        if ($users->accept($user_id)) {
            return $res->withRedirect($this->pathFor('adminPanel.page'));
        }

        return $res->withStatus(500);
    }
}