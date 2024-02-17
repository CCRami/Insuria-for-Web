<?php

namespace App\Controller;
use App\Entity\Offre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\OffreformType; 
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OffreRepository;

class OffreController extends AbstractController
{
    #[Route('/offre', name: 'app_offre')]
    public function index(): Response
    {
        return $this->render('back/afficheoffre.html.twig', [
            'controller_name' => 'OffreController',
        ]);
    }

    #[Route('/displayof', name: 'display_offre')]
    public function displayOffre(OffreRepository $offreRepository): Response
    {
        $offres = $offreRepository->findAll();

        return $this->render('back/afficheroffre.html.twig', [
            'offres' => $offres,
        ]);
    }

    #[Route('/addof', name: 'add_offre')]
    public function addOffre(Request $request, EntityManagerInterface $em): Response
    {
        $offre = new Offre();
        $form = $this->createForm(OffreformType::class, $offre);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($offre);
            $em->flush();
            
            return $this->redirectToRoute('display_offre');
        }

        return $this->render('back/addoffre.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/editof/{id}', name: 'edit_offre')]
    public function editOffre(Request $request, EntityManagerInterface $em, OffreRepository $rep, int $id): Response
    {
        $offre = $rep->find($id);
        $form = $this->createForm(OffreformType::class, $offre);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            
            return $this->redirectToRoute('display_offre');
        }
    
        return $this->render('back/editoffre.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/deleteof/{id}', name: 'delete_offre')]
    public function deleteOffre(OffreRepository $rep, $id, EntityManagerInterface $em): Response
    {
        $offre = $rep->find($id);
        $em->remove($offre);
        $em->flush();
        return $this->redirectToRoute('display_offre');
    }
}
