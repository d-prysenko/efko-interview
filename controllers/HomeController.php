<?php


namespace App\Controller;

class HomeController extends Controller
{
    public function get()
    {
        return $this->container->get('view')->render($this->res, "index.phtml");
    }
}