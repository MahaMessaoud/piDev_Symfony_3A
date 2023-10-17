<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultpageController extends AbstractController
{
    #[Route('/', name: 'app_defaultpage')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_login'); 
    }
}
