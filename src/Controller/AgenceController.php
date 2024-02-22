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

    #[Route('/delete/{id}', name: 'agence_delete')]
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
    #[Route('/edit/{id}', name: 'agence_edit')]
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


    
}
