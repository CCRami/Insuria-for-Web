<?php

namespace App\Controller;
use App\Entity\Commande;
use App\Entity\User;
use App\Entity\Assurance;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CommandeRepository;
use App\Repository\AssuranceRepository;
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


    #[Route('/basket', name: 'basket')]
    public function viewBasket(CommandeRepository $commandeRepository): Response
    {
        // Get the user with ID 1 from the database
        $userId = 1;
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);

        // Retrieve commands associated with the user from the database
        $commands = $commandeRepository->findBy(['user' => $user]);

        return $this->render('front/Basket.html.twig', [
            'commands' => $commands,
        ]);
    }
    
   


    #[Route('/displayComB', name: 'display_comb')]
    public function displayIns(CommandeRepository $commandeRepository,AssuranceRepository $assuranceRepository): Response
{
    $assurances = $assuranceRepository->findAll();
    $commande = $commandeRepository->findAll();

    // Initialize an array to hold command counts for each assurance
    $assurancesCommandsCounts = [];

    // Loop through each assurance and get command counts
    foreach ($assurances as $assurance) {
        $commandCount = $commandeRepository->getCommandChartData($assurance->getId());
        $assurancesCommandsCounts[] = [
            'assurance' => $assurance,
            'commandCount' => count($commandCount), // Assuming getCommandChartData returns an array of commands
        ];
    }

    // Total number of assurances and commands for a visualization
    $totalAssurances = count($assurances);
    $totalCommands = count($commande);

    return $this->render('back/CommandeBack.html.twig', [
        'command' => $commande,
        'totalCommands' => $totalCommands,
        'assurancesCommandsCounts' => $assurancesCommandsCounts,
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
    
    #[Route('/displayComD/{idc}', name: 'display_comD')]
    public function CommandDetailsF(EntityManagerInterface $em, int $idc): Response
    {
        $command = $em->getRepository(Commande::class)->find($idc);
    
        return $this->render('front/CommandDetails.html.twig', [
            'command' => $command,
            'idc' => $idc,
        ]);
    }




#[Route('/addcom/{id}', name: 'add_com')]
public function addCom(Request $request, EntityManagerInterface $em, int $id): Response
{
    $assurance = $em->getRepository(Assurance::class)->find($id);
    $user = $em->getRepository(User::class)->find('1');
    $doaValues = $assurance->getDoa();
    
   
    $com = new Commande();

    
    $form = $this->createForm(CommandeFormType::class, $com, [
        'doa_values' => $doaValues,
    ]);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
       

        $com->setDateEffet(new DateTime());

        
        $dateExpedition = new DateTime();
        $dateExpedition->modify('+1 year');
        $com->setDateExp($dateExpedition);

        $baseMontant = $assurance->getMontant();
        $insvalue = $form->get('insvalue')->getData();
        $additionalValue = $insvalue * 0.0075;
        $finalMontant = $baseMontant + $additionalValue;
        
      

        
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
        $com->setMontant($finalMontant);
        
        $em->persist($com);
        $em->flush();

        $idc = $com->getId(); 
        
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
    $com = $rep->find($id);
    
    $assurance = $com->getDoaCom();
    $doaValues = $assurance ? $assurance->getDoa() : [];

    $form = $this->createForm(CommandeFormType::class, $com, [
        'doa_values' => $doaValues,
    ]);

   
$data = $com->getFullDoa(); 

foreach ($data as $index => $value) {
   
    $fieldName = $index;
    $form->get($fieldName)->setData(htmlspecialchars($value)); 
    dump($fieldName);
    dump($value); 
}

    
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Handle form submission and entity updates
        try {
          
            
            $baseMontant = $assurance->getMontant();
            $insvalue = $form->get('insvalue')->getData();
            $additionalValue = $insvalue * 0.0075;
            $finalMontant = $baseMontant + $additionalValue;
           
            $com->setMontant($finalMontant);
            
            $em->flush();
            
            return $this->redirectToRoute('display_com', ['idc' => $com->getId()]);
        } catch (\Exception $e) {
            // Handle any exceptions here, e.g., log the error
            $this->addFlash('error', 'An error occurred while processing your request.');
        }
    }

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
    

    #[Route('/userInsurances', name: 'user_insurances')]
    public function userInsurances(CommandeRepository $commandeRepository): Response
    {
        // Get the user with ID 1 from the database
        $userId = 1;
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);

        if (!$user) {
            throw $this->createNotFoundException('User not found.');
        }

        // Fetch insurances associated with the user
        $insurances = $commandeRepository->findBy(['user' => $user]);

        return $this->render('front/userCommand.html.twig', [
            'insurances' => $insurances,
        ]);
    } 
    



    
}
