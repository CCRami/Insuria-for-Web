<?php

namespace App\Controller;

use App\Repository\AvisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Agence;
use App\Entity\Avis;
use App\Form\avisadd;
use App\Form\avisType;

use App\Repository\AgenceRepository;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class AvisController extends AbstractController
{
    #[Route('/avis', name: 'app_avis')]
    public function index(): Response
    {
        return $this->render('avis/avisback.html.twig', [
            'controller_name' => 'AvisController',
        ]);
    }

    #[Route('/afficheravis', name: 'app_afficheravis')]
    public function listavis(Request $request,AvisRepository $repository ,PaginatorInterface $paginator)
    {$avisQuery = $repository->createQueryBuilder('r')
        ->orderBy('r.id', 'DESC')
        ->getQuery();
        $pagination = $paginator->paginate(
            $avisQuery, 
            $request->query->getInt('page', 1), 
            7
        ); return $this->render('avis/avisback.html.twig',['listX' =>  $repository->findAll(),
        'pagination' => $pagination, ]);
        
    }




    #[Route('/afficheravisc', name: 'app_afficheravisc')]
    public function listavisc(AvisRepository $repository)
    {
        $list= $repository->findAllbyetat();
        return $this->render('avis/avisfront.html.twig',['listX' => $list, ]);
    }
    #[Route('/deleteavis/{id}', name: 'deleteavis')]
    public function deleteAvis(AvisRepository $rep,$id,EntityManagerInterface $em):Response
    {
        $avis=new Avis();
        $avis=$rep->find($id);
        $em->remove($avis);
        $em->flush();
       return $this->redirectToRoute('app_afficheravis');

        
    }
    #[Route('/deleteavisc/{id}', name: 'deleteavisc')]
    public function deleteAvisc(AvisRepository $rep,$id,EntityManagerInterface $em):Response
    {
        $avis=new Avis();
        $avis=$rep->find($id);
        $em->remove($avis);
        $em->flush();
       return $this->redirectToRoute('app_afficheravisc');

        
    }

    #[Route('/ajouteravis/{id}', name: 'addavis')]
    public function addAAvis(Request $request,EntityManagerInterface $em,$id):Response
    {
        $avis=new Avis();
       $agence = $em->getRepository(Agence::class)->find($id); $avis->setEtat(false);
       $avis->setAgenceav($agence);
       $avis->setdate_avis(new \DateTime());
        $form=$this->createForm(avisadd::class,$avis);
        $form->add('save',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        { 
            /*
            $dictionaryPath = '/path/to/dictionary/file.txt';

            // Set the dictionary for the Builder class
            Builder::setDictionary($dictionaryPath);
            */
            $content = $avis->getCommentaire();
            $cleanedContenu = \ConsoleTVs\Profanity\Builder::blocker($content)->filter();
            $avis->setCommentaire($cleanedContenu);
            $user=$this->getUser();
            $avis->setAvis($user);
            $em->persist($avis);
            $em->flush();
            return $this->redirectToRoute('app_afficheravisc');
        }
        return $this->render('avis/ajouteravis.html.twig',['form'=>$form->createView()]);

    }

    #[Route('/avisbyclient/{id}', name:'SearchByclient')]
    function Rechercheavisbyclient(AvisRepository $repo, $id){
       
        $x=$repo->findavisbyclient($id);
        
        return $this->render('avis/avisback.html.twig',['listX' => $x, ]);
       }

       #[Route('/avisbyagence/{id}', name:'SearchByagence')]
       function Rechercheavisbyag(AvisRepository $repo, $id){
           
           $x=$repo->findavisbyagence($id);
           $averageRating = $repo->findAverageRatingByAgence($id);
           return $this->render('avis/avisbackbyag.html.twig',['listX' => $x,'averageRating' => $averageRating, ]);
          }

          #[Route('/avisbyagencec/{id}', name:'SearchfrontByagence')]
          function Rechercheavisbyagfront(AvisRepository $repo, $id){
           
            $averageRating = $repo->findAverageRatingByAgence($id);
              $x=$repo->findavisbyagence($id);
              $averageRating = $repo->findAverageRatingByAgence($id);
              return $this->render('avis/avisbyagence.html.twig',['listX' => $x, 'averageRating' => $averageRating, ]);
             }

          #[Route('/mesavis', name:'Searchmesavis')]
          function Recherchmesavis(AvisRepository $repo){
             $user=$this->getUser();
              $x=$repo->findavisbyid($user->getId());
              
              return $this->render('avis/mesavis.html.twig',['listX' => $x, ]);
             }
             #[Route('/editavis/{id}', name: 'avis_edit')]
             public function editavis(Request $request,EntityManagerInterface $em,AvisRepository $rep,int $id):Response
             {
                 $avis=new Avis();
                 $avis=$rep->find($id);
                 $form=$this->createForm(avisType::class,$avis);
                 $form->add('save',SubmitType::class);
                 $form->handleRequest($request);
                 if($form->isSubmitted() && $form->isValid())
                 { 
                     $em->persist($avis);
                     $em->flush();
                     return $this->redirectToRoute('app_afficheravisc');
                 }
                 return $this->render('avis/ajouteravis.html.twig',['form'=>$form->createView()]);
         
             }

             #[Route('/editetatavis/{id}', name: 'avis_etatedit')]
             public function editavisetat(EntityManagerInterface $entityManager,AvisRepository $rep,int $id):Response
             {
                 $avis=new Avis();
                 $avis=$rep->find($id);
                 $avis->setEtat(true);
                 $entityManager->flush();

                     return $this->redirectToRoute('app_afficheravis');
                 
                 
         
             }
    
}
