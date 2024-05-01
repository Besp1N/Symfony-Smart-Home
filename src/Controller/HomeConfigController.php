<?php

namespace App\Controller;

use App\Entity\House;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
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

        return $this->redirectToRoute('app_home_config');
    }
}
