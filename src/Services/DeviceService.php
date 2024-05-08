<?php

namespace App\Services;

use App\Entity\Device;
use App\Entity\Room;
use App\Entity\User;
use App\Interfaces\DeviceInterface;
use App\Repository\DeviceRepository;
use App\Repository\RoomRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

readonly class DeviceService implements DeviceInterface
{
    public function __construct(
        private RoomRepository $roomRepository,
        private DeviceRepository $deviceRepository,
        private EntityManagerInterface $entityManager,
        private Security $security
    ) {}

    public function deviceServiceAdd(Request $request): void
    {
        $name = $request->request->get('Name');
        $description = $request->request->get('Description');
        $roomId = $request->request->get('Room');
        $room = $this->roomRepository->find($roomId);

        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new AuthenticationException('Authentication Exception.');
        }

        if (!$this->checkDeviceOwner($room, $user)) {
            throw new AccessDeniedException('Access Denied.');
        }

        $device = new Device();
        $device->setName($name);
        $device->setDescription($description);
        $device->setStatus(true);
        $device->setRoom($room);

        $this->entityManager->persist($device);
        $this->entityManager->flush();
    }

    public function deviceServiceDelete(Request $request): void
    {
        $user = $this->security->getUser();

        $deviceId = $request->request->get('DeviceId');
        $device = $this->deviceRepository->find($deviceId);
        $room = $device->getRoom();

        if (!$user instanceof User) {
            throw new AuthenticationException('Authentication Exception.');
        }

        if (!$this->checkDeviceOwner($room, $user)) {
            throw new AccessDeniedException('Access Denied.');
        }


        $this->entityManager->remove($device);
        $this->entityManager->flush();

    }

    public function checkDeviceOwner(Room $room, User $user): bool
    {
        $owner = $room->getHouse()->getOwner();
        return $owner === $user;
    }
}