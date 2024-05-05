<?php

namespace App\Controller;

use App\Entity\House;
use App\Entity\User;
use App\Repository\HouseRepository;
use App\Services\HomeConfigService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class HomeConfigController extends AbstractController
{
    // index func renders a main view of home config section
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

    /*
     * addHouse func and configDashboard func bellow use a HomeConfigService to
     * separate business logic from controller.
     */
    #[Route('/add-house', name: 'app_add_house')]
    public function addHouse(
        Request $request,
        HomeConfigService $homeConfigService
    ): Response {
        $homeConfigService->houseServiceAdd($request);

        $this->addFlash('success', 'Home added successfully');
        return $this->redirectToRoute('app_home_config');
    }

    #[Route('/home-config/config/{id}', name: 'app_home_config_config')]
    public function configDashboard(
        int $id,
        HomeConfigService $homeConfigService
    ): Response {
        $configData = $homeConfigService->houseServiceConfigDashboard($id);

        /*
         * $configData variable has data to render the view, or if sth goes wrong
         * it returns an empty array. This if statement bellow checks thant and redirects.
         */
        if (empty($configData)) {
            $this->addFlash('error', 'You are not the owner of this house');
            return $this->redirectToRoute('app_home_config');
        }

        return $this->render('home_config/config.html.twig', $configData);
    }
}
