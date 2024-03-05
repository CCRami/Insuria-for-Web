<?php

namespace App\Controller;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Reclamation;
use App\Entity\Commande;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use App\Form\ReclamationEditType;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\FileUploader;
use Doctrine\Persistence\ManagerRegistry;
    use App\Service\MyGmailMailerService;
class ReclamationController extends AbstractController
{  


    private MyGmailMailerService $mailerService;

    public function __construct(MyGmailMailerService $mailerService)
    {
        $this->mailerService = $mailerService;
    }
    #[Route('/reclamation', name: 'app_reclamation')]
    public function index(): Response
    {
        return $this->render('reclamation/ReclamationsUser.html.twig', [
            'controller_name' => 'ReclamationController',
        ]);
    }
    #[Route('/tableReclamations', name: 'app_reclamation_admin')]
    public function index_reclamation_admin(ReclamationRepository $rep): Response
    {$list=$rep->findAll();
        return $this->render('reclamation\ReclamationBack.html.twig', 
          ['list' => $list, ]
        );
    }
    #[Route('listReclamation/edit/{id}', name: 'reclamationAdmin_edit')]
    public function editRec(Request $request, EntityManagerInterface $em, ReclamationRepository $rep, int $id): Response
    {
        $rec = $rep->find($id);
        $form = $this->createForm(ReclamationEditType::class, $rec);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
    
            if ($rec->isReponse() === 'refused') {
                
                return $this->redirectToRoute('reclamation_refuse', ['reclamationId' => $rec->getId()]);
            } elseif ($rec->isReponse() === 'accepted') {
                
                return $this->redirectToRoute('reclamation_accepte', ['reclamationId' => $rec->getId()]);
            } else {
                
                return $this->redirectToRoute('app_reclamation_admin');
            }
        }
        return $this->render('reclamation/formAdmin.html.twig', [
            'form' => $form->createView(),
            'reclamation' => $rec, // Passer la réclamation au template
        ]);
    }
    



    #[Route('list/delete/{id}', name: 'rec_delete')]
    public function deleteRec(Request $req,ReclamationRepository $rep,$id,EntityManagerInterface $em):Response
    {
        $rec=new Reclamation();
        $rec=$rep->find($id);
        $indemnisation = $rec->getIndemnissation();
        if ($indemnisation) {
            $em->remove($indemnisation);}
        $em->remove($rec);
        $em->flush();
        return $this->redirectToRoute('app_reclamation_admin');

        
    }  
    #[Route('Reclamation/detail/{id}', name: 'reclamationAdmin_show')]
    public function showRec( ReclamationRepository $repository, $id): Response
    {
       
        $reclamation = $repository->find($id);
    
        
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }
    
    #[Route('Reclamation/new', name: 'reclamationAdmin_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $rec = new Reclamation();
    $form = $this->createForm(ReclamationType::class, $rec);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $file = $form->get('file')->getData();
        if ($file) {
          
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('upload_directory'),
                $fileName
            );
           
            $rec->setFileName($fileName);}
        
        $entityManager->persist($rec);
        $entityManager->flush();

       
        return $this->redirectToRoute('app_reclamation_admin');
    }

    return $this->render('reclamation/addreclamation.html.twig', [
        'form' => $form->createView(),
    ]);
}

//User :
//creer une reclamation:



#[Route('user/newReclamation', name: 'reclamationUser_new')]
public function newReclamation(Request $request, EntityManagerInterface $entityManager): Response {
   
        $rec = new Reclamation();


$form = $this->createForm(ReclamationType::class, $rec);
$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {
    $file = $form->get('file')->getData();
    if ($file) {
      
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move(
            $this->getParameter('upload_directory'),
            $fileName
        );

        $rec->setFileName($fileName);}
    
    $entityManager->persist($rec);
    $entityManager->flush();
    $this->mailerService->sendEmail(
        "ramitoubib@hotmail.com",
        'Reclamation added',
        $this->renderView('email/rec_email.html.twig', [
            'rec' => $rec
        ])
    );

        
     
    return $this->redirectToRoute('app_reclamation_user');
    
}

return $this->render('reclamation/ReclamationsUser.html.twig', [
    'form' => $form->createView(),'reclamation' => $rec
]);
}

//afficher mes reclamation :

#[Route('user/Reclamations', name: 'app_reclamation_user')]
    public function index_reclamation_user(ReclamationRepository $rep,PaginatorInterface $paginator, Request $request): Response
    {$list=$rep->findAll();
        $pagination = $paginator->paginate(
            $list,
            $request->query->getInt('page', 1), 
            3 
        );

        return $this->render('reclamation\ReclamationFront.html.twig', 
        ['pagination' => $pagination]
        );
  
      
}
//affiche une reclamation:
#[Route('affiche/{id}', name: 'rec_affichUser', methods: ['GET'])]
public function afficheRecUser( ReclamationRepository $repository, $id):Response
{$reclamation = $repository->find($id);
    return $this->render('reclamation\afficherClaim.html.twig',['rec'=>$reclamation,]);
}

//supprimer :
#[Route('delete/{id}', name: 'rec_deleteUser')]
public function deleteRecUser(Request $req,ReclamationRepository $rep,$id,EntityManagerInterface $em):Response
{
    $rec=new Reclamation();
    $rec=$rep->find($id);
    $indemnisation = $rec->getIndemnissation();
    if ($indemnisation) {
        $em->remove($indemnisation);}
    $em->remove($rec);
    $em->flush();
    return $this->redirectToRoute('app_reclamation_user');

    
}  
//editer :
#[Route('edit/{id}', name: 'rec_editUser')]
public function editRecUser(Request $request, EntityManagerInterface $em, ReclamationRepository $rep, int $id): Response
{
    $rec = $rep->find($id);
    $form = $this->createForm(ReclamationType::class, $rec);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->flush();

      
        return $this->redirectToRoute('app_reclamation_user');
    }

    return $this->render('reclamation/ReclamationsUser.html.twig', [
        'form' => $form->createView(),
        'reclamation' => $rec, 
    ]);
}









}
