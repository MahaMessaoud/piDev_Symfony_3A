<?php

namespace App\Controller;

use App\Entity\CategoryReclamation;
use App\Form\CategoryReclamationType;
use App\Repository\CategoryReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category/reclamation')]
class CategoryReclamationController extends AbstractController
{
    #[Route('/', name: 'app_category_reclamation_index', methods: ['GET'])]
    public function index(CategoryReclamationRepository $categoryReclamationRepository): Response
    {
        return $this->render('category_reclamation/index.html.twig', [
            'category_reclamations' => $categoryReclamationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_category_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CategoryReclamationRepository $categoryReclamationRepository): Response
    {
        $categoryReclamation = new CategoryReclamation();
        $form = $this->createForm(CategoryReclamationType::class, $categoryReclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryReclamationRepository->save($categoryReclamation, true);

            return $this->redirectToRoute('app_category_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('category_reclamation/new.html.twig', [
            'category_reclamation' => $categoryReclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_category_reclamation_show', methods: ['GET'])]
    public function show(CategoryReclamation $categoryReclamation): Response
    {
        return $this->render('category_reclamation/show.html.twig', [
            'category_reclamation' => $categoryReclamation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_category_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategoryReclamation $categoryReclamation, CategoryReclamationRepository $categoryReclamationRepository): Response
    {
        $form = $this->createForm(CategoryReclamationType::class, $categoryReclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryReclamationRepository->save($categoryReclamation, true);

            return $this->redirectToRoute('app_category_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('category_reclamation/edit.html.twig', [
            'category_reclamation' => $categoryReclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_category_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, CategoryReclamation $categoryReclamation, CategoryReclamationRepository $categoryReclamationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categoryReclamation->getId(), $request->request->get('_token'))) {
            $categoryReclamationRepository->remove($categoryReclamation, true);
        }

        return $this->redirectToRoute('app_category_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
}
