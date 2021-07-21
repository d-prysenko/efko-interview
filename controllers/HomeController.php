<?php


namespace App\Controller;

use Slim\Http\Response;

class HomeController extends Controller
{
    public function get(): Response
    {
        if (!$this->isAuth()) {
            return $this->redirect('/login');
        }
        return $this->render("index.phtml");
    }
}