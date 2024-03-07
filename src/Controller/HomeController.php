<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index_client(Request $request): Response
    {
        $request->getSession()->getFlashBag()->clear();
        return $this->render('/front/home.html.twig', [
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
    #[Route('/sinistre', name: 'app_sinistre')]
    public function index_sinistre(): Response
    {
        return $this->render('/front/sinistre.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
