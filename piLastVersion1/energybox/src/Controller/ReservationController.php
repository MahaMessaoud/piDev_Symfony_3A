<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Entity\Reservation;
use App\Entity\User;
use App\Form\ReservationType;
use App\Repository\MenuRepository;
use App\Repository\PlatRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Plat;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Twilio\Rest\Client;
use Symfony\Component\HttpFoundation\JsonResponse;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;


#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $reservations = $paginator->paginate(
            $reservationRepository->findAll(),
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    //AffichageJSON
    #[Route('/AllReservationjson', name: 'app_AllReservationjson')]
    public function AllReservationJson(ReservationRepository $repo,NormalizerInterface $normalizer)
    {
        $reservation=$repo->findAll();
        $reservationNormalises=$normalizer->normalize($reservation,'json',['groups' => "reservation"]);
        $json=json_encode($reservationNormalises);
        return new Response($json);
    }

    //EndAffichageJSon

//stat
    #[Route('/stats',name:'app_reclamation_stat')]
    public function stats(ReservationRepository $repository,NormalizerInterface $Normalizer)
    {
        $reservations=$repository->countByDate();
        $dates=[];
        $reservationsCount=[];
        foreach($reservations as $reservation){
            $dates[] = $reservation['dateReservation'];
            $reservationsCount[] = $reservation['count'];
        }
        dump($reservationsCount);
        return $this->render('reservation/stat.html.twig',[
            'dates' => json_encode($dates),
            'reservationsCount' => json_encode($reservationsCount),
        ]);
    }
//endStat

    #[Route('/new/{id}', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReservationRepository $reservationRepository, PlatRepository $repository, ManagerRegistry $doctrine, $id): Response
    {
        $plat = $repository->find($id);
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $doctrine->getManager();
            $reservation->setIdplat($plat);
            $reservation->addClient($this->getUser());
            $p = $plat->getNbp() - 1;
            $plat->setNbp($p);
            $em->persist($reservation);
            $em->flush();
            $reservationRepository->save($reservation, true);

        //    return $this->redirectToRoute('app_reservation_done', [], Response::HTTP_SEE_OTHER);
            return $this->render('reservation/reservationDone.html.twig');
        }

        return $this->renderForm('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
            'idplat' => $id, // pass id to template
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        $plat = new Plat();
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
            'tabplat' => $plat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationRepository->save($reservation, true);

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_reservation_delete')]
    public function delete(ManagerRegistry $mg, ReservationRepository $repository, $id)
    {
        $reservation = $repository->find($id);
        $em = $mg->getManager();
        $em->remove($reservation);
        $em->flush();
        return $this->redirectToRoute('app_reservation_index');
    }

//SMS
    #[Route('/sms/{id}', name: 'app_sms')]
    function envoyerSMS(ReservationRepository $repository, $id, Request $request, ManagerRegistry $doctrine)
    {
        $reservation = $repository->find($id);
        $em = $doctrine->getManager();
        $em->flush();
        $repository->sms();
        $em->flush();
        $this->addFlash('Danger', 'SMS envoyée avec succées');
        return $this->redirectToRoute('app_reservation_index');
    }

//WorkShopJson

    #[Route('/Reservationjson/{id}', name: 'app_Menujson')]
    public function ReservationidJson(ReservationRepository $repo,NormalizerInterface $normalizer,$id)
    {
        $reservation=$repo->find($id);
        $reservationNormalises=$normalizer->normalize($reservation,'json',['groups' => "reservation"]);
        $json=json_encode($reservationNormalises);
        return new Response($json);
    }
    #[Route('/AddReservationjson/new', name: 'app_AddReservationjson')]
    public function AddReservationJson(NormalizerInterface $normalizer,Request $request,ManagerRegistry $mg)
    {
        $em = $mg->getManager();
        $reservation=new Reservation();
        $date=$request->get('date');
        $reservation->setDate(new DateTime($date));
        $reservation->setUserId($request->get('user'));
       // $reservation->setIdplat($request->get('idplat'));
        $em->persist($reservation);
        $em->flush();

        $jsonContent=$normalizer->normalize($reservation,'json',['groups' => "reservation"]);
        return new Response("Reservation ajouté avec succés" .json_encode($jsonContent));
    }


    #[Route('/DeleteReservationjson/{id}', name: 'app_DeleteReservationjson')]
    public function DeleteReservationJson(NormalizerInterface $normalizer,Request $request,ManagerRegistry $mg,$id)
    {
        $em = $mg->getManager();
        $reservation=$em->getRepository(Reservation::class)->find($id);
        $em->remove($reservation);
        $em->flush();
        $jsonContent=$normalizer->normalize($reservation,'json',['groups' => "reservation"]);
        return new Response("Reservation supprimé avec succés" . json_encode($jsonContent));
    }







}

