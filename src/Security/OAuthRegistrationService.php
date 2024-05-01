<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use League\OAuth2\Client\Provider\GoogleUser;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;

readonly class OAuthRegistrationService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
    }

    /**
     * @param GoogleUser $resourceOwner
     * @return User
     * @throws Exception
     */
    public function persist(ResourceOwnerInterface $resourceOwner): User
    {
        $user = (new User())
            ->setName($resourceOwner->getName())
            ->setLastName($resourceOwner->getLastName())
            ->setEmail($resourceOwner->getEmail())
            ->setGoogleId($resourceOwner->getId())
            ->setRoles(['ROLE_USER'])
            ->setpassword($this->generateRandomPassword(20));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * @throws Exception
     */
    private function generateRandomPassword(int $length): string
    {
        $randomBytes = random_bytes($length / 2);
        return bin2hex($randomBytes);
    }

}