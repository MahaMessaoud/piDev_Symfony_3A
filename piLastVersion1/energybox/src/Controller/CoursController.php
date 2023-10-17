<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Activite;
use App\Form\CoursType;
use App\Repository\CoursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Knp\Component\Pager\PaginatorInterface;


#[Route('/cours/crud')]
class CoursController extends AbstractController
{
    // BackOffice
    #[Route('/', name: 'app_cours_crud_index', methods: ['GET'])]
    public function index(
        Request $request, 
        CoursRepository $coursRepository,
        PaginatorInterface $paginator): Response
    {
        $data=$coursRepository->findAll();

        $cours=$paginator->paginate(
            $data,
            $request->query->getInt('page',1),
            10
        );

        return $this->render('cours_crud/index.html.twig', [
            'cours' => $cours,
        ]);
    }

    #[Route('/viewCours', name: 'app_cours_crud_index_front', methods: ['GET'])]
    public function viewCours(CoursRepository $coursRepository): Response
    {
        return $this->render('cours_crud/view.html.twig', [
            'cours' => $coursRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_cours_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CoursRepository $coursRepository): Response
    {
        $cour = new Cours();
        $form = $this->createForm(CoursType::class, $cour);
        $form->handleRequest($request);
        $form->activities = $cour->getActivites();
        
        if ($form->isSubmitted() && $form->isValid()) {
            $coursRepository->save($cour, true);

            return $this->redirectToRoute('app_cours_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cours_crud/new.html.twig', [
            'cour' => $cour,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cours_crud_show', methods: ['GET'])]
    public function show(Cours $cour): Response
    {
        return $this->render('cours_crud/show.html.twig', [
            'cour' => $cour,
        ]);
    }

    #[Route('/showCours/{id}', name: 'app_cours_crud_show_front', methods: ['GET'])]
    public function showCours(Cours $cour): Response
    {
        return $this->render('cours_crud/showFront.html.twig', [
            'cour' => $cour,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cours_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cours $cour, CoursRepository $coursRepository): Response
    {
        $form = $this->createForm(CoursType::class, $cour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coursRepository->save($cour, true);

            return $this->redirectToRoute('app_cours_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cours_crud/edit.html.twig', [
            'cour' => $cour,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cours_crud_delete', methods: ['POST'])]
    public function delete(Request $request, Cours $cour, CoursRepository $coursRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cour->getId(), $request->request->get('_token'))) {
            $coursRepository->remove($cour, true);
        }

        return $this->redirectToRoute('app_cours_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}
