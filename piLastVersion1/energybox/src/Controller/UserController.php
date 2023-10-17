<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUsersType;
use App\Form\EdituserTypeformType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\ResetPasswordFormType;
use App\Form\ResetPasswordRequestFormType;

#[Route('/user')]
class UserController extends AbstractController
{

    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        /**$currentUser = $this->getUser();
         *
         * // create a query builder for the User entity
         * $queryBuilder = $entityManager->createQueryBuilder();
         * $queryBuilder->select('u')
         * ->from(User::class, 'u')
         * ->where('u.id <> :currentUserId')
         * ->setParameter('currentUserId', $currentUser->getUserIdentifier());
         *
         * // execute the query and get the results
         * $users = $queryBuilder->getQuery()->getResult();
         *
         * // render the view with the list of users
         * return $this->render('user/index.html.twig', [
         * 'users' => $users,
         * ]);**/


        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll()]);

    }


    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userRepository->save($user, true);
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }
  


    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(EdituserTypeformType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);
            return $this->redirectToRoute('app_abonne_front_profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/editpopup.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/editAdmin', name: 'app_admin_edit', methods: ['GET', 'POST'])]
    public function editA(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(EdituserTypeformType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);
            return $this->redirectToRoute('app_admin_back', [], Response::HTTP_SEE_OTHER);

        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/editusers', name: 'app_users_edit', methods: ['GET', 'POST'])]
    public function editusers(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(EditUsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);

        }

        return $this->renderForm('user/editallusers.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    //block user by id
    #[Route('/block/{id}', name: 'app_user_block', methods: ['GET', 'POST'])]
    public function block(Request $request, User $user, UserRepository $userRepository): Response
    {
        $user->setIsBlocked(true);
        $userRepository->save($user, true);
       // $user->setEtat("l utlisateur est bloque");
        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    //unblock user by id
    #[Route('/unblock/{id}', name: 'app_user_unblock', methods: ['GET', 'POST'])]
    public function unblock(Request $request, User $user, UserRepository $userRepository): Response
    {
        $user->setIsBlocked(false);
        $userRepository->save($user, true);
       // $user->setEtat("l utilisateur est debloque");
        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

















   
}
