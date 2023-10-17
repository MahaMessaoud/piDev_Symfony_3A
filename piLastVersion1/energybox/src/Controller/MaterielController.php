<?php

namespace App\Controller;

use App\Entity\Materiel;
use App\Form\MaterielType;
use App\Repository\ChargeRepository;
use App\Repository\MaterielRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/materiel')]
class MaterielController extends AbstractController
{
    #[Route('/', name: 'app_materiel_index', methods: ['GET'])]
    public function index(MaterielRepository $materielRepository): Response
    {
        $materiel = $materielRepository->findAll();
        return $this->render('materiel/index.html.twig', [
            'materiels' => $materiel,
        ]);
    }
    //Research
    #[Route('/search', name: 'app_materiel_search', methods: ['GET', 'POST' ])]
    public function search(Request $request, MaterielRepository $materielRepository): Response
    {
        $query = $request->request->get('query');
        $materiels = $materielRepository->findBySearchQuery($query);
        return $this->render('materiel/index.html.twig', [
            'materiels' => $materiels,
            'query' => $query,
        ]);
    }
/* Tri */
    #[Route('/tri', name: 'app_materiel_tri', methods: ['GET'])]
    public function afficherSOrdredNom(MaterielRepository $repo): Response
    {
        $materiel = $repo->getStudentOrdredByName();
        return $this->render('materiel/index.html.twig', [
            'materiels' => $materiel,
        ]);
    }
//Json functions
    #[Route('/detailMJ/{id}', name: 'app_materiel_detailMJ', methods: ['GET'])]
    public function detailMJJ($id, MaterielRepository $materielRepository, NormalizerInterface $normalizer): Response
    {
        $materiel = $materielRepository->find($id);
        $matNormalize = $normalizer->normalize($materiel, 'json', ['groups' => "materiels"]);

        return new Response(json_encode($matNormalize));
    }
    #[Route('/AllMateriels', name: 'listM', methods: ['GET'])]
    public function listMateriels(MaterielRepository $materielRepository, SerializerInterface $serializerr)
    {
        $materiel = $materielRepository->findAll();
        $json = $serializerr->serialize($materiel, 'json', ['groups' => "materiels"]);


        return new Response($json);
    }


    #[Route('/newJson', name: 'app_materiel_new_Json', methods: ['GET', 'POST'])]
    public function Jnew(Request $request, EntityManagerInterface $em, NormalizerInterface $normalizer): Response
    {

        $materiel = new Materiel();
        $materiel->setQuantiteMateriel(0);
        $materiel->setNomMateriel($request->get('nom'));
        $materiel->setReferenceMateriel($request->get('ref'));
        $materiel->setDateMaintenanceMateriel((new \DateTime('now'))->modify('+ 12 months'));
        $em->persist($materiel);
        $em->flush();

        $JsonContent = $normalizer->normalize($materiel , 'json', ['groups' => "materiels"]);
        return new Response(json_encode($JsonContent));

    }
    #[Route('/editJson/{id}', name: 'app_materiel_edit_Json', methods: ['GET', 'POST'])]
    public function Jedit($id,Request $request, EntityManagerInterface $em, NormalizerInterface $normalizer): Response
    {

        $materiel = $em->getRepository(Materiel::class)->find($id);
        $materiel->setQuantiteMateriel($materiel->getQuantiteMateriel());
        $materiel->setId($materiel->getId());
        $materiel->setDateMaintenanceMateriel($materiel->getDateMaintenanceMateriel());
        $materiel->setNomMateriel($request->get('nom'));
        $materiel->setReferenceMateriel($request->get('ref'));
        $em->persist($materiel);
        $em->flush();

        $JsonContent = $normalizer->normalize($materiel , 'json', ['groups' => "materiels"]);
        return new Response("Materiel updated successfully".json_encode($JsonContent));

    }
    #[Route('/deleteJ/{id}', name: 'app_materiel_detailJ', methods: ['GET'])]
    public function deleteJ($id, EntityManagerInterface $em, NormalizerInterface $normalizer): Response
    {
        $materiel = $em->getRepository(Materiel::class)->find($id);
        $em->remove($materiel);
        $em->flush();
        $matNormalize = $normalizer->normalize($materiel, 'json', ['groups' => "materiels"]);

        return new Response("deleted successefully".json_encode($matNormalize));
    }
//End Json functions
    #[Route('/new', name: 'app_materiel_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MaterielRepository $materielRepository): Response
    {
        $materiel = new Materiel();
        $form = $this->createForm(MaterielType::class, $materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $materiel->setDateMaintenanceMateriel((new \DateTime('now'))->modify('+ 12 months'));

            $materiel->setQuantiteMateriel(0);
            $materielRepository->save($materiel, true);

            return $this->redirectToRoute('app_materiel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('materiel/new.html.twig', [
            'materiel' => $materiel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_materiel_show', methods: ['GET'])]
    public function show(Materiel $materiel): Response
    {
        return $this->render('materiel/show.html.twig', [
            'materiel' => $materiel,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_materiel_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Materiel $materiel, MaterielRepository $materielRepository): Response
    {
        $form = $this->createForm(MaterielType::class, $materiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $materielRepository->save($materiel, true);

            return $this->redirectToRoute('app_materiel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('materiel/edit.html.twig', [
            'materiel' => $materiel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_materiel_delete', methods: ['POST'])]
    public function delete(Request $request, Materiel $materiel, MaterielRepository $materielRepository, ChargeRepository $chargeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $materiel->getId(), $request->request->get('_token'))) {
            $chargess = $materiel->getCharges();
            foreach ($chargess as $charge) {
                $chargeRepository->remove($charge, true);
            }

            $materielRepository->remove($materiel, true);
        }

        return $this->redirectToRoute('app_materiel_index', [], Response::HTTP_SEE_OTHER);
    }










}
