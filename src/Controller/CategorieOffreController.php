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
            
            // Check if an image has been uploaded
            if ($imageFile) {
                // Generate a unique name for the file
                $fileName = uniqid().'.'.$imageFile->guessExtension();
                
                // Move the file to the directory where images are stored
                try {
                    $imageFile->move(
                        $this->getParameter('offre_images_directory'), // Directory defined in services.yaml
                        $fileName
                    );
                } catch (FileException $e) {
                    // Handle file upload error
                }
                
                // Set the image file name to the offre entity
                $catoffre->setCatimg($fileName);
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
            
            // Check if a new image has been uploaded
            if ($imageFile) {
                // Generate a unique name for the file
                $fileName = uniqid().'.'.$imageFile->guessExtension();
                
                // Move the file to the directory where images are stored
                try {
                    $imageFile->move(
                        $this->getParameter('offre_images_directory'), // Directory defined in services.yaml
                        $fileName
                    );
                } catch (FileException $e) {
                    // Handle file upload error
                }
                
                // Set the new image file name to the offre entity
                $catoffre->setCatimg($fileName);
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
