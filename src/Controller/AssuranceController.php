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
use App\Repository\CategorieAssuranceRepository;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

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
public function displayInsF(AssuranceRepository $assuranceRepository,CategorieAssuranceRepository $categorieAssuranceRepository): Response
{
$assurances = $assuranceRepository->findAll();
$catA=$categorieAssuranceRepository->findAll();

return $this->render('front/InsFront.html.twig', [
    'assurances' => $assurances,
    'categories' => $catA,
]);
}





#[Route('/addAss', name: 'add_assurance')]
public function addIns(Request $request, EntityManagerInterface $em): Response
{
    $assurance = new Assurance();
    $form = $this->createForm(AssuranceFormType::class, $assurance);
    
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Handle file upload
        $imageFile = $form['insimage']->getData();

        // Check if a file was uploaded
        if ($imageFile) {
            // Generate a unique file name
            $newFilename = md5(uniqid()).'.'.$imageFile->guessExtension();
            // Move the file to the desired directory
            $imageFile->move(
                $this->getParameter('image_directory'), // Define 'image_directory' in your parameters.yaml
                $newFilename
            );
            // Store the file name in the entity property
            $assurance->setInsImage($newFilename);
        }

        $doaData = $form->get('doa')->getData();


        // Persist and flush the entity
        $em->persist($assurance);
        $em->flush();
        
        // Redirect to the appropriate route
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
    $originalImage = $assurance->getInsImage(); // Keep track of the original image filename

    $form = $this->createForm(AssuranceFormType::class, $assurance);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
        // Handle file upload for image
        $imageFile = $form['insimage']->getData();
        if ($imageFile) {
            // Generate a unique filename and move the file
            $newFilename = md5(uniqid()).'.'.$imageFile->guessExtension();
            $imageFile->move(
                $this->getParameter('image_directory'), // Define 'image_directory' in your parameters.yaml
                $newFilename
            );
            $assurance->setInsImage($newFilename);
        } else {
            // If no new image is uploaded, keep the original image filename
            $assurance->setInsImage($originalImage);
        }

        $em->persist($assurance);
        $em->flush();
        
        return $this->redirectToRoute('display_assurance');
    }

    return $this->render('back/AddIns.html.twig', [
        'form' => $form->createView(),
    ]);
}
    

    
    #[Route('/deleteAss/{id}', name: 'delete_assurance')]
    public function deleteIns(AssuranceRepository $rep, $id, EntityManagerInterface $em): Response
    {
        $author = $rep->find($id);
        $em->remove($author);
        $em->flush();
        return $this->redirectToRoute('display_assurance');
    }


    private function generateUniqueFileName(UploadedFile $file): string
    {
        // Generate a unique filename based on the original filename and a unique identifier
        return md5(uniqid()).'.'.$file->guessExtension();
    }


}
