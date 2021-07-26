<?php


namespace App\Controller;

use Slim\Http\Response;

class ProfileController extends Controller
{
    public function settings($req, $res, $args): Response
    {
        return $this->render($res, "settings.twig");
    }

    public function adminpanel($req, $res, $args): Response
    {
        return $this->render($res, "adminpanel.twig");
    }
}