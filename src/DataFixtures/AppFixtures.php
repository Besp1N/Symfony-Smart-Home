<?php

namespace App\DataFixtures;

use App\Entity\Device;
use App\Entity\House;
use App\Entity\Room;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    )
    {
    }

    public function load(EntityManagerInterface|ObjectManager $manager): void
    {
        $user = new User();
        $user->setName('Kacper');
        $user->setLastName('Karabinowski');
        $user->setEmail('kacper@user.com');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
        $user->setRoles(['ROLE_USER']);
        $user->setGoogleId(null);

        $house = new House();
        $house->setName('House 1');
        $house->setCity('City 1');
        $house->setAddress('Address 1');
        $house->setOwner($user);

        $room = new Room();
        $room->setName('Room 1');
        $room->setHouse($house);
        $room->setDescription('Description 1');

        $house->addRoom($room);

        $device = new Device();
        $device->setName('Device 1');
        $device->setDescription('Description 1');
        $device->setStatus(true);

        $room->addDevice($device);

        $manager->persist($user);
        $manager->persist($house);
        $manager->persist($room);
        $manager->persist($device);
        $manager->flush();
    }
}
