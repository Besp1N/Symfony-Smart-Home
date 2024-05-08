<?php

namespace App\Services;

use App\Entity\Room;
use App\Entity\User;
use App\Interfaces\RoomInterface;
use App\Repository\HouseRepository;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

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
        $houseId = $request->request->get('HouseId');
        $name = $request->request->get('Name');
        $description = $request->request->get('Description');

        $house = $this->houseRepository->find($houseId);

        $room = new Room();
        $room->setName($name);
        $room->setDescription($description);
        $room->setHouse($house);

        $this->entityManager->persist($room);
        $this->entityManager->flush();
    }

    public function roomServiceDelete(Request $request): void
    {
        $roomId = $request->request->get('roomId');
        $room = $this->roomRepository->find($roomId);

        $devices = $room->getDevice();
        foreach ($devices as $device) {
           $this->entityManager->remove($device);
        }

        $this->entityManager->remove($room);
        $this->entityManager->flush();
    }




    public function checkRoomOwner(Room $room, User $user): bool
    {
        $owner = $room->getHouse()->getOwner();
        return $owner === $user;
    }
}