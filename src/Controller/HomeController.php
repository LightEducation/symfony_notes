<?php

namespace App\Controller;

use App\Entity\Client;
use App\Product;
use App\Repository\CommandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route ("/req/{id?}", name="app_req", methods={"GET"},defaults={"id":1})
     */
    public function req(Request $request,$id): Response
    {
        dump($request);
        return new Response("Requested ID: $id");
    }

    /**
     * @Route ("/json")
     */
    public function js(): JsonResponse
    {
        return $this->json(["Key" => "Value"]);
    }

    /**
     * @Route ("/twig", name="app_twig")
     */
    public function twig(): Response{
        $title = "List of products";
        $products = [];
        for($i=0;$i<10;$i++){
            $products[] = new Product(uniqid("P"),rand(0,100),rand(10,1000)/10);
        }
        return $this->render("home/twig_example.html.twig",[
            "products" => $products,
            "title" => $title
        ]);
    }

    /**
     * @Route ("/", name="home")
     */
    public function home(): Response
    {
        return $this->render("home/index.html.twig");
    }

    /**
     * @Route ("/template")
     */
    public function template(): Response
    {
        return $this->render("home/template.twig");
    }

    /**
     * @Route ("/commands", name="app_cmd")
     */
    public function cmd(CommandRepository $commandRepository,EntityManagerInterface $entityManager): Response
    {
        $cr = $entityManager->getRepository(Client::class);
        dump($cr->find(7));

        $pr = $entityManager->getRepository(\App\Entity\Product::class);
        dump($pr->find(1));

        $commands = $commandRepository->findAll();
        dump($commands);


        return new Response();
    }
}
