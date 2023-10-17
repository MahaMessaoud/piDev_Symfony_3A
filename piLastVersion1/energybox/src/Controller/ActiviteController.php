<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Form\ActiviteType;
use App\Repository\ActiviteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Filesystem;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;


#[Route('/activite/crud')]
class ActiviteController extends AbstractController
{
    #[Route('/', name: 'app_activite_crud_index', methods: ['GET'])]
    public function index(
        Request $request, 
        ActiviteRepository $activiteRepository,
        PaginatorInterface $paginator): Response
    {
        $data=$activiteRepository->findAll();

        $activites=$paginator->paginate(
            $data,
            $request->query->getInt('page',1),
            10
        );

        return $this->render('activite_crud/index.html.twig', [
            'activites' => $activites,
        ]);
    }

    #[Route('/viewActivite', name: 'app_activite_crud_index_front', methods: ['GET'])]
    public function viewActivite(
        ActiviteRepository $activiteRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response
    {
        $data=$activiteRepository->findAll();

        $activites=$paginator->paginate(
            $data,
            $request->query->getInt('page',1),
            6
        );

        return $this->render('activite_crud/view.html.twig', [
            'activites' => $activites,
        ]);
    }


    #[Route('/new', name: 'app_activite_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ActiviteRepository $activiteRepository): Response
    {
        $activite = new Activite();
        $filesystem = new Filesystem();
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $activiteRepository->save($activite, true);

            $uploadedFile = $form->get('imageActivite')->getData();
            $formData =  $uploadedFile->getPathname();
            $sourcePath = strval($formData);
            $destinationPath = 'uploads/photo'.$activite->getNomActivite().strval($activite->getId()).'.png';
            $activite->setImageActivite($destinationPath);
            $filesystem->copy($sourcePath, $destinationPath);
            $activiteRepository->save($activite, true);


            return $this->redirectToRoute('app_activite_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('activite_crud/new.html.twig', [
            'activite' => $activite,
            'form' => $form,
        ]);
    }

    

    #[Route('/{id}', name: 'app_activite_crud_show', methods: ['GET'])]
    public function show(Activite $activite): Response
    {
        return $this->render('activite_crud/show.html.twig', [
            'activite' => $activite,
        ]);
    }

    #[Route('/show/{id}', name: 'app_activite_crud_show_front', methods: ['GET'])]
    public function showActivite(Activite $activite): Response
    {
        return $this->render('activite_crud/showFront.html.twig', [
            'activite' => $activite,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_activite_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Activite $activite, ActiviteRepository $activiteRepository): Response
    {
        $filesystem = new Filesystem();
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $activiteRepository->save($activite, true);

            
            $uploadedFile = $form->get('imageActivite')->getData();
            $formData =  $uploadedFile->getPathname();
            $sourcePath = strval($formData);
            $destinationPath = 'uploads/photo'.$activite->getNomActivite().strval($activite->getId()).'.png';
            $activite->setImageActivite($destinationPath);
            $filesystem->copy($sourcePath, $destinationPath);
            $activiteRepository->save($activite, true);

            return $this->redirectToRoute('app_activite_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('activite_crud/edit.html.twig', [
            'activite' => $activite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_activite_crud_delete', methods: ['POST'])]
    public function delete(Request $request, Activite $activite, ActiviteRepository $activiteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$activite->getId(), $request->request->get('_token'))) {
            $activiteRepository->remove($activite, true);
        }

        return $this->redirectToRoute('app_activite_crud_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/exportPDF/{id}', name: 'exportPDF')]
    public function AfficheActivitePDF(ActiviteRepository $repo, $id)
    {
        $pdfoptions = new Options();
        $pdfoptions->set('defaultFont', 'Arial');
        $pdfoptions->setIsRemoteEnabled(true);
        
        $dompdf = new Dompdf($pdfoptions);

        $activite = $repo->find($id);

        // Check if the activity exists
        if (!$activite) {
            throw $this->createNotFoundException('Votre Activité n est pas disponible.');
        }

        $html = $this->renderView('activite_crud/pdfExport.html.twig', [
            'activite' => $activite
        ]);

        $html = '<div>' . $html . '</div>';

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A6', 'landscape');
        $dompdf->render();

        $pdfOutput = $dompdf->output();

        return new Response($pdfOutput, Response::HTTP_OK, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="activitePDF.pdf"'
        ]);
    }

}
