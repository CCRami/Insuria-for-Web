<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\LoginType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Service\MyGmailMailerService;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use App\Service\AuthenticatorService;

class UserController extends AbstractController
{
/**
 * Admin controller
 */
    private $manager;
    private UserRepository $repo;
    private MyGmailMailerService $mailerService;
    public function __construct(TokenGeneratorInterface $tokenGenerator,ManagerRegistry $manager,UserRepository $repo, MyGmailMailerService $mailerService){
        $this->manager=$manager->getManager();
        $this->repo=$repo;
        $this->mailerService = $mailerService;
        $this->tokenGenerator = $tokenGenerator;
        
    }
    #[Route('/admin/user', name: 'app_admin_user', methods: ['GET', 'POST'])]
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $users = $userRepository->findAll();

        $user = new User();
        $user->setBirthDate(new \DateTime());
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword($user, $form->get('password')->getData()));
            $user->setIsVerified(true);
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
    public function edit(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $userId = $request->attributes->get('id');
        $user = $entityManager->getRepository(User::class)->find($userId);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword($user, $form->get('password')->getData()));
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
#[Route('/home/user/{id}', name: 'app_home_manage')]
public function displaymanage(Request $request, EntityManagerInterface $entityManager,$id , UserPasswordEncoderInterface $passwordEncoder, AuthenticatorService $authenticatorservice): Response
{
    
    $userRepository = $this->getDoctrine()->getRepository(User::class);
    $user = $userRepository->find($id);
    $encodedpass=$user->getPassword();
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $user->setPassword($encodedpass);
        $entityManager->flush();
        $this->addFlash('success', 'User updated successfully');

        return $this->redirectToRoute('app_home_manage', ['id' => $id]);
    }
    if ($request->isMethod(request::METHOD_POST))
    {
        $authenticatorservice->validatepairing($user, $request->request->get('secret'));
        $this->addFlash('success', '2FA was paired successfully!');	
        return $this->redirectToRoute('app_home_manage', ['id' => $id]);

    }
    [$qrCodeUri, $secret] = $authenticatorservice->getQrCodeUri($user);
    return $this->render('front/user_details.html.twig', [
        'user' => $user,
        'form' => $form->createView(),
        'qrCodeUri' => $qrCodeUri,
        'secret' => $secret
    ]);
}

#[Route('/forgot-password/{email}', name: 'app_forgot_password')]
    public function forgotPassword(Request $request,$email , EntityManagerInterface $entityManager): Response
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);
            $user = $userRepository->findOneBy(['email' => $email]);

            if ($user) {
                $resetToken = $this->tokenGenerator->generateToken();
                $entityManager->persist($user);
                $entityManager->flush();

                $resetUrl = $this->generateUrl('app_reset_password', [
                    'id' => $user->getId(),
                    'token' => $resetToken,
                ], UrlGeneratorInterface::ABSOLUTE_URL);

                $this->mailerService->sendEmail(
                    $user->getEmail(),
                    'Password Reset',
                    $this->renderView('email/reset_pass.html.twig', [
                        'user' => $user,
                        'resetUrl' => $resetUrl,
                    ])
                );

            }
        return $this->redirectToRoute('app_home_manage', ['id' => $user->getId()]);
    }
#[Route('/change-pass/{id}', name: 'app_reset_password')]
    public function changepass(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $userId = $request->attributes->get('id');

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $userId]);

    if (!$user) {
        throw $this->createNotFoundException('No user found for id '.$userId);
    }

    if ($request->isMethod('POST')) {
        $currentPassword = $request->request->get('current_password');
        $newPassword = $request->request->get('new_password');
        $confirmNewPassword = $request->request->get('confirm_new_password');


        if (!$passwordEncoder->isPasswordValid($user, $currentPassword)) {
            $this->addFlash('error', 'Incorrect current password. Please try again.');
            return $this->redirectToRoute('app_reset_password', ['id' => $user->getId()]);
        }
        
        if ($newPassword !== $confirmNewPassword OR empty($newPassword)) {

            $this->addFlash('error', 'New password and confirm new password do not match nor can be blank. Please try again.');
            return $this->redirectToRoute('app_reset_password', ['id' => $user->getId()]);
        }

        $user->setPassword($passwordEncoder->encodePassword($user, $newPassword));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'Password successfully changed!');
        return $this->redirectToRoute('app_home_manage', ['id' => $user->getId()]);
    }

    return $this->render('front/reset_password.html.twig', [
        'user' => $user,
    ]);

    }
 
}