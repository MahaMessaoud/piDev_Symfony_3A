<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AbonneFrontProfilController extends AbstractController
{
    #[Route('/abonne/front/profil', name: 'app_abonne_front_profil')]
    public function index(): Response
    {
        return $this->render('abonne_front_profil/index.html.twig', [
            'controller_name' => 'AbonneFrontProfilController',
        ]);
    }
}
