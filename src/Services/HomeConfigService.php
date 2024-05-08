<?php

namespace App\Services;

use App\Entity\House;
use App\Entity\User;
use App\Interfaces\HouseConfigInterface;
use App\Repository\HouseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

readonly class HomeConfigService implements HouseConfigInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private HouseRepository        $houseRepository,
        private Security               $security,
    )
    {}

    /*
     * houseServiceAdd function supports adding a new house to database
     */
    public function houseServiceAdd(Request $request): void
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            throw new AuthenticationException('Authentication Exception.');
        }

        $houseName = $request->request->get('Name');
        $houseCity = $request->request->get('City');
        $houseAddress = $request->request->get('Address');

        // setting up new house
        $house = new House();
        $house->setName($houseName);
        $house->setCity($houseCity);
        $house->setAddress($houseAddress);
        $house->setOwner($user);

        $this->entityManager->persist($house);
        $this->entityManager->flush();
    }

    public function homeServiceDelete(Request $request): void
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new AuthenticationException('Authentication Exception.');
        }

        $houseId = $request->request->get('HouseId');
        $house = $this->houseRepository->find($houseId);

        $rooms = $house->getRoom()->toArray();
        $devices = [];

        foreach ($rooms as $room) {
            $devices = array_merge($devices, $room->getDevice()->toArray());
        }

        foreach ($rooms as $room) {
            $this->entityManager->remove($room);
        }

        foreach ($devices as $device) {
            $this->entityManager->remove($device);
        }

        $this->entityManager->remove($house);
        $this->entityManager->flush();
    }

    public function houseServiceConfigDashboard(int $id): array
    {
        $user = $this->security->getUser();
        $user = $user instanceof User ? $user : null;
        $house = $this->houseRepository->find($id);

        if (!$this->checkHouseOwner($house, $user)) return [];

        $rooms = $house->getRoom()->toArray();
        $devices = [];

        foreach ($rooms as $room) {
            $devices = array_merge($devices, $room->getDevice()->toArray());
        }

        if (empty($rooms)) $rooms = null;
        if (empty($devices)) $devices = null;

        return [
            'house' => $house,
            'rooms' => $rooms,
            'devices' => $devices,
        ];
    }

    public function checkHouseOwner(House $house, User $user): bool
    {
        $houseOwner = $house->getOwner();
        return $houseOwner === $user;
    }
}