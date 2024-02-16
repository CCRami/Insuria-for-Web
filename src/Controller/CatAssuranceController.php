<?php

namespace App\Controller;
use App\Entity\CategorieAssurance;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CatAssFormType; 
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategorieAssuranceRepository;

class CatAssuranceController extends AbstractController
{
    #[Route('/assurance', name: 'app_assurance')]
    public function index(): Response
    {
        return $this->render('back/InsBack.html.twig', [
            'controller_name' => 'AssuranceController',
        ]);
    }

    #[Route('/displayCatA', name: 'display_catA')]
    public function displayIns(CategorieAssuranceRepository $catARepository): Response
{
    $catA = $catARepository->findAll();

    return $this->render('back/CatInsBack.html.twig', [
        'catA' => $catA,
    ]);
}




    #[Route('/addcatA', name: 'add_catA')]
public function addIns(Request $request, EntityManagerInterface $em): Response
{
    $catA = new CategorieAssurance();
    $form = $this->createForm(CatAssFormType::class, $catA);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($catA);
        $em->flush();
        
        return $this->redirectToRoute('display_catA');
    }

    return $this->render('back/AddCatIns.html.twig', [
        'form' => $form->createView(),
    ]);
}


    #[Route('/edit/{id}', name: 'edit_catA')]
    public function editIns(Request $request, EntityManagerInterface $em, CategorieAssuranceRepository $rep, int $id): Response
    {
        $catA = $rep->find($id);
        $form = $this->createForm(CatAssFormType::class, $catA);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($catA);
            $em->flush();
            
            return $this->redirectToRoute('display_catA');
        }
    
        return $this->render('back/AddCatIns.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    // Function to delete an author
    #[Route('/delete/{id}', name: 'delete_catA')]
    public function deleteIns(CategorieAssuranceRepository $rep, $id, EntityManagerInterface $em): Response
    {
        $author = $rep->find($id);
        $em->remove($author);
        $em->flush();
        return $this->redirectToRoute('display_catA');
    }

}
