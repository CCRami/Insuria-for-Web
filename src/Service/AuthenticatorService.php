<?php
namespace App\Service;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use OTPHP\TOTP;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AuthenticatorService
{
    private EntityManagerInterface $entityManager;
    private ParameterBagInterface $params;
    public function __construct(EntityManagerInterface $em, ParameterBagInterface $parameters)
    {
        $this->entityManager = $em;
        $this->params = $parameters;
    }
    public function getQrCodeUri(User $user)
    {
        $totp = TOTP::generate();
        $totp->setIssuer($this->params->get('app.issuer'));
        $totp->setLabel($user->getUsername());
        $qrCodeUri = $totp->getQRCodeUri(
            'https://api.qrserver.com/v1/create-qr-code/?color=000000&bgcolor=FFFFFF&data=[DATA]&qzone=2&margin=0&size=300x300&ecc=M',
            '[DATA]'
        );
        return [$qrCodeUri ,$totp->getSecret()];
    }
    public function validatepairing(User $user, string $secret): void
    {
        if (!$secret) {
            return;
        }
        $user->setSecret($secret);
        $this->entityManager->flush();
    }
    
}