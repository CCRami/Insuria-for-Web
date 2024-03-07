<?php

namespace App\Controller;
use App\Form\agenceadd;
use App\Form\agenceType;

use App\Entity\Agence;
use App\Repository\AgenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\PdfService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class AgenceController extends AbstractController
{
    #[Route('/agence', name: 'app_agence')]
    public function index(): Response
    {  
        return $this->render('agence/index.html.twig', [
            'controller_name' => 'AgenceController',
    
        

        ]);
       

    }

    #[Route('/afficheragences', name: 'app_afficheragences')]
    public function listagence(AgenceRepository $repository,Request $request,PaginatorInterface $paginator)
    {
        $agenceQuery = $repository->createQueryBuilder('r')
        ->orderBy('r.nomage', 'DESC')
        ->getQuery();
        $pagination = $paginator->paginate(
            $agenceQuery, 
            $request->query->getInt('page', 1), 
            7
        );
        return $this->render('agence/agenceback.html.twig',['listX' =>  $repository->findAll(),
        'pagination' => $pagination,
     ]);
    }
    #[Route('/afficheragencesc', name: 'app_afficheragencesc')]
    public function listagencec(AgenceRepository $repository)
    {
        $list= $repository->findAll();
        return $this->render('agence/agencefront.html.twig',['listX' => $list, ]);
    }

    #[Route('/dellete/{id}', name: 'agence_delete')]
    public function deleteagence(AgenceRepository $rep,$id,EntityManagerInterface $em):Response
    {
        $agence=new Agence();
        $agence=$rep->find($id);
        $em->remove($agence);
        $em->flush();
       return $this->redirectToRoute('app_afficheragences');

        
    }
    #[Route('/ajouteragence', name: 'agence_add')]
    public function addagence(Request $request,EntityManagerInterface $em):Response
    {
        $agence=new Agence();
        $form=$this->createForm(agenceadd::class,$agence);
        $agence->setcreate_at(new \DateTime());
        $form->add('save',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        { 
            $em->persist($agence);
            $em->flush();
            return $this->redirectToRoute('app_afficheragences');
        }
        return $this->render('agence/ajouterage.html.twig',['form'=>$form->createView()]);

    }
    #[Route('/ediit/{id}', name: 'agence_edit')]
    public function editagence(Request $request,EntityManagerInterface $em,AgenceRepository $rep,int $id):Response
    {
        $agence=new Agence();
        $agence=$rep->find($id);
        $form=$this->createForm(agenceType::class,$agence);
        $form->add('save',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        { 
            $em->persist($agence);
            $em->flush();
            return $this->redirectToRoute('app_afficheragences');
        }
        return $this->render('agence/editage.html.twig',['form'=>$form->createView()]);

    }
 
  #[Route("/users/download", name:"users_data_download")]
  public function pdf(AgenceRepository $repository): Response
  {
    
      $list = $repository->findAll();
      $pdfoptions = new Options(); // Use 'Options' instead of 'options'
      $pdfoptions->set("defaultFont", "arial");
      $pdfoptions->setIsRemoteEnabled(true);
  
      $dompdf = new Dompdf($pdfoptions);
      $contexte = stream_context_create([
          "ssl" => [
              "allow_self_signed" => true,
              "verify_peer" => false,
              "verify_peer_name" => false,
          ],
      ]);
  
      $dompdf->setHttpContext($contexte);
      $html = $this->renderView('agence/agenceback.html.twig', ['listX' => $list]);
      $dompdf->loadHtml($html);
      $dompdf->setPaper('A4', 'portrait');
      $dompdf->render();
  
      $fichier = 'agence-data';
      $dompdf->stream($fichier, ["Attachment" => true]);
  
      return new Response(); 
  }

  #[Route('/agencebyemail',name:'mail')]
  function byeMail(AgenceRepository $repo){
 
      $list=$repo->OrderbyMail();
      return $this->render('agence/agenceback.html.twig', [
          'listX' => $list,
      ]);
     }     

        #[Route('/agencebyemailc',name:'mailc')]
  function byeMailc(AgenceRepository $repo){
 
      $list=$repo->OrderbyMail();
      return $this->render('agence/agencefront.html.twig', [
          'listX' => $list,
      ]);
     }          
       
     #[Route('/agencebynom',name:'bynom')]
     function byNom(AgenceRepository $repo){
    
         $list=$repo->OrderagBynom();
         return $this->render('agence/agenceback.html.twig', [
             'listX' => $list,
         ]);
        }


     #[ Route('/agencebynomc',name:'bynomc')]
     function byNomc(AgenceRepository $repo){
    
         $list=$repo->OrderagBynom();
         return $this->render('agence/agencefront.html.twig', [
             'listX' => $list,
         ]);
        }
       

      //  #[Route('/pdf', name: 'agencepdf')]
       //  function generatePdfPersonne(AgenceRepository $repo) {
        //    $list= $repo->findAll();
         //   $html = $this->render('agence/agenceback.html.twig', ['agence' => $list]);
         //   $this->pdfService->showPdfFile($html);return new Response();
      //  }












































}
