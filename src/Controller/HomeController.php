<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/test", name="app_test")
     */
    public function test(): Response
    {
        return new Response("<h1>Hello world!</h1>");
    }

    /**
     * @Route ("/", name="home")
     */
    public function home(): Response
    {
        return $this->render("home/index.html.twig");
    }
}
