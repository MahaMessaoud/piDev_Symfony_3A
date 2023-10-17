<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Form\MenuType;
use App\Repository\MenuRepository;
use App\Repository\PlatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OurMenuController extends AbstractController
{
    #[Route('/our/menu', name: 'app_our_menu')]
    public function index(): Response
    {
        return $this->render('our_menu/OurMenu.html.twig', [
            'controller_name' => 'OurMenuController',
        ]);
    }


}
