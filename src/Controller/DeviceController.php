<?php

namespace App\Controller;

use App\Entity\Device;
use App\Repository\DeviceRepository;
use App\Repository\RoomRepository;
use App\Services\DeviceService;
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
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;


class DeviceController extends AbstractController
{
    #[Route('/device/add', name: 'app_device_add')]
    public function add(
        Request $request,
        DeviceService $deviceService
    ): Response {
        try {
            $deviceService->deviceServiceAdd($request);
        } catch (AuthenticationException $exception) {
            return $this->redirectToRoute('app_login');
        }

        $this->addFlash('success', 'Device added successfully.');
        return $this->redirectToRoute('app_home_config');
    }

    #[Route('/device/delete', name: 'app_device_delete')]
    public function delete(
        Request $request,
        DeviceService $deviceService
    ): Response {
        try {
            $deviceService->deviceServiceDelete($request);
        } catch (AuthenticationException $exception) {
            return $this->redirectToRoute('app_login');
        }

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
    public function enable(
        Request $request,
        DeviceService $deviceService
    ): Response {
        $deviceService->deviceServiceToggle($request, true);

        $this->addFlash('success', 'Your device has been enabled');
        return $this->redirectToRoute('app_home_config');
    }

    /**
     * @throws ConnectingToBrokerFailedException
     * @throws ConfigurationInvalidException
     * @throws RepositoryException
     * @throws DataTransferException
     */
    #[Route('/device/disable', name: 'app_device_disable')]
    public function disable(
        Request $request,
        DeviceService $deviceService
    ): Response {
        $deviceService->deviceServiceToggle($request, false);

        $this->addFlash('success', 'Your device has been disabled');
        return $this->redirectToRoute('app_home_config');
    }

}