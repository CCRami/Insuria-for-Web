<?php

namespace App\Service;

use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentService
{
    private $stripeSK;

    public function __construct(string $stripeSK)
    {
        $this->stripeSK = $stripeSK;
    }

    public function createCheckoutSession(UrlGeneratorInterface $urlGenerator, int $totalPrice): Session
    {
        Stripe::setApiKey($this->stripeSK);
       
        return Session::create([
            'payment_method_types' => ['card'],
            'line_items'           => [
                [
                    'price_data' => [
                        'currency'     => 'usd',
                        'product_data' => [
                            'name' => 'Total Price',
                        ],
                        'unit_amount'  => $totalPrice * 100, 
                    ],
                    'quantity'   => 1,
                ]
            ],
            'mode'                 => 'payment',
            'success_url'          => $urlGenerator->generate('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url'           => $urlGenerator->generate('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
    }
}
