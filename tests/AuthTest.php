<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\UserRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AuthTest extends WebTestCase
{
    public function testVisitingWhileLoggedIn(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail('kacper@user.com');

        $client->loginUser($testUser);

        $client->request('GET', '/home-config');
        $this->assertResponseIsSuccessful();
    }
}
