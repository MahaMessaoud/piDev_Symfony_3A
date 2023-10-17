<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminBackController extends AbstractController
{
    #[Route('/admin/back', name: 'app_admin_back')]
    public function index(): Response
    {
        return $this->render('admin_back/index.html.twig', [
            'controller_name' => 'AdminBackController',
        ]);
    }
}
