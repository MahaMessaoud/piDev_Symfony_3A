<?php

namespace App\Controller;

use App\Entity\Charge;
use App\Entity\Fournisseur;
use App\Form\ChargeType;
use App\Repository\ChargeRepository;
use App\Repository\FournisseurRepository;
use App\Repository\MaterielRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/charge')]
class ChargeController extends AbstractController
{
    #[Route('/', name: 'app_charge_index', methods: ['GET'])]
    public function index(ChargeRepository $chargeRepository): Response
    {
        return $this->render('charge/index.html.twig', [
            'charges' => $chargeRepository->findAll(),
        ]);
    }
    #[Route('/triC', name: 'app_charge_tri_d', methods: ['GET'])]
    public function DateArrivageDesc(ChargeRepository $repo): Response
    {
        $charge = $repo->getChargeByDateDesc();
        return $this->render('charge/index.html.twig', [
            'charges' => $charge,
        ]);
    }
    //Json Begin functions
    #[Route('/AllCharge', name: 'listC', methods: ['GET'])]
    public function listCharge(ChargeRepository $chargeRepository, SerializerInterface $serializerr)
    {
        $charge = $chargeRepository->findAll();
        $json = $serializerr->serialize($charge, 'json', ['groups' => "charges"]);
        return new Response($json);
    }
    #[Route('/detailCJ/{id}', name: 'app_charge_detailedj', methods: ['GET'])]
    public function detailedJ($id, ChargeRepository $chargeRepository, NormalizerInterface $normalizer): Response
    {
        $charge = $chargeRepository->find($id);
        $chargenormalize = $normalizer->normalize($charge, 'json', ['groups' => "charges"]);

        return new Response(json_encode($chargenormalize));
    }

    #[Route('/newCJ', name: 'app_charge_new_Json', methods: ['GET', 'POST'])]
    public function Jnew(Request $request, EntityManagerInterface $em,FournisseurRepository $fp,MaterielRepository $mp ,  NormalizerInterface $normalizer): Response
    {


        $materiel = new Charge();
        $materiel->setQuantiteCharge($request->get('qtt'));
        $materiel->setDateArrivageCharge(new \DateTime('now'));
        $materiel->setMateriel($mp->findOneBy(['id' => $request->get('mat')]));
        $materiel->setFournisseur($fp->findOneBy(['id' => $request->get('four')]));
       $qttajouté= $mp->findOneBy(['id' => $request->get('mat')])->setQuantiteMateriel($mp->findOneBy(['id' => $request->get('mat')])->getQuantiteMateriel()+$request->get('qtt'));
       $em->persist($qttajouté);
        $em->persist($materiel);
        $em->flush();


        $JsonContent = $normalizer->normalize($materiel , 'json', ['groups' => "charges"]);
        return new Response(json_encode($JsonContent));

    }
    #[Route('/editCJ/{id}', name: 'app_charge_edit_Json', methods: ['GET', 'POST'])]
    public function Jedit($id,Request $request, EntityManagerInterface $em,FournisseurRepository $fp,MaterielRepository $mp , NormalizerInterface $normalizer): Response
    {

        $materiel = $em->getRepository(Charge::class)->find($id);
        $materiel->getMateriel()->setQuantiteMateriel($materiel->getMateriel()->getQuantiteMateriel()-$materiel->getQuantiteCharge());
        $materiel->setId($materiel->getId());
        $materiel->setQuantiteCharge($request->get('qtt'));
        $materiel->setDateArrivageCharge($materiel->getDateArrivageCharge());
        $materiel->setMateriel($mp->findOneBy(['id' => $request->get('mat')]));
        $materiel->setFournisseur($fp->findOneBy(['id' => $request->get('four')]));
        $qttajouté= $mp->findOneBy(['id' => $request->get('mat')])->setQuantiteMateriel($mp->findOneBy(['id' => $request->get('mat')])->getQuantiteMateriel()+$request->get('qtt'));
        $em->persist($qttajouté);
        $em->persist($materiel);
        $em->flush();

        $JsonContent = $normalizer->normalize($materiel , 'json', ['groups' => "charges"]);
        return new Response("Charge updated successfully".json_encode($JsonContent));

    }
    #[Route('/deleteCJ/{id}', name: 'app_charge_detailJ', methods: ['GET'])]
    public function deleteJ($id, EntityManagerInterface $em, NormalizerInterface $normalizer): Response
    {
        $materiel = $em->getRepository(Charge::class)->find($id);
        $qttretiré = $materiel->getQuantiteCharge();
        $matcharge = $materiel->getMateriel();
        $qttmatchg=$materiel->getMateriel()->getQuantiteMateriel();
        $em->remove($materiel);
        $matcharge->setQuantiteMateriel($qttmatchg-$qttretiré);
        $em->flush();
        $matNormalize = $normalizer->normalize($materiel, 'json', ['groups' => "charges"]);

        return new Response("charge deleted successefully".json_encode($matNormalize));
    }
//End Json functions

