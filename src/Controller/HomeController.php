<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index_client(): Response
    {
        return $this->render('/front/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/admin', name: 'app_admin')]
    public function index_admin(): Response
    {
        return $this->render('/back/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
