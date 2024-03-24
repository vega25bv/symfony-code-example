<?php
namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailService
{
    private $mailer;
    private $senderEmail;

    public function __construct(MailerInterface $mailer, string $senderEmail)
    {
        $this->mailer = $mailer;
        $this->senderEmail = $senderEmail;
    }

    public function sendEmail(string $recipient, string $subject, string $body): void
    {
        $email = (new Email())
            ->from($this->senderEmail)
            ->to($recipient)
            ->subject($subject)
            ->html($body);

        $this->mailer->send($email);
    }
}