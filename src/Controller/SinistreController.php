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
    #[Route('/sinistre/edit/{id}', name: 'sinistre_edit')]
    public function editsini(Request $request,EntityManagerInterface $em,SinistreRepository $rep,int $id):Response
  {
        $rec=new Sinistre();
        $rec=$rep->find($id);
        $form=$this->createForm(SinistreType::class,$rec);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        { 
            $em->persist($rec);
            $em->flush();
            return $this->redirectToRoute('app_sinistre');
        }
        return $this->render('back/sinistreedit.html.twig',['form'=>$form->createView()]);

    }
    #[Route('/sinistre/new', name: 'sinistre_new')]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $sinistre = new Sinistre();
    $form = $this->createForm(SinistreType::class, $sinistre);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($sinistre);
        $entityManager->flush();

        // Redirection après l'ajout
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
        // Gérez le cas où le sinistre n'existe pas, par exemple, en ajoutant un message flash d'erreur
        $this->addFlash('error', 'Le sinistre demandé n\'existe pas.');
        return $this->redirectToRoute('app_sinistre');
    }

    $em->remove($rec);
    $em->flush();

    // Ajouter ici un message flash de succès si désiré
    $this->addFlash('success', 'Le sinistre a été supprimé avec succès.');

    return $this->redirectToRoute('app_sinistre');
}


}
