<?php

namespace App\Controller;

use App\Entity\House;
use App\Entity\User;
use App\Repository\HouseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeConfigController extends AbstractController
{
    #[Route('/home-config', name: 'app_home_config')]
    public function index(): Response
    {
        $user = $this->getUser();
        $houses = $user instanceof User ? $user->getHouse()->isEmpty() ? null : $user->getHouse() : null;

        return $this->render('home_config/index.html.twig', [
            'user' => $user,
            'houses' => $houses
        ]);
    }

    #[Route('/add-house', name: 'app_add_house')]
    public function addHouse(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $house = new House();

        $houseName = $request->request->get('Name');
        $houseCity = $request->request->get('City');
        $houseAddress = $request->request->get('Address');

        $house->setName($houseName);
        $house->setCity($houseCity);
        $house->setAddress($houseAddress);
        $house->setOwner($user);

        $entityManager->persist($house);
        $entityManager->flush();

        $this->addFlash('success', 'Home added successfully');
        return $this->redirectToRoute('app_home_config');
    }

    #[Route('/home-config/config/{id}', name: 'app_home_config_config')]
    public function configDashboard(int $id, HouseRepository $houseRepository): Response
    {
        $user = $this->getUser();
        $house = $houseRepository->find($id);

        if ($house->getOwner() !== $user) {
            $this->addFlash('error', 'You are not the owner of this house');
            return $this->redirectToRoute('app_home_config');
        }

        $rooms = $house->getRoom()->toArray();
        $devices = [];

        foreach ($rooms as $room) {
            $devices = array_merge($devices, $room->getDevice()->toArray());
        }

        return $this->render('home_config/config.html.twig', [
            'house' => $house,
            'rooms' => $rooms,
            'devices' => $devices
        ]);
    }
}
