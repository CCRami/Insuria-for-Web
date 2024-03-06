<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function create(FlashyNotifier $flashy)
    {
    
    
        $flashy->success('Offer added!', 'front/offre.html.twig');
    
        return $this->redirectToRoute('home');
    }





}
