<?php

namespace App\Controller;
use App\Entity\Commande;
use App\Entity\Assurance;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CommandeRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CommandeFormType; 
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;
use Symfony\Component\Form\FormInterface;

class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'app_commande')]
    public function index(): Response
    {
        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
        ]);
    }

    
    #[Route('/displayCom', name: 'display_com')]
    public function displayCom(CommandeRepository $catARepository): Response
{
    $catA = $catARepository->findAll();

    return $this->render('back/CatInsBack.html.twig', [
        'catA' => $catA,
    ]);
}


#[Route('/addcom/{id}', name: 'add_com')]
public function addCom(Request $request, EntityManagerInterface $em, int $id): Response
{
    $assurance = $em->getRepository(Assurance::class)->find($id);
    // Get the doa values from the Assurance entity
    $doaValues = $assurance->getDoa();

    // Create a new instance of Commande entity
    $com = new Commande();

    // Create the form and pass the doa values to it
    $form = $this->createForm(CommandeFormType::class, $com, [
        'doa_values' => $doaValues,
    ]);

    // Handle form submission
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Process form data and redirect
        // $entityManager->persist($com);
        // $entityManager->flush();

        return $this->redirectToRoute('display_com');
    }

    return $this->render('front/CommandeFront.html.twig', [
        'form' => $form->createView(),
        'id' => $id,
    ]);
}


    #[Route('/editcom/{id}', name: 'edit_com')]
    public function editCom(Request $request, EntityManagerInterface $em, CommandeRepository $rep, int $id): Response
    {
        $com = $rep->find($id);
        $form = $this->createForm(CommandeFormType::class, $com);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($com);
            $em->flush();
            
            return $this->redirectToRoute('display_catA');
        }
    
        return $this->render('back/AddCatIns.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    
    #[Route('/deletecom/{id}', name: 'delete_com')]
    public function deleteCom(CommandeRepository $rep, $id, EntityManagerInterface $em): Response
    {
        $com = $rep->find($id);
        $em->remove($com);
        $em->flush();
        return $this->redirectToRoute('display_catA');
    }

}
