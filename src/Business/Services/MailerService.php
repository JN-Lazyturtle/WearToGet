<?php

namespace TheFeed\Business\Services;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use TheFeed\Business\Exception\ServiceException;

class MailerService
{
    /**
     * @throws TransportExceptionInterface
     */
    public function sendEmail()
    {
        $to = 'ninihrkt@gmail.com';
        $subject = 'Sujet de l\'email';
        $message = 'Contenu de l\'email en HTML ou en texte brut';
        $headers = 'From: expéditeur@example.com' . "\r\n" .
            'Reply-To: expéditeur@example.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        mail($to, $subject, $message, $headers);
    }
}