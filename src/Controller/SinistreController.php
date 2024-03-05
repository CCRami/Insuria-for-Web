<?php

namespace App\Controller;

use App\Repository\SinistreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Sinistre;
use App\Form\SinistreType;
use Symfony\Component\String\Slugger\SluggerInterface;




class SinistreController extends AbstractController
{
    #[Route('/sinistre', name: 'app_sinistre')]
    public function index(SinistreRepository $rep ): Response
    {
        $list=$rep->findAll(); 
        return $this->render('back/sinistre.html.twig', 
        ['list' => $list, ]
        );
    }
    #[Route('/sinistrefront', name: 'front_sinistres')]
    public function listSinistres(SinistreRepository $sinistreRepository): Response
    {
        $sinistre = $sinistreRepository->findAll();

        return $this->render('front/sinistre.html.twig', [
            'sinistre' => $sinistre,
        ]);
    }
    #[Route('/sinistre/edit/{id}', name: 'sinistre_edit')]
public function editsini(Request $request, EntityManagerInterface $em, SinistreRepository $rep, int $id, SluggerInterface $slugger): Response
{
    $rec = $rep->find($id);
    $originalImage = $rec->getImagePath();
    $form = $this->createForm(SinistreType::class, $rec);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $file = $form->get('imagePath')->getData();
        if ($file) {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

            
            $sinistreDirectory = $this->getParameter('kernel.project_dir').'/public/uploads/sinistres';
            $file->move($sinistreDirectory, $newFilename);

            $rec->setImagePath($newFilename);
        } else {
            
            $rec->setImagePath($originalImage);
        }

        $em->persist($rec);
        $em->flush();

        return $this->redirectToRoute('app_sinistre');
    }

    return $this->render('back/sinistreedit.html.twig', [
        'form' => $form->createView(),
    ]);
}

    #[Route('/sinistre/new', name: 'sinistre_new')]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $sinistre = new Sinistre();
        $form = $this->createForm(SinistreType::class, $sinistre );
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('imagePath')->getData(); 
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                
                $safeFilename = $slugger->slug($originalFilename);
                
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
    
                
                $sinistreDirectory = $this->getParameter('kernel.project_dir').'/public/uploads/sinistres';
                $file->move($sinistreDirectory, $newFilename);
    
                
                $sinistre->setImagePath($newFilename);
            }
    
            $entityManager->persist($sinistre);
            $entityManager->flush();
    
            
            return $this->redirectToRoute('app_sinistre');
        }
    
        return $this->render('back/sinistreadd.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
#[Route('/sinistre/delete/{id}', name: 'sinistre_delete')]
public function deleteRec(Request $req, SinistreRepository $rep, $id, EntityManagerInterface $em): Response
{
    $rec = $rep->find($id);

    if (!$rec) {
        
        $this->addFlash('error', 'Le sinistre demandé n\'existe pas.');
        return $this->redirectToRoute('app_sinistre');
    }

    $em->remove($rec);
    $em->flush();

    
    $this->addFlash('success', 'Le sinistre a été supprimé avec succès.');

    return $this->redirectToRoute('app_sinistre');
}


}
