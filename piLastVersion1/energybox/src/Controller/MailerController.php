<?php

// src/Controller/MailerController.php
namespace App\Controller;
use App\Entity\Abonnement;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AbonnementRepository;
class MailerController extends AbstractController
{
    #[Route('/emailwiem', name: 'app_email_wi', methods: ['GET', 'POST'])]
    public function sendEmail(MailerInterface $mailer, AbonnementRepository $abonnementRepository): Response
    {
        $abonnementsInactifs = $abonnementRepository->findBy(['etatAbonnement' => 'non actif']);
    
        foreach ($abonnementsInactifs as $abonnement) {
            $user = $abonnement->getUser();
            $email = (new Email())
                ->from('EnergyBox@gmail.com')
                ->to($user->getEmail())
                ->subject('Votre abonnement a expiré!')
                ->html('<p>Veuillez renouveler votre abonnement dès que possible pour continuer à profiter de nos services.</p>');
    
            $mailer->send($email);
        }
    
        return $this->render('/mailer/index.html.twig');
    }
}