<?php

namespace App\Controller;

use App\Entity\Sponsor;
use App\Repository\SponsorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
#[Route('/sponsor/mobile')]
class SponsorMobileController extends AbstractController
{
    #[Route('/sponsor/mobile', name: 'app_sponsor_mobile')]
    public function index(): Response
    {
        return $this->render('sponsor_mobile/index.html.twig', [
            'controller_name' => 'SponsorMobileController',
        ]);
    }

     /**getall json**/
     #[Route('/mobileAll_sponsor', name: 'app_sponsor_indexMobile', methods: ['GET'])]
     public function indexMobile(SponsorRepository $sponsorRepository,  NormalizerInterface $normalizer): JsonResponse
     {
         $sponsors = $sponsorRepository->findAll();
         $normalizedsponsor = $normalizer->normalize($sponsors, 'json', ['groups' => 'sponsors']);
         return new JsonResponse($normalizedsponsor);
     }
     
    /**ajout json**/
    #[Route('/newSmobile', name: 'app_sponsorMobile_new', methods: ['GET', 'POST'])]
    public function newMobile(Request $request, SponsorRepository $sponsorRepository,  NormalizerInterface $normalizer): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $sponsor = new Sponsor();
        $sponsor->setNomSponsor($request->$this->get('nomSponsor'));
        $sponsor->setDonnation($request->$this->get('donnation'));
        $sponsor->setDatedebut($request->$this->get('dateDebut'));
        $sponsor->setDateFin($request->$this->get('DateFin'));
        $em->persist($sponsor);
        $em->flush();
        $jsonContent = $normalizer->normalize($sponsor, 'json', ['groups' => 'sponsors']);
        return new Response(json_encode($jsonContent));

    }
    /**show byId */
    #[Route('/show/{id}', name: 'app_sponsor_showMobile', methods: ['GET'])]
    public function showMobile(Sponsor $sponsor, NormalizerInterface $normalizer): JsonResponse
    {
        $jsonContent = $normalizer->normalize($sponsor, 'json', ['groups' => 'sponsors']);
        return new JsonResponse($jsonContent);
    }
    /**edit */

    #[Route('/{id}/editSponsorMobile', name: 'sponsor_edit_mobile')]
    public function editAdminMobile(NormalizerInterface $normalizer, Request $request, Sponsor $sponsor, SponsorRepository $sponsorRepository): Response
    {
        $em=$this->getDoctrine()->getManager();
        $sponsor = $em->getRepository(Sponsor::class)->find($request->$this->get('id'));
        $sponsor->setNomSponsor($request->$this->get('nomSponsor'));
        $sponsor->setDonnation($request->$this->get('donnation'));
        $sponsor->setDatedebut($request->$this->get('dateDebut'));
        $sponsor->setDateFin($request->$this->get('DateFin'));
        $em->persist($sponsor);
        $em->flush();
        $jsonContent=$normalizer->normalize($sponsor,'json',['groups'=>'sponsors']);
        return new JsonResponse(['message'=>'sponsor updated successfully','data'=>$jsonContent]);
    }    

    /**delete */
    #[Route('/{id}/deletemobile_sponsor', name: 'app_deletemobile_sponsor')]
    public function deleteMobile(Request $request, Sponsor $sponsor, SponsorRepository $sponsorRepository): Response
   {
       $em=$this->getDoctrine()->getManager();
       $user = $em->getRepository(Sponsor::class)->find($request->$this->get('id'));
       $em->remove($sponsor);
       $em->flush();
       return new JsonResponse(['message'=>'sponsor deleted successfully']);
   }

}



