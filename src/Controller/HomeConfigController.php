<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\HomeConfigService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;


class HomeConfigController extends AbstractController
{
    /*
     * index func renders a main view of home config section
     */
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
    #[Route('/house/add', name: 'app_add_house')]
    public function addHouse(
        Request $request,
        HomeConfigService $homeConfigService
    ): Response {
        try {
            $homeConfigService->houseServiceAdd($request);
        } catch (AuthenticationException $exception) {
            return $this->redirectToRoute('app_login');
        }

        $this->addFlash('success', 'Home added successfully');
        return $this->redirectToRoute('app_home_config');
    }

    #[Route('/house/delete', name: 'app_delete_house')]
    public function deleteHouse(
        Request $request,
        HomeConfigService $homeConfigService
    ): Response {
        $homeConfigService->homeServiceDelete($request);

        $this->addFlash('success', 'You have delete home successfully');
        return $this->redirectToRoute('app_home_config');
    }

    /*
     * This func displays the user config dashboard.
     */
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
