<?php

namespace App\Controller;
use App\Entity\CategorieOffre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CategorieOffreformType; 
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategorieOffreRepository;

class CategorieOffreController extends AbstractController
{
    #[Route('/catoffre', name: 'app_catoffre')]
    public function index(): Response
    {
        return $this->render('back/affichercatoffre.html.twig', [
            'controller_name' => 'CatergorieOffreController',
        ]);
    }

    #[Route('/displaycat', name: 'displayCategorieOffre')]
    public function displayCategorieOffre(CategorieOffreRepository $CategorieOffreRepository): Response
    {
        $catoffres = $CategorieOffreRepository->findAll();

        return $this->render('back/affichercatoffre.html.twig', [
            'catoffres' => $catoffres,
        ]);
    }

    #[Route('/displaycatt', name: 'displayCategorieOffref')]
    public function displayCategorieOffref(CategorieOffreRepository $CategorieOffreRepository): Response
    {
        $catoffres = $CategorieOffreRepository->findAll();

        return $this->render('front/categorieoffre.html.twig', [
            'catoffres' => $catoffres,
        ]);
    }

    #[Route('/addcat', name: 'add_catoffre')]
    public function addcatOffre(Request $request, EntityManagerInterface $em): Response
    {
        $catoffre = new CategorieOffre();
        $form = $this->createForm(CategorieOffreformType::class, $catoffre);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('catimg')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $imageFile->guessExtension();
    
                $newFilename = $originalFilename.'.'.$extension;
    
                $targetDirectory = $this->getParameter('image_directory');
    
                $imageFile->move(
                    $targetDirectory, 
                    $newFilename
                );
    
                $relativePath = 'file:C:/Users/Mon Pc/Project Insuria/Insuria/public/uploads/images/' . $newFilename;
                $catoffre->setCatimg($relativePath);
            }
           
            $em->persist($catoffre);
            $em->flush();
            
            return $this->redirectToRoute('displayCategorieOffre');
        }

        return $this->render('back/addcatoffre.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/editcat/{id}', name: 'edit_catoffre')]
    public function editcatOffre(Request $request, EntityManagerInterface $em, CategorieOffreRepository $rep, int $id): Response
    {
        $catoffre = $rep->find($id);
        $form = $this->createForm(CategorieOffreformType::class, $catoffre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('catimg')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $imageFile->guessExtension();
    
                $newFilename = $originalFilename.'.'.$extension;
    
                $targetDirectory = $this->getParameter('image_directory');
    
                $imageFile->move(
                    $targetDirectory, 
                    $newFilename
                );
    
                $relativePath = 'file:C:/Users/Mon Pc/Project Insuria/Insuria/public/uploads/images/' . $newFilename;
                $catoffre->setCatimg($relativePath);
            }
            
            $em->flush();
            
            return $this->redirectToRoute('displayCategorieOffre');
        }
    
        return $this->render('back/editcatoffre.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    #[Route('/deletecat/{id}', name: 'delete_catoffre')]
    public function deletecatOffre(CategorieOffreRepository $rep, $id, EntityManagerInterface $em): Response
    {
        $catoffre = $rep->find($id);
        $em->remove($catoffre);
        $em->flush();
        return $this->redirectToRoute('displayCategorieOffre');
    }

   
}
