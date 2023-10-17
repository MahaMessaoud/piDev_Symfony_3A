<?php

namespace App\Controller;

use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use App\Entity\Ticket;
use App\Form\Ticket1Type;
use App\Form\TicketType;
use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile; 




#[Route('/ticket')]
class TicketController extends AbstractController
{

    #[Route('/', name: 'app_ticket_index', methods: ['GET'])]
    public function index(TicketRepository $ticketRepository): Response
    {
        return $this->render('ticket/index.html.twig', [
            'tickets' => $ticketRepository->findAll(),
        ]);
    }

    /////////////////////////jsonNEW/////////////////////////
    #[Route('/newJsonn', name: 'app_ticket_newJsonn')]
    public function newJson(Request $request, NormalizerInterface $normalizer): Response
    {
        $em = $this->getDoctrine()->getManager();
        $ticket = new Ticket();
        $ticket->setDescriptionTicket($request->get('descriptionTicket'));
       // $ticket->setCompetition.setId($request->get('competition.id'));
        $em->persist($ticket);
        $em->flush();
        $jsonContent = $normalizer->normalize($ticket,'json',['groups'=>'ticket']);
        return new Response(json_encode($jsonContent));
    }
    ////////////////////end jsonNEW/////////////////////////  

    ///////////////////affichage index json/////////////////////
    #[Route('/json', name: 'app_ticket_indexJson')]     
    public function indexJson(TicketRepository $ticketRepository, NormalizerInterface $normalizer): Response
    {
        $tickets = $ticketRepository->findAll();
        $jsonContent = $normalizer->normalize($tickets,'json',['groups'=>'ticket']);
        return new Response(json_encode($jsonContent));
    }
 
    #[Route('/new', name: 'app_ticket_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TicketRepository $ticketRepository): Response
    {
        $ticket = new Ticket();
        $form = $this->createForm(Ticket1Type::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ticketRepository->save($ticket, true);

            return $this->redirectToRoute('app_ticket_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ticket/new.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
        ]);
    }

    

    #[Route('/competition/{id}/ticket', name: 'app_ticket_Front', methods: ['GET'])]
    public function showFront(int $id, TicketRepository $ticketRepository): Response
    {
        $ticket = $ticketRepository->findOneBy(['competition' => $id]);

        if (!$ticket) {
            return $this->render('ticket/notAvailableTicket.html.twig', [
                'message' => 'Ticket non trouvé pour la compétition ID '.$id
            ]);
        }

        return $this->render('ticket/showTicketFront.html.twig', [
            'ticket' => $ticket,
        ]);
    }

    #[Route('/{id}', name: 'app_ticket_show', methods: ['GET'])]
    public function show(Ticket $ticket): Response
    {
        return $this->render('ticket/show.html.twig', [
            'ticket' => $ticket,
        ]);
    }

    

    #[Route('/{id}/edit', name: 'app_ticket_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ticket $ticket, TicketRepository $ticketRepository): Response
    {
        $form = $this->createForm(Ticket1Type::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ticketRepository->save($ticket, true);

            return $this->redirectToRoute('app_ticket_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ticket/edit.html.twig', [
            'ticket' => $ticket,
            'form' => $form,
        ]);
    }

    

    #[Route('/{id}', name: 'app_ticket_delete', methods: ['POST'])]
    public function delete(Request $request, Ticket $ticket, TicketRepository $ticketRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ticket->getId(), $request->request->get('_token'))) {
            $ticketRepository->remove($ticket, true);
        }

        return $this->redirectToRoute('app_ticket_index', [], Response::HTTP_SEE_OTHER);
    }

    

   

    /////////////////PDF/////////////competition/{id}/ticket/pdf
    #[Route('/competition/{id}/ticket/pdf', name: 'app_ticket_pdf', methods: ['GET'])]     
    public function AfficheTicketPDF(TicketRepository $ticketRepository, $id)
    {
    $pdfoptions = new Options();
    $pdfoptions->set('defaultFont', 'Arial');
    $pdfoptions->setIsRemoteEnabled(true);
    

    $dompdf = new Dompdf($pdfoptions);

    $ticket = $ticketRepository->find($id);

    // Check if the ticket exists
    if (!$ticket) {
        throw $this->createNotFoundException('The ticket does not exist');
    }

    $html = $this->renderView('ticket/myPDFticket.html.twig', [
        'ticket' => $ticket
    ]);

    $html = '<div>' . $html . '</div>';

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();

    $pdfOutput = $dompdf->output();

    return new Response($pdfOutput, Response::HTTP_OK, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="ticketPDF.pdf"'
    ]);
}

 }


     