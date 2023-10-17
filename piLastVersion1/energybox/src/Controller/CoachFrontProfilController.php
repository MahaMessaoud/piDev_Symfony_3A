<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoachFrontProfilController extends AbstractController
{
    #[Route('/coach/front/profil', name: 'app_coach_front_profil')]
    public function index(): Response
    {
        return $this->render('coach_front_profil/index.html.twig', [
            'controller_name' => 'CoachFrontProfilController',
        ]);
    }
}