    #[Route('/new', name: 'app_charge_new', methods: ['GET', 'POST'])]
    public function new(Request $request,EntityManagerInterface $em, ChargeRepository $chargeRepository,FournisseurRepository $repository): Response
    {
        $charge = new Charge();
        $form = $this->createForm(ChargeType::class, $charge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $datearr =$charge->getDateArrivageCharge();
            $now = new \DateTime('now');
            if($datearr < $now){
            $charge->setDateArrivageCharge(new \DateTime('now'));
            }
            $chargeRepository->save($charge, true);
            $materiel = $charge->getMateriel();
            $materiel->setQuantiteMateriel($materiel->getQuantiteMateriel()+$charge->getQuantiteCharge());
            $em->persist($materiel);
            $em->flush();
            $fourtel = $charge->getFournisseur()->getContactFournisseur();
            $repository->sms(strval($fourtel));
            $this->addFlash('Danger', 'SMS envoyée avec succées');
            return $this->redirectToRoute('app_charge_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('charge/new.html.twig', [
            'charge' => $charge,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_charge_show', methods: ['GET'])]
    public function show(Charge $charge): Response
    {
        return $this->render('charge/show.html.twig', [
            'charge' => $charge,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_charge_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Charge $charge, EntityManagerInterface $em,ChargeRepository $chargeRepository): Response
    {
        $qttmateriel=$charge->getMateriel()->getQuantiteMateriel();
        $qttcharge=$charge->getQuantiteCharge();
        $oldmat=$charge->getMateriel();
        $newqtt=$qttmateriel-$qttcharge;
       

        $form = $this->createForm(ChargeType::class, $charge);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $chargeRepository->save($charge, true);
            //get materiel de this charge
            $materiel = $charge->getMateriel();
            //get les charges   affecté a ce materiel
            $charges = $materiel->getCharges();
            //à stocker
            $qtt = 0;
            foreach($charges as $charge){
                // parcourir la liste des charges pour ce materiel en ajoutant leurs valeur de qtt et les stocker dans la variable qtt
                $qtt+= $charge->getQuantiteCharge();
            }
            $oldmat->setQuantiteMateriel($newqtt);
            //affecter la noyuvelle valeur du qtt
            $materiel->setQuantiteMateriel($qtt);
            $em->persist($oldmat);
            //stocké le infos dans le EntityManager
            $em->persist($materiel);
            //abaath le DB
            $em->flush();
            return $this->redirectToRoute('app_charge_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('charge/edit.html.twig', [
            'charge' => $charge,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_charge_delete', methods: ['POST'])]
    public function delete(Request $request, Charge $charge, EntityManagerInterface $em, ChargeRepository $chargeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$charge->getId(), $request->request->get('_token'))) {
            $materiel = $charge->getMateriel();
            $materiel->setQuantiteMateriel($materiel->getQuantiteMateriel()-$charge->getQuantiteCharge());
            $em->persist($materiel);
            $chargeRepository->remove($charge, true);

            $em->flush();
        }

        return $this->redirectToRoute('app_charge_index', [], Response::HTTP_SEE_OTHER);
    }
}
