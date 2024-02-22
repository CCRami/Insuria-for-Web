<?php

namespace App\Controller;
use App\Entity\Commande;
use App\Entity\User;
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
use Symfony\Component\Form\FormError;

class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'app_commande')]
    public function index(): Response
    {
        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
        ]);
    }


    #[Route('/displayComB', name: 'display_comb')]
    public function displayIns(CommandeRepository $commandeRepository): Response
{
    $commande = $commandeRepository->findAll();

    return $this->render('back/CommandeBack.html.twig', [
        'command' => $commande,
    ]);
}
    
    

    #[Route('/displayCom/{idc}', name: 'display_com')]
    public function CommandDetails(EntityManagerInterface $em, int $idc): Response
    {
        $command = $em->getRepository(Commande::class)->find($idc);
    
        return $this->render('front/DisplayCommande.html.twig', [
            'command' => $command,
            'idc' => $idc,
        ]);
    }
    




#[Route('/addcom/{id}', name: 'add_com')]
public function addCom(Request $request, EntityManagerInterface $em, int $id): Response
{
    $assurance = $em->getRepository(Assurance::class)->find($id);
    // Get the doa values from the Assurance entity
    $doaValues = $assurance->getDoa();
    $user = $em->getRepository(User::class)->find('1');
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

        // Set the date_effet property to the current date
        $com->setDateEffet(new DateTime());

        // Set the date_expedition property to the current date plus one year
        $dateExpedition = new DateTime();
        $dateExpedition->modify('+1 year');
        $com->setDateExp($dateExpedition);

        // Set the default value for the montant property
      

        // Set the doafull property of the Commande entity
        $doaData = [];
        foreach ($form->all() as $childForm) {
            $childName = $childForm->getName();
            if (strpos($childName, 'doa_') === 0) {
                $doaData[$childName] = $childForm->getData();
            }

        }
        $com->setFullDoa($doaData);
        $com->setDoaCom($assurance);
        $com->setUser($user);

        $baseMontant = $assurance->getMontant();

        // Calculate the additional value based on the form data
        $insvalue = $form->get('insvalue')->getData();
        $additionalValue = $insvalue * 0.0075;

        // Calculate the final montant
        $finalMontant = $baseMontant + $additionalValue;

        // Set the montant property of the Commande entity
        $com->setMontant($finalMontant);
        // Process form data and redirect
        $em->persist($com);
        $em->flush();

        $idc = $com->getId(); 
        // Redirect to the display_com route with the ID of the newly created command
        return $this->redirectToRoute('display_com', ['id' => $id, 'idc' => $idc]);
    }

    return $this->render('front/CommandeFront.html.twig', [
        'form' => $form->createView(),
        'id' => $id,
    ]);
}




#[Route('/editcom/{id}', name: 'edit_com')]
public function editCom(Request $request, EntityManagerInterface $em, CommandeRepository $rep, int $id): Response
{
    // Retrieve the existing command entity
    $com = $rep->find($id);
    
    // Check if the command entity exists
    if (!$com) {
        throw $this->createNotFoundException('Command not found');
    }
   
    // Retrieve the associated assurance entity from the command entity
    $assurance = $com->getDoaCom();
   
    // Retrieve the doa values from the associated assurance entity if available
    $doaValues = $assurance ? $assurance->getDoa() : [];

    // Create the form and bind it to the existing command entity
    $form = $this->createForm(CommandeFormType::class, $com, [
        'doa_values' => $doaValues,
    ]);
    
    // Loop through doa values and set initial data for each doa_ field
    foreach ($doaValues as $index => $value) {
        $fieldName = 'doa_' . $index;
        if ($form->has($fieldName)) {
            $form->get($fieldName)->setData($value);
        }
    }
    
    // Handle form submission
    $form->handleRequest($request);
    
    // Process form submission and entity update
    if ($form->isSubmitted() && $form->isValid()) {
        // Persist changes to the database
        $em->flush();
        
        // Recalculate the final montant
        $baseMontant = $assurance->getMontant();
        $insvalue = $form->get('insvalue')->getData();
        $additionalValue = $insvalue * 0.0075;
        $finalMontant = $baseMontant + $additionalValue;

        // Set the montant property of the Commande entity
        $com->setMontant($finalMontant);
        
        // Persist the updated montant to the database
        $em->flush();
        
        // Redirect to the appropriate route
        return $this->redirectToRoute('display_com', ['idc' => $com->getId()]);
    }

    // Render the form for editing
    return $this->render('front/CommandeFront.html.twig', [
        'form' => $form->createView(),
        'id' => $id,
        'command' => $com,
    ]);
}



    
    #[Route('/deletecom/{id}', name: 'delete_com')]
    public function deleteCom(CommandeRepository $rep, $id, EntityManagerInterface $em): Response
    {
        $com = $rep->find($id);
        $em->remove($com);
        $em->flush();
        return $this->redirectToRoute('display_comb');
    }

}
