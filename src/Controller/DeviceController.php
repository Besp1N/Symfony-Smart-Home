<?php

namespace App\Controller;

use App\Entity\Device;
use App\Repository\DeviceRepository;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeviceController extends AbstractController
{
    #[Route('/device/add/', name: 'app_device_add')]
    public function add(Request $request, DeviceRepository $deviceRepository, EntityManagerInterface $entityManager, RoomRepository $roomRepository): Response
    {
        $name = $request->request->get('Name');
        $description = $request->request->get('Description');
        $roomId = $request->request->get('Room');
        $room = $roomRepository->find($roomId);

        $device = new Device();
        $device->setName($name);
        $device->setDescription($description);
        $device->setStatus(true);
        $device->setRoom($room);

        $entityManager->persist($device);
        $entityManager->flush();

        $this->addFlash('success', 'Device added successfully');
        return $this->redirectToRoute('app_home_config');
    }

}