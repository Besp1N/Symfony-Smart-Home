<?php

namespace App\Controller;

use App\Entity\Room;
use App\Repository\HouseRepository;
use App\Repository\RoomRepository;
use App\Services\RoomService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RoomController extends AbstractController
{
    #[Route('/room/add', name: 'app_room_add')]
    public function add(
        Request $request,
        RoomService $roomService
    ): Response {
        $roomService->roomServiceAdd($request);

        return $this->redirectToRoute('app_home_config');
    }

    #[Route('/room/delete/{roomId}', name: 'app_room_delete')]
    public function delete(int $roomId, RoomRepository $roomRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $room = $roomRepository->find($roomId);

        $houseOwner = $room->getHouse()->getOwner();
        if ($houseOwner !== $user) {
            $this->addFlash('error', 'This is not your room');
            return $this->redirectToRoute('app_home_config');
        }

        $devices = $room->getDevice();
        foreach ($devices as $device) {
            $entityManager->remove($device);
        }

        $entityManager->remove($room);
        $entityManager->flush();

        $this->addFlash('success', 'Room deleted successfully');
        return $this->redirectToRoute('app_home_config');
    }
}
