<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;


#[Route('/reclamation')]
class ReclamationController extends AbstractController
{
    #[Route('/', name: 'app_reclamation_index', methods: ['GET'])]
    public function index(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }
    /* JSON Add */
    #[Route('/newJSON', name: 'addJSONReclamation')]
    public function newJSON(Request $request, NormalizerInterface $Normalizer, EntityManagerInterface $em)
    {
        $em =$this->getDoctrine()->getManager();
        $reclamation = new Reclamation();
        $dateString=$request->get('dateReclamation');
        $reclamationDate=new DateTime($dateString);
        $reclamation->setNomUserReclamation($request->get('nomUserReclamation'));
        $reclamation->setEmailUserReclamation($request->get('emailUserReclamation'));
        $reclamation->setObjetReclamation($request->get('objetReclamation'));
        $reclamation->setTexteReclamation($request->get('texteReclamation'));
        $reclamation->setDateReclamation($reclamationDate);

        $em->persist($reclamation);
        $em->flush();

        $jsonContent = $Normalizer->normalize($reclamation,'json',['groups'=>'reclamation']);

        return new Response("Reclamation ajouté avec succés".json_encode($jsonContent));
    }
    /* VIEW RECLAMATION JSON */
    #[Route('/viewJSON', name: 'viewJSONReclamation')]
    public function viewJSON(NormalizerInterface $Normalizer, ReclamationRepository $reclamationRepository)
    {
        $reclamation = $reclamationRepository->findAll();

        $reclamationNormalises = $Normalizer->normalize($reclamation,'json',['groups'=>'reclamation']);

        $json = json_encode($reclamationNormalises);
        
        return new Response($json);

    }

    #[Route('/merci', name: 'app_reclamation_merci', methods: ['GET'])]
    public function merci(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reclamation/merci.html.twig');
    }

    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReclamationRepository $reclamationRepository): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rr = $this->filterwords($reclamation->getTexteReclamation());

            $reclamation->setTexteReclamation($rr);

            $reclamation->setDateReclamation(new DateTime());
            $reclamationRepository->save($reclamation, true);

            return $this->redirectToRoute('app_reclamation_merci', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamationRepository->save($reclamation, true);

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $reclamationRepository->remove($reclamation, true);
        }

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }

    public function filterwords($text)
    {
        $filterWords = array('hate', 'bhim', 'msatek', 'fuck', 'slut', 'fucku');
        $filterCount = count($filterWords);
        $str = "";
        $data = preg_split('/\s+/',  $text);
        foreach($data as $s){
            $g = false;
            foreach ($filterWords as $lib) {
                if($s == $lib){
                    $t = "";
                    for($i =0; $i<strlen($s); $i++) $t .= "*";
                    $str .= $t . " ";
                    $g = true;
                    break;
                }
            }
            if(!$g)
            $str .= $s . " ";
        }
        return $str;
    }
}
