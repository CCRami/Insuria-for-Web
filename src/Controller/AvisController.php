<?php

namespace App\Controller;

use App\Repository\AvisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Agence;
use App\Entity\Avis;
use App\Form\avisadd;
use App\Repository\AgenceRepository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

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
    public function listagence(AvisRepository $repository)
    {
        $list= $repository->findAll();
        return $this->render('avis/avisback.html.twig',['listX' => $list, ]);
    }
    #[Route('/deleteavis/{id}', name: 'name_deleteavis')]
    public function deleteAuthor(AvisRepository $rep,$id,EntityManagerInterface $em):Response
    {
        $auth=new Avis();
        $auth=$rep->find($id);
        $em->remove($auth);
        $em->flush();
       return $this->redirectToRoute('app_afficheravis');

        
    }

    #[Route('/ajouteravis/{id}', name: 'name_addavis')]
    public function addAAvis(Request $request,EntityManagerInterface $em,$id):Response
    {
        $auth=new Avis();
       // $auth=$rep->find($id);
       $agence = $em->getRepository(Agence::class)->find($id);
       $auth->setAgenceav($agence);
      // $auth->setAgenceav($id);
        $form=$this->createForm(avisadd::class,$auth);
        $form->add('save',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        { 
            $em->persist($auth);
            $em->flush();
            return $this->redirectToRoute('app_afficheravis');//elli mte3ou
        }
        return $this->render('avis/ajouteravis.html.twig',['form'=>$form->createView()]);

    }

    #[Route('/avisbyclient/{id}', name:'SearchByclient')]
    function Rechercheavisbyclient(AvisRepository $repo, $id){
        //$x=$request->get('LaRef');
        $x=$repo->findavisbyclient($id);
        
        return $this->render('avis/avisback.html.twig',['listX' => $x, ]);
       }

       #[Route('/avisbyagence/{id}', name:'SearchByagence')]
       function Rechercheavisbyag(AvisRepository $repo, $id){
           //$x=$request->get('LaRef');
           $x=$repo->findavisbyagence($id);
           
           return $this->render('avis/avisback.html.twig',['listX' => $x, ]);
          }

          #[Route('/mesavis/{id}', name:'Searchmes avis')]
          function Recherchmesavis(AvisRepository $repo, $id){
              //$x=$request->get('LaRef');
              $x=$repo->findavisbyid($id);
              
              return $this->render('avis/mesavis.html.twig',['listX' => $x, ]);
             }
    
    
}
