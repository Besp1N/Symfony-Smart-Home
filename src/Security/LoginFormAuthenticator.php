<?php


namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;

class LoginFormAuthenticator extends AbstractAuthenticator
{
    private UrlGeneratorInterface $urlGenerator;
    private string $serviceName = 'myOauthService';

    public function __construct(
        UrlGeneratorInterface           $urlGenerator,
        private readonly UserRepository $userRepository)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function supports(Request $request): ?bool
    {
        return 'auth_oauth_login' === $request->attributes->get('_route') &&
            $request->get('service') == $this->serviceName;
    }

    public function authenticate(Request $request): SelfValidatingPassport
    {
        $email = $request->request->get('_username', '');

        $request->getSession()->set('LAST_USERNAME', $email);

        return new SelfValidatingPassport(
            new UserBadge($email, function() use ($email) {
                return $this->userRepository->findOneBy(['email' => $email]);
            }),
            [
                new PasswordCredentials($request->request->get('_password', '')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): RedirectResponse
    {
        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): RedirectResponse
    {
        if ($request->hasSession()) {
            $request->getSession()->set(SecurityRequestAttributes::AUTHENTICATION_ERROR, $exception);
        }

        return new RedirectResponse($this->urlGenerator->generate('auth_login'));
    }
}