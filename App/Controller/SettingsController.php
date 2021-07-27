<?php


namespace App\Controller;

use Slim\Http\Request;
use Slim\Http\Response;

class SettingsController extends Controller
{
    public function settings(Request $req, Response $res, array $args): Response
    {
        return $this->render($res, "settings.twig");
    }
}
