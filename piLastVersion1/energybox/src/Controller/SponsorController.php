<?php

namespace App\Controller;

use App\Entity\Sponsor;
use App\Form\SponsorType;
use App\Form\SponsorTypeEdit;
use App\Repository\SponsorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/sponsor')]
class SponsorController extends AbstractController
{
    #[Route('/', name: 'app_sponsor_index', methods: ['GET'])]
    public function index(SponsorRepository $sponsorRepository,PaginatorInterface $paginator,Request $request): Response
    {
        $sponsors = $sponsorRepository->findAll();
        $sponsorsP = $paginator->paginate(
            $sponsors,
            $request->query->getInt('page', 1),
            3
        );
        return $this->render('sponsor/index.html.twig', [
            'sponsors' => $sponsorsP,
        ]);
    }

    /**getall json**/

    #[Route('/mobileAll', name: 'app_sponsor_indexMobile', methods: ['GET'])]
    public function indexMobile(SponsorRepository $sponsorRepository,  SerializerInterface $serializer): JsonResponse
    {
        $sponsors = $sponsorRepository->findAll();
        $serializedsponsor = $serializer->serialize($sponsors, 'json', ['groups' => 'sponsors']);
        return new JsonResponse($serializedsponsor);
    }

    #[Route('/new', name: 'app_sponsor_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SponsorRepository $sponsorRepository): Response
    {
        $sponsor = new Sponsor();
        $filesystem = new Filesystem();
        $form = $this->createForm(SponsorType::class, $sponsor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form->get('image')->getData();
            $formData = $uploadedFile->getPathname();
            $sourcePath = strval($formData);
            $sponsorRepository->save($sponsor, true);
            $destinationPath = 'uploads/image' . strval($sponsor->getId()) . '.png';
            $sponsor->setImage($destinationPath);
            $filesystem->copy($sourcePath, $destinationPath);
            $sponsorRepository->save($sponsor, true);

            return $this->redirectToRoute('app_sponsor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sponsor/new.html.twig', [
            'sponsor' => $sponsor,
            'form' => $form,
        ]);
    }

    /**ajout json**/
    #[Route('/newSmobile', name: 'app_sponsorMobile_new', methods: ['GET', 'POST'])]
    public function newMobile(Request $request, SponsorRepository $sponsorRepository, SerializerInterface $serializer): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $sponsor = new Sponsor();
        $sponsor->setNomSponsor($request->get('nomSponsor'));
        $sponsor->setDonnation($request->get('donnation'));
        $sponsor->setDatedebut($request->get('dateDebut'));
        $sponsor->setDateFin($request->get('DateFin'));

        $em->persist($sponsor);
        $em->flush();
        $jsonContent = $serializer->serialize($sponsor, 'json', ['groups' => 'sponsors']);
        return new Response(json_encode($jsonContent));

    }


    #[Route('/{id}', name: 'app_sponsor_show', methods: ['GET'])]
    public function show(Sponsor $sponsor): Response
    {
        return $this->render('sponsor/show.html.twig', [
            'sponsor' => $sponsor,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sponsor_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Sponsor $sponsor, SponsorRepository $sponsorRepository): Response
    {
        $form = $this->createForm(SponsorTypeEdit::class, $sponsor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sponsorRepository->save($sponsor, true);

            return $this->redirectToRoute('app_sponsor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sponsor/edit.html.twig', [
            'sponsor' => $sponsor,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sponsor_delete', methods: ['POST'])]
    public function delete(Request $request, Sponsor $sponsor, SponsorRepository $sponsorRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $sponsor->getId(), $request->request->get('_token'))) {
            $sponsorRepository->remove($sponsor, true);
        }

        return $this->redirectToRoute('app_sponsor_index', [], Response::HTTP_SEE_OTHER);
    }
}
