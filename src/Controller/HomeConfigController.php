<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeConfigController extends AbstractController
{
    #[Route('/home-config', name: 'app_home_config')]
    public function index(): Response
    {
        return $this->render('home_config/index.html.twig', []);
    }
}
