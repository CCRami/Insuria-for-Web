<?php
declare(strict_types=1);
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
use GuzzleHttp\Exception\GuzzleException;
use OpenAI;
//use App\Service\ChatGPTClient;
use Exception;

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
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $imageFile->guessExtension();

            $newFilename = $originalFilename.'.'.$extension;

            $targetDirectory = $this->getParameter('image_directory');

            $imageFile->move(
                $targetDirectory, 
                $newFilename
            );

            $relativePath = 'C:\\Users\\Mon Pc\\Project Insuria\\Insuria\\public\\uploads\\images\\' . $newFilename;
            $assurance->setInsImage($relativePath);
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
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $imageFile->guessExtension();

            $newFilename = $originalFilename.'.'.$extension;

            $targetDirectory = $this->getParameter('image_directory');

            $imageFile->move(
                $targetDirectory, 
                $newFilename
            );

            $relativePath = 'C:\\Users\\Mon Pc\\Project Insuria\\Insuria\\public\\uploads\\images\\' . $newFilename;
            $assurance->setInsImage($relativePath);
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

   

   


   
   #[Route("/filter-assurances/{categoryId}", name:"filter_assurances")]
public function filterAssurances(Request $request, AssuranceRepository $assuranceRepository, $categoryId): JsonResponse
{   
    

    if ($categoryId === '*') {
    
        $assurances = $assuranceRepository->findAll();
    } else {
       
        $assurances = $assuranceRepository->findBy(['catA' => $categoryId]);
    }

    $assuranceData = [];
    foreach ($assurances as $assurance) {
        $assuranceData[] = [
            'id' => $assurance->getId(),
            'nameins' => $assurance->getNameIns(),
            'montant' => $assurance->getMontant(),
            'catA' => $assurance->getCatA()->getId(), 
            'insImage' => $assurance->getinsImage(),
        ];
    }

   
    return new JsonResponse($assuranceData);
}


#[Route('/filter-prices', name: 'filter_prices', methods: ['POST'])]
public function filterPrices(Request $request, AssuranceRepository $assuranceRepository): JsonResponse
{
    $jsonData = json_decode($request->getContent(), true);
    $sortingOption = $jsonData['sortingOption'];

  
    $assurances = $assuranceRepository->findAll();
   
   
    if ($sortingOption === 'high_to_low') {
        usort($assurances, function ($a, $b) {
            return $b->getMontant() - $a->getMontant();
        });
    } elseif ($sortingOption === 'low_to_high') {
        usort($assurances, function ($a, $b) {
            return $a->getMontant() - $b->getMontant();
        });
    }

   
    $assuranceData = [];
    foreach ($assurances as $assurance) {
        $assuranceData[] = [
            'id' => $assurance->getId(),
            'nameins' => $assurance->getNameIns(),
            'montant' => $assurance->getMontant(),
            'insImage' => $assurance->getinsImage(),
        ];
    }
    
   
    return new JsonResponse($assuranceData);
}





#[Route('/assurance-recommendation', name: 'assurance_recommendation')]
public function chat(Request $request): Response
{
    $form = $this->createForm(QuestionnaireFormType::class);
    $form->handleRequest($request);
    $answer = ''; 

    if ($form->isSubmitted() && $form->isValid()) {
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
        
      
        $prompt = "What is your age? $age What is your annual income? $income 
        What is your marital status? $status 
        What is your employment status? $employment
        How is your health? $health What is your risk tolerance? 
        $risk Total value of your assets? $assets Total value of your liabilities? 
        $liabilities What are your financial goals? $goals What is your preferred coverage level?
        $coverage What is your geographic location? $location 
        According to the questions above, just answer me with one of these insurance types and give me a brief description";
        
        $myApiKey = $_ENV['OPENAI_KEY'];

        $client = new \GuzzleHttp\Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . $myApiKey,
                'Content-Type' => 'application/json',
            ]
        ]);
    
        try {
            $response = $client->post('chat/completions', [
                'json' => [
                    'model' => 'gpt-3.5-turbo-0125',
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'max_tokens' => 2048
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            dump($result);
            if (isset($result['choices'][0]['message']['content'])) {
                $answer = $result['choices'][0]['message']['content'];
            } else {
                $answer = 'An error occurred: Unexpected response format';
            }
        } catch (\Exception $e) {
           
            $answer = 'An error occurred: ' . $e->getMessage();
        }
    }

    return $this->render('front/questionnaire.html.twig', [
        'form' => $form->createView(),
        'answer' => $answer,
    ]);
}


}

