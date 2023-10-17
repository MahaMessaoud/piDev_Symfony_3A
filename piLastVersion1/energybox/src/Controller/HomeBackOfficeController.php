<?php

namespace App\Controller;

use App\Repository\SponsorRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeBackOfficeController extends AbstractController
{
    #[Route('/home/back/office', name: 'app_home_back_office')]
    public function index(UserRepository $userRepository, SponsorRepository $sponsorRepository): Response
    {
        $Abonne=$userRepository->countAbonne();
        $Coach= $userRepository->countCoach();
        $unblocked=$userRepository->countBlocked();
        $blocked=$userRepository->countUnBlocked();
        $Admin=$userRepository->countAdmin();
        $sponsor=$sponsorRepository->countSponsor();
        
        
        return $this->render('home_back_office/index.html.twig', [
            'controller_name' => 'HomeBackOfficeController',
            'Abonne'=>$Abonne,
            'Coach'=>$Coach,
            'blocked'=>$blocked,
            'unblocked'=>$unblocked,
            'Admin'=>$Admin,
        ]);
    }
}
