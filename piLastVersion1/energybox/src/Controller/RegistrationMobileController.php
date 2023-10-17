<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\CssSelector\XPath\TranslatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class RegistrationMobileController extends AbstractController
{

    #[Route('/signup', name: 'app_register_mobile')]
    public function signUp(Request $request,  UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager)
    {
        $email = $request->query->get("email");
        $username = $request->query->get("username");
        $numTel = $request->query->get("numTel");
        $password = $request->query->get("password");
        $image = $request->query->get("image");

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new Response("Email Invalid");
        }
        $user = new User();
        $user->setEmail($email);
        $user->setUsername($username);

        $user->setNumTel($numTel);
        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $password
            )
        );
        $user->setImage($image);

        $roles[] = 'ROLE_ADMIN';
        $user->setRoles($roles);
        try {
            $entityManager->persist($user);
            $entityManager->flush();
            return new JsonResponse("Acount is Created", 200);
        } catch (\Exception $ex) {
            return new Response("Exception" . $ex->getMessage());
        }
    }
    #[Route('/signin', name: 'app_login_mobile')]
    public function signIn(NormalizerInterface $normalizer, Request $request,  UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager)
    {
        $email = $request->query->get("email");
        $password = $request->query->get("password");

        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
            if ($user && password_verify($password, $user->getPassword())) {
                $formatted = $normalizer->normalize($user);
                return new JsonResponse($formatted);
            } else {
                return new JsonResponse("invalid",Response::HTTP_UNAUTHORIZED);
            }
        
    }
}
