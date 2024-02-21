<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use App\Form\ReclamationEditType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use App\Service\FileUploader;
class ReclamationController extends AbstractController
{
    #[Route('/reclamation', name: 'app_reclamation')]
    public function index(): Response
    {
        return $this->render('reclamation/ReclamationsUser.html.twig', [
            'controller_name' => 'ReclamationController',
        ]);
    }
    #[Route('/tableReclamations', name: 'app_reclamation_admin')]
    public function index_reclamation_admin(ReclamationRepository $rep): Response
    {$list=$rep->findAll();
        return $this->render('reclamation\ReclamationBack.html.twig', 
          ['list' => $list, ]
        );
    }
    #[Route('listReclamation/edit/{id}', name: 'reclamationAdmin_edit')]
    public function editRec(Request $request, EntityManagerInterface $em, ReclamationRepository $rep, int $id): Response
    {
        $rec = $rep->find($id);
        $form = $this->createForm(ReclamationEditType::class, $rec);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
    
            if ($rec->isReponse() === 'refused') {
                // Redirection vers l'action reclamation_refuse en passant l'ID de la réclamation
                return $this->redirectToRoute('reclamation_refuse', ['reclamationId' => $rec->getId()]);
            } elseif ($rec->isReponse() === 'accepted') {
                // Redirection vers l'action reclamation_accepte en passant l'ID de la réclamation
                return $this->redirectToRoute('reclamation_accepte', ['reclamationId' => $rec->getId()]);
            } else {
                // Redirection par défaut si la réponse n'est ni acceptée ni refusée
                return $this->redirectToRoute('app_reclamation_admin');
            }
        }
        return $this->render('reclamation/formAdmin.html.twig', [
            'form' => $form->createView(),
            'reclamation' => $rec, // Passer la réclamation au template
        ]);
    }
    



    #[Route('list/delete/{id}', name: 'rec_delete')]
    public function deleteRec(Request $req,ReclamationRepository $rep,$id,EntityManagerInterface $em):Response
    {
        $rec=new Reclamation();
        $rec=$rep->find($id);
        $indemnisation = $rec->getIndemnissation();
        if ($indemnisation) {
            $em->remove($indemnisation);}
        $em->remove($rec);
        $em->flush();
        return $this->redirectToRoute('app_reclamation_admin');

        
    }  
    #[Route('Reclamation/detail/{id}', name: 'reclamationAdmin_show')]
    public function showRec(Request $request, ReclamationRepository $repository, $id): Response
    {
       
        $reclamation = $repository->find($id);
    
        
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }
    
    #[Route('Reclamation/new', name: 'reclamationAdmin_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $rec = new Reclamation();
    $form = $this->createForm(ReclamationType::class, $rec);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $file = $form->get('file')->getData();
        if ($file) {
          
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('upload_directory'),
                $fileName
            );
            // Enregistrez le nom du fichier dans l'entité Reclamation si nécessaire
            $rec->setFileName($fileName);}
        
        $entityManager->persist($rec);
        $entityManager->flush();

       
        return $this->redirectToRoute('app_reclamation_admin');
    }

    return $this->render('reclamation/addreclamation.html.twig', [
        'form' => $form->createView(),
    ]);
}

//User
#[Route('user/newReclamation', name: 'reclamationUser_new')]
public function newReclamation(Request $request, EntityManagerInterface $entityManager): Response
{ $label = $request->query->get('label');
$rec = new Reclamation();
$label = $request->query->get('label');

$form = $this->createForm(ReclamationType::class, $rec, ['label' => $label]);
$form->handleRequest($request);
$success = false;
if ($form->isSubmitted() && $form->isValid()) {
    $file = $form->get('file')->getData();
    if ($file) {
      
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move(
            $this->getParameter('upload_directory'),
            $fileName
        );
      
        $rec->setFileName($fileName);}
    
    $entityManager->persist($rec);
    $entityManager->flush();

    $success = true;
    
}

return $this->render('reclamation/ReclamationAdd.html.twig', [
    'form' => $form->createView(),'reclamation' => $rec,'success' => $success,
]);
}



#[Route('user/typeReclamations', name: 'app_reclamation_user')]
    public function index_reclamation_user(ReclamationRepository $rep): Response
    {$list=$rep->findAll();
        return $this->render('reclamation\ReclamationFront.html.twig', 
          ['list' => $list, ]
        );
    }
    #[Route('user/reponse', name: 'showRep')]
    public function reponse_user(ReclamationRepository $rep): Response
    {
    } 

    

}