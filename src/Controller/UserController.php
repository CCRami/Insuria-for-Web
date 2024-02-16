<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class UserController extends AbstractController
{
/**
 * Admin controller
 */
    private $manager;
    private UserRepository $repo;
    public function __construct(ManagerRegistry $manager,UserRepository $repo){
        $this->manager=$manager->getManager();
        $this->repo=$repo;
    }
    #[Route('/admin/user', name: 'app_admin_user', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $users = $userRepository->findAll();

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'User created successfully');

            return $this->redirectToRoute('app_admin_user');
        }

        return $this->render('/back/user.html.twig', [
            'controller_name' => 'UserController',
            'users' => $users,
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/user/edit/{id}', name: 'app_admin_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $userId = $request->attributes->get('id');
        $user = $entityManager->getRepository(User::class)->find($userId);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
    
            $this->addFlash('success', 'User updated successfully');
    
            return $this->redirectToRoute('app_admin_user');
        }
    
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $users = $userRepository->findAll();
        
        return $this->render('/back/user.html.twig', [
            'controller_name' => 'UserController',
            'users' => $users,
            'form' => $form->createView(),
        ]);
    }
    


    #[Route('/admin/user/delete/{id}', name: 'app_admin_user_delete')]
    public function delete($id): Response
    {
            $user=$this->repo->find($id);
            $this->manager->remove($user);
            $this->manager->flush();

        return $this->redirectToRoute('app_admin_user', [], Response::HTTP_SEE_OTHER);
    }
    /**
 * client controller
 */
    #[Route('/home/signup', name: 'app_home_signup')]
    public function index_client(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'User created successfully');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('/front/signup.html.twig', [
            'controller_name' => 'UserController',
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}