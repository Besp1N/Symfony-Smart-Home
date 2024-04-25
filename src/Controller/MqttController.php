<?php

namespace App\Controller;

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

class MqttController extends AbstractController
{
    private string $server = '10.189.0.144';
    private string $port = '1883';


    #[Route('/', name: 'app_mqtt')]
    public function index(): Response
    {
        return $this->render('mqtt/index.html.twig', []);
    }

    /**
     * @throws ConfigurationInvalidException
     * @throws ConnectingToBrokerFailedException
     * @throws RepositoryException
     * @throws ProtocolNotSupportedException
     * @throws DataTransferException
     */
    #[Route('/publish', name: 'app_mqtt_publish')]
    public function publish(Request $request): Response
    {
        $state = $request->request->get('butt');

        $mqtt = new MqttClient($this->server, $this->port);
        $mqtt->connect();
        $mqtt->publish('dupa/dupa', 'dupa');
        $mqtt->disconnect();
        return $this->render('mqtt/index.html.twig', []);
    }
}
