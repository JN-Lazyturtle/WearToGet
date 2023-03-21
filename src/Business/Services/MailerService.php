<?php

namespace TheFeed\Business\Services;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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

        ini_set('SMTP', 'smtp.gmail.com');
        ini_set('smtp_port', 587);
        ini_set('auth_username', 'votre_adresse_gmail');
        ini_set('auth_password', 'votre_mot_de_passe_gmail');
        ini_set('smtp_crypto', 'tls');

        try {
            $resp = mail($to, $subject, $message, $headers);
        }catch (\Throwable $e) {
            return new Response(["error" => $e->getMessage()], 400);
        }

    }
}