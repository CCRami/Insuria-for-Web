<?php

namespace App\Controller;
use App\Entity\Offre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\OffreformType; 
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OffreRepository;
use App\Repository\AssuranceRepository;
use Knp\Component\Pager\PaginatorInterface;
//use App\Controller\FlashyNotifier;
use App\Controller\FlashyNotifier as ControllerFlashyNotifier;
use MercurySeries\FlashyBundle\FlashyNotifier;
use App\Repository\CommandeRepository;
use App\Entity\User;



class OffreController extends AbstractController
{
    #[Route('/offre', name: 'app_offre')]
    public function index(): Response
    {
        return $this->render('back/afficheoffre.html.twig', [
            'controller_name' => 'OffreController',
        ]);
    }

    #[Route('/displayof', name: 'display_offre')]
    public function displayOffre(OffreRepository $offreRepository, Request $request): Response
    {
        $sortField = $request->query->get('sortField', 'id'); // Default sort field is 'id'
        $sortOrder = $request->query->get('sortOrder', 'asc'); // Default sort order is 'asc'
        $offres = $offreRepository->findBy([], [$sortField => $sortOrder]);

        return $this->render('back/afficheroffre.html.twig', [
            'offres' => $offres,
            'sortField' => $sortField,
            'sortOrder' => $sortOrder,
            
        ]);
    }

    #[Route('/addof', name: 'add_offre')]
    public function addOffre(Request $request,EntityManagerInterface $em,FlashyNotifier $flashy): Response
    {
        $offre = new Offre();
        $form = $this->createForm(OffreformType::class, $offre);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $imageFile = $form->get('offreimg')->getData();
            
           
            if ($imageFile) {
               
                $fileName = uniqid().'.'.$imageFile->guessExtension();
                
                
                try {
                    $imageFile->move(
                        $this->getParameter('offre_images_directory'), 
                        $fileName
                    );
                } catch (FileException $e) {
                    
                }
                
                
                $offre->setOffreimg($fileName);
            }
            
           
            $em->persist($offre);
            $em->flush();
            $flashy->success('Offer added', 'http://your-awesome-link.com');
            
            
            return $this->redirectToRoute('display_offre');
        }
    
        return $this->render('back/addoffre.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    #[Route('/editof/{id}', name: 'edit_offre')]
public function editOffre(Request $request, EntityManagerInterface $em, OffreRepository $rep,FlashyNotifier $flashy, int $id): Response
{
    $offre = $rep->find($id);
    $form = $this->createForm(OffreformType::class, $offre);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
        
        $imageFile = $form->get('offreimg')->getData();
        
       
        if ($imageFile) {
            
            $fileName = uniqid().'.'.$imageFile->guessExtension();
            
           
            try {
                $imageFile->move(
                    $this->getParameter('offre_images_directory'), // Directory defined in services.yaml
                    $fileName
                );
            } catch (FileException $e) {
              
            }
            
          
            $offre->setOffreimg($fileName);
        }
        
       
        $em->flush();
        $flashy->success('Offer edited', 'http://your-awesome-link.com');
        
        return $this->redirectToRoute('display_offre');
    }

    return $this->render('back/editoffre.html.twig', [
        'form' => $form->createView(),
    ]);
}


    #[Route('/deleteof/{id}', name: 'delete_offre')]
    public function deleteOffre(OffreRepository $rep, $id, EntityManagerInterface $em,FlashyNotifier $flashy): Response
    {
        $offre = $rep->find($id);
        $em->remove($offre);
        $em->flush();
        $flashy->success('Offer deleted', 'http://your-awesome-link.com');
        return $this->redirectToRoute('display_offre');
    }


    #[Route('/displayoff', name: 'display_offref')]
    public function displayOffref(OffreRepository $offreRepository): Response
    {
        $offres = $offreRepository->findAll();

        return $this->render('front/offre.html.twig', [
            'offres' => $offres,
        ]);
    }

    #[Route('/displayoffbycat/{id}', name: 'display_offrefbycat', methods: ['GET', 'POST'])]
    public function displayOffrefbycat(AssuranceRepository $rep,CommandeRepository $repe,OffreRepository $offreRepository, PaginatorInterface $paginator, Request $request, $id): Response
    {
        $offres = $offreRepository->findBy(['categorie' => $id]);
        $id=$this->getUser();
        $commands=$repe->findBy(['user' => $id]);
        $assurances = [];
        foreach ($commands as $command) {
            $assurancesForCommand = $rep->findBy(['id' => $command->getDoaCom()]);
            $assurances = array_merge($assurances, $assurancesForCommand);
        }
        $pagination = $paginator->paginate(
            $offres,
            $request->query->getInt('page', 1), // Get the current page from the request
            3 // Number of items per page
        );
    
        return $this->render('front/offre.html.twig', [
            'pagination' => $pagination,
            'commandes' => $commands,
            'assurances' => $assurances,
        ]);
}  




public function create(FlashyNotifier $flashy)
{
    

    $this->flashy->success('Event created!', 'http://your-awesome-link.com');

    return $this->redirectToRoute('home');
}





}
