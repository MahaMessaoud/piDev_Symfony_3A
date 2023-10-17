<?php

namespace App\Controller;

use App\Repository\SponsorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeFrontController extends AbstractController
{
    #[Route('/home/front', name: 'app_home_front')]
    public function index(SponsorRepository $sponsorRepository): Response
    {
        return $this->render('home_front/index.html.twig', [
            'controller_name' => 'HomeFrontController',
            'sponsors' => $sponsorRepository->findAll(),
        ]);
    }



}
