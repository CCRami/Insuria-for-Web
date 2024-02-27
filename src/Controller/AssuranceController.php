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
use App\Controller\JsonResponse;

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

    private function determineInsurance(array $answers): array
{
    $age = $answers['age'];
    $income = $answers['income'];
    $maritalStatus = $answers['marital_status'];
    $employmentStatus = $answers['employment_status'];
    $healthStatus = $answers['health_status'];
    $riskTolerance = $answers['risk_tolerance'];
    $assets = $answers['assets'];
    $liabilities = $answers['liabilities'];
   
    $financialGoals = $answers['financial_goals'];
    $preferredCoverage = $answers['preferred_coverage'];
   
    $geographicFactors = $answers['geographic_factors'];
   

    
    $insuranceOptions = [
        'Life Insurance' => 0,
        'Health Insurance' => 0,
        'Car Insurance' => 0,
        'Home Insurance' => 0,
        'Disability Insurance' => 0,
    ];

    
    if ($age == 'under_30') {
        $insuranceOptions['Life Insurance'] += 5;
        $insuranceOptions['Health Insurance'] += 2;
        $insuranceOptions['Car Insurance'] += 1;
    } elseif ($age == '30_50') {
        $insuranceOptions['Life Insurance'] += 3;
        $insuranceOptions['Health Insurance'] += 3;
        $insuranceOptions['Car Insurance'] += 2;
    } elseif ($age == 'over_50') {
        $insuranceOptions['Life Insurance'] += 2;
        $insuranceOptions['Health Insurance'] += 5;
        $insuranceOptions['Car Insurance'] += 1;
        $insuranceOptions['Home Insurance'] += 3;
    }

    
    if ($income == 'lt_15000') {
        $insuranceOptions['Life Insurance'] += 3;
        $insuranceOptions['Health Insurance'] += 3;
        $insuranceOptions['Home Insurance'] += 2;
        $insuranceOptions['Disability Insurance'] += 2;
    } elseif ($income == '15000_30000') {
        $insuranceOptions['Health Insurance'] += 2;
        $insuranceOptions['Car Insurance'] += 1;
    } elseif ($income == 'gt_30000') {
        $insuranceOptions['Car Insurance'] += 2;
        $insuranceOptions['Disability Insurance'] += 1;
    }

    
    if ($maritalStatus == 'married') {
        $insuranceOptions['Life Insurance'] += 3;
        $insuranceOptions['Home Insurance'] += 2;
    }

    
    if ($employmentStatus == 'employed_full_time') {
        $insuranceOptions['Life Insurance'] += 2;
        $insuranceOptions['Disability Insurance'] += 3;
    }

    
    
    if ($healthStatus == 'good') {
        $insuranceOptions['Life Insurance'] += 3;
        $insuranceOptions['Health Insurance'] += 3;
    } elseif ($healthStatus == 'fair') {
        $insuranceOptions['Health Insurance'] += 2;
    } elseif ($healthStatus == 'poor') {
        $insuranceOptions['Health Insurance'] += 3;
        $insuranceOptions['Disability Insurance'] += 3;
    }

    
    if ($riskTolerance == 'low_risk_tolerance') {
        $insuranceOptions['Life Insurance'] += 2;
        $insuranceOptions['Health Insurance'] += 1;
        $insuranceOptions['Car Insurance'] += 1;
    } elseif ($riskTolerance == 'high_risk_tolerance') {
        $insuranceOptions['Life Insurance'] += 3;
        $insuranceOptions['Car Insurance'] += 2;
    }

   
    if ($assets > $liabilities) {
        $insuranceOptions['Life Insurance'] += 2;
        $insuranceOptions['Health Insurance'] += 1;
        $insuranceOptions['Home Insurance'] += 2;
    } elseif ($liabilities > $assets) {
        $insuranceOptions['Car Insurance'] += 2;
        $insuranceOptions['Disability Insurance'] += 1;
    }

    

    // Apply scoring based on financial goals
    if ($financialGoals == 'retirement') {
        $insuranceOptions['Life Insurance'] += 3;
        $insuranceOptions['Disability Insurance'] += 2;
    }

    // Apply scoring based on preferred coverage
    if ($preferredCoverage == 'high_coverage') {
        $insuranceOptions['Life Insurance'] += 2;
        $insuranceOptions['Health Insurance'] += 2;
        $insuranceOptions['Disability Insurance'] += 2;
    }

    
    if ($geographicFactors === null) {
        $geographicFactors = [];
    }
    // Apply scoring based on geographic factors
    if ($geographicFactors === 'urban') {
        $insuranceOptions['Car Insurance'] += 1;
        $insuranceOptions['Home Insurance'] += 1;
    } elseif ($geographicFactors === 'rural') {
        $insuranceOptions['Home Insurance'] += 2;
    }
    
    

    // Determine the insurance types with the highest scores
    arsort($insuranceOptions); // Sort the options by score in descending order
    $topInsuranceTypes = array_keys(array_slice($insuranceOptions, 0, 5, true)); // Get the top 5 insurance types
    
    return $topInsuranceTypes;
}


    #[Route('/assurance-recommendation', name: 'assurance_recommendation')]
    public function recommendation(Request $request): Response
    {
        $form = $this->createForm(QuestionnaireFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $answers = $form->getData();
            
    $assurance = $this->determineInsurance($answers);
   
            return $this->render('front/Recommendation.html.twig', [
                'recommendation' => $assurance,
            ]);
        }

        return $this->render('front/Questionnaire.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    


}
