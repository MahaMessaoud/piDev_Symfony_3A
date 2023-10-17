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
class CompetitionController extends AbstractController
{

    #[Route('/newJson', name: 'app_competition_newJson')]
    public function newJson(Request $request, NormalizerInterface $normalizer): Response
    {
        $em = $this->getDoctrine()->getManager();
        $competition = new Competition();
        $dateString=$request->get('dateCompetition');
        $compDate=new DateTime($dateString);
        $competition->setDateCompetition($compDate);

        $competition->setNomCompetition($request->get('nomCompetition'));
        $competition->setFraisCompetition($request->get('fraisCompetition'));
        
        $competition->setNbrMaxInscrit($request->get('nbrMaxInscrit'));
        $competition->setNbrParticipant($request->get('*'));
        $competition->setEtatCompetition($request->get('etatCompetition'));

        $em->persist($competition);
        $em->flush();

        $jsonContent = $normalizer->normalize($competition,'json',['groups'=>'competitions']);
        return new Response(json_encode($jsonContent));
    }

    #[Route('/CompetitionsJson', name: 'app_competition_json')]
    public function getCompetitions(CompetitionRepository $repo, NormalizerInterface $normalizer)
    { 
            $competitions = $repo->findAll();
            $competitionNormalises = $normalizer->normalize($competitions,'json', ['groups' => "competitions"]);
            $json=json_encode($competitionNormalises);
            return new Response($json);
    }

    #[Route('/', name: 'app_competition_index', methods: ['GET'])]
    public function index(CompetitionRepository $competitionRepository): Response
    {
        return $this->render('competition/index.html.twig', [
            'competitions' => $competitionRepository->findAll(),
        ]);
    }

    

    ///////// Route pour front////////////////////////
    #[Route('/viewFront', name: 'app_competition_showFront', methods: ['GET'])]
    public function FrontView(CompetitionRepository $competitionRepository, EntityManagerInterface $entityManager, PaginatorInterface $Paginator, request $request): Response
    {
        $now= new \DateTime();
        $competitions=$competitionRepository->findAll();

         $competitions = $this->getDoctrine()->getRepository(Competition::class)->findBy(['etatCompetition' => "disponible"]);
        foreach($competitions as $competition)
        {
            if($competition->getDateCompetition()<$now)
            {
                $competition->setEtatCompetition("non disponible");
            }
            else
            {
                $competition->setEtatCompetition("disponible");
            }
        }

        $entityManager->persist($competition);
        $entityManager->flush();
        $competitions = $this->getDoctrine()->getRepository(Competition::class)->findBy(['etatCompetition' => "disponible"]);
        $competitions = $Paginator->paginate(
            $competitions,
            $request->query->getInt(key:'page',default: 1),
            limit: 3
        );

        return $this->render('competition/viewFront.html.twig', [
            'pagination'=>$competitions,
             $competitionRepository->findBy(['etatCompetition' => 'disponible',
        ]), 'competitions' =>$competitions,
    ]);

    }

    #[Route('/viewNonDispo', name: 'app_competition_nonDispo', methods: ['GET'])]
    public function showNonDispo(CompetitionRepository $competitionRepository, PaginatorInterface $Paginator, request $request): Response
    {
        $competitions = $this->getDoctrine()->getRepository(Competition::class)->findBy(['etatCompetition' => "non disponible"]);
        $competitions = $Paginator->paginate(
            $competitions,
            $request->query->getInt(key: 'page', default: 1),
           limit:3
        );
        return $this->render('competition/viewFrontNonDispo.html.twig', [
            'pagination'=>$competitions,
            $competitionRepository->findBy(['etatCompetition' => 'non disponible',
            ]),'competitions' =>$competitions,
          
        ]);

    }
    // End route Front

    #[Route('/viewFront/{id}', name: 'app_competition_Front', methods: ['GET'])]
    public function showFront(Competition $competition): Response
    {
        return $this->render('competition/showFront.html.twig', [
            'competition' => $competition,
        ]);
    }
    // End route Front

    #[Route('/new', name: 'app_competition_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CompetitionRepository $competitionRepository): Response
    {
        $competition = new Competition();
        $form = $this->createForm(CompetitionType::class, $competition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $competitionRepository->save($competition, true);

            return $this->redirectToRoute('app_competition_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('competition/new.html.twig', [
            'competition' => $competition,
            'form' => $form,
        ]);
    }

    
    

    #[Route('/{id}/reserver', name: 'app_competition_reserver', methods: ['POST','GET'])]
    public function reserver(request $request,CompetitionRepository $competitionRepository, EntityManagerInterface $entityManager ,Competition $competition, MailerInterface $mailer, $id ): Response
    { 
        $now= new \DateTime(); 
        if($competition->getNbrMaxInscrit()==0)
        return $this->render('competition/notAvailable.html.twig');

        if(($competition->getNbrMaxInscrit() > 0)&&($competition->getDateCompetition()>$now))
        {
          //  $user = $this->get('security.token_storage')->getToken()->getUser()->getId();

            $competition->setEtatCompetition("disponible");
            $competition->setNbrMaxInscrit($competition->getNbrMaxInscrit()-1);
            $competition->setNbrParticipant($competition->getNbrParticipant()+1);
            $competition->addInscrit(
                $this->getUser()
            
            );
            $entityManager->persist($competition);
            $entityManager->flush();
        }
        else      
            $competition->setEtatCompetition("non disponible");
        if (($competition->getEtatCompetition() == 'non disponible')||($competition->getDateCompetition()<$now)) 
        {
            return $this->render('competition/notAvailable.html.twig');
        }
        else
        {
        // ...
        $competitionUrl=sprintf('http://127.0.0.1:8000/ticket/competition/%d/ticket',$id);

        $meail = $this->get('security.token_storage')->getToken()->getUser()->getEmail();

        $email = (new Email())
        ->from('energyBox@gmail.com')
        ->to($meail)
        //->cc('cc@example.com')
        //->bcc('bcc@example.com')
        //->replyTo('fabien@example.com')
        //->priority(Email::PRIORITY_HIGH)
        ->subject('Votre compétition a été reservé avec succés')
        ->text("Hello!, ceci est un email de confirmation de votre réservation,
        Pour consulter votre ticket, vous pouvez la trouver via ce lien: 'http://127.0.0.1:8000/ticket/competition/$id/ticket',  Merci de votre confiance ! à bientôt !")

        ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);

        //return $this->render('app_email');

         return $this->redirectToRoute('app_competition_Front', ['id' => $competition->getId()]);
    
        
        }
    }

    

    #[Route('/{id}', name: 'app_competition_show', methods: ['GET'])]
    public function show(Competition $competition): Response
    {
        return $this->render('competition/show.html.twig', [
            'competition' => $competition,
        ]);
    }

   



    #[Route('/{id}/edit', name: 'app_competition_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Competition $competition, CompetitionRepository $competitionRepository): Response
    {
        $form = $this->createForm(CompetitionType::class, $competition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $competitionRepository->save($competition, true);

            return $this->redirectToRoute('app_competition_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('competition/edit.html.twig', [
            'competition' => $competition,
            'form' => $form,
        ]);
    }

   

    #[Route('/{id}', name: 'app_competition_delete', methods: ['POST'])]
    public function delete(Request $request, Competition $competition, CompetitionRepository $competitionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$competition->getId(), $request->request->get('_token'))) {
            $competitionRepository->remove($competition, true);
        }

        return $this->redirectToRoute('app_competition_index', [], Response::HTTP_SEE_OTHER);
    }
    
    
    

}





