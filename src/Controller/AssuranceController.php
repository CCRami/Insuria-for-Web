<?php

namespace App\Controller;
use App\Entity\Assurance;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AssuranceFormType; 
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AssuranceRepository;

class AssuranceController extends AbstractController
{
    #[Route('/assurance', name: 'app_assurance')]
    public function index(): Response
    {
        return $this->render('back/InsBack.html.twig', [
            'controller_name' => 'AssuranceController',
        ]);
    }

    #[Route('/displayIns', name: 'display_assurance')]
    public function displayIns(AssuranceRepository $assuranceRepository): Response
{
    $assurances = $assuranceRepository->findAll();

    return $this->render('back/InsBack.html.twig', [
        'assurances' => $assurances,
    ]);
}

#[Route('/displayInsF', name: 'display_assuranceF')]
public function displayInsF(AssuranceRepository $assuranceRepository): Response
{
$assurances = $assuranceRepository->findAll();

return $this->render('front/InsFront.html.twig', [
    'assurances' => $assurances,
]);
}




    #[Route('/addAss', name: 'add_assurance')]
public function addIns(Request $request, EntityManagerInterface $em): Response
{
    $assurance = new Assurance();
    $form = $this->createForm(AssuranceFormType::class, $assurance);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($assurance);
        $em->flush();
        
        return $this->redirectToRoute('display_assurance');
    }

    return $this->render('back/AddIns.html.twig', [
        'form' => $form->createView(),
    ]);
}


    #[Route('/editAss/{id}', name: 'edit_assurance')]
    public function editIns(Request $request, EntityManagerInterface $em, AssuranceRepository $rep, int $id): Response
    {
        $assurance = $rep->find($id);
        $form = $this->createForm(AssuranceFormType::class, $assurance);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($assurance);
            $em->flush();
            
            return $this->redirectToRoute('display_assurance');
        }
    
        return $this->render('back/AddIns.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    // Function to delete an author
    #[Route('/deleteAss/{id}', name: 'delete_assurance')]
    public function deleteIns(AssuranceRepository $rep, $id, EntityManagerInterface $em): Response
    {
        $author = $rep->find($id);
        $em->remove($author);
        $em->flush();
        return $this->redirectToRoute('display_assurance');
    }

}
