<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

use App\Entity\Reservation;

class ChaymaMailerController extends AbstractController
{
    #[Route('/email', name: 'app_mail_ch')]
    public function sendEmail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('EnergyBoxAdmin@gmail.com')
            ->to('User@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('EnergyBox_Restaurant!')
            ->text('!!')
            ->html('<p>Merci de nous faire confiance , votre plat sera prét dans une heure</p>');

        $mailer->send($email);

        // ...
        return $this->render('mailer/indexCH.html.twig');
    }
}
