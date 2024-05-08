<?php

namespace App\Services;

use App\Entity\House;
use App\Entity\Room;
use App\Entity\User;
use App\Interfaces\RoomInterface;
use App\Repository\HouseRepository;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

readonly class RoomService implements RoomInterface
{
    public function __construct(
        private RoomRepository $roomRepository,
        private EntityManagerInterface $entityManager,
        private Security $security,
        private HouseRepository $houseRepository
    )
    {}

    public function roomServiceAdd(Request $request): void
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new AuthenticationException('Authentication Exception.');
        }

        $houseId = $request->request->get('HouseId');
        $name = $request->request->get('Name');
        $description = $request->request->get('Description');

        $house = $this->houseRepository->find($houseId);

        if (!$this->checkRoomOwner($house, $user)) {
            throw new AccessDeniedException('Access Denied.');
        }

        $room = new Room();
        $room->setName($name);
        $room->setDescription($description);
        $room->setHouse($house);

        $this->entityManager->persist($room);
        $this->entityManager->flush();
    }

    public function roomServiceDelete(Request $request): void
    {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new AuthenticationException('Authentication Exception.');
        }

        $roomId = $request->request->get('roomId');
        $room = $this->roomRepository->find($roomId);
        $house = $room->getHouse();

        if (!$this->checkRoomOwner($house, $user)) {
            throw new AccessDeniedException('Access Denied.');
        }

        $devices = $room->getDevice();
        foreach ($devices as $device) {
           $this->entityManager->remove($device);
        }

        $this->entityManager->remove($room);
        $this->entityManager->flush();
    }




    public function checkRoomOwner(House $house, User $user): bool
    {
        $owner = $house->getOwner();
        return $owner === $user;
    }
}