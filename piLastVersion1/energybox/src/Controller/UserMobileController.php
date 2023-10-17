<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EdituserTypeformType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserMobileController extends AbstractController
{
  /** get all user with json **/
  #[Route('/All', name: 'app_user_indexMobile', methods: ['GET'])]
  public function indexMobile(UserRepository $userRepository, EntityManagerInterface $entityManager, NormalizerInterface $normalizer): JsonResponse
  {
    $users = $userRepository->findAll();
    $serializedUsers = $normalizer->normalize($users, 'json', ['groups' => 'users']);
    return new JsonResponse($serializedUsers);
  }

  /** show by id json */
  #[Route('/{id}/mobile', name: 'app_user_showMobile', methods: ['GET'])]
  public function showMobile(User $user, NormalizerInterface $normalizer): JsonResponse
  {
    $serializedUser = $normalizer->normalize($user, 'json', ['groups' => 'users']);
    return new JsonResponse($serializedUser);
  }



  /** add user mobile json */
  #[Route('/addUserMobile', name: 'app_user_add_mobile', methods: ['POST'])]
  public function addUserMobile(Request $request, UserPasswordHasherInterface $passwordHasher, NormalizerInterface $normalizer): JsonResponse
  {
    $user = new User();
    $user->setUsername($request->get('username'));
    $user->setEmail($request->get('email'));
    $user->setPassword($passwordHasher->hashPassword($user, $request->get('password')));
    $user->setRoles(['ROLE_USER']);
    $em = $this->getDoctrine()->getManager();
    $em->persist($user);
    $em->flush();
    $jsonContent = $normalizer->normalize($user, 'json', ['groups' => 'users']);
    return new JsonResponse(['message' => 'User added successfully', 'data' => $jsonContent]);
  }


  /** edit user mobile json */

  #[Route('/{id}/editUserMobile', name: 'app_user_edit_mobile', methods: ['PUT'])]
  public function editUserMobile(NormalizerInterface $normalizer, Request $request, User $user, UserRepository $userRepository): Response
  {
    $em = $this->getDoctrine()->getManager();
    $user = $em->getRepository(User::class)->find($request->get('id'));
    $username = $user->getUsername();
    if ($username !== null) {
      $user->setUsername($request->get('username'));
    }
    $email = $user->getEmail();
    if ($email !== null) {
      $user->setEmail($request->get('email'));
    }

    $em->flush();
    $jsonContent = $normalizer->normalize($user, 'json', ['groups' => 'users']);
    return new JsonResponse(['message' => 'User updated successfully', 'data' => $jsonContent]);
  }

  // ...

  /** edit Admin mobile json */


  #[Route('/{id}/editAdminMobile', name: 'app_admin_edit_mobile')]
  public function editAdminMobile(NormalizerInterface $normalizer, Request $request, User $user, UserRepository $userRepository): Response
  {
    $em = $this->getDoctrine()->getManager();
    $user = $em->getRepository(User::class)->find($request->get('id'));
    $username = $user->getUsername();
    if ($username !== null) {
      $user->setUsername($request->get('username'));
    }
    $email = $user->getEmail();
    if ($email !== null) {
      $user->setEmail($request->get('email'));
    }

    $em->flush();
    $jsonContent = $normalizer->normalize($user, 'json', ['groups' => 'users']);
    return new JsonResponse(['message' => 'Admin updated successfully', 'data' => $jsonContent]);
  }


  /** delete mobile json */
  #[Route('/{id}/deletemobile', name: 'app_deletemobile')]
  public function deleteMobile(Request $request, User $user, UserRepository $userRepository): Response
  {
    $em = $this->getDoctrine()->getManager();
    $user = $em->getRepository(User::class)->find($request->get('id'));
    $em->remove($user);
    $em->flush();
    return new JsonResponse(['message' => 'User deleted successfully']);
  }
}
