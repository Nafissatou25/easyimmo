<?php
namespace App\Controller;

use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;



class HomeController extends AbstractController
{
                                    // /**
                                        //*@var Environment
                                        //*/
                                        //private $twig;

                                        //public function __construct(Environment $twig)
                                        //{
                                        //   $this->twig = $twig;
                                        //}

    /**
     * @Route("/", name="home")
     * @param PropertyRepository $repository
     * @return Response
     */

    public function index(PropertyRepository $repository): Response
    {
        $properties = $repository->findLatest();
        return $this->render('pages/home.html.twig', ['properties'=> $properties
    ]);             //return new Response ($this->twig->render('pages/home.html.twig')); // 'salut les gens'msg affiché a lacceuil rempladé par twig

    }


}