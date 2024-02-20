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
use Doctrine\Persistence\ManagerRegistry;

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
    public function listagence(AgenceRepository $repository)
    {
        $list= $repository->findAll();
        return $this->render('agence/agenceback.html.twig',['listX' => $list, ]);
    }
    #[Route('/afficheragencesc', name: 'app_afficheragencesc')]
    public function listagencec(AgenceRepository $repository)
    {
        $list= $repository->findAll();
        return $this->render('agence/agencefront.html.twig',['listX' => $list, ]);
    }

    #[Route('/delete/{id}', name: 'name_delete')]
    public function deleteAuthor(AgenceRepository $rep,$id,EntityManagerInterface $em):Response
    {
        $auth=new Agence();
        $auth=$rep->find($id);
        $em->remove($auth);
        $em->flush();
       return $this->redirectToRoute('app_afficheragences');

        
    }
    #[Route('/ajouteragence', name: 'name_add')]
    public function addAuthor(Request $request,EntityManagerInterface $em):Response
    {
        $auth=new Agence();
        $form=$this->createForm(agenceadd::class,$auth);
        $form->add('save',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        { 
            $em->persist($auth);
            $em->flush();
            return $this->redirectToRoute('app_afficheragences');
        }
        return $this->render('agence/ajouterage.html.twig',['form'=>$form->createView()]);

    }
    #[Route('/edit/{id}', name: 'name_edit')]
    public function editAuthor(Request $request,EntityManagerInterface $em,AgenceRepository $rep,int $id):Response
    {
        $auth=new Agence();
        $auth=$rep->find($id);
        $form=$this->createForm(agenceType::class,$auth);
        $form->add('save',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        { 
            $em->persist($auth);
            $em->flush();
            return $this->redirectToRoute('app_afficheragences');
        }
        return $this->render('agence/editage.html.twig',['form'=>$form->createView()]);

    }


    
}
