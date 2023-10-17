<?php

namespace App\Controller;

use App\Entity\Planning;
use App\Form\PlanningType;
use App\Repository\PlanningRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;


#[Route('/planning/crud')]
class PlanningController extends AbstractController
{
    #[Route('/calendrier', name: 'app_calendar')]
    public function CalendarView(PlanningRepository $repo): Response
    {
        $events = $repo->findAll();

        $rdvs = [];

        foreach($events as $event){
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getDatePlanning()->format('Y-m-d'),
                'title' => $event->getCours()->getNomCours(),
                'nomCoach' => $event->getCours()->getNomCoach(),
                'backgroundColor' => "#2C3E50",
                'borderColor' => "#2C3E50",
                'textColor' => "#ffffff",
            ];
        }

        $data = json_encode($rdvs);

        return $this->render('planning_crud/calendrier.html.twig', compact('data'));
    }
    
    #[Route('/new', name: 'app_planning_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PlanningRepository $planningRepository): Response
    {
        $planning = new Planning();
        $form = $this->createForm(PlanningType::class, $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $planningRepository->save($planning, true);

            return $this->redirectToRoute('app_planning_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('planning_crud/new.html.twig', [
            'planning' => $planning,
            'form' => $form,
        ]);
    }

    #[Route('/viewPlanning' , name:'app_planning_crud_index_front', methods: ['GET'])]
    public function viewPlanning(PlanningRepository $planningRepository): Response
    {
        return $this->render('planning_crud/view.html.twig', [
            'plannings' => $planningRepository->findAll(),
        ]);
    }

    
    #[Route('/{id}', name: 'app_planning_crud_show', methods: ['GET'])]
    public function show(Planning $planning): Response
    {
        return $this->render('planning_crud/show.html.twig', [
            'planning' => $planning,
        ]);
    }

    #[Route('/{id}', name: 'app_planning_crud_delete', methods: ['POST'])]
    public function delete(Request $request, Planning $planning, PlanningRepository $planningRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$planning->getId(), $request->request->get('_token'))) {
            $planningRepository->remove($planning, true);
        }

        return $this->redirectToRoute('app_planning_crud_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/edit', name: 'app_planning_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Planning $planning, PlanningRepository $planningRepository): Response
    {
        $form = $this->createForm(PlanningType::class, $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $planningRepository->save($planning, true);

            return $this->redirectToRoute('app_planning_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('planning_crud/edit.html.twig', [
            'planning' => $planning,
            'form' => $form,
        ]);
    }

    #[Route('/', name: 'app_planning_crud_index', methods: ['GET'])]
    public function index(
        PlanningRepository $planningRepository,
        Request $request, 
        PaginatorInterface $paginator): Response
    {
        $data=$planningRepository->findAll();

        $plannings=$paginator->paginate(
            $data,
            $request->query->getInt('page',1),
            10
        );

        return $this->render('planning_crud/index.html.twig', [
            'plannings' => $plannings,
        ]);
    }
    
}
