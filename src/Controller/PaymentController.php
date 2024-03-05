<?php

namespace App\Controller;

use App\Service\PaymentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Repository\CommandeRepository;
use Symfony\Component\HttpFoundation\Request;


class PaymentController extends AbstractController
{
    #[Route('/payment', name: 'payment')]
    public function index(): Response
    {
        return $this->render('payment/index.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }

    #[Route('/checkout', name: 'checkout')]
    public function checkout(PaymentService $paymentService, UrlGeneratorInterface $urlGenerator, CommandeRepository $commandeRepository, Request $request): Response
    {
        // Retrieve basket items from the database
        $commands = $commandeRepository->findAll();
    
        // Calculate total price
        $totalPrice = 0;
        foreach ($commands as $command) {
            $totalPrice += $command->getMontant();
        }
    
        // Create the checkout session using the PaymentService
        $session = $paymentService->createCheckoutSession($urlGenerator, $totalPrice);
    
        // Redirect the user to the payment page
        return $this->redirect($session->url, 303);
    }

    
    #[Route('/success-url', name: 'success_url')]
    public function successUrl(): Response
    {
        return $this->render('front/success.html.twig', []);
    }

    #[Route('/cancel-url', name: 'cancel_url')]
    public function cancelUrl(): Response
    {
        return $this->render('payment/cancel.html.twig', []);
    }
}