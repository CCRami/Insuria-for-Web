<?php

namespace App\Controller;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Police;
use App\Entity\Sinistre;
use App\Form\PoliceType;
use App\Repository\PoliceRepository;
use App\Repository\SinistreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class PoliceController extends AbstractController
{
    #[Route('/police', name: 'app_police')]
    public function index(PoliceRepository $rep,PaginatorInterface $paginator, Request $request ): Response
    {
        $query = $rep->createQueryBuilder('u')->getQuery();
        $pagination = $paginator->paginate(
            $query, 
            $request->query->getInt('page', 1),
            2 
        );
        return $this->render('back/police.html.twig', [
        'pagination' => $pagination,
    ]);
    }
    #[Route('/policesfront', name: 'front_polices')]
    public function listPolices(PoliceRepository $policeRepository,SinistreRepository $sinistreRepository): Response
    {
        $polices = $policeRepository->findAll();
        $sinistres = $sinistreRepository->findAll();

        return $this->render('front/police.html.twig', [
            'sinistres' => $sinistres,
            'polices' => $polices,
        ]);
    }
    
    #[Route('/police/edit/{id}', name: 'police_edit')]
    public function editPol(Request $request,EntityManagerInterface $em,PoliceRepository $rep,int $id):Response
  {
        $rec=new Police();
        $rec=$rep->find($id);
        $form=$this->createForm(PoliceType::class,$rec);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        { 
            $em->persist($rec);
            $em->flush();
            return $this->redirectToRoute('app_police');
        }
        return $this->render('back/Policeedit.html.twig',['form'=>$form->createView()]);

    }
    #[Route('/police/new', name: 'police_new')]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $police = new Police();
    $form = $this->createForm(PoliceType::class, $police);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($police);
        $entityManager->flush();

        
        return $this->redirectToRoute('app_police');
    }

    return $this->render('back/policeadd.html.twig', [
        'form' => $form->createView(),
    ]);
}


   
#[Route('/police/delete/{id}', name: 'police_delete')]
public function deleteRec(Request $req, PoliceRepository $rep, $id, EntityManagerInterface $em): Response
{
    $rec = $rep->find($id);

    if (!$rec) {
        
        $this->addFlash('error', 'La police demandée n\'existe pas.');
        return $this->redirectToRoute('app_police');
    }

    $em->remove($rec);
    $em->flush();

    $this->addFlash('success', 'La police a été supprimée avec succès.');

    return $this->redirectToRoute('app_police');
}
#[Route('/police/{id}/pdf', name: 'police_pdf')]
public function pdf(Pdf $snappy, $id,Request $req, PoliceRepository $rep): Response
    {
        $police = $rep->find($id);  
        
        $html = $this->renderView('front/pdf.html.twig', [
            'police' => $police,
        ]);

        $pdf = $snappy->getOutputFromHtml($html);

        return new Response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $police->getPoliceName() . '.pdf"',
        ]);
    }
    // src/Controller/PoliceController.php

    #[Route('/filter-polices/{sinistreId}', name: 'filter_polices')]
    public function filterPolices(PoliceRepository $policeRepository, $sinistreId): JsonResponse
    {
        $polices = $policeRepository->findBy(['sinistre' => $sinistreId]);
        $data = [];
    
        foreach ($polices as $police) {
            $data[] = [
                'id' => $police->getId(),
                'policeName' => $police->getPoliceName(),
                'descriptionPolice' => $police->getDescriptionPolice(),
               
            ];
        }
    
        return new JsonResponse(['polices' => $data]);
    }
    // src/Controller/PoliceController.php

#[Route('/all-polices', name: 'all_polices')]
public function allPolices(PoliceRepository $policeRepository): JsonResponse
{
    $polices = $policeRepository->findAll();
    $data = [];

    foreach ($polices as $police) {
        $data[] = [
            'id' => $police->getId(),
            'policeName' => $police->getPoliceName(),
            'descriptionPolice' => $police->getDescriptionPolice(),
            
        ];
    }

    return new JsonResponse(['polices' => $data]);
}
#[Route('/searchp', name: 'ajax_searchp')]
    public function searchAction(EntityManagerInterface $em,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $posts =  $em->getRepository(Police::class)->findEntitiesByString($requestString);
        dump($posts);
        if(!$posts) {
            $result['sinisters']['error'] = "policie  Not found :( ";
        } else {
            $result['sinisters'] = $this->getRealEntities($posts);
        }
        return new Response(json_encode($result));
    }
    public function getRealEntities($sinisters){
        foreach ($sinisters as $sinisters){
            $realEntities[$sinisters->getId()] = [
                $sinisters->getPoliceName(),
                $sinisters->getDescriptionPolice(),
                $sinisters->getSinistre()->getSinName()
            ];

        }
        return $realEntities;
    }


   
    
    

}
