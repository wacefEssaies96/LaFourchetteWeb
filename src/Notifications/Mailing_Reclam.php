<?php

namespace App\Notifications;



use Swift_Message;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Mailing_Reclam
{
    /**
     * Propriété contenant le module d'envoi de mails
     * 
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * Propriété contenant l'environnement Twig
     *
     * @var Environment
     */
    private $renderer;

    public function __construct(\Swift_Mailer $mailer, Environment $renderer)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    /**
     * Méthode de notification (envoi de mail)
     *
     * @return void
     */
    public function notify(Reclam $rec)
    {
        
        $message = (new Swift_Message('La Fourchette - Réclamation'))

            ->setFrom('travel.me.pridev@gmail.com')
           
            ->setTo($rec->getEmail())
            
            ->setBody(
                $this->renderer->render(
                    'emails/Reclam_traitée.html.twig'
                ),
                'text/html'
            );

       
        $this->mailer->send($message);
        
    }
}