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
class TicketJSONController extends AbstractController
{
    
    
    //////////////////////jsonSHOW/////////////////////////
    #[Route('/{id}/json', name: 'app_ticket_showJson')]
    public function showJson($id,Ticket $ticket,  NormalizerInterface $normalizer,ticketRepository $tr ): Response
    {
        $ticket = $tr->find($id);
        $jsonContent = $normalizer->normalize($ticket, 'json', ['groups'=>"ticket"]);
        return new Response(json_encode($jsonContent));
    }
    //////////////////////end jsonSHOW/////////////////////////

    

    //////////////////////jsonEDIT/////////////////////////
    #[Route('/editJson/{id}', name: 'app_ticket_editJson')]     
    public function editJson(Request $request, Ticket $ticket, TicketRepository $ticketRepository, NormalizerInterface $normalizer): Response
    {
        $em = $this->getDoctrine()->getManager();
        $ticket = $ticketRepository->find($request->get('id'));
        $descriptionTicket = $request->get('descriptionTicket');
        if ($descriptionTicket !== null) {
            $ticket->setDescriptionTicket($descriptionTicket); 
        }
        else 
        throw new \Exception('la description ne peut pas être vide.');
        $em->persist($ticket);
        $em->flush();
        $ticketNormalizes = $normalizer->normalize($ticket, 'json', ['groups' => "ticket"]);
        return new Response(json_encode($ticketNormalizes));
    }

    

    //////////////////////jsonDELETE/////////////////////////
    #[Route('/{id}/deleteJson', name: 'app_ticket_deleteJson')]
    public function deleteJson($id,Request $request, Ticket $ticket, TicketRepository $ticketRepository, NormalizerInterface $normalizer): Response
    {
        $em = $this->getDoctrine()->getManager();
        $ticket = $em->getRepository(ticket::class)->find($id);
        $em->remove($ticket);
        $em->flush();
        $ticketNormalizes = $normalizer->normalize($ticket, 'json', ['groups' => "ticket"]);
        return new Response("Ticket supprimée avec succée" .  json_encode($ticketNormalizes));
    }

    
 
    

    

    

    
    //////////////////////end jsonDELETE/////////////////////////

   

    

 }


     