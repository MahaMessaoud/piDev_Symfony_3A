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
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;


class ActiviteJSONController extends AbstractController
{
    /* JSON Edit */
    #[Route('/activite/editJSON/{id}', name: 'editJSONActivite')]
    public function editJSON($id,Request $request, Activite $activite, NormalizerInterface $Normalizer)
    {
        $em=$this->getDoctrine()->getManager();

        $activite=$em->getRepository(Activite::class)->find($id);
        $activite->setNomActivite($request->get('nomActivite'));
        $activite->setDureeActivite($request->get('dureeActivite'));
        $activite->setTenueActivite($request->get('tenueActivite'));
        $activite->setDifficulteActivite($request->get('difficulteActivite'));
        $activite->setImageActivite($request->get('imageActivite'));
        $activite->setDescriptionActivite($request->get('descriptionActivite'));

        $em->flush();

        $jsonContent = $Normalizer->normalize($activite,'json',['groups'=>'activites']);

        return new Response("Activite est mise à jour".json_encode($jsonContent));
    }
    
    /* JSON Delete */
    #[Route('/activite/deleteJSON/{id}', name: 'deleteJSONActivite')]
    public function deleteJSON(Request $request,Activite $activite, $id, NormalizerInterface $Normalizer, EntityManagerInterface $em)
    {
        $em=$this->getDoctrine()->getManager();
        $activite=$em->getRepository(Activite::class)->find($id);
        $em->remove($activite);
        $em->flush();
        $jsonContent=$Normalizer->normalize($activite,'json',['groups'=>'activites']);

        return new Response("Activite supprimée avec succés".json_encode($jsonContent));
    }

    /* JSON Show */
    #[Route('/activite/showJSON/{id}', name: 'showJSONActivite')]
    public function showJSON($id,ActiviteRepository $repo,NormalizerInterface $normalizer)
    {
        $activite=$repo->find($id);
        $activitesNormalises=$normalizer->normalize($activite,'json',['groups'=>"activites"]);
        return new Response(json_encode($activitesNormalises));
    }



    /* JSON View Back */
    #[Route('/activite/viewJSON', name: 'listJSONActivite')]
    public function getActivities(NormalizerInterface $normalizer, ActiviteRepository $activiteRepository)
    {
        $activites = $activiteRepository->findAll();

        $activiteNormalises = $normalizer->normalize($activites,'json',['groups'=>'activites']);

        $json = json_encode($activiteNormalises);
        
        return new Response($json);
    }
    

    /* JSON Add */
    #[Route('/activite/newJSON', name: 'addJSONActivite')]
    public function newJSON(Request $request, NormalizerInterface $Normalizer, EntityManagerInterface $em)
    {
        $em =$this->getDoctrine()->getManager();
        $activite = new Activite();
        $activite->setNomActivite($request->get('nomActivite'));
        $activite->setDureeActivite($request->get('dureeActivite'));
        $activite->setTenueActivite($request->get('tenueActivite'));
        $activite->setDifficulteActivite($request->get('difficulteActivite'));
        $activite->setImageActivite($request->get('imageActivite'));
        $activite->setDescriptionActivite($request->get('descriptionActivite'));

        $em->persist($activite);
        $em->flush();

        $jsonContent = $Normalizer->normalize($activite,'json',['groups'=>'activites']);

        return new Response(json_encode($jsonContent));
    }

    #[Route('/searchActivite', name: 'searchActivity')]
    public function searchActivity(ActiviteRepository $repo, Request $req, NormalizerInterface $normalizer):Response
    {
        $data=$req->get('searchData');
        $activite=$repo->searchActivity($data);

        $json = $normalizer->normalize($activite, 'json',['groups'=>'activites']);

        return new Response(json_encode($json));
    }

}
