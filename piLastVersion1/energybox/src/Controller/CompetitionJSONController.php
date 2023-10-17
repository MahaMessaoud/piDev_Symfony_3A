<?php

namespace App\Controller;

use App\Entity\Competition;
use App\Entity\mailler;
use App\Form\CompetitionType;
use App\Repository\CompetitionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

use DateTime;



#[Route('/competition')]
class CompetitionJSONController extends AbstractController
{
    #[Route('/{id}/json', name: 'app_competition_showJson')]
    public function showJson($id, NormalizerInterface $normalizer, CompetitionRepository $competitionRepository)
    {
        $competition = $competitionRepository->find($id);
        $competitionNormalises = $normalizer->normalize($competition,'json', ['groups' => "competitions"]);
        return new Response(json_encode($competitionNormalises));
    }

    #[Route('/editJson/{id}', name: 'app_competition_editJson')]
    public function editJson(Request $request,$id, NormalizerInterface $normalizer,CompetitionRepository $competitionRepository )
    {
        $em= $this->getDoctrine()->getManager();
        $competition = $competitionRepository->find($request->get('id'));

        $nomCompetition = $request->get('nomCompetition');
        $competition->setNomCompetition($nomCompetition);

        $fraisCompetition =$request->get('fraisCompetition');
        $competition->setFraisCompetition($fraisCompetition);
        
        $etatCompetition= $request->get('etatCompetition');
            if ($etatCompetition !== null) {
                $competition->setEtatCompetition($etatCompetition);
            } else {
                    throw new \Exception('L\'état de la compétition ne peut pas être vide.');
            }
        $nbrMaxInscrit = $request->get('nbrMaxInscrit');
            if ($nbrMaxInscrit !== null) {
                $competition->setNbrMaxInscrit((int)$nbrMaxInscrit);
                }
        $em->flush();
        $jsonContent=$normalizer->normalize($competition,'json', ['groups' => "competitions"]);
        return new Response("competition modifiée avec succée" . json_encode($jsonContent));
    
        
    }

    
    #[Route('/deleteJson/{id}', name: 'app_competition_deleteJson')]
    public function deleteJson(Request $request,$id,NormalizerInterface $normalizer)
    {
        $em= $this->getDoctrine()->getManager();	
        $competition = $em->getRepository(Competition::class)->find($id);
        $em->remove($competition);
        $em->flush();
        $jsonContent=$normalizer->normalize($competition,'json', ['groups' => "competitions"]);
        return new Response("competition supprimée avec succée" . json_encode($jsonContent));
    }

    

    
    
    

    

    
    

}





