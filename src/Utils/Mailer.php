<?php

namespace App\Utils;

use App\Entity\TempUser;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface as Generator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Mailer {
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var Generator
     */
    private $urlGenerator;


    /*public function __construct(\Swift_Mailer $mailer, Generator $urlGenerator) {
        $this->mailer = $mailer;
        $this->urlGenerator = $urlGenerator;
    }*/

    public function sendConfirmationMail(TempUser $user)
    {
        $transport = (new \Swift_SmtpTransport('smtp.mailtrap.io', 25))
            ->setUsername('64346a2d2d4cdc')
            ->setPassword('507143d2e44515');
        $mailer = new \Swift_Mailer($transport);
        $link = "http://localhost:8000/user/new/" . $user->getToken();
        $message = new \Swift_Message('Bienvenue à notre annuaire');
        $message->setFrom(['bienetre@symfony.com' => 'annuaire.fr'])
            ->setTo($user->getEmail())
            ->setBody(
                '<p>Pour confirmer votre inscription, veuillez cliquer sur ce lien : </p><a href=".$link.">' . $link . '</a>
                       <p>Veuillez par la suite remplir votre profil</p> ', 'text/html'
            );

        $result = $mailer->send($message);
    }
}