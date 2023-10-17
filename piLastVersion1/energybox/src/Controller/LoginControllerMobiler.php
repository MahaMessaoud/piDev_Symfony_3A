<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('/loginMobile')]
class LoginControllerMobileController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login_mobile')]
    public function login(AuthenticationUtils $authenticationUtils, NormalizerInterface $normalizer): JsonResponse
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
    
        $data = [
            'last_username' => $lastUsername,
            'error' => $error,
        ];
    
        $jsonData = json_encode($normalizer->normalize($data));
    
        return new JsonResponse($jsonData, 200, [], true);
    }

    #[Route(path: '/logout', name: 'app_logout_mobile')]
    public function logout(): JsonResponse
    {
    return new JsonResponse(['message' => 'Vous êtes maintenant déconnecté.'], 200);
    }
}
