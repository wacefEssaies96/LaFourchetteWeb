<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailController extends AbstractController
{
    /**
     * @param MailerInterface $mailer
     * @return Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @Route("/mail", name="app_mail")
     */
    public function index(MailerInterface $mailer): Response
    {

        $res = $this->getDoctrine()->getRepository(Reservation::class)->findAll();
        $email = (new Email())
            ->from('lafourchette.esprit@gmail.com')
            ->to('iheb.benhelel@esprit.tn')
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');
        $mailer->send($email);

        return $this->render('mail/index.html.twig', [
            'controller_name' => 'MailController',
        ]);
    }
}
