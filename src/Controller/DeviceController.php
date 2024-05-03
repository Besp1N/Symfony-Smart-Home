<?php

namespace App\Controller;

use App\Entity\Device;
use App\Repository\DeviceRepository;
use App\Repository\RoomRepository;
use Composer\XdebugHandler\Status;
use Doctrine\ORM\EntityManagerInterface;
use PhpMqtt\Client\Exceptions\ConfigurationInvalidException;
use PhpMqtt\Client\Exceptions\ConnectingToBrokerFailedException;
use PhpMqtt\Client\Exceptions\DataTransferException;
use PhpMqtt\Client\Exceptions\ProtocolNotSupportedException;
use PhpMqtt\Client\Exceptions\RepositoryException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use PhpMqtt\Client\MqttClient;


class DeviceController extends AbstractController
{
    #[Route('/device/add/', name: 'app_device_add')]
    public function add(Request $request, EntityManagerInterface $entityManager, RoomRepository $roomRepository): Response
    {
        $name = $request->request->get('Name');
        $description = $request->request->get('Description');
        $roomId = $request->request->get('Room');
        $room = $roomRepository->find($roomId);

        $owner = $room->getHouse()->getOwner();
        $this->getUser();

        if ($owner !== $this->getUser()) {
            $this->addFlash('error', 'You are not the owner of this house');
            return $this->redirectToRoute('app_home_config');
        }

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

    #[Route('/device/delete/{deviceId}', name: 'app_device_delete')]
    public function delete(int $deviceId, DeviceRepository $deviceRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $device = $deviceRepository->find($deviceId);
        $owner = $device->getRoom()->getHouse()->getOwner();

        if ($owner !== $user) {
            $this->addFlash('error', 'This is not your device');
            return $this->redirectToRoute('app_home_config');
        }

        $entityManager->remove($device);
        $entityManager->flush();

        $this->addFlash('success', 'Device deleted successfully');
        return $this->redirectToRoute('app_home_config');
    }

    /**
     * @throws ConfigurationInvalidException
     * @throws ConnectingToBrokerFailedException
     * @throws RepositoryException
     * @throws DataTransferException
     */
    #[Route('/device/enable', name: 'app_device_enable')]
    public function enable(Request $request, DeviceRepository $deviceRepository, EntityManagerInterface $entityManager): Response
    {
        $deviceId = $request->request->get('DeviceId');
        $device = $deviceRepository->find($deviceId);
        $user = $this->getUser();
        $owner = $device->getRoom()->getHouse()->getOwner();

        if ($owner !== $user) {
            $this->addFlash('error', 'This is not your device');
            return $this->redirectToRoute('app_home_config');
        }

        $device->setStatus(true);

        $mqtt = new MqttClient('broker.mqtt.cool', '1883');
        $mqtt->connect();
        $mqtt->publish('test/test', 'test');$mqtt->disconnect();

        $entityManager->persist($device);
        $entityManager->flush();

        return $this->redirectToRoute('app_home_config');
    }

    #[Route('/device/disable', name: 'app_device_disable')]
    public function disable(): Response
    {
        return new Response(status: 200);
    }

}