<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Form\FournisseurType;
use App\Repository\ChargeRepository;
use App\Repository\FournisseurRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Twilio\Rest\Client;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\Controller\ManagerRegistry;

#[Route('/fournisseur')]
class FournisseurController extends AbstractController
{
    #[Route('/', name: 'app_fournisseur_index', methods: ['GET'])]
    public function index(FournisseurRepository $fournisseurRepository): Response
    {
        return $this->render('fournisseur/index.html.twig', [
            'fournisseurs' => $fournisseurRepository->findAll(),
        ]);
    }
    #[Route('/email/{id}', name:'app_fournisseur_sendemail', methods: ['GET','POST'])]
    public function sendEmail(MailerInterface $mailer,$id ,FournisseurRepository $fournisseurRepository): Response
    {
        $fournisseur = $fournisseurRepository->find($id);
        $destemail = $fournisseur->getEmailFournisseur();
        $email = (new Email())
            ->from('ahmed.benabid@esprit.tn')
            ->to($destemail)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Demande de charge')
            ->text('Demande de charge')
            ->html("<p>j'ai besoin d'une charge</p>");

        $mailer->send($email);

        // ...
        return $this->redirectToRoute('app_fournisseur_index', [], Response::HTTP_SEE_OTHER);

    }

    #[Route('/AllFournisseur', name: 'listF', methods: ['GET'])]




    public function listMateriels(FournisseurRepository $fournisseurRepository, SerializerInterface $serializerr)
    {
        $fournisseur = $fournisseurRepository->findAll();
        $json = $serializerr->serialize($fournisseur, 'json', ['groups' => "fournisseurs"]);


        return new Response($json);
    }
    #[Route('/detailFJ/{id}', name: 'app_fournisseur_detailedj', methods: ['GET'])]
    public function detailedJ($id, FournisseurRepository $fournisseurRepository, NormalizerInterface $normalizer): Response
    {
        $fournisseur = $fournisseurRepository->find($id);
        $fnormalize = $normalizer->normalize($fournisseur, 'json', ['groups' => "fournisseurs"]);

        return new Response(json_encode($fnormalize));
    }

    #[Route('/newFJ', name: 'app_fournisseur_new_Json', methods: ['GET', 'POST'])]
    public function Jnew(Request $request, EntityManagerInterface $em, NormalizerInterface $normalizer): Response
    {

        $materiel = new Fournisseur();
        $materiel->setContactFournisseur($request->get('tel'));
        $materiel->setNomFournisseur($request->get('nom'));
        $materiel->setEmailFournisseur($request->get('mail'));
        $materiel->setAdresseFournisseur($request->get('add'));
        $em->persist($materiel);
        $em->flush();

        $JsonContent = $normalizer->normalize($materiel , 'json', ['groups' => "fournisseurs"]);
        return new Response(json_encode($JsonContent));

    }
    #[Route('/editFJ/{id}', name: 'app_fournisseur_edit_Json', methods: ['GET', 'POST'])]
    public function Jedit($id,Request $request, EntityManagerInterface $em, NormalizerInterface $normalizer): Response
    {

        $materiel = $em->getRepository(Fournisseur::class)->find($id);
        $materiel->setId($materiel->getId());
        $materiel->setContactFournisseur($request->get('tel'));
        $materiel->setNomFournisseur($request->get('nom'));
        $materiel->setEmailFournisseur($request->get('mail'));
        $materiel->setAdresseFournisseur($request->get('add'));
        $em->persist($materiel);
        $em->flush();

        $JsonContent = $normalizer->normalize($materiel , 'json', ['groups' => "fournisseurs"]);
        return new Response("Fournisseur updated successfully".json_encode($JsonContent));

    }
    #[Route('/deleteFJ/{id}', name: 'app_fournisseur_detailJ', methods: ['GET'])]
    public function deleteJ($id, EntityManagerInterface $em, NormalizerInterface $normalizer): Response
    {
        $materiel = $em->getRepository(Fournisseur::class)->find($id);
        $em->remove($materiel);
        $em->flush();
        $matNormalize = $normalizer->normalize($materiel, 'json', ['groups' => "fournisseurs"]);

        return new Response("deleted successefully".json_encode($matNormalize));
    }
//End Json functions

    #[Route('/new', name: 'app_fournisseur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FournisseurRepository $fournisseurRepository): Response
    {
        $fournisseur = new Fournisseur();
        $form = $this->createForm(FournisseurType::class, $fournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fournisseurRepository->save($fournisseur, true);

            return $this->redirectToRoute('app_fournisseur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fournisseur/new.html.twig', [
            'fournisseur' => $fournisseur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fournisseur_show', methods: ['GET'])]
    public function show(Fournisseur $fournisseur): Response
    {
        return $this->render('fournisseur/show.html.twig', [
            'fournisseur' => $fournisseur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fournisseur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Fournisseur $fournisseur, FournisseurRepository $fournisseurRepository): Response
    {
        $form = $this->createForm(FournisseurType::class, $fournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fournisseurRepository->save($fournisseur, true);

            return $this->redirectToRoute('app_fournisseur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fournisseur/edit.html.twig', [
            'fournisseur' => $fournisseur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fournisseur_delete', methods: ['POST'])]
    public function delete(Request $request, Fournisseur $fournisseur, FournisseurRepository $fournisseurRepository,ChargeRepository $chargeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fournisseur->getId(), $request->request->get('_token'))) {
            $chargess = $fournisseur->getCharges();
            foreach ($chargess as $charge)
            {
                $chargeRepository->remove($charge, true);

            }

            $fournisseurRepository->remove($fournisseur, true);
        }

        return $this->redirectToRoute('app_fournisseur_index', [], Response::HTTP_SEE_OTHER);
    }
}
