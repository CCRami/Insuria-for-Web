<?php

namespace App\Controller;
use App\Service\MyGmailMailerService;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegistrationController extends AbstractController
{
    private MyGmailMailerService $mailerService;

    public function __construct(MyGmailMailerService $mailerService)
    {
        $this->mailerService = $mailerService;
    }

    #[Route('/home/signup', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager,UrlGeneratorInterface $router): Response
    {
        $user = new User();
        $user->setRoles(['ROLE_CLIENT']);
        $user->setBirthDate(new \DateTime());
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();
            
            $verificationToken = hash('sha256', $user->getEmail());
            $verificationUrl = $router->generate('app_verify_email', [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'token' => $verificationToken,
            ], UrlGeneratorInterface::ABSOLUTE_URL);
            // generate a signed url and email it to the user
            $this->mailerService->sendEmail(
                $user->getEmail(),
                'Please Confirm your Email',
                $this->renderView('email/confirmation_email.html.twig', [
                    'user' => $user,
                    'verificationUrl' => $verificationUrl,
                ])
            );

            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_login');
        }

        return $this->render('front/signup.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    #[Route('/verify-email/{id}/{email}', name: 'app_verify_email')]
    public function verifyEmail(Request $request): Response
    {
        $userId = $request->attributes->get('id');
        $email = $request->attributes->get('email');

        // Fetch the user from the database based on the provided id and email
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
            'id' => $userId,
            'email' => $email,
        ]);

        if (!$user) {
            // Handle the case where the user is not found
            throw $this->createNotFoundException('User not found');
        }

        // Check if the user is already verified
        if ($user->isVerified()) {
            $this->addFlash('info', 'Your email address is already verified. You can now log in.');
            return $this->redirectToRoute('app_login');
        }
            $user->setIsVerified(true);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Your email address has been verified. You can now log in.');
            return $this->redirectToRoute('app_login');
    }

}
