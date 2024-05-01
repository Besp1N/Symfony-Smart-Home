<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use League\OAuth2\Client\Provider\GoogleUser;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class GoogleAuthenticator extends AbstractOAuthAuthenticator
{
    protected string $serviceName = 'google';

    protected function getUserFromResourceOwner(ResourceOwnerInterface $resourceOwner, UserRepository $userRepository): ?User
    {
        if (!($resourceOwner instanceof GoogleUser)) {
            throw new \RuntimeException('expecting google user');
        }

        if (!($resourceOwner->toArray()['email_verified'] ?? null)) {
            throw new AuthenticationException('email not verified');
        }

        return $userRepository->findOneBy([
            'googleId' => $resourceOwner->getId(),
            'email' => $resourceOwner->getEmail()

        ]);
    }
}
