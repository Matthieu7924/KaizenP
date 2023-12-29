<?php

namespace App\Service;

use SebastianBergmann\Template\Template;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;


class SendMailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer) {
        $this->mailer = $mailer;
    }

    //méthode pour l'envoi du mail
    public function send(
        string $from,
        string $to,
        string $subject,
        string $template,
        array $context
    ):void{
        //création du mail
        $mail = (new TemplatedEmail())
        ->from($from)
        ->to($to)
        ->subject($subject)
        ->htmlTemplate("emails/$template.html.twig")
        ->context($context);

        //envoi du mail
        $this->mailer->send($mail);
    }
}