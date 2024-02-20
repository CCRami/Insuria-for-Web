<?php

namespace App\Controller;

use App\Repository\ReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReclamationController extends AbstractController
{
    #[Route('/reclamation', name: 'app_reclamation')]
    public function index(): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'controller_name' => 'ReclamationController',
        ]);
    }
    #[Route('/tableReclamation', name: 'app_reclamation_admin')]
    public function index_reclamation_admin(ReclamationRepository $rep): Response
    {$list=$rep->findAll();
        return $this->render('reclamation\ReclamationBack.html.twig', 
          ['list' => $list, ]
        );
    }

}
