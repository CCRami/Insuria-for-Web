<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use OTPHP\TOTP;
use Symfony\Component\Security\Guard\AuthenticatorInterface;

class LoginFormAuthAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private UserRepository $userRepository;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UserRepository $userRepository, UrlGeneratorInterface $urlGenerator, Security $security)
    {
        $this->userRepository = $userRepository;
        $this->urlGenerator = $urlGenerator;    
        $this->security = $security;
    }

    
    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            throw new UserNotFoundException();
        }
        $hasSecret = $user->getSecret() !== null;

        if (!$user->isVerified()) {
            throw new CustomUserMessageAuthenticationException('Your account is not verified. Please check your email for verification.');
        }
        if ($hasSecret) {
            $otp = $request->request->get('otp');
            if (!$otp) {
                $request->getSession()->getFlashBag()->add('requires_otp', true);
                throw new CustomUserMessageAuthenticationException('Please enter your OTP.');
            }
            $totp = TOTP::create($user->getSecret());
            if (!$totp->verify($otp)) {
                $request->getSession()->getFlashBag()->add('requires_otp', true);
                throw new CustomUserMessageAuthenticationException('Invalid OTP.');
            }
        }
        
        return new Passport(
            new UserBadge($email, function ($userIdentifier) use ($user) {
                return $user;
            }),
            new PasswordCredentials($password)
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $user = $token->getUser();

        if ($this->security->isGranted('ROLE_ADMIN')) {

            return new RedirectResponse($this->urlGenerator->generate('app_admin'));
        }
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }
    
    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
    
}
