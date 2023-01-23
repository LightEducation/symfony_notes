<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\User;
use App\Product;
use App\Repository\CommandRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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
    public function home(Request $request,UserPasswordHasherInterface $hasher,UserRepository $repository): Response
    {
        $form = $this->createFormBuilder()
            ->add("email")
            ->add('password',RepeatedType::class,[
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
            ])
            ->add('attachment',FileType::class)
            ->add('singup',SubmitType::class,[
                'attr' => [
                    'class' => 'btn-success float-end'
                ],
                'label' => "Sign UP"
            ])
            ->getForm();
        $form->handleRequest($request);
        $form->getErrors();
        if($form->isSubmitted() and $form->isValid()){
            $data = $form->getData();
            $user = new User();
            $user->setEmail($data["email"]);
            $user->setPassword($hasher->hashPassword($user,$data["password"]));
            $this->addFlash("info","User registered successfully");

            /** @var UploadedFile $file */
            $file = $request->files->get("form")["attachment"];
            $filename = md5(uniqid()).".".$file->guessClientExtension();
            $file->move($this->getParameter('upload_dir'),$filename);
            $this->addFlash("info","<a href='uploads/$filename'>See file</a>");
            try {
                $repository->add($user, true);
            } catch (Exception $e) {
                dd($e);
            }
        }
        return $this->render("home/index.html.twig",[
            "signup_form" => $form->createView()
        ]);
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

    /**
     * @Route ("/signup", name="signup")
     */
    public function signup(): Response
    {
       return new Response();
    }
}
