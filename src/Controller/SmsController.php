<?php

namespace App\Controller;

use App\Service\SmsGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SmsController extends AbstractController
{
    // Vue du formulaire d'envoi du SMS
    #[Route('/sms', name: 'app_sms')]
    public function index(): Response
    {
        return $this->render('sms/base.html.twig', ['smsSent' => false]);
    }

    // Gestion de l'envoi du SMS
    #[Route('/sendSms', name: 'send_sms', methods: ['POST'])]
    public function sendSms(Request $request, SmsGenerator $smsGenerator): Response
    {
        // Numéro de destination fixe pour le test
        $number = '+21652564095';

        // Texte du SMS
        $text = 'we got a new offer check our website to find more about it';

        // Appel du service pour envoyer le SMS
        $smsGenerator->sendSms($number, '', $text); // Pas besoin du nom pour ce SMS

        return $this->render('sms/base.html.twig', ['smsSent' => true]);
    }
}