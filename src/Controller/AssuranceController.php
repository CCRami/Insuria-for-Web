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
use App\Form\QuestionnaireFormType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Form\ChatType;
use App\Service\ChatGPTClient;


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
       
        $imageFile = $form['insimage']->getData();

        
        if ($imageFile) {
            
            $newFilename = md5(uniqid()).'.'.$imageFile->guessExtension();
           
            $imageFile->move(
                $this->getParameter('image_directory'), 
                $newFilename
            );
            
            $assurance->setInsImage($newFilename);
        }

        $doaData = $form->get('doa')->getData();


       
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
    $originalImage = $assurance->getInsImage(); 

    $form = $this->createForm(AssuranceFormType::class, $assurance);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
        
        $imageFile = $form['insimage']->getData();
        if ($imageFile) {
            
            $newFilename = md5(uniqid()).'.'.$imageFile->guessExtension();
            $imageFile->move(
                $this->getParameter('image_directory'), 
                $newFilename
            );
            $assurance->setInsImage($newFilename);
        } else {
           
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
        
        return md5(uniqid()).'.'.$file->guessExtension();
    }

   

    #[Route('/assurance-recommendation', name: 'assurance_recommendation', methods: ['GET', 'POST'])]
    public function recommendation(Request $request, ChatGPTClient $chatGPTClient): Response
    {
        $form = $this->createForm(QuestionnaireFormType::class);
        $form->handleRequest($request);
        $answer = null; // Initialize answer variable
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Get form data and modify the prompt
            $age = $form->get('age')->getData();
            $income = $form->get('income')->getData();
            $status = $form->get('marital_status')->getData();
            $employment = $form->get('employment_status')->getData();
            $health = $form->get('health_status')->getData();
            $risk = $form->get('risk_tolerance')->getData();
            $assets = $form->get('assets')->getData();
            $liabilities = $form->get('liabilities')->getData();
            $goals = $form->get('financial_goals')->getData();
            $coverage = $form->get('preferred_coverage')->getData();
            $location = $form->get('geographic_factors')->getData();
            
            // Construct the prompt
            $prompt = "What is your age? $age What is your annual income? $income What is your marital status? $status What is your employment status? $employment How is your health? $health What is your risk tolerance? $risk Total value of your assets? $assets Total value of your liabilities? $liabilities What are your financial goals? $goals What is your preferred coverage level? $coverage What is your geographic location? $location According to the questions above, just answer me with one of these insurance types only: auto, health, home";
            
            // Get response from ChatGPT
            $answer = $chatGPTClient->getAnswer($prompt);
        }
    
        return $this->render('front/Questionnaire.html.twig', [
            'form' => $form->createView(),
            'answer' => $answer,
        ]);
    }


    #[Route('/filter-assurances', name: 'filter_assurances', methods: ['POST'])]
public function filterAssurances(Request $request, AssuranceRepository $assuranceRepository): JsonResponse
{
    // Get the category ID from the AJAX request
    $categoryId = $request->request->get('catA');

    // Retrieve assurances based on the selected category
    $assurances = $assuranceRepository->findBy(['catA' => $categoryId]);

    // Transform assurances into an array of data to return
    $assuranceData = [];
    foreach ($assurances as $assurance) {
        $assuranceData[] = [
            'id' => $assurance->getId(),
            'insname' => $assurance->getNameIns(), // Adjust as per your Assurance entity properties
            // Add more properties as needed
        ];
    }

    // Return the filtered assurances as a JSON response
    return new JsonResponse($assuranceData);
}

    


}
