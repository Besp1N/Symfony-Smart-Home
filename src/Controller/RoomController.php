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
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class RoomController extends AbstractController
{
    #[Route('/room/add', name: 'app_room_add')]
    public function add(
        Request $request,
        RoomService $roomService
    ): Response {
        try {
            $roomService->roomServiceAdd($request);
        } catch (AuthenticationException $exception) {
            return $this->redirectToRoute('app_login');
        }

        return $this->redirectToRoute('app_home_config');
    }

    #[Route('/room/delete', name: 'app_room_delete')]
    public function delete(
        Request $request,
        RoomService $roomService
    ): Response {
        try {
            $roomService->roomServiceDelete($request);
        } catch (AuthenticationException $exception) {
            return $this->redirectToRoute('app_login');
        }

        $this->addFlash('success', 'Room deleted successfully');
        return $this->redirectToRoute('app_home_config');
    }
}
